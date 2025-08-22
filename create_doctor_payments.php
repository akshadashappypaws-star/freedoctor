<?php
// Create doctor_payments table directly

$host = '127.0.0.1';
$database = 'laravel';
$username = 'root';
$password = '';

echo "Creating doctor_payments table...\n";

try {
    $pdo = new PDO("mysql:host={$host};dbname={$database};charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database successfully!\n";
    
    // Check if table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'doctor_payments'");
    if ($stmt->rowCount() == 0) {
        echo "Creating doctor_payments table...\n";
        
        $createTable = "
        CREATE TABLE doctor_payments (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            doctor_id BIGINT UNSIGNED NOT NULL,
            amount DECIMAL(10, 2) NOT NULL,
            payment_id VARCHAR(255) NULL,
            order_id VARCHAR(255) NULL,
            payment_status ENUM('pending', 'processing', 'completed', 'success', 'failed', 'cancelled') DEFAULT 'pending',
            payment_details JSON NULL,
            payment_date TIMESTAMP NULL,
            receipt_number VARCHAR(255) NULL,
            description TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_doctor_id (doctor_id),
            INDEX idx_payment_status (payment_status)
        )";
        
        $pdo->exec($createTable);
        echo "doctor_payments table created successfully!\n";
    } else {
        echo "doctor_payments table already exists.\n";
    }
    
    // Show table structure
    echo "\nTable structure:\n";
    $stmt = $pdo->query("DESCRIBE doctor_payments");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($columns as $column) {
        $nullable = $column['Null'] === 'YES' ? ' (nullable)' : '';
        $default = $column['Default'] ? " [default: {$column['Default']}]" : '';
        echo "- {$column['Field']}: {$column['Type']}{$nullable}{$default}\n";
    }
    
    // Insert a test record if table is empty
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM doctor_payments");
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    if ($count == 0) {
        echo "\nInserting test record...\n";
        $pdo->exec("
            INSERT INTO doctor_payments (doctor_id, amount, payment_status, description) 
            VALUES (1, 1000.00, 'pending', 'Test doctor payout')
        ");
        echo "Test record inserted!\n";
    }
    
    echo "\nSetup completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
