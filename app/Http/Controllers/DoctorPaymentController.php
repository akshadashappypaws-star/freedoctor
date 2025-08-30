<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\DoctorPayment;
use App\Models\AdminSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
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
        try {
            // Get subscription fee from settings
            $subscriptionSetting = AdminSetting::where('setting_key', 'doctor_subscription_fee')->first();
            $subscriptionFee = $subscriptionSetting ? $subscriptionSetting->percentage_value : 500;
            
            $validated = $request->validate([
                'amount' => 'required|numeric|min:' . $subscriptionFee,
                'terms' => 'required|accepted'
            ]);

            $doctor = Doctor::findOrFail($doctorId);
            
            // Validate that the amount matches the subscription fee
            if ($validated['amount'] != $subscriptionFee) {
                return back()->withErrors(['amount' => 'Invalid payment amount. Expected: ₹' . number_format($subscriptionFee, 2)])
                            ->withInput();
            }
            
            // Check if payment already exists
            $existingPayment = DoctorPayment::where('doctor_id', $doctor->id)
                                          ->where('payment_status', 'pending')
                                          ->first();
            
            if ($existingPayment) {
                // Use existing pending payment
                $payment = $existingPayment;
            } else {
                // Create Razorpay order
                $orderData = [
                    'amount' => $validated['amount'] * 100, // Razorpay expects amount in paisa
                    'currency' => 'INR',
                    'receipt' => 'receipt_' . time(),
                    'notes' => [
                        'doctor_id' => $doctor->id,
                        'doctor_name' => $doctor->doctor_name,
                        'purpose' => 'Doctor Registration Fee'
                    ]
                ];

                try {
                    // Create Razorpay order using API
                    $response = Http::withBasicAuth(env('RAZORPAY_KEY_ID'), env('RAZORPAY_SECRET'))
                        ->post('https://api.razorpay.com/v1/orders', $orderData);

                    if ($response->successful()) {
                        $razorpayOrder = $response->json();
                        $orderId = $razorpayOrder['id'];
                    } else {
                        Log::error('Razorpay order creation failed: ' . $response->body());
                        $orderId = 'order_' . Str::random(10); // Fallback
                    }
                } catch (\Exception $e) {
                    Log::error('Razorpay API error: ' . $e->getMessage());
                    $orderId = 'order_' . Str::random(10); // Fallback
                }
                
                // Create new payment record
                $payment = DoctorPayment::create([
                    'doctor_id' => $doctor->id,
                    'amount' => $validated['amount'],
                    'order_id' => $orderId,
                    'receipt_number' => $orderData['receipt'],
                    'payment_status' => 'pending',
                    'description' => 'Doctor Registration Fee - ' . $doctor->doctor_name
                ]);
            }

            // Here you would integrate with Razorpay API
            // For now, we'll simulate the order creation
            
            return view('doctor.payment.razorpay', compact('payment', 'doctor'));
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Payment creation error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Something went wrong. Please try again.'])->withInput();
        }
    }

    /**
     * Handle successful payment
     */
    public function paymentSuccess(Request $request, $paymentId)
    {
        try {
            $payment = DoctorPayment::findOrFail($paymentId);
            
            // Validate required Razorpay parameters
            $request->validate([
                'razorpay_payment_id' => 'required|string',
                'razorpay_order_id' => 'required|string',
                'razorpay_signature' => 'required|string'
            ]);
            
            // Verify Razorpay signature for security
            $signature = hash_hmac('sha256', 
                $request->razorpay_order_id . '|' . $request->razorpay_payment_id, 
                env('RAZORPAY_SECRET')
            );
            
            if ($signature !== $request->razorpay_signature) {
                Log::error('Razorpay signature verification failed', [
                    'expected' => $signature,
                    'received' => $request->razorpay_signature,
                    'payment_id' => $paymentId
                ]);
                
                return redirect()->route('doctor.payment.failure', $payment->id)
                               ->with('error', 'Payment verification failed. Please contact support.');
            }
            
            // Update payment record with Razorpay details
            $payment->update([
                'payment_id' => $request->razorpay_payment_id,
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
            
        } catch (\Exception $e) {
            Log::error('Payment success handling error: ' . $e->getMessage());
            return redirect()->route('doctor.payment.form', $payment->doctor->id ?? 1)
                           ->with('error', 'Something went wrong processing your payment. Please try again.');
        }
    }

    /**
     * Handle payment failure
     */
    public function paymentFailure(Request $request, $paymentId)
    {
        try {
            $payment = DoctorPayment::findOrFail($paymentId);
            
            // Update payment status to failed if not already failed
            if ($payment->payment_status !== 'failed') {
                $payment->update([
                    'payment_status' => 'failed',
                    'payment_details' => $request->all()
                ]);
            }

            return view('doctor.payment.failure', compact('payment'));
            
        } catch (\Exception $e) {
            Log::error('Payment failure handling error: ' . $e->getMessage());
            
            // Redirect to payment form with error message
            return redirect()->route('doctor.payment.form', $paymentId ?? 1)
                           ->with('error', 'There was an issue processing your payment failure. Please contact support.');
        }
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
