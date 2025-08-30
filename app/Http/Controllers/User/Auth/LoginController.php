<?php
namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm(Request $request)
    {
        // Store redirect URL if coming from a campaign registration
        if ($request->has('redirect')) {
            session(['redirect_after_login' => $request->get('redirect')]);
        }
        
        return view('user.login');
    }

public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::guard('user')->attempt($credentials)) {
        $user = Auth::guard('user')->user();
        
        if (!$user->email_verified_at) {
            Auth::guard('user')->logout();
            
            // Store user info in session for the modal
            session([
                'unverified_user_email' => $user->email,
                'unverified_user_name' => $user->name,
            ]);
            
            return back()
                ->with('error', 'Please verify your email first.')
                ->with('show_verification_modal', true)
                ->with('user_email', $user->email);
        }

        // Success message to show after login
        $successMessage = 'Login successful.';

        // Check for redirect URL from request (set via JS or form)
        if ($request->has('redirect_url') && !empty($request->get('redirect_url'))) {
            $redirectUrl = $request->get('redirect_url');
            return redirect($redirectUrl)->with('success', $successMessage);
        }

        // Check for redirect from session (e.g., campaign registration)
        if (session()->has('redirect_after_login')) {
            $redirectUrl = session()->pull('redirect_after_login');
            return redirect($redirectUrl)->with('success', $successMessage);
        }

        // Default redirection to dashboard
        return redirect()->intended(route('user.dashboard'))->with('success', $successMessage);
    }

    return back()->with('error', 'Invalid credentials.');
}

public function resendVerificationEmail(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email'
    ]);

    $user = \App\Models\User::where('email', $request->email)->first();

    if ($user && !$user->email_verified_at) {
        $user->sendEmailVerificationNotification();
        
        return response()->json([
            'success' => true,
            'message' => 'Verification email sent successfully! Please check your inbox.'
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'User is already verified or email not found.'
    ], 400);
}


public function logout(Request $request)
{
    Auth::guard('user')->logout();

    // Check if user wants to redirect to doctor portal
    if ($request->has('redirect_to_doctor') && $request->get('redirect_to_doctor') === 'true') {
        return redirect()
            ->route('doctor.login')
            ->with('info', 'Please login as a doctor to post medical camps.');
    }

    // Redirect to home page with success toast
    return redirect('/')
        ->with('success', 'You have been logged out successfully.');
}

}
