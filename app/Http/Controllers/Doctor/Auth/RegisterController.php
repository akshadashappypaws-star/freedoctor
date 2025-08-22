<?php

namespace App\Http\Controllers\Doctor\Auth;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\AdminSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use App\Notifications\DoctorVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail; // Ensure this is imported if you are using MustVerifyEmail
use Exception;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $specialties = Specialty::all();
        
        // Get doctor subscription fee from settings
        $subscriptionSetting = AdminSetting::where('setting_key', 'doctor_subscription_fee')->first();
        $subscriptionFee = $subscriptionSetting ? $subscriptionSetting->amount : 500;
        
        return view('doctor.register', compact('specialties', 'subscriptionFee'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'Doctor_name'   => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:doctors',
            'phone'         => 'nullable|string|max:20',
            'location'      => 'nullable|string|max:255',
            'gender'        => 'nullable|string',
            'specialty_id'  => 'nullable|exists:specialties,id',
            'hospital_name' => 'nullable|string|max:255',
            'experience'    => 'nullable|integer|min:0',
            'description'   => 'nullable|string',
            'password'      => 'required|string|confirmed|min:8',
            'accept_terms'  => 'required|accepted',
        ]);

        $doctor = Doctor::create([
            'doctor_name'   => $request->Doctor_name,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'location'      => $request->location,
            'gender'        => $request->gender,
            'specialty_id'  => $request->specialty_id,
            'hospital_name' => $request->hospital_name,
            'experience'    => $request->experience,
            'description'   => $request->description,
            'password'      => Hash::make($request->password),
        ]);
    try {
            // Try sending verification email
            // triggers Laravel email verification
 
$doctor->notify(new DoctorVerifyEmail());



   } catch (Exception $e) {
            Log::error('Email verification send failed: ' . $e->getMessage());

            // Delete the doctor if email send fails (optional)
            $doctor->delete();

            return back()->withInput()->withErrors([
                'email' => 'We could not send a verification email. Please check your email address or try again later.',
            ]);
        }
        
        // Check doctor subscription fee from settings
        $subscriptionSetting = AdminSetting::where('setting_key', 'doctor_subscription_fee')->first();
        $subscriptionFee = $subscriptionSetting ? $subscriptionSetting->percentage_value : 500;
        
        // If subscription fee is 0, allow free registration and login
        if ($subscriptionFee == 0) {
            // Send verification email and log in the doctor
            Auth::guard('doctor')->login($doctor);
            
            return redirect()->route('doctor.dashboard')->with('success', 'Registration successful! Welcome to FreeDoctor platform.');
        } else {
            // Redirect to payment form if subscription fee is required
            return redirect()->route('doctor.payment.form', $doctor->id)->with('success', 'Registration successful! Please complete the payment of ₹' . number_format($subscriptionFee, 2) . ' to activate your account.');
        }
    }
}

