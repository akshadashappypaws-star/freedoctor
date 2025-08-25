@extends('layouts.app')

@section('title', 'Payment Successful')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-900 via-blue-900 to-indigo-900 flex items-center justify-center p-4">
    <div class="max-w-2xl w-full">
        <!-- Success Animation -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-green-500 rounded-full mb-6 animate-pulse">
                <i class="fas fa-check text-4xl text-white"></i>
            </div>
            <h1 class="text-4xl font-bold text-white mb-2">Payment Successful!</h1>
            <p class="text-gray-300 text-lg">Your doctor registration has been processed</p>
        </div>

        <!-- Success Card -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-500 to-blue-600 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold">Registration Complete</h2>
                        <p class="text-green-100">Welcome to FreeDoctor Platform</p>
                    </div>
                    <div class="text-right">
                        <p class="text-green-100 text-sm">Payment ID</p>
                        <p class="text-white font-mono text-sm">{{ $payment->payment_id ?? 'Processing...' }}</p>
                    </div>
                </div>
            </div>

            <!-- Payment Details -->
            <div class="p-8">
                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Payment Summary -->
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Payment Summary</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Amount Paid</span>
                                <span class="font-semibold text-green-600">₹{{ number_format($payment->amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Payment Date</span>
                                <span class="font-semibold">{{ $payment->payment_date ? $payment->payment_date->format('M d, Y H:i') : 'Just now' }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">Status</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    {{ ucfirst($payment->payment_status) }}
                                </span>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="text-gray-600">Receipt Number</span>
                                <span class="font-semibold">{{ $payment->receipt_number }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Next Steps -->
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-4">What's Next?</h3>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                    <span class="text-blue-600 font-bold text-sm">1</span>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Admin Review</h4>
                                    <p class="text-gray-600 text-sm">Our team will review your profile and payment within 24 hours</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                    <span class="text-blue-600 font-bold text-sm">2</span>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Account Activation</h4>
                                    <p class="text-gray-600 text-sm">You'll receive an email once your account is approved</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                    <span class="text-blue-600 font-bold text-sm">3</span>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Start Creating Camps</h4>
                                    <p class="text-gray-600 text-sm">Access your dashboard and begin organizing medical camps</p>
                                </div>
                            </div>
                        </div>

                        <!-- Email Notification -->
                        <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-blue-600 mr-2"></i>
                                <span class="text-blue-800 text-sm font-medium">Confirmation email sent to {{ $payment->doctor->email }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('doctor.login') }}" 
                       class="flex-1 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-bold py-3 px-6 rounded-xl hover:from-blue-600 hover:to-purple-700 transition-all duration-300 text-center">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Go to Login
                    </a>
                    
                    <button onclick="window.print()" 
                            class="flex-1 bg-gray-600 text-white font-bold py-3 px-6 rounded-xl hover:bg-gray-700 transition-all duration-300">
                        <i class="fas fa-print mr-2"></i>
                        Print Receipt
                    </button>
                </div>
            </div>
        </div>

        <!-- Support Info -->
        <div class="mt-8 text-center">
            <p class="text-gray-300 text-sm mb-2">
                Need help? Contact our support team
            </p>
            <div class="flex items-center justify-center space-x-6 text-gray-400 text-sm">
                <a href="mailto:support@freedoctor.com" class="hover:text-white">
                    <i class="fas fa-envelope mr-1"></i>
                    support@freedoctor.com
                </a>
                <a href="tel:+911234567890" class="hover:text-white">
                    <i class="fas fa-phone mr-1"></i>
                    +91 12345 67890
                </a>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    .bg-white, .bg-white * {
        visibility: visible;
    }
    .bg-white {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
}
</style>
@endsection
