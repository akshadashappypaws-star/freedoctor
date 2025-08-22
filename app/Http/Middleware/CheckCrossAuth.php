<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckCrossAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $intendedGuard
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $intendedGuard)
    {
        // Check if user is already authenticated in a different guard
        $currentGuard = null;
        $currentUserName = null;
        
        if (Auth::guard('user')->check()) {
            $currentGuard = 'user';
            $currentUserName = Auth::guard('user')->user()->name ?? Auth::guard('user')->user()->username ?? 'User';
        } elseif (Auth::guard('doctor')->check()) {
            $currentGuard = 'doctor';
            $currentUserName = Auth::guard('doctor')->user()->name ?? Auth::guard('doctor')->user()->username ?? 'Doctor';
        } elseif (Auth::guard('admin')->check()) {
            $currentGuard = 'admin';
            $currentUserName = Auth::guard('admin')->user()->name ?? Auth::guard('admin')->user()->username ?? 'Admin';
        }
        
        // If user is authenticated in a different guard than intended
        if ($currentGuard && $currentGuard !== $intendedGuard) {
            $currentPortal = ucfirst($currentGuard);
            $intendedPortal = ucfirst($intendedGuard);
            
            // Set session data for modal
            Session::flash('cross_auth_error', [
                'current_guard' => $currentGuard,
                'current_portal' => $currentPortal,
                'intended_portal' => $intendedPortal,
                'current_user_name' => $currentUserName,
                'logout_route' => route($currentGuard . '.logout')
            ]);
            
            // Redirect back to the portal they're currently logged into
            return redirect()->route($currentGuard . '.dashboard');
        }
        
        return $next($request);
    }
}
