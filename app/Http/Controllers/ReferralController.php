<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Campaign;
use App\Models\CampaignReferral;
use App\Models\User;
use App\Models\PatientRegistration;
use App\Models\UserMessage;
use App\Events\MessageReceived;

class ReferralController extends Controller
{
    /**
     * Handle referral link click and store referral code in session/cookie
     */
    public function handleReferralClick(Request $request, $campaignId)
    {
        $referralCode = $request->get('ref');
        
        if (!$referralCode) {
            return redirect()->route('user.campaign.details', $campaignId);
        }
        
        // Parse referral code to get referrer user ID
        $parts = explode('_', $referralCode);
        if (count($parts) < 2 || $parts[0] !== 'REF') {
            return redirect()->route('user.campaign.details', $campaignId);
        }
        
        $referrerUserId = $parts[1];
        
        // Verify referrer exists
        $referrer = User::find($referrerUserId);
        if (!$referrer) {
            return redirect()->route('user.campaign.details', $campaignId);
        }
        
        // Verify campaign exists
        $campaign = Campaign::find($campaignId);
        if (!$campaign) {
            return redirect()->route('home');
        }
        
        // Store referral information in session and cookie (for persistent tracking)
        $referralData = [
            'referrer_user_id' => $referrerUserId,
            'campaign_id' => $campaignId,
            'referral_code' => $referralCode,
            'per_campaign_refer_cost' => $campaign->per_refer_cost ?? 0,
            'clicked_at' => now()->toISOString()
        ];
        
        // Store in session (temporary)
        session(['referral_data' => $referralData]);
        
        // Store in cookie (persistent across browser sessions) - 30 days
        cookie()->queue('referral_data', json_encode($referralData), 60 * 24 * 30);
        
        Log::info('Referral link clicked', [
            'referrer_user_id' => $referrerUserId,
            'campaign_id' => $campaignId,
            'referral_code' => $referralCode,
            'user_ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        
        return redirect()->route('user.campaign.details', $campaignId)
                        ->with('referral_info', "You were referred by " . $referrer->username);
    }
    
    /**
     * Handle clean referral links from campaign details page
     */
    public function handleCleanReferralLink(Request $request, $campaignId)
    {
        $encodedRef = $request->get('ref');
        
        if (!$encodedRef) {
            return;
        }
        
        // Decode the referral code
        $referralCode = base64_decode($encodedRef);
        
        if (!$referralCode) {
            return;
        }
        
        // Parse referral code to get referrer user ID
        $parts = explode('_', $referralCode);
        if (count($parts) < 2 || $parts[0] !== 'REF') {
            return;
        }
        
        $referrerUserId = $parts[1];
        
        // Verify referrer exists
        $referrer = User::find($referrerUserId);
        if (!$referrer) {
            return;
        }
        
        // Verify campaign exists
        $campaign = Campaign::find($campaignId);
        if (!$campaign) {
            return;
        }
        
        // Store referral information in session and cookie (for persistent tracking)
        $referralData = [
            'referrer_user_id' => $referrerUserId,
            'campaign_id' => $campaignId,
            'referral_code' => $referralCode,
            'per_campaign_refer_cost' => $campaign->per_refer_cost ?? 0,
            'clicked_at' => now()->toISOString()
        ];
        
        // Store in session (temporary)
        session(['referral_data' => $referralData]);
        
        // Store in cookie (persistent across browser sessions) - 30 days
        cookie()->queue('referral_data', json_encode($referralData), 60 * 24 * 30);
        
        Log::info('Clean referral link clicked', [
            'referrer_user_id' => $referrerUserId,
            'campaign_id' => $campaignId,
            'referral_code' => $referralCode,
            'user_ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        
        return $referralData;
    }
    
    /**
     * Process referral from localStorage data (API endpoint for frontend)
     */
    public function processReferralFromLocalStorage(Request $request)
    {
        $request->validate([
            'referral_code' => 'required|string',
            'campaign_id' => 'required|exists:campaigns,id',
            'user_id' => 'required|exists:users,id',
            'registration_id' => 'required|exists:patient_registrations,id'
        ]);
        
        // Parse referral code to get referrer user ID
        $parts = explode('_', $request->referral_code);
        if (count($parts) < 2 || $parts[0] !== 'REF') {
            return response()->json(['success' => false, 'message' => 'Invalid referral code']);
        }
        
        $referrerUserId = $parts[1];
        $campaign = Campaign::findOrFail($request->campaign_id);
        
        // Check if user is not referring themselves
        if ($referrerUserId == $request->user_id) {
            return response()->json(['success' => false, 'message' => 'Cannot refer yourself']);
        }
        
        // Check if this referral already exists
        $existingReferral = CampaignReferral::where('user_id', $request->user_id)
                                          ->where('campaign_id', $request->campaign_id)
                                          ->first();
        
        if ($existingReferral) {
            return response()->json(['success' => false, 'message' => 'Referral already processed']);
        }
        
        // Create the referral record
        $referral = CampaignReferral::create([
            'user_id' => $request->user_id,
            'campaign_id' => $request->campaign_id,
            'referrer_user_id' => $referrerUserId,
            'per_campaign_refer_cost' => $campaign->per_refer_cost ?? 0,
            'referral_code' => $request->referral_code,
            'registration_completed_at' => now(),
            'status' => 'completed',
            'notes' => 'Referral processed from localStorage tracking'
        ]);
        
        // Update referrer's total earnings and available balance
        $referrer = User::find($referrerUserId);
        if ($referrer) {
            $earningAmount = $campaign->per_refer_cost ?? 0;
            $referrer->update([
                'total_earnings' => $referrer->total_earnings + $earningAmount,
                'available_balance' => $referrer->available_balance + $earningAmount
            ]);
            
            Log::info('Referrer earnings updated', [
                'referrer_id' => $referrer->id,
                'earning_amount' => $earningAmount,
                'new_total_earnings' => $referrer->total_earnings,
                'new_available_balance' => $referrer->available_balance
            ]);
        }
        
        Log::info('Referral processed from localStorage', [
            'referral_id' => $referral->id,
            'user_id' => $request->user_id,
            'referrer_user_id' => $referrerUserId,
            'campaign_id' => $request->campaign_id,
            'refer_cost' => $campaign->per_refer_cost ?? 0
        ]);
        
        // Notify referrer about successful referral
        $referrerMsg = "Congratulations! You earned ₹{$referral->per_campaign_refer_cost} for referring a user to '{$campaign->title}'";
        UserMessage::create([
            'user_id' => $referrerUserId,
            'message' => $referrerMsg,
        ]);
        event(new MessageReceived($referrerMsg, $referrerUserId));
        
        return response()->json([
            'success' => true,
            'message' => 'Referral processed successfully',
            'referral_id' => $referral->id,
            'earnings' => $campaign->per_refer_cost ?? 0
        ]);
    }
    
    /**
     * Process referral when user successfully registers for a campaign
     * Only creates CampaignReferral record if user has referred_by value
     */
    public function processReferral(PatientRegistration $registration)
    {
        try {
            // Get the user who registered
            $user = User::find($registration->user_id);
            if (!$user) {
                Log::warning('User not found for registration', [
                    'registration_id' => $registration->id,
                    'user_id' => $registration->user_id
                ]);
                return false;
            }
            
            // Check if user has referred_by value (was referred by someone)
            if (empty($user->referred_by)) {
                // User was not referred, no need to create CampaignReferral record
                Log::info('User was not referred, skipping referral processing', [
                    'user_id' => $user->id,
                    'campaign_id' => $registration->campaign_id
                ]);
                return false;
            }
            
            // Find the referrer using the referred_by field
            $referrer = User::where('your_referral_id', $user->referred_by)->first();
            if (!$referrer) {
                Log::warning('Referrer not found for referred_by value', [
                    'user_id' => $user->id,
                    'referred_by' => $user->referred_by,
                    'campaign_id' => $registration->campaign_id
                ]);
                return false;
            }
            
            // Get the campaign to fetch per_refer_cost
            $campaign = Campaign::find($registration->campaign_id);
            if (!$campaign) {
                Log::warning('Campaign not found for registration', [
                    'registration_id' => $registration->id,
                    'campaign_id' => $registration->campaign_id
                ]);
                return false;
            }
            
            // Check if user is not referring themselves
            if ($referrer->id == $user->id) {
                Log::warning('User tried to refer themselves', [
                    'user_id' => $user->id,
                    'campaign_id' => $registration->campaign_id
                ]);
                return false;
            }
            
            // Generate referral code if needed
            $referralCode = 'REF_' . $referrer->id . '_' . $campaign->id . '_' . time();
            
            // Create the CampaignReferral record with per_refer_cost from campaign
            $referral = CampaignReferral::create([
                'user_id' => $user->id,
                'campaign_id' => $registration->campaign_id,
                'referrer_user_id' => $referrer->id,
                'per_campaign_refer_cost' => $campaign->per_refer_cost ?? 0, // Use campaign's per_refer_cost
                'referral_code' => $referralCode,
                'registration_completed_at' => now(),
                'status' => 'completed',
                'notes' => 'Campaign referral created when user registered with referred_by value'
            ]);
            
            // Update referrer's total earnings and available balance
            $earningAmount = $campaign->per_refer_cost ?? 0;
            $referrer->update([
                'total_earnings' => $referrer->total_earnings + $earningAmount,
                'available_balance' => $referrer->available_balance + $earningAmount
            ]);
            
            Log::info('Campaign referral created successfully', [
                'referral_id' => $referral->id,
                'user_id' => $user->id,
                'referrer_user_id' => $referrer->id,
                'campaign_id' => $registration->campaign_id,
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
            event(new MessageReceived($referrerMsg, $referrer->id));
            
            return $referral;
            
        } catch (\Exception $e) {
            Log::error('Error processing campaign referral', [
                'error' => $e->getMessage(),
                'registration_id' => $registration->id,
                'user_id' => $registration->user_id ?? null,
                'campaign_id' => $registration->campaign_id ?? null
            ]);
            return false;
        }
    }
    
    /**
     * Get user's referral dashboard
     */
    public function dashboard()
    {
        $user = Auth::guard('user')->user();
        
        if (!$user) {
            return redirect()->route('user.login');
        }
        
        // Get user's referral statistics using user table fields
        $totalReferrals = CampaignReferral::where('referrer_user_id', $user->id)
                                         ->where('status', 'completed')
                                         ->count();
        
        // Use user table fields for earnings calculation
        $totalEarnings = $user->total_earnings ?? 0;
        $withdrawnAmount = $user->withdrawn_amount ?? 0;
        $availableBalance = $user->available_balance ?? 0;
        
        // Calculate withdrawal eligibility
        $minimumWithdrawal = 100; // ₹100 minimum
        $canWithdraw = $availableBalance >= $minimumWithdrawal;
        
        $pendingReferrals = CampaignReferral::where('referrer_user_id', $user->id)
                                           ->where('status', 'pending')
                                           ->count();
        
        // Get referrals grouped by campaign
        $referralsByCampaign = CampaignReferral::where('referrer_user_id', $user->id)
                                   ->with(['campaign', 'user'])
                                   ->where('status', 'completed')
                                   ->get()
                                   ->groupBy('campaign_id')
                                   ->map(function ($referrals) {
                                       $campaign = $referrals->first()->campaign;
                                       return [
                                           'campaign_name' => $campaign->title,
                                           'campaign_id' => $campaign->id,
                                           'total_referrals' => $referrals->count(),
                                           'total_amount' => $referrals->sum('per_campaign_refer_cost'),
                                           'per_refer_cost' => $campaign->per_refer_cost ?? 0,
                                           'referrals' => $referrals
                                       ];
                                   });
        
        // Also include direct user referrals (users who registered through referral links)
        $directUserReferrals = User::where('referred_by', $user->your_referral_id)
                                   ->where('referral_completed_at', '!=', null)
                                   ->count();
        
        // Get recent referrals (last 10)
        $recentReferrals = CampaignReferral::where('referrer_user_id', $user->id)
                               ->with(['campaign', 'user'])
                               ->where('status', 'completed')
                               ->orderBy('created_at', 'desc')
                               ->limit(10)
                               ->get();
        
        // Get withdrawal requests from patient_payments table
        $withdrawalRequests = \App\Models\PatientPayment::where('amount', '<', 0) // Negative amounts are withdrawals
            ->whereHas('patientRegistration', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($payment) {
                $details = $payment->payment_details ?? [];
                return [
                    'id' => $payment->id,
                    'amount' => abs($payment->amount),
                    'status' => $payment->payment_status,
                    'bank_name' => $details['bank_name'] ?? 'Not specified',
                    'account_number' => $details['bank_account_number'] ?? 'N/A',
                    'ifsc_code' => $details['bank_ifsc_code'] ?? 'N/A',
                    'account_holder_name' => $details['account_holder_name'] ?? 'N/A',
                    'order_id' => $payment->order_id,
                    'payment_id' => $payment->payment_id,
                    'receipt_number' => $payment->receipt_number,
                    'created_at' => $payment->created_at,
                    'payment_date' => $payment->payment_date,
                    'type' => 'referral_withdrawal'
                ];
            });
        
        return view('user.pages.referral-dashboard', compact(
            'totalReferrals', 
            'totalEarnings', 
            'withdrawnAmount',
            'availableBalance',
            'canWithdraw',
            'minimumWithdrawal',
            'pendingReferrals', 
            'referralsByCampaign', 
            'recentReferrals', 
            'withdrawalRequests',
            'directUserReferrals'
        ));
    }
    
    /**
     * Generate a new referral link for a campaign
     */
    public function generateReferralLink(Request $request)
    {
        $request->validate([
            'campaign_id' => 'required|exists:campaigns,id'
        ]);
        
        $user = Auth::guard('user')->user();
        
        if (!$user || !$user->your_referral_id) {
            return response()->json([
                'success' => false,
                'message' => 'User referral ID not found. Please contact support.'
            ]);
        }
        
        $campaign = Campaign::findOrFail($request->campaign_id);
        
        // Create referral link using user's referral ID
        $baseUrl = config('app.url');
        $referralLink = $baseUrl . '/user/register?ref=' . $user->your_referral_id . '&campaign=' . $request->campaign_id;
        
        return response()->json([
            'success' => true,
            'referral_code' => $user->your_referral_id,
            'referral_link' => $referralLink,
            'campaign_title' => $campaign->title,
            'per_refer_cost' => $campaign->per_cost_refer ?? 0
        ]);
    }
    
    /**
     * Track referral link clicks (API endpoint)
     */
    public function trackReferralClick(Request $request)
    {
        $request->validate([
            'referral_code' => 'required|string',
            'campaign_id' => 'required|exists:campaigns,id',
            'click_timestamp' => 'required|numeric'
        ]);
        
        // Parse referral code to get referrer user ID
        $parts = explode('_', $request->referral_code);
        if (count($parts) < 2 || $parts[0] !== 'REF') {
            return response()->json(['success' => false, 'message' => 'Invalid referral code']);
        }
        
        $referrerUserId = $parts[1];
        
        // Log the click for analytics
        Log::info('Referral link clicked via API', [
            'referrer_user_id' => $referrerUserId,
            'campaign_id' => $request->campaign_id,
            'referral_code' => $request->referral_code,
            'timestamp' => $request->click_timestamp,
            'user_ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Click tracked successfully'
        ]);
    }
    
    /**
     * Sync existing referral earnings to user table
     * This method can be used to update user earnings from existing CampaignReferral records
     */
    public function syncReferralEarnings()
    {
        try {
            // Get all users who have made referrals
            $referrers = User::whereHas('referralsMade', function ($query) {
                $query->where('status', 'completed');
            })->get();
            
            foreach ($referrers as $user) {
                // Calculate total earnings from completed referrals
                $totalEarnings = $user->referralsMade()
                    ->where('status', 'completed')
                    ->sum('per_campaign_refer_cost');
                
                // Update user's total earnings (keep existing withdrawn_amount)
                $currentWithdrawn = $user->withdrawn_amount ?? 0;
                $availableBalance = max(0, $totalEarnings - $currentWithdrawn);
                
                $user->update([
                    'total_earnings' => $totalEarnings,
                    'available_balance' => $availableBalance
                ]);
                
                Log::info('User earnings synced', [
                    'user_id' => $user->id,
                    'total_earnings' => $totalEarnings,
                    'available_balance' => $availableBalance,
                    'withdrawn_amount' => $currentWithdrawn
                ]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Referral earnings synced successfully',
                'users_updated' => $referrers->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error syncing referral earnings', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to sync referral earnings'
            ], 500);
        }
    }
    
    /**
     * Get user's current earnings summary
     */
    public function getEarningsSummary()
    {
        $user = Auth::guard('user')->user();
        
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
        
        // Get earnings from user table
        $userEarnings = [
            'total_earnings' => $user->total_earnings ?? 0,
            'withdrawn_amount' => $user->withdrawn_amount ?? 0,
            'available_balance' => $user->available_balance ?? 0
        ];
        
        // Get calculated earnings from referrals (for comparison)
        $calculatedEarnings = $user->referralsMade()
            ->where('status', 'completed')
            ->sum('per_campaign_refer_cost');
        
        // Get referral counts
        $referralCounts = [
            'total_referrals' => $user->referralsMade()->where('status', 'completed')->count(),
            'pending_referrals' => $user->referralsMade()->where('status', 'pending')->count()
        ];
        
        return response()->json([
            'user_earnings' => $userEarnings,
            'calculated_earnings' => $calculatedEarnings,
            'referral_counts' => $referralCounts,
            'earnings_match' => $userEarnings['total_earnings'] == $calculatedEarnings
        ]);
    }
}
