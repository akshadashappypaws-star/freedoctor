@extends('user.master')

@section('title', 'Sponsor Campaigns - FreeDoctor')

@push('styles')
<style>
    .glass-card {
        background: rgba(30, 41, 59, 0.2);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(148, 163, 184, 0.3);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .glass-card:hover {
        background: rgba(30, 41, 59, 0.3);
        border-color: rgba(59, 130, 246, 0.5);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        transform: translateY(-2px);
    }

    .stat-card {
        background: linear-gradient(135deg, rgba(30, 41, 59, 0.4), rgba(51, 65, 85, 0.4));
        backdrop-filter: blur(20px);
        border: 1px solid rgba(148, 163, 184, 0.2);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
    }

    .animate-fade-in {
        animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .hero-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .btn-modern {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
    }

    .btn-modern:hover {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
        color: white;
    }

    .benefits-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
    }

    .benefits-card:hover {
        background: rgba(255, 255, 255, 0.1);
        transform: translateY(-3px);
    }

    /* Modern button styles */
    .btn-gradient {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        transition: all 0.3s ease;
    }

    .btn-gradient:hover {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
        transform: translateY(-2px);
    }

    .btn-outline {
        background: rgba(30, 41, 59, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(148, 163, 184, 0.3) !important;
        transition: all 0.3s ease;
    }

    .btn-outline:hover {
        background: rgba(59, 130, 246, 0.1);
        border-color: rgba(59, 130, 246, 0.6) !important;
        transform: translateY(-1px);
    }

    /* Filter buttons */
    .filter-btn {
        background: rgba(30, 41, 59, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(148, 163, 184, 0.3) !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .filter-btn.active {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border-color: rgba(99, 102, 241, 0.5) !important;
        color: white;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        transform: translateY(-1px);
    }

    .filter-btn:hover:not(.active) {
        background: rgba(99, 102, 241, 0.1);
        border-color: rgba(99, 102, 241, 0.5) !important;
        transform: translateY(-1px);
    }

    /* Status badges */
    .status-badge {
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        font-weight: 600;
        letter-spacing: 0.025em;
    }

    /* Search input */
    .search-input {
        background: rgba(30, 41, 59, 0.2);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(148, 163, 184, 0.3) !important;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        background: rgba(30, 41, 59, 0.3);
        border-color: rgba(99, 102, 241, 0.7) !important;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    /* Animation keyframes */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fadeInUp 0.6s ease-out;
    }

    /* Floating elements */
    .float-element {
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-10px);
        }
    }

    /* Gradient text */
    .gradient-text {
        background: linear-gradient(135deg, #6366f1, #8b5cf6, #ec4899);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Modern input styles */
    .modern-input {
        background: rgba(30, 41, 59, 0.2);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(148, 163, 184, 0.3) !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .modern-input:focus {
        background: rgba(30, 41, 59, 0.3);
        border-color: rgba(99, 102, 241, 0.7) !important;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        transform: translateY(-1px);
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
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
    }

    /* Loading animation */
    .loading-spinner {
        border: 3px solid rgba(99, 102, 241, 0.3);
        border-top: 3px solid #6366f1;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .mobile-hide {
            display: none !important;
        }

        .mobile-stack {
            flex-direction: column !important;
            gap: 0.5rem !important;
        }
    }

 
    /* Modern glass effect cards */
    .glass-card {
        background: rgba(30, 41, 59, 0.2);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(148, 163, 184, 0.3) !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .glass-card:hover {
        background: rgba(30, 41, 59, 0.3);
        border-color: rgba(59, 130, 246, 0.5) !important;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        transform: translateY(-2px);
    }
    
    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(5px);
    }

    .modal-content {
        background: linear-gradient(135deg, rgba(30, 41, 59, 0.95), rgba(51, 65, 85, 0.95));
        backdrop-filter: blur(20px);
        margin: 5% auto;
        padding: 0;
        border: 1px solid rgba(148, 163, 184, 0.3);
        border-radius: 1rem;
        width: 90%;
        max-width: 600px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem;
        border-bottom: 1px solid rgba(148, 163, 184, 0.2);
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(139, 92, 246, 0.1));
    }

    .close {
        color: #94a3b8;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .close:hover {
        color: #f1f5f9;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-control {
        background: rgba(30, 41, 59, 0.3);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(148, 163, 184, 0.3);
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
        width: 100%;
        color: white;
        font-size: 0.875rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: rgba(99, 102, 241, 0.7);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        background: rgba(30, 41, 59, 0.4);
    }

    .form-control::placeholder {
        color: rgba(148, 163, 184, 0.7);
    }

    .payment-info {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(139, 92, 246, 0.1));
        padding: 1rem;
        border-radius: 0.75rem;
        border: 1px solid rgba(99, 102, 241, 0.3);
        margin-bottom: 1.5rem;
    }
    
    /* Modern button styles */
    .btn-gradient {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        transition: all 0.3s ease;
    }
    
    .btn-gradient:hover {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
        transform: translateY(-2px);
    }
    
    /* Filter buttons */
    .filter-btn {
        background: rgba(30, 41, 59, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(148, 163, 184, 0.3) !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .filter-btn.active {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border-color: rgba(99, 102, 241, 0.5) !important;
        color: white;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        transform: translateY(-1px);
    }
    
    /* Modern input styles */
    .modern-input {
        background: rgba(30, 41, 59, 0.2);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(148, 163, 184, 0.3) !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .modern-input:focus {
        background: rgba(30, 41, 59, 0.3);
        border-color: rgba(99, 102, 241, 0.7) !important;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        transform: translateY(-1px);
    }
    
    /* Animation keyframes */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in {
        animation: fadeInUp 0.6s ease-out;
    }
    
    /* Floating elements */
    .float-element {
        animation: float 6s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    /* Gradient text */
    .gradient-text {
        background: linear-gradient(135deg, #6366f1, #8b5cf6, #ec4899);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .mobile-hide {
            display: none !important;
        }
        
        .mobile-stack {
            flex-direction: column !important;
            gap: 0.5rem !important;
        }

        .modal-content {
            width: 95%;
            margin: 10% auto;
        }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen space-y-8">
    <!-- Hero Section -->
    <div class="glass-card rounded-2xl p-8 text-center">
        <div class="max-w-4xl mx-auto">
            <div class="w-24 h-24 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-2xl">
                <i class="fas fa-handshake text-4xl text-white"></i>
            </div>
            <h1 class="text-5xl font-bold text-white mb-4">Sponsor This Healthcare Portal</h1>
            <p class="text-xl text-slate-300 mb-8 leading-relaxed">
                Join us in making healthcare accessible to everyone. Your sponsorship helps fund medical camps, 
                provides free healthcare services, and supports communities in need.
            </p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="sponsor-tier rounded-xl p-6">
                    <h3 class="text-xl font-bold text-yellow-400 mb-2">Gold Sponsor</h3>
                    <p class="text-3xl font-bold text-white mb-2">Rs50,000+</p>
                    <p class="text-slate-300 text-sm">Fund multiple medical camps and receive premium branding</p>
                </div>
                
                <div class="sponsor-tier rounded-xl p-6">
                    <h3 class="text-xl font-bold text-gray-400 mb-2">Silver Sponsor</h3>
                    <p class="text-3xl font-bold text-white mb-2">Rs10,000+</p>
                    <p class="text-slate-300 text-sm">Support specific campaigns with recognition benefits</p>
                </div>
                
                <div class="sponsor-tier rounded-xl p-6">
                    <h3 class="text-xl font-bold text-orange-400 mb-2">Bronze Sponsor</h3>
                    <p class="text-3xl font-bold text-white mb-2">Rs5000+</p>
                    <p class="text-slate-300 text-sm">Contribute to healthcare accessibility with acknowledgment</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Campaign Filters -->
    <div class="glass-card rounded-2xl p-6">
        <form id="filterForm" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-white text-sm font-medium mb-2">Search Campaigns</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." 
                       class="w-full bg-slate-700/50 border border-slate-600 rounded-xl px-4 py-3 text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-white text-sm font-medium mb-2">Medical Specialty</label>
                <select name="specialty" class="w-full bg-slate-700/50 border border-slate-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Specialties</option>
                    @foreach($specialties as $specialty)
                        <option value="{{ $specialty->id }}" {{ request('specialty') == $specialty->id ? 'selected' : '' }}>
                            {{ $specialty->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-white text-sm font-medium mb-2">Location</label>
                <input type="text" name="location" value="{{ request('location') }}" placeholder="Location..." 
                       class="w-full bg-slate-700/50 border border-slate-600 rounded-xl px-4 py-3 text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="w-full btn-modern">
                    <i class="fas fa-search mr-2"></i>Filter Campaigns
                </button>
            </div>
        </form>
    </div>

    <!-- Campaigns to Sponsor -->
    <div class="glass-card rounded-2xl p-6">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-white mb-2">
                <i class="fas fa-calendar-heart mr-2 text-red-400"></i>Campaigns Available for Sponsorship
            </h2>
            <p class="text-slate-400">Support medical campaigns and help make healthcare accessible to communities in need</p>
        </div>
        
        @if($campaigns->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($campaigns as $campaign)
            <div class="campaign-card rounded-xl p-6 text-white">
                <!-- Campaign Header -->
                <div class="flex justify-between items-start mb-4">
                    <div class="bg-green-500/20 px-3 py-1 rounded-full">
                        <span class="text-green-400 text-sm font-medium">
                            <i class="fas fa-check mr-1"></i>Approved
                        </span>
                    </div>
                    <div class="bg-yellow-500/20 px-3 py-1 rounded-full">
                        <span class="text-yellow-400 text-xs font-medium">
                            <i class="fas fa-heart mr-1"></i>Needs Sponsors
                        </span>
                    </div>
                </div>
                
                <!-- Campaign Content -->
                <h3 class="text-xl font-bold text-white mb-3">{{ $campaign->title }}</h3>
                <p class="text-slate-400 text-sm mb-4 line-clamp-3">{{ Str::limit($campaign->description, 120) }}</p>
                
                <!-- Campaign Details -->
                <div class="space-y-2 mb-6">
                    <div class="flex items-center text-slate-300 text-sm">
                        <i class="fas fa-user-md w-4 text-blue-400 mr-3"></i>
                        <span>Dr. {{ $campaign->doctor->doctor_name ?? 'N/A' }}</span>
                    </div>
                    @if($campaign->doctor && $campaign->doctor->specialty)
                    <div class="flex items-center text-slate-300 text-sm">
                        <i class="fas fa-stethoscope w-4 text-green-400 mr-3"></i>
                        <span>{{ $campaign->doctor->specialty->name }}</span>
                    </div>
                    @endif
                    @if($campaign->category)
                    <div class="flex items-center text-slate-300 text-sm">
                        <i class="material-icons w-4 text-purple-400 mr-3" style="font-size: 16px;">{{ $campaign->category->icon_class ?? 'local_hospital' }}</i>
                        <span>{{ $campaign->category->category_name }}</span>
                    </div>
                    @endif
                    <div class="flex items-center text-slate-300 text-sm">
                        <i class="fas fa-map-marker-alt w-4 text-red-400 mr-3"></i>
                        <span>{{ $campaign->location }}</span>
                    </div>
                    <div class="flex items-center text-slate-300 text-sm">
                        <i class="fas fa-calendar w-4 text-yellow-400 mr-3"></i>
                        <span>{{ \Carbon\Carbon::parse($campaign->date_from)->format('M d, Y') }}</span>
                    </div>
                    <div class="flex items-center text-slate-300 text-sm">
                        <i class="fas fa-users w-4 text-purple-400 mr-3"></i>
                        <span>{{ $campaign->patientRegistrations->count() }} Registered</span>
                    </div>
                    @if($campaign->target_amount)
                    <div class="flex items-center text-slate-300 text-sm">
                        <i class="fas fa-target w-4 text-orange-400 mr-3"></i>
                        <span>Target: ${{ number_format($campaign->target_amount) }}</span>
                    </div>
                    @endif
                </div>
                
                <!-- Sponsorship Progress -->
                @php
                    $totalSponsored = $campaign->campaignSponsors->sum('amount');
                    $targetAmount = $campaign->target_amount ?? 1000;
                    $progressPercentage = min(($totalSponsored / $targetAmount) * 100, 100);
                @endphp
                <div class="mb-4">
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-slate-400">Sponsored</span>
                        <span class="text-white">Rs{{ number_format($totalSponsored) }} / Rs{{ number_format($targetAmount) }}</span>
                    </div>
                    <div class="w-full bg-slate-700 rounded-full h-2">
                        <div class="bg-gradient-to-r from-green-500 to-blue-600 h-2 rounded-full transition-all duration-300" 
                             style="width: {{ $progressPercentage }}%"></div>
                    </div>
                    <div class="text-xs text-slate-400 mt-1">{{ number_format($progressPercentage, 1) }}% funded</div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex gap-2">
                    <a href="{{ route('user.campaigns.view', $campaign->id) }}"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-medium transition-colors">
                        <i class="fas fa-eye mr-1"></i>View
                    </a>
                  <button
    type="button"
    onclick="openSponsorModal({{ $campaign->id }}, {{ $campaign->sponsorship_amount ?? 0 }})"
    class="flex-1 bg-yellow-600 hover:bg-yellow-700 text-white py-2 px-4 rounded-lg font-medium transition-colors flex items-center justify-center gap-1"
>
    <i class="fas fa-hand-holding-heart"></i>
    Sponsor
</button>

                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12">
            <div class="bg-slate-700/30 rounded-full w-24 h-24 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-heart-broken text-3xl text-slate-400"></i>
            </div>
            <h3 class="text-xl font-medium text-white mb-2">No Campaigns Available for Sponsorship</h3>
            <p class="text-slate-400">Check back later for new campaigns that need sponsorship support</p>
        </div>
        @endif
    </div>
</div>

<!-- Sponsorship Modal -->
<!-- Sponsor Registration Modal -->
<div id="sponsorRegistrationModal" class="modal" style="margin-top:0!important;">
 <div class="modal-content">
        <div class="modal-header">
            <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                <i class="fas fa-hand-holding-usd"></i>
                Become a Campaign Sponsor
            </h2>
            <span class="close" onclick="closeSponsorModal()">&times;</span>
        </div>
        <div class="p-6">
            <form id="sponsorRegistrationForm">
                @csrf
                <input type="hidden" name="campaign_id" id="sponsorCampaignId" value="">
                <input type="hidden" name="user_id" id="sponsorUserId" value="{{ auth('user')->id() }}">

                <!-- Row 1: Name and Phone -->
                <div class="flex flex-col md:flex-row gap-4 mb-4">
                    <div class="w-full md:w-1/2">
                        <label class="text-slate-300 text-sm font-medium mb-2 block">
                            <i class="fas fa-user mr-2"></i>Full Name *
                        </label>
                        <input type="text" name="name" class="form-control w-full text-white" placeholder="Enter your name" required>
                    </div>
                    <div class="w-full md:w-1/2">
                        <label class="text-slate-300 text-sm font-medium mb-2 block">
                            <i class="fas fa-phone mr-2"></i>Phone Number *
                        </label>
                        <input type="tel" name="phone_number" class="form-control w-full text-white" placeholder="Enter your phone number" required>
                    </div>
                </div>

                <!-- Row 2: Address -->
                <div class="mb-4">
                    <label class="text-slate-300 text-sm font-medium mb-2 block">
                        <i class="fas fa-map-marker-alt mr-2"></i>Address *
                    </label>
                    <input type="text" name="address" class="form-control w-full text-white" placeholder="Enter your address" required>
                </div>

                <!-- Row 3: Message -->
                <div class="mb-4">
                    <label class="text-slate-300 text-sm font-medium mb-2 block">
                        <i class="fas fa-comment-alt mr-2"></i>Message (optional)
                    </label>
                    <textarea 
                        name="message"
                        class="form-control w-full bg-gray-800 text-white border border-gray-600 rounded p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        rows="3"
                        placeholder="Any message you'd like to add for the campaign team."></textarea>
                </div>

                <!-- Row 4: Sponsorship Amount -->
                <div class="mb-4">
                    <label class="text-slate-300 text-sm font-medium mb-2 block">
                        <i class="fas fa-rupee-sign mr-2"></i>Sponsorship Amount *
                    </label>
                    <input type="number" name="amount" class="form-control w-full text-white" placeholder="Enter amount (INR)" min="1" required>
                </div>

                <!-- Button Row -->
                <div class="flex gap-4 mt-6">
                    <button type="submit" id="sponsorSubmitBtn" class="flex-1 bg-gradient-to-r from-yellow-600 to-yellow-700 hover:from-yellow-700 hover:to-yellow-800 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-300 flex items-center justify-center gap-2">
                        <i class="fas fa-hand-holding-heart"></i>
                        Sponsor Now
                    </button>
                    
                    <button type="button" onclick="closeSponsorModal()" class="flex-1 bg-gradient-to-r from-slate-600 to-slate-700 hover:from-slate-700 hover:to-slate-800 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-300 flex items-center justify-center gap-2">
                        <i class="fas fa-times"></i>
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.0/dist/echo.iife.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


<script>
$(function () {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    const loginUrl  = '{{ route("user.login") }}';
    window.isUserLoggedIn = @json(auth('user')->check());

    // 1) Open/close modal
    window.openSponsorModal = function(campaignId, amount = 0) {
        if (!window.isUserLoggedIn) {
            return Swal.fire({
                title: 'Login Required',
                text:  'Please log in to sponsor.',
                icon:  'info',
                showCancelButton: true,
                confirmButtonText: 'Login'
            }).then(r => r.isConfirmed && (window.location = loginUrl));
        }

        // populate hidden fields
        $('#sponsorCampaignId').val(campaignId);
        $('#sponsorUserId').val('{{ auth("user")->id() }}');
        
        // If amount is provided, pre-fill it
        if (amount > 0) {
            $('input[name="amount"]').val(amount);
        }

        // show modal
        $('#sponsorRegistrationModal').show();
        $('body').css('overflow','hidden');
    };

    window.closeSponsorModal = function() {
        $('#sponsorRegistrationModal').hide();
        $('body').css('overflow','auto');
        $('#sponsorRegistrationForm')[0].reset();
    };

    $('.close').on('click', closeSponsorModal);
    $(window).on('click', e => { if (e.target.id === 'sponsorRegistrationModal') closeSponsorModal(); });
    $(document).on('keydown', e => { if (e.key === 'Escape') closeSponsorModal(); });

    // 2) Form submission with Razorpay payment
    $('#sponsorRegistrationForm').on('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // Get the amount from the form
        const amount = parseFloat($('input[name="amount"]').val() || 0);
        
        if (amount <= 0) {
            Swal.fire('Error', 'Please enter a valid sponsorship amount.', 'error');
            return;
        }

        const amountPaise = amount * 100; // Convert to paise for Razorpay

        // Prepare form data
        const formData = new FormData(form);

        const options = {
            key: '{{ config("services.razorpay.key") }}',
            amount: amountPaise,
            currency: 'INR',
            name: 'FreeDoctor',
            description: 'Campaign Sponsorship',
            image: '{{ asset("images/logo.png") }}',
            prefill: {
                name: $('input[name="name"]').val(),
                contact: $('input[name="phone_number"]').val()
            },
            theme: { 
                color: '#10b981' 
            },
            handler: function(response) {
                // Payment successful, add payment ID to form data
                formData.append('razorpay_payment_id', response.razorpay_payment_id);
                formData.append('payment_status', 'success');

                // Submit the form with payment details
                $.ajax({
                    url: '{{ route("user.campaigns.sponsors.payment") }}',
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('#sponsorSubmitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Processing...');
                    },
                    success: function(data) {
                        Swal.fire({
                            title: 'Success!',
                            text: data.message || 'Sponsorship registered successfully!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            closeSponsorModal();
                            // Redirect if provided, otherwise reload page
                            if (data.redirect_url) {
                                window.location.href = data.redirect_url;
                            } else {
                                window.location.reload();
                            }
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'Payment processing failed. Please try again.';
                        
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = Object.values(xhr.responseJSON.errors).flat();
                            errorMessage = errors.join('\n');
                        }
                        
                        Swal.fire('Error', errorMessage, 'error');
                    },
                    complete: function() {
                        $('#sponsorSubmitBtn').prop('disabled', false).html('<i class="fas fa-hand-holding-heart mr-2"></i>Sponsor Now');
                    }
                });
            },
            modal: {
                ondismiss: function() {
                    Swal.fire('Cancelled', 'Payment was cancelled.', 'warning');
                }
            }
        };

        // Open Razorpay checkout
        const rzp = new Razorpay(options);
        rzp.open();
    });

    // 3) Auto-fill user info if logged in
    if (window.isUserLoggedIn) {
        const user = @json(auth('user')->user());
        if (user) {
            $('#sponsorRegistrationForm input[name="name"]').val(user.name || '');
            $('#sponsorRegistrationForm input[name="phone_number"]').val(user.phone_number || '');
            $('#sponsorRegistrationForm input[name="address"]').val(user.address || '');
        }
    }

    // 4) Filter form auto-submit
    $('#filterForm input, #filterForm select').on('change', function() {
        $('#filterForm').submit();
    });
});</script>

@endpush


