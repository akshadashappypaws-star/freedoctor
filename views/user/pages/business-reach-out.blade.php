@extends('user.master')

@section('title', 'Business Reach Out')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">Business Reach Out</h1>
                    <p class="mb-0 text-muted">Connect with organizations for health camp opportunities</p>
                </div>
                <button class="btn btn-primary" data-toggle="modal" data-target="#businessRequestModal">
                    <i class="fas fa-plus"></i> New Request
                </button>
            </div>
        </div>
    </div>

    <!-- Business Opportunity Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-4">
            <div class="card border-left-primary shadow h-100">
                <div class="card-body">
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="fas fa-building fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title">Corporate Health Camps</h5>
                        <p class="card-text text-muted">Partner with corporations for employee health checkups and wellness programs.</p>
                        <div class="mt-3">
                            <span class="badge badge-primary">High Demand</span>
                            <span class="badge badge-success">Good Revenue</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card border-left-success shadow h-100">
                <div class="card-body">
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="fas fa-school fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title">Educational Institutes</h5>
                        <p class="card-text text-muted">Provide health services to schools, colleges, and universities for students and staff.</p>
                        <div class="mt-3">
                            <span class="badge badge-success">Regular Opportunity</span>
                            <span class="badge badge-info">Bulk Bookings</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card border-left-warning shadow h-100">
                <div class="card-body">
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="fas fa-users fa-3x text-warning"></i>
                        </div>
                        <h5 class="card-title">Community Organizations</h5>
                        <p class="card-text text-muted">Collaborate with NGOs, community centers, and social organizations.</p>
                        <div class="mt-3">
                            <span class="badge badge-warning">Social Impact</span>
                            <span class="badge badge-secondary">Volume Based</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Business Requests Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Your Business Requests</h6>
        </div>
        <div class="card-body">
            @if($requests->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Organization</th>
                                <th>Type</th>
                                <th>Specialty</th>
                                <th>Date Range</th>
                                <th>People</th>
                                <th>Status</th>
                                <th>Submitted</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $request)
                                <tr>
                                    <td>
                                        <div class="font-weight-bold">{{ $request->organization_name }}</div>
                                        <small class="text-muted">{{ $request->email }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ ucfirst($request->camp_request_type) }}</span>
                                    </td>
                                    <td>{{ $request->specialty->name ?? 'N/A' }}</td>
                                    <td>
                                        <small>
                                            {{ $request->date_from->format('M d') }} - 
                                            {{ $request->date_to->format('M d, Y') }}
                                        </small>
                                    </td>
                                    <td>{{ $request->number_of_people }}</td>
                                    <td>
                                        @switch($request->status)
                                            @case('pending')
                                                <span class="badge badge-warning">Pending</span>
                                                @break
                                            @case('approved')
                                                <span class="badge badge-success">Approved</span>
                                                @break
                                            @case('rejected')
                                                <span class="badge badge-danger">Rejected</span>
                                                @break
                                            @default
                                                <span class="badge badge-secondary">Unknown</span>
                                        @endswitch
                                    </td>
                                    <td>{{ $request->created_at->diffForHumans() }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#viewRequestModal" 
                                                onclick="viewRequest({{ json_encode($request) }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-handshake fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-muted">No business requests yet</h5>
                    <p class="text-muted">Start by creating your first business request to connect with organizations.</p>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#businessRequestModal">
                        <i class="fas fa-plus"></i> Create Your First Request
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Business Request Modal -->
<div class="modal fade" id="businessRequestModal" tabindex="-1" role="dialog" aria-labelledby="businessRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="businessRequestModalLabel">Create Business Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('user.business-request.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="organization_name">Organization Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="organization_name" name="organization_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="camp_request_type">Camp Type <span class="text-danger">*</span></label>
                                <select class="form-control" id="camp_request_type" name="camp_request_type" required>
                                    <option value="">Select Type</option>
                                    <option value="medical">Medical Camp</option>
                                    <option value="surgical">Surgical Camp</option>
                                    <option value="wellness">Wellness Camp</option>
                                    <option value="screening">Health Screening</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone_number">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="phone_number" name="phone_number" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="specialty_id">Specialty Required <span class="text-danger">*</span></label>
                                <select class="form-control" id="specialty_id" name="specialty_id" required>
                                    <option value="">Select Specialty</option>
                                    <!-- Add specialty options here -->
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="date_from">From Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="date_from" name="date_from" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="date_to">To Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="date_to" name="date_to" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="number_of_people">Expected People <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="number_of_people" name="number_of_people" min="1" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="location">Location <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="location" name="location" placeholder="Complete address" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="description" name="description" rows="4" 
                                  placeholder="Describe your requirements, expectations, and any special needs..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Request Modal -->
<div class="modal fade" id="viewRequestModal" tabindex="-1" role="dialog" aria-labelledby="viewRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewRequestModalLabel">Request Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="requestDetails">
                <!-- Request details will be populated here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('css')
<style>
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
    
    .card {
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
</style>
@endpush

@push('js')
<script>
function viewRequest(request) {
    const details = `
        <div class="row">
            <div class="col-md-6">
                <h6>Organization Details</h6>
                <p><strong>Name:</strong> ${request.organization_name}</p>
                <p><strong>Email:</strong> ${request.email}</p>
                <p><strong>Phone:</strong> ${request.phone_number}</p>
                <p><strong>Type:</strong> ${request.camp_request_type}</p>
            </div>
            <div class="col-md-6">
                <h6>Camp Details</h6>
                <p><strong>Date Range:</strong> ${request.date_from} to ${request.date_to}</p>
                <p><strong>Expected People:</strong> ${request.number_of_people}</p>
                <p><strong>Location:</strong> ${request.location}</p>
                <p><strong>Status:</strong> <span class="badge badge-${getStatusBadgeClass(request.status)}">${request.status}</span></p>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <h6>Description</h6>
                <p>${request.description}</p>
            </div>
        </div>
        ${request.hired_doctor_id ? `
        <div class="row mt-3">
            <div class="col-12">
                <h6>Assigned Doctor</h6>
                <p>Doctor ID: ${request.hired_doctor_id}</p>
            </div>
        </div>
        ` : ''}
    `;
    
    document.getElementById('requestDetails').innerHTML = details;
}

function getStatusBadgeClass(status) {
    switch(status) {
        case 'pending': return 'warning';
        case 'approved': return 'success';
        case 'rejected': return 'danger';
        default: return 'secondary';
    }
}

// Set minimum date to today
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('date_from').setAttribute('min', today);
    document.getElementById('date_to').setAttribute('min', today);
    
    // Update to date minimum when from date changes
    document.getElementById('date_from').addEventListener('change', function() {
        document.getElementById('date_to').setAttribute('min', this.value);
    });
});
</script>
@endpush
@endsection
