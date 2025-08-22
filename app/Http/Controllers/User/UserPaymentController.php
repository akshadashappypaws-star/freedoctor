<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\PatientRegistration;
use App\Models\Specialty;
use App\Models\PatientPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;
use App\Events\MessageReceived;
use App\Models\UserMessage;
use App\Models\CampaignSponsor;
use App\Models\BusinessOrganizationRequest;
use Exception;
use App\Models\DoctorMessage;
use App\Events\DoctorMessageSent;
use App\Http\Controllers\ReferralController;
use App\Models\User;
use App\Models\CampaignReferral;    

class UserPaymentController extends Controller
{
    protected $razorpay;

    public function __construct()
    {
        $this->razorpay = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );
    }

    /**
     * Handle free campaign registration (no payment required)
     */
    public function register(Request $request)
    {
        // 1. Validate the incoming data for free registration
        $data = $request->validate([
            'user_id'       => 'required',
            'campaign_id'   => 'required|exists:campaigns,id',
            'name'          => 'required|string|max:255',
            'email'         => 'required|email',
            'phone_number'  => 'required|string|max:15',
            'address'       => 'nullable|string|max:255',
        ]);

        // 2. Ensure the form's user_id matches the authenticated user
        if (!Auth::guard('user')->check() || Auth::guard('user')->id() !== (int)$data['user_id']) {
            return response()->json([
                'message' => 'Unauthorized. User mismatch.',
            ], 403);
        }

        // 3. Fetch the campaign and verify it's free
        $campaign = Campaign::findOrFail($data['campaign_id']);
        
        if ($campaign->registration_payment > 0) {
            return response()->json([
                'message' => 'This campaign requires payment. Please use the payment option.',
            ], 400);
        }

        // 4. Check if user is already registered
        $existingRegistration = PatientRegistration::where('user_id', $data['user_id'])
                                                  ->where('campaign_id', $data['campaign_id'])
                                                  ->first();
        
        if ($existingRegistration) {
            return response()->json([
                'message' => 'You are already registered for this campaign.',
            ], 409);
        }

        // 5. Create the free registration
        $registration = PatientRegistration::create([
            'amount'       => 0,
            'user_id'      => $data['user_id'],
            'name'         => $data['name'],
            'email'        => $data['email'],
            'phone_number' => $data['phone_number'],
            'address'      => $data['address'] ?? null,
            'campaign_id'  => $data['campaign_id'],
            'status'       => 'registered', // Free registration status
        ]);

        // 6. Create a user notification
        $msgText = "Hi {$data['name']}, your free registration for '{$campaign->title}' has been successfully completed.";
        UserMessage::create([
            'user_id' => $data['user_id'],
            'message' => $msgText,
        ]);

        // 7. Broadcast the message event
        event(new MessageReceived($msgText, $data['user_id']));

        // 8. Process referral if exists
        $referralController = new ReferralController();
        $referralResult = $referralController->processReferral($registration);
        
        if ($referralResult) {
            // Update referrer's total earnings and available balance for free registration
            $referrer = User::find($referralResult->referrer_user_id);
            if ($referrer) {
                $earningAmount = $referralResult->per_campaign_refer_cost ?? 0;
                $referrer->update([
                    'total_earnings' => $referrer->total_earnings + $earningAmount,
                    'available_balance' => $referrer->available_balance + $earningAmount
                ]);
                
                Log::info('Referrer earnings updated for free registration', [
                    'referrer_id' => $referrer->id,
                    'earning_amount' => $earningAmount,
                    'new_total_earnings' => $referrer->total_earnings,
                    'new_available_balance' => $referrer->available_balance
                ]);
            }
            
            Log::info('Referral processed for free registration', [
                'registration_id' => $registration->id,
                'referral_id' => $referralResult->id,
                'referrer_user_id' => $referralResult->referrer_user_id,
                'refer_cost' => $referralResult->per_campaign_refer_cost
            ]);
            
            // Notify referrer about successful referral
            $referrerMsg = "Congratulations! You earned ₹{$referralResult->per_campaign_refer_cost} for referring a user to '{$campaign->title}'";
            UserMessage::create([
                'user_id' => $referralResult->referrer_user_id,
                'message' => $referrerMsg,
            ]);
            event(new MessageReceived($referrerMsg, $referralResult->referrer_user_id));
        }

        // 9. Create doctor notification
        $user = auth()->user();
        $message = DoctorMessage::create([
            'doctor_id' => $campaign->doctor_id,
            'campaign_id' => $campaign->id,
            'user_id' => $user->id,
            'type' => 'registration',
            'message' => "New free registration from {$user->name} for campaign '{$campaign->title}'.",
            'amount' => 0,
            'read' => false,
        ]);

        // 10. Fire the broadcast
        broadcast(new DoctorMessageSent($message))->toOthers();

        // 11. Return JSON response
        return response()->json([
            'success' => true,
            'redirect_url' => route('user.my-registrations'),
            'message' => 'Free registration completed successfully!',
        ]);
    }

    public function createPayment(Request $request)
    {
        // 1. Validate including user_id from the form
        $data = $request->validate([
            'amount'            => 'required',
            'user_id'              => 'required',
            'campaign_id'          => 'required|exists:campaigns,id',
            'name'                 => 'required|string|max:255',
            'email'                => 'required|email',
            'phone_number'         => 'required|string|max:15',
            'address'              => 'nullable|string|max:255',
            'razorpay_payment_id'  => 'required|string',
        ]);

        // 2. Ensure the form's user_id matches the authenticated user
        if (! Auth::guard('user')->check() || Auth::guard('user')->id() !== (int)$data['user_id']) {
            return response()->json([
                'message' => 'Unauthorized. User mismatch.',
            ], 403);
        }

        // 3. Fetch the campaign
        $campaign = Campaign::findOrFail($data['campaign_id']);

        // 4. Create the registration
        $registration = PatientRegistration::create([
            'amount'      => $data['amount'],
            'user_id'      => $data['user_id'],
            'name'         => $data['name'],
            'email'        => $data['email'],
            'phone_number' => $data['phone_number'],
            'address'      => $data['address'] ?? null,
            'campaign_id'  => $data['campaign_id'],
            'status'       => 'paid',  // ensure 'paid' is allowed in your column definition
        ]);

        // 5. Create a user notification
        $msgText = "Hi {$data['name']}, your registration for '{$campaign->title}' has been successfully completed.";
        UserMessage::create([
            'user_id' => $data['user_id'],
            'message' => $msgText,
        ]);

        // 6. Broadcast the message event
        event(new MessageReceived($msgText, $data['user_id']));

        // 7. Process referral if exists - only create campaign_referrals when user has referred_by
        try {
            // Get the user who registered
            $user = User::find($data['user_id']);
            if (!$user) {
                Log::warning('User not found for registration', [
                    'registration_id' => $registration->id,
                    'user_id' => $data['user_id']
                ]);
            } else {
                // Check if user has referred_by value (was referred by someone)
                if (!empty($user->referred_by)) {
                    // Find the referrer using the referred_by field
                    $referrer = User::where('your_referral_id', $user->referred_by)->first();
                    if ($referrer) {
                        // Check if user is not referring themselves
                        if ($referrer->id != $user->id) {
                           
                                // Generate referral code
                                $referralCode = 'REF_' . $referrer->id . '_' . $campaign->id . '_' . time();
                                
                                // Create the CampaignReferral record with per_refer_cost from campaign
                                $referral = CampaignReferral::create([
                                    'user_id' => $user->id,
                                    'campaign_id' => $data['campaign_id'],
                                    'referrer_user_id' => $referrer->id,
                                    'per_campaign_refer_cost' => $campaign->per_refer_cost ?? 0,
                                    'referral_code' => $referralCode,
                                    'registration_completed_at' => now(),
                                    'status' => 'completed',
                                    'notes' => 'Campaign referral created when user registered with referred_by value (paid registration)'
                                ]);
                                
                                // Update referrer's total earnings and available balance
                                $earningAmount = $campaign->per_refer_cost ?? 0;
                                $referrer->update([
                                    'total_earnings' => $referrer->total_earnings + $earningAmount,
                                    'available_balance' => $referrer->available_balance + $earningAmount
                                ]);
                                
                                Log::info('Campaign referral created successfully for paid registration', [
                                    'referral_id' => $referral->id,
                                    'user_id' => $user->id,
                                    'referrer_user_id' => $referrer->id,
                                    'campaign_id' => $data['campaign_id'],
                                    'per_refer_cost' => $campaign->per_refer_cost ?? 0,
                                    'referred_by' => $user->referred_by,
                                    'referrer_earnings_updated' => [
                                        'earning_amount' => $earningAmount,
                                        'new_total_earnings' => $referrer->total_earnings,
                                        'new_available_balance' => $referrer->available_balance
                                    ]
                                ]);
                                
                                // Notify referrer about successful referral
                                $referrerMsg = "Congratulations! You earned ₹{$referral->per_campaign_refer_cost} for referring {$user->username} to '{$campaign->title}'";
                                UserMessage::create([
                                    'user_id' => $referrer->id,
                                    'message' => $referrerMsg,
                                ]);
                                event(new \App\Events\MessageReceived($referrerMsg, $referrer->id));
                           
                        } else {
                            Log::warning('User tried to refer themselves', [
                                'user_id' => $user->id,
                                'campaign_id' => $data['campaign_id']
                            ]);
                        }
                    } else {
                        Log::warning('Referrer not found for referred_by value', [
                            'user_id' => $user->id,
                            'referred_by' => $user->referred_by,
                            'campaign_id' => $data['campaign_id']
                        ]);
                    }
                } else {
                    // User was not referred, no need to create CampaignReferral record
                    Log::info('User was not referred, skipping referral processing', [
                        'user_id' => $user->id,
                        'campaign_id' => $data['campaign_id']
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error processing campaign referral for paid registration', [
                'error' => $e->getMessage(),
                'registration_id' => $registration->id,
                'user_id' => $data['user_id'],
                'campaign_id' => $data['campaign_id']
            ]);
        }

        $user = auth()->user();

        $message = DoctorMessage::create([
            'doctor_id' => $campaign->doctor_id,
            'campaign_id' => $campaign->id,
            'user_id' => $user->id,
            'type' => 'registration',
            'message' => "New registration from {$user->name} for campaign '{$campaign->title}'.",
            'amount' => $data['amount'],
            'read' => false,
        ]);

        // 🔔 Fire the broadcast
        broadcast(new DoctorMessageSent($message))->toOthers();

        // 8. Return JSON response
        return response()->json([
            'redirect_url' => route('user.my-registrations'),
            'message'      => 'Registration completed successfully!',
        ]);
    }

    public function sponserPayment(Request $request)
    {
        // 1) Validate the incoming data
        $data = $request->validate([
            'campaign_id'   => 'required|exists:campaigns,id',
            'user_id'       => 'required|exists:users,id',
            'name'          => 'required|string|max:255',
            'phone_number'  => 'required|string|max:20',
            'address'       => 'required|string|max:255',
            'message'       => 'nullable|string|max:1000',
            'amount'        => 'required|numeric|min:1',
        ]);

        // 2) Ensure the form's user_id matches the logged‑in user
        $currentUserId = Auth::guard('user')->id();
        if ($currentUserId !== (int)$data['user_id']) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Unauthorized action.',
            ], 403);
        }

        // 3) Fetch the campaign to include its title in the notification
        $campaign = Campaign::findOrFail($data['campaign_id']);

        // 4) Create the sponsor record
        $sponsor = CampaignSponsor::create([
            'campaign_id'  => $data['campaign_id'],
            'user_id'      => $data['user_id'],
            'name'         => $data['name'],
            'phone_number' => $data['phone_number'],
            'address'      => $data['address'],
            'message'      => $data['message'] ?? null,
            'amount'       => $data['amount'],
            'staus'         => 'paid', // Default status
        ]);

        // 5) Create a notification entry
        $msgText = "Hi {$data['name']}, thank you for sponsoring '{$campaign->title}' with ₹{$data['amount']}!";
        UserMessage::create([
            'user_id' => $data['user_id'],
            'message' => $msgText,
        ]);

        // 6) Broadcast the notification event
        event(new MessageReceived($msgText, $data['user_id']));

        $user = auth()->user();

        DoctorMessage::create([
            'doctor_id'    => $campaign->doctor_id,
            'campaign_id'  => $campaign->id,
            'user_id'      => $user->id,
            'type'         => 'registration',
            'message'      => "New Sponsor from {$user->name} for campaign '{$campaign->title}'.",
            'amount'       => $data['amount'] ?? 0,
            'read'         => false,
        ]);

        // 🔔 Fire the broadcast
        
        // 7) Return JSON response for front‑end handling
        return response()->json([
            'redirect_url' => route('user.my-registrations'),
            'message'      => 'Thank you for sponsoring this campaign!',
            'data'         => $sponsor,
        ]);
    }
}
