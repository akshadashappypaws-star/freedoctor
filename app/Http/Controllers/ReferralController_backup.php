<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Campaign;
use App\Models\CampaignReferral;
use App\Models\User;
use App\Models\PatientRegistration;

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
     * Process referral when user successfully registers for a campaign
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
            
            Log::info('Campaign referral created successfully', [
                'referral_id' => $referral->id,
                'user_id' => $user->id,
                'referrer_user_id' => $referrer->id,
                'campaign_id' => $registration->campaign_id,
                'per_refer_cost' => $campaign->per_refer_cost ?? 0,
                'referred_by' => $user->referred_by
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
        
        // Get user's referral statistics
        $totalReferrals = $user->referralsMade()->where('status', 'completed')->count();
        $totalEarnings = $user->getTotalReferralEarnings();
        $pendingReferrals = $user->referralsMade()->where('status', 'pending')->count();
        
        // Get referrals grouped by campaign
        $referralsByCampaign = $user->referralsMade()
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
                                           'per_refer_cost' => $campaign->per_refer_cost,
                                           'referrals' => $referrals
                                       ];
                                   });
        
        return view('user.pages.referral-dashboard', compact(
            'totalReferrals', 
            'totalEarnings', 
            'pendingReferrals', 
            'referralsByCampaign', 
            'recentReferrals', 
            'withdrawalRequests'
        ));
    }
    
    /**
     * Generate a new referral link for a campaign
     */
    public function generateReferralLink(Request $request)
    {
        $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'user_id' => 'required|exists:users,id'
        ]);
        
        $user = Auth::guard('user')->user();
        $campaign = Campaign::findOrFail($request->campaign_id);
        
        // Generate unique referral code
        $referralCode = CampaignReferral::generateReferralCode($user->id, $request->campaign_id);
        
        // Create referral link
        $referralLink = route('referral.click', [
            'campaignId' => $request->campaign_id,
            'ref' => $referralCode
        ]);
        
        return response()->json([
            'success' => true,
            'referral_code' => $referralCode,
            'referral_link' => $referralLink,
            'campaign_title' => $campaign->title,
            'refer_cost' => $campaign->per_refer_cost ?? 0
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
     * Track referral conversions (API endpoint)
     */
    public function trackReferralConversion(Request $request)
    {
        $request->validate([
            'referral_code' => 'required|string',
            'campaign_id' => 'required|exists:campaigns,id',
            'registration_id' => 'required|numeric',
            'conversion_timestamp' => 'required|numeric'
        ]);
        
        // Parse referral code to get referrer user ID
        $parts = explode('_', $request->referral_code);
        if (count($parts) < 2 || $parts[0] !== 'REF') {
            return response()->json(['success' => false, 'message' => 'Invalid referral code']);
        }
        
        $referrerUserId = $parts[1];
        $campaign = Campaign::findOrFail($request->campaign_id);
        
        // Check if this conversion already exists
        $existingReferral = CampaignReferral::where('referral_code', $request->referral_code)
                                          ->where('campaign_id', $request->campaign_id)
                                          ->first();
        
        if (!$existingReferral) {
            // Create the referral record for successful conversion
            $referral = CampaignReferral::create([
                'user_id' => $request->registration_id, // This should be the actual user_id from registration
                'campaign_id' => $request->campaign_id,
                'referrer_user_id' => $referrerUserId,
                'per_campaign_refer_cost' => $campaign->per_refer_cost ?? 0,
                'referral_code' => $request->referral_code,
                'registration_completed_at' => now(),
                'status' => 'completed',
                'notes' => 'Conversion tracked via localStorage API'
            ]);
            
            Log::info('Referral conversion tracked via API', [
                'referral_id' => $referral->id,
                'referrer_user_id' => $referrerUserId,
                'campaign_id' => $request->campaign_id,
                'refer_cost' => $campaign->per_refer_cost ?? 0,
                'timestamp' => $request->conversion_timestamp
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Conversion tracked successfully',
                'referral_id' => $referral->id,
                'earnings' => $campaign->per_refer_cost ?? 0
            ]);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Conversion already tracked',
                'referral_id' => $existingReferral->id
            ]);
        }
    }
  
    public function generateReferralLink(Request $request)
    {
        $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'user_id' => 'required|exists:users,id'
        ]);
        
        $user = Auth::guard('user')->user();
        
        if (!$user || !$request->campaign_id) {
            return response()->json(['error' => 'Invalid request'], 400);
        }
        
        $campaign = Campaign::find($request->campaign_id);
        if (!$campaign) {
            return response()->json(['error' => 'Campaign not found'], 404);
        }
        
        // Generate unique referral code
        $referralCode = CampaignReferral::generateReferralCode($user->id, $request->campaign_id);
        
        // Create referral link using the existing route
        $referralLink = route('referral.click', [
            'campaignId' => $request->campaign_id,
            'ref' => $referralCode
        ]);
        
        return response()->json([
            'success' => true,
            'referral_code' => $referralCode,
            'referral_link' => $referralLink,
            'campaign_title' => $campaign->title,
            'per_refer_cost' => $campaign->per_refer_cost ?? 0
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
     * Track referral conversions (API endpoint)
     */
    public function trackReferralConversion(Request $request)
    {
        $request->validate([
            'referral_code' => 'required|string',
            'campaign_id' => 'required|exists:campaigns,id',
            'registration_id' => 'required|numeric',
            'conversion_timestamp' => 'required|numeric'
        ]);
        
        // Parse referral code to get referrer user ID
        $parts = explode('_', $request->referral_code);
        if (count($parts) < 2 || $parts[0] !== 'REF') {
            return response()->json(['success' => false, 'message' => 'Invalid referral code']);
        }
        
        $referrerUserId = $parts[1];
        $campaign = Campaign::findOrFail($request->campaign_id);
        
        // Check if this conversion already exists
        $existingReferral = CampaignReferral::where('referral_code', $request->referral_code)
                                          ->where('campaign_id', $request->campaign_id)
                                          ->first();
        
        if (!$existingReferral) {
            // Create the referral record for successful conversion
            $referral = CampaignReferral::create([
                'user_id' => $request->registration_id, // This should be the actual user_id from registration
                'campaign_id' => $request->campaign_id,
                'referrer_user_id' => $referrerUserId,
                'per_campaign_refer_cost' => $campaign->per_refer_cost ?? 0,
                'referral_code' => $request->referral_code,
                'registration_completed_at' => now(),
                'status' => 'completed',
                'notes' => 'Conversion tracked via localStorage API'
            ]);
            
            Log::info('Referral conversion tracked via API', [
                'referral_id' => $referral->id,
                'referrer_user_id' => $referrerUserId,
                'campaign_id' => $request->campaign_id,
                'refer_cost' => $campaign->per_refer_cost ?? 0,
                'timestamp' => $request->conversion_timestamp
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Conversion tracked successfully',
                'referral_id' => $referral->id,
                'earnings' => $campaign->per_refer_cost ?? 0
            ]);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Conversion already tracked',
                'referral_id' => $existingReferral->id
            ]);
        }
    }
}
