<?php

try {
    $pdo = new PDO('mysql:host=localhost;dbname=freedoctor', 'root', '');
    
    echo "=== workflow_logs table structure ===\n";
    $stmt = $pdo->query('DESCRIBE workflow_logs');
    while($row = $stmt->fetch()) {
        echo $row[0] . ' - ' . $row[1] . "\n";
    }
    
    echo "\n=== workflow_errors table structure ===\n";
    $stmt = $pdo->query('DESCRIBE workflow_errors');
    while($row = $stmt->fetch()) {
        echo $row[0] . ' - ' . $row[1] . "\n";
    }
    
    echo "\n=== workflow_machine_configs table structure ===\n";
    $stmt = $pdo->query('DESCRIBE workflow_machine_configs');
    while($row = $stmt->fetch()) {
        echo $row[0] . ' - ' . $row[1] . "\n";
    }
    
} catch(Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
