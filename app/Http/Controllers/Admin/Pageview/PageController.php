<?php

namespace App\Http\Controllers\Admin\Pageview;

use App\Http\Controllers\Controller;
use App\Models\Specialty;
use App\Models\Doctor;
use App\Models\AdminMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api as RazorpayApi;
use Razorpay\Api\Errors\BadRequestError;

class PageController extends Controller
{
    public function __construct()
    {
        // Share unread notification count with all admin views
        View::composer('*', function ($view) {
            if (request()->is('admin/*')) {
                $unreadNotifications = AdminMessage::where('read', false)->count();
                $view->with('unreadNotifications', $unreadNotifications);
            }
        });
    }

    public function specification() {
            $specialties = Specialty::all(); // or Specialty::all();
    return view('admin.pages.specification', compact('specialties'));
      
    }

  public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $specialty = Specialty::create(['name' => $request->name]);

        return response()->json(['success' => true, 'specialty' => $specialty]);
    }

    public function destroy($id)
    {
        $specialty = Specialty::findOrFail($id);
        $specialty->delete();

        return response()->json(['success' => true]);
    }

    public function doctors() {
        $specialties = Specialty::all();
        $doctors = Doctor::all();
      
        return view('admin.pages.doctors', compact('specialties','doctors'));
    }

    public function campaigns() {
        return view('admin.pages.campaigns');
    }

    public function campaignSponsors() {
        return view('admin.pages.campaign_sponsors');
    }

    public function patients() {
        return view('admin.pages.patients');
    }

    public function doctorVerification() {
        return view('admin.pages.doctor_verification');
    }

    public function whatsappBot() {
        // Get basic stats for WhatsApp bot
        $todayMessages = \App\Models\WhatsappConversation::whereDate('created_at', today())->count();
        $activeTemplates = \App\Models\WhatsappTemplate::where('is_active', true)->count();
        $responseStats = \App\Models\WhatsappConversation::selectRaw('
            COUNT(*) as total_responses,
            SUM(CASE WHEN response_type IS NOT NULL THEN 1 ELSE 0 END) as successful_responses
        ')->first();
        
        // Calculate success rate
        $successRate = $responseStats->total_responses > 0 
            ? round(($responseStats->successful_responses / $responseStats->total_responses) * 100, 1) . '%'
            : '0%';

        // Create stats object
        $stats = (object)[
            'messages_today' => $todayMessages,
            'active_templates' => $activeTemplates,
            'success_rate' => $successRate,
            'api_status' => config('services.whatsapp.api_key') !== null
        ];

        return view('admin.pages.whatsapp-bot', compact('stats'));
    }

    public function settings() {
        $adminSettings = \App\Models\AdminSetting::all();
        return view('admin.pages.settings', compact('adminSettings'));
    }

    public function leads() {
        $leads = \App\Models\OrganicLead::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.pages.leads', compact('leads'));
    }

    public function profit() {
        $earnings = \App\Models\AdminEarning::orderBy('created_at', 'desc')->get();
        
        $totalRegistrationEarnings = \App\Models\AdminEarning::where('earning_type', 'registration')->sum('commission_amount');
        $totalSponsorEarnings = \App\Models\AdminEarning::where('earning_type', 'sponsor')->sum('commission_amount');
        $totalDoctorRegistrationEarnings = \App\Models\AdminEarning::where('earning_type', 'doctor_registration')->sum('commission_amount');
        $totalEarnings = $totalRegistrationEarnings + $totalSponsorEarnings + $totalDoctorRegistrationEarnings;
        
        return view('admin.pages.profit', compact('earnings', 'totalRegistrationEarnings', 'totalSponsorEarnings', 'totalDoctorRegistrationEarnings', 'totalEarnings'));
    }

    public function notifications() {
        $notifications = \App\Models\AdminMessage::orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('admin.pages.notifications', compact('notifications'));
    }

    public function subscriptions() {
        return view('admin.pages.subscriptions');
    }

    public function profile() {
        return view('admin.pages.profile');
    }

    public function patientPayouts(Request $request) {
        // Get all patient payments 
        $query = \App\Models\PatientPayment::with('user');
            
        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $patientPayments = $query->orderBy('created_at', 'desc')->paginate(20);
            
        return view('admin.pages.patient-payouts', compact('patientPayments'));
    }

    public function doctorPayouts(Request $request) {
        $query = \App\Models\DoctorPayment::with('doctor')
            ->where('payment_status', 'pending')
            ->whereNotNull('payment_details');
            
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
        
        $doctorPayments = $query->orderBy('created_at', 'desc')->paginate(20);
            
        return view('admin.pages.doctor-payouts', compact('doctorPayments'));
    }

    public function processPatientPayout($paymentId) {
        try {
            $payment = \App\Models\PatientPayment::with(['patientRegistration.user'])->findOrFail($paymentId);
            
            if ($payment->status !== 'pending') {
                return response()->json(['success' => false, 'message' => 'Payment is not in pending status']);
            }
            
            // Check if patient registration exists
            if (!$payment->patientRegistration || !$payment->patientRegistration->user) {
                return response()->json(['success' => false, 'message' => 'Patient registration or user data not found']);
            }
            
            // Update status to processing
            $payment->update([
                'status' => 'processing',
                'processed_at' => now()
            ]);
            
            // Integrate with Razorpay Payout API for real fund transfer
            $bankDetails = is_array($payment->bank_details) ? $payment->bank_details : json_decode($payment->bank_details, true);
            
            if (!$bankDetails || !isset($bankDetails['account_number'], $bankDetails['ifsc_code'], $bankDetails['account_holder_name'])) {
                throw new \Exception('Invalid bank details provided');
            }
            
            // Convert negative amount to positive for payout
            $payoutAmount = abs($payment->amount);
            
            // Initialize Razorpay API
            $api = new RazorpayApi(config('services.razorpay.key'), config('services.razorpay.secret'));
            
            try {
                // Use patient registration data
                $patientReg = $payment->patientRegistration;
                $user = $patientReg->user;
                
                // Create contact for the patient
                $contact = $api->contact->create([
                    'name' => $patientReg->patient_name ?: $user->name,
                    'email' => $patientReg->email ?: $user->email,
                    'contact' => $patientReg->phone_number ?: $user->phone ?: '9999999999',
                    'type' => 'customer'
                ]);
                
                // Create fund account with bank details
                $fundAccount = $api->fundAccount->create([
                    'contact_id' => $contact->id,
                    'account_type' => 'bank_account',
                    'bank_account' => [
                        'name' => $bankDetails['account_holder_name'],
                        'account_number' => $bankDetails['account_number'],
                        'ifsc' => $bankDetails['ifsc_code']
                    ]
                ]);
                
                // Create payout with positive amount
                $payout = $api->payout->create([
                    'account_number' => config('services.razorpay.account_number'), // Your Razorpay account number
                    'fund_account_id' => $fundAccount->id,
                    'amount' => $payoutAmount * 100, // Convert to paise and ensure positive
                    'currency' => 'INR',
                    'mode' => 'IMPS', // or 'NEFT', 'RTGS', 'UPI'
                    'purpose' => 'refund',
                    'queue_if_low_balance' => true,
                    'reference_id' => 'patient_refund_' . $payment->id,
                    'narration' => 'Patient refund payout for registration ID: ' . $patientReg->id
                ]);
                
                // Update payment with payout details
                $payment->update([
                    'status' => $payout->status === 'processed' ? 'completed' : 'processing',
                    'razorpay_payout_id' => $payout->id,
                    'processed_at' => now(),
                    'failure_reason' => $payout->status === 'failed' ? $payout->failure_reason : null
                ]);
                
                return response()->json([
                    'success' => true, 
                    'message' => 'Patient refund processed successfully',
                    'payout_id' => $payout->id,
                    'status' => $payout->status,
                    'amount' => $payoutAmount
                ]);
                
            } catch (BadRequestError $e) {
                // Handle Razorpay API errors
                $payment->update([
                    'status' => 'failed',
                    'failure_reason' => $e->getMessage()
                ]);
                
                return response()->json([
                    'success' => false, 
                    'message' => 'Refund failed: ' . $e->getMessage()
                ]);
            }
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    public function checkPatientPayoutStatus($paymentId) {
        try {
            $payment = \App\Models\PatientPayment::findOrFail($paymentId);
            
            // Check with Razorpay API for actual status
            if ($payment->razorpay_payout_id) {
                $api = new RazorpayApi(config('services.razorpay.key'), config('services.razorpay.secret'));
                
                try {
                    $payout = $api->payout->fetch($payment->razorpay_payout_id);
                    
                    // Update local status based on Razorpay status
                    $newStatus = $payout->status;
                    if ($payout->status === 'processed') {
                        $newStatus = 'completed';
                    } elseif (in_array($payout->status, ['failed', 'cancelled', 'rejected'])) {
                        $newStatus = 'failed';
                    } elseif ($payout->status === 'queued') {
                        $newStatus = 'processing';
                    }
                    
                    $payment->update([
                        'status' => $newStatus,
                        'failure_reason' => $payout->status === 'failed' ? $payout->failure_reason : null
                    ]);
                    
                    return response()->json([
                        'success' => true, 
                        'status' => $newStatus,
                        'razorpay_status' => $payout->status,
                        'utr' => $payout->utr ?? null
                    ]);
                    
                } catch (BadRequestError $e) {
                    return response()->json([
                        'success' => false, 
                        'message' => 'Error fetching status: ' . $e->getMessage()
                    ]);
                }
            }
            
            // If no Razorpay payout ID, return current status
            return response()->json(['success' => true, 'status' => $payment->status]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    public function patientPayoutDetails($paymentId) {
        $payment = \App\Models\PatientPayment::with(['patientRegistration.user', 'patientRegistration.campaign'])
                                            ->findOrFail($paymentId);
        return view('admin.pages.patient-payout-details', compact('payment'));
    }
    
    public function processDoctorPayout($paymentId) {
        try {
            // Validate payment ID
            if (!$paymentId || !is_numeric($paymentId)) {
                return response()->json(['success' => false, 'message' => 'Invalid payment ID']);
            }

            // Check if the doctor_payments table exists
            if (!Schema::hasTable('doctor_payments')) {
                return response()->json(['success' => false, 'message' => 'Doctor payments table does not exist. Please run migrations first.']);
            }

            $payment = \App\Models\DoctorPayment::find($paymentId);
            
            if (!$payment) {
                return response()->json(['success' => false, 'message' => 'Payment not found']);
            }
            
            if ($payment->payment_status !== 'pending') {
                return response()->json(['success' => false, 'message' => 'Payment is not in pending status']);
            }
            
            // Update status to processing
            $payment->update([
                'payment_status' => 'processing'
            ]);
            
            // Integrate with Razorpay Payout API for real fund transfer
            $paymentDetails = is_array($payment->payment_details) ? $payment->payment_details : json_decode($payment->payment_details, true);
            $bankDetails = $paymentDetails ?? [];
            
            if (!isset($bankDetails['account_number'], $bankDetails['ifsc_code'], $bankDetails['account_holder_name'])) {
                throw new \Exception('Invalid bank details provided');
            }
            
            // Initialize Razorpay API
            $api = new RazorpayApi(config('services.razorpay.key'), config('services.razorpay.secret'));
            
            try {
                // Create contact for the doctor
                $contact = $api->contact->create([
                    'name' => $payment->doctor->doctor_name,
                    'email' => $payment->doctor->email,
                    'contact' => $payment->doctor->phone ?? '9999999999',
                    'type' => 'vendor'
                ]);
                
                // Create fund account with bank details
                $fundAccount = $api->fundAccount->create([
                    'contact_id' => $contact->id,
                    'account_type' => 'bank_account',
                    'bank_account' => [
                        'name' => $bankDetails['account_holder_name'],
                        'account_number' => $bankDetails['account_number'],
                        'ifsc' => $bankDetails['ifsc_code']
                    ]
                ]);
                
                // Create payout
                $payout = $api->payout->create([
                    'account_number' => config('services.razorpay.account_number'), // Your Razorpay account number
                    'fund_account_id' => $fundAccount->id,
                    'amount' => $payment->amount * 100, // Amount in paise
                    'currency' => 'INR',
                    'mode' => 'IMPS', // or 'NEFT', 'RTGS'
                    'purpose' => 'payout',
                    'queue_if_low_balance' => true,
                    'reference_id' => 'doctor_payout_' . $payment->id,
                    'narration' => 'Doctor earnings withdrawal payout'
                ]);
                
                // Update payment with payout details
                $payment->update([
                    'payment_status' => $payout->status === 'processed' ? 'completed' : 'processing',
                    'payment_id' => $payout->id,
                    'payment_details' => json_encode(array_merge($paymentDetails, [
                        'razorpay_payout_id' => $payout->id,
                        'payout_status' => $payout->status,
                        'processed_at' => now()->toDateTimeString()
                    ]))
                ]);
                
                return response()->json([
                    'success' => true, 
                    'message' => 'Doctor payout processed successfully',
                    'payout_id' => $payout->id,
                    'status' => $payout->status
                ]);
                
            } catch (BadRequestError $e) {
                // Handle Razorpay API errors
                $payment->update([
                    'payment_status' => 'failed',
                    'payment_details' => json_encode(array_merge($paymentDetails, [
                        'failure_reason' => $e->getMessage(),
                        'failed_at' => now()->toDateTimeString()
                    ]))
                ]);
                
                return response()->json([
                    'success' => false, 
                    'message' => 'Doctor payout failed: ' . $e->getMessage()
                ]);
            }
            
        } catch (\Exception $e) {
            \Log::error('Doctor payout processing error: ' . $e->getMessage(), [
                'payment_id' => $paymentId,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false, 
                'message' => 'An error occurred while processing the payout: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function checkDoctorPayoutStatus($paymentId) {
        try {
            $payment = \App\Models\DoctorPayment::findOrFail($paymentId);
            
            // Check with Razorpay API for actual status
            if ($payment->payment_id && strpos($payment->payment_id, 'pout_') === 0) {
                $api = new RazorpayApi(config('services.razorpay.key'), config('services.razorpay.secret'));
                
                try {
                    $payout = $api->payout->fetch($payment->payment_id);
                    
                    // Update local status based on Razorpay status
                    $newStatus = $payout->status;
                    if ($payout->status === 'processed') {
                        $newStatus = 'completed';
                    } elseif (in_array($payout->status, ['failed', 'cancelled', 'rejected'])) {
                        $newStatus = 'failed';
                    } elseif ($payout->status === 'queued') {
                        $newStatus = 'processing';
                    }
                    
                    // Update payment details with latest info
                    $paymentDetails = is_array($payment->payment_details) ? $payment->payment_details : json_decode($payment->payment_details, true);
                    $paymentDetails = $paymentDetails ?? [];
                    $paymentDetails['payout_status'] = $payout->status;
                    $paymentDetails['utr'] = $payout->utr ?? null;
                    $paymentDetails['last_status_check'] = now()->toDateTimeString();
                    
                    if ($payout->status === 'failed') {
                        $paymentDetails['failure_reason'] = $payout->failure_reason;
                    }
                    
                    $payment->update([
                        'payment_status' => $newStatus,
                        'payment_details' => json_encode($paymentDetails)
                    ]);
                    
                    return response()->json([
                        'success' => true, 
                        'status' => $newStatus,
                        'razorpay_status' => $payout->status,
                        'utr' => $payout->utr ?? null
                    ]);
                    
                } catch (BadRequestError $e) {
                    return response()->json([
                        'success' => false, 
                        'message' => 'Error fetching status: ' . $e->getMessage()
                    ]);
                }
            }
            
            // If no Razorpay payout ID, return current status
            return response()->json(['success' => true, 'status' => $payment->payment_status]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    public function doctorPayoutDetails($paymentId) {
        $payment = \App\Models\DoctorPayment::with('doctor')->findOrFail($paymentId);
        return view('admin.pages.doctor-payout-details', compact('payment'));
    }
    
    public function doctorPayoutReceipt($paymentId) {
        $payment = \App\Models\DoctorPayment::with('doctor')->findOrFail($paymentId);
        return view('admin.pages.doctor-payout-receipt', compact('payment'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'setting_key' => 'required|string',
            'percentage_value' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $setting = \App\Models\AdminSetting::firstOrCreate(
            ['setting_key' => $request->setting_key],
            [
                'setting_name' => ucfirst(str_replace('_', ' ', $request->setting_key)),
                'percentage_value' => $request->percentage_value,
                'description' => $request->description,
                'is_active' => $request->has('is_active') ? 1 : 0
            ]
        );

        $setting->update([
            'percentage_value' => $request->percentage_value,
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? 1 : 0
        ]);

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }

    public function exportProfits()
    {
        $earnings = \App\Models\AdminEarning::orderBy('created_at', 'desc')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="admin_earnings_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($earnings) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'Date', 'Earning Type', 'Original Amount', 'Commission %', 'Commission Amount', 'Net Amount', 'Reference Type', 'Reference ID', 'Description'
            ]);

            foreach ($earnings as $earning) {
                fputcsv($file, [
                    $earning->created_at->format('Y-m-d H:i:s'),
                    ucfirst(str_replace('_', ' ', $earning->earning_type)),
                    $earning->original_amount,
                    $earning->percentage_rate . '%',
                    $earning->commission_amount,
                    $earning->net_amount_to_receiver,
                    $earning->reference_type,
                    $earning->reference_id,
                    $earning->description ?? 'No description'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
