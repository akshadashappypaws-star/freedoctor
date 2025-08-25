@extends('../admin.dashboard')

@push('styles')
<style>
    .glass-effect {
        background: rgba(30, 41, 59, 0.15);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(148, 163, 184, 0.2);
    }
    p

    .stat-card:hover {
        transform: translateY(-2px) scale(1.02);
        background: rgba(30, 41, 59, 0.25);
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: rgba(30, 41, 59, 0.1);
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb {
        background: rgba(59, 130, 246, 0.3);
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: rgba(59, 130, 246, 0.5);
    }

    .image-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .image-gallery img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 8px;
        transition: transform 0.3s ease;
    }

    .image-gallery img:hover {
        transform: scale(1.05);
    }

    .register-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .register-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
    }

    .register-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .register-btn:hover::before {
        left: 100%;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(8px);
    }

    .modal-content {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        margin: 5% auto;
        padding: 0;
        border-radius: 16px;
        width: 90%;
        max-width: 600px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
        border: 1px solid rgba(148, 163, 184, 0.2);
    }

    .modal-header {
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        padding: 1.5rem;
        border-radius: 16px 16px 0 0;
        border-bottom: 1px solid rgba(148, 163, 184, 0.2);
    }

    .close {
        color: #fff;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        transition: color 0.3s;
    }

    .close:hover {
        color: #f87171;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        background: rgba(30, 41, 59, 0.5);
        border: 1px solid rgba(148, 163, 184, 0.3);
        border-radius: 8px;
        color: white;
        font-size: 0.875rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        background: rgba(30, 41, 59, 0.7);
    }

    .form-control::placeholder {
        color: rgba(148, 163, 184, 0.7);
    }

    .payment-info {
        background: linear-gradient(135deg, #059669, #047857);
        padding: 1rem;
        border-radius: 8px;
        margin: 1rem 0;
        border: 1px solid rgba(5, 150, 105, 0.3);
    }

    .free-badge {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    @media (max-width: 768px) {
        .modal-content {
            width: 95%;
            margin: 10% auto;
        }

        .grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('title', 'Campaign Details - FreeDoctor')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 p-4 md:p-6 space-y-6 md:space-y-8">

    <!-- Campaign Header -->
    <div class="glass-effect rounded-xl p-4 md:p-8 border border-slate-600/30 backdrop-blur-sm">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="flex flex-col md:flex-row items-start md:items-center gap-4 w-full lg:w-auto">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-2xl flex-shrink-0">
                    <i class="fas fa-calendar-alt text-2xl text-white"></i>
                </div>
                <div class="flex-1">
                    <h1 class="text-2xl md:text-4xl font-bold text-white">{{ $campaign->title }}</h1>
                    <p class="text-slate-300 mt-2 text-base md:text-lg flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-red-400"></i>{{ $campaign->location }}
                    </p>
                    <div class="flex flex-wrap items-center gap-2 md:gap-4 mt-2">
                        @php
                        $statusClass = match($campaign->approval_status) {
                        'approved' => 'bg-green-500',
                        'pending' => 'bg-yellow-500',
                        'rejected' => 'bg-red-500',
                        default => 'bg-gray-500'
                        };
                        @endphp
                        <span class="{{ $statusClass }} text-white px-3 py-1 rounded-full text-sm font-medium">
                            {{ ucfirst($campaign->approval_status ?? 'pending') }}
                        </span>

                        <!-- Admin Action Buttons -->
                        @if($campaign->approval_status !== 'approved')
                        <button onclick="approveCampaign({{ $campaign->id }})" 
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-1 rounded-full text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-check"></i>
                            Approve
                        </button>
                        @endif
                        
                        @if($campaign->approval_status !== 'rejected')
                        <button onclick="rejectCampaign({{ $campaign->id }})" 
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-1 rounded-full text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-times"></i>
                            Reject
                        </button>
                        @endif

                        @if($campaign->camp_type === 'medical')
                        <span class="bg-blue-500/20 text-blue-300 px-3 py-1 rounded-full text-sm border border-blue-500/30">
                            <i class="fas fa-stethoscope mr-1"></i>Medical Camp
                        </span>
                        @elseif($campaign->camp_type === 'surgical')
                        <span class="bg-purple-500/20 text-purple-300 px-3 py-1 rounded-full text-sm border border-purple-500/30">
                            <i class="fas fa-user-md mr-1"></i>Surgical Camp
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-4 w-full lg:w-auto">
                <div class="flex items-center gap-4">
                    <div class="text-center">
                        <div class="text-blue-400 text-sm">Start Date</div>
                        <div class="text-white font-semibold">{{ \Carbon\Carbon::parse($campaign->start_date)->format('M j, Y') }}</div>
                    </div>
                    <div class="w-px h-12 bg-slate-600"></div>
                    <div class="text-center">
                        <div class="text-red-400 text-sm">End Date</div>
                        <div class="text-white font-semibold">{{ \Carbon\Carbon::parse($campaign->end_date)->format('M j, Y') }}</div>
                    </div>
                </div>

                <!-- Register Button -->
                <button onclick="handleRegistration({{ $campaign->id }}, {{ $campaign->registration_payment ?? 0 }})" class="register-btn text-white px-6 py-3 rounded-lg font-semibold flex items-center gap-2 transform hover:scale-105 transition-all duration-300 shadow-lg">
                    <i class="fas fa-user-plus"></i>
                    Register Now
                </button>
            </div>
        </div>
    </div>



    <!-- Campaign Details Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 md:gap-8">
        <!-- Main Content -->
        <div class="xl:col-span-2 space-y-6">

            <!-- Campaign Information -->
            <div class="glass-effect rounded-xl p-4 md:p-6 border border-slate-600/30 backdrop-blur-sm">
                <h2 class="text-lg md:text-xl font-bold text-white mb-4 md:mb-6 flex items-center gap-3">
                    <i class="fas fa-info-circle text-blue-400"></i>
                    Campaign Information
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                    <div class="bg-slate-800/50 p-4 rounded-lg border border-slate-600/20">
                        <label class="text-slate-400 text-sm block mb-2"><i class="fas fa-map-marker-alt mr-2"></i>Location</label>
                        <div class="text-white font-medium">{{ $campaign->location }}</div>
                    </div>

                    <div class="bg-slate-800/50 p-4 rounded-lg border border-slate-600/20">
                        <label class="text-slate-400 text-sm block mb-2"><i class="fas fa-calendar mr-2"></i>Duration</label>
                        <div class="text-white font-medium">
                            {{ \Carbon\Carbon::parse($campaign->start_date)->format('M j') }} - {{ \Carbon\Carbon::parse($campaign->end_date)->format('M j, Y') }}
                        </div>
                    </div>

                    <div class="bg-slate-800/50 p-4 rounded-lg border border-slate-600/20">
                        <label class="text-slate-400 text-sm block mb-2"><i class="fas fa-clock mr-2"></i>Timings</label>
                        <div class="text-white font-medium">{{ $campaign->timings }}</div>
                    </div>

                    <div class="bg-slate-800/50 p-4 rounded-lg border border-slate-600/20">
                        <label class="text-slate-400 text-sm block mb-2"><i class="fas fa-users mr-2"></i>Expected Patients</label>
                        <div class="text-white font-medium">{{ $campaign->expected_patients }}</div>
                    </div>

                    <div class="bg-slate-800/50 p-4 rounded-lg border border-slate-600/20">
                        <label class="text-slate-400 text-sm block mb-2"><i class="fas fa-rupee-sign mr-2"></i>Registration Fee</label>
                        <div class="text-white font-medium">
                            @if($campaign->registration_payment && $campaign->registration_payment > 0)
                            ₹{{ number_format($campaign->registration_payment) }}
                            @else
                            <span class="free-badge">
                                <i class="fas fa-gift"></i>Free
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="bg-slate-800/50 p-4 rounded-lg border border-slate-600/20">
                        <label class="text-slate-400 text-sm block mb-2"><i class="fas fa-phone mr-2"></i>Contact</label>
                        <div class="text-white font-medium">{{ $campaign->contact_number }}</div>
                    </div>

                    @php
                    $specializations = is_string($campaign->specializations)
                    ? json_decode($campaign->specializations, true)
                    : $campaign->specializations;
                    @endphp

                    @if($specializations)
                    <div class="bg-slate-800/50 p-4 rounded-lg border border-slate-600/20 md:col-span-2">
                        <label class="text-slate-400 text-sm block mb-2">
                            <i class="fas fa-stethoscope mr-2"></i>Specializations
                        </label>
                        <div class="text-white font-medium">
                            {{ is_array($specializations) ? implode(', ', array_column($specializations, 'name')) : 'N/A' }}
                        </div>
                    </div>
                    @endif

                </div>

                @if($campaign->description)
                <div class="mt-6">
                    <label class="text-slate-400 text-sm block mb-3"><i class="fas fa-align-left mr-2"></i>Description</label>
                    <div class="bg-slate-800/50 p-4 rounded-lg border border-slate-600/20">
                        <p class="text-white leading-relaxed">{{ $campaign->description }}</p>
                    </div>
                </div>
                @endif

                @if($campaign->service_in_camp)
                <div class="mt-6">
                    <label class="text-slate-400 text-sm block mb-3"><i class="fas fa-medical-kit mr-2"></i>Services in Camp</label>
                    <div class="bg-slate-800/50 p-4 rounded-lg border border-slate-600/20">
                        <p class="text-white leading-relaxed">{{ $campaign->service_in_camp }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Thumbnail + Video Side-by-Side -->


            <!-- Video (full width) -->
            @if($campaign->video && Storage::exists('public/' . $campaign->video))
            <div class="mb-6">
                <h2 class="text-lg md:text-xl font-bold text-white mb-4 flex items-center gap-3">
                    <i class="fas fa-video text-red-400"></i>
                    Campaign Video
                </h2>
                <div class="aspect-video rounded-lg overflow-hidden">
                    <video controls class="w-full h-full object-cover">
                        <source src="{{ asset('storage/' . $campaign->video) }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
            @endif


            <!-- Campaign Images Gallery -->
            @if($campaign->images)
            @php
            $images = is_string($campaign->images) ? json_decode($campaign->images, true) : $campaign->images;
            @endphp
            @if(is_array($images) && count($images) > 0)
            <div class="glass-effect rounded-xl p-4 md:p-6 border border-slate-600/30 backdrop-blur-sm">
                <h2 class="text-lg md:text-xl font-bold text-white mb-4 md:mb-6 flex items-center gap-3">
                    <i class="fas fa-images text-green-400"></i>
                    Campaign Images
                </h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    @foreach($images as $image)
                    <img src="{{ asset('storage/' . $image) }}"
                        alt="Campaign Image"
                        class="w-full h-auto rounded-lg cursor-pointer hover:scale-105 transition-transform duration-200"
                        onclick="openImageModal(this.src)">
                    @endforeach
                </div>
            </div>
            @endif
            @endif

        </div>

        <!-- Sidebar -->
        <div class="space-y-6">


            <!-- Campaign Timeline -->
            <div class="glass-effect rounded-xl p-4 md:p-6 border border-slate-600/30 backdrop-blur-sm">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-chart-bar text-green-400"></i>
                    Campaign Info
                </h3>

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Created:</span>
                        <span class="text-white text-sm">{{ \Carbon\Carbon::parse($campaign->created_at)->diffForHumans() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Last Updated:</span>
                        <span class="text-white text-sm">{{ \Carbon\Carbon::parse($campaign->updated_at)->diffForHumans() }}</span>
                    </div>
                    @if($campaign->thumbnail)
                    <div>
                        <span class="text-slate-400 block mb-2">Thumbnail:</span>
                        <div class="w-full aspect-square max-h-[400px] overflow-hidden rounded-lg">
                            <img src="{{ asset('storage/' . $campaign->thumbnail) }}"
                                alt="Thumbnail"
                                class="w-full h-full object-cover">
                        </div>
                    </div>
                    @endif

                </div>
            </div>

            <!-- Registration Summary -->
            <div class="glass-effect rounded-xl p-4 md:p-6 border border-slate-600/30 backdrop-blur-sm">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-clipboard-list text-purple-400"></i>
                    Registration Summary
                </h3>

                <div class="space-y-3">
                    <div class="bg-slate-800/50 p-3 rounded-lg">
                        <div class="text-slate-400 text-sm">Fee Type</div>
                        <div class="text-white font-semibold">
                            @if($campaign->registration_payment && $campaign->registration_payment > 0)
                            Paid (₹{{ number_format($campaign->registration_payment) }})
                            @else
                            Free Registration
                            @endif
                        </div>
                    </div>
                    <div class="bg-slate-800/50 p-3 rounded-lg">
                        <div class="text-slate-400 text-sm">Registration Status</div>
                        <div class="text-green-400 font-semibold">Open</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Registration Modal -->
<div id="registrationModal" class="modal"style="margin-top:0!important;">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                <i class="fas fa-user-plus"></i>
                Register for Campaign
            </h2>
            <span class="close">&times;</span>
        </div>
        <div class="p-6">
           <form id="registrationForm">
    @csrf
    <input type="hidden" name="campaign_id" id="campaignId" value="">
  <input
    type="hidden"
    name="user_id"
    id="userId"
    value="{{ auth('user')->id() }}"
  />
<input type="hidden" name="amount" id="amount">
    <!-- Row 1: Name and Email -->
    <div class="flex flex-col md:flex-row gap-4 mb-4">
        <div class="w-full md:w-1/2">
            <label class="text-slate-300 text-sm font-medium mb-2 block">
                <i class="fas fa-user mr-2"></i>Full Name *
            </label>
            <input type="text"style="color:white;" name="name" class="form-control w-full" placeholder="Enter your full name" required>
        </div>
        <div class="w-full md:w-1/2">
            <label class="text-slate-300 text-sm font-medium mb-2 block">
                <i class="fas fa-envelope mr-2"></i>Email Address *
            </label>
            <input type="email"style="color:white;" name="email" class="form-control w-full" placeholder="Enter your email address" required>
        </div>
    </div>

    <!-- Row 2: Phone and Address -->
    <div class="flex flex-col md:flex-row gap-4 mb-4">
        <div class="w-full md:w-1/2">
            <label class="text-slate-300 text-sm font-medium mb-2 block">
                <i class="fas fa-phone mr-2"></i>Phone Number *
            </label>
            <input type="tel"style="color:white;" name="phone_number" class="form-control w-full" placeholder="Enter your phone number" required>
        </div>
        <div class="w-full md:w-1/2">
            <label class="text-slate-300 text-sm font-medium mb-2 block">
                <i class="fas fa-map-marker-alt mr-2"></i>Address *
            </label>
            <input type="text"style="color:white;" name="address" class="form-control w-full" placeholder="Enter your address" required>
        </div>
    </div>

    <!-- Row 3: Description full width -->
     <div class="mb-4">
    <label class="text-slate-300 text-sm font-medium mb-2 block">
        <i class="fas fa-comment mr-2"></i>Additional Information
    </label>
    <textarea 
        name="description"
        class="form-control w-full bg-gray-800 text-white border border-gray-600 rounded p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
        rows="3"
        placeholder="Any additional information or special requirements (optional)">
    </textarea>
       </div>




                <!-- Payment Information (will be populated by JavaScript) -->
                <div id="paymentInfo" class="payment-info hidden">
                    <h4 class="text-white font-semibold mb-2 flex items-center gap-2">
                        <i class="fas fa-credit-card"></i>
                        Payment Required
                    </h4>
                    <div class="text-white">
                        <p>Registration Fee: <span id="paymentAmount" class="font-bold text-xl"></span></p>
                        <p class="text-sm mt-1 text-green-100">Secure payment via Razorpay</p>
                    </div>
                </div>

                <div id="freeInfo" class="bg-gradient-to-r from-green-600 to-green-700 p-4 rounded-lg hidden">
                    <h4 class="text-white font-semibold mb-2 flex items-center gap-2">
                        <i class="fas fa-gift"></i>
                        Free Registration
                    </h4>
                    <p class="text-green-100">This campaign offers free registration for all participants!</p>
                </div>

                <div class="flex gap-4 mt-6">
                    <button type="button" id="paymentBtn" class="flex-1 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-300 flex items-center justify-center gap-2 hidden">
                        <i class="fas fa-credit-card"></i>
                        <span id="paymentBtnText">Pay Now</span>
                    </button>
                    
                    <button type="submit" id="freeRegisterBtn" class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-300 flex items-center justify-center gap-2 hidden">
                        <i class="fas fa-check"></i>
                        Register Now
                    </button>
                    
                    <button type="button" onclick="closeRegistrationModal()" class="flex-1 bg-gradient-to-r from-slate-600 to-slate-700 hover:from-slate-700 hover:to-slate-800 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-300 flex items-center justify-center gap-2">
                        <i class="fas fa-times"></i>
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Place this in your main layout (e.g. layouts/app.blade.php) just before your other scripts --}}
<script>
    // Expose user-authenticated flag for the “user” guard
    window.isUserLoggedIn = {{ auth('user')->check() ? 'true' : 'false' }};
</script>

<!-- Campaign Management Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.0/dist/echo.iife.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
// ————————————————————————————————————————————————
// Global Campaign Approval Functions (Load Immediately)
// ————————————————————————————————————————————————

// Define functions immediately when script loads
window.approveCampaign = function(campaignId) {
    console.log('approveCampaign called with ID:', campaignId);
    
    Swal.fire({
        title: 'Approve Campaign?',
        text: 'Are you sure you want to approve this campaign? This will send a real-time notification to the doctor.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#ef4444',
        confirmButtonText: 'Yes, Approve',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            updateCampaignStatus(campaignId, 'approve');
        }
    });
};

window.rejectCampaign = function(campaignId) {
    console.log('rejectCampaign called with ID:', campaignId);
    
    Swal.fire({
        title: 'Reject Campaign?',
        text: 'Are you sure you want to reject this campaign? This will send a real-time notification to the doctor.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Reject',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            updateCampaignStatus(campaignId, 'reject');
        }
    });
};

function updateCampaignStatus(campaignId, action) {
    const url = `{{ url('admin/campaigns') }}/${campaignId}/${action}`;
    const token = '{{ csrf_token() }}';

    console.log('Updating campaign status:', { campaignId, action, url });

    $.ajax({
        url: url,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        beforeSend: function() {
            Swal.fire({
                title: 'Processing...',
                html: `
                    <div class="text-center">
                        <div class="spinner-border text-primary mb-3" role="status"></div>
                        <p>${action === 'approve' ? 'Approving' : 'Rejecting'} campaign...</p>
                        <p class="small text-muted">Sending real-time notification to doctor...</p>
                    </div>
                `,
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        },
        success: function(response) {
            console.log('Campaign status updated successfully:', response);
            
            Swal.fire({
                title: 'Success!',
                html: `
                    <div class="text-center">
                        <i class="fas fa-check-circle text-success" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                        <p class="mb-2">${response.message || `Campaign ${action}d successfully!`}</p>
                        <p class="small text-muted">
                            <i class="fas fa-bell me-1"></i>
                            Real-time notification sent to doctor
                        </p>
                    </div>
                `,
                icon: 'success',
                confirmButtonColor: '#10b981',
                confirmButtonText: 'Continue',
                timer: 3000,
                timerProgressBar: true
            }).then(() => {
                // Reload the page to reflect the status change
                window.location.reload();
            });

            // Show a toast notification as well
            if (typeof toastr !== 'undefined') {
                toastr.success(`Campaign ${action}d and doctor notified!`, 'Success', {
                    timeOut: 3000,
                    progressBar: true
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Error updating campaign status:', { xhr, status, error });
            
            let errorMessage = 'An error occurred while updating the campaign status.';
            
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.status === 403) {
                errorMessage = 'You do not have permission to perform this action.';
            } else if (xhr.status === 404) {
                errorMessage = 'Campaign not found.';
            } else if (xhr.status === 422) {
                errorMessage = 'Invalid request data.';
            } else if (xhr.status === 500) {
                errorMessage = 'Server error occurred. Please try again.';
            }

            Swal.fire({
                title: 'Error!',
                text: errorMessage,
                icon: 'error',
                confirmButtonColor: '#ef4444'
            });

            // Show error toast
            if (typeof toastr !== 'undefined') {
                toastr.error(errorMessage, 'Error', {
                    timeOut: 5000,
                    progressBar: true
                });
            }
        }
    });
}

// Test that functions are available
console.log('Campaign approval functions loaded:', {
    approveCampaign: typeof window.approveCampaign,
    rejectCampaign: typeof window.rejectCampaign,
    updateCampaignStatus: typeof updateCampaignStatus
});
</script>

<script>
$(function() {
    // ————————————————————————————————————————————————
    // Globals & Auth flag
    // ————————————————————————————————————————————————
    const csrfToken       = $('meta[name="csrf-token"]').attr('content');
    const loginUrl        = '{{ route("user.login") }}';
    window.isUserLoggedIn = @json(auth('user')->check());

    // ————————————————————————————————————————————————
    // Pusher & Echo setup
    // ————————————————————————————————————————————————
    window.Pusher = Pusher;
    window.Echo   = new Echo({
        broadcaster: 'pusher',
        key:         '{{ config("broadcasting.connections.pusher.key") }}',
        cluster:     '{{ config("broadcasting.connections.pusher.options.cluster") }}',
        forceTLS:    true,
        auth: { headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' } }
    });

    @auth('user')
    Echo.private(`user.{{ auth('user')->id() }}`)
        .listen('.message.received', e => toastr.success(e.message, '📨 New Message'));
    @endauth

    // ————————————————————————————————————————————————
    // Modal open/close helpers
    // ————————————————————————————————————————————————
    function openRegistrationModal(id, fee = 0) {
        if (!window.isUserLoggedIn) {
            return Swal.fire({
                title: 'Login Required',
                text:  'Please log in to register.',
                icon:  'info',
                showCancelButton: true,
                confirmButtonText: 'Login'
            }).then(r => r.isConfirmed && (window.location = loginUrl));
        }

      $('#campaignId').val(id);
$('#userId').val('{{ auth("user")->id() }}');
$('#amount').val(fee); // Set the hidden amount input

if (fee > 0) {
    $('#paymentInfo').removeClass('hidden');
    $('#freeInfo').addClass('hidden');
    $('#paymentBtn').removeClass('hidden');
    $('#freeRegisterBtn').addClass('hidden');
    $('#paymentAmount').text(`₹${new Intl.NumberFormat('en-IN').format(fee)}`);
    $('#paymentBtnText').text(`Pay ₹${new Intl.NumberFormat('en-IN').format(fee)}`);
} else {
    $('#paymentInfo').addClass('hidden');
    $('#freeInfo').removeClass('hidden');
    $('#paymentBtn').addClass('hidden');
    $('#freeRegisterBtn').removeClass('hidden');
}

$('#registrationModal').show();
$('body').css('overflow', 'hidden');
    }

    function closeRegistrationModal() {
        $('#registrationModal').hide();
        $('body').css('overflow', 'auto');
        $('#registrationForm')[0].reset();
    }

    window.handleRegistration      = openRegistrationModal;
    window.closeRegistrationModal = closeRegistrationModal;

    $('.close').on('click', closeRegistrationModal);
    $(window).on('click', e => { if (e.target.id === 'registrationModal') closeRegistrationModal(); });
    $(document).on('keydown', e => { if (e.key === 'Escape') closeRegistrationModal(); });

    // ————————————————————————————————————————————————
    // Filter campaigns
    // ————————————————————————————————————————————————
   function filterCampaigns() {
    const titleSearch    = $('#searchTitle').val().toLowerCase();
    const locationSearch = $('#searchLocation').val().toLowerCase();
    const typeFilter     = $('#typeFilter').val();

    let visibleCount = 0;
    $('.campaign-card').each(function() {
        const card     = $(this);
        const title    = (card.data('title')    || '').toLowerCase();
        const location = (card.data('location') || '').toLowerCase();
        const type     = card.data('type');

        const matchesTitle    = title.includes(titleSearch);
        const matchesLocation = location.includes(locationSearch);
        const matchesType     = !typeFilter || type === typeFilter;

        if (matchesTitle && matchesLocation && matchesType) {
            card.show();
            visibleCount++;
        } else {
            card.hide();
        }
    });

    $('#noResults').toggleClass('hidden', visibleCount > 0);
}

$('#searchTitle, #searchLocation').on('input', filterCampaigns);
$('#typeFilter').on('change', filterCampaigns);

    // ————————————————————————————————————————————————
    // Free registration (no payment)
    // ————————————————————————————————————————————————
    $('#registrationForm').on('submit', function(e) {
        e.preventDefault();
        // if payment flow is active, skip
        if ($('#paymentBtn').is(':visible')) return;

        const fd = new FormData(this);
        Swal.fire({ title: 'Processing...', didOpen: Swal.showLoading });

        fetch('{{ route("user.patient.campaigns.register") }}', {
            method:      'POST',
            credentials: 'include',
            headers:     { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body:        fd
        })
        .then(res => {
            if ([401,419].includes(res.status)) throw new Error('login');
            return res.json();
        })
        .then(d => {
            Swal.close();
            if (d.success) {
                closeRegistrationModal();
                Swal.fire('Success', 'Registered successfully', 'success');
            } else {
                Swal.fire('Error', d.message || 'Registration failed', 'error');
            }
        })
        .catch(err => {
            Swal.close();
            if (err.message === 'login') {
                Swal.fire('Session Expired', 'Please log in to continue', 'warning')
                    .then(() => window.location = loginUrl);
            } else {
                Swal.fire('Error', err.message, 'error');
            }
        });
    });

    // ————————————————————————————————————————————————
    // Paid registration via Razorpay
    // ————————————————————————————————————————————————
    $('#paymentBtn').on('click', function(e) {
        e.preventDefault();

        const form = $('#registrationForm')[0];
        if (!form.checkValidity()) {
            form.reportValidity();
            return console.warn('❌ Form validation failed.');
        }

        const fd          = new FormData(form);
        const campaignId  = $('#campaignId').val();
        const userId      = $('#userId').val();
        const amountPaise = parseFloat(document.querySelector('#amount').value || 0) * 100;


        fd.set('campaign_id', campaignId);
        fd.set('user_id', userId);

        const options = {
            key:      '{{ config("services.razorpay.key") }}',
            amount:   amountPaise,
            currency: 'INR',
            name:     'FreeDoctor',
            description: 'Campaign Registration',
            image:    '{{ asset("images/logo.png") }}',
            prefill: {
                name:    $('input[name="name"]').val(),
                email:   $('input[name="email"]').val(),
                contact: $('input[name="phone_number"]').val(),
            },
            theme: { color: '#3b82f6' },
            handler(response) {
                fd.set('razorpay_payment_id', response.razorpay_payment_id);

                $.ajax({
                    url:         '{{ route("user.campaigns.payment.create") }}',
                    method:      'POST',
                    data:        fd,
                    headers:     { 'X-CSRF-TOKEN': csrfToken },
                    processData: false,
                    contentType: false,
                    success(data) {
                        Swal.fire('Success', data.message, 'success')
                            .then(() => {
                                closeRegistrationModal();
                                window.location = data.redirect_url;
                            });
                    },
                    error(xhr) {
                        Swal.fire('Error', 'Something went wrong.', 'error');
                    }
                });
            },
            modal: {
                ondismiss() {
                    Swal.fire('Cancelled', 'You cancelled the payment.', 'warning');
                }
            }
        };

        new Razorpay(options).open();
    });

    // ————————————————————————————————————————————————
    // Auto‑fill user info
    // ————————————————————————————————————————————————
    if (window.isUserLoggedIn) {
        const user = @json(auth('user')->user());
        $('#registrationForm input[name="name"]').val(user.name || '');
        $('#registrationForm input[name="email"]').val(user.email || '');
        $('#registrationForm input[name="phone_number"]').val(user.phone_number || '');
        $('#registrationForm input[name="address"]').val(user.address || '');
    }
});
</script>
@endsection