<?php

use App\Models\User;
use App\Models\PatientPayment;

$user = User::first();

if ($user) {
    PatientPayment::create([
        'user_id' => $user->id,
        'type' => 'withdrawal',
        'amount' => 1500.00,
        'payment_method' => 'razorpay',
        'status' => 'pending',
        'bank_details' => json_encode([
            'bank_name' => 'State Bank of India',
            'account_number' => '1234567890',
            'ifsc_code' => 'SBIN0001234',
            'account_holder_name' => $user->name
        ])
    ]);
    
    PatientPayment::create([
        'user_id' => $user->id,
        'type' => 'withdrawal',
        'amount' => 2000.00,
        'payment_method' => 'razorpay',
        'status' => 'completed',
        'bank_details' => json_encode([
            'bank_name' => 'HDFC Bank',
            'account_number' => '9876543210',
            'ifsc_code' => 'HDFC0001234',
            'account_holder_name' => $user->name
        ]),
        'razorpay_payout_id' => 'payout_test123',
        'processed_at' => now()
    ]);
    
    echo "Test patient payments created successfully!";
} else {
    echo "No users found!";
}
