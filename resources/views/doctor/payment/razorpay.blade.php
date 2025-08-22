@extends('layouts.app')

@section('title', 'Complete Payment')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-900 via-purple-900 to-indigo-900 flex items-center justify-center p-4">
    <div class="max-w-lg w-full">
        <!-- Payment Processing Card -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-500 to-blue-600 p-6 text-white text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white bg-opacity-20 rounded-full mb-4">
                    <i class="fas fa-credit-card text-2xl"></i>
                </div>
                <h1 class="text-2xl font-bold">Complete Payment</h1>
                <p class="text-green-100">Secure payment with Razorpay</p>
            </div>

            <!-- Payment Details -->
            <div class="p-6">
                <div class="text-center mb-6">
                    <p class="text-gray-600 mb-2">Amount to Pay</p>
                    <div class="text-3xl font-bold text-green-600">₹{{ number_format($payment->amount, 2) }}</div>
                    <p class="text-sm text-gray-500">{{ $payment->description }}</p>
                </div>

                <!-- Doctor Info -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-gray-800 mb-2">Doctor Details</h3>
                    <p class="text-gray-600">{{ $doctor->doctor_name }}</p>
                    <p class="text-gray-500 text-sm">{{ $doctor->email }}</p>
                </div>

                <!-- Payment Button -->
                <button id="rzp-button" 
                        class="w-full bg-gradient-to-r from-green-500 to-blue-600 text-white font-bold py-4 px-6 rounded-xl hover:from-green-600 hover:to-blue-700 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <i class="fas fa-shield-alt mr-2"></i>
                    Pay with Razorpay
                </button>

                <!-- Loading State -->
                <div id="loading" class="hidden text-center py-4">
                    <div class="inline-flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processing payment...
                    </div>
                </div>

                <!-- Security Info -->
                <div class="mt-6 text-center">
                    <div class="flex items-center justify-center space-x-4 text-gray-500 text-sm">
                        <div class="flex items-center">
                            <i class="fas fa-lock mr-1"></i>
                            <span>256-bit SSL</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-shield-alt mr-1"></i>
                            <span>PCI Compliant</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Link -->
        <div class="mt-6 text-center">
            <a href="{{ route('doctor.register') }}" class="text-gray-300 hover:text-white text-sm">
                <i class="fas fa-arrow-left mr-1"></i> Back to Registration
            </a>
        </div>
    </div>
</div>

<!-- Razorpay Integration -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.getElementById('rzp-button').onclick = function(e) {
    e.preventDefault();
    
    // Show loading
    document.getElementById('rzp-button').style.display = 'none';
    document.getElementById('loading').classList.remove('hidden');
    
    var options = {
        "key": "{{ env('RAZORPAY_KEY_ID', 'rzp_test_1234567890') }}", // Your Razorpay Key ID
        "amount": {{ $payment->amount * 100 }}, // Amount in paisa
        "currency": "INR",
        "name": "FreeDoctor",
        "description": "{{ $payment->description }}",
        "order_id": "{{ $payment->order_id }}",
        "handler": function (response) {
            // Payment successful
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("doctor.payment.success", $payment->id) }}';
            
            // Add CSRF token
            var csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            // Add payment details
            var paymentId = document.createElement('input');
            paymentId.type = 'hidden';
            paymentId.name = 'razorpay_payment_id';
            paymentId.value = response.razorpay_payment_id;
            form.appendChild(paymentId);
            
            var orderId = document.createElement('input');
            orderId.type = 'hidden';
            orderId.name = 'razorpay_order_id';
            orderId.value = response.razorpay_order_id;
            form.appendChild(orderId);
            
            var signature = document.createElement('input');
            signature.type = 'hidden';
            signature.name = 'razorpay_signature';
            signature.value = response.razorpay_signature;
            form.appendChild(signature);
            
            document.body.appendChild(form);
            form.submit();
        },
        "modal": {
            "ondismiss": function() {
                // Payment cancelled
                document.getElementById('rzp-button').style.display = 'block';
                document.getElementById('loading').classList.add('hidden');
            }
        },
        "prefill": {
            "name": "{{ $doctor->doctor_name }}",
            "email": "{{ $doctor->email }}",
            "contact": "{{ $doctor->phone ?? '' }}"
        },
        "theme": {
            "color": "#10B981"
        }
    };
    
    var rzp = new Razorpay(options);
    rzp.open();
};
</script>
@endsection
