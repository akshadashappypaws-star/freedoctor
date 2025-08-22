@extends('../admin.dashboard')

@section('content')
<div class="p-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Doctor Details</h1>
            <a href="{{ route('admin.doctors') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i>Back to Doctors
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Doctor Image and Video -->
            <div class="lg:col-span-1">
                <div class="text-center">
                    @if($doctor->image)
                        <img src="{{ asset('storage/' . $doctor->image) }}" 
                             alt="Dr. {{ $doctor->doctor_name }}" 
                             class="w-48 h-48 mx-auto rounded-full object-cover shadow-lg">
                    @else
                        <div class="w-48 h-48 mx-auto bg-blue-500 rounded-full flex items-center justify-center shadow-lg">
                            <i class="fas fa-user-md text-6xl text-white"></i>
                        </div>
                    @endif
                    
                    <h2 class="text-2xl font-bold text-gray-800 mt-4">Dr. {{ $doctor->doctor_name }}</h2>
                    <p class="text-lg text-blue-600">{{ $doctor->specialty->name ?? 'No Specialty' }}</p>
                    
                    <!-- Status Badges -->
                    <div class="flex justify-center space-x-2 mt-4">
                        <span class="inline-block px-3 py-1 rounded-full text-sm
                            {{ $doctor->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $doctor->status ? 'Active' : 'Inactive' }}
                        </span>
                        <span class="inline-block px-3 py-1 rounded-full text-sm
                            {{ $doctor->approved_by_admin === 'yes' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $doctor->approved_by_admin === 'yes' ? 'Approved' : 'Pending' }}
                        </span>
                        @if($doctor->phone_verified)
                            <span class="inline-block px-3 py-1 rounded-full text-sm bg-green-100 text-green-800">
                                Phone Verified
                            </span>
                        @endif
                    </div>
                </div>

                @if($doctor->intro_video)
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold mb-3">Introduction Video</h3>
                        <video controls class="w-full rounded-lg shadow-md">
                            <source src="{{ asset('storage/' . $doctor->intro_video) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                @endif
            </div>

            <!-- Doctor Details -->
            <div class="lg:col-span-2">
                <div class="space-y-6">
                    <!-- Personal Information -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Personal Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Full Name</label>
                                <p class="text-lg text-gray-800">Dr. {{ $doctor->doctor_name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Gender</label>
                                <p class="text-lg text-gray-800">{{ ucfirst($doctor->gender) }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Email</label>
                                <p class="text-lg text-gray-800">{{ $doctor->email }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Phone</label>
                                <p class="text-lg text-gray-800">{{ $doctor->phone }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Location</label>
                                <p class="text-lg text-gray-800">{{ $doctor->location }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Professional Information -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Professional Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Specialty</label>
                                <p class="text-lg text-gray-800">{{ $doctor->specialty->name ?? 'No Specialty' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Experience</label>
                                <p class="text-lg text-gray-800">{{ $doctor->experience }} years</p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-600">Hospital/Clinic</label>
                                <p class="text-lg text-gray-800">{{ $doctor->hospital_name }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($doctor->description)
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">About Doctor</h3>
                        <p class="text-gray-700 leading-relaxed">{{ $doctor->description }}</p>
                    </div>
                    @endif

                    <!-- Account Information -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Account Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Account Status</label>
                                <span class="inline-block px-3 py-1 rounded-full text-sm
                                    {{ $doctor->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $doctor->status ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Approval Status</label>
                                <span class="inline-block px-3 py-1 rounded-full text-sm
                                    {{ $doctor->approved_by_admin === 'yes' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $doctor->approved_by_admin === 'yes' ? 'Approved' : 'Pending Approval' }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Phone Verification</label>
                                <span class="inline-block px-3 py-1 rounded-full text-sm
                                    {{ $doctor->phone_verified ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $doctor->phone_verified ? 'Verified' : 'Not Verified' }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Registration Date</label>
                                <p class="text-lg text-gray-800">{{ $doctor->created_at->format('F j, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Quick Actions</h3>
                        <div class="flex flex-wrap gap-3">
                            <button onclick="editDoctor({{ $doctor->id }})" 
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition">
                                <i class="fas fa-edit mr-2"></i>Edit Doctor
                            </button>
                            
                            @if($doctor->approved_by_admin !== 'yes')
                                <button onclick="approveDoctor({{ $doctor->id }})"
                                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition">
                                    <i class="fas fa-check mr-2"></i>Approve Doctor
                                </button>
                            @endif
                            
                            <button onclick="toggleStatus({{ $doctor->id }}, {{ $doctor->status ? 0 : 1 }})"
                                class="bg-{{ $doctor->status ? 'red' : 'green' }}-500 hover:bg-{{ $doctor->status ? 'red' : 'green' }}-600 text-white px-4 py-2 rounded-lg transition">
                                <i class="fas fa-{{ $doctor->status ? 'ban' : 'check' }} mr-2"></i>
                                {{ $doctor->status ? 'Deactivate' : 'Activate' }} Doctor
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function editDoctor(id) {
    window.location.href = `{{ route('admin.doctors') }}?edit=${id}`;
}

function approveDoctor(id) {
    Swal.fire({
        title: 'Approve Doctor?',
        text: 'This will approve the doctor\'s account.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, approve!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin/doctors/${id}/approve`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (data) {
                    if (data.success) {
                        location.reload();
                        Swal.fire('Approved!', 'Doctor has been approved.', 'success');
                    } else {
                        Swal.fire('Error!', 'Failed to approve doctor.', 'error');
                    }
                }
            });
        }
    });
}

function toggleStatus(id, newStatus) {
    const action = newStatus ? 'activate' : 'deactivate';
    
    Swal.fire({
        title: `${action.charAt(0).toUpperCase() + action.slice(1)} Doctor?`,
        text: `This will ${action} the doctor's account.`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: newStatus ? '#10b981' : '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: `Yes, ${action}!`
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin/doctors/${id}/toggle-status`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    status: newStatus
                },
                success: function (data) {
                    if (data.success) {
                        location.reload();
                        Swal.fire(`${action.charAt(0).toUpperCase() + action.slice(1)}d!`, `Doctor has been ${action}d.`, 'success');
                    } else {
                        Swal.fire('Error!', `Failed to ${action} doctor.`, 'error');
                    }
                }
            });
        }
    });
}
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
