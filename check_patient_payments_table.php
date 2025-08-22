<?php

// Simple script to check table structure without artisan
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=freedoctor', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if patient_payments table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'patient_payments'");
    $tableExists = $stmt->rowCount() > 0;
    
    if ($tableExists) {
        echo "✅ patient_payments table exists\n";
        
        // Show table structure
        $stmt = $pdo->query("DESCRIBE patient_payments");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "\n📋 Table structure:\n";
        foreach ($columns as $column) {
            echo "- {$column['Field']} ({$column['Type']})\n";
        }
        
        // Check if 'type' column exists
        $hasTypeColumn = false;
        foreach ($columns as $column) {
            if ($column['Field'] === 'type') {
                $hasTypeColumn = true;
                break;
            }
        }
        
        if ($hasTypeColumn) {
            echo "\n✅ 'type' column exists\n";
        } else {
            echo "\n❌ 'type' column is missing\n";
            echo "Need to run migration or add the column manually\n";
        }
        
    } else {
        echo "❌ patient_payments table does not exist\n";
        echo "Need to run migration to create the table\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Database connection error: " . $e->getMessage() . "\n";
    echo "Please check your database configuration\n";
}
