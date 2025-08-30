<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;
use App\Notifications\UserVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('user.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|max:15',
            'password' => 'required|min:6|confirmed',
            'accept_terms' => 'required|accepted',
            'referred_by' => 'nullable|string|max:255', // Allow referral tracking
        ]);

                // Generate referral ID for this user
        $yourReferralId = User::generateReferralId();
        
        // Check if user was referred by someone - priority order:
        // 1. Hidden input field (referred_by)
        // 2. Session data from campaign visit
        // 3. URL parameter (ref)
        $referredBy = null;
        $referralSource = null;
        
        // Check hidden input field first
        if ($request->input('referred_by')) {
            $referredBy = $request->input('referred_by');
            $referralSource = 'form_input';
        }
        // Check session data from campaign visit
        elseif (session('referral_data')) {
            $referralData = session('referral_data');
            $referredBy = $referralData['referred_by'] ?? null;
            $referralSource = 'session_campaign_visit';
        }
        // Check URL parameter as fallback
        elseif ($request->get('ref')) {
            $referredBy = $request->get('ref');
            $referralSource = 'url_parameter';
        }
        
        // Log referral information for debugging
        if ($referredBy) {
            Log::info('New user registration with referral', [
                'new_user_email' => $request->email,
                'referred_by' => $referredBy,
                'referral_source' => $referralSource,
                'your_referral_id' => $yourReferralId,
                'session_data' => session('referral_data')
            ]);
        }
      
        $user = User::create([
            'name' => $request->username, // Use username as name for regular registration
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'status' => 'active',
            'your_referral_id' => $yourReferralId,
            'referred_by' => $referredBy,
            'referral_completed_at' => $referredBy ? now() : null,
        ]);

        // Send notification to referrer when someone registers using their referral code
        if ($referredBy) {
            $referrer = User::where('your_referral_id', $referredBy)->first();
            if ($referrer) {
                // Create notification for the referrer
                \App\Models\UserMessage::create([
                    'user_id' => $referrer->id,
                    'message' => "🎉 Great news! {$user->username} just registered using your referral code ({$referredBy}). You'll earn rewards when they make their first campaign payment!",
                    'type' => 'referral_registration',
                    'is_read' => false,
                ]);

                Log::info('Referral registration notification sent', [
                    'referrer_id' => $referrer->id,
                    'new_user_id' => $user->id,
                    'referral_code' => $referredBy,
                    'referral_source' => $referralSource
                ]);
            } else {
                Log::warning('Referrer not found for referral code', [
                    'referred_by' => $referredBy,
                    'new_user_id' => $user->id,
                    'referral_source' => $referralSource
                ]);
            }
            
            // Clear session referral data after successful registration
            session()->forget('referral_data');
        }

        try {
            // Try sending verification email
            $user->notify(new UserVerifyEmail());
        } catch (Exception $e) {
            Log::error('Email verification send failed: ' . $e->getMessage());

            // Delete the user if email send fails (optional)
            $user->delete();

            return back()->withInput()->withErrors([
                'email' => 'We could not send a verification email. Please check your email address or try again later.',
            ]);
        }

        auth('user')->login($user);

        return redirect()->route('user.user.verification.notice');
    }

    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user already exists
            $existingUser = User::where('email', $googleUser->getEmail())->first();
            
            if ($existingUser) {
                // Update Google ID if not set
                if (!$existingUser->google_id) {
                    $existingUser->update([
                        'google_id' => $googleUser->getId(),
                        'email_verified_at' => now()
                    ]);
                }
                
                Auth::guard('user')->login($existingUser);
                return redirect()->route('user.dashboard')->with('success', 'Welcome back!');
            }
            
            // Create new user
            $user = User::create([
                'name' => $googleUser->getName(),
                'username' => $this->generateUniqueUsername($googleUser->getName()),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'email_verified_at' => now(),
                'password' => Hash::make(uniqid()), // Random password since they'll use Google
                'avatar' => $googleUser->getAvatar(),
                'your_referral_id' => User::generateReferralId(),
                'status' => 'active',
            ]);

            Auth::guard('user')->login($user);
            
            return redirect()->route('user.dashboard')->with('success', 'Account created successfully! Welcome to FreeDoctor!');
            
        } catch (Exception $e) {
            Log::error('Google OAuth Error: ' . $e->getMessage());
            return redirect()->route('user.register')->with('error', 'Unable to login with Google. Please try again.');
        }
    }

    /**
     * Generate unique username from Google name
     */
    private function generateUniqueUsername($name)
    {
        $baseUsername = strtolower(str_replace(' ', '', $name));
        $username = $baseUsername;
        $counter = 1;
        
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }
        
        return $username;
    }
}
