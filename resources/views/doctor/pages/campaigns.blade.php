@extends('doctor.master')

@section('title', 'My Campaigns - FreeDoctor')

@push('styles')
<style>
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
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
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
</style>
@endpush
@section('content')
<!-- Modern Campaigns Dashboard -->
<div class="min-h-screen space-y-8">
    <!-- Header Section -->
    <div class="glass-card rounded-2xl p-8 animate-fade-in">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-2xl float-element">
                    <i class="fas fa-calendar-plus text-2xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-4xl font-bold gradient-text">
                        My Campaigns
                    </h1>
                    <p class="text-slate-300 mt-2 text-lg">Manage your medical camps and activities</p>
                    <p class="text-sm text-slate-400">
                        Total campaigns: <span class="text-blue-400 font-semibold">{{ $campaigns->count() }}</span>
                    </p>
                </div>
            </div>
            <div class="flex gap-3 mobile-stack">
                <button id="openModalBtn"
                    class="btn-gradient text-white px-6 py-3 rounded-xl font-medium transition-all duration-300 shadow-lg transform hover:scale-105 flex items-center gap-2">
                    <i class="fas fa-plus"></i>
                    <span>Create Campaign</span>
                </button>
               
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 animate-fade-in">
        <div class="glass-card rounded-xl p-6 hover:shadow-2xl hover:shadow-green-500/20 hover:bg-white/10 transition-all duration-300 transform-gpu">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-300 text-sm font-medium uppercase tracking-wider">Active</p>
                    <p class="text-2xl font-bold text-white">{{ $campaigns->where('approval_status', 'approved')->count() }}</p>
                </div>
                <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-3 rounded-xl">
                    <i class="fas fa-check-circle text-white text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="glass-card rounded-xl p-6 hover:shadow-2xl hover:shadow-orange-500/20 hover:bg-white/10 transition-all duration-300 transform-gpu">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-300 text-sm font-medium uppercase tracking-wider">Pending</p>
                    <p class="text-2xl font-bold text-white">{{ $campaigns->where('approval_status', 'pending')->count() }}</p>
                </div>
                <div class="bg-gradient-to-br from-yellow-500 to-orange-600 p-3 rounded-xl">
                    <i class="fas fa-clock text-white text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="glass-card rounded-xl p-6 hover:shadow-2xl hover:shadow-blue-500/20 hover:bg-white/10 transition-all duration-300 transform-gpu">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-300 text-sm font-medium uppercase tracking-wider">Completed</p>
                    @php
    use Carbon\Carbon;

    $completedCampaigns = $campaigns->filter(function ($campaign) {
        return Carbon::parse($campaign->end_date)->isPast();
    })->count();
@endphp

<p class="text-2xl font-bold text-white">{{ $completedCampaigns }}</p>
                </div>
                <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-3 rounded-xl">
                    <i class="fas fa-trophy text-white text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="glass-card rounded-xl p-6 hover:shadow-2xl hover:shadow-purple-500/20 hover:bg-white/10 transition-all duration-300 transform-gpu">
            <div class="flex items-center justify-between">
                <div>
                 @php
    $totalPatients = $campaigns->sum(function($campaign) {
        return $campaign->patientRegistrations->count();
    });
@endphp

