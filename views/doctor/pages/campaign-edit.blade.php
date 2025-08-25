@extends('doctor.master')

@section('title', 'Edit Campaign - FreeDoctor')

@push('styles')
<style>
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
    
    .form-control {
        background: rgba(30, 41, 59, 0.5) !important;
        border: 1px solid rgba(148, 163, 184, 0.3) !important;
        color: #f8fafc !important;
        border-radius: 8px !important;
    }
    
    .form-control:focus {
        background: rgba(30, 41, 59, 0.7) !important;
        border-color: rgba(59, 130, 246, 0.7) !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
    }
    
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
</style>
@endpush

@section('content')
<div class="min-h-screen space-y-8">
    <!-- Page Header -->
    <div class="glass-card rounded-2xl p-8">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-2xl">
                    <i class="fas fa-edit text-2xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-white">Edit Campaign</h1>
                    <p class="text-slate-300 mt-2 text-lg">Update campaign details and information</p>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('doctor.campaigns.view', $campaign->id) }}" class="btn btn-outline-light">
                    <i class="fas fa-arrow-left mr-2"></i>Back to View
                </a>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="glass-card rounded-xl p-8">
        <form id="editCampaignForm" method="POST" action="{{ route('doctor.campaigns.update', $campaign->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <!-- Basic Information -->
                <div class="col-md-8">
                    <h3 class="text-xl font-semibold text-white mb-6">Basic Information</h3>
                    
                    <div class="mb-4">
                        <label class="form-label text-slate-300">Campaign Title</label>
                        <input type="text" class="form-control" name="title" value="{{ $campaign->title }}" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label text-slate-300">Description</label>
                        <textarea class="form-control" name="description" rows="4" required>{{ $campaign->description }}</textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label text-slate-300">Location</label>
                            <input type="text" class="form-control" name="location" value="{{ $campaign->location }}" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label text-slate-300">Contact Number</label>
                            <input type="text" class="form-control" name="contact_number" value="{{ $campaign->contact_number }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label text-slate-300">Start Date</label>
                            <input type="date" class="form-control" name="start_date" value="{{ $campaign->start_date }}" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label text-slate-300">End Date</label>
                            <input type="date" class="form-control" name="end_date" value="{{ $campaign->end_date }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label text-slate-300">Timings</label>
                            <input type="text" class="form-control" name="timings" value="{{ $campaign->timings }}" placeholder="e.g., 9:00 AM - 5:00 PM" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label text-slate-300">Camp Type</label>
                            <select class="form-control" name="camp_type" required>
                                <option value="medical" {{ $campaign->camp_type == 'medical' ? 'selected' : '' }}>Medical</option>
                                <option value="surgical" {{ $campaign->camp_type == 'surgical' ? 'selected' : '' }}>Surgical</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label text-slate-300">Expected Patients</label>
                            <input type="number" class="form-control" name="expected_patients" value="{{ $campaign->expected_patients }}" min="1" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label text-slate-300">Registration Payment (₹)</label>
                            <input type="number" class="form-control" name="registration_payment" value="{{ $campaign->registration_payment }}" min="0" step="0.01">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label text-slate-300">Services in Camp</label>
                        <textarea class="form-control" name="service_in_camp" rows="3" required>{{ $campaign->service_in_camp }}</textarea>
                    </div>
                </div>
                
                <!-- Sidebar Information -->
                <div class="col-md-4">
                    <h3 class="text-xl font-semibold text-white mb-6">Additional Details</h3>
                    
                    <div class="mb-4">
                        <label class="form-label text-slate-300">Specialties</label>
                        <select class="form-control" name="specializations[]" multiple style="height: 120px;">
                            @foreach($specialties as $specialty)
                                @php
                                    $campaignSpecializations = is_string($campaign->specializations) 
                                        ? json_decode($campaign->specializations, true) ?? []
                                        : (is_array($campaign->specializations) ? $campaign->specializations : []);
                                @endphp
                                <option value="{{ $specialty->id }}" 
                                    {{ in_array($specialty->id, $campaignSpecializations) ? 'selected' : '' }}>
                                    {{ $specialty->name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-slate-400">Hold Ctrl to select multiple</small>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label text-slate-300">Campaign Images</label>
                        <input type="file" class="form-control" name="images[]" multiple accept="image/*">
                        <small class="text-slate-400">Leave empty to keep existing images</small>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label text-slate-300">Thumbnail</label>
                        <input type="file" class="form-control" name="thumbnail" accept="image/*">
                        <small class="text-slate-400">Leave empty to keep existing thumbnail</small>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label text-slate-300">Campaign Video</label>
                        <input type="file" class="form-control" name="video" accept="video/*">
                        <small class="text-slate-400">Leave empty to keep existing video</small>
                    </div>
                    
                    <!-- Current Status -->
                    <div class="bg-slate-700/30 p-4 rounded-lg border border-slate-600/30 mb-4">
                        <h4 class="text-white font-semibold mb-2">Current Status</h4>
                        <span class="badge badge-{{ $campaign->approval_status == 'approved' ? 'success' : ($campaign->approval_status == 'pending' ? 'warning' : 'danger') }}">
                            {{ ucfirst($campaign->approval_status) }}
                        </span>
                        <p class="text-slate-400 text-sm mt-2">Created: {{ $campaign->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="border-t border-slate-600/30 pt-6 mt-8">
                <div class="flex justify-between">
                    <a href="{{ route('doctor.campaigns.view', $campaign->id) }}" class="btn btn-outline-light">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn-modern">
                        <i class="fas fa-save mr-2"></i>Update Campaign
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $('#editCampaignForm').on('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        
        // Show loading
        Swal.fire({
            title: 'Updating Campaign...',
            allowEscapeKey: false,
            allowOutsideClick: false,
            showConfirmButton: false,
            background: 'rgba(30, 41, 59, 0.95)',
            color: '#f8fafc',
            willOpen: () => {
                Swal.showLoading();
            }
        });
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false,
                        background: 'rgba(30, 41, 59, 0.95)',
                        color: '#f8fafc'
                    }).then(() => {
                        window.location.href = "{{ route('doctor.campaigns.view', $campaign->id) }}";
                    });
                }
            },
            error: function(xhr) {
                let message = 'Failed to update campaign.';
                
                // Handle validation errors
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = Object.values(xhr.responseJSON.errors).flat();
                    message = errors.join('\n');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: message,
                    background: 'rgba(30, 41, 59, 0.95)',
                    color: '#f8fafc'
                });
            }
        });
    });
});
</script>
@endsection
