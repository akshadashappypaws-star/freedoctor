<?php
// Check existing databases
$host = '127.0.0.1';
$port = '3306';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to MySQL server.\n\n";
    
    // Show all databases
    $stmt = $pdo->query("SHOW DATABASES");
    $databases = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Available databases:\n";
    foreach ($databases as $db) {
        echo "- $db\n";
    }
    
    // Look for likely candidates
    $candidates = ['freedoctor', 'freedoctor_web', 'laravel', 'freedoctorweb'];
    
    echo "\nChecking for existing project databases:\n";
    foreach ($candidates as $candidate) {
        if (in_array($candidate, $databases)) {
            echo "✓ Found: $candidate\n";
            
            // Check if it has users table
            try {
                $pdo->exec("USE `$candidate`");
                $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
                if ($stmt->rowCount() > 0) {
                    echo "  ✓ Has users table\n";
                    
                    // Check users table structure
                    $stmt = $pdo->query("DESCRIBE users");
                    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
                    
                    $hasGoogleId = in_array('google_id', $columns);
                    $hasAvatar = in_array('avatar', $columns);
                    
                    echo "  - google_id: " . ($hasGoogleId ? "EXISTS" : "MISSING") . "\n";
                    echo "  - avatar: " . ($hasAvatar ? "EXISTS" : "MISSING") . "\n";
                    
                    if (!$hasGoogleId || !$hasAvatar) {
                        echo "  → This database needs Google OAuth fields!\n";
                    }
                } else {
                    echo "  ✗ No users table\n";
                }
            } catch (Exception $e) {
                echo "  ✗ Error accessing database: " . $e->getMessage() . "\n";
            }
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