<p class="text-slate-300 text-sm font-medium uppercase tracking-wider">Total Patients</p>
<p class="text-2xl font-bold text-white">{{ $totalPatients }}</p>                </div>
                <div class="bg-gradient-to-br from-purple-500 to-pink-600 p-3 rounded-xl">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="glass-card rounded-xl p-6 animate-fade-in">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Search by Title</label>
                <input type="text" id="searchTitle" placeholder="Search campaigns..."
                    class="modern-input w-full px-4 py-3 text-white placeholder-slate-400 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Filter by Location</label>
                <input type="text" id="searchLocation" placeholder="Filter by location..."
                    class="modern-input w-full px-4 py-3 text-white placeholder-slate-400 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Filter by Specialty</label>
                <select id="specialtyFilter" class="modern-input w-full px-4 py-3 text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Specialties</option>
                    @if(isset($specialties) && $specialties)
                        @foreach($specialties as $specialty)
                            <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            
        </div>
    </div>

    <!-- Campaigns Grid -->
    <div id="campaignGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 animate-fade-in">
        @foreach($campaigns as $campaign)
        <div class="glass-card campaign-card rounded-xl overflow-hidden border border-slate-600/30 cursor-pointer"
            data-id="{{ $campaign->id }}" 
            data-title="{{ strtolower($campaign->title) }}"
            data-location="{{ strtolower($campaign->location) }}"
            data-type="{{ $campaign->camp_type }}"
            data-specializations="{{ implode(',', is_array($campaign->specializations) ? $campaign->specializations : json_decode($campaign->specializations ?: '[]', true)) }}">

            <!-- Campaign Image -->
            <div class="relative h-48 bg-gradient-to-br from-blue-900/20 to-purple-900/20">
                @if($campaign->thumbnail)
                    <img src="{{ asset('storage/' . $campaign->thumbnail) }}" 
                         alt="{{ $campaign->title }}" 
                         class="w-full h-full object-cover opacity-80">
                @elseif($campaign->images && count(json_decode($campaign->images, true) ?? []) > 0)
                    @php $images = json_decode($campaign->images, true); @endphp
                    <img src="{{ asset('storage/' . $images[0]) }}"
                         alt="{{ $campaign->title }}"
                         class="w-full h-full object-cover opacity-80">
                @else

                    <div class="w-full h-full flex items-center justify-center">
                        <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center shadow-lg">
                            <i class="fas fa-calendar-alt text-3xl text-white"></i>
                        </div>
                    </div>
                @endif
                
                <!-- Status Badge -->
                <div class="absolute top-3 left-3 z-10">
                    @php
                        $statusClass = match($campaign->approval_status) {
                            'approved' => 'bg-green-500',
                            'pending' => 'bg-yellow-500',
                            'rejected' => 'bg-red-500',
                            default => 'bg-gray-500'
                        };
                    @endphp
                    <span class="{{ $statusClass }} text-white text-xs px-3 py-1 rounded-full font-medium shadow-lg">
                        {{ ucfirst($campaign->approval_status ?? 'pending') }}
                    </span>
                </div>

                <!-- Type Badge -->
                <div class="absolute top-3 right-3 z-10">
                    @if($campaign->camp_type === 'medical')
                        <span class="bg-blue-500/90 text-white text-xs px-2 py-1 rounded-full backdrop-blur-sm">
                            <i class="fas fa-stethoscope mr-1"></i>Medical
                        </span>
                    @elseif($campaign->camp_type === 'surgical')
                        <span class="bg-purple-500/90 text-white text-xs px-2 py-1 rounded-full backdrop-blur-sm">
                            <i class="fas fa-user-md mr-1"></i>Surgical
                        </span>
                    @endif
                </div>
            </div>

            <!-- Campaign Info -->
            <div class="p-4">
                <h3 class="text-lg font-bold text-white mb-2 truncate">{{ $campaign->title }}</h3>
                <p class="text-sm text-blue-300 mb-3 flex items-center">
                    <i class="fas fa-map-marker-alt mr-2"></i>{{ $campaign->location }}
                </p>
                
                <div class="space-y-2 text-xs text-gray-300 mb-4">
                 <div class="space-y-2 text-xs text-gray-300 mb-4">
    <div class="flex items-center">
        <i class="fas fa-calendar text-blue-400 w-4"></i>
        <span class="ml-2 truncate">{{ \Carbon\Carbon::parse($campaign->start_date)->format('M j') }} - {{ \Carbon\Carbon::parse($campaign->end_date)->format('M j, Y') }}</span>
    </div>
    <div class="flex items-center">
        <i class="fas fa-clock text-green-400 w-4"></i>
        <span class="ml-2 truncate">
            @if($campaign->start_time && $campaign->end_time)
                {{ \Carbon\Carbon::parse($campaign->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($campaign->end_time)->format('h:i A') }}
            @else
                Time not specified
            @endif
        </span>
    </div>
    <div class="flex items-center">
        <i class="fas fa-users text-purple-400 w-4"></i>
        <span class="ml-2">{{ $campaign->expected_patients }} patients expected</span>
    </div>
    <div class="flex items-center">
        <i class="fas fa-user-check text-emerald-400 w-4"></i>
        <span class="ml-2">{{ $campaign->patientRegistrations->count() }} patients registered</span>
    </div>
    @if($campaign->registration_payment)
    <div class="flex items-center">
        <i class="fas fa-rupee-sign text-yellow-400 w-4"></i>
        <span class="ml-2">₹{{ number_format($campaign->registration_payment) }} registration fee</span>
    </div>
    @endif
</div>

                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2 mt-4">
                    <button class="edit-btn flex-1 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white text-xs py-1 px-2 rounded-lg transition-all duration-200 font-medium shadow-lg hover:shadow-xl hover:shadow-blue-500/30">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </button>
                    <button class="view-btn flex-1 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white text-xs py-1 px-2 rounded-lg transition-all duration-200 font-medium shadow-lg hover:shadow-xl hover:shadow-purple-500/30">
                        <i class="fas fa-eye mr-1"></i>View
                    </button>
                       <button class="delete-btn bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white text-xs py-1 px-2 rounded-md transition-all duration-200 font-medium">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

                <!-- Secondary Actions -->
                <div class="flex gap-2 mt-2">
                    <!-- <button class="register-patient-btn flex-1 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white text-xs py-1.5 px-2 rounded-md transition-all duration-200 font-medium">
                        <i class="fas fa-user-plus mr-1"></i>Register Patient
                    </button> -->
                 
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- No Results Message -->
    <div id="noResults" class="hidden text-center py-12">
        <div class="text-gray-500">
            <i class="fas fa-search text-6xl mb-4"></i>
            <h3 class="text-xl font-medium mb-2">No campaigns found</h3>
            <p>Try adjusting your search criteria</p>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="campaignModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-[9999]">
    <div class="bg-slate-800 border border-slate-600 rounded-lg p-6 w-full max-w-2xl shadow-xl max-h-screen overflow-y-auto">
        <h2 id="modalTitle" class="text-2xl font-bold mb-6 text-white">Add New Campaign</h2>
        <form id="campaignForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="campaignId" name="id">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Title *</label>
                    <input type="text" name="title" id="campaignTitle" required 
                        class="w-full px-4 py-2 bg-slate-600 border border-slate-500 text-white placeholder-gray-400 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Description *</label>
                    <textarea name="description" id="campaignDescription" rows="3" required
                        class="w-full px-4 py-2 bg-slate-600 border border-slate-500 text-white placeholder-gray-400 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"></textarea>
                </div>

                <!-- Location with Google Maps Integration -->
              <div class="md:col-span-2">
    <label class="block text-sm font-medium text-gray-300 mb-2">
        Campaign Location *
        <span class="text-xs text-gray-400 block mt-1">Search for address or use current location</span>
    </label>
    
    <!-- Location Input Container -->
    <div class="relative">
        <input type="text" 
               name="location" 
               id="campaignLocation" 
               required 
               autocomplete="off"
               class="w-full px-4 py-3 pr-20 bg-slate-600 border border-slate-500 text-white placeholder-gray-400 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
               placeholder="Start typing address or place name...">
        
        <!-- Action Buttons -->
        <div class="absolute right-2 top-1/2 transform -translate-y-1/2 flex gap-1">
            <button type="button" 
                    id="getCurrentLocationBtn" 
                    class="text-green-400 hover:text-green-300 p-2 rounded-md hover:bg-slate-700/50 transition-colors" 
                    title="Use current location">
                <i class="fas fa-location-crosshairs text-sm"></i>
            </button>
            <button type="button" 
                    id="clearLocationBtn" 
                    class="text-red-400 hover:text-red-300 p-2 rounded-md hover:bg-slate-700/50 transition-colors hidden" 
                    title="Clear location">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
    </div>

    <!-- Hidden Coordinate Fields -->
    <input type="hidden" name="latitude" id="campaignLatitude">
    <input type="hidden" name="longitude" id="campaignLongitude">
    <input type="hidden" name="place_id" id="campaignPlaceId">
    <input type="hidden" name="formatted_address" id="campaignFormattedAddress">

    <!-- Location Status and Preview -->
    <div id="locationStatus" class="mt-3 space-y-2">
        <!-- Verification Status -->
        <div id="verificationStatus" class="hidden">
            <div class="flex items-center gap-2 text-green-400 text-sm">
                <i class="fas fa-check-circle"></i>
                <span>Location verified and coordinates saved</span>
            </div>
        </div>
        
        <!-- Coordinates Display -->
        <div id="coordinatesDisplay" class="hidden text-xs text-gray-400">
            <i class="fas fa-map-marker-alt mr-1"></i>
            <span id="coordinatesText"></span>
        </div>
        
        <!-- Address Preview -->
        <div id="addressPreview" class="hidden">
            <div class="bg-slate-700/50 border border-slate-600 rounded-lg p-3">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-blue-500/20 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fas fa-map-marker-alt text-blue-400 text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-white font-medium text-sm" id="previewTitle"></div>
                        <div class="text-gray-300 text-xs mt-1" id="previewAddress"></div>
                        <div class="text-gray-400 text-xs mt-1" id="previewCoords"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mini Map Container -->
    <div id="locationMap" class="mt-3 h-48 bg-slate-700 rounded-lg border border-slate-600 hidden overflow-hidden">
        <div class="w-full h-full flex items-center justify-center text-gray-400">
            <div class="text-center">
                <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                <div class="text-sm">Loading map...</div>
            </div>
        </div>
    </div>

    <!-- Loading State -->
    <div id="locationLoading" class="hidden mt-2">
        <div class="flex items-center gap-2 text-blue-400 text-sm">
            <i class="fas fa-spinner fa-spin"></i>
            <span id="loadingText">Getting location...</span>
        </div>
    </div>

    <!-- Error State -->
    <div id="locationError" class="hidden mt-2">
        <div class="bg-red-900/20 border border-red-600/30 rounded-lg p-3">
            <div class="flex items-start gap-2">
                <i class="fas fa-exclamation-triangle text-red-400 text-sm mt-0.5"></i>
                <div class="text-red-300 text-sm" id="errorText"></div>
            </div>
        </div>
    </div>

    <!-- Help Text -->
    <div class="mt-2 text-xs text-gray-500">
        <div class="flex items-start gap-2">
            <i class="fas fa-info-circle text-blue-400 mt-0.5"></i>
            <div>
                <div class="mb-1">Precise coordinates are required for accurate distance calculations and patient navigation.</div>
                <div>• Type to search for places, addresses, or landmarks</div>
                <div>• Select from dropdown suggestions for best results</div>
                <div>• Use GPS button to get your current location</div>
            </div>
        </div>
    </div>
</div>

                <!-- Date Range -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Start Date *</label>
                    <input type="date" name="start_date" id="campaignStartDate" required 
                        class="w-full px-4 py-2 bg-slate-600 border border-slate-500 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">End Date *</label>
                    <input type="date" name="end_date" id="campaignEndDate" required 
                        class="w-full px-4 py-2 bg-slate-600 border border-slate-500 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <!-- Start and End Time -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Start Time *</label>
                    <input type="time" name="start_time" id="campaignStartTime" required 
                        class="w-full px-4 py-2 bg-slate-600 border border-slate-500 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">End Time *</label>
                    <input type="time" name="end_time" id="campaignEndTime" required 
                        class="w-full px-4 py-2 bg-slate-600 border border-slate-500 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <!-- Camp Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Camp Type *</label>
                    <select name="camp_type" id="campaignType" required 
                        class="w-full px-4 py-2 bg-slate-600 border border-slate-500 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="medical">Medical</option>
                        <option value="surgical">Surgical</option>
                    </select>
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Category *</label>
                    <select name="category_id" id="campaignCategory" required 
                        class="w-full px-4 py-2 bg-slate-600 border border-slate-500 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Select Category</option>
                        @if(isset($categories) && $categories)
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Registration Fee -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        Registration Fee (₹)
                        <span class="text-xs text-gray-400 block mt-1">Amount charged for patient registration</span>
                    </label>
                    <input type="number" name="registration_payment" id="campaignRegistrationFee" min="0" step="0.01" 
                        class="w-full px-4 py-2 bg-slate-600 border border-slate-500 text-white placeholder-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Enter registration fee (leave empty for free registration)">
                </div>

                <!-- Per Refer Cost -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        Per Refer Cost (₹)
                        <span class="text-xs text-gray-400 block mt-1">Amount earned for each successful referral</span>
                    </label>
                    <input type="number" name="per_refer_cost" id="campaignPerReferCost" min="0" step="0.01" 
                        class="w-full px-4 py-2 bg-slate-600 border border-slate-500 text-white placeholder-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Enter amount per referral">
                </div>

                <!-- Sponsor Amount -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Sponsor Amount (₹)</label>
                    <input type="number" name="amount" id="campaignAmount" min="0" step="0.01" 
                        class="w-full px-4 py-2 bg-slate-600 border border-slate-500 text-white placeholder-gray-400 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Enter sponsor amount needed">
                </div>

                <!-- Doctor -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Doctor *</label>
                    <input type="text" value="Dr. {{ auth('doctor')->user()->doctor_name ?? 'Current Doctor' }}" readonly
                        class="w-full px-4 py-2 bg-slate-500 border border-slate-400 text-gray-300 rounded-lg cursor-not-allowed">
                    <input type="hidden" name="doctor_id" value="{{ auth('doctor')->user()->id }}" id="campaignDoctorId">
                    <p class="text-xs text-gray-400 mt-1">Campaign will be created for the current logged-in doctor</p>
                </div>

                <!-- Specializations -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Specializations *</label>
                    <select name="specializations[]" id="campaignSpecializations" multiple required 
                        class="w-full px-4 py-2 bg-slate-600 border border-slate-500 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @if(isset($specialties) && $specialties)
                            @foreach($specialties as $specialty)
                                <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Contact Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Contact Number *</label>
                    <input type="text" name="contact_number" id="campaignContactNumber" required 
                        class="w-full px-4 py-2 bg-slate-600 border border-slate-500 text-white placeholder-gray-400 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <!-- Expected Patients -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Expected Patients *</label>
                    <input type="number" name="expected_patients" id="campaignExpectedPatients" min="1" required 
                        class="w-full px-4 py-2 bg-slate-600 border border-slate-500 text-white placeholder-gray-400 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <!-- Images -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Images (Max 4)</label>
                    <input type="file" name="images[]" id="campaignImages" accept="image/*" multiple 
                        class="w-full px-4 py-2 bg-slate-600 border border-slate-500 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                    <p class="text-xs text-gray-400 mt-1">Choose new images or keep existing ones</p>
                    
                    <!-- Current Images Display -->
                    <div id="currentImages" class="mt-3 hidden">
                        <p class="text-sm text-blue-300 mb-2 flex items-center gap-2">
                            <i class="fas fa-images"></i>
                            Current Images:
                        </p>
                        <div id="currentImagesGrid" class="grid grid-cols-3 gap-2">
                            <!-- Images will be populated here -->
                        </div>
                        <p class="text-xs text-yellow-300 mt-1">
                            <i class="fas fa-info-circle"></i>
                            Uploading new images will replace all existing images
                        </p>
                    </div>
                </div>

                <!-- Thumbnail -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Thumbnail Image</label>
                    <input type="file" name="thumbnail" id="campaignThumbnail" accept="image/*" 
                        class="w-full px-4 py-2 bg-slate-600 border border-slate-500 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                    <p class="text-xs text-gray-400 mt-1">Choose new thumbnail or keep existing one</p>
                    
                    <!-- Current Thumbnail Display -->
                    <div id="currentThumbnail" class="mt-2 hidden">
                        <p class="text-sm text-blue-300 mb-1 flex items-center gap-2">
                            <i class="fas fa-image"></i>
                            Current Thumbnail:
                        </p>
                        <div id="currentThumbnailImg" class="w-20 h-20 rounded-lg overflow-hidden border-2 border-blue-500/30">
                            <!-- Thumbnail will be populated here -->
                        </div>
                        <p class="text-xs text-yellow-300 mt-1">
                            <i class="fas fa-info-circle"></i>
                            Upload new thumbnail to replace current one
                        </p>
                    </div>
                </div>

                <!-- Video -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Video (Max 30MB)</label>
                    <input type="file" name="video" id="campaignVideo" accept="video/*" 
                        class="w-full px-4 py-2 bg-slate-600 border border-slate-500 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                    <p class="text-xs text-gray-400 mt-1">Choose new video or keep existing one</p>
                    
                    <!-- Current Video Display -->
                    <div id="currentVideo" class="mt-2 hidden">
                        <p class="text-sm text-blue-300 mb-1 flex items-center gap-2">
                            <i class="fas fa-video"></i>
                            Current Video:
                        </p>
                        <div id="currentVideoDisplay" class="bg-slate-700 p-3 rounded-lg border border-blue-500/30">
                            <!-- Video info will be populated here -->
                        </div>
                        <p class="text-xs text-yellow-300 mt-1">
                            <i class="fas fa-info-circle"></i>
                            Upload new video to replace current one
                        </p>
                    </div>
                </div>

                <!-- Service in Camp -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Service in Camp *</label>
                    <textarea name="service_in_camp" id="campaignService" rows="3" required
                        class="w-full px-4 py-2 bg-slate-600 border border-slate-500 text-white placeholder-gray-400 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"></textarea>
                </div>

                <!-- Approval Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Approval Status</label>
                    <select name="approval_status" id="campaignApprovalStatus" 
                        class="w-full px-4 py-2 bg-slate-600 border border-slate-500 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="pending">Pending</option>
                     
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" id="cancelModalBtn"
                    class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">Cancel</button>
                <button type="submit" id="submitBtn"
                    class="px-6 py-2 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-lg transition font-medium">
                    <span id="submitBtnText">Add Campaign</span>
                    <i id="submitBtnSpinner" class="fas fa-spinner fa-spin ml-2 hidden"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Patient Registration Modal -->
<div id="patientRegistrationModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-[9998]">
    <div class="bg-white dark:bg-slate-800 rounded-xl p-6 w-full max-w-xl shadow-2xl max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="flex justify-between items-center mb-6 border-b border-gray-200 dark:border-slate-700 pb-4">
            <h2 class="text-2xl font-bold text-slate-800 dark:text-white flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-green-500/10 flex items-center justify-center">
                    <i class="fas fa-user-plus text-green-500"></i>
                </div>
                Patient Registration
            </h2>
            <button type="button" id="closeModalBtn" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="patientRegistrationForm" class="space-y-6">
            @csrf
            <input type="hidden" id="selectedCampaignId" name="campaign_id">
            <input type="hidden" id="selectedCampaignName" name="campaign_name">
            
            <!-- Campaign Info Display -->
            <div class="bg-gradient-to-br from-slate-50 to-blue-50 dark:from-slate-800 dark:to-blue-900/20 p-4 rounded-xl border border-blue-100 dark:border-blue-800 mb-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-lg bg-blue-500/10 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-hospital-alt text-xl text-blue-500"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-800 dark:text-white mb-1" id="campaignDisplayName"></h3>
                        <p class="text-slate-600 dark:text-slate-300 text-sm flex items-center gap-2" id="campaignDisplayLocation">
                            <i class="fas fa-map-marker-alt text-blue-500/70"></i>
                            <span></span>
                        </p>
                        <div class="mt-2 text-sm" id="campaignRegistrationType"></div>
                    </div>
                </div>
            </div>

            <!-- Patient Information Section -->
            <div class="bg-white dark:bg-slate-800/50 rounded-xl border border-slate-200 dark:border-slate-700 p-5 space-y-6">
                <div class="flex items-center gap-2 text-slate-800 dark:text-white mb-2">
                    <i class="fas fa-user-circle text-blue-500"></i>
                    <h4 class="font-medium">Patient Details</h4>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Patient Name -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-1.5">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="patientName" required 
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-slate-900 dark:text-white placeholder-slate-400">
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-1.5">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" id="patientEmail" required 
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-slate-900 dark:text-white">
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-1.5">
                            Phone <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="phone_number" id="patientPhone" required 
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-slate-900 dark:text-white">
                    </div>

                    <!-- Address -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-1.5">
                            Address <span class="text-red-500">*</span>
                        </label>
                        <textarea name="address" id="patientAddress" rows="2" required
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-slate-900 dark:text-white"></textarea>
                    </div>

                    <!-- Medical Concerns -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-1.5">
                            Medical Concerns <span class="text-slate-400 text-xs">(optional)</span>
                        </label>
                        <textarea name="description" id="patientDescription" rows="2"
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-slate-900 dark:text-white"
                            placeholder="Please mention any specific medical conditions or concerns..."></textarea>
                    </div>
                </div>
            </div>

                <!-- Payment Information Section -->
                <div class="mt-6 space-y-4">
                    <!-- Registration Fee Section -->
                    <div id="registrationFeeSection" class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 p-5 rounded-xl border border-blue-200 dark:border-blue-800 hidden">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-blue-500/10 flex items-center justify-center">
                                <i class="fas fa-credit-card text-blue-500"></i>
                            </div>
                            <h3 class="font-semibold text-slate-800 dark:text-white">Registration Fee Details</h3>
                        </div>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between items-center p-2 rounded bg-white/50 dark:bg-slate-800/50">
                                <span class="text-slate-600 dark:text-slate-300">Registration Amount</span>
                                <span id="registrationAmount" class="font-semibold text-blue-600 dark:text-blue-400">₹0</span>
                            </div>
                            <div class="flex justify-between items-center p-2 rounded bg-white/50 dark:bg-slate-800/50">
                                <span class="text-slate-600 dark:text-slate-300">GST (18%)</span>
                                <span id="gstAmount" class="text-slate-600 dark:text-slate-400">₹0</span>
                            </div>
                            <hr class="border-blue-200 dark:border-blue-800">
                            <div class="flex justify-between items-center p-2 rounded bg-blue-500/10 dark:bg-blue-500/5">
                                <span class="font-medium text-blue-800 dark:text-blue-300">Total Amount</span>
                                <span id="totalAmount" class="font-bold text-blue-800 dark:text-blue-300">₹0</span>
                            </div>
                        </div>
                    </div>

                    <!-- Free Registration Notice -->
                    <div id="freeRegistrationSection" class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 p-5 rounded-xl border border-green-200 dark:border-green-800 hidden">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-full bg-green-500/10 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check-circle text-green-500"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-green-800 dark:text-green-400">Free Registration</h4>
                                <p class="text-green-700 dark:text-green-500 text-sm mt-1">
                                    This medical camp offers free registration. You can proceed with your registration without any payment.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            <!-- Payment Modal Section (Hidden initially) -->
            <div id="paymentModalSection" class="hidden mt-6">
                <hr class="mb-6">
                <h3 class="text-xl font-bold text-center text-blue-600 mb-4">
                    <i class="fas fa-credit-card mr-2"></i>Payment Required
                </h3>
                
                <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200 mb-4">
                    <p class="text-yellow-800 text-sm">
                        <i class="fas fa-info-circle mr-2"></i>
                        Complete payment to finalize patient registration. Patient data will only be saved after successful payment.
                    </p>
                </div>
                
                <div class="flex justify-center gap-3">
                    <button type="button" id="payWithRazorpayBtn"
                        class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2 shadow-lg">
                        <i class="fas fa-credit-card"></i>
                        Pay Now with Razorpay
                    </button>
                    <button type="button" id="backToFormBtn"
                        class="px-8 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        Back to Form
                    </button>
                </div>
            </div>

            <!-- Action Buttons Section -->
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" id="cancelPatientModalBtn"
                    class="px-6 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500 transition">Cancel</button>
                
                <!-- For Free Campaigns -->
                <button type="submit" id="submitPatientBtn"
                    class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <i class="fas fa-user-plus mr-2"></i>Register Patient (Free)
                </button>
                
                <!-- For Paid Campaigns -->
                <button type="button" id="proceedToPaymentBtn"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition hidden">
                    <i class="fas fa-credit-card mr-2"></i>Proceed to Payment
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Form submission validation
$(document).ready(function() {
    $('#campaignForm').on('submit', function(e) {
        const lat = document.getElementById('campaignLatitude').value;
        const lng = document.getElementById('campaignLongitude').value;
        
        if (!lat || !lng) {
            e.preventDefault();
            showLocationError('Please select a valid location with coordinates');
            document.getElementById('campaignLocation').focus();
            return false;
        }
        
        console.log('Form submitted with coordinates:', { lat, lng });
    });
    
    // Initialize Google Maps when document is ready
    if (typeof google !== 'undefined' && google.maps) {
        initLocationSearch();
    }
});
</script>
                


<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function () {
    let isEditMode = false;

    const modal = $('#campaignModal');
    const modalTitle = $('#modalTitle');
    const submitBtn = $('#submitBtn');
    const campaignForm = $('#campaignForm');

    $('#openModalBtn').on('click', function () {
        isEditMode = false;
        modalTitle.text('Add New Campaign');
        submitBtn.text('Add Campaign');
        campaignForm[0].reset();
        $('#campaignId').val('');
        
        // Hide current media displays for new campaigns
        $('#currentImages').addClass('hidden');
        $('#currentThumbnail').addClass('hidden');
        $('#currentVideo').addClass('hidden');
        
        modal.removeClass('hidden');
    });

    $('#cancelModalBtn').on('click', function () {
        modal.addClass('hidden');
    });

    // Client-side validation function
    function validateCampaignForm() {
        const errors = [];
        
        // Title validation
        const title = $('#campaignTitle').val().trim();
        if (!title) {
            errors.push('Campaign title is required');
        } else if (title.length < 5) {
            errors.push('Campaign title must be at least 5 characters');
        } else if (title.length > 255) {
            errors.push('Campaign title cannot exceed 255 characters');
        }
        
        // Description validation
        const description = $('#campaignDescription').val().trim();
        if (!description) {
            errors.push('Campaign description is required');
        } else if (description.length < 10) {
            errors.push('Campaign description must be at least 10 characters');
        }
        
        // Location validation
        const location = $('#campaignLocation').val().trim();
        if (!location) {
            errors.push('Campaign location is required');
        }
        
        // Date validation
        const startDate = $('#campaignStartDate').val();
        const endDate = $('#campaignEndDate').val();
        const today = new Date().toISOString().split('T')[0];
        
        if (!startDate) {
            errors.push('Start date is required');
        } else if (startDate < today) {
            errors.push('Start date cannot be in the past');
        }
        
        if (!endDate) {
            errors.push('End date is required');
        } else if (endDate < startDate) {
            errors.push('End date must be same or after start date');
        }
        
        // Time validation
        const startTime = $('#campaignStartTime').val();
        const endTime = $('#campaignEndTime').val();
        
        if (!startTime) {
            errors.push('Start time is required');
        }
        
        if (!endTime) {
            errors.push('End time is required');
        } else if (startTime && endTime <= startTime) {
            errors.push('End time must be after start time');
        }
        
        // Contact number validation
        const contactNumber = $('#campaignContactNumber').val().trim();
        const phoneRegex = /^[0-9+\-\s()]+$/;
        if (!contactNumber) {
            errors.push('Contact number is required');
        } else if (contactNumber.length < 10) {
            errors.push('Contact number must be at least 10 digits');
        } else if (contactNumber.length > 15) {
            errors.push('Contact number cannot exceed 15 characters');
        } else if (!phoneRegex.test(contactNumber)) {
            errors.push('Contact number format is invalid');
        }
        
        // Expected patients validation
        const expectedPatients = parseInt($('#campaignExpectedPatients').val());
        if (!expectedPatients || isNaN(expectedPatients)) {
            errors.push('Expected patients count is required');
        } else if (expectedPatients < 10) {
            errors.push('Expected patients must be at least 10');
        } else if (expectedPatients > 10000) {
            errors.push('Expected patients cannot exceed 10,000');
        }
        
        // Specializations validation
        const selectedSpecializations = $('#campaignSpecializations').val();
        if (!selectedSpecializations || selectedSpecializations.length === 0) {
            errors.push('Please select at least one specialization');
        }
        
        // Amount validation (if camp type requires payment)
        const campType = $('#campaignType').val();
        const registrationPayment = parseFloat($('#campaignAmount').val());
        if (campType && registrationPayment && registrationPayment < 0) {
            errors.push('Registration payment cannot be negative');
        }
        
        return errors;
    }

    campaignForm.on('submit', function (e) {
        e.preventDefault();
        
        // First validate location coordinates
        if (!validateLocation()) {
            return false;
        }
        
        // Validate form before submission
        const validationErrors = validateCampaignForm();
        if (validationErrors.length > 0) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: validationErrors.map(error => `• ${error}`).join('<br>'),
                confirmButtonText: 'Fix Errors'
            });
            return false;
        }
        
        const formData = new FormData(this);
        
        // Ensure coordinates are included
        const lat = document.getElementById('campaignLatitude').value;
        const lng = document.getElementById('campaignLongitude').value;
        formData.set('latitude', lat);
        formData.set('longitude', lng);
        
        const url = isEditMode ? 
            `/doctor/campaigns/${$('#campaignId').val()}` : 
            '/doctor/campaigns';

        if (isEditMode) {
            formData.append('_method', 'PUT');
        }

        // Show loading state
        $('#submitBtnText').text('Saving...');
        $('#submitBtnSpinner').removeClass('hidden');
        submitBtn.prop('disabled', true);

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function (data) {
                $('#submitBtnText').text(isEditMode ? 'Update Campaign' : 'Add Campaign');
                $('#submitBtnSpinner').addClass('hidden');
                submitBtn.prop('disabled', false);
                
                if (data.success) {
                    modal.addClass('hidden');
                    location.reload();
                    Swal.fire({
                        icon: 'success',
                        title: isEditMode ? 'Updated!' : 'Added!',
                        text: `Campaign ${isEditMode ? 'updated' : 'added'} successfully.`,
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire('Error!', data.message || 'Failed.', 'error');
                }
            },
            error: function (xhr) {
                $('#submitBtnText').text(isEditMode ? 'Update Campaign' : 'Add Campaign');
                $('#submitBtnSpinner').addClass('hidden');
                submitBtn.prop('disabled', false);
                
                console.error('Campaign save failed:', xhr.responseText);
                console.error('Status:', xhr.status);
                
                let errorMessage = 'Unexpected error occurred.';
                
                // Handle validation errors
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = Object.values(xhr.responseJSON.errors).flat();
                    errorMessage = errors.join('\n');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.status === 413) {
                    errorMessage = 'File size too large. Please upload smaller files.';
                } else if (xhr.status === 500) {
                    errorMessage = 'Server error occurred. Please try again later.';
                }
                
                Swal.fire('Error!', errorMessage, 'error');
            }
        });
    });

    $(document).on('click', '.edit-btn', function (e) {
        e.stopPropagation();
        const card = $(this).closest('.campaign-card');
        const id = card.data('id');

        // Reset form first
        campaignForm[0].reset();
        
        // Hide current media displays
        $('#currentImages').addClass('hidden');
        $('#currentThumbnail').addClass('hidden');
        $('#currentVideo').addClass('hidden');

        $.get(`/doctor/campaigns/${id}`, function (campaign) {
            isEditMode = true;
            modalTitle.text('Edit Campaign');
            submitBtn.text('Update Campaign');

            // Populate form with campaign data
            $('#campaignId').val(campaign.id);
            $('#campaignTitle').val(campaign.title);
            $('#campaignDescription').val(campaign.description);
            $('#campaignLocation').val(campaign.location);
            $('#campaignRegistrationFee').val(campaign.registration_payment || '');
            
            // Format dates properly for date inputs
            if (campaign.start_date) {
                const startDate = new Date(campaign.start_date);
                $('#campaignStartDate').val(startDate.toISOString().split('T')[0]);
            }
            if (campaign.end_date) {
                const endDate = new Date(campaign.end_date);
                $('#campaignEndDate').val(endDate.toISOString().split('T')[0]);
            }
            
            // Set time values
            if (campaign.start_time) {
                $('#campaignStartTime').val(campaign.start_time.substring(0, 5)); // Extract HH:MM from HH:MM:SS
            }
            if (campaign.end_time) {
                $('#campaignEndTime').val(campaign.end_time.substring(0, 5)); // Extract HH:MM from HH:MM:SS
            }
            
            $('#campaignType').val(campaign.camp_type);
            $('#campaignCategory').val(campaign.category_id);
            $('#campaignAmount').val(campaign.amount);
            $('#campaignPerReferCost').val(campaign.per_refer_cost || 0);
            $('#campaignContactNumber').val(campaign.contact_number);
            $('#campaignExpectedPatients').val(campaign.expected_patients);
            $('#campaignService').val(campaign.service_in_camp);
            $('#campaignApprovalStatus').val(campaign.approval_status || 'pending');

            // Handle specializations
            if (campaign.specializations) {
                $('#campaignSpecializations').val(campaign.specializations);
            }

            // Display current images
            if (campaign.images && campaign.images.length > 0) {
                $('#currentImages').removeClass('hidden');
                let imagesHtml = '';
                campaign.images.forEach(function(image, index) {
                    imagesHtml += `
                        <div class="relative group">
                            <img src="{{ asset('storage/') }}/${image}" 
                                 alt="Campaign Image ${index + 1}" 
                                 class="w-full h-16 object-cover rounded-lg border border-blue-500/30">
                            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                                <span class="text-white text-xs font-medium">Image ${index + 1}</span>
                            </div>
                        </div>
                    `;
                });
                $('#currentImagesGrid').html(imagesHtml);
            }

            // Display current thumbnail
            if (campaign.thumbnail) {
                $('#currentThumbnail').removeClass('hidden');
                $('#currentThumbnailImg').html(`
                    <img src="{{ asset('storage/') }}/${campaign.thumbnail}" 
                         alt="Current Thumbnail" 
                         class="w-full h-full object-cover">
                `);
            }

            // Display current video
            if (campaign.video) {
                $('#currentVideo').removeClass('hidden');
                const videoName = campaign.video.split('/').pop();
                const videoSize = 'Unknown size'; // You could get this from the backend if needed
                $('#currentVideoDisplay').html(`
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-600/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-video text-green-400"></i>
                            </div>
                            <div>
                                <div class="text-white font-medium">${videoName}</div>
                                <div class="text-gray-400 text-xs">${videoSize}</div>
                            </div>
                        </div>
                        <a href="{{ asset('storage/') }}/${campaign.video}" 
                           target="_blank" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs font-medium transition-colors">
                            <i class="fas fa-external-link-alt mr-1"></i> Preview
                        </a>
                    </div>
                `);
            }

            modal.removeClass('hidden');
        }).fail(function(xhr) {
            console.error('Failed to load campaign data:', xhr.responseText);
            Swal.fire('Error!', 'Failed to load campaign data for editing.', 'error');
        });
    });

    $(document).on('click', '.delete-btn', function (e) {
        e.stopPropagation();
        const card = $(this).closest('.campaign-card');
        const id = card.data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "This campaign will be permanently deleted.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e3342f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/doctor/campaigns/${id}`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        if (data.success) {
                            card.remove();
                            checkNoResults();
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Campaign has been deleted.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire('Error!', data.message || 'Failed to delete campaign.', 'error');
                        }
                    },
                    error: function(xhr) {
                        console.error('Delete failed:', xhr.responseText);
                        Swal.fire('Error!', 'Failed to delete campaign.', 'error');
                    }
                });
            }
        });
    });

    $(document).on('click', '.view-btn', function (e) {
        e.stopPropagation();
        const card = $(this).closest('.campaign-card');
        const id = card.data('id');
        
        // Redirect to doctor campaign detail page
        window.location.href = `/doctor/campaigns/${id}/view`;
    });

    $(document).on('click', '.register-patient-btn', function (e) {
        e.stopPropagation();
        const card = $(this).closest('.campaign-card');
        const campaignId = card.data('id');
        const campaignTitle = card.find('h3').text();
        const campaignLocation = card.find('.text-blue-300').text();
        
        // Get campaign data including registration fee
        $.get(`/doctor/campaigns/${campaignId}`, function(campaign) {
            // Set campaign data in the modal
            $('#selectedCampaignId').val(campaignId);
            $('#selectedCampaignName').val(campaignTitle);
            $('#campaignDisplayName').text(campaignTitle);
            $('#campaignDisplayLocation').text(campaignLocation);
            
            // Reset and show patient registration form
            $('#patientRegistrationForm')[0].reset();
            $('#selectedCampaignId').val(campaignId);
            
            // Check if registration fee is required
            if (campaign.registration_payment && campaign.registration_payment > 0) {
                const registrationFee = parseFloat(campaign.registration_payment);
                const adminCommission = registrationFee * 0.05; // 5% admin commission
                const doctorAmount = registrationFee - adminCommission;
                
                // Show payment section
                $('#registrationFeeSection').removeClass('hidden');
                $('#freeRegistrationSection').addClass('hidden');
                
                // Update amounts
                $('#registrationAmount').text(`₹${registrationFee.toFixed(2)}`);
                $('#adminCommission').text(`₹${adminCommission.toFixed(2)}`);
                $('#doctorAmount').text(`₹${doctorAmount.toFixed(2)}`);
                $('#totalAmount').text(`₹${registrationFee.toFixed(2)}`);
                
                // Show proceed to payment button, hide submit button
                $('#submitPatientBtn').addClass('hidden');
                $('#proceedToPaymentBtn').removeClass('hidden');
            } else {
                // Show free registration notice for free campaigns
                $('#registrationFeeSection').addClass('hidden');
                $('#freeRegistrationSection').removeClass('hidden');
                $('#submitPatientBtn').removeClass('hidden');
                $('#proceedToPaymentBtn').addClass('hidden');
            }
            
            // Reset payment modal
            $('#paymentModalSection').addClass('hidden');
            
            $('#patientRegistrationModal').removeClass('hidden');
        }).fail(function() {
            // Fallback if campaign details endpoint doesn't exist
            $('#selectedCampaignId').val(campaignId);
            $('#selectedCampaignName').val(campaignTitle);
            $('#campaignDisplayName').text(campaignTitle);
            $('#campaignDisplayLocation').text(campaignLocation);
            
            $('#patientRegistrationForm')[0].reset();
            $('#selectedCampaignId').val(campaignId);
            
            // Default to free registration
            $('#registrationFeeSection').addClass('hidden');
            $('#freeRegistrationSection').removeClass('hidden');
            $('#submitPatientBtn').removeClass('hidden');
            $('#proceedToPaymentBtn').addClass('hidden');
            $('#paymentModalSection').addClass('hidden');
            
            $('#patientRegistrationModal').removeClass('hidden');
        });
    });

    $('#cancelPatientModalBtn').on('click', function () {
        $('#patientRegistrationModal').addClass('hidden');
    });

    // Proceed to Payment Button
    $('#proceedToPaymentBtn').on('click', function() {
        const patientName = $('#patientName').val();
        const patientEmail = $('#patientEmail').val();
        const patientPhone = $('#patientPhone').val();
        const patientAddress = $('#patientAddress').val();
        
        if (!patientName || !patientEmail || !patientPhone || !patientAddress) {
            Swal.fire('Error!', 'Please fill in all required patient details before proceeding to payment.', 'error');
            return;
        }
        
        // Hide form buttons and show payment section
        $('#proceedToPaymentBtn').addClass('hidden');
        $('#submitPatientBtn').addClass('hidden');
        $('#paymentModalSection').removeClass('hidden');
    });

    // Back to Form Button
    $('#backToFormBtn').on('click', function() {
        // Show form buttons and hide payment section
        $('#proceedToPaymentBtn').removeClass('hidden');
        $('#submitPatientBtn').addClass('hidden');
        $('#paymentModalSection').addClass('hidden');
    });

    $('#patientRegistrationForm').on('submit', function (e) {
        e.preventDefault();
        
        // Only allow free registrations through normal submit
        const registrationFee = parseFloat($('#registrationAmount').text().replace('₹', '')) || 0;
        if (registrationFee > 0) {
            Swal.fire('Error!', 'This is a paid campaign. Please use the payment option.', 'error');
            return;
        }
        
        submitPatientRegistration(new FormData(this), 'free');
    });

    // Payment button handlers
    $('#payWithRazorpayBtn').on('click', function() {
        const registrationAmount = parseFloat($('#registrationAmount').text().replace('₹', ''));
        const campaignName = $('#selectedCampaignName').val();
        const patientName = $('#patientName').val();
        const patientEmail = $('#patientEmail').val();
        const patientPhone = $('#patientPhone').val();
        
        if (!patientName || !patientEmail || !patientPhone) {
            Swal.fire('Error!', 'Please fill in all required patient details before proceeding to payment.', 'error');
            return;
        }
        
        // Initialize Razorpay payment
        const options = {
            key: 'rzp_test_your_key_here', // Replace with actual Razorpay key
            amount: registrationAmount * 100, // Amount in paise
            currency: 'INR',
            name: 'FreeDoctor Medical Camps',
            description: `Registration fee for ${campaignName}`,
            prefill: {
                name: patientName,
                email: patientEmail,
                contact: patientPhone
            },
            theme: {
                color: '#3B82F6'
            },
            handler: function(response) {
                // Payment successful, submit form with payment details
                const formData = new FormData($('#patientRegistrationForm')[0]);
                formData.append('payment_id', response.razorpay_payment_id);
                formData.append('payment_status', 'paid');
                formData.append('payment_amount', registrationAmount);
                
                submitPatientRegistration(formData, 'paid');
            },
            modal: {
                ondismiss: function() {
                    console.log('Payment cancelled');
                }
            }
        };
        
        const rzp = new Razorpay(options);
        rzp.open();
    });

    function submitPatientRegistration(formData, paymentType = 'free') {
        $.ajax({
            url: '/doctor/patient-registrations',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function (data) {
                if (data.success) {
                    $('#patientRegistrationModal').addClass('hidden');
                    // Reset payment modal
                    $('#paymentModalSection').addClass('hidden');
                    $('#proceedToPaymentBtn').removeClass('hidden');
                    
                    let message = paymentType === 'paid' ? 
                        'Payment successful! Patient registered.' : 
                        'Patient registered successfully!';
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Registration Successful!',
                        text: message,
                        timer: 3000,
                        showConfirmButton: false
                    });
                    $('#patientRegistrationForm')[0].reset();
                } else {
                    Swal.fire('Error!', data.message || 'Registration failed.', 'error');
                }
            },
            error: function (xhr) {
                console.error('Registration failed:', xhr.responseText);
                
                let errorMessage = 'Registration failed.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).flat().join('\n');
                }
                
                Swal.fire('Error!', errorMessage, 'error');
            }
        });
    }

    function filterCampaigns() {
        const titleSearch = $('#searchTitle').val().toLowerCase();
        const locationSearch = $('#searchLocation').val().toLowerCase();
        const specialtyFilter = $('#specialtyFilter').val();
        const typeFilter = $('#typeFilter').val();

        let visibleCount = 0;

        $('.campaign-card').each(function () {
            const card = $(this);
            const title = card.data('title');
            const location = card.data('location');
            const type = card.data('type');
            const specializations = card.data('specializations').toString();

            const matchesTitle = title.includes(titleSearch);
            const matchesLocation = location.includes(locationSearch);
            const matchesSpecialty = !specialtyFilter || specializations.includes(specialtyFilter);
            const matchesType = !typeFilter || type === typeFilter;

            if (matchesTitle && matchesLocation && matchesSpecialty && matchesType) {
                card.show();
                visibleCount++;
            } else {
                card.hide();
            }
        });

        checkNoResults();
    }

    function checkNoResults() {
        const visibleCards = $('.campaign-card:visible');
        if (visibleCards.length === 0) {
            $('#noResults').removeClass('hidden');
        } else {
            $('#noResults').addClass('hidden');
        }
    }

    $('#searchTitle, #searchLocation, #specialtyFilter, #typeFilter').on('input change', filterCampaigns);

    modal.on('click', function (e) {
        if (e.target === this) {
            modal.addClass('hidden');
        }
    });

    $('#patientRegistrationModal').on('click', function (e) {
        if (e.target === this) {
            $('#patientRegistrationModal').addClass('hidden');
        }
    });

    // Show/hide amount field based on camp type
    $('#campaignType').on('change', function() {
        const amountField = $('#campaignAmount').closest('div');
        if ($(this).val() === 'paid') {
            amountField.show();
            $('#campaignAmount').attr('required', true);
        } else {
            amountField.hide();
            $('#campaignAmount').removeAttr('required').val('');
        }
    });

    // Sponsor button handler
    $(document).on('click', '.sponsor-btn', function() {
        const campaignId = $(this).data('campaign-id');
        
        // Open sponsor form in new tab/window
        window.open(`/sponsor/form/${campaignId}`, '_blank');
    });
});
</script>
<!-- Google Maps API -->
<script>
    // Global variables for location services
    let userLatitude = null;
    let userLongitude = null;
    let autocomplete = null;
    let map = null;
    let marker = null;
    let geocoder = null;

    // Initialize Google Maps - this is the callback function
    function initMaps() {
        console.log('Google Maps API loaded, initializing location services...');
        
        // Initialize geocoder
        geocoder = new google.maps.Geocoder();
        
        // Initialize location search functionality
        initLocationSearch();
    }

    // Initialize Google Maps location services
    function initLocationSearch() {
        console.log('Setting up location search functionality...');

        // Initialize autocomplete for location input
        const locationInput = document.getElementById('campaignLocation');
        if (locationInput && typeof google !== 'undefined' && google.maps && google.maps.places) {
            try {
                autocomplete = new google.maps.places.Autocomplete(locationInput, {
                    types: ['geocode', 'establishment'],
                    componentRestrictions: {
                        country: 'IN'
                    },
                    fields: ['formatted_address', 'geometry', 'name', 'place_id']
                });

                // Listen for place selection
                autocomplete.addListener('place_changed', function() {
                    const place = autocomplete.getPlace();
                    console.log('Place selected:', place);

                    if (place.geometry && place.geometry.location) {
                        userLatitude = place.geometry.location.lat();
                        userLongitude = place.geometry.location.lng();

                        // Set the coordinates in hidden fields
                        document.getElementById('campaignLatitude').value = userLatitude;
                        document.getElementById('campaignLongitude').value = userLongitude;

                        console.log('Location coordinates set:', {
                            lat: userLatitude,
                            lng: userLongitude,
                            address: place.formatted_address || place.name
                        });

                        // Update UI feedback
                        updateLocationFeedback(userLatitude, userLongitude, place.formatted_address || place.name);
                        
                        // Show location verification
                        showLocationVerification(true);
                        
                        // Show mini map if container exists
                        showLocationOnMap(userLatitude, userLongitude);
                    } else {
                        console.warn('Selected place has no geometry');
                        showLocationError('Please select a valid location from the dropdown suggestions');
                        clearLocationData();
                    }
                });

                // Handle manual input validation
                locationInput.addEventListener('blur', function() {
                    const inputValue = this.value.trim();
                    if (inputValue && (!userLatitude || !userLongitude)) {
                        // If user typed something but didn't select from autocomplete
                        console.log('Manual input detected, attempting geocoding...');
                        geocodeAddress(inputValue);
                    }
                });

                console.log('Autocomplete initialized successfully');
            } catch (error) {
                console.error('Error initializing autocomplete:', error);
                showLocationError('Failed to initialize location search. Please refresh the page.');
            }
        } else {
            console.error('Google Maps Places API not available');
        }

        // Initialize current location button
        const getCurrentLocationBtn = document.getElementById('getCurrentLocationBtn');
        if (getCurrentLocationBtn) {
            getCurrentLocationBtn.addEventListener('click', function(e) {
                e.preventDefault();
                getCurrentLocation();
            });
        }
    }

    // Geocode address manually entered by user
    function geocodeAddress(address) {
        if (!geocoder) {
            console.error('Geocoder not initialized');
            return;
        }

        geocoder.geocode({ address: address, componentRestrictions: { country: 'IN' } }, function(results, status) {
            if (status === 'OK' && results[0]) {
                const location = results[0].geometry.location;
                userLatitude = location.lat();
                userLongitude = location.lng();

                // Set coordinates in hidden fields
                document.getElementById('campaignLatitude').value = userLatitude;
                document.getElementById('campaignLongitude').value = userLongitude;

                // Update location input with formatted address
                document.getElementById('campaignLocation').value = results[0].formatted_address;

                console.log('Address geocoded successfully:', {
                    lat: userLatitude,
                    lng: userLongitude,
                    address: results[0].formatted_address
                });

                // Update UI feedback
                updateLocationFeedback(userLatitude, userLongitude, results[0].formatted_address);
                showLocationVerification(true);
                showLocationOnMap(userLatitude, userLongitude);
            } else {
                console.error('Geocoding failed:', status);
                showLocationError('Could not find the specified location. Please try a different address.');
                clearLocationData();
            }
        });
    }

    // Get user's current location
    function getCurrentLocation() {
        const btn = document.getElementById('getCurrentLocationBtn');
        const locationInput = document.getElementById('campaignLocation');

        if (!navigator.geolocation) {
            showLocationError('Geolocation is not supported by this browser');
            return;
        }

        // Show loading state
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        btn.disabled = true;
        btn.title = 'Getting location...';

        const options = {
            enableHighAccuracy: true,
            timeout: 15000,
            maximumAge: 300000 // 5 minutes
        };

        navigator.geolocation.getCurrentPosition(
            function(position) {
                userLatitude = position.coords.latitude;
                userLongitude = position.coords.longitude;

                // Set coordinates in hidden fields
                document.getElementById('campaignLatitude').value = userLatitude;
                document.getElementById('campaignLongitude').value = userLongitude;

                console.log('Current location detected:', {
                    lat: userLatitude,
                    lng: userLongitude,
                    accuracy: position.coords.accuracy
                });

                // Reverse geocode to get address
                if (geocoder) {
                    const latlng = new google.maps.LatLng(userLatitude, userLongitude);

                    geocoder.geocode({ location: latlng }, function(results, status) {
                        if (status === 'OK' && results[0]) {
                            locationInput.value = results[0].formatted_address;
                            
                            // Update UI feedback
                            updateLocationFeedback(userLatitude, userLongitude, results[0].formatted_address);
                            showLocationVerification(true);
                            showLocationOnMap(userLatitude, userLongitude);

                            console.log('Address resolved:', results[0].formatted_address);
                        } else {
                            console.error('Reverse geocoding failed:', status);
                            locationInput.value = `${userLatitude.toFixed(6)}, ${userLongitude.toFixed(6)}`;
                            updateLocationFeedback(userLatitude, userLongitude, 'Current Location');
                            showLocationVerification(true);
                            showLocationOnMap(userLatitude, userLongitude);
                        }
                        
                        // Reset button
                        resetLocationButton(btn);
                    });
                } else {
                    // Fallback if geocoder not available
                    locationInput.value = `${userLatitude.toFixed(6)}, ${userLongitude.toFixed(6)}`;
                    updateLocationFeedback(userLatitude, userLongitude, 'Current Location');
                    showLocationVerification(true);
                    resetLocationButton(btn);
                }
            },
            function(error) {
                console.error('Geolocation error:', error);
                let errorMessage = 'Could not detect location';
                
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        errorMessage = 'Location access denied. Please enable location permissions and try again.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage = 'Location information unavailable. Please try again or enter address manually.';
                        break;
                    case error.TIMEOUT:
                        errorMessage = 'Location request timed out. Please try again.';
                        break;
                    default:
                        errorMessage = 'An unknown error occurred while getting location.';
                        break;
                }
                
                showLocationError(errorMessage);
                resetLocationButton(btn);
            },
            options
        );
    }

    // Reset location button to original state
    function resetLocationButton(btn) {
        btn.innerHTML = '<i class="fas fa-location-crosshairs"></i>';
        btn.disabled = false;
        btn.title = 'Use current location';
    }

    // Show location on mini map
    function showLocationOnMap(lat, lng) {
        const mapContainer = document.getElementById('locationMap');
        if (mapContainer && typeof google !== 'undefined' && google.maps) {
            mapContainer.classList.remove('hidden');
            
            // Initialize map
            map = new google.maps.Map(mapContainer, {
                center: { lat: lat, lng: lng },
                zoom: 15,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                disableDefaultUI: true,
                zoomControl: true,
                styles: [
                    {
                        featureType: 'all',
                        elementType: 'geometry.fill',
                        stylers: [{ color: '#1e293b' }]
                    },
                    {
                        featureType: 'water',
                        elementType: 'geometry',
                        stylers: [{ color: '#0f172a' }]
                    }
                ]
            });

            // Add marker
            if (marker) {
                marker.setMap(null);
            }
            
            marker = new google.maps.Marker({
                position: { lat: lat, lng: lng },
                map: map,
                title: 'Campaign Location',
                icon: {
                    url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="16" cy="16" r="8" fill="#3B82F6"/>
                            <circle cx="16" cy="16" r="4" fill="white"/>
                        </svg>
                    `),
                    scaledSize: new google.maps.Size(32, 32),
                    anchor: new google.maps.Point(16, 16)
                }
            });
        }
    }

    // Update location feedback display
    function updateLocationFeedback(lat, lng, address) {
        const coordinatesDisplay = document.getElementById('coordinatesDisplay');
        const addressVerification = document.getElementById('addressVerification');
        
        if (coordinatesDisplay) {
            coordinatesDisplay.textContent = `Coordinates: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
            coordinatesDisplay.classList.remove('hidden');
        }
        
        if (addressVerification) {
            addressVerification.innerHTML = '<i class="fas fa-check-circle"></i> Location verified';
            addressVerification.classList.remove('hidden');
        }
        
        // Show feedback div
        const feedbackDiv = document.getElementById('locationFeedback');
        if (feedbackDiv) {
            feedbackDiv.classList.remove('hidden');
        }
    }

    // Clear location data
    function clearLocationData() {
        userLatitude = null;
        userLongitude = null;
        document.getElementById('campaignLatitude').value = '';
        document.getElementById('campaignLongitude').value = '';
        
        // Hide feedback
        const coordinatesDisplay = document.getElementById('coordinatesDisplay');
        const addressVerification = document.getElementById('addressVerification');
        const feedbackDiv = document.getElementById('locationFeedback');
        
        if (coordinatesDisplay) coordinatesDisplay.classList.add('hidden');
        if (addressVerification) addressVerification.classList.add('hidden');
        if (feedbackDiv) feedbackDiv.classList.add('hidden');
        
        showLocationVerification(false);
        
        // Hide map
        const mapContainer = document.getElementById('locationMap');
        if (mapContainer) {
            mapContainer.classList.add('hidden');
        }
    }

    // Show location verification status
    function showLocationVerification(isVerified) {
        const status = document.getElementById('locationStatus');
        if (status) {
            if (isVerified) {
                status.classList.remove('hidden');
            } else {
                status.classList.add('hidden');
            }
        }
    }

    // Show location error
    function showLocationError(message) {
        console.error('Location error:', message);
        
        // Show error using SweetAlert if available
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'warning',
                title: 'Location Error',
                text: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true
            });
        } else {
            // Fallback to alert
            alert('Location Error: ' + message);
        }
        
        showLocationVerification(false);
    }

    // Validate location before form submission
    function validateLocation() {
        const lat = document.getElementById('campaignLatitude').value;
        const lng = document.getElementById('campaignLongitude').value;
        const locationInput = document.getElementById('campaignLocation').value.trim();
        
        if (!locationInput) {
            showLocationError('Please enter a campaign location');
            document.getElementById('campaignLocation').focus();
            return false;
        }
        
        if (!lat || !lng || lat === '0' || lng === '0') {
            showLocationError('Please select a valid location with coordinates. Use the GPS button or select from dropdown suggestions.');
            document.getElementById('campaignLocation').focus();
            return false;
        }
        
        // Validate coordinate ranges (basic check)
        const latitude = parseFloat(lat);
        const longitude = parseFloat(lng);
        
        if (isNaN(latitude) || isNaN(longitude)) {
            showLocationError('Invalid coordinates detected. Please select location again.');
            return false;
        }
        
        // Check if coordinates are within reasonable bounds for India
        if (latitude < 6 || latitude > 37 || longitude < 68 || longitude > 98) {
            showLocationError('Location appears to be outside India. Please verify the location.');
            return false;
        }
        
        console.log('Location validation passed:', { lat: latitude, lng: longitude });
        return true;
    }

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, checking for Google Maps API...');
        
        // Check if Google Maps API is already loaded
        if (typeof google !== 'undefined' && google.maps) {
            console.log('Google Maps API already loaded');
            initMaps();
        } else {
            console.log('Waiting for Google Maps API to load...');
        }
    });
</script>

<!-- Load Google Maps API with callback -->
<script
    src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initMaps"
    async
    defer
    onerror="console.error('Failed to load Google Maps API')"
></script>

@endsection
