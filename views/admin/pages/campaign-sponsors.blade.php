@extends('../admin.dashboard')

@section('content')
<!-- Include DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<div style="padding:15px" class="p-6 rounded shadow">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-white-800">Campaign Sponsors</h1>
            <p class="text-white-600 mt-2">Manage campaign sponsorships and track donations</p>
        </div>
        <div class="flex space-x-3">
            <button id="exportBtn" class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white px-6 py-3 rounded-lg hover:from-emerald-700 hover:to-emerald-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                <i class="fas fa-download mr-2"></i>Export Excel
            </button>
        </div>
    </div>

    <!-- Advanced Filters -->
    <div class=" rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold text-white-800 mb-4">
            <i class="fas fa-filter mr-2"></i>Advanced Filters
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-white-700 mb-2">Filter by Campaign</label>
                <select style="color:#111"id="campaignFilter"  class="w-full text-grey-700 px-4 py-2  border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option style="color:#111"class="text-grey-700" value="">All Campaigns</option>
                    @if(isset($campaigns) && $campaigns)
                        @foreach($campaigns as $campaign)
                            <option value="{{ $campaign->id }}">{{ $campaign->title }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-grey-700 mb-2">Filter by Location</label>
                <select style="color:#111"id="locationFilter" class="w-full text-black-700  px-4 py-2  border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option style="color:#111"class="text-grey-700" value="">All Locations</option>
                    @if(isset($locations) && $locations)
                        @foreach($locations as $location)
                            <option value="{{ $location }}">{{ $location }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-grey-700 mb-2">Min Amount</label>
                <input type="number" id="minAmount" placeholder="Min amount" 
                    style="color:#111"class="w-full px-4 py-2 text-grey-700 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-grey-700 mb-2">Max Amount</label>
                <input style="color:#111" type="number" id="maxAmount" placeholder="Max amount"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
        
            <div class="flex items-end"style="color:#111">
                <button id="applyFilters" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-search mr-2"></i>Apply Filters
                </button>
            </div>
        </div>
        
        <!-- Results Count -->
        <div class="mt-4 p-3  rounded-lg">
            <div class="flex justify-between items-center">
                <span class="text-sm text-white-600">
                    <i class="fas fa-info-circle mr-2"></i>
                    Showing <span id="recordCount" class="font-semibold">{{ isset($sponsors) ? count($sponsors) : 0 }}</span> sponsors
                    | Total Amount: ₹<span id="totalAmount" class="font-semibold text-green-600">{{ isset($sponsors) ? number_format($sponsors->sum('amount'), 2) : '0.00' }}</span>
                </span>
                <button id="clearFilters" class="text-sm text-blue-600 hover:text-blue-800 transition">
                    <i class="fas fa-times mr-1"></i>Clear All Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Total Sponsors</p>
                    <p class="text-3xl font-bold">{{ isset($sponsors) ? $sponsors->count() : 0 }}</p>
                </div>
                <div class="bg-blue-400 rounded-full p-3">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Total Amount</p>
                    <p class="text-3xl font-bold">₹{{ isset($sponsors) ? number_format($sponsors->sum('amount'), 0) : '0' }}</p>
                </div>
                <div class="bg-green-400 rounded-full p-3">
                    <i class="fas fa-rupee-sign text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-lg p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                                       @php
$commissionPercent = DB::table('admin_settings')
        ->where('setting_key', 'sponsor_fee_percentage')
        ->value('percentage_value') ?? 0;

    
    // Total sponsor amount
    $totalSponsorAmount = $sponsors->sum('amount');

    // Admin's profit from sponsor payments
    $sponsorProfit = ($totalSponsorAmount * $commissionPercent) / 100;
    
@endphp


    <p class="text-yellow-100 text-sm">Total Profit</p>
    <p class="text-3xl font-bold">₹{{ number_format($sponsorProfit, 2) }}</p>                </div>
                <div class="bg-yellow-400 rounded-full p-3">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
            </div>
        </div>
        

    </div>

    <!-- Sponsors Table -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table id="sponsorsTable" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-blue-50 to-indigo-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-user mr-2"></i>Sponsor Details
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-phone mr-2"></i>Contact
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-calendar-alt mr-2"></i>Campaign
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-rupee-sign mr-2"></i>Amount
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-credit-card mr-2"></i>Payment Status
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-clock mr-2"></i>Date
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-cogs mr-2"></i>Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="sponsorsTableBody">
                    @if(isset($sponsors) && $sponsors)
                        @foreach($sponsors as $sponsor)
                        <tr class="hover:bg-gray-50 transition-colors duration-200 sponsor-row" 
                            data-location="{{ $sponsor->campaign->location ?? '' }}"
                            data-campaign="{{ $sponsor->campaign_id }}"
                            data-status="{{ $sponsor->payment_status }}"
                            data-amount="{{ $sponsor->amount }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12">
                                        <div class="h-12 w-12 rounded-full bg-gradient-to-r from-purple-400 to-purple-600 flex items-center justify-center">
                                            <span class="text-white font-semibold text-lg">
                                                {{ strtoupper(substr($sponsor->name, 0, 1)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $sponsor->name }}</div>
                                        @if($sponsor->message)
                                            <div class="text-sm text-gray-500 truncate max-w-xs" title="{{ $sponsor->message }}">
                                                <i class="fas fa-comment text-blue-500 mr-1"></i>{{ Str::limit($sponsor->message, 30) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <i class="fas fa-phone text-blue-500 mr-2"></i>{{ $sponsor->phone_number }}
                                </div>
                                <div class="text-sm text-gray-500 truncate max-w-xs" title="{{ $sponsor->address }}">
                                    <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>{{ Str::limit($sponsor->address, 30) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $sponsor->campaign->title ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-map-marker-alt mr-1"></i>{{ $sponsor->campaign->location ?? 'N/A' }}
                                </div>
                                @if($sponsor->campaign)
                                    <div class="text-xs text-blue-600">
                                        Dr. {{ $sponsor->campaign->doctor->doctor_name ?? 'N/A' }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-lg font-bold text-green-600">
                                    ₹{{ number_format($sponsor->amount, 2) }}
                                </div>
                                @if($sponsor->payment_date)
                                    <div class="text-xs text-gray-500">
                                        {{ $sponsor->payment_date->format('M j, Y') }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $sponsor->payment_status_color }}">
                                
                                        <i class="fas text-green-600 mr-1">success</i>
                                   
                                    
                                </span>
                                @if($sponsor->payment_id)
                                    <div class="text-xs text-gray-500 mt-1">
                                        ID: {{ Str::limit($sponsor->payment_id, 10) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar mr-2 text-gray-400"></i>
                                    {{ $sponsor->created_at->format('M j, Y') }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ $sponsor->created_at->format('g:i A') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button class="view-sponsor-btn text-blue-600 hover:text-blue-900 transition" 
                                            data-id="{{ $sponsor->id }}" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                            
                                    <button class="delete-sponsor-btn text-red-600 hover:text-red-900 transition" 
                                            data-id="{{ $sponsor->id }}" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Sponsor Detail Modal -->
<div id="sponsorDetailModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-[9995]">
    <div class="bg-white rounded-lg p-6 w-full max-w-3xl shadow-xl max-h-screen overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-hands-helping mr-2 text-purple-500"></i>Sponsor Details
            </h2>
            <button id="closeSponsorModal" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div id="sponsorDetailContent">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<!-- Include DataTables JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    let table = $('#sponsorsTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[5, 'desc']], // Sort by date desc
        columnDefs: [
            { orderable: false, targets: [6] } // Disable sorting for actions column
        ],
        language: {
            search: "Search sponsors:",
            lengthMenu: "Show _MENU_ sponsors per page",
            info: "Showing _START_ to _END_ of _TOTAL_ sponsors",
            infoEmpty: "No sponsors found",
            infoFiltered: "(filtered from _MAX_ total sponsors)"
        }
    });

    // Apply Filters
    $('#applyFilters').on('click', function() {
        const campaign = $('#campaignFilter').val();
        const location = $('#locationFilter').val();
        const minAmount = parseFloat($('#minAmount').val()) || 0;
        const maxAmount = parseFloat($('#maxAmount').val()) || Infinity;
        const status = $('#statusFilter').val();
        
        let visibleCount = 0;
        let totalAmount = 0;
        
        // Show/hide rows based on filters
        $('.sponsor-row').each(function() {
            const row = $(this);
            const rowLocation = row.data('location');
            const rowCampaign = row.data('campaign').toString();
            const rowStatus = row.data('status');
            const rowAmount = parseFloat(row.data('amount'));
            
            let show = true;
            
            if (location && rowLocation !== location) show = false;
            if (campaign && rowCampaign !== campaign) show = false;
            if (status && rowStatus !== status) show = false;
            if (rowAmount < minAmount || rowAmount > maxAmount) show = false;
            
            if (show) {
                row.show();
                visibleCount++;
                totalAmount += rowAmount;
            } else {
                row.hide();
            }
        });
        
        // Update counts
        $('#recordCount').text(visibleCount);
        $('#totalAmount').text(totalAmount.toLocaleString('en-IN', {minimumFractionDigits: 2}));
        
        // Redraw DataTable
        table.draw();
    });

    // Clear Filters
    $('#clearFilters').on('click', function() {
        $('#campaignFilter, #locationFilter, #statusFilter, #minAmount, #maxAmount').val('');
        $('.sponsor-row').show();
        
        // Recalculate totals
        let totalCount = $('.sponsor-row').length;
        let totalAmount = 0;
        $('.sponsor-row').each(function() {
            totalAmount += parseFloat($(this).data('amount'));
        });
        
        $('#recordCount').text(totalCount);
        $('#totalAmount').text(totalAmount.toLocaleString('en-IN', {minimumFractionDigits: 2}));
        table.draw();
    });

    // Export functionality
    $('#exportBtn').on('click', function() {
        const campaign = $('#campaignFilter').val();
        const location = $('#locationFilter').val();
        const minAmount = $('#minAmount').val();
        const maxAmount = $('#maxAmount').val();
        const status = $('#statusFilter').val();
        
        let url = '/admin/sponsors/export?';
        const params = [];
        
        if (campaign) params.push(`campaign_id=${campaign}`);
        if (location) params.push(`location=${encodeURIComponent(location)}`);
        if (minAmount) params.push(`min_amount=${minAmount}`);
        if (maxAmount) params.push(`max_amount=${maxAmount}`);
        if (status) params.push(`payment_status=${status}`);
        
        url += params.join('&');
        
        window.location.href = url;
    });

    // View Sponsor Details
    $(document).on('click', '.view-sponsor-btn', function() {
        const sponsorId = $(this).data('id');
        
        $.get(`/admin/sponsors/${sponsorId}`, function(sponsor) {
            const content = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="bg-purple-700 p-4 rounded-lg">
                            <h3 class="font-semibold text-black-800 mb-2">Sponsor Information</h3>
                            <div class="space-y-2">
                                <p><span class="font-medium">Name:</span> ${sponsor.name}</p>
                                <p><span class="font-medium">Phone:</span> ${sponsor.phone_number}</p>
                                <p><span class="font-medium">Address:</span> ${sponsor.address}</p>
                            </div>
                        </div>
                        
                        ${sponsor.message ? `
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-blue-800 mb-2">Message</h3>
                            <p class="text-gray-700">${sponsor.message}</p>
                        </div>
                        ` : ''}
                        
                        <div class="bg-green-700 p-4 rounded-lg">
                            <h3 class="font-semibold text-black-800 mb-2">Payment Information</h3>
                            <div class="space-y-2">
                                <p><span class="font-medium">Amount:</span> ₹${parseFloat(sponsor.amount).toLocaleString('en-IN', {minimumFractionDigits: 2})}</p>
                                <p><span class="font-medium">Status:</span> 
                                    <span class="px-2 py-1 rounded text-xs bg-green-200 text-green-800">
                                        Success
                                    </span>
                                </p>
                                ${sponsor.payment_id ? `<p><span class="font-medium">Payment ID:</span> ${sponsor.payment_id}</p>` : ''}
                               ${sponsor.payment_date ? `
  <p>
    <span class="font-medium">Payment Date:</span> 
    ${new Date(sponsor.payment_date).toLocaleDateString()} 
    <small class="text-muted">(${readableDuration})</small>
  </p>` : ''}
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="bg-indigo-700 p-4 rounded-lg">
                            <h3 class="font-semibold text-black-800 mb-2">Campaign Details</h3>
                            <div class="space-y-2">
                                <p><span class="font-medium">Campaign:</span> ${sponsor.campaign.title}</p>
                                <p><span class="font-medium">Location:</span> ${sponsor.campaign.location}</p>
                                <p><span class="font-medium">Doctor:</span> Dr. ${sponsor.campaign.doctor.doctor_name}</p>
                                <p><span class="font-medium">Duration:</span> ${sponsor.campaign.start_date} to ${sponsor.campaign.end_date}</p>
                            </div>
                        </div>
                        
                        <div class="bg-gray-700 p-4 rounded-lg">
                            <h3 class="font-semibold text-dark-800 mb-2">Registration Info</h3>
                            <div class="space-y-2">
                                <p><span class="font-medium">Registered:</span> ${new Date(sponsor.created_at).toLocaleDateString()}</p>
                                <p><span class="font-medium">Time:</span> ${new Date(sponsor.created_at).toLocaleTimeString()}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            $('#sponsorDetailContent').html(content);
            $('#sponsorDetailModal').removeClass('hidden');
        });
    });

    // Close Sponsor Modal
    $('#closeSponsorModal, #sponsorDetailModal').on('click', function(e) {
        if (e.target === this) {
            $('#sponsorDetailModal').addClass('hidden');
        }
    });

    // Update Sponsor Status
    $(document).on('change', '.status-update-select', function() {
        const sponsorId = $(this).data('id');
        const newStatus = $(this).val();
        const currentStatus = $(this).data('current');
        
        if (newStatus === currentStatus) return;
        
        $.ajax({
            url: `/admin/sponsors/${sponsorId}`,
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: { payment_status: newStatus },
            success: function(response) {
                if (response.success) {
                    location.reload(); // Reload to update status display
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            },
            error: function() {
                Swal.fire('Error!', 'Failed to update status.', 'error');
            }
        });
    });

    // Delete Sponsor
    $(document).on('click', '.delete-sponsor-btn', function() {
        const sponsorId = $(this).data('id');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "This sponsor record will be permanently deleted.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e3342f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/sponsors/${sponsorId}`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Failed to delete sponsor.', 'error');
                    }
                });
            }
        });
    });
});
</script>

@endsection
