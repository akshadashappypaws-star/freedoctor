<?php
// Manual database migration for Google OAuth fields

try {
    // Database connection
    $host = '127.0.0.1';
    $port = '3306';
    $dbname = 'laravel';
    $username = 'root';
    $password = '';

    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected to database successfully.\n";

    // Check if google_id column exists
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'google_id'");
    if ($stmt->rowCount() == 0) {
        // Add google_id column
        $pdo->exec("ALTER TABLE users ADD COLUMN google_id VARCHAR(255) NULL UNIQUE AFTER email");
        echo "Added google_id column.\n";
    } else {
        echo "google_id column already exists.\n";
    }

    // Check if avatar column exists
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'avatar'");
    if ($stmt->rowCount() == 0) {
        // Add avatar column
        $pdo->exec("ALTER TABLE users ADD COLUMN avatar VARCHAR(255) NULL AFTER google_id");
        echo "Added avatar column.\n";
    } else {
        echo "avatar column already exists.\n";
    }

    // Check if email_verified_at exists and modify it to be nullable
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'email_verified_at'");
    if ($stmt->rowCount() > 0) {
        $pdo->exec("ALTER TABLE users MODIFY COLUMN email_verified_at TIMESTAMP NULL");
        echo "Modified email_verified_at column to be nullable.\n";
    }

    echo "Database migration completed successfully!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
