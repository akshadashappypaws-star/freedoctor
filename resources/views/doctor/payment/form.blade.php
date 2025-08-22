@extends('layouts.app')

@section('title', 'Doctor Registration Payment')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-900 via-purple-900 to-indigo-900 flex items-center justify-center p-4">
    <div class="max-w-2xl w-full">
        <!-- Payment Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-green-400 to-blue-500 rounded-full mb-4">
                <i class="fas fa-user-md text-3xl text-white"></i>
            </div>
            <h1 class="text-4xl font-bold text-white mb-2">Complete Your Registration</h1>
            <p class="text-gray-300 text-lg">One-time payment to activate your doctor account</p>
        </div>

        <!-- Payment Card -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Doctor Info Section -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-6 text-white">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-md text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold">{{ $doctor->doctor_name }}</h2>
                        <p class="text-blue-100">{{ $doctor->email }}</p>
                        <p class="text-blue-100 text-sm">{{ $doctor->phone ?? 'Phone not provided' }}</p>
                    </div>
                </div>
            </div>

            <!-- Payment Details -->
            <div class="p-8">
                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Payment Benefits -->
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-6">Registration Benefits</h3>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                    <i class="fas fa-check text-green-600 text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Verified Profile</h4>
                                    <p class="text-gray-600 text-sm">Get a verified badge on your profile to build patient trust</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                    <i class="fas fa-bullhorn text-green-600 text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Create Medical Camps</h4>
                                    <p class="text-gray-600 text-sm">Organize and manage free medical camps in your area</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                    <i class="fas fa-users text-green-600 text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Patient Management</h4>
                                    <p class="text-gray-600 text-sm">Access patient registration data and manage appointments</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                    <i class="fas fa-chart-line text-green-600 text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Analytics Dashboard</h4>
                                    <p class="text-gray-600 text-sm">Track your impact and campaign performance</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                    <i class="fas fa-support text-green-600 text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Priority Support</h4>
                                    <p class="text-gray-600 text-sm">Get dedicated support for your campaigns and technical issues</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Form -->
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-6">Payment Details</h3>
                        
                        <!-- Amount Display -->
                        <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-xl p-6 mb-6 border border-green-200">
                            <div class="text-center">
                                <p class="text-gray-600 mb-2">Registration Fee</p>
                                <div class="text-4xl font-bold text-green-600 mb-2">₹{{ number_format($subscriptionFee, 2) }}</div>
                                <p class="text-sm text-gray-500">One-time payment • Lifetime access</p>
                            </div>
                        </div>

                        <!-- Payment Form -->
                        <form action="{{ route('doctor.payment.create', $doctor->id) }}" method="POST" id="paymentForm">
                            @csrf
                            <input type="hidden" name="amount" value="{{ $subscriptionFee }}">
                            
                            <div class="space-y-4">
                                <!-- Terms and Conditions -->
                                <div class="flex items-start space-x-3">
                                    <input type="checkbox" id="terms" name="terms" required
                                           class="mt-1 w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="terms" class="text-sm text-gray-600">
                                        I agree to the <a href="#" class="text-blue-600 hover:underline">Terms and Conditions</a> 
                                        and <a href="#" class="text-blue-600 hover:underline">Privacy Policy</a>
                                    </label>
                                </div>

                                <!-- Payment Button -->
                                <button type="submit" 
                                        class="w-full bg-gradient-to-r from-green-500 to-blue-600 text-white font-bold py-4 px-6 rounded-xl hover:from-green-600 hover:to-blue-700 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl">
                                    <i class="fas fa-shield-alt mr-2"></i>
                                    Proceed to Secure Payment
                                </button>
                            </div>
                        </form>

                        <!-- Security Info -->
                        <div class="mt-6 text-center">
                            <div class="flex items-center justify-center space-x-4 text-gray-500 text-sm">
                                <div class="flex items-center">
                                    <i class="fas fa-lock mr-1"></i>
                                    <span>256-bit SSL</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-shield-alt mr-1"></i>
                                    <span>Razorpay Secured</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="mt-8 text-center">
            <p class="text-gray-300 text-sm">
                Questions about payment? <a href="mailto:support@freedoctor.com" class="text-blue-400 hover:underline">Contact Support</a>
            </p>
        </div>
    </div>
</div>

<style>
    .bg-gradient-to-br {
        background: linear-gradient(135deg, #1e3a8a 0%, #7c3aed 50%, #3730a3 100%);
    }
</style>
@endsection
