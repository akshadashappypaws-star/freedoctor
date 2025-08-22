@extends('../admin.dashboard')

@section('content')
<!-- Include DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

<div style="padding:15px" class="p-6 rounded shadow bg-slate-800 border border-slate-700">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-white">Profit & Earnings</h1>
            <p class="text-gray-300 mt-2">Track admin commission earnings from registrations and sponsors</p>
        </div>
        <div class="flex space-x-3">
            <button id="exportEarningsBtn" class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white px-6 py-3 rounded-lg hover:from-emerald-700 hover:to-emerald-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                <i class="fas fa-download mr-2"></i>Export Earnings
            </button>
        </div>
    </div>

    <!-- Earnings Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Total Earnings -->
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Total Earnings</p>
                    <p class="text-3xl font-bold">₹{{ number_format($totalEarnings, 2) }}</p>
                </div>
                <div class="bg-purple-500/30 rounded-full p-3">
                    <i class="fas fa-chart-line text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 text-sm text-purple-100">
                <i class="fas fa-arrow-up mr-1"></i>
                All-time commission earnings
            </div>
        </div>

        <!-- Registration Earnings -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Patient Registration</p>
                    <p class="text-3xl font-bold">₹{{ number_format($totalRegistrationEarnings, 2) }}</p>
                </div>
                <div class="bg-blue-500/30 rounded-full p-3">
                    <i class="fas fa-user-plus text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 text-sm text-blue-100">
                <i class="fas fa-percentage mr-1"></i>
                Patient registration fees
            </div>
        </div>

        <!-- Sponsor Earnings -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Sponsor Earnings</p>
                    <p class="text-3xl font-bold">₹{{ number_format($totalSponsorEarnings, 2) }}</p>
                </div>
                <div class="bg-green-500/30 rounded-full p-3">
                    <i class="fas fa-hand-holding-usd text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 text-sm text-green-100">
                <i class="fas fa-percentage mr-1"></i>
                Sponsor payments
            </div>
        </div>

        <!-- Doctor Registration Earnings -->
        <div class="bg-gradient-to-r from-orange-600 to-orange-700 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Doctor Registration</p>
                    <p class="text-3xl font-bold">₹{{ number_format($totalDoctorRegistrationEarnings, 2) }}</p>
                </div>
                <div class="bg-orange-500/30 rounded-full p-3">
                    <i class="fas fa-user-md text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 text-sm text-orange-100">
                <i class="fas fa-percentage mr-1"></i>
                Doctor registration fees
            </div>
        </div>
    </div>

    <!-- Earnings Table -->
    <div class="bg-slate-700 border border-slate-600 rounded-lg shadow-lg overflow-hidden">
        <div class="p-4 border-b border-slate-600">
            <h3 class="text-lg font-semibold text-white">
                <i class="fas fa-table mr-2"></i>Detailed Earnings History
            </h3>
        </div>
        
        <div class="overflow-x-auto">
            <table id="earningsTable" class="min-w-full divide-y divide-slate-600">
                <thead class="bg-slate-600">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            <i class="fas fa-calendar mr-2"></i>Date
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            <i class="fas fa-tag mr-2"></i>Type
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            <i class="fas fa-rupee-sign mr-2"></i>Original Amount
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            <i class="fas fa-percentage mr-2"></i>Commission %
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            <i class="fas fa-coins mr-2"></i>Commission Earned
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            <i class="fas fa-info-circle mr-2"></i>Description
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-slate-700 divide-y divide-slate-600">
                    @forelse($earnings as $earning)
                        <tr class="hover:bg-slate-600 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-alt text-blue-400 mr-2"></i>
                                    {{ $earning->created_at->format('M j, Y') }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ $earning->created_at->format('g:i A') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $typeClass = match($earning->earning_type) {
                                        'registration' => 'bg-blue-100 text-blue-800',
                                        'sponsor' => 'bg-green-100 text-green-800',
                                        'doctor_registration' => 'bg-orange-100 text-orange-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                    $typeIcon = match($earning->earning_type) {
                                        'registration' => 'fas fa-user-plus',
                                        'sponsor' => 'fas fa-hand-holding-usd',
                                        'doctor_registration' => 'fas fa-user-md',
                                        default => 'fas fa-question'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $typeClass }}">
                                    <i class="{{ $typeIcon }} mr-1"></i>
                                    {{ ucfirst(str_replace('_', ' ', $earning->earning_type)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">
                                ₹{{ number_format($earning->original_amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                {{ $earning->percentage_rate }}%
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-400">
                                ₹{{ number_format($earning->commission_amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                <div class="max-w-xs truncate">
                                    {{ $earning->description ?? 'No description' }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ $earning->reference_type }} #{{ $earning->reference_id }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-chart-line text-gray-500 text-6xl mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-400 mb-2">No Earnings Yet</h3>
                                    <p class="text-gray-500">Earnings will appear here as patients register and sponsors pay for campaigns.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Profit Insights -->
    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-slate-700 border border-slate-600 rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold text-white mb-4">
                <i class="fas fa-chart-pie mr-2 text-purple-400"></i>Earnings Breakdown
            </h3>
            
            @if($totalEarnings > 0)
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-300">Patient Registration:</span>
                        <div class="text-right">
                            <span class="font-bold text-blue-400">₹{{ number_format($totalRegistrationEarnings, 2) }}</span>
                            <div class="text-xs text-gray-400">
                                {{ number_format(($totalRegistrationEarnings / $totalEarnings) * 100, 1) }}%
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-300">Sponsor Commission:</span>
                        <div class="text-right">
                            <span class="font-bold text-green-400">₹{{ number_format($totalSponsorEarnings, 2) }}</span>
                            <div class="text-xs text-gray-400">
                                {{ number_format(($totalSponsorEarnings / $totalEarnings) * 100, 1) }}%
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-300">Doctor Registration:</span>
                        <div class="text-right">
                            <span class="font-bold text-orange-400">₹{{ number_format($totalDoctorRegistrationEarnings, 2) }}</span>
                            <div class="text-xs text-gray-400">
                                {{ number_format(($totalDoctorRegistrationEarnings / $totalEarnings) * 100, 1) }}%
                            </div>
                        </div>
                    </div>
                    
                    <hr class="border-slate-600">
                    
                    <div class="flex justify-between items-center text-lg font-semibold">
                        <span class="text-white">Total Earnings:</span>
                        <span class="text-purple-400">₹{{ number_format($totalEarnings, 2) }}</span>
                    </div>
                </div>
            @else
                <p class="text-gray-400">No earnings data available yet.</p>
            @endif
        </div>

        <div class="bg-slate-700 border border-slate-600 rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold text-white mb-4">
                <i class="fas fa-info-circle mr-2 text-blue-400"></i>Profit Information
            </h3>
            
            <div class="space-y-4 text-sm text-gray-300">
                <div class="bg-slate-600 rounded-lg p-3">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-user-plus text-blue-400 mr-2"></i>
                        <span class="font-semibold">Registration Commission</span>
                    </div>
                    <p class="text-gray-400">Earned from patient registration fees based on admin settings percentage.</p>
                </div>
                
                <div class="bg-slate-600 rounded-lg p-3">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-hand-holding-usd text-green-400 mr-2"></i>
                        <span class="font-semibold">Sponsor Commission</span>
                    </div>
                    <p class="text-gray-400">Earned from sponsor payments for campaigns based on admin settings percentage.</p>
                </div>
                
                <div class="bg-blue-900/20 border border-blue-500/30 rounded-lg p-3">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-lightbulb text-yellow-400 mr-2"></i>
                        <span class="font-semibold text-blue-300">Tip</span>
                    </div>
                    <p class="text-blue-200 text-xs">Adjust commission percentages in Settings to optimize profit margins.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include DataTables JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#earningsTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[0, 'desc']], // Sort by date desc
        columnDefs: [
            { orderable: false, targets: [5] } // Disable sorting for description column
        ],
        language: {
            search: "Search earnings:",
            lengthMenu: "Show _MENU_ earnings per page",
            info: "Showing _START_ to _END_ of _TOTAL_ earnings",
            infoEmpty: "No earnings found",
            infoFiltered: "(filtered from _MAX_ total earnings)"
        }
    });

    // Export functionality
    $('#exportEarningsBtn').on('click', function() {
        window.location.href = '/admin/profit/export';
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
