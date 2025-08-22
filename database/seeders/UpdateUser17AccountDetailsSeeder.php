<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateUser17AccountDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::find(17);
        
        if ($user) {
            $user->update([
                'bank_account_number' => '1234567890123456',
                'bank_ifsc_code' => 'HDFC0001234',
                'bank_name' => 'HDFC Bank',
                'account_holder_name' => $user->username ?? 'John Doe',
                'total_earnings' => 500.00,
                'withdrawn_amount' => 0.00,
                'available_balance' => 500.00
            ]);
            
            echo "User 17 account details updated successfully!\n";
        } else {
            echo "User 17 not found!\n";
        }
    }
}
