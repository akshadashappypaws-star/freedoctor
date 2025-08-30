@extends('../admin.dashboard')

@section('content')
<!-- SweetAlert2 -->

<div style="padding:15px" class="p-6 rounded shadow">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Doctors Management</h1>
        <button id="openModalBtn"
            class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
            <i class="fas fa-plus mr-2"></i>Add Doctor
        </button>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search by Name</label>
                <input type="text" id="searchInput" placeholder="Search doctors..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-black">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Specialty</label>
                <select id="specialtyFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-black">
                    <option value="">All Specialties</option>
                    @if(isset($specialties) && $specialties)
                        @foreach($specialties as $specialty)
                            <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Location</label>
                <input type="text" id="locationFilter" placeholder="Filter by location..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-black">
            </div>
        </div>
    </div>

    <!-- Doctors Grid -->
    <div id="doctorGrid"
        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 transition-all duration-300">
        @foreach($doctors as $doctor)
        <div class="doctor-card bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:scale-102 overflow-hidden"
            data-id="{{ $doctor->id }}" data-name="{{ strtolower($doctor->doctor_name) }}" 
            data-specialty="{{ $doctor->specialty_id }}" data-location="{{ strtolower($doctor->location) }}">
            
            <!-- Status Badge -->
            <div class="absolute top-3 left-3 z-10">
                @if($doctor->status === 'active')
                    <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full">Active</span>
                @else
                    <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">Inactive</span>
                @endif
            </div>

            <!-- Approval Badge -->
            <div class="absolute top-3 right-3 z-10">
                @if($doctor->approved_by_admin === 'yes')
                    <span class="bg-blue-500 text-white text-xs px-2 py-1 rounded-full">Approved</span>
                @else
                    <span class="bg-yellow-500 text-white text-xs px-2 py-1 rounded-full">Pending</span>
                @endif
            </div>

            <!-- Delete Button -->
            <button class="absolute top-12 right-3 z-10 bg-red-500 hover:bg-red-600 text-white text-xs w-6 h-6 rounded-full delete-btn transform transition-all duration-200 hover:scale-125 flex items-center justify-center">
                ✕
            </button>

            <!-- Doctor Image -->
            <div class="relative h-48 bg-gradient-to-br from-blue-50 to-indigo-100">
                @if($doctor->image)
                    <img src="{{ asset('storage/' . $doctor->image) }}" 
                         alt="{{ $doctor->doctor_name }}" 
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <div class="w-20 h-20 bg-blue-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-md text-3xl text-white"></i>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Doctor Info -->
            <div class="p-4">
                <h3 class="text-lg font-bold text-gray-800 mb-1 truncate">{{ $doctor->doctor_name }}</h3>
                <p class="text-sm text-blue-600 mb-2">{{ $doctor->specialty->name ?? 'No Specialty' }}</p>
                
                <div class="space-y-1 text-xs text-gray-600">
                    <div class="flex items-center">
                        <i class="fas fa-phone w-4"></i>
                        <span class="ml-2 truncate">{{ $doctor->phone }}</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-map-marker-alt w-4"></i>
                        <span class="ml-2 truncate">{{ $doctor->location }}</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-venus-mars w-4"></i>
                        <span class="ml-2">{{ ucfirst($doctor->gender) }}</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-hospital w-4"></i>
                        <span class="ml-2 truncate">{{ $doctor->hospital_name }}</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-clock w-4"></i>
                        <span class="ml-2">{{ $doctor->experience }} years</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-4 flex justify-center space-x-2">
                    <button class="edit-btn bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded-full transition">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </button>
                    <button class="view-btn bg-purple-500 hover:bg-purple-600 text-white text-xs px-3 py-1 rounded-full transition">
                        <i class="fas fa-eye mr-1"></i>View
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- No Results Message -->
    <div id="noResults" class="hidden text-center py-12">
        <div class="text-gray-500">
            <i class="fas fa-search text-6xl mb-4"></i>
            <h3 class="text-xl font-medium mb-2">No doctors found</h3>
            <p>Try adjusting your search criteria</p>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="doctorModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-[9991]">
    <div class="bg-white rounded-lg p-6 w-full max-w-2xl shadow-xl max-h-screen overflow-y-auto">
        <h2 id="modalTitle" class="text-2xl font-bold mb-6">Add New Doctor</h2>
        <form id="doctorForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="doctorId" name="id">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Doctor Image -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Doctor Image</label>
                    <div class="flex items-center space-x-4">
                        <div id="imagePreview" class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden">
                            <i class="fas fa-user-md text-gray-400 text-2xl"></i>
                        </div>
                        <input type="file" id="doctorImage" name="image" accept="image/*" class="flex-1 px-3 py-2 border rounded-lg">
                    </div>
                </div>

                <!-- Doctor Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Doctor Name *</label>
                    <input type="text" name="doctor_name" id="doctorName" required 
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-black">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" name="email" id="doctorEmail" required 
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-black">
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                    <input type="tel" name="phone" id="doctorPhone" required 
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-black">
                </div>

                <!-- Phone Verified -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Verified</label>
                    <select name="phone_verified" id="doctorPhoneVerified" 
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-black">
                        <option value="0">Not Verified</option>
                        <option value="1">Verified</option>
                    </select>
                </div>

                <!-- Location -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Location *</label>
                    <input type="text" name="location" id="doctorLocation" required 
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-black">
                </div>

                <!-- Gender -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gender *</label>
                    <select name="gender" id="doctorGender" required 
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-black">
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <!-- Specialty -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Specialty *</label>
                    <select name="specialty_id" id="doctorSpecialty" required 
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-black">
                        <option value="">Select Specialty</option>
                        @if(isset($specialties) && $specialties)
                            @foreach($specialties as $specialty)
                                <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Hospital Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Hospital Name *</label>
                    <input type="text" name="hospital_name" id="hospitalName" required 
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-black">
                </div>

                <!-- Experience -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Experience (Years) *</label>
                    <input type="number" name="experience" id="doctorExperience" min="0" required 
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-black">
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" id="doctorPassword" 
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-black" 
                        placeholder="Leave blank for default password">
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="doctorDescription" rows="3" 
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-black" 
                        placeholder="Doctor's bio or description"></textarea>
                </div>

                <!-- Intro Video -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Intro Video File</label>
                    <input type="file" name="intro_video" id="doctorIntroVideo" accept="video/*"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-black" 
                        placeholder="Select video file">
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" id="doctorStatus" 
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-black">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>

                <!-- Approval Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Approval Status</label>
                    <select name="approved_by_admin" id="doctorApproved" 
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-black">
                        <option value="no">Pending</option>
                        <option value="yes">Approved</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" id="cancelModalBtn"
                    class="px-6 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500 transition">Cancel</button>
                <button type="submit" id="submitBtn"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Add Doctor</button>
            </div>
        </form>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    let isEditMode = false;

    const modal = $('#doctorModal');
    const modalTitle = $('#modalTitle');
    const submitBtn = $('#submitBtn');
    const doctorForm = $('#doctorForm');

    $('#openModalBtn').on('click', function () {
        isEditMode = false;
        modalTitle.text('Add New Doctor');
        submitBtn.text('Add Doctor');
        doctorForm[0].reset();
        $('#doctorId').val('');
        $('#imagePreview').html('<i class="fas fa-user-md text-gray-400 text-2xl"></i>');
        modal.removeClass('hidden');
    });

    $('#cancelModalBtn').on('click', function () {
        modal.addClass('hidden');
    });

    $('#doctorImage').on('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $('#imagePreview').html(`<img src="${e.target.result}" class="w-full h-full object-cover rounded-full">`);
            };
            reader.readAsDataURL(file);
        }
    });

    doctorForm.on('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        const url = isEditMode ? 
            `/admin/doctors/${$('#doctorId').val()}/update` : 
            '{{ route("admin.doctors.store") }}';

        if (isEditMode) {
            formData.append('_method', 'PUT');
        }

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function (data) {
                if (data.success) {
                    modal.addClass('hidden');
                    location.reload();
                    Swal.fire({
                        icon: 'success',
                        title: isEditMode ? 'Updated!' : 'Added!',
                        text: `Doctor ${isEditMode ? 'updated' : 'added'} successfully.`,
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire('Error!', data.message || 'Failed.', 'error');
                }
            },
            error: function (xhr) {
                console.error('Fetch failed:', xhr.responseText);
                console.error('Status:', xhr.status);
                
                let errorMessage = 'Unexpected error occurred.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).flat().join('\n');
                }
                
                Swal.fire('Error!', errorMessage, 'error');
            }
        });
    });

    $(document).on('click', '.edit-btn', function () {
        const card = $(this).closest('.doctor-card');
        const id = card.data('id');

        // Reset form first
        doctorForm[0].reset();
        $('#imagePreview').html('<i class="fas fa-user-md text-gray-400 text-2xl"></i>');

        $.get(`/admin/doctors/${id}/show`, function (doctor) {
            isEditMode = true;
            modalTitle.text('Edit Doctor');
            submitBtn.text('Update Doctor');

            // Populate form with doctor data
            $('#doctorId').val(doctor.id);
            $('#doctorName').val(doctor.doctor_name);
            $('#doctorEmail').val(doctor.email);
            $('#doctorPhone').val(doctor.phone);
            $('#doctorPhoneVerified').val(doctor.phone_verified ? '1' : '0');
            $('#doctorLocation').val(doctor.location);
            $('#doctorGender').val(doctor.gender);
            $('#doctorSpecialty').val(doctor.specialty_id);
            $('#hospitalName').val(doctor.hospital_name);
            $('#doctorExperience').val(doctor.experience);
            $('#doctorDescription').val(doctor.description);
            $('#doctorStatus').val(doctor.status ? '1' : '0');
            $('#doctorApproved').val(doctor.approved_by_admin || 'no');

            // Handle existing image
            if (doctor.image) {
                $('#imagePreview').html(`<img src="/storage/${doctor.image}" class="w-full h-full object-cover rounded-full">`);
            }

            modal.removeClass('hidden');
        }).fail(function(xhr) {
            console.error('Failed to load doctor data:', xhr.responseText);
            Swal.fire('Error!', 'Failed to load doctor data for editing.', 'error');
        });
    });

    $(document).on('click', '.delete-btn', function () {
        const card = $(this).closest('.doctor-card');
        const id = card.data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "This doctor will be permanently deleted.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e3342f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/doctors/${id}/delete`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        if (data.success) {
                            card.remove();
                            checkNoResults();
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Doctor has been deleted.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire('Error!', 'Failed to delete doctor.', 'error');
                        }
                    }
                });
            }
        });
    });

    $(document).on('click', '.view-btn', function (e) {
        e.stopPropagation();
        const card = $(this).closest('.doctor-card');
        const id = card.data('id');
        
        // Open doctor detail page in new tab
        window.open(`/admin/doctors/${id}/detail`, '_blank');
    });

    function filterDoctors() {
        const searchTerm = $('#searchInput').val().toLowerCase();
        const specialtyFilter = $('#specialtyFilter').val();
        const locationFilter = $('#locationFilter').val().toLowerCase();

        let visibleCount = 0;

        $('.doctor-card').each(function () {
            const card = $(this);
            const name = card.data('name');
            const specialty = card.data('specialty');
            const location = card.data('location');

            const matchesSearch = name.includes(searchTerm);
            const matchesSpecialty = !specialtyFilter || specialty == specialtyFilter;
            const matchesLocation = !locationFilter || location.includes(locationFilter);

            if (matchesSearch && matchesSpecialty && matchesLocation) {
                card.show();
                visibleCount++;
            } else {
                card.hide();
            }
        });

        checkNoResults();
    }

    function checkNoResults() {
        const visibleCards = $('.doctor-card:visible');
        if (visibleCards.length === 0) {
            $('#noResults').removeClass('hidden');
        } else {
            $('#noResults').addClass('hidden');
        }
    }

    $('#searchInput, #specialtyFilter, #locationFilter').on('input change', filterDoctors);

    modal.on('click', function (e) {
        if (e.target === this) {
            modal.addClass('hidden');
        }
    });
});

</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection