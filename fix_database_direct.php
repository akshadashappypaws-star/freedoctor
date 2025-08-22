<?php
// Direct MySQL connection to fix patient_payments table

// Read .env file manually
$env = [];
if (file_exists('.env')) {
    $lines = file('.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && !str_starts_with($line, '#')) {
            list($key, $value) = explode('=', $line, 2);
            $env[trim($key)] = trim($value);
        }
    }
}

// Database configuration
$host = $env['DB_HOST'] ?? 'localhost';
$database = $env['DB_DATABASE'] ?? 'freedoctor';
$username = $env['DB_USERNAME'] ?? 'root';
$password = $env['DB_PASSWORD'] ?? '';

echo "Connecting to database: {$database} on {$host}\n";

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host={$host};dbname={$database};charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected successfully!\n\n";
    
    // Check if table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'patient_payments'");
    if ($stmt->rowCount() == 0) {
        echo "Table 'patient_payments' doesn't exist. Creating it...\n";
        
        $createTable = "
        CREATE TABLE patient_payments (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id BIGINT UNSIGNED NOT NULL,
            type ENUM('deposit', 'withdrawal') DEFAULT 'deposit',
            amount DECIMAL(10, 2) NOT NULL,
            status ENUM('pending', 'processing', 'completed', 'failed', 'cancelled') DEFAULT 'pending',
            payment_method VARCHAR(255) NULL,
            razorpay_payment_id VARCHAR(255) NULL,
            razorpay_payout_id VARCHAR(255) NULL,
            bank_details JSON NULL,
            notes TEXT NULL,
            processed_at TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id)
        )";
        
        $pdo->exec($createTable);
        echo "Table created successfully!\n";
    } else {
        echo "Table 'patient_payments' exists. Checking columns...\n";
        
        // Get current columns
        $stmt = $pdo->query("DESCRIBE patient_payments");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $existingColumns = [];
        foreach ($columns as $column) {
            $existingColumns[] = $column['Field'];
        }
        
        echo "Current columns: " . implode(', ', $existingColumns) . "\n\n";
        
        // Check and add missing columns
        if (!in_array('type', $existingColumns)) {
            echo "Adding 'type' column...\n";
            $pdo->exec("ALTER TABLE patient_payments ADD COLUMN type ENUM('deposit', 'withdrawal') DEFAULT 'deposit' AFTER user_id");
            echo "Column 'type' added!\n";
        } else {
            echo "Column 'type' already exists.\n";
        }
        
        if (!in_array('razorpay_payout_id', $existingColumns)) {
            echo "Adding 'razorpay_payout_id' column...\n";
            $pdo->exec("ALTER TABLE patient_payments ADD COLUMN razorpay_payout_id VARCHAR(255) NULL");
            echo "Column 'razorpay_payout_id' added!\n";
        } else {
            echo "Column 'razorpay_payout_id' already exists.\n";
        }
        
        if (!in_array('bank_details', $existingColumns)) {
            echo "Adding 'bank_details' column...\n";
            $pdo->exec("ALTER TABLE patient_payments ADD COLUMN bank_details JSON NULL");
            echo "Column 'bank_details' added!\n";
        } else {
            echo "Column 'bank_details' already exists.\n";
        }
        
        if (!in_array('processed_at', $existingColumns)) {
            echo "Adding 'processed_at' column...\n";
            $pdo->exec("ALTER TABLE patient_payments ADD COLUMN processed_at TIMESTAMP NULL");
            echo "Column 'processed_at' added!\n";
        } else {
            echo "Column 'processed_at' already exists.\n";
        }
        
        // Update status column to include all needed values
        echo "Updating 'status' column enum values...\n";
        $pdo->exec("ALTER TABLE patient_payments MODIFY COLUMN status ENUM('pending', 'processing', 'completed', 'failed', 'cancelled') DEFAULT 'pending'");
        echo "Status column updated!\n";
    }
    
    // Show final table structure
    echo "\nFinal table structure:\n";
    $stmt = $pdo->query("DESCRIBE patient_payments");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($columns as $column) {
        $nullable = $column['Null'] === 'YES' ? ' (nullable)' : '';
        $default = $column['Default'] ? " [default: {$column['Default']}]" : '';
        echo "- {$column['Field']}: {$column['Type']}{$nullable}{$default}\n";
    }
    
    echo "\nDatabase update completed successfully!\n";
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
