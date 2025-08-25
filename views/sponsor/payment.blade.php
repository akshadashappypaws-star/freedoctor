<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - Sponsor {{ $sponsor->campaign->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-green-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-lg">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-r from-blue-600 to-green-600 text-white rounded-full p-3">
                        <i class="fas fa-heart text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">FreedDoctor</h1>
                        <p class="text-sm text-gray-600">Healthcare for Everyone</p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-6 py-12">
        <div class="max-w-4xl mx-auto">
            <!-- Page Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-credit-card text-blue-600 mr-3"></i>
                    Complete Your Payment
                </h1>
                <p class="text-xl text-gray-600">
                    You're just one step away from making a difference!
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Payment Summary -->
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-file-invoice text-blue-600 mr-3"></i>
                        Payment Summary
                    </h2>

                    <!-- Sponsor Details -->
                    <div class="space-y-4 mb-8">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-blue-800 mb-2">Sponsor Information</h3>
                            <div class="space-y-2 text-sm">
                                <p><span class="font-medium">Name:</span> {{ $sponsor->name }}</p>
                                <p><span class="font-medium">Phone:</span> {{ $sponsor->phone_number }}</p>
                                <p><span class="font-medium">Address:</span> {{ $sponsor->address }}</p>
                            </div>
                        </div>

                        <div class="bg-green-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-green-800 mb-2">Campaign Details</h3>
                            <div class="space-y-2 text-sm">
                                <p><span class="font-medium">Campaign:</span> {{ $sponsor->campaign->title }}</p>
                                <p><span class="font-medium">Location:</span> {{ $sponsor->campaign->location }}</p>
                                <p><span class="font-medium">Doctor:</span> Dr. {{ $sponsor->campaign->doctor->doctor_name }}</p>
                            </div>
                        </div>

                        @if($sponsor->message)
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-purple-800 mb-2">Your Message</h3>
                            <p class="text-sm text-gray-700">{{ $sponsor->message }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Amount Breakdown -->
                    <div class="border-t pt-6">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-lg font-medium text-gray-700">Sponsorship Amount</span>
                            <span class="text-2xl font-bold text-green-600">₹{{ number_format($sponsor->amount, 2) }}</span>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <p class="text-sm text-yellow-800">
                                <i class="fas fa-info-circle mr-2"></i>
                                This amount will be used to provide free healthcare services to those in need.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Payment Options -->
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        <i class="fas fa-payment text-green-600 mr-3"></i>
                        Payment Method
                    </h2>

                    <!-- Payment Method Selection -->
                    <div class="space-y-4 mb-8">
                        <div class="border-2 border-blue-200 rounded-lg p-4 bg-blue-50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="bg-blue-600 text-white rounded-full p-3 mr-4">
                                        <i class="fas fa-bolt text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-800">Razorpay</h3>
                                        <p class="text-sm text-gray-600">Pay with UPI, Card, Netbanking & more</p>
                                    </div>
                                </div>
                                <div class="text-blue-600 font-semibold">Secure</div>
                            </div>
                        </div>
                    </div>

                    <!-- Security Features -->
                    <div class="grid grid-cols-2 gap-4 mb-8">
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <i class="fas fa-shield-alt text-green-600 text-2xl mb-2"></i>
                            <div class="text-sm font-medium text-gray-700">SSL Encrypted</div>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <i class="fas fa-lock text-blue-600 text-2xl mb-2"></i>
                            <div class="text-sm font-medium text-gray-700">100% Secure</div>
                        </div>
                    </div>

                    <!-- Pay Now Button -->
                    <button id="payNowBtn" 
                        class="w-full bg-gradient-to-r from-green-600 to-blue-600 text-white py-4 rounded-lg font-semibold text-lg hover:from-green-700 hover:to-blue-700 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl">
                        <i class="fas fa-credit-card mr-3"></i>
                        Pay ₹{{ number_format($sponsor->amount, 2) }} Now
                    </button>

                    <div class="mt-6 text-center">
                        <p class="text-xs text-gray-500">
                            By proceeding, you agree to our terms and conditions. 
                            Your payment is processed securely by Razorpay.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-16">
        <div class="container mx-auto px-6 text-center">
            <div class="flex items-center justify-center mb-4">
                <div class="bg-gradient-to-r from-blue-600 to-green-600 text-white rounded-full p-3 mr-4">
                    <i class="fas fa-heart text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold">FreedDoctor</h3>
                    <p class="text-sm text-gray-400">Healthcare for Everyone</p>
                </div>
            </div>
            <p class="text-gray-400">
                Thank you for your generous support in making healthcare accessible to all.
            </p>
        </div>
    </footer>

    <script>
        $(document).ready(function() {
            // CSRF Token Setup
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Razorpay Payment
            $('#payNowBtn').on('click', function() {
                const amount = {!! $sponsor->amount * 100 !!}; // Convert to paise
                
                const options = {
                    key: "{!! env('RAZORPAY_KEY_ID', 'rzp_test_1234567890') !!}", // Your Razorpay Key ID
                    amount: amount,
                    currency: "INR",
                    name: "FreedDoctor",
                    description: "Sponsorship for {!! json_encode($sponsor->campaign->title) !!}",
                    image: "{!! asset('images/logo.png') !!}",
                    order_id: "", // This should be generated from backend
                    handler: function (response) {
                        // Payment successful
                        const paymentData = {
                            payment_id: response.razorpay_payment_id,
                            order_id: response.razorpay_order_id,
                            signature: response.razorpay_signature
                        };
                        
                        $.ajax({
                            url: '/sponsor/payment-success/{!! $sponsor->id !!}',
                            method: 'POST',
                            data: paymentData,
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Payment Successful!',
                                        text: response.message,
                                        confirmButtonText: 'Continue'
                                    }).then(() => {
                                        window.location.href = response.redirect_url;
                                    });
                                }
                            },
                            error: function() {
                                Swal.fire('Error', 'Payment verification failed. Please contact support.', 'error');
                            }
                        });
                    },
                    prefill: {
                        name: "{!! json_encode($sponsor->name) !!}",
                        contact: "{!! $sponsor->phone_number !!}"
                    },
                    notes: {
                        sponsor_id: "{!! $sponsor->id !!}",
                        campaign_id: "{!! $sponsor->campaign_id !!}"
                    },
                    theme: {
                        color: "#3B82F6"
                    },
                    modal: {
                        ondismiss: function() {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Payment Cancelled',
                                text: 'Your payment was cancelled. You can try again anytime.',
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                };

                const rzp = new Razorpay(options);
                rzp.open();
            });
        });
    </script>
</body>
</html>
