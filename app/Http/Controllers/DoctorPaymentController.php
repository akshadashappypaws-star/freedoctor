<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\DoctorPayment;
use App\Models\AdminSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\DoctorPaymentSuccess;

class DoctorPaymentController extends Controller
{
    /**
     * Show payment form for doctor registration
     */
    public function showPaymentForm($doctorId)
    {
        $doctor = Doctor::findOrFail($doctorId);
        
        // Get subscription fee from settings
        $subscriptionSetting = AdminSetting::where('setting_key', 'doctor_subscription_fee')->first();
        $subscriptionFee = $subscriptionSetting ? $subscriptionSetting->percentage_value : 500;
        
        // If subscription fee is 0, redirect to dashboard with free registration
        if ($subscriptionFee == 0) {
            return redirect()->route('doctor.dashboard')
                           ->with('success', 'Registration completed successfully! No payment required.');
        }
        
        // Check if payment already exists and is successful
        $existingPayment = DoctorPayment::where('doctor_id', $doctor->id)
                                      ->where('payment_status', 'success')
                                      ->first();
        
        if ($existingPayment) {
            return redirect()->route('doctor.payment.success', $existingPayment->id)
                           ->with('info', 'Payment already completed for this registration.');
        }

        return view('doctor.payment.form', compact('doctor', 'subscriptionFee'));
    }

    /**
     * Create Razorpay order and store payment record
     */
    public function createPayment(Request $request, $doctorId)
    {
        // Get subscription fee from settings
        $subscriptionSetting = AdminSetting::where('setting_key', 'doctor_subscription_fee')->first();
        $subscriptionFee = $subscriptionSetting ? $subscriptionSetting->percentage_value : 500;
        
        $validated = $request->validate([
            'amount' => 'required|numeric|min:' . $subscriptionFee
        ]);

        $doctor = Doctor::findOrFail($doctorId);
        
        // Validate that the amount matches the subscription fee
        if ($validated['amount'] != $subscriptionFee) {
            return back()->withErrors(['amount' => 'Invalid payment amount. Expected: ₹' . number_format($subscriptionFee, 2)]);
        }
        
        // Create payment record
        $payment = DoctorPayment::create([
            'doctor_id' => $doctor->id,
            'amount' => $validated['amount'],
            'order_id' => 'order_' . Str::random(10),
            'receipt_number' => 'receipt_' . time(),
            'payment_status' => 'pending',
            'description' => 'Doctor Registration Fee - ' . $doctor->doctor_name
        ]);

        // Here you would integrate with Razorpay API
        // For now, we'll simulate the order creation
        
        return view('doctor.payment.razorpay', compact('payment', 'doctor'));
    }

    /**
     * Handle successful payment
     */
    public function paymentSuccess(Request $request, $paymentId)
    {
        $payment = DoctorPayment::findOrFail($paymentId);
        
        // Validate Razorpay payment (you would verify with Razorpay API)
        $payment->update([
            'payment_id' => $request->razorpay_payment_id ?? 'test_payment_' . Str::random(10),
            'payment_status' => 'success',
            'payment_date' => now(),
            'payment_details' => $request->all()
        ]);

        // Mark doctor as payment completed (but still needs admin approval)
        $payment->doctor->update([
            'payment_completed' => true
        ]);

        // Send confirmation email
        try {
            Mail::to($payment->doctor->email)->send(new DoctorPaymentSuccess($payment));
        } catch (\Exception $e) {
            Log::error('Failed to send doctor payment success email: ' . $e->getMessage());
        }

        return view('doctor.payment.success', compact('payment'));
    }

    /**
     * Handle payment failure
     */
    public function paymentFailure(Request $request, $paymentId)
    {
        $payment = DoctorPayment::findOrFail($paymentId);
        
        $payment->update([
            'payment_status' => 'failed',
            'payment_details' => $request->all()
        ]);

        return view('doctor.payment.failure', compact('payment'));
    }

    /**
     * Admin view to manage doctor payments
     */
    public function adminIndex(Request $request)
    {
        $query = DoctorPayment::with('doctor');

        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('doctor', function($q) use ($search) {
                $q->where('doctor_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $payments = $query->latest()->paginate(15);

        return view('admin.pages.doctor-payments', compact('payments'));
    }

    /**
     * Admin approve doctor after payment verification
     */
    public function adminApprove(Request $request, $paymentId)
    {
        $payment = DoctorPayment::findOrFail($paymentId);
        
        if ($payment->payment_status === 'success') {
            $payment->doctor->update([
                'approved_by_admin' => true,
                'status' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Doctor approved successfully!'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Cannot approve doctor with unsuccessful payment'
        ], 400);
    }
}
