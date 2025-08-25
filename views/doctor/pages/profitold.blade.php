@extends('doctor.master')

@section('title', 'Profit & Earnings - FreeDoctor')

@section('content')
<div class=" space-y-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="glass-effect p-6 rounded-xl">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white flex items-center gap-3 mb-2">
                        <div class="bg-gradient-to-br from-emerald-400 to-teal-600 p-3 rounded-xl">
                            <i class="fas fa-chart-line text-xl"></i>
                        </div>
                        Profit & Earnings
                    </h1>
                    <p class="text-gray-300">Track your commission earnings from patient registrations and sponsors</p>
                </div>
                <!-- <div class="mt-4 md:mt-0">
                    <button class="bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105" onclick="alert('Export earnings coming soon!')">
                        <i class="fas fa-download mr-2"></i>Export Earnings
                    </button>
                </div> -->
            </div>
        </div>
    </div>

    <!-- Earnings Summary Cards -->
    <div class="grid  grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Total Earnings -->
<!-- Total Earnings (Net of both) -->
@php
    $totalNetEarnings = $earnings->sum('net_earning');

    $totalRegistrationEarnings = $earnings->filter(fn($e) => $e->earning_type === 'registration')
        ->sum('net_earning');

    $totalSponsorEarnings = $earnings->filter(fn($e) => $e->earning_type === 'sponsor')
        ->sum('net_earning');
@endphp

<!-- Display Boxes -->

    <!-- Total Net Earnings -->
    <div class="glass-effect p-6  rounded-xl border-l-4 border-emerald-500 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm font-medium uppercase tracking-wider mb-2">Total Earnings</p>
                <p class="text-3xl font-bold text-white mb-1">₹{{ number_format($totalNetEarnings, 2) }}</p>
                <p class="text-gray-400 text-sm">Net after commission</p>
            </div>
            <div class="bg-gradient-to-br from-emerald-400 to-emerald-600 p-4 rounded-xl">
                <i class="fas fa-chart-line text-2xl text-white"></i>
            </div>
        </div>
    </div>

    <!-- Patient Registration Earnings -->
    <div class="glass-effect p-6 rounded-xl border-l-4 border-green-500 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm font-medium uppercase tracking-wider mb-2">Patient Registration Earnings</p>
                <p class="text-3xl font-bold text-white mb-1">₹{{ number_format($totalRegistrationEarnings, 2) }}</p>
                <p class="text-gray-400 text-sm">₹300 per registration (net)</p>
            </div>
            <div class="bg-gradient-to-br from-green-400 to-green-600 p-4 rounded-xl">
                <i class="fas fa-user-plus text-2xl text-white"></i>
            </div>
        </div>
    </div>

    <!-- Sponsor Earnings -->
    <div class="glass-effect p-6 rounded-xl border-l-4 border-blue-500 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm font-medium uppercase tracking-wider mb-2">Sponsor Earnings</p>
                <p class="text-3xl font-bold text-white mb-1">₹{{ number_format($totalSponsorEarnings, 2) }}</p>
                <p class="text-gray-400 text-sm">After 10% commission</p>
            </div>
            <div class="bg-gradient-to-br from-blue-400 to-blue-600 p-4 rounded-xl">
                <i class="fas fa-hand-holding-usd text-2xl text-white"></i>
            </div>
        </div>
    </div>
