@extends('../admin.dashboard')

@section('content')
<div style="padding:15px" class="p-6 rounded shadow bg-slate-800 border border-slate-700">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-white">Campaigns Management</h1>
        <button id="openModalBtn"
            class="bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-3 rounded-lg hover:from-green-700 hover:to-green-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
            <i class="fas fa-plus mr-2"></i>Add Campaign
        </button>
    </div>

    <!-- Search and Filters -->
    <div class="bg-slate-700 border border-slate-600 rounded-lg shadow-md p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Search by Title</label>
                <input type="text" id="searchTitle" placeholder="Search campaigns..."
                    class="w-full px-4 py-2 bg-slate-600 border border-slate-500 text-white placeholder-gray-400 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Filter by Location</label>
                <input type="text" id="searchLocation" placeholder="Filter by location..."
                    class="w-full px-4 py-2 bg-slate-600 border border-slate-500 text-white placeholder-gray-400 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Filter by Specialty</label>
                <select id="specialtyFilter" class="w-full px-4 py-2 bg-slate-600 border border-slate-500 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">All Specialties</option>
                    @if(isset($specialties) && $specialties)
                    @foreach($specialties as $specialty)
                    <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                    @endforeach
                    @endif
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Filter by Type</label>
                <select id="typeFilter" class="w-full px-4 py-2 bg-slate-600 border border-slate-500 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">All Types</option>
                    <option value="free">Free</option>
                    <option value="paid">Paid</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Campaigns Grid -->
<div id="campaignGrid"
     class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-3 gap-6 transition-all duration-300">
    @foreach($campaigns as $campaign)
    <div class="bg-slate-800/40 rounded-xl p-6 border border-slate-700 hover:border-blue-500/50 transition-all duration-300 hover:transform hover:scale-105"
         data-id="{{ $campaign->id }}"
         data-title="{{ strtolower($campaign->title) }}"
         data-location="{{ strtolower($campaign->location) }}"
         data-type="{{ $campaign->camp_type }}"
         data-specializations="{{ implode(',', is_array($campaign->specializations) ? $campaign->specializations : json_decode($campaign->specializations ?: '[]', true)) }}">

        <!-- Campaign Thumbnail -->
        <div class="relative h-48 w-full bg-slate-100 dark:bg-slate-700 overflow-hidden rounded-lg mb-4">
            @if($campaign->thumbnail)
                <img src="{{ asset('storage/'.$campaign->thumbnail) }}" alt="{{ $campaign->title }}" class="object-cover w-full h-full">
            @else
                <div class="flex items-center justify-center h-full">
                    <i class="fas fa-hospital-alt text-4xl text-slate-400 dark:text-slate-500"></i>
                </div>
            @endif

            <!-- Type Badge -->
            <div class="absolute top-3 left-3">
                <span class="badge rounded-pill {{ $campaign->camp_type === 'paid' ? 'bg-primary text-white' : 'bg-success text-white' }}">
                    <i class="fas {{ $campaign->camp_type === 'paid' ? 'fa-credit-card' : 'fa-heart' }} me-1"></i>
                    {{ ucfirst($campaign->camp_type) }}
                </span>
            </div>

            <!-- Delete Button -->
            <button class="absolute top-3 right-3 btn btn-danger btn-sm rounded-circle shadow delete-btn">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>

        <!-- Card Content -->
        <div class="text-white space-y-3">
            <!-- Title and Description -->
            <div>
                <h3 class="h5 fw-semibold truncate">{{ $campaign->title }}</h3>
                <p class="text-sm text-slate-300 line-clamp-2">{{ $campaign->description }}</p>
            </div>

            <!-- Location and Date -->
            <div class="d-flex justify-content-between small">
                <div><i class="fas fa-map-marker-alt me-1"></i>{{ $campaign->location }}</div>
                <div><i class="fas fa-calendar me-1"></i>{{ \Carbon\Carbon::parse($campaign->start_date)->format('M d, Y') }}</div>
            </div>

            <!-- Doctor Info -->
            <div class="d-flex align-items-center bg-slate-700/50 p-2 rounded">
                <div class="me-3 d-flex align-items-center justify-content-center bg-dark rounded-circle" style="width: 40px; height: 40px;">
                    <i class="fas fa-user-md text-white"></i>
                </div>
                <div>
                    <div class="fw-medium">{{ $campaign->doctor->doctor_name ?? 'Not Assigned' }}</div>
                    <small class="">{{ implode(', ', $campaign->specialization_names) }}</small>
                </div>
            </div>

            <!-- Status -->
            <div class="d-flex justify-content-between align-items-center p-2 rounded {{ $campaign->approval_status === 'approved' ? 'bg-success bg-opacity-25' : 'bg-warning bg-opacity-25' }}">
                <span class="fw-bold {{ $campaign->approval_status === 'approved' ? 'text-success' : 'text-warning' }}">
                    <i class="fas {{ $campaign->approval_status === 'approved' ? 'fa-check-circle' : 'fa-clock' }} me-1"></i>
                    {{ ucfirst($campaign->approval_status) }}
                </span>
                <span class="small ">
                    <i class="fas fa-users me-1"></i>
                    {{ $campaign->expected_patients }} Expected
                </span>
            </div>

            <!-- Additional Info -->
            @if($campaign->budget)
            <div class="d-flex justify-content-between align-items-center p-2 rounded bg-blue-500 bg-opacity-25">
                <span class="fw-bold text-blue-300">
                    <i class="fas fa-rupee-sign me-1"></i>
                    Budget: ₹{{ number_format($campaign->budget, 2) }}
                </span>
            </div>
            @endif

            @if($campaign->status)
            <div class="d-flex justify-content-between align-items-center p-2 rounded bg-slate-700/50">
                <span class="small text-slate-300">
                    <i class="fas fa-info-circle me-1"></i>
                    Status: {{ ucfirst($campaign->status) }}
                </span>
            </div>
            @endif

            @if($campaign->target_audience)
            <div class="p-2 rounded bg-purple-500 bg-opacity-25">
                <span class="small text-purple-200">
                    <i class="fas fa-bullseye me-1"></i>
                    Target: {{ Str::limit($campaign->target_audience, 50) }}
                </span>
            </div>
            @endif

            <!-- Buttons -->
            <div class="d-flex justify-content-between gap-2 pt-2">
    <a href="{{ route('campaigns.show', $campaign->id) }}" class="btn btn-outline-light btn-sm flex-grow-1">
        <i class="fas fa-eye me-1"></i> View
    </a>
