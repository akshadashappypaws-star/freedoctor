@extends('admin.master')


@section('title', 'WhatsApp Templates & Campaign Linking')

@section('content')
<div class="container px-6 mx-auto grid">
    <div class="flex justify-between items-center mb-6 mt-4">
        <h2 class="text-2xl font-semibold text-gray-700">WhatsApp Templates & Campaign Linking</h2>
        <div class="flex gap-3">
            <button 
                onclick="refreshTemplates()"
                class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors duration-300 flex items-center">
                <i class="fas fa-sync mr-2"></i> Refresh Templates
            </button>
            <button 
                onclick="openLinkModal()"
                class="px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition-colors duration-300 flex items-center">
                <i class="fas fa-link mr-2"></i> Link Template
            </button>
        </div>
    </div>

    <!-- Statistics Row -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Approved Templates</p>
                    <p class="text-2xl font-semibold text-gray-900" id="approvedCount">{{ count($approvedTemplates) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-bullhorn text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Campaigns</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ count($campaigns) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-link text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Links</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ count($templateCampaigns) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-magic text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Dynamic Templates</p>
                    <p class="text-2xl font-semibold text-gray-900" id="dynamicCount">{{ count(array_filter($approvedTemplates, function($t) { return $t['has_dynamic_content'] ?? false; })) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button 
                    onclick="switchTab('approved-templates')"
                    class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm tab-button active"
                    id="approved-templates-tab">
                    <i class="fas fa-check-circle mr-2"></i>Approved Templates
                </button>
                <button 
                    onclick="switchTab('template-links')"
                    class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm tab-button"
                    id="template-links-tab">
                    <i class="fas fa-link mr-2"></i>Template-Campaign Links
                </button>
                <button 
                    onclick="switchTab('campaigns')"
                    class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm tab-button"
                    id="campaigns-tab">
                    <i class="fas fa-bullhorn mr-2"></i>Available Campaigns
                </button>
            </nav>
        </div>

        <div class="p-6">
            <!-- Approved Templates Tab -->
            <div id="approved-templates-content" class="tab-content">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @forelse($approvedTemplates as $template)
                    <div class="bg-white border rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 template-card" data-template-id="{{ $template['id'] }}">
                        <div class="bg-green-500 text-white px-4 py-3 rounded-t-lg flex justify-between items-center">
                            <div>
                                <h3 class="font-semibold">{{ $template['name'] }}</h3>
                                @if($template['has_dynamic_content'] ?? false)
                                <small><i class="fas fa-magic"></i> Dynamic</small>
                                @endif
                            </div>
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">{{ $template['status'] }}</span>
                        </div>
                        <div class="p-4">
                            <div class="mb-3 max-h-32 overflow-y-auto">
                                <pre class="text-sm text-gray-700 whitespace-pre-wrap">{{ $template['content'] ?? 'No content available' }}</pre>
                            </div>
                            
                            @if(!empty($template['dynamic_parameters']))
                            <div class="mb-3">
                                <h6 class="text-sm font-medium text-gray-600 mb-2">Dynamic Parameters:</h6>
                                <div class="flex flex-wrap gap-1">
                                    @foreach($template['dynamic_parameters'] as $param)
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">{{ '{' . $param['position'] . '}' }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <div class="text-xs text-gray-500 mb-3">
                                <div><strong>Category:</strong> {{ $template['category'] ?? 'N/A' }}</div>
                                <div><strong>Language:</strong> {{ $template['language'] ?? 'en' }}</div>
                            </div>
                        </div>
                        <div class="border-t bg-gray-50 px-4 py-3 rounded-b-lg">
                            <div class="flex gap-2">
                                <button onclick="linkTemplate('{{ $template['id'] }}', '{{ $template['name'] }}')" 
                                        class="flex-1 bg-blue-500 text-white px-3 py-2 rounded text-sm hover:bg-blue-600">
                                    <i class="fas fa-link mr-1"></i> Link to Campaign
                                </button>
                                <button onclick="previewTemplate('{{ $template['id'] }}')" 
                                        class="bg-gray-500 text-white px-3 py-2 rounded text-sm hover:bg-gray-600">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-3 text-center py-8">
                        <div class="text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-4"></i>
                            <p class="mb-4">No Approved Templates Found</p>
                            <p class="text-sm mb-4">Please create and approve templates in Meta Business Manager first.</p>
                            <button onclick="refreshTemplates()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                <i class="fas fa-sync mr-2"></i> Refresh Templates
                            </button>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Template-Campaign Links Tab -->
            <div id="template-links-content" class="tab-content hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Template</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campaign</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trigger Event</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Delay</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="templateLinksTable">
                            @forelse($templateCampaigns as $link)
                            <tr data-link-id="{{ $link->id }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $link->whatsappTemplate->name }}</div>
                                        @if($link->whatsappTemplate->parameters)
                                        <div class="text-sm text-gray-500">{{ count($link->whatsappTemplate->parameters) }} dynamic parameters</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $link->campaign->title }}</div>
                                        <div class="text-sm text-gray-500">Dr. {{ $link->campaign->doctor->name }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ ucfirst($link->trigger_event) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $link->delay_minutes }}m</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($link->is_active)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                    @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button onclick="testTemplateCampaign('{{ $link->id }}')" 
                                                class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-play"></i>
                                        </button>
                                        <button onclick="editLink('{{ $link->id }}')" 
                                                class="text-indigo-600 hover:text-indigo-900">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="unlinkTemplate('{{ $link->id }}')" 
                                                class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-unlink"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    <i class="fas fa-link text-2xl mb-2 block"></i>
                                    No template-campaign links found
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Available Campaigns Tab -->
            <div id="campaigns-content" class="tab-content hidden">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @forelse($campaigns as $campaign)
                    <div class="bg-white border rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                        <div class="bg-blue-500 text-white px-4 py-3 rounded-t-lg">
                            <h3 class="font-semibold">{{ $campaign->title }}</h3>
                        </div>
                        <div class="p-4">
                            <div class="text-sm text-gray-600 mb-2">
                                <div><strong>Doctor:</strong> {{ $campaign->doctor->name }}</div>
                                <div><strong>Category:</strong> {{ $campaign->category->name }}</div>
                                <div><strong>Type:</strong> {{ ucfirst($campaign->type) }}</div>
                            </div>
                            <p class="text-sm text-gray-500">{{ Str::limit($campaign->description, 100) }}</p>
                        </div>
                        <div class="border-t bg-gray-50 px-4 py-3 rounded-b-lg">
                            <button onclick="selectCampaignForLinking('{{ $campaign->id }}', '{{ $campaign->title }}')" 
                                    class="w-full bg-blue-500 text-white px-3 py-2 rounded text-sm hover:bg-blue-600">
                                <i class="fas fa-plus mr-1"></i> Link Template
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-3 text-center py-8">
                        <div class="text-gray-500">
                            <i class="fas fa-bullhorn text-4xl mb-4"></i>
                            <p>No Active Campaigns Found</p>
                            <p class="text-sm">Create some campaigns first to link them with templates.</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Link Template Modal -->
<div id="linkModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[9985]">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-screen overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-700">Link Template to Campaign</h3>
                    <button onclick="closeLinkModal()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="linkTemplateForm">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Template</label>
                            <select name="template_id" id="templateSelect" required 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                <option value="">Select Template</option>
                                @foreach($approvedTemplates as $template)
                                <option value="{{ $template['id'] }}" 
                                        data-has-dynamic="{{ $template['has_dynamic_content'] ? 'true' : 'false' }}"
                                        data-parameters="{{ json_encode($template['dynamic_parameters'] ?? []) }}">
                                    {{ $template['name'] }}
                                    @if($template['has_dynamic_content'] ?? false) (Dynamic) @endif
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Campaign</label>
                            <select name="campaign_id" id="campaignSelect" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                <option value="">Select Campaign</option>
                                @foreach($campaigns as $campaign)
                                <option value="{{ $campaign->id }}">{{ $campaign->title }} (Dr. {{ $campaign->doctor->name }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Trigger Event</label>
                            <select name="trigger_event" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                <option value="">Select Event</option>
                                <option value="registration">Patient Registration</option>
                                <option value="reminder">Appointment Reminder</option>
                                <option value="follow_up">Follow-up</option>
                                <option value="confirmation">Booking Confirmation</option>
                                <option value="cancellation">Cancellation Notice</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Delay (minutes)</label>
                            <input type="number" name="delay_minutes" value="0" min="0"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                            <p class="text-xs text-gray-500 mt-1">Time to wait before sending after trigger</p>
                        </div>
                    </div>

                    <!-- Dynamic Parameters Section -->
                    <div id="dynamicParamsSection" class="hidden mb-4">
                        <h4 class="text-lg font-medium text-gray-700 mb-3">Dynamic Parameters Mapping</h4>
                        <div id="dynamicParamsContainer" class="space-y-3"></div>
                    </div>

                    <!-- Preview Section -->
                    <div id="previewSection" class="hidden mb-4">
                        <h4 class="text-lg font-medium text-gray-700 mb-3">Preview</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <pre id="previewContent" class="whitespace-pre-wrap text-sm text-gray-700"></pre>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="generatePreview()" 
                                class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                            <i class="fas fa-eye mr-1"></i> Preview
                        </button>
                        <button type="button" onclick="closeLinkModal()" 
                                class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="button" onclick="saveLinkTemplate()" 
                                class="px-4 py-2 bg-purple-500 text-white rounded-md hover:bg-purple-600">
                            <i class="fas fa-save mr-1"></i> Save Link
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Tab Management
function switchTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab-button').forEach(tab => {
        tab.classList.remove('active', 'border-purple-500', 'text-purple-600');
        tab.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById(`${tabName}-content`).classList.remove('hidden');
    
    // Add active class to selected tab
    const activeTab = document.getElementById(`${tabName}-tab`);
    activeTab.classList.add('active', 'border-purple-500', 'text-purple-600');
    activeTab.classList.remove('border-transparent', 'text-gray-500');
}

// Modal Management
function openLinkModal() {
    document.getElementById('linkModal').classList.remove('hidden');
}

function closeLinkModal() {
    document.getElementById('linkModal').classList.add('hidden');
    document.getElementById('linkTemplateForm').reset();
    hideDynamicParametersSection();
    document.getElementById('previewSection').classList.add('hidden');
}

// Template Selection Handler
function initializeTemplateSelectHandler() {
    const templateSelect = document.getElementById('templateSelect');
    if (templateSelect) {
        templateSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const hasDynamic = selectedOption.dataset.hasDynamic === 'true';
            const parameters = JSON.parse(selectedOption.dataset.parameters || '[]');
            
            if (hasDynamic && parameters.length > 0) {
                showDynamicParametersSection(parameters);
            } else {
                hideDynamicParametersSection();
            }
        });
    }
}

