@extends('../admin.dashboard')

@section('title', 'Leads Management - FreeDoctor Admin')

@section('content')

<div class="p-6 space-y-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
        <div>
            <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                <i class="fas fa-user-plus text-orange-500"></i>
                Organic Leads
            </h1>
            <p class="text-gray-300 mt-2">Manage user interest and notification requests</p>
        </div>
        <div class="flex space-x-3 mt-4 md:mt-0">
            <button onclick="exportLeads()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                <i class="fas fa-download"></i>
                Export CSV
            </button>
            <button onclick="refreshLeads()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                <i class="fas fa-sync"></i>
                Refresh
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Total Leads</p>
                    <p class="text-3xl font-bold">{{ $leads->total() }}</p>
                    <p class="text-orange-200 text-xs mt-1">All time</p>
                </div>
                <div class="bg-orange-400 bg-opacity-30 rounded-lg p-3">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Today's Leads</p>
                    <p class="text-3xl font-bold">{{ \App\Models\OrganicLead::whereDate('created_at', today())->count() }}</p>
                    <p class="text-green-200 text-xs mt-1">New today</p>
                </div>
                <div class="bg-green-400 bg-opacity-30 rounded-lg p-3">
                    <i class="fas fa-calendar-day text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">This Week</p>
                    <p class="text-3xl font-bold">{{ \App\Models\OrganicLead::where('created_at', '>=', now()->startOfWeek())->count() }}</p>
                    <p class="text-blue-200 text-xs mt-1">Past 7 days</p>
                </div>
                <div class="bg-blue-400 bg-opacity-30 rounded-lg p-3">
                    <i class="fas fa-calendar-week text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Popular Category</p>
                    <p class="text-lg font-bold">{{ \App\Models\OrganicLead::selectRaw('category, COUNT(*) as count')->groupBy('category')->orderBy('count', 'desc')->first()->category ?? 'N/A' }}</p>
                    <p class="text-purple-200 text-xs mt-1">Most requested</p>
                </div>
                <div class="bg-purple-400 bg-opacity-30 rounded-lg p-3">
                    <i class="fas fa-star text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-slate-800 border border-slate-700 rounded-xl p-6">
        <div class="flex flex-col lg:flex-row gap-4 items-center justify-between">
            <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
                <div class="relative">
                    <input type="text" id="searchLeads" placeholder="Search by name, mobile, or location..." 
                           class="bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white placeholder-gray-400 w-full sm:w-80 focus:outline-none focus:border-orange-500">
                    <i class="fas fa-search absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
                <select id="categoryFilter" class="bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-orange-500">
                    <option value="">All Categories</option>
                    <option value="Dental">Dental</option>
                    <option value="Eye">Eye Care</option>
                    <option value="General Health">General Health</option>
                    <option value="Cardiology">Cardiology</option>
                    <option value="Orthopedic">Orthopedic</option>
                    <option value="Dermatology">Dermatology</option>
                    <option value="Pediatric">Pediatric</option>
                    <option value="Women's Health">Women's Health</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button onclick="clearFilters()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                    Clear Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Leads Table -->
    <div class="bg-slate-800 border border-slate-700 rounded-xl overflow-hidden">
        <div class="p-6 border-b border-slate-700">
            <h3 class="text-xl font-semibold text-white flex items-center gap-2">
                <i class="fas fa-table text-orange-500"></i>
                Leads List
            </h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-white">
                <thead class="bg-slate-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-user"></i>
                                Lead Details
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-phone"></i>
                                Contact
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-map-marker-alt"></i>
                                Location
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-tag"></i>
                                Category
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-clock"></i>
                                Date
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-slate-800 divide-y divide-slate-700" id="leadsTableBody">
                    @forelse($leads as $lead)
                    <tr class="hover:bg-slate-700 transition-colors lead-row" data-lead-id="{{ $lead->id }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-orange-600 rounded-full flex items-center justify-center">
                                    <span class="text-white font-semibold text-sm">{{ substr($lead->name, 0, 2) }}</span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-white">{{ $lead->name }}</div>
                                    <div class="text-sm text-gray-400">Lead #{{ $lead->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-white">{{ $lead->mobile }}</div>
                            <div class="text-sm text-gray-400">
                                <a href="tel:{{ $lead->mobile }}" class="text-green-400 hover:text-green-300">
                                    <i class="fas fa-phone-alt mr-1"></i>Call
                                </a>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-white">{{ $lead->location }}</div>
                            <div class="text-sm text-gray-400">
                                <i class="fas fa-map-marker-alt mr-1"></i>{{ $lead->location }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($lead->category === 'Dental') bg-blue-100 text-blue-800
                                @elseif($lead->category === 'Eye') bg-purple-100 text-purple-800
                                @elseif($lead->category === 'General Health') bg-green-100 text-green-800
                                @elseif($lead->category === 'Cardiology') bg-red-100 text-red-800
                                @elseif($lead->category === 'Orthopedic') bg-yellow-100 text-yellow-800
                                @elseif($lead->category === 'Dermatology') bg-pink-100 text-pink-800
                                @elseif($lead->category === 'Pediatric') bg-indigo-100 text-indigo-800
                                @elseif($lead->category === 'Women\'s Health') bg-purple-100 text-purple-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $lead->category }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-white">{{ $lead->created_at->format('M d, Y') }}</div>
                            <div class="text-sm text-gray-400">{{ $lead->created_at->format('h:i A') }}</div>
                            <div class="text-xs text-gray-500">{{ $lead->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button onclick="viewLead({{ $lead->id }})" 
                                        class="text-blue-400 hover:text-blue-300 transition-colors" 
                                        title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="contactLead('{{ $lead->mobile }}')" 
                                        class="text-green-400 hover:text-green-300 transition-colors" 
                                        title="Contact">
                                    <i class="fas fa-phone"></i>
                                </button>
                                <button onclick="deleteLead({{ $lead->id }})" 
                                        class="text-red-400 hover:text-red-300 transition-colors" 
                                        title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center">
                            <div class="text-gray-400">
                                <i class="fas fa-users text-6xl mb-4 opacity-30"></i>
                                <p class="text-lg font-medium">No leads found</p>
                                <p class="text-sm">Leads will appear here when users submit notification requests</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($leads->hasPages())
        <div class="bg-slate-700 px-6 py-4">
            {{ $leads->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Lead Details Modal -->
<div id="leadModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[9989] flex items-center justify-center">
    <div class="bg-slate-800 rounded-xl p-6 w-full max-w-md mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-white">Lead Details</h3>
            <button onclick="closeLead
                Modal()" class="text-gray-400 hover:text-white">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div id="leadDetails" class="space-y-4">
            <!-- Lead details will be populated here -->
        </div>
        <div class="flex gap-3 mt-6">
            <button onclick="closeLeadModal()" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                Close
            </button>
            <button onclick="contactCurrentLead()" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-phone mr-2"></i>Contact
            </button>
        </div>
    </div>
</div>

<script>
// Lead management functions
let currentLead = null;

function viewLead(leadId) {
    // Get lead data from the table row
    const leadRow = document.querySelector(`[data-lead-id="${leadId}"]`);
    const cells = leadRow.querySelectorAll('td');
    
    const leadData = {
        id: leadId,
        name: cells[0].querySelector('.text-sm.font-medium').textContent,
        mobile: cells[1].querySelector('.text-sm.text-white').textContent,
        location: cells[2].querySelector('.text-sm.text-white').textContent,
        category: cells[3].querySelector('span').textContent.trim(),
        date: cells[4].querySelector('.text-sm.text-white').textContent,
        time: cells[4].querySelector('.text-sm.text-gray-400').textContent,
        relative: cells[4].querySelector('.text-xs.text-gray-500').textContent
    };
    
    currentLead = leadData;
    
    document.getElementById('leadDetails').innerHTML = `
        <div class="space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-orange-600 rounded-full flex items-center justify-center">
                    <span class="text-white font-bold">${leadData.name.substring(0, 2)}</span>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-white">${leadData.name}</h4>
                    <p class="text-gray-400">Lead #${leadData.id}</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 gap-4">
                <div class="bg-slate-700 rounded-lg p-3">
                    <label class="text-xs text-gray-400 uppercase tracking-wider">Mobile Number</label>
                    <p class="text-white font-medium">${leadData.mobile}</p>
                </div>
                
                <div class="bg-slate-700 rounded-lg p-3">
                    <label class="text-xs text-gray-400 uppercase tracking-wider">Location</label>
                    <p class="text-white font-medium">${leadData.location}</p>
                </div>
                
                <div class="bg-slate-700 rounded-lg p-3">
                    <label class="text-xs text-gray-400 uppercase tracking-wider">Interest Category</label>
                    <p class="text-white font-medium">${leadData.category}</p>
                </div>
                
                <div class="bg-slate-700 rounded-lg p-3">
                    <label class="text-xs text-gray-400 uppercase tracking-wider">Submitted</label>
                    <p class="text-white font-medium">${leadData.date} at ${leadData.time}</p>
                    <p class="text-gray-400 text-sm">${leadData.relative}</p>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('leadModal').classList.remove('hidden');
}

function closeLeadModal() {
    document.getElementById('leadModal').classList.add('hidden');
    currentLead = null;
}

function contactLead(mobile) {
    window.open(`tel:${mobile}`, '_self');
}

function contactCurrentLead() {
    if (currentLead) {
        contactLead(currentLead.mobile);
    }
}

function deleteLead(leadId) {
    Swal.fire({
        title: 'Delete Lead?',
        text: 'Are you sure you want to delete this lead? This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Here you would make an AJAX call to delete the lead
            // For now, just remove from the table
            document.querySelector(`[data-lead-id="${leadId}"]`).remove();
            
            Swal.fire({
                title: 'Deleted!',
                text: 'The lead has been deleted successfully.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });
}

// Search and filter functions
function setupFilters() {
    const searchInput = document.getElementById('searchLeads');
    const categoryFilter = document.getElementById('categoryFilter');
    
    function filterLeads() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedCategory = categoryFilter.value;
        const rows = document.querySelectorAll('.lead-row');
        
        rows.forEach(row => {
            const name = row.querySelector('.text-sm.font-medium').textContent.toLowerCase();
            const mobile = row.querySelector('.text-sm.text-white').textContent.toLowerCase();
            const location = row.querySelectorAll('.text-sm.text-white')[1].textContent.toLowerCase();
            const category = row.querySelector('span').textContent.trim();
            
            const matchesSearch = !searchTerm || 
                name.includes(searchTerm) || 
                mobile.includes(searchTerm) || 
                location.includes(searchTerm);
            
            const matchesCategory = !selectedCategory || category === selectedCategory;
            
            if (matchesSearch && matchesCategory) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    searchInput.addEventListener('input', filterLeads);
    categoryFilter.addEventListener('change', filterLeads);
}

function clearFilters() {
    document.getElementById('searchLeads').value = '';
    document.getElementById('categoryFilter').value = '';
    document.querySelectorAll('.lead-row').forEach(row => {
        row.style.display = '';
    });
}

function exportLeads() {
    // Create CSV content
    const leads = [];
    document.querySelectorAll('.lead-row').forEach(row => {
        if (row.style.display !== 'none') {
            const cells = row.querySelectorAll('td');
            leads.push({
                name: cells[0].querySelector('.text-sm.font-medium').textContent,
                mobile: cells[1].querySelector('.text-sm.text-white').textContent,
                location: cells[2].querySelector('.text-sm.text-white').textContent,
                category: cells[3].querySelector('span').textContent.trim(),
                date: cells[4].querySelector('.text-sm.text-white').textContent + ' ' + cells[4].querySelector('.text-sm.text-gray-400').textContent
            });
        }
    });
    
    const csvContent = "data:text/csv;charset=utf-8," + 
        "Name,Mobile,Location,Category,Date\n" +
        leads.map(lead => 
            `"${lead.name}","${lead.mobile}","${lead.location}","${lead.category}","${lead.date}"`
        ).join("\n");
    
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "organic_leads_" + new Date().toISOString().split('T')[0] + ".csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    Swal.fire({
        title: 'Export Complete!',
        text: 'Leads have been exported to CSV file.',
        icon: 'success',
        timer: 2000,
        showConfirmButton: false
    });
}

function refreshLeads() {
    location.reload();
}

// Initialize filters when page loads
document.addEventListener('DOMContentLoaded', function() {
    setupFilters();
});

// Close modal when clicking outside
document.getElementById('leadModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLeadModal();
    }
});
</script>

@endsection
