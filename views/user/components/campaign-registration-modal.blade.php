<!-- Registration Modal -->
<div id="campaignRegistrationModal" class="fixed inset-0 bg-black bg-opacity-50 z-[9980] hidden">
    <div class="min-h-screen px-4 text-center">
        <!-- This element centers the modal -->
        <span class="inline-block h-screen align-middle" aria-hidden="true">&#8203;</span>
        
        <!-- Modal dialog -->
        <div class="inline-block align-middle bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg w-full">
            <!-- Modal Header -->
            <div class="bg-slate-900 px-6 py-4 border-b border-slate-700">
                <h3 class="text-lg font-semibold text-white" id="campaignRegistrationModalTitle">
                    Register for Campaign
                </h3>
            </div>
            
            <!-- Modal Body -->
            <div class="bg-slate-800 px-6 py-4">
                <form id="campaignRegistrationForm" class="space-y-4">
                                <!-- Name -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-slate-300">
                                        Full Name
                                    </label>
                                    <input type="text" name="name" id="name" required
                                           class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-slate-600 rounded-md bg-slate-700 text-white">
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-slate-300">
                                        Phone Number
                                    </label>
                                    <input type="tel" name="phone" id="phone" required
                                           class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-slate-600 rounded-md bg-slate-700 text-white">
                                </div>

                                <!-- Age -->
                                <div>
                                    <label for="age" class="block text-sm font-medium text-slate-300">
                                        Age
                                    </label>
                                    <input type="number" name="age" id="age" required min="1" max="120"
                                           class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-slate-600 rounded-md bg-slate-700 text-white">
                                </div>

                                <!-- Gender -->
                                <div>
                                    <label for="gender" class="block text-sm font-medium text-slate-300">
                                        Gender
                                    </label>
                                    <select name="gender" id="gender" required
                                            class="mt-1 block w-full py-2 px-3 border border-slate-600 bg-slate-700 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-white">
                                        <option value="">Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>

                                <!-- Address -->
                                <div>
                                    <label for="address" class="block text-sm font-medium text-slate-300">
                                        Address
                                    </label>
                                    <textarea name="address" id="address" rows="3" required
                                              class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-slate-600 rounded-md bg-slate-700 text-white"></textarea>
                                </div>

                                <!-- Health Issues (Optional) -->
                                <div>
                                    <label for="health_issues" class="block text-sm font-medium text-slate-300">
                                        Health Issues (if any)
                                    </label>
                                    <textarea name="health_issues" id="health_issues" rows="2"
                                              class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-slate-600 rounded-md bg-slate-700 text-white"
                                              placeholder="Please mention any existing health conditions..."></textarea>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                </form>
            </div>
            
            <!-- Modal Footer -->
            <div class="bg-slate-900 px-6 py-4 border-t border-slate-700 flex justify-end space-x-3">
                <button type="button" onclick="closeRegistrationModal()"
                        class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-300 rounded-lg font-medium focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 focus:ring-offset-slate-800 transition-colors">
                    Cancel
                </button>
                <button type="button" onclick="submitRegistration()"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-slate-800 transition-colors">
                    Register
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentCampaignId = null;

function showRegistrationModal(campaignId) {
    currentCampaignId = campaignId;
    document.getElementById('campaignRegistrationModal').classList.remove('hidden');
    document.getElementById('campaignRegistrationForm').reset();
}

function closeRegistrationModal() {
    document.getElementById('campaignRegistrationModal').classList.add('hidden');
    currentCampaignId = null;
}

function submitRegistration() {
    if (!currentCampaignId) return;

    const form = document.getElementById('campaignRegistrationForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const formData = new FormData(form);
    fetch(`/user/campaigns/${currentCampaignId}/register`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(Object.fromEntries(formData))
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Registration successful!');
            window.location.reload();
        } else {
            alert(data.message || 'Registration failed. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    })
    .finally(() => {
        closeRegistrationModal();
    });
}

// Close modal when clicking outside
document.getElementById('campaignRegistrationModal').addEventListener('click', function(event) {
    if (event.target === this) {
        closeRegistrationModal();
    }
});
</script>
@endpush