function showDynamicParametersSection(parameters) {
    const container = document.getElementById('dynamicParamsContainer');
    container.innerHTML = '';
    
    parameters.forEach((param) => {
        const paramDiv = document.createElement('div');
        paramDiv.innerHTML = `
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Parameter {${param.position}} (${param.component})
            </label>
            <select name="dynamic_params[${param.position}]" required
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50 param-select">
                <option value="">Select Data Source</option>
                <option value="campaign.title">Campaign Title</option>
                <option value="campaign.description">Campaign Description</option>
                <option value="doctor.name">Doctor Name</option>
                <option value="doctor.specialization">Doctor Specialization</option>
                <option value="doctor.phone">Doctor Phone</option>
                <option value="campaign.location">Campaign Location</option>
                <option value="campaign.price">Campaign Price</option>
                <option value="campaign.duration">Campaign Duration</option>
                <option value="patient.name">Patient Name</option>
                <option value="patient.phone">Patient Phone</option>
                <option value="booking.date">Booking Date</option>
                <option value="booking.time">Booking Time</option>
                <option value="custom">Custom Value</option>
            </select>
            <input type="text" class="w-full mt-2 rounded-md border-gray-300 shadow-sm custom-value hidden" placeholder="Enter custom value">
        `;
        
        const select = paramDiv.querySelector('.param-select');
        const customInput = paramDiv.querySelector('.custom-value');
        
        select.addEventListener('change', function() {
            if (this.value === 'custom') {
                customInput.classList.remove('hidden');
                customInput.required = true;
            } else {
                customInput.classList.add('hidden');
                customInput.required = false;
                customInput.value = '';
            }
        });
        
        container.appendChild(paramDiv);
    });
    
    document.getElementById('dynamicParamsSection').classList.remove('hidden');
}

