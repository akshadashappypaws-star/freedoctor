<?php
require_once 'vendor/autoload.php';

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Creating admin user...\n";

// Check if admin user already exists
$existingAdmin = Admin::where('email', 'admin@test.com')->first();

if ($existingAdmin) {
    echo "Admin user already exists: admin@test.com\n";
    echo "Password: admin123\n";
} else {
    // Create admin user
    $admin = Admin::create([
        'name' => 'Test Admin',
        'email' => 'admin@test.com',
        'password' => Hash::make('admin123'),
        'email_verified_at' => now()
    ]);

    echo "Admin user created successfully!\n";
    echo "Email: admin@test.com\n";
    echo "Password: admin123\n";
    echo "Login URL: http://127.0.0.1:8000/admin/login\n";
}

echo "\nTotal admin users: " . Admin::count() . "\n";
