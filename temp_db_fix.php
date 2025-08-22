<?php
// Simple database fix script - no Laravel dependencies

$host = '127.0.0.1';
$database = 'laravel';
$username = 'root';
$password = '';

echo "Connecting to database: {$database} on {$host}\n";

try {
    $pdo = new PDO("mysql:host={$host};dbname={$database};charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected successfully!\n\n";
    
    $stmt = $pdo->query("SHOW TABLES LIKE 'patient_payments'");
    if ($stmt->rowCount() == 0) {
        echo "Creating patient_payments table...\n";
        
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
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        
        $pdo->exec($createTable);
        echo "Table created!\n";
    } else {
        echo "Table exists. Checking columns...\n";
        
        $stmt = $pdo->query("DESCRIBE patient_payments");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $existingColumns = array();
        foreach ($columns as $column) {
            $existingColumns[] = $column['Field'];
        }
        
        echo "Current columns: " . implode(', ', $existingColumns) . "\n";
        
        if (!in_array('type', $existingColumns)) {
            echo "Adding type column...\n";
            $pdo->exec("ALTER TABLE patient_payments ADD COLUMN type ENUM('deposit', 'withdrawal') DEFAULT 'deposit' AFTER user_id");
            echo "Type column added!\n";
        }
        
        if (!in_array('razorpay_payout_id', $existingColumns)) {
            echo "Adding razorpay_payout_id column...\n";
            $pdo->exec("ALTER TABLE patient_payments ADD COLUMN razorpay_payout_id VARCHAR(255) NULL");
            echo "Razorpay payout ID column added!\n";
        }
        
        if (!in_array('bank_details', $existingColumns)) {
            echo "Adding bank_details column...\n";
            $pdo->exec("ALTER TABLE patient_payments ADD COLUMN bank_details JSON NULL");
            echo "Bank details column added!\n";
        }
        
        if (!in_array('processed_at', $existingColumns)) {
            echo "Adding processed_at column...\n";
            $pdo->exec("ALTER TABLE patient_payments ADD COLUMN processed_at TIMESTAMP NULL");
            echo "Processed at column added!\n";
        }
        
        echo "Updating status column...\n";
        $pdo->exec("ALTER TABLE patient_payments MODIFY COLUMN status ENUM('pending', 'processing', 'completed', 'failed', 'cancelled') DEFAULT 'pending'");
        echo "Status column updated!\n";
    }
    
    echo "\nFinal structure:\n";
    $stmt = $pdo->query("DESCRIBE patient_payments");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($columns as $column) {
        echo "- {$column['Field']}: {$column['Type']}\n";
    }
    
    echo "\nDatabase fixed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
