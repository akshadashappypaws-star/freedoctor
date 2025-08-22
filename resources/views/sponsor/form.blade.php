<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sponsor Campaign - {{ $campaign ? $campaign->title : 'FreedDoctor' }}</title>
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
                <nav class="hidden md:flex space-x-6">
                    <a href="{{ route('user.dashboard') }}" class="text-gray-600 hover:text-blue-600 transition">
                        <i class="fas fa-home mr-2"></i>Home
                    </a>
                    <a href="#" class="text-blue-600 font-semibold">
                        <i class="fas fa-hands-helping mr-2"></i>Sponsor
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-6 py-12">
        <div class="max-w-6xl mx-auto">
            <!-- Page Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-hands-helping text-blue-600 mr-3"></i>
                    Sponsor a Healthcare Campaign
                </h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Your generous contribution will help provide free healthcare services to those in need. 
                    Together, we can make a difference in people's lives.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Campaign Details -->
                @if($campaign)
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="relative">
                        @if($campaign->thumbnail)
                            <div class="h-64 bg-gradient-to-r from-blue-500 to-green-500 relative overflow-hidden">
                                <img src="{{ asset('storage/' . $campaign->thumbnail) }}" 
                                     alt="{{ $campaign->title }}" 
                                     class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black bg-opacity-30"></div>
                                <div class="absolute bottom-4 left-4 text-white">
                                    <h2 class="text-2xl font-bold">{{ $campaign->title }}</h2>
                                    <p class="text-sm opacity-90">
                                        <i class="fas fa-map-marker-alt mr-2"></i>{{ $campaign->location }}
                                    </p>
                                </div>
                            </div>
                        @elseif($campaign->images && count($campaign->images) > 0)
                            <div class="h-64 bg-gradient-to-r from-blue-500 to-green-500 relative overflow-hidden">
                                <img src="{{ asset('storage/' . $campaign->images[0]) }}" 
                                     alt="{{ $campaign->title }}" 
                                     class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black bg-opacity-30"></div>
                                <div class="absolute bottom-4 left-4 text-white">
                                    <h2 class="text-2xl font-bold">{{ $campaign->title }}</h2>
                                    <p class="text-sm opacity-90">
                                        <i class="fas fa-map-marker-alt mr-2"></i>{{ $campaign->location }}
                                    </p>
                                </div>
                            </div>
                        @else
                            <div class="h-64 bg-gradient-to-r from-blue-500 to-green-500 flex items-center justify-center">
                                <div class="text-center text-white">
                                    <i class="fas fa-hospital text-6xl mb-4"></i>
                                    <h2 class="text-2xl font-bold">{{ $campaign->title }}</h2>
                                    <p class="text-sm opacity-90">
                                        <i class="fas fa-map-marker-alt mr-2"></i>{{ $campaign->location }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="p-8">
                        <div class="space-y-6">
                            <!-- Campaign Info -->
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-calendar text-blue-600 mr-2"></i>
                                        <span class="font-semibold text-gray-700">Duration</span>
                                    </div>
                                    <p class="text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($campaign->start_date)->format('M j') }} - 
                                        {{ \Carbon\Carbon::parse($campaign->end_date)->format('M j, Y') }}
                                    </p>
                                </div>
                                <div class="bg-green-50 p-4 rounded-lg">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-user-md text-green-600 mr-2"></i>
                                        <span class="font-semibold text-gray-700">Doctor</span>
                                    </div>
                                    <p class="text-sm text-gray-600">Dr. {{ $campaign->doctor->doctor_name }}</p>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="bg-gray-50 p-6 rounded-lg">
                                <h3 class="font-semibold text-gray-800 mb-3">
                                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>About This Campaign
                                </h3>
                                <p class="text-gray-600 leading-relaxed">{{ $campaign->description }}</p>
                            </div>

                            <!-- Campaign Stats -->
                            <div class="grid grid-cols-2 gap-4">
                                <div class="text-center p-4 bg-yellow-50 rounded-lg">
                                    <div class="text-2xl font-bold text-yellow-600">{{ $campaign->expected_patients }}</div>
                                    <div class="text-sm text-gray-600">Expected Patients</div>
                                </div>
                                <div class="text-center p-4 bg-purple-50 rounded-lg">
                                    <div class="text-2xl font-bold text-purple-600">
                                        @if($campaign->camp_type === 'medical')
                                            <i class="fas fa-stethoscope"></i> Medical
                                        @elseif($campaign->camp_type === 'surgical')
                                            <i class="fas fa-user-md"></i> Surgical
                                        @else
                                            {{ ucfirst($campaign->camp_type) }}
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-600">Campaign Type</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Sponsor Form -->
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-800 mb-3">
                            <i class="fas fa-heart text-red-500 mr-3"></i>
                            Become a Sponsor
                        </h2>
                        <p class="text-gray-600">Fill in your details to sponsor this healthcare campaign</p>
                    </div>

                    <form id="sponsorForm" class="space-y-6">
                        @csrf
                        @if($campaign)
                            <input type="hidden" name="campaign_id" value="{{ $campaign->id }}">
                        @endif

                        <!-- Personal Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-user text-blue-500 mr-2"></i>Full Name *
                                </label>
                                <input type="text" name="name" id="sponsorName" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-phone text-green-500 mr-2"></i>Phone Number *
                                </label>
                                <input type="tel" name="phone_number" id="sponsorPhone" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>
                        </div>

                        <!-- Address -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>Address *
                            </label>
                            <textarea name="address" id="sponsorAddress" required rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                placeholder="Enter your complete address"></textarea>
                        </div>

                        <!-- Message -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-comment-alt text-purple-500 mr-2"></i>Message (Optional)
                            </label>
                            <textarea name="message" id="sponsorMessage" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                placeholder="Any message or special instructions..."></textarea>
                        </div>

                        <!-- Amount -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-rupee-sign text-yellow-500 mr-2"></i>Sponsorship Amount *
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-3 text-gray-500">₹</span>
                                <input type="number" name="amount" id="sponsorAmount" required min="1" step="0.01"
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                    placeholder="Enter amount">
                            </div>
                            <div class="mt-2 flex flex-wrap gap-2">
                                <button type="button" class="amount-btn px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition" data-amount="500">₹500</button>
                                <button type="button" class="amount-btn px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition" data-amount="1000">₹1,000</button>
                                <button type="button" class="amount-btn px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition" data-amount="2500">₹2,500</button>
                                <button type="button" class="amount-btn px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition" data-amount="5000">₹5,000</button>
                            </div>
                        </div>

                        @if($campaign)
                        <!-- Campaign Name Display -->
                        <div class="bg-gradient-to-r from-blue-50 to-green-50 p-4 rounded-lg border border-blue-200">
                            <div class="flex items-center">
                                <i class="fas fa-hospital text-blue-600 mr-3"></i>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Sponsoring Campaign:</span>
                                    <div class="font-bold text-gray-800">{{ $campaign->title }}</div>
                                    <div class="text-sm text-gray-600">{{ $campaign->location }}</div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Submit Button -->
                        <div class="pt-4">
                            <button type="submit" id="proceedToPayment" 
                                class="w-full bg-gradient-to-r from-blue-600 to-green-600 text-white py-4 rounded-lg font-semibold text-lg hover:from-blue-700 hover:to-green-700 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl">
                                <i class="fas fa-credit-card mr-3"></i>Proceed to Payment
                            </button>
                        </div>
                    </form>
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

            // Quick amount selection
            $('.amount-btn').on('click', function() {
                const amount = $(this).data('amount');
                $('#sponsorAmount').val(amount);
                $('.amount-btn').removeClass('bg-blue-500 text-white').addClass('bg-blue-100 text-blue-700');
                $(this).removeClass('bg-blue-100 text-blue-700').addClass('bg-blue-500 text-white');
            });

            // Form submission
            $('#sponsorForm').on('submit', function(e) {
                e.preventDefault();
                
                // Validate form
                const name = $('#sponsorName').val().trim();
                const phone = $('#sponsorPhone').val().trim();
                const address = $('#sponsorAddress').val().trim();
                const amount = parseFloat($('#sponsorAmount').val());
                
                if (!name || !phone || !address || !amount || amount <= 0) {
                    Swal.fire('Error', 'Please fill in all required fields with valid data.', 'error');
                    return;
                }
                
                // Initiate Razorpay payment
                const options = {
                    key: 'rzp_test_your_key_here', // Replace with actual Razorpay key
                    amount: amount * 100, // Amount in paise
                    currency: 'INR',
                    name: 'FreedDoctor',
                    description: 'Campaign Sponsorship - {{ $campaign ? $campaign->title : "Healthcare Campaign" }}',
                    prefill: {
                        name: name,
                        contact: phone
                    },
                    notes: {
                        campaign_id: '{{ $campaign ? $campaign->id : "" }}',
                        sponsor_name: name,
                        sponsor_address: address
                    },
                    theme: {
                        color: '#3B82F6'
                    },
                    handler: function(response) {
                        // Payment successful, submit form with payment details
                        const formData = new FormData(document.getElementById('sponsorForm'));
                        formData.append('payment_id', response.razorpay_payment_id);
                        formData.append('payment_status', 'success');
                        
                        const submitBtn = $('#proceedToPayment');
                        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Processing...');
                        
                        $.ajax({
                            url: '/sponsor/store',
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Thank You!',
                                        text: 'Your sponsorship payment was successful. Thank you for supporting healthcare!',
                                        confirmButtonColor: '#3B82F6'
                                    }).then(() => {
                                        window.location.href = response.redirect_url || '/';
                                    });
                                } else {
                                    Swal.fire('Error', response.message || 'Something went wrong after payment.', 'error');
                                }
                                submitBtn.prop('disabled', false).html('<i class="fas fa-credit-card mr-3"></i>Proceed to Payment');
                            },
                            error: function(xhr) {
                                submitBtn.prop('disabled', false).html('<i class="fas fa-credit-card mr-3"></i>Proceed to Payment');
                                Swal.fire('Error', 'Payment was successful but there was an error saving your sponsorship. Please contact support.', 'error');
                            }
                        });
                    },
                    modal: {
                        ondismiss: function() {
                            console.log('Payment cancelled by user');
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
