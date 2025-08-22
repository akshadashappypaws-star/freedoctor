<?php
// Add type column to patient_payments table
try {
    // Load Laravel environment
    require_once 'vendor/autoload.php';
    
    // Load environment variables
    $app = require_once 'bootstrap/app.php';
    $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    
    // Get database connection
    $db = \Illuminate\Support\Facades\DB::connection();
    
    // Check if table exists
    if (!$db->getSchemaBuilder()->hasTable('patient_payments')) {
        echo "Table 'patient_payments' doesn't exist. Creating it...\n";
        
        // Create the table
        $db->getSchemaBuilder()->create('patient_payments', function ($table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->enum('type', ['deposit', 'withdrawal'])->default('deposit');
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('razorpay_payment_id')->nullable();
            $table->string('razorpay_payout_id')->nullable();
            $table->json('bank_details')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
        
        echo "Table 'patient_payments' created successfully!\n";
    } else {
        // Check if type column exists
        if (!$db->getSchemaBuilder()->hasColumn('patient_payments', 'type')) {
            echo "Adding 'type' column to patient_payments table...\n";
            
            $db->getSchemaBuilder()->table('patient_payments', function ($table) {
                $table->enum('type', ['deposit', 'withdrawal'])->default('deposit')->after('user_id');
            });
            
            echo "Column 'type' added successfully!\n";
        } else {
            echo "Column 'type' already exists in patient_payments table.\n";
        }
        
        // Check and add other missing columns
        $columnsToCheck = [
            'status' => "enum('pending', 'processing', 'completed', 'failed', 'cancelled')",
            'razorpay_payout_id' => 'string',
            'bank_details' => 'json',
            'processed_at' => 'timestamp'
        ];
        
        foreach ($columnsToCheck as $column => $type) {
            if (!$db->getSchemaBuilder()->hasColumn('patient_payments', $column)) {
                echo "Adding '$column' column...\n";
                
                $db->getSchemaBuilder()->table('patient_payments', function ($table) use ($column, $type) {
                    switch ($column) {
                        case 'status':
                            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled'])->default('pending');
                            break;
                        case 'razorpay_payout_id':
                            $table->string('razorpay_payout_id')->nullable();
                            break;
                        case 'bank_details':
                            $table->json('bank_details')->nullable();
                            break;
                        case 'processed_at':
                            $table->timestamp('processed_at')->nullable();
                            break;
                    }
                });
                
                echo "Column '$column' added successfully!\n";
            } else {
                echo "Column '$column' already exists.\n";
            }
        }
    }
    
    // Show final table structure
    echo "\nFinal table structure:\n";
    $columns = $db->select("DESCRIBE patient_payments");
    foreach ($columns as $column) {
        echo "- {$column->Field}: {$column->Type}" . ($column->Null === 'YES' ? ' (nullable)' : '') . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
