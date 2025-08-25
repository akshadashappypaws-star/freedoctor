@extends('user.master')

@section('title', 'My Registrations')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">My Registrations</h1>
                    <p class="mb-0 text-muted">Your campaign registrations and attendance status</p>
                </div>
                <div>
                    <span class="badge badge-primary badge-pill">{{ $registrations->count() }} Total</span>
                </div>
            </div>
        </div>
    </div>

    @if($registrations->count() > 0)
        <!-- Registrations Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Registration History</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Campaign</th>
                                <th>Doctor</th>
                                <th>Registration Date</th>
                                <th>Campaign Date</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($registrations as $registration)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($registration->campaign->thumbnail)
                                                <img src="{{ asset('storage/' . $registration->campaign->thumbnail) }}" 
                                                     class="rounded-circle mr-3" 
                                                     style="width: 40px; height: 40px; object-fit: cover;"
                                                     alt="Campaign">
                                            @else
                                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mr-3" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-heartbeat text-white"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-weight-bold">{{ $registration->campaign->title }}</div>
                                                <small class="text-muted">{{ Str::limit($registration->campaign->description, 50) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="font-weight-bold">{{ $registration->campaign->doctor->name ?? 'N/A' }}</div>
                                            <small class="text-muted">
                                                @if($registration->campaign->specializations && is_array($registration->campaign->specializations))
                                                    {{ implode(', ', array_slice($registration->campaign->specializations, 0, 2)) }}
                                                @endif
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>{{ $registration->created_at->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ $registration->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $registration->campaign->start_date->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ $registration->campaign->timings }}</small>
                                    </td>
                                    <td>
                                        <div>{{ Str::limit($registration->campaign->location, 30) }}</div>
                                        <small class="text-muted">
                                            <i class="fas fa-phone"></i> {{ $registration->campaign->contact_number }}
                                        </small>
                                    </td>
                                    <td>
                                        @php
                                            $status = $registration->getStatusWithLogic();
                                        @endphp
                                        
                                        @switch($status)
                                            @case('yet_to_came')
                                                <span class="badge badge-primary">
                                                    <i class="fas fa-clock mr-1"></i>Yet to Come
                                                </span>
                                                @break
                                            @case('came')
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check mr-1"></i>Attended
                                                </span>
                                                @break
                                            @case('not_came')
                                                <span class="badge badge-danger">
                                                    <i class="fas fa-times mr-1"></i>Not Attended
                                                </span>
                                                @break
                                            @default
                                                <span class="badge badge-secondary">Unknown</span>
                                        @endswitch
                                        
                                        <!-- Campaign status indicator -->
                                        <div class="mt-1">
                                            @php
                                                $today = now();
                                                $campaignStatus = 'upcoming';
                                                $campaignStatusClass = 'info';
                                                $campaignStatusText = 'Upcoming';
                                                
                                                if ($today->isAfter($registration->campaign->end_date)) {
                                                    $campaignStatus = 'completed';
                                                    $campaignStatusClass = 'secondary';
                                                    $campaignStatusText = 'Completed';
                                                } elseif ($today->between($registration->campaign->start_date, $registration->campaign->end_date)) {
                                                    $campaignStatus = 'ongoing';
                                                    $campaignStatusClass = 'warning';
                                                    $campaignStatusText = 'Ongoing';
                                                }
                                            @endphp
                                            <small class="badge badge-{{ $campaignStatusClass }}">{{ $campaignStatusText }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('user.campaign.details', $registration->campaign->id) }}" 
                                               class="btn btn-sm btn-info" title="View Campaign">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button class="btn btn-sm btn-primary" 
                                                    data-toggle="modal" 
                                                    data-target="#registrationModal"
                                                    onclick="viewRegistration({{ json_encode($registration) }})"
                                                    title="View Registration Details">
                                                <i class="fas fa-info-circle"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <i class="fas fa-user-plus fa-5x text-gray-300 mb-4"></i>
                <h4 class="text-muted mb-3">No Registrations Yet</h4>
                <p class="text-muted mb-4">
                    You haven't registered for any health campaigns yet. 
                    Start by browsing available campaigns and register to participate.
                </p>
                <a href="{{ route('campaigns.index') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-search mr-2"></i>Browse Campaigns
                </a>
            </div>
        </div>
    @endif
</div>

<!-- Registration Details Modal -->
<div class="modal fade" id="registrationModal" tabindex="-1" role="dialog" aria-labelledby="registrationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registrationModalLabel">Registration Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="registrationDetails">
                <!-- Registration details will be populated here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
</style>
@endpush

@push('js')
<script>
function viewRegistration(registration) {
    const campaign = registration.campaign;
    const doctor = campaign.doctor || {};
    
    const details = `
        <div class="row">
            <div class="col-md-6">
                <h6>Personal Information</h6>
                <p><strong>Name:</strong> ${registration.name}</p>
                <p><strong>Email:</strong> ${registration.email}</p>
                <p><strong>Phone:</strong> ${registration.phone_number}</p>
                <p><strong>Address:</strong> ${registration.address}</p>
            </div>
            <div class="col-md-6">
                <h6>Registration Details</h6>
                <p><strong>Registration Date:</strong> ${new Date(registration.created_at).toLocaleDateString()}</p>
                <p><strong>Status:</strong> 
                    <span class="badge badge-${getStatusBadgeClass(registration.status)}">
                        ${getStatusLabel(registration.status)}
                    </span>
                </p>
                ${registration.description ? `<p><strong>Description:</strong> ${registration.description}</p>` : ''}
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <h6>Campaign Information</h6>
                <p><strong>Title:</strong> ${campaign.title}</p>
                <p><strong>Location:</strong> ${campaign.location}</p>
                <p><strong>Date:</strong> ${new Date(campaign.start_date).toLocaleDateString()} - ${new Date(campaign.end_date).toLocaleDateString()}</p>
                <p><strong>Timings:</strong> ${campaign.timings}</p>
                <p><strong>Contact:</strong> ${campaign.contact_number}</p>
            </div>
            <div class="col-md-6">
                <h6>Doctor Information</h6>
                <p><strong>Name:</strong> ${doctor.name || 'N/A'}</p>
                ${campaign.specializations ? `<p><strong>Specializations:</strong> ${Array.isArray(campaign.specializations) ? campaign.specializations.join(', ') : campaign.specializations}</p>` : ''}
                <p><strong>Camp Type:</strong> 
                    <span class="badge badge-${campaign.camp_type === 'free' ? 'success' : 'warning'}">
                        ${campaign.camp_type === 'free' ? 'Free' : 'Paid'} Camp
                    </span>
                </p>
                ${campaign.amount ? `<p><strong>Amount:</strong> ₹${campaign.amount}</p>` : ''}
            </div>
        </div>
        ${campaign.description ? `
        <hr>
        <div class="row">
            <div class="col-12">
                <h6>Campaign Description</h6>
                <p>${campaign.description}</p>
            </div>
        </div>
        ` : ''}
    `;
    
    document.getElementById('registrationDetails').innerHTML = details;
}

function getStatusBadgeClass(status) {
    switch(status) {
        case 'yet_to_came': return 'primary';
        case 'came': return 'success';
        case 'not_came': return 'danger';
        default: return 'secondary';
    }
}

function getStatusLabel(status) {
    switch(status) {
        case 'yet_to_came': return 'Yet to Come';
        case 'came': return 'Attended';
        case 'not_came': return 'Not Attended';
        default: return 'Unknown';
    }
}

// Initialize DataTable if registrations exist
document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('dataTable');
    if (table && typeof $.fn.DataTable !== 'undefined') {
        $(table).DataTable({
            "pageLength": 10,
            "responsive": true,
            "order": [[ 2, "desc" ]], // Sort by registration date descending
            "columnDefs": [
                { "orderable": false, "targets": -1 } // Disable sorting on actions column
            ]
        });
    }
});
</script>
@endpush
@endsection