function hideDynamicParametersSection() {
    document.getElementById('dynamicParamsSection').classList.add('hidden');
    document.getElementById('dynamicParamsContainer').innerHTML = '';
}

// Template Actions
function linkTemplate(templateId, templateName) {
    document.getElementById('templateSelect').value = templateId;
    document.getElementById('templateSelect').dispatchEvent(new Event('change'));
    openLinkModal();
}

function selectCampaignForLinking(campaignId, campaignTitle) {
    document.getElementById('campaignSelect').value = campaignId;
    openLinkModal();
    switchTab('approved-templates');
}

function generatePreview() {
    const templateId = document.getElementById('templateSelect').value;
    const campaignId = document.getElementById('campaignSelect').value;
    
    if (!templateId || !campaignId) {
        alert('Please select both template and campaign');
        return;
    }
    
    // Collect dynamic parameters
    const dynamicParams = {};
    document.querySelectorAll('[name^="dynamic_params"]').forEach(function(select) {
        const name = select.name;
        const match = name.match(/dynamic_params\[(\d+)\]/);
        if (match && select.value) {
            const paramPosition = match[1];
            let value = select.value;
            
            // If custom value, get the custom input
            if (value === 'custom') {
                const customInput = select.parentNode.querySelector('.custom-value');
                value = customInput.value;
            }
            
            dynamicParams[paramPosition] = value;
        }
    });
    
    fetch('/admin/whatsapp/template-campaign-preview', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            template_id: templateId,
            campaign_id: campaignId,
            dynamic_params: dynamicParams
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('previewContent').textContent = data.preview;
            document.getElementById('previewSection').classList.remove('hidden');
        } else {
            alert(data.message || 'Failed to generate preview');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error generating preview');
    });
}

