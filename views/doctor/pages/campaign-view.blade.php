@extends('doctor.master')


@push('styles')
<style>
    .glass-effect {
        background: rgba(30, 41, 59, 0.15);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(148, 163, 184, 0.2);
    }
    
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
</style>
@endpush

@section('title', 'Campaign Details - FreeDoctor')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 p-6 space-y-8">
    <!-- Campaign Header -->
    <div class="glass-effect rounded-xl p-8 border border-slate-600/30 backdrop-blur-sm">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-2xl">
                    <i class="fas fa-calendar-alt text-2xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-white">{{ $campaign->title }}</h1>
                    <p class="text-slate-300 mt-2 text-lg flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-red-400"></i>{{ $campaign->location }}
                    </p>
                    <div class="flex items-center gap-4 mt-2">
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
            
            <div class="flex items-center gap-4">
                <div class="text-center">
                    <div class="text-blue-400 text-sm">Campaign Start</div>
                    <div class="text-white font-semibold">{{ \Carbon\Carbon::parse($campaign->start_date)->format('M j, Y') }}</div>
                </div>
                <div class="w-px h-12 bg-slate-600"></div>
                <div class="text-center">
                    <div class="text-red-400 text-sm">Campaign End</div>
                    <div class="text-white font-semibold">{{ \Carbon\Carbon::parse($campaign->end_date)->format('M j, Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="stat-card glass-effect rounded-xl p-6 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-sm">Total Patients</p>
                    <p class="text-2xl font-bold text-white">{{ $totalPatients }}</p>
                </div>
                <div class="bg-blue-500/20 p-3 rounded-lg">
                    <i class="fas fa-users text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card glass-effect rounded-xl p-6 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-sm">Total Sponsors</p>
                    <p class="text-2xl font-bold text-white">{{ $totalSponsors }}</p>
                </div>
                <div class="bg-green-500/20 p-3 rounded-lg">
                    <i class="fas fa-handshake text-green-400 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card glass-effect rounded-xl p-6 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-sm">Sponsor Amount</p>
                    <p class="text-2xl font-bold text-white">₹{{ number_format($totalSponsorAmount) }}</p>
                </div>
                <div class="bg-yellow-500/20 p-3 rounded-lg">
                    <i class="fas fa-coins text-yellow-400 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card glass-effect rounded-xl p-6 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-sm">Total Earnings</p>
                    <p class="text-2xl font-bold text-white">₹{{ number_format($totalEarnings) }}</p>
                </div>
                <div class="bg-purple-500/20 p-3 rounded-lg">
                    <i class="fas fa-chart-line text-purple-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Campaign Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Campaign Information -->
            <div class="glass-effect rounded-xl p-6 border border-slate-600/30 backdrop-blur-sm">
                <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-3">
                    <i class="fas fa-info-circle text-blue-400"></i>
                    Campaign Information
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-slate-800/50 p-4 rounded-lg border border-slate-600/20">
                        <label class="text-slate-400 text-sm block mb-2">Location</label>
                        <div class="text-white font-medium flex items-center gap-2">
                            <i class="fas fa-map-marker-alt text-red-400"></i>
                            {{ $campaign->location }}
                        </div>
                    </div>
                    
                    <div class="bg-slate-800/50 p-4 rounded-lg border border-slate-600/20">
                        <label class="text-slate-400 text-sm block mb-2">Duration</label>
                        <div class="text-white font-medium flex items-center gap-2">
                            <i class="fas fa-calendar text-green-400"></i>
                            {{ \Carbon\Carbon::parse($campaign->start_date)->format('M j') }} - {{ \Carbon\Carbon::parse($campaign->end_date)->format('M j, Y') }}
                        </div>
                    </div>
                    
                    <div class="bg-slate-800/50 p-4 rounded-lg border border-slate-600/20">
                        <label class="text-slate-400 text-sm block mb-2">Timings</label>
                        <div class="text-white font-medium flex items-center gap-2">
                            <i class="fas fa-clock text-blue-400"></i>
                            {{ $campaign->timings }}
                        </div>
                    </div>
                    
                    <div class="bg-slate-800/50 p-4 rounded-lg border border-slate-600/20">
                        <label class="text-slate-400 text-sm block mb-2">Expected Patients</label>
                        <div class="text-white font-medium flex items-center gap-2">
                            <i class="fas fa-users text-purple-400"></i>
                            {{ $campaign->expected_patients }}
                        </div>
                    </div>
                    
                    <div class="bg-slate-800/50 p-4 rounded-lg border border-slate-600/20">
                        <label class="text-slate-400 text-sm block mb-2">Registration Fee</label>
                        <div class="text-white font-medium flex items-center gap-2">
                            <i class="fas fa-rupee-sign text-yellow-400"></i>
                            @if($campaign->registration_payment)
                                ₹{{ number_format($campaign->registration_payment) }}
                            @else
                                Free
                            @endif
                        </div>
                    </div>
                    
                    <div class="bg-slate-800/50 p-4 rounded-lg border border-slate-600/20">
                        <label class="text-slate-400 text-sm block mb-2">Contact</label>
                        <div class="text-white font-medium flex items-center gap-2">
                            <i class="fas fa-phone text-green-400"></i>
                            {{ $campaign->contact_number }}
                        </div>
                    </div>
                </div>
                
                <div class="mt-6">
                    <label class="text-slate-400 text-sm block mb-3">Description</label>
                    <div class="bg-slate-800/50 p-4 rounded-lg border border-slate-600/20">
                        <p class="text-white leading-relaxed">{{ $campaign->description }}</p>
                    </div>
                </div>
                
                <div class="mt-6">
                    <label class="text-slate-400 text-sm block mb-3">Services in Camp</label>
                    <div class="bg-slate-800/50 p-4 rounded-lg border border-slate-600/20">
                        <p class="text-white leading-relaxed">{{ $campaign->service_in_camp }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="glass-effect rounded-xl p-6 border border-slate-600/30 backdrop-blur-sm">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-bolt text-yellow-400"></i>
                    Quick Actions
                </h3>
                
                <div class="space-y-3">
                    <a href="{{ route('doctor.campaigns.edit', $campaign->id) }}" 
                       class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-4 py-3 rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center gap-2 font-medium">
                        <i class="fas fa-edit"></i>Edit Campaign
                    </a>
                    
                    <a href="{{ route('doctor.campaigns') }}" 
                       class="w-full bg-gradient-to-r from-slate-600 to-slate-700 hover:from-slate-700 hover:to-slate-800 text-white px-4 py-3 rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center gap-2 font-medium">
                        <i class="fas fa-arrow-left"></i>Back to Campaigns
                    </a>
                </div>
            </div>
            
            <!-- Campaign Timeline -->
            <div class="glass-effect rounded-xl p-6 border border-slate-600/30 backdrop-blur-sm">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-chart-bar text-green-400"></i>
                    Campaign Timeline
                </h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Created:</span>
                        <span class="text-white">{{ \Carbon\Carbon::parse($campaign->created_at)->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
