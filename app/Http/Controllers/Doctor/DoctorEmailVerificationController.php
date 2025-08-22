<?php
namespace App\Http\Controllers\Doctor;

use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\Doctor;
use App\Http\Controllers\Controller;

class DoctorEmailVerificationController extends Controller
{
    /**
     * Show the email verification notice.
     */
    public function notice(Request $request)
    {
        return $request->user('doctor')->hasVerifiedEmail()
                    ? redirect()->intended(route('doctor.dashboard'))
                    : view('doctor.auth.verify-email');
    }

    /**
     * Mark the authenticated user's email address as verified.
     */
    public function verify(Request $request, $id, $hash)
    {
        $doctor = Doctor::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($doctor->getEmailForVerification()))) {
            abort(403);
        }

        if (! $doctor->hasVerifiedEmail()) {
            $doctor->markEmailAsVerified();
            event(new Verified($doctor));
        }

        return redirect()->intended('/doctor/dashboard')->with('verified', true);
    }

    /**
     * Send a new email verification notification.
     */
    public function send(Request $request)
    {
        if ($request->user('doctor')->hasVerifiedEmail()) {
            return redirect()->intended(route('doctor.dashboard'));
        }

        $request->user('doctor')->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
