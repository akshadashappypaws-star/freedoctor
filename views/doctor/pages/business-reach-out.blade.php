@extends('doctor.master')

@section('title', 'Business Opportunities - FreeDoctor')

@push('styles')
<style>
    .proposal-message {
        max-width: 300px;
        word-wrap: break-word;
    }
    
    .badge.status-rejected {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.75rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    #viewRequestBtn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        background: #64748b;
    }
    
    #viewRequestBtn:not(:disabled):hover {
        background: linear-gradient(135deg, #3b82f6, #1e40af);
        transform: translateY(-1px);
    }
    
    .alert {
        border-radius: 0.75rem;
        border: none;
        margin-bottom: 1.5rem;
    }
    
    .alert-success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }
    
    .alert-danger {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }
</style>
@endpush

@section('content')
<!-- Modern Business Opportunities Dashboard -->
<div class="min-h-screen space-y-8">
    <!-- Page Header -->
    <div class="page-header-card animate-fade-in">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-4">
            <div class="d-flex align-items-center gap-4">
                <div class="page-header-icon">
                    <i class="fas fa-briefcase text-white" style="font-size: 1.5rem;"></i>
                </div>
                <div>
                    <h1 class="page-title gradient-text">Business Opportunities</h1>
                    <p class="page-subtitle">Connect with organizations for {{ $doctorSpecialty ?? 'Medical' }} camps and business partnerships</p>
                    <p class="page-meta">Exclusive opportunities for healthcare professionals</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="stats-grid animate-fade-in">
        <!-- Available Requests -->
        <div class="stats-card-modern">
            <div class="stats-content">
                <div class="stats-text">
                    <p class="stats-label-modern">Available Requests</p>
                    <p class="stats-value-modern">{{ $totalRequests ?? 0 }}</p>
                    <p class="text-slate-300 small">For {{ $doctor->specialty->name ?? 'your specialty' }}</p>
                </div>
                <div class="stats-icon-modern">
                    <i class="fas fa-briefcase"></i>
                </div>
            </div>
        </div>
        
        <!-- My Proposals -->
        <div class="stats-card-modern">
            <div class="stats-content">
                <div class="stats-text">
                    <p class="stats-label-modern">My Proposals</p>
                    <p class="stats-value-modern">{{ $totalProposals ?? 0 }}</p>
                    <p class="text-slate-300 small">Submitted to admin</p>
                </div>
                <div class="stats-icon-modern">
                    <i class="fas fa-paper-plane"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Proposal Form Section - ENSURE VISIBILITY -->
    <div class="table-container animate-fade-in" id="proposalFormSection" style="display: block !important; visibility: visible !important;">
        <div class="d-flex justify-content-between align-items-center p-4 border-bottom border-theme">
            <div class="d-flex align-items-center gap-3">
                <div class="filter-icon">
                    <i class="fas fa-edit text-white"></i>
                </div>
                <h3 class="filter-title">Send Business Proposal to Admin</h3>
            </div>
            <div class="text-sm text-slate-400">
                <i class="fas fa-info-circle"></i> Submit proposals for business opportunities
            </div>
        </div>
        
        <div class="p-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <form action="{{ route('doctor.proposals.store') }}" method="POST" id="proposalForm">
                @csrf
                <div class="mb-4">
                    <label for="business_organization_request_id" class="block text-sm font-medium text-slate-300 mb-2">
                        Business Request (Optional)
                    </label>
                    <div class="d-flex gap-2">
                        <select 
                            name="business_organization_request_id" 
                            id="business_organization_request_id"
                            class="flex-1 px-4 py-3 bg-slate-700/50 border border-slate-600/50 rounded-xl text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all backdrop-blur-sm"
                        >
                            <option value="">Select a Business Request (Optional)</option>
                            @foreach($businessOrgRequests as $request)
                                <option value="{{ $request->id }}" data-request='@json($request)'>
                                    {{ $request->organization_name }} - {{ $request->camp_request_type }} 
                                    ({{ $request->location }})
                                </option>
                            @endforeach
                        </select>
                        <button type="button" id="viewRequestBtn" class="btn-modern" disabled onclick="viewSelectedRequest()">
                            <i class="fas fa-eye"></i> View Details
                        </button>
                    </div>
                    @error('business_organization_request_id')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="message" class="block text-sm font-medium text-slate-300 mb-2">
                        Your Business Proposal Message
                    </label>
                    <textarea 
                        name="message" 
                        id="message" 
                        rows="5" 
                        class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600/50 rounded-xl text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all backdrop-blur-sm" 
                        placeholder="Describe your business proposal, partnership ideas, or collaboration requests for the admin..."
                        required
                    >{{ old('message') }}</textarea>
                    @error('message')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="d-flex gap-3">
                    <button type="submit" class="btn-modern">
                        <i class="fas fa-paper-plane mr-2"></i>Send Proposal
                    </button>
                    <button type="reset" class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-3 rounded-xl transition-colors">
                        <i class="fas fa-undo mr-2"></i>Reset
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- My Proposals Table -->
    <div class="table-container animate-fade-in">
        <div class="d-flex justify-content-between align-items-center p-4 border-bottom border-theme">
            <div class="d-flex align-items-center gap-3">
                <div class="filter-icon">
                    <i class="fas fa-list text-white"></i>
                </div>
                <h3 class="filter-title">My Proposals</h3>
            </div>
        </div>
        
        <div class="table-responsive">
            @if(isset($doctorProposals) && count($doctorProposals) > 0)
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Business Request</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Submitted Date</th>
                            <th>Admin Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($doctorProposals as $proposal)
                        <tr>
                            <td class="text-slate-300">#{{ $proposal->id }}</td>
                            <td>
                                @if($proposal->businessOrganizationRequest)
                                    <div>
                                        <div class="fw-medium text-white">{{ $proposal->businessOrganizationRequest->organization_name }}</div>
                                        <small class="text-slate-400">{{ $proposal->businessOrganizationRequest->camp_request_type }} - {{ $proposal->businessOrganizationRequest->location }}</small>
                                    </div>
                                @else
                                    <span class="text-slate-500 fst-italic">General Proposal</span>
                                @endif
                            </td>
                            <td>
                                <div class="proposal-message">
                                    {{ Str::limit($proposal->message, 100) }}
                                    @if(strlen($proposal->message) > 100)
                                        <button class="text-blue-400 hover:text-blue-300 ml-2" onclick="showFullMessage('{{ addslashes($proposal->message) }}')">
                                            Read More
                                        </button>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($proposal->status === 'pending')
                                    <span class="badge status-applied">
                                        <i class="fas fa-clock"></i>
                                        Pending
                                    </span>
                                @elseif($proposal->status === 'approved')
                                    <span class="badge status-available">
                                        <i class="fas fa-check-circle"></i>
                                        Approved
                                    </span>
                                @else
                                    <span class="badge status-rejected">
                                        <i class="fas fa-times-circle"></i>
                                        Rejected
                                    </span>
                                @endif
                            </td>
                            <td class="text-slate-300">
                                <div class="date-range-display">
                                    <span class="date-badge">{{ $proposal->created_at->format('d M Y') }}</span>
                                    <small class="text-slate-400 d-block">{{ $proposal->created_at->format('H:i A') }}</small>
                                </div>
                            </td>
                            <td class="text-slate-300">
                                @if($proposal->admin_remarks)
                                    {{ Str::limit($proposal->admin_remarks, 50) }}
                                    @if(strlen($proposal->admin_remarks) > 50)
                                        <button class="text-blue-400 hover:text-blue-300 ml-2" onclick="showAdminRemarks('{{ addslashes($proposal->admin_remarks) }}')">
                                            Read More
                                        </button>
                                    @endif
                                @else
                                    <span class="text-slate-500">No remarks yet</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-paper-plane text-slate-300" style="font-size: 2rem;"></i>
                    </div>
                    <h4 class="empty-state-title">No Proposals Submitted</h4>
                    <p class="empty-state-description">You haven't submitted any business proposals yet. Use the form above to send your first proposal to the admin.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Business Requests Table -->
    <div class="table-container animate-fade-in">
        <div class="d-flex justify-content-between align-items-center p-4 border-bottom border-theme">
            <div class="d-flex align-items-center gap-3">
                <div class="filter-icon">
                    <i class="fas fa-building text-white"></i>
                </div>
                <h3 class="filter-title">Business Organization Requests</h3>
            </div>
            <button class="btn-modern btn-sm" onclick="refreshOpportunities()">
                <i class="fas fa-sync-alt me-2"></i>Refresh
            </button>
        </div>
        
        <div class="table-responsive">
            @if(isset($businessOrgRequests) && count($businessOrgRequests) > 0)
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Organization</th>
                            <th>Camp Type</th>
                            <th>Duration</th>
                            <th>People</th>
                            <th>Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($businessOrgRequests as $request)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="business-card-icon">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <div>
                                        <div class="fw-medium">{{ $request->organization_name ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge status-available">
                                    <i class="fas fa-stethoscope"></i>
                                    {{ $request->camp_request_type ?? 'N/A' }}
                                </span>
                            </td>
                            @php
                                $fromDate = $request->date_from ? \Carbon\Carbon::parse($request->date_from)->format('d M Y') : 'N/A';
                                $toDate = $request->date_to ? \Carbon\Carbon::parse($request->date_to)->format('d M Y') : 'N/A';
                            @endphp
                            <td>
                                <div class="date-range-display">
                                    <span class="date-badge">{{ $fromDate }}</span>
                                    <span class="date-separator">to</span>
                                    <span class="date-badge">{{ $toDate }}</span>
                                </div>
                            </td>
                            <td class="text-slate-300">{{ $request->number_of_people ?? 'N/A' }}</td>
                            <td class="text-slate-300">{{ $request->location ?? 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-briefcase text-slate-300" style="font-size: 2rem;"></i>
                    </div>
                    <h4 class="empty-state-title">No Business Requests Available</h4>
                    <p class="empty-state-description">No business organization requests found for {{ $doctor->specialty->name ?? 'your specialty' }}. Check back later for new opportunities.</p>
                    <button class="btn-modern" onclick="alert('Request notification feature coming soon!')">
                        <i class="fas fa-bell me-2"></i>Get Notified
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

    <!-- Comprehensive Earnings Breakdown -->
    <!-- <div class="glass-card rounded-xl p-6 animate-fade-in">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-chart-bar text-white"></i>
            </div>
            <h3 class="text-xl font-semibold text-white">Earnings Breakdown</h3>
            <span class="text-sm text-slate-400 ml-auto">All revenue streams</span>
        </div>
        
        <div class="modern-table">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="text-left">Revenue Source</th>
                        <th class="text-left">Count</th>
                        <th class="text-left">Amount</th>
                        <th class="text-left">Commission Rate</th>
                        <th class="text-left">Your Earnings</th>
                        <th class="text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                  
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-users text-white text-sm"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-white">Patient Registrations</div>
                                    <div class="text-sm text-slate-400">From your campaigns</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-slate-300">{{ $totalPatients ?? 0 }}</td>
                        <td class="text-slate-300">₹{{ number_format(($totalPatients ?? 0) * 500) }}</td>
                        <td class="text-slate-300">60%</td>
                        <td class="text-green-400 font-semibold">₹{{ number_format($patientEarnings ?? 0) }}</td>
                        <td>
                            <span class="status-badge status-available">
                                <i class="fas fa-check-circle"></i>
                                Active
                            </span>
                        </td>
                    </tr>
                    
       
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-hand-holding-heart text-white text-sm"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-white">Successful Sponsors</div>
                                    <div class="text-sm text-slate-400">Campaign sponsorships</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-slate-300">{{ $successfulSponsors ?? 0 }}</td>
                        <td class="text-slate-300">₹{{ number_format($totalSponsorAmount ?? 0) }}</td>
                        <td class="text-slate-300">10%</td>
                        <td class="text-green-400 font-semibold">₹{{ number_format($sponsorEarnings ?? 0) }}</td>
                        <td>
                            <span class="status-badge status-available">
                                <i class="fas fa-check-circle"></i>
                                Active
                            </span>
                        </td>
                    </tr>
                    
                   
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-briefcase text-white text-sm"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-white">Business Partnerships</div>
                                    <div class="text-sm text-slate-400">Approved collaborations</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-slate-300">{{ $approvedRequests ?? 0 }}</td>
                        <td class="text-slate-300">₹{{ number_format(($approvedRequests ?? 0) * 5000) }}</td>
                        <td class="text-slate-300">100%</td>
                        <td class="text-green-400 font-semibold">₹{{ number_format($potentialEarnings ?? 0) }}</td>
                        <td>
                            <span class="status-badge status-available">
                                <i class="fas fa-check-circle"></i>
                                Active
                            </span>
                        </td>
                    </tr>
                    
                   
                    <tr class="border-t-2 border-slate-600/50 bg-slate-700/20">
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-calculator text-white text-sm"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-white text-lg">Total Earnings</div>
                                    <div class="text-sm text-slate-400">All revenue streams</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-slate-300 font-semibold">{{ ($totalPatients ?? 0) + ($successfulSponsors ?? 0) + ($approvedRequests ?? 0) }}</td>
                        <td class="text-slate-300 font-semibold">₹{{ number_format((($totalPatients ?? 0) * 500) + ($totalSponsorAmount ?? 0) + (($approvedRequests ?? 0) * 5000)) }}</td>
                        <td class="text-slate-300 font-semibold">Variable</td>
                        <td class="text-green-400 font-bold text-xl">₹{{ number_format($totalEarnings ?? 0) }}</td>
                        <td>
                            <span class="status-badge status-available">
                                <i class="fas fa-chart-line"></i>
                                Growing
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div> -->

    <!-- Recent Applications -->
    <!-- <div class="glass-card rounded-xl p-6 animate-fade-in">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-history text-white"></i>
            </div>
            <h3 class="text-xl font-semibold text-white">Recent Applications</h3>
        </div>
        
        @if(isset($doctorBusinessRequests) && count($doctorBusinessRequests) > 0)
            <div class="space-y-4">
                @foreach($doctorBusinessRequests as $application)
                <div class="flex items-center justify-between p-4 bg-slate-700/30 rounded-lg border border-slate-600/30">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-building text-white text-sm"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-white">{{ $application->organization_name ?? 'Business Organization' }}</div>
                            <div class="text-sm text-slate-400">Applied {{ $application->created_at ? $application->created_at->diffForHumans() : '2 days ago' }}</div>
                        </div>
                    </div>
                    <span class="status-badge status-applied">
                        <i class="fas fa-clock"></i>
                        {{ $application->status ?? 'Pending' }}
                    </span>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <div class="w-16 h-16 bg-gradient-to-br from-slate-600 to-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-inbox text-2xl text-slate-400"></i>
                </div>
                <p class="text-slate-400">No applications submitted yet. Start by applying to available business requests above.</p>
            </div>
        @endif
    </div> -->
</div>

<!-- Application Modal -->
<div id="applicationModal" class="fixed inset-0 flex items-center justify-center bg-black/70 backdrop-blur-sm hidden z-[9982]">
    <div class="glass-card rounded-xl p-8 w-full max-w-md shadow-2xl mx-4">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-white">Apply to Request</h2>
            <button id="closeModal" class="text-slate-400 hover:text-white transition-colors p-2 hover:bg-slate-700/50 rounded-lg">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Cover Message</label>
                <textarea class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600/50 rounded-xl text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all backdrop-blur-sm" 
                          rows="4" placeholder="Introduce yourself and explain why you're interested..."></textarea>
            </div>
            
            <div class="flex gap-3">
                <button class="btn-modern flex-1" onclick="submitApplication()">
                    <i class="fas fa-paper-plane mr-2"></i>Submit Application
                </button>
                <button class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-3 rounded-xl transition-colors" onclick="closeApplicationModal()">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Business Request Details Modal -->
<div id="businessRequestModal" class="fixed inset-0 flex items-center justify-center bg-black/70 backdrop-blur-sm hidden z-[9981]">
    <div class="glass-card rounded-xl p-8 w-full max-w-2xl shadow-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-white">Business Request Details</h2>
            <button onclick="closeBusinessRequestModal()" class="text-slate-400 hover:text-white transition-colors p-2 hover:bg-slate-700/50 rounded-lg">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div id="businessRequestDetails" class="space-y-4 text-white">
            <!-- Business request details will be loaded here -->
        </div>
        
        <div class="mt-6 d-flex gap-3">
            <button onclick="closeBusinessRequestModal()" class="btn-modern flex-1">
                <i class="fas fa-check mr-2"></i>Close
            </button>
        </div>
    </div>
</div>

<script>
function showApplyModal() {
    document.getElementById('applicationModal').classList.remove('hidden');
}

function closeApplicationModal() {
    document.getElementById('applicationModal').classList.add('hidden');
}

function refreshOpportunities() {
    location.reload();
}

function viewRequest(id) {
    alert('View request details feature coming soon!');
}

function applyToRequest(id) {
    showApplyModal();
}

function submitApplication() {
    alert('Application submitted successfully! You will be notified of the response.');
    closeApplicationModal();
}

function showFullMessage(message) {
    alert(message);
}

function showAdminRemarks(remarks) {
    alert(remarks);
}

function viewSelectedRequest() {
    const select = document.getElementById('business_organization_request_id');
    const selectedOption = select.options[select.selectedIndex];
    
    if (!selectedOption.value) {
        alert('Please select a business request first.');
        return;
    }
    
    const requestData = JSON.parse(selectedOption.getAttribute('data-request'));
    showBusinessRequestDetails(requestData);
}

function showBusinessRequestDetails(request) {
    const modalContent = document.getElementById('businessRequestDetails');
    
    // Format dates
    const dateFrom = new Date(request.date_from).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    const dateTo = new Date(request.date_to).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    
    modalContent.innerHTML = `
        <div class="bg-slate-700/50 rounded-lg p-4 mb-4">
            <h3 class="text-xl font-semibold text-white mb-3">
                <i class="fas fa-building mr-2 text-blue-400"></i>
                ${request.organization_name}
            </h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div class="bg-slate-700/30 rounded-lg p-4">
                <h4 class="font-semibold text-blue-300 mb-2">
                    <i class="fas fa-stethoscope mr-2"></i>Camp Details
                </h4>
                <p><strong>Type:</strong> ${request.camp_request_type}</p>
                <p><strong>Specialty:</strong> ${request.specialty ? request.specialty.name : 'Not specified'}</p>
                <p><strong>Participants:</strong> ${request.number_of_people} people</p>
            </div>
            
            <div class="bg-slate-700/30 rounded-lg p-4">
                <h4 class="font-semibold text-green-300 mb-2">
                    <i class="fas fa-calendar mr-2"></i>Schedule & Location
                </h4>
                <p><strong>From:</strong> ${dateFrom}</p>
                <p><strong>To:</strong> ${dateTo}</p>
                <p><strong>Location:</strong> ${request.location}</p>
            </div>
        </div>
        
        <div class="bg-slate-700/30 rounded-lg p-4 mb-4">
            <h4 class="font-semibold text-purple-300 mb-2">
                <i class="fas fa-envelope mr-2"></i>Contact Information
            </h4>
            <p><strong>Email:</strong> ${request.email}</p>
            <p><strong>Phone:</strong> ${request.phone_number}</p>
        </div>
        
        ${request.description ? `
        <div class="bg-slate-700/30 rounded-lg p-4">
            <h4 class="font-semibold text-yellow-300 mb-2">
                <i class="fas fa-info-circle mr-2"></i>Description
            </h4>
            <p class="text-slate-200">${request.description}</p>
        </div>
        ` : ''}
    `;
    
    document.getElementById('businessRequestModal').classList.remove('hidden');
}

function closeBusinessRequestModal() {
    document.getElementById('businessRequestModal').classList.add('hidden');
}

// Enable/disable view button based on selection
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('business_organization_request_id');
    const viewBtn = document.getElementById('viewRequestBtn');
    
    select.addEventListener('change', function() {
        viewBtn.disabled = !this.value;
        if (this.value) {
            viewBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            viewBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    });
});

// Form submission handling
document.getElementById('proposalForm').addEventListener('submit', function(e) {
    const message = document.getElementById('message').value.trim();
    if (message.length < 50) {
        e.preventDefault();
        alert('Please provide a more detailed proposal message (at least 50 characters).');
        return false;
    }
});

// Event handlers for new button classes
document.addEventListener('click', function(e) {
    if (e.target.closest('.view-request-btn')) {
        const id = e.target.closest('.view-request-btn').getAttribute('data-id');
        viewRequest(id);
    }
    
    if (e.target.closest('.apply-request-btn')) {
        const id = e.target.closest('.apply-request-btn').getAttribute('data-id');
        applyToRequest(id);
    }
});

// Close modal when clicking outside
document.getElementById('applicationModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeApplicationModal();
    }
});

document.getElementById('businessRequestModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeBusinessRequestModal();
    }
});

document.getElementById('closeModal').addEventListener('click', closeApplicationModal);
</script>
@endsection
