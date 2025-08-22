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
        return $request->user('user')->hasVerifiedEmail()
                    ? redirect()->intended(default: route('user.dashboard'))
                    : view('user.auth.verify-email');
    }

    /**
     * Mark the authenticated user's email address as verified.
     */
    public function verify(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403);
        }

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        return redirect()->intended('/user/dashboard')->with('verified', true);
    }

    /**
     * Send a new email verification notification.
     */
    public function send(Request $request)
    {
        if ($request->user('user')->hasVerifiedEmail()) {
            return redirect()->intended(route('user.dashboard'));
        }

        $request->user('user')->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
