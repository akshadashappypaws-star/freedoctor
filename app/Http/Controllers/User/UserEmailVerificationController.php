<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;

use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use App\Models\User;

class UserEmailVerificationController extends Controller
{
    /**
     * Show the email verification notice.
     */
    public function notice(Request $request)
    {
        $user = $request->user('user');
        
        return $user && $user->email_verified_at
                    ? redirect()->intended(route('user.dashboard'))
                    : view('user.auth.verify-email');
    }

    /**
     * Mark the authenticated user's email address as verified.
     */
    public function verify(Request $request, $id, $hash)
    {
        try {
            // Find the user
            $user = User::findOrFail($id);

            // Verify the hash matches (this is the primary security check)
            if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
                \Illuminate\Support\Facades\Log::error('Email verification failed: Hash mismatch', [
                    'user_id' => $id,
                    'expected_hash' => sha1($user->getEmailForVerification()),
                    'received_hash' => $hash
                ]);
                return redirect()->route('user.login')->with('error', 'Invalid verification link.');
            }

            // Check if already verified
            if ($user->email_verified_at) {
                \Illuminate\Support\Facades\Log::info('Email verification attempted for already verified user', ['user_id' => $id]);
                return redirect()->route('user.login')->with('success', 'Email already verified. You can now login.');
            }

            // Mark as verified
            $user->email_verified_at = now();
            $saved = $user->save();

            if ($saved) {
                // Fire the verified event
                event(new Verified($user));
                
                \Illuminate\Support\Facades\Log::info('Email verification successful', ['user_id' => $id, 'email' => $user->email]);
                return redirect()->route('user.login')->with('success', 'Email verified successfully! You can now login to your account.');
            } else {
                \Illuminate\Support\Facades\Log::error('Email verification failed: Could not save user', ['user_id' => $id]);
                return redirect()->route('user.login')->with('error', 'Verification failed. Please try again.');
            }

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Email verification error: ' . $e->getMessage(), [
                'user_id' => $id,
                'hash' => $hash,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('user.login')->with('error', 'Verification failed. Please try again or contact support.');
        }
    }

    /**
     * Send a new email verification notification.
     */
    public function send(Request $request)
    {
        $user = $request->user('user');
        
        if ($user && $user->email_verified_at) {
            return redirect()->intended(route('user.dashboard'));
        }

        if ($user) {
            $user->sendEmailVerificationNotification();
        }

        return back()->with('status', 'verification-link-sent');
    }
}
