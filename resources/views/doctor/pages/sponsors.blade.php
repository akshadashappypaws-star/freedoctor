@extends('doctor.master')

@section('title', 'Sponsorships & Funding - FreeDoctor')

@push('styles')
<style>
    /* Enhanced glass effect cards */
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

    /* Sponsor card specific styling */
    .sponsor-card {
        background: rgba(30, 41, 59, 0.15);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(148, 163, 184, 0.3) !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }

    .sponsor-card:hover {
        background: rgba(30, 41, 59, 0.25);
        border-color: rgba(59, 130, 246, 0.6) !important;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
        transform: translateY(-3px);
    }

    /* Modern button styling */
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

    .btn-outline {
        background: rgba(30, 41, 59, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(148, 163, 184, 0.3) !important;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        color: #f8fafc;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-outline:hover {
        background: rgba(99, 102, 241, 0.1);
        border-color: rgba(99, 102, 241, 0.6) !important;
        transform: translateY(-1px);
        color: #f8fafc;
    }

    /* Status badges */
    .status-badge {
        padding: 0.375rem 0.875rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }

    .status-active {
        background: rgba(34, 197, 94, 0.2);
        color: #10b981;
        border-color: rgba(34, 197, 94, 0.3);
    }

    .status-pending {
        background: rgba(251, 191, 36, 0.2);
        color: #f59e0b;
        border-color: rgba(251, 191, 36, 0.3);
    }

    .status-expired {
        background: rgba(239, 68, 68, 0.2);
        color: #ef4444;
        border-color: rgba(239, 68, 68, 0.3);
    }

    /* Gradient text */
    .gradient-text {
        background: linear-gradient(135deg, #6366f1, #8b5cf6, #ec4899);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Animation */
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

    /* Floating animation */
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

    /* Progress bar */
    .progress-bar {
        background: rgba(30, 41, 59, 0.3);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        overflow: hidden;
        height: 8px;
    }

    .progress-fill {
        background: linear-gradient(90deg, #6366f1, #8b5cf6);
        height: 100%;
        border-radius: 20px;
        transition: width 0.3s ease;
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

    /* Filter inputs */
    .modern-input {
        background: rgba(30, 41, 59, 0.2);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(148, 163, 184, 0.3) !important;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        color: #f8fafc;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .modern-input:focus {
        background: rgba(30, 41, 59, 0.3);
        border-color: rgba(99, 102, 241, 0.7) !important;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        transform: translateY(-1px);
    }
</style>
@endpush

@section('content')
<!-- Modern Sponsors Dashboard -->
<div class="min-h-screen space-y-8 px-4 py-6">
    <!-- Sponsors Grid Header -->
  <div class="glass-card rounded-2xl p-8 animate-fade-in">
        <div class="flex flex-col lg:flex-row  items-start lg:items-center gap-3">
            <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-2xl">
                <i class="fas fa-handshake text-2xl text-white" aria-hidden="true"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold text-white bg-gradient-to-r from-emerald-400 to-teal-300 bg-clip-text text-transparent">
                    Sponsorships & Funding
                </h1>
                <p class="text-slate-300 mt-2 text-lg">
                    Manage your campaign sponsors and funding for {{ $doctorSpecialty ?? 'Medical' }} specialty
                </p>
            </div>
        </div>
        <!-- <div class="flex gap-3 justify-start col-span-full lg:col-span-1">
            <button type="button" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-xl font-medium transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105 border border-blue-500/20"
                onclick="alert('Request new sponsorship coming soon!')">
                <i class="fas fa-plus mr-2" aria-hidden="true"></i>Request Sponsorship
            </button>
            <button type="button" class="bg-gradient-to-r from-emerald-600 to-teal-700 hover:from-emerald-700 hover:to-teal-800 text-white px-6 py-3 rounded-xl font-medium transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105 border border-emerald-500/20"
                onclick="alert('Sponsor finder coming soon!')">
                <i class="fas fa-search mr-2" aria-hidden="true"></i>Find Sponsors
            </button>
        </div> -->
    </div>

    <!-- Enhanced Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Total Sponsors Card -->
        <section aria-labelledby="total-sponsors-title" class="glass-effect rounded-xl p-6 border-l-4 border-blue-500 backdrop-blur-sm hover:shadow-2xl hover:shadow-blue-500/20 hover:bg-white/10 transition-all duration-300">
            <h2 id="total-sponsors-title" class="sr-only">Total Sponsors</h2>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-sm font-medium uppercase mb-2">Total Sponsors</p>
                    <p class="text-3xl font-bold text-white mb-1">{{ $totalSponsors }}</p>
                    @if($doctorSpecialty && $specialtySponsors > 0)
                        <p class="text-slate-400 text-sm">{{ $specialtySponsors }} in {{ $doctorSpecialty }}</p>
                    @endif
                </div>
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-4 rounded-xl" aria-hidden="true">
                    <i class="fas fa-users text-2xl text-white"></i>
                </div>
            </div>
        </section>

        <!-- Total Sponsor Earnings Card -->
     <section aria-labelledby="total-earnings-title" class="glass-effect rounded-xl p-6 border-l-4 border-emerald-500 backdrop-blur-sm hover:shadow-2xl hover:shadow-emerald-500/20 hover:bg-white/10 transition-all duration-300">
    <h2 id="total-earnings-title" class="sr-only">Total Sponsor Earnings</h2>
    <div class="flex items-center justify-between">
        <div>
            <p class="text-slate-400 text-sm font-medium uppercase mb-2">Total Sponsor Earnings</p>
            <p class="text-3xl font-bold text-white mb-1">₹{{ number_format($doctorEarningsFromSponsors, 2) }}</p>
            <p class="text-slate-400 text-sm">{{ $sponsorFeePercentage }}% commission from ₹{{ number_format($successfulFunding, 2) }}</p>
        </div>
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 p-4 rounded-xl" aria-hidden="true">
            <i class="fas fa-coins text-2xl text-white"></i>
        </div>
    </div>
</section>


        <!-- Total Funding Received Card -->
        <section aria-labelledby="total-funding-title" class="glass-effect rounded-xl p-6 border-l-4 border-teal-500 backdrop-blur-sm hover:shadow-2xl hover:shadow-teal-500/20 hover:bg-white/10 transition-all duration-300">
            <h2 id="total-funding-title" class="sr-only">Total Funding Received</h2>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-sm font-medium uppercase mb-2">Total Funding Received</p>
                    <p class="text-3xl font-bold text-white mb-1">₹{{ number_format($successfulFunding, 2) }}</p>
                    <p class="text-slate-400 text-sm">From all sponsors</p>
                </div>
                <div class="bg-gradient-to-br from-teal-500 to-teal-600 p-4 rounded-xl" aria-hidden="true">
                    <i class="fas fa-chart-line text-2xl text-white"></i>
                </div>
            </div>
        </section>

        <!-- Pending Sponsor Payments Card -->
     
    </div>

    <!-- Main Content: Sponsors Table and Sidebar -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sponsors Table (Main Content) -->
        <section aria-labelledby="sponsors-table-title" class="lg:col-span-2">
            <div class="glass-effect rounded-xl p-6 border border-slate-600/30 backdrop-blur-sm">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center" aria-hidden="true">
                            <i class="fas fa-hand-holding-usd text-white"></i>
                        </div>
                        <h3 id="sponsors-table-title" class="text-xl font-bold text-white">Campaign Sponsors & Funding</h3>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" class="bg-blue-600/20 hover:bg-blue-600/40 text-blue-400 hover:text-blue-300 px-4 py-2 rounded-lg border border-blue-600/30 transition" onclick="filterSponsors('all')">All Sponsors</button>
                        @if($doctorSpecialty)
                        <button type="button" class="bg-emerald-600/20 hover:bg-emerald-600/40 text-emerald-400 hover:text-emerald-300 px-4 py-2 rounded-lg border border-emerald-600/30 transition" onclick="filterSponsors('specialty')">{{ $doctorSpecialty }} Only</button>
                        @endif
                    </div>
                </div>

                @if($sponsorships->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full border text-sm text-slate-300 bg-slate-900 rounded-lg overflow-hidden" role="table" aria-describedby="sponsors-table-desc">
                        <caption id="sponsors-table-desc" class="sr-only">List of sponsors with campaign details and payment status</caption>
                        <thead class="bg-gradient-to-r from-slate-800 to-slate-700 text-slate-200">
                            <tr>
                                <th scope="col" class="px-4 py-2 text-left">Sponsor Details</th>
                                <th scope="col" class="px-4 py-2 text-left">Campaign</th>
                                <th scope="col" class="px-4 py-2 text-left">Amount</th>
                                <th scope="col" class="px-4 py-2 text-left"> Specialty</th>
                                
                              
                                <th scope="col" class="px-4 py-2 text-left">Date</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sponsorships as $sponsorship)
                            <tr class="border-b border-slate-700">
                                <td class="px-4 py-2">
                                    <div class="flex items-center gap-2">
                                        <span class="flex items-center justify-center w-8 h-8 rounded-full bg-emerald-500 text-white font-bold" aria-hidden="true">
                                            {{ substr($sponsorship->sponsor_name ?? 'S', 0, 1) }}
                                        </span>
                                        <div>
                                            <div class="font-semibold text-white">{{ $sponsorship->name ?? 'Anonymous Sponsor' }}</div>
                                            <div class="text-xs text-slate-400">{{ $sponsorship->phone_number ?? 'No phonenumber provided' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-2">
                                    <div class="font-semibold text-white">{{ $sponsorship->campaign->title ?? 'No Title' }}</div>
                                    <div class="text-xs text-slate-400">{{ $sponsorship->campaign->location ?? 'No location' }}</div>
                                </td>
                                <td class="px-4 py-2">
                                    <span class="font-bold text-green-400">₹{{ number_format($sponsorship->amount, 2) }}</span>
                                </td>
                                <td class="px-4 py-2">
                                    @php
                                        $campaignSpecializations = $sponsorship->campaign->specializations ?? [];
                                        if (is_string($campaignSpecializations)) {
                                            $campaignSpecializations = json_decode($campaignSpecializations, true) ?? [];
                                        }
                                        $specialtyNames = [];
                                        if (is_array($campaignSpecializations)) {
                                            $specialtyNames = \App\Models\Specialty::whereIn('id', $campaignSpecializations)->pluck('name')->toArray();
                                        }
                                    @endphp

                                    @if(!empty($specialtyNames))
                                        @foreach($specialtyNames as $specialtyName)
                                            <span class="inline-block bg-blue-600 text-white px-2 py-1 rounded-full text-xs mr-1">{{ $specialtyName }}</span>
                                        @endforeach
                                    @else
                                        <span class="inline-block bg-slate-500 text-white px-2 py-1 rounded-full text-xs">General Medical</span>
                                    @endif
                                </td>
                              
                               
                                <td class="px-4 py-2">
                                    <small>{{ $sponsorship->created_at ? $sponsorship->created_at->format('M d, Y') : 'N/A' }}</small>
                                </td>
                               
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-hand-holding-usd fa-4x text-gray-300 mb-3" aria-hidden="true"></i>
                        <h4 class="text-gray-600">No Sponsors Yet</h4>
                        <p class="text-slate-400">Your campaigns haven't received any sponsorships yet. Keep promoting your medical campaigns to attract sponsors.</p>
                        <button type="button" class="btn btn-primary mt-3 bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800" onclick="alert('Campaign promotion tips coming soon!')">
                            <i class="fas fa-lightbulb mr-2" aria-hidden="true"></i>Get Promotion Tips
                        </button>
                    </div>
                @endif
            </div>
        </section>

        <!-- Sidebar with Charts & Quick Actions -->
        <aside class="col-span-1 space-y-6">


            <!-- Recent Sponsorships -->
            <section aria-labelledby="recent-sponsorships-title" class="glass-effect rounded-xl p-6 border border-slate-600/30 backdrop-blur-sm">
                <h3 id="recent-sponsorships-title" class="font-bold text-lg text-blue-400 mb-4">Recent Sponsorships</h3>
                @if($sponsorships->count() > 0)
                    @foreach($sponsorships->take(5) as $sponsorship)
                        <article class="flex items-center mb-3" role="listitem">
                            <div
                                class="flex items-center justify-center w-8 h-8 rounded-full
                                @if($sponsorship->payment_status == 'success') bg-green-600
                                @elseif($sponsorship->payment_status == 'pending') bg-yellow-500
                                @else bg-red-600
                                @endif text-white mr-3" aria-hidden="true">
                                @if($sponsorship->payment_status == 'success')
                                    <i class="fas fa-check"></i>
                                @elseif($sponsorship->payment_status == 'pending')
                                    <i class="fas fa-clock"></i>
                                @else
                                    <i class="fas fa-times"></i>
                                @endif
                            </div>
                            <div class="flex-grow">
                                <div class="font-bold text-white text-sm">{{ $sponsorship->sponsor_name ?? 'Anonymous Sponsor' }}</div>
                                <div class="text-xs text-slate-400">
                                    ₹{{ number_format($sponsorship->amount, 2) }} &bull; {{ $sponsorship->created_at ? $sponsorship->created_at->diffForHumans() : 'Recently' }}
                                </div>
                            </div>
                            <div>
                                <span class="inline-block px-2 py-1 rounded-full text-xs font-medium
                                     bg-green-600 text-white
                                  "
                                >
                                    Paid
                                </span>
                            </div>
                        </article>
                    @endforeach
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-handshake fa-2x text-gray-300 mb-2" aria-hidden="true"></i>
                        <p class="text-slate-400 text-sm">No sponsorships yet</p>
                    </div>
                @endif
            </section>

            <!-- Quick Actions -->
            <section aria-labelledby="quick-actions-title" class="glass-effect rounded-xl p-6 border border-slate-600/30 backdrop-blur-sm">
                <h3 id="quick-actions-title" class="font-bold text-lg text-blue-400 mb-4">Quick Actions</h3>
                <div class="flex flex-col gap-2">
                    <button type="button" class="btn btn-primary btn-sm bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800" onclick="alert('Create sponsorship request coming soon!')">
                        <i class="fas fa-plus mr-2" aria-hidden="true"></i>Request New Sponsorship
                    </button>
                    <button type="button" class="btn btn-success btn-sm bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800" onclick="alert('Sponsor outreach tools coming soon!')">
                        <i class="fas fa-envelope mr-2" aria-hidden="true"></i>Contact Sponsors
                    </button>
                    <button type="button" class="btn btn-info btn-sm bg-cyan-700 text-white px-4 py-2 rounded hover:bg-cyan-800" onclick="alert('Sponsorship analytics coming soon!')">
                        <i class="fas fa-chart-bar mr-2" aria-hidden="true"></i>View Analytics
                    </button>
                    <button type="button" class="btn btn-warning btn-sm bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600" onclick="alert('Export functionality coming soon!')">
                        <i class="fas fa-download mr-2" aria-hidden="true"></i>Export Report
                    </button>
                </div>
            </section>
        </aside>
    </div>
</div>

<style>
    /* Glassmorphism base effect */
    .glass-effect {
        background: linear-gradient(135deg, #10162eaa 30%, #18575a60 100%);
        box-shadow: 0 10px 32px 0 #0003, 0 1.5px 6px 0 #3332;
        border-radius: 1rem;
        border: 1px solid #2dd4bf33;
        backdrop-filter: blur(6px);
    }
</style>

<style>
    .glass-effect {
        background: linear-gradient(135deg, #10162eaa 30%, #18575a60 100%);
        box-shadow: 0 10px 32px 0 #0003, 0 1.5px 6px 0 #3332;
        border-radius: 1rem;
        border: 1px solid #2dd4bf33;
        backdrop-filter: blur(6px);
    }
</style>

<!-- Sponsorship Details Modal -->
<div class="modal fade" id="sponsorshipModal" tabindex="-1" role="dialog" aria-labelledby="sponsorshipModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sponsorshipModalLabel">Sponsorship Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="sponsorshipDetails">
                <!-- Sponsorship details will be populated here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="alert('Contact sponsor feature coming soon!')">
                    <i class="fas fa-envelope mr-2"></i>Contact Sponsor
                </button>
            </div>
        </div>
    </div>
</div>

@push('css')
<style>
    .table th {
        border-top: none;
        font-weight: 600;
        background-color: #f8f9fc;
    }

    .table-responsive {
        border-radius: 0.35rem;
    }

    .badge {
        font-size: 0.75em;
    }

    .btn-group .btn {
        margin-right: 2px;
    }

    .btn-group .btn:last-child {
        margin-right: 0;
    }

    .card {
        transition: all 0.3s ease;
    }

    tr:hover {
        background-color: #f8f9fc;
    }

    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }

    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }

    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }

    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }

    .border-left-danger {
        border-left: 0.25rem solid #e74a3b !important;
    }

    .border-left-secondary {
        border-left: 0.25rem solid #6c757d !important;
    }

    .avatar {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 14px;
    }

    .avatar-sm {
        width: 24px;
        height: 24px;
        font-size: 12px;
    }

    .chart-area {
        position: relative;
        height: 200px;
    }
</style>
@endpush

@push('js')
<script>
    $(document).ready(function() {
        // Initialize DataTables
        $('#sponsorsTable').DataTable({
            "pageLength": 10,
            "ordering": true,
            "searching": true,
            "responsive": true
        });

        // Initialize Sponsorship Status Chart
        initializeSponsorshipStatusChart();
    });

    // Sponsorship Status Chart
    function initializeSponsorshipStatusChart() {
        const ctx = document.getElementById('sponsorshipStatusChart');
        if (!ctx) return;

        const paidCount = {
            {
                $paidSponsorships ?? 0
            }
        };
        const pendingCount = {
            {
                $pendingSponsorships ?? 0
            }
        };
        const failedCount = {
            {
                $failedSponsorships ?? 0
            }
        };

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Paid', 'Pending', 'Failed'],
                datasets: [{
                    data: [paidCount, pendingCount, failedCount],
                    backgroundColor: ['#1cc88a', '#f6c23e', '#e74a3b'],
                    hoverBackgroundColor: ['#17a673', '#dda20a', '#c0392b'],
                    borderWidth: 0,
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: false
                },
                cutoutPercentage: 80,
            },
        });
    }

    // Filter sponsors by specialty
    function filterSponsors(type) {
        if (type === 'all') {
            $('.sponsor-row').show();
        } else if (type === 'specialty') {
            $('.sponsor-row').each(function() {
                const specialties = $(this).data('specialty');
                if (specialties && specialties.includes('{{ $doctorSpecialty }}')) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }
    }

    // Sponsor Functions  
    function viewSponsorshipDetails(sponsorshipId) {
        const sponsorship = @json($sponsorships).find(s => s.id === sponsorshipId);
        if (sponsorship) {
            let details = `
            <div class="row">
                <div class="col-md-6">
                    <h6><i class="fas fa-user mr-2"></i>Sponsor Information</h6>
                    <div class="card border-0 bg-light mb-3">
                        <div class="card-body">
                            <p><strong>Name:</strong> ${sponsorship.sponsor_name || 'Anonymous Sponsor'}</p>
                            <p><strong>Email:</strong> ${sponsorship.sponsor_email || 'Not provided'}</p>
                            <p><strong>Amount:</strong> <span class="text-success font-weight-bold">₹${sponsorship.amount.toLocaleString()}</span></p>
                            <p><strong>Status:</strong> <span class="badge badge-${sponsorship.payment_status === 'success' ? 'success' : sponsorship.payment_status === 'pending' ? 'warning' : 'danger'}">${sponsorship.payment_status}</span></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <h6><i class="fas fa-calendar-plus mr-2"></i>Campaign Information</h6>
                    <div class="card border-0 bg-light mb-3">
                        <div class="card-body">
                            <p><strong>Title:</strong> ${sponsorship.campaign.title}</p>
                            <p><strong>Location:</strong> ${sponsorship.campaign.location}</p>
                            <p><strong>Date:</strong> ${new Date(sponsorship.created_at).toLocaleDateString()}</p>
                            <p><strong>Your Commission:</strong> <span class="text-primary font-weight-bold">₹${(sponsorship.amount * 0.1).toLocaleString()}</span></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <h6><i class="fas fa-chart-pie mr-2"></i>Sponsorship Summary</h6>
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="text-muted small">Sponsorship Amount</div>
                                    <div class="h5 text-success">₹${sponsorship.amount.toLocaleString()}</div>
                                </div>
                                <div class="col-4">
                                    <div class="text-muted small">Your Commission (10%)</div>
                                    <div class="h5 text-primary">₹${(sponsorship.amount * 0.1).toLocaleString()}</div>
                                </div>
                                <div class="col-4">
                                    <div class="text-muted small">Platform Fee</div>
                                    <div class="h5 text-info">₹${(sponsorship.amount * 0.05).toLocaleString()}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

            $('#sponsorshipDetails').html(details);
            $('#sponsorshipModalLabel').text('Sponsorship Details');
            $('#sponsorshipModal').modal('show');
        }
    }

    function followUpPayment(sponsorshipId) {
        if (confirm('Send a payment reminder to the sponsor?')) {
            // Here you would typically make an AJAX call to send the reminder
            alert('Payment reminder sent successfully! The sponsor will receive an email notification.');
        }
    }
</script>
@endpush

@endsection