<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin user
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('00000000'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
        
        $this->command->info('Default admin created successfully!');
        $this->command->info('Email: admin@gmail.com');
        $this->command->info('Password: 00000000');
    }
}