function saveLinkTemplate() {
    const formData = new FormData(document.getElementById('linkTemplateForm'));
    
    // Handle custom values in dynamic parameters
    document.querySelectorAll('[name^="dynamic_params"]').forEach(function(select) {
        if (select.value === 'custom') {
            const customInput = select.parentNode.querySelector('.custom-value');
            formData.set(select.name, customInput.value);
        }
    });
    
    fetch('/admin/whatsapp/link-template-campaign', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Template linked to campaign successfully!');
            closeLinkModal();
            location.reload();
        } else {
            alert(data.message || 'Failed to link template');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error linking template to campaign');
    });
}

function unlinkTemplate(linkId) {
    if (!confirm('Are you sure you want to unlink this template from the campaign?')) {
        return;
    }
    
    fetch(`/admin/whatsapp/unlink-template-campaign/${linkId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Template unlinked successfully!');
            document.querySelector(`tr[data-link-id="${linkId}"]`).remove();
        } else {
            alert(data.message || 'Failed to unlink template');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error unlinking template');
    });
}

function testTemplateCampaign(linkId) {
    const phone = prompt('Enter phone number to test (with country code):');
    if (!phone) return;
    
    fetch('/admin/whatsapp/send-campaign-template', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            link_id: linkId,
            phone: phone
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Test message sent successfully!');
        } else {
            alert(data.message || 'Failed to send test message');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error sending test message');
    });
}

function refreshTemplates() {
    const btn = document.querySelector('button[onclick="refreshTemplates()"]');
    const originalHTML = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Refreshing...';
    btn.disabled = true;
    
    fetch('/admin/whatsapp/refresh-templates', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Templates refreshed successfully!');
            location.reload();
        } else {
            alert(data.message || 'Failed to refresh templates');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error refreshing templates');
    })
    .finally(() => {
        btn.innerHTML = originalHTML;
        btn.disabled = false;
    });
}

function previewTemplate(templateId) {
    // Find template in approved templates
    const templates = @json($approvedTemplates);
    const template = templates.find(t => t.id === templateId);
    
    if (template) {
        const modalContent = `
            <div class="fixed inset-0 bg-black bg-opacity-50 z-50" id="templatePreviewModal">
                <div class="flex items-center justify-center min-h-screen px-4">
                    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-screen overflow-y-auto">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-xl font-semibold text-gray-700">Template Preview: ${template.name}</h3>
                                <button onclick="closePreviewModal()" class="text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        ${template.status}
                                    </span>
                                    <span class="ml-2 text-sm text-gray-600">
                                        <strong>Category:</strong> ${template.category || 'N/A'}
                                    </span>
                                    <span class="ml-2 text-sm text-gray-600">
                                        <strong>Language:</strong> ${template.language || 'en'}
                                    </span>
                                </div>
                                
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="font-medium text-gray-700 mb-2">Template Content</h4>
                                    <pre class="whitespace-pre-wrap text-sm text-gray-700">${template.content || 'No content available'}</pre>
                                </div>
                                
                                ${template.dynamic_parameters && template.dynamic_parameters.length > 0 ? `
                                <div>
                                    <h4 class="font-medium text-gray-700 mb-2">Dynamic Parameters</h4>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="grid grid-cols-3 gap-2 text-sm">
                                            <div class="font-medium">Position</div>
                                            <div class="font-medium">Component</div>
                                            <div class="font-medium">Type</div>
                                            ${template.dynamic_parameters.map(param => `
                                                <div>{${param.position}}</div>
                                                <div>${param.component}</div>
                                                <div>${param.type}</div>
                                            `).join('')}
                                        </div>
                                    </div>
                                </div>
                                ` : ''}
                            </div>
                            
                            <div class="mt-6 flex justify-end">
                                <button onclick="closePreviewModal()" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modalContent);
    }
}

function closePreviewModal() {
    const modal = document.getElementById('templatePreviewModal');
    if (modal) {
        modal.remove();
    }
}

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    console.log('WhatsApp Templates page loaded');
    
    // Set default active tab
    switchTab('approved-templates');
    
    // Initialize template select handler
    initializeTemplateSelectHandler();
    
    // Verify functions are available
    console.log('Functions available:', {
        refreshTemplates: typeof refreshTemplates,
        openLinkModal: typeof openLinkModal,
        closeLinkModal: typeof closeLinkModal,
        linkTemplate: typeof linkTemplate,
        previewTemplate: typeof previewTemplate
    });
});

// Ensure functions are globally available
window.refreshTemplates = refreshTemplates;
window.openLinkModal = openLinkModal;
window.closeLinkModal = closeLinkModal;
window.linkTemplate = linkTemplate;
window.previewTemplate = previewTemplate;
window.switchTab = switchTab;
</script>
@endsection