</div>


    </div>

    <!-- Recent Earnings Table -->
    <div class="glass-effect rounded-xl  mt-8 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-700/50">
            <h3 class="text-xl font-bold text-white flex items-center gap-3">
                <div class="bg-gradient-to-br from-purple-400 to-purple-600 p-2 rounded-lg">
                    <i class="fas fa-table text-sm"></i>
                </div>
                Recent Earnings
            </h3>
        </div>
        <div class="p-6">
            @if($earnings->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full" id="earningsTable">
    <thead>
        <tr class="border-b border-gray-700/50">
            <th class="text-left py-3 px-4 text-gray-300 font-medium">Date</th>
            <th class="text-left py-3 px-4 text-gray-300 font-medium">Type</th>
            <th class="text-left py-3 px-4 text-gray-300 font-medium">Campaign</th>
            <th class="text-left py-3 px-4 text-gray-300 font-medium">Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach($earnings as $earning)
        <tr class="border-b border-gray-700/30 hover:bg-white/5 transition-colors duration-200">
            <td class="py-4 px-4 text-white font-medium">
                {{ \Carbon\Carbon::parse($earning->created_at)->format('M j, Y') }}
            </td>
            <td class="py-4 px-4">
                @php
                    $typeClass = match($earning->earning_type) {
                        'registration' => 'bg-green-500/20 text-green-400 border border-green-500/30',
                        'sponsor' => 'bg-blue-500/20 text-blue-400 border border-blue-500/30',
                        default => 'bg-gray-500/20 text-gray-400 border border-gray-500/30'
                    };
                    $typeIcon = match($earning->earning_type) {
                        'registration' => 'fas fa-user-plus',
                        'sponsor' => 'fas fa-hand-holding-usd', 
                        default => 'fas fa-circle'
                    };
                @endphp
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-lg text-sm font-medium {{ $typeClass }}">
                    <i class="{{ $typeIcon }}"></i>
                    {{ ucfirst(str_replace('_', ' ', $earning->earning_type)) }}
                </span>
            </td>
            <td class="py-4 px-4 text-white font-medium">
                {{ $earning->title ?? 'No title' }}
            </td>
          <td class="py-4 px-4">
    <span class="text-emerald-400 font-bold">
        ₹{{ number_format($earning->net_earning, 2) }}
    </span>
    <div class="text-xs text-gray-400 mt-1">
        <span>Admin {{ $earning->percentage_rate }}%: ₹{{ number_format($earning->commission_amount, 2) }}</span>
    </div>
</td>
        </tr>
        @endforeach
    </tbody>
</table>

                </div>
            @else
                <div class="text-center py-16">
                    <div class="bg-gradient-to-br from-gray-600 to-gray-700 p-6 rounded-full w-24 h-24 mx-auto mb-6 flex items-center justify-center">
                        <i class="fas fa-chart-line text-4xl text-gray-300"></i>
                    </div>
                    <h4 class="text-xl font-bold text-white mb-2">No Earnings Yet</h4>
                    <p class="text-gray-400 max-w-md mx-auto">Start promoting your campaigns to generate earnings from patient registrations and sponsors.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('css')
<style>
    /* DataTables dark theme customization */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        color: #9ca3af;
    }
    
    .dataTables_wrapper .dataTables_filter input {
        background-color: rgba(55, 65, 81, 0.5);
        border: 1px solid rgba(139, 92, 246, 0.3);
        color: white;
        border-radius: 0.5rem;
        padding: 0.5rem 1rem;
    }
    
    .dataTables_wrapper .dataTables_length select {
        background-color: rgba(55, 65, 81, 0.5);
        border: 1px solid rgba(139, 92, 246, 0.3);
        color: white;
        border-radius: 0.5rem;
        padding: 0.25rem 0.5rem;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        color: #9ca3af !important;
        background: transparent !important;
        border: none !important;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        color: white !important;
        background: rgba(139, 92, 246, 0.3) !important;
        border-radius: 0.5rem !important;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        color: white !important;
        background: linear-gradient(to right, #8b5cf6, #a855f7) !important;
        border-radius: 0.5rem !important;
    }
    
    /* Custom scrollbar for the table */
    .overflow-x-auto::-webkit-scrollbar {
        height: 8px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-track {
        background: rgba(55, 65, 81, 0.3);
        border-radius: 4px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: linear-gradient(to right, #8b5cf6, #a855f7);
        border-radius: 4px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to right, #7c3aed, #9333ea);
    }
</style>
@endpush

@push('js')
<script>
$(document).ready(function() {
    // Initialize DataTables with dark theme
  $('#earningsTable').DataTable({
    "pageLength": 10,
    "ordering": true,
    "searching": true,
    "responsive": true,
    "language": {
        "search": "Search earnings:",
        "lengthMenu": "Show _MENU_ entries",
        "info": "Showing _START_ to _END_ of _TOTAL_ earnings",
        "paginate": {
            "first": "First",
            "last": "Last",
            "next": "Next",
            "previous": "Previous"
        },
        "emptyTable": "No earnings data available"
    },
    "dom": '<"flex flex-col md:flex-row md:items-center md:justify-between mb-4"<"mb-2 md:mb-0"l><"mb-2 md:mb-0"f>>rtip',
    "columnDefs": [
        {
            "targets": [3], // Only the "Amount" column
            "className": "text-center"
        }
    ]
});

    // Add loading animation for export button
    document.querySelector('[onclick*="Export earnings"]').addEventListener('click', function() {
        const btn = this;
        const originalText = btn.innerHTML;
        
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Preparing Export...';
        btn.disabled = true;
        
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
            
            // Show success message
            const toast = document.createElement('div');
            toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg z-50 transform translate-x-full transition-transform duration-300';
            toast.innerHTML = '<i class="fas fa-check mr-2"></i>Export feature coming soon!';
            document.body.appendChild(toast);
            
            setTimeout(() => toast.classList.remove('translate-x-full'), 100);
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => document.body.removeChild(toast), 300);
            }, 3000);
        }, 2000);
    });
});
</script>
@endpush

@endsection
