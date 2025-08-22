@extends('doctor.master')

@section('title', 'Verify Email - FreeDoctor Doctor')

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 20px 0;">
    <div class="container mx-auto px-4">
        <div class="max-w-lg mx-auto">
            <!-- Back to Login Button -->
            <div class="mb-4">
                <a href="{{ route('doctor.login') }}" class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 text-white rounded-lg hover:bg-opacity-30 transition-all duration-200 backdrop-blur-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Login
                </a>
            </div>

            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-8 text-center">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-white">Doctor Email Verification</h1>
                    <p class="text-green-100 mt-2">Please verify your professional email address</p>
                </div>

                <!-- Content -->
                <div class="px-6 py-8">
                    @if (session('status') == 'verification-link-sent')
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <p class="text-green-800 font-medium">Verification email sent successfully!</p>
                            </div>
                            <p class="text-green-700 text-sm mt-1">A fresh verification link has been sent to your email address.</p>
                        </div>
                    @endif

                    <div class="text-center mb-6">
                        <p class="text-gray-700 mb-4 text-sm sm:text-base">
                            We've sent a verification email to:
                        </p>
                        <p class="font-semibold text-gray-800 bg-gray-50 px-4 py-3 rounded-lg text-sm sm:text-base break-all">
                            {{ auth('doctor')->user()->email }}
                        </p>
                    </div>

                    <div class="space-y-4">
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <h3 class="font-semibold text-green-800 mb-2 flex items-center">
                                <span class="text-lg mr-2">🩺</span>
                                Professional Verification Required
                            </h3>
                            <p class="text-green-700 text-sm">
                                Please check your email inbox and click the verification button to activate your doctor account.
                            </p>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h3 class="font-semibold text-blue-800 mb-2 flex items-center">
                                <span class="text-lg mr-2">📧</span>
                                Check Your Email
                            </h3>
                            <p class="text-blue-700 text-sm">
                                Look for an email from FreeDoctor with your verification link. This helps us ensure account security.
                            </p>
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <h3 class="font-semibold text-yellow-800 mb-2 flex items-center">
                                <span class="text-lg mr-2">🕐</span>
                                Email not received?
                            </h3>
                            <ul class="text-yellow-700 text-sm space-y-1">
                                <li>• Check your spam/junk folder</li>
                                <li>• Allow a few minutes for email delivery</li>
                                <li>• Ensure you used your professional email</li>
                                <li>• Request a new verification email below</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Resend Button -->
                    <form method="POST" action="{{ route('doctor.doctor.verification.send') }}" class="mt-6">
                        @csrf
                        <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 text-sm sm:text-base">
                            <span class="inline-flex items-center">
                                <span class="mr-2">🔄</span>
                                Send Another Verification Email
                            </span>
                        </button>
                    </form>

                    <!-- Support Info -->
                    <div class="mt-6 pt-6 border-t border-gray-200 text-center">
                        <p class="text-sm text-gray-600 mb-3">Need assistance?</p>
                        <div class="flex flex-col sm:flex-row justify-center items-center space-y-2 sm:space-y-0 sm:space-x-4 text-sm">
                            <a href="mailto:doctors@freedoctor.com" class="text-green-600 hover:text-green-800 font-medium flex items-center">
                                <span class="mr-1">📧</span>
                                Doctor Support
                            </a>
                            <span class="hidden sm:inline text-gray-300">|</span>
                            <a href="tel:+919876543210" class="text-green-600 hover:text-green-800 font-medium flex items-center">
                                <span class="mr-1">📞</span>
                                Call Support
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-gray-50 px-6 py-4 text-center">
                    <div class="flex items-center justify-center space-x-2 text-sm text-gray-600">
                        <div class="w-6 h-6 bg-gradient-to-r from-green-600 to-emerald-600 rounded text-white flex items-center justify-center text-xs font-bold">
                            F
                        </div>
                        <span>FreeDoctor - Professional Medical Camps </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-refresh status after resending email
document.querySelector('form').addEventListener('submit', function() {
    const button = this.querySelector('button');
    const originalContent = button.innerHTML;
    
    button.innerHTML = '<span class="inline-flex items-center"><span class="mr-2">⏳</span>Sending...</span>';
    button.disabled = true;
    
    setTimeout(() => {
        button.innerHTML = '<span class="inline-flex items-center"><span class="mr-2">✅</span>Email Sent! Check your inbox</span>';
        setTimeout(() => {
            button.innerHTML = originalContent;
            button.disabled = false;
        }, 3000);
    }, 1000);
});

// Mobile responsive adjustments
if (window.innerWidth < 640) {
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.querySelector('.max-w-lg');
        if (container) {
            container.classList.add('mx-4');
        }
    });
}
</script>
@endsection
