<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DoctorPayment;
use App\Models\Doctor;
use Carbon\Carbon;

class TestDoctorPaymentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = Doctor::all();
        
        if ($doctors->count() > 0) {
            // Add some successful payments with different amounts and dates
            $testPayments = [
                ['amount' => 500, 'days_ago' => 0],
                ['amount' => 600, 'days_ago' => 1],
                ['amount' => 750, 'days_ago' => 2],
                ['amount' => 550, 'days_ago' => 3],
                ['amount' => 800, 'days_ago' => 4],
            ];
            
            foreach ($testPayments as $index => $payment) {
                $doctor = $doctors->get($index % $doctors->count());
                
                DoctorPayment::create([
                    'doctor_id' => $doctor->id,
                    'amount' => $payment['amount'],
                    'payment_status' => 'success',
                    'order_id' => 'TEST_SUCCESS_' . time() . '_' . $index,
                    'payment_id' => 'pay_' . time() . '_' . $index,
                    'created_at' => Carbon::now()->subDays($payment['days_ago']),
                    'updated_at' => Carbon::now(),
                ]);
            }
            
            echo "Added " . count($testPayments) . " successful doctor payments\n";
        } else {
            echo "No doctors found in database\n";
        }
    }
}
