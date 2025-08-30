@extends('layouts.app')

@section('title', 'Complete Payment')

@section('content')
<!-- Back Arrow Button -->
<div class="back-arrow-container">
    <a href="{{ route('doctor.payment.form', $doctor->id) }}" class="back-arrow-btn">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m12 19-7-7 7-7"/>
            <path d="M19 12H5"/>
        </svg>
        Back to Payment
    </a>
</div>

<div class="login-card" role="main" aria-label="Payment Processing">
    <!-- Logo Section -->
    <div class="logo-section" style="text-align: center; margin-bottom: 25px;">
        <img src="{{ asset('storage/PngVectordeisgn.png') }}" alt="FreeDoctor Logo" style="height: 70px; width: auto; margin-bottom: 15px; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.1));" />
        <div style="display: flex; align-items: center; justify-content: center; gap: 10px;">
            <h2 style="color: #667eea; margin: 0; font-size: 24px; font-weight: 700; background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                FreeDoctor
            </h2>
        </div>
    </div>
    
    <h1 class="title">Complete Payment</h1>
    <p class="subtitle">Secure payment with Razorpay</p>

    <div class="tabs" role="tablist" aria-label="Payment Processing">
        <button class="tab active" role="tab" aria-selected="true" aria-controls="payment-panel" id="payment-tab" tabindex="0">Payment Gateway</button>
    </div>

    <!-- Amount Display -->
    <div style="background: linear-gradient(135deg, #e8f5e8, #f0f8f0); border-radius: 8px; padding: 25px; text-align: center; margin-bottom: 25px; border: 1px solid #d4edda;">
        <p style="margin: 0 0 8px 0; color: #666; font-size: 14px;">Amount to Pay</p>
        <div style="font-size: 32px; font-weight: bold; color: #28a745; margin-bottom: 8px;">₹{{ number_format($payment->amount, 2) }}</div>
        <p style="margin: 0; font-size: 13px; color: #666;">{{ $payment->description }}</p>
    </div>

    <!-- Doctor Info -->
    <div style="background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 8px; padding: 20px; margin-bottom: 25px; color: white;">
        <h3 style="margin: 0 0 10px 0; font-size: 16px; font-weight: 600;">Doctor Details</h3>
        <p style="margin: 0 0 5px 0; font-size: 15px; font-weight: 500;">{{ $doctor->doctor_name }}</p>
        <p style="margin: 0; opacity: 0.9; font-size: 13px;">{{ $doctor->email }}</p>
    </div>

    <!-- Payment Button -->
    <div class="row">
        <div class="col-12">
            <button id="rzp-button" class="btn btn-primary btn-block" style="background: linear-gradient(135deg, #28a745, #20c997); border: none; padding: 15px; font-weight: 600; font-size: 16px; position: relative;">
                <i class="fas fa-shield-alt" style="margin-right: 8px;"></i>
                Pay with Razorpay
            </button>
        </div>
    </div>

    <!-- Loading State -->
    <div id="loading" class="hidden" style="text-align: center; margin-top: 20px;">
        <div style="display: inline-flex; align-items: center; color: #666;">
            <svg class="animate-spin" style="width: 20px; height: 20px; margin-right: 8px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Processing payment...
        </div>
    </div>

    <!-- Security Info -->
    <div style="text-align: center; margin-top: 25px;">
        <div style="display: flex; align-items: center; justify-content: center; gap: 15px; color: #666; font-size: 12px;">
            <div style="display: flex; align-items: center; gap: 4px;">
                <i class="fas fa-lock"></i>
                <span>256-bit SSL</span>
            </div>
            <div style="display: flex; align-items: center; gap: 4px;">
                <i class="fas fa-shield-alt"></i>
                <span>PCI Compliant</span>
            </div>
        </div>
    </div>

    <!-- Support Link -->
    <p style="text-align: center; margin-top: 25px; font-size: 13px; color: #666;">
        Need help? <a href="mailto:support@freedoctor.com" style="color: #667eea;">Contact Support</a>
    </p>
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
        "key": "{{ env('RAZORPAY_KEY_ID') }}", 
        "amount": {{ $payment->amount * 100 }}, 
        "currency": "INR",
        "name": "FreeDoctor",
        "description": "{{ $payment->description }}",
        "order_id": "{{ $payment->order_id }}",
        "handler": function (response) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("doctor.payment.success", $payment->id) }}';
            
            var csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
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
                document.getElementById('rzp-button').style.display = 'block';
                document.getElementById('loading').classList.add('hidden');
                
                // Show message that user can try again
                if (confirm('Payment was cancelled. Would you like to try again?')) {
                    // User wants to try again, keep them on the page
                    return;
                } else {
                    // User wants to go back to payment form
                    window.location.href = '{{ route("doctor.payment.form", $doctor->id) }}';
                }
            }
        },
        "prefill": {
            "name": "{{ $doctor->doctor_name }}",
            "email": "{{ $doctor->email }}",
            "contact": "{{ $doctor->phone ?? '' }}"
        },
        "theme": {
            "color": "#667eea"
        }
    };
    
    var rzp = new Razorpay(options);
    
    // Add error handling
    rzp.on('payment.failed', function (response) {
        console.error('Payment failed:', response.error);
        
        // Create form to submit failure data
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("doctor.payment.failure", $payment->id) }}';
        
        var csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        var errorCode = document.createElement('input');
        errorCode.type = 'hidden';
        errorCode.name = 'error_code';
        errorCode.value = response.error.code;
        form.appendChild(errorCode);
        
        var errorDescription = document.createElement('input');
        errorDescription.type = 'hidden';
        errorDescription.name = 'error_description';
        errorDescription.value = response.error.description;
        form.appendChild(errorDescription);
        
        document.body.appendChild(form);
        form.submit();
    });
    
    rzp.open();
};
</script>
@endsection