</div>

        </div>
    </div>
    @endforeach
</div>




    <!-- No Results Message -->
    <div id="noResults" class="hidden text-center py-12">
        <div class="text-gray-500">
            <i class="fas fa-search text-6xl mb-4"></i>
            <h3 class="text-xl font-medium mb-2">No campaigns found</h3>
            <p>Try adjusting your search criteria</p>
        </div>
    </div>
</div>

<!-- Campaign Modal -->
<div id="campaignModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[9999]">
    <div class="bg-slate-800 p-8 rounded-lg shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 id="modalTitle" class="text-2xl font-bold text-white">Add New Campaign</h2>
            <button id="cancelModalBtn" class="text-gray-400 hover:text-white transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form id="campaignForm" class="space-y-6">
            @csrf
            <input type="hidden" id="campaignId" name="id">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="campaignTitle" class="block text-sm font-medium text-gray-300 mb-2">Campaign Title</label>
                    <input type="text" id="campaignTitle" name="title" required
                        class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div>
                    <label for="campaignLocation" class="block text-sm font-medium text-gray-300 mb-2">Location</label>
                    <input type="text" id="campaignLocation" name="location" required
                        class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div>
                    <label for="campaignStartDate" class="block text-sm font-medium text-gray-300 mb-2">Start Date</label>
                    <input type="date" id="campaignStartDate" name="start_date" required
                        class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div>
                    <label for="campaignEndDate" class="block text-sm font-medium text-gray-300 mb-2">End Date</label>
                    <input type="date" id="campaignEndDate" name="end_date" required
                        class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div>
                    <label for="campaignType" class="block text-sm font-medium text-gray-300 mb-2">Campaign Type</label>
                    <select id="campaignType" name="type" required
                        class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Select Type</option>
                        <option value="free">Free Camp</option>
                        <option value="paid">Paid Camp</option>
                    </select>
                </div>

                <div class="hidden">
                    <label for="campaignAmount" class="block text-sm font-medium text-gray-300 mb-2">Amount (₹)</label>
                    <input type="number" id="campaignAmount" name="amount" min="0" step="0.01"
                        class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div>
                    <label for="expectedPatients" class="block text-sm font-medium text-gray-300 mb-2">Expected Patients</label>
                    <input type="number" id="expectedPatients" name="expected_patients" min="1" required
                        class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div>
                    <label for="doctorId" class="block text-sm font-medium text-gray-300 mb-2">Assign Doctor</label>
                    <select id="doctorId" name="doctor_id" required
                        class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Select Doctor</option>
                        @foreach($doctors ?? [] as $doctor)
                            <option value="{{ $doctor->id }}">{{ $doctor->doctor_name }} - {{ $doctor->specialty_name ?? 'General' }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="campaignDescription" class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                <textarea id="campaignDescription" name="description" rows="4" required
                    class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"></textarea>
            </div>

            <!-- New Fields -->
            <div>
                <label for="campaignBudget" class="block text-sm font-medium text-gray-300 mb-2">Budget (₹)</label>
                <input type="number" id="campaignBudget" name="budget" min="0" step="0.01"
                    class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>

            <div>
                <label for="targetAudience" class="block text-sm font-medium text-gray-300 mb-2">Target Audience</label>
                <textarea id="targetAudience" name="target_audience" rows="2"
                    class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    placeholder="Describe the target audience for this campaign"></textarea>
            </div>

            <div>
                <label for="campaignRequirements" class="block text-sm font-medium text-gray-300 mb-2">Requirements</label>
                <textarea id="campaignRequirements" name="requirements" rows="3"
                    class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    placeholder="List any specific requirements for this campaign"></textarea>
            </div>

            <div>
                <label for="campaignStatus" class="block text-sm font-medium text-gray-300 mb-2">Status</label>
                <select id="campaignStatus" name="status"
                    class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="draft">Draft</option>
                    <option value="active" selected>Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <div class="flex justify-end gap-4">
                <button type="button" id="cancelModalBtn" 
                    class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    Cancel
                </button>
                <button type="submit" id="submitBtn"
                    class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    Add Campaign
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Patient Registration Modal -->
<div id="patientRegistrationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[9998]">
    <div class="bg-slate-800 p-8 rounded-lg shadow-2xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-content-between align-items-center mb-4">
            <h2 class="text-2xl font-bold text-white">Register for Campaign</h2>
            <button id="cancelPatientModalBtn" class="text-gray-400 hover:text-white transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form id="patientRegistrationForm" class="space-y-4">
            @csrf
            <input type="hidden" id="modalCampaignId" name="campaign_id">

            <div>
                <label for="patientName" class="block text-sm font-medium text-gray-300 mb-2">Full Name</label>
                <input type="text" id="patientName" name="name" required
                    class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label for="patientPhone" class="block text-sm font-medium text-gray-300 mb-2">Phone Number</label>
                <input type="tel" id="patientPhone" name="phone" required
                    class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label for="patientAge" class="block text-sm font-medium text-gray-300 mb-2">Age</label>
                <input type="number" id="patientAge" name="age" min="1" max="120" required
                    class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label for="patientGender" class="block text-sm font-medium text-gray-300 mb-2">Gender</label>
                <select id="patientGender" name="gender" required
                    class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:ring-2 focus:ring-green-500">
                    <option value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="d-flex justify-content-end gap-3">
                <button type="button" id="cancelPatientModalBtn" 
                    class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    Cancel
                </button>
                <button type="button" id="proceedToPaymentBtn"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    Register
                </button>
            </div>

            <!-- Payment Section (Hidden by default) -->
            <div id="paymentModalSection" class="hidden mt-4 pt-4 border-t border-slate-600">
                <h3 class="text-lg font-medium text-white mb-3">Payment Details</h3>
                <div class="bg-slate-700 p-4 rounded-lg mb-4">
                    <div class="d-flex justify-content-between">
                        <span class="text-gray-300">Campaign Fee:</span>
                        <span class="text-white font-bold" id="campaignFeeDisplay">₹0</span>
                    </div>
                </div>
                <button type="button" id="proceedToRazorpayBtn"
                    class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-credit-card me-2"></i>Pay Now
                </button>
            </div>
        </form>
    </div>
</div>







<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        let isEditMode = false;

        const modal = $('#campaignModal');
        const modalTitle = $('#modalTitle');
        const submitBtn = $('#submitBtn');
        const campaignForm = $('#campaignForm');

        $('#openModalBtn').on('click', function() {
            isEditMode = false;
            modalTitle.text('Add New Campaign');
            submitBtn.text('Add Campaign');
            
            // Safely reset form if it exists
            if (campaignForm.length > 0 && campaignForm[0].reset) {
                campaignForm[0].reset();
            }
            
            $('#campaignId').val('');
            modal.removeClass('hidden').addClass('flex');
            modal.css('display', 'flex');
        });

        $('#cancelModalBtn').on('click', function() {
            modal.addClass('hidden').removeClass('flex');
            modal.css('display', 'none');
        });

        campaignForm.on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            const url = isEditMode ?
                `/admin/campaigns/${$('#campaignId').val()}` :
                '{{ route("campaigns.store") }}';

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
                success: function(data) {
                    if (data.success) {
                        modal.addClass('hidden');
                        location.reload();
                        Swal.fire({
                            icon: 'success',
                            title: isEditMode ? 'Updated!' : 'Added!',
                            text: `Campaign ${isEditMode ? 'updated' : 'added'} successfully.`,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire('Error!', data.message || 'Failed.', 'error');
                    }
                },
                error: function(xhr) {
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

        $(document).on('click', '.edit-btn', function(e) {
            e.stopPropagation();
            const card = $(this).closest('.campaign-card');
            const id = card.data('id');

            // Reset form first
            if (campaignForm.length > 0 && campaignForm[0].reset) {
                campaignForm[0].reset();
            }

            $.get(`/admin/campaigns/${id}/show`, function(campaign) {
                isEditMode = true;
                modalTitle.text('Edit Campaign');
                submitBtn.text('Update Campaign');

                // Populate form with campaign data
                $('#campaignId').val(campaign.id);
                $('#campaignTitle').val(campaign.title);
                $('#campaignDescription').val(campaign.description);
                $('#campaignLocation').val(campaign.location);
                $('#campaignStartDate').val(campaign.start_date);
                $('#campaignEndDate').val(campaign.end_date);
                $('#campaignTimings').val(campaign.timings);
                $('#campaignType').val(campaign.camp_type);
                $('#campaignAmount').val(campaign.amount);
                $('#campaignDoctor').val(campaign.doctor_id);
                $('#campaignContactNumber').val(campaign.contact_number);
                $('#campaignExpectedPatients').val(campaign.expected_patients);
                $('#campaignService').val(campaign.service_in_camp);
                $('#campaignApprovalStatus').val(campaign.approval_status || 'pending');

                // Handle specializations
                if (campaign.specializations) {
                    $('#campaignSpecializations').val(campaign.specializations);
                }

                modal.removeClass('hidden');
            }).fail(function(xhr) {
                console.error('Failed to load campaign data:', xhr.responseText);
                Swal.fire('Error!', 'Failed to load campaign data for editing.', 'error');
            });
        });

        $(document).on('click', '.delete-btn', function(e) {
            e.stopPropagation();
            const card = $(this).closest('.campaign-card');
            const id = card.data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "This campaign will be permanently deleted.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/campaigns/${id}`,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            if (data.success) {
                                card.remove();
                                checkNoResults();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: 'Campaign has been deleted.',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire('Error!', 'Failed to delete campaign.', 'error');
                            }
                        }
                    });
                }
            });
        });

        $(document).on('click', '.view-btn', function(e) {
            e.stopPropagation();
            const card = $(this).closest('.campaign-card');
            const id = card.data('id');

            // Redirect to campaign detail page or open detailed modal
            window.open(`/admin/campaigns/${id}`, '_blank');
        });

        // Sponsor button functionality
        $(document).on('click', '.sponsor-btn', function(e) {
            e.stopPropagation();
            e.preventDefault();
            const campaignId = $(this).closest('.campaign-card').data('id');

            // Open sponsor page in a new tab
            window.open(`/admin/campaigns/${campaignId}/sponsors`, '_blank');
        });

        $(document).on('click', '.register-patient-btn', function(e) {
            e.stopPropagation();
            const card = $(this).closest('.campaign-card');
            const campaignId = card.data('id');
            const campaignTitle = card.find('h3').text();
            const campaignLocation = card.find('.text-green-600').text();

            // Reset form and clear previous data
            const regForm = $('#patientRegistrationForm');
            if (regForm.length > 0 && regForm[0].reset) {
                regForm[0].reset();
            }
            $('#selectedCampaignId').val(campaignId);
            $('#campaignDisplayName').text(campaignTitle);
            $('#campaignDisplayLocation').text(campaignLocation);

            // Get campaign details including registration fee
            $.get(`/admin/campaigns/${campaignId}/details`, function(campaign) {
                const registrationFee = parseFloat(campaign.registration_payment) || 0;

                if (registrationFee > 0) {
                    // Paid Campaign
                    const gstAmount = registrationFee * 0.18; // 18% GST
                    const totalAmount = registrationFee + gstAmount;

                    // Update fee display
                    $('#registrationAmount').text(`₹${registrationFee.toFixed(2)}`);
                    $('#gstAmount').text(`₹${gstAmount.toFixed(2)}`);
                    $('#totalAmount').text(`₹${totalAmount.toFixed(2)}`);
                    $('#campaignFeeInfo').html(`<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    <i class="fas fa-credit-card mr-1"></i>Registration Fee: ₹${registrationFee}
                </span>`);

                    // Show/hide relevant sections
                    $('#registrationFeeSection').removeClass('hidden');
                    $('#freeRegistrationSection').addClass('hidden');
                    $('#proceedToPaymentBtn').removeClass('hidden');
                    $('#submitFreeRegistrationBtn').addClass('hidden');
                } else {
                    // Free Campaign
                    $('#campaignFeeInfo').html(`<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <i class="fas fa-check-circle mr-1"></i>Free Registration
                </span>`);

                    // Show/hide relevant sections
                    $('#registrationFeeSection').addClass('hidden');
                    $('#freeRegistrationSection').removeClass('hidden');
                    $('#proceedToPaymentBtn').addClass('hidden');
                    $('#submitFreeRegistrationBtn').removeClass('hidden');
                }

                // Reset payment modal
                $('#paymentModalSection').addClass('hidden');

                $('#patientRegistrationModal').removeClass('hidden');
            }).fail(function() {
                // Fallback if campaign details endpoint doesn't exist
                $('#selectedCampaignId').val(campaignId);
                $('#selectedCampaignName').val(campaignTitle);
                $('#campaignDisplayName').text(campaignTitle);
                $('#campaignDisplayLocation').text(campaignLocation);

                const regForm = $('#patientRegistrationForm');
                if (regForm.length > 0 && regForm[0].reset) {
                    regForm[0].reset();
                }
                $('#selectedCampaignId').val(campaignId);

                // Default to free registration
                $('#registrationFeeSection').addClass('hidden');
                $('#freeRegistrationSection').removeClass('hidden');
                $('#submitPatientBtn').removeClass('hidden');
                $('#proceedToPaymentBtn').addClass('hidden');
                $('#paymentModalSection').addClass('hidden');

                $('#patientRegistrationModal').removeClass('hidden');
            });
        });

        $('#cancelPatientModalBtn').on('click', function() {
            $('#patientRegistrationModal').addClass('hidden');
        });

        // Proceed to Payment Button
        $('#proceedToPaymentBtn').on('click', function() {
            const patientName = $('#patientName').val();
            const patientEmail = $('#patientEmail').val();
            const patientPhone = $('#patientPhone').val();
            const patientAddress = $('#patientAddress').val();

            if (!patientName || !patientEmail || !patientPhone || !patientAddress) {
                Swal.fire('Error!', 'Please fill in all required patient details before proceeding to payment.', 'error');
                return;
            }

            // Hide form buttons and show payment section
            $('#proceedToPaymentBtn').addClass('hidden');
            $('#submitPatientBtn').addClass('hidden');
            $('#paymentModalSection').removeClass('hidden');
        });

        // Back to Form Button
        $('#backToFormBtn').on('click', function() {
            // Show form buttons and hide payment section
            $('#proceedToPaymentBtn').removeClass('hidden');
            $('#submitPatientBtn').addClass('hidden');
            $('#paymentModalSection').addClass('hidden');
        });

        $('#patientRegistrationForm').on('submit', function(e) {
            e.preventDefault();

            // Only allow free registrations through normal submit
            const registrationFee = parseFloat($('#registrationAmount').text().replace('₹', '')) || 0;
            if (registrationFee > 0) {
                Swal.fire('Error!', 'This is a paid campaign. Please use the payment option.', 'error');
                return;
            }

            submitPatientRegistration(new FormData(this), 'free');
        });

        // Payment button handlers
        $('#payWithRazorpayBtn').on('click', function() {
            const registrationAmount = parseFloat($('#registrationAmount').text().replace('₹', ''));
            const campaignName = $('#selectedCampaignName').val();
            const patientName = $('#patientName').val();
            const patientEmail = $('#patientEmail').val();
            const patientPhone = $('#patientPhone').val();

            if (!patientName || !patientEmail || !patientPhone) {
                Swal.fire('Error!', 'Please fill in all required patient details before proceeding to payment.', 'error');
                return;
            }

            // Initialize Razorpay payment
            const options = {
                key: 'rzp_test_your_key_here', // Replace with actual Razorpay key
                amount: registrationAmount * 100, // Amount in paise
                currency: 'INR',
                name: 'FreeDoctor Medical Camps',
                description: `Registration fee for ${campaignName}`,
                prefill: {
                    name: patientName,
                    email: patientEmail,
                    contact: patientPhone
                },
                theme: {
                    color: '#3B82F6'
                },
                handler: function(response) {
                    // Payment successful, submit form with payment details
                    const formData = new FormData($('#patientRegistrationForm')[0]);
                    formData.append('payment_id', response.razorpay_payment_id);
                    formData.append('payment_status', 'paid');
                    formData.append('payment_amount', registrationAmount);

                    submitPatientRegistration(formData, 'paid');
                },
                modal: {
                    ondismiss: function() {
                        console.log('Payment cancelled');
                    }
                }
            };

            const rzp = new Razorpay(options);
            rzp.open();
        });

        function submitPatientRegistration(formData, paymentType = 'free') {
            $.ajax({
                url: '/admin/patient-registrations',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(data) {
                    if (data.success) {
                        $('#patientRegistrationModal').addClass('hidden');
                        // Reset payment modal
                        $('#paymentModalSection').addClass('hidden');
                        $('#proceedToPaymentBtn').removeClass('hidden');

                        let message = paymentType === 'paid' ?
                            'Payment successful! Patient registered.' :
                            'Patient registered successfully!';

                        Swal.fire({
                            icon: 'success',
                            title: 'Registration Successful!',
                            text: message,
                            timer: 3000,
                            showConfirmButton: false
                        });
                        const regForm = $('#patientRegistrationForm');
                        if (regForm.length > 0 && regForm[0].reset) {
                            regForm[0].reset();
                        }
                    } else {
                        Swal.fire('Error!', data.message || 'Registration failed.', 'error');
                    }
                },
                error: function(xhr) {
                    console.error('Registration failed:', xhr.responseText);

                    let errorMessage = 'Registration failed.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMessage = Object.values(xhr.responseJSON.errors).flat().join('\n');
                    }

                    Swal.fire('Error!', errorMessage, 'error');
                }
            });
        }

        function filterCampaigns() {
            const titleSearch = $('#searchTitle').val().toLowerCase();
            const locationSearch = $('#searchLocation').val().toLowerCase();
            const specialtyFilter = $('#specialtyFilter').val();
            const typeFilter = $('#typeFilter').val();

            let visibleCount = 0;

            $('.campaign-card').each(function() {
                const card = $(this);
                const title = card.data('title');
                const location = card.data('location');
                const type = card.data('type');
                const specializations = card.data('specializations').toString();

                const matchesTitle = title.includes(titleSearch);
                const matchesLocation = location.includes(locationSearch);
                const matchesSpecialty = !specialtyFilter || specializations.includes(specialtyFilter);
                const matchesType = !typeFilter || type === typeFilter;

                if (matchesTitle && matchesLocation && matchesSpecialty && matchesType) {
                    card.show();
                    visibleCount++;
                } else {
                    card.hide();
                }
            });

            checkNoResults();
        }

        function checkNoResults() {
            const visibleCards = $('.campaign-card:visible');
            if (visibleCards.length === 0) {
                $('#noResults').removeClass('hidden');
            } else {
                $('#noResults').addClass('hidden');
            }
        }

        $('#searchTitle, #searchLocation, #specialtyFilter, #typeFilter').on('input change', filterCampaigns);

        modal.on('click', function(e) {
            if (e.target === this) {
                modal.addClass('hidden').removeClass('flex');
                modal.css('display', 'none');
            }
        });

        $('#patientRegistrationModal').on('click', function(e) {
            if (e.target === this) {
                $('#patientRegistrationModal').addClass('hidden');
            }
        });

        // Show/hide amount field based on camp type
        $('#campaignType').on('change', function() {
            const amountField = $('#campaignAmount').closest('div');
            if ($(this).val() === 'paid') {
                amountField.show();
                $('#campaignAmount').attr('required', true);
            } else {
                amountField.hide();
                $('#campaignAmount').removeAttr('required').val('');
            }
        });

        // Sponsor button handler
        $(document).on('click', '.sponsor-btn', function() {
            const campaignId = $(this).data('campaign-id');

            // Open sponsor form in new tab/window
            window.open(`/sponsor/form/${campaignId}`, '_blank');
        });
    });
</script>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection