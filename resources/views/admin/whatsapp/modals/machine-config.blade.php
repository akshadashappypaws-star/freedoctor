<!-- Machine Configuration Modal -->
<div class="modal fade" id="machineConfigModal" tabindex="-1" aria-labelledby="machineConfigModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="machineConfigModalLabel">
                    <i class="fas fa-cogs me-2"></i>Machine Configuration
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="machineConfigForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="machineName" class="form-label">Machine Name</label>
                                <input type="text" class="form-control" id="machineName" placeholder="Enter machine name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="machineType" class="form-label">Machine Type</label>
                                <select class="form-select" id="machineType" required>
                                    <option value="">Select Type</option>
                                    <option value="ai">AI Machine</option>
                                    <option value="function">Function Machine</option>
                                    <option value="datatable">DataTable Machine</option>
                                    <option value="template">Template Machine</option>
                                    <option value="visualization">Visualization Machine</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="machineDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="machineDescription" rows="3" placeholder="Enter machine description"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="machineStatus" class="form-label">Status</label>
                                <select class="form-select" id="machineStatus">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="maintenance">Maintenance</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="machinePriority" class="form-label">Priority</label>
                                <select class="form-select" id="machinePriority">
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Type-specific configurations -->
                    <div id="aiMachineConfig" class="machine-config-section" style="display: none;">
                        <h6 class="mb-3"><i class="fas fa-brain me-2"></i>AI Configuration</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="aiModel" class="form-label">AI Model</label>
                                    <select class="form-select" id="aiModel">
                                        <option value="gpt-3.5-turbo">GPT-3.5 Turbo</option>
                                        <option value="gpt-4">GPT-4</option>
                                        <option value="claude">Claude</option>
                                        <option value="gemini">Gemini</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="aiTemperature" class="form-label">Temperature</label>
                                    <input type="range" class="form-range" id="aiTemperature" min="0" max="1" step="0.1" value="0.7">
                                    <div class="d-flex justify-content-between">
                                        <small>Conservative</small>
                                        <small>Creative</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="aiPrompt" class="form-label">System Prompt</label>
                            <textarea class="form-control" id="aiPrompt" rows="4" placeholder="Enter system prompt for AI responses"></textarea>
                        </div>
                    </div>
                    
                    <div id="functionMachineConfig" class="machine-config-section" style="display: none;">
                        <h6 class="mb-3"><i class="fas fa-code me-2"></i>Function Configuration</h6>
                        <div class="mb-3">
                            <label for="functionCode" class="form-label">Function Code</label>
                            <textarea class="form-control" id="functionCode" rows="6" placeholder="Enter your JavaScript function code"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="functionTimeout" class="form-label">Timeout (seconds)</label>
                                    <input type="number" class="form-control" id="functionTimeout" value="30" min="1" max="300">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="functionRetries" class="form-label">Max Retries</label>
                                    <input type="number" class="form-control" id="functionRetries" value="3" min="0" max="10">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="datatableMachineConfig" class="machine-config-section" style="display: none;">
                        <h6 class="mb-3"><i class="fas fa-table me-2"></i>DataTable Configuration</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="dataSource" class="form-label">Data Source</label>
                                    <select class="form-select" id="dataSource">
                                        <option value="database">Database</option>
                                        <option value="api">API Endpoint</option>
                                        <option value="csv">CSV File</option>
                                        <option value="json">JSON Data</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="dataTable" class="form-label">Table/Collection</label>
                                    <input type="text" class="form-control" id="dataTable" placeholder="Enter table name">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="dataQuery" class="form-label">Query/Filter</label>
                            <textarea class="form-control" id="dataQuery" rows="3" placeholder="Enter SQL query or filter conditions"></textarea>
                        </div>
                    </div>
                    
                    <div id="templateMachineConfig" class="machine-config-section" style="display: none;">
                        <h6 class="mb-3"><i class="fas fa-file-code me-2"></i>Template Configuration</h6>
                        <div class="mb-3">
                            <label for="templateContent" class="form-label">Template Content</label>
                            <textarea class="form-control" id="templateContent" rows="6" placeholder="Enter message template with variables like {{name}}, {{date}}, etc."></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="templateLanguage" class="form-label">Language</label>
                                    <select class="form-select" id="templateLanguage">
                                        <option value="en">English</option>
                                        <option value="es">Spanish</option>
                                        <option value="fr">French</option>
                                        <option value="de">German</option>
                                        <option value="hi">Hindi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="templateCategory" class="form-label">Category</label>
                                    <select class="form-select" id="templateCategory">
                                        <option value="marketing">Marketing</option>
                                        <option value="transactional">Transactional</option>
                                        <option value="otp">OTP</option>
                                        <option value="authentication">Authentication</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="visualizationMachineConfig" class="machine-config-section" style="display: none;">
                        <h6 class="mb-3"><i class="fas fa-chart-pie me-2"></i>Visualization Configuration</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="chartType" class="form-label">Chart Type</label>
                                    <select class="form-select" id="chartType">
                                        <option value="bar">Bar Chart</option>
                                        <option value="line">Line Chart</option>
                                        <option value="pie">Pie Chart</option>
                                        <option value="doughnut">Doughnut Chart</option>
                                        <option value="radar">Radar Chart</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="chartDataSource" class="form-label">Data Source</label>
                                    <input type="text" class="form-control" id="chartDataSource" placeholder="API endpoint or data query">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="chartConfig" class="form-label">Chart Configuration (JSON)</label>
                            <textarea class="form-control" id="chartConfig" rows="4" placeholder="Enter Chart.js configuration"></textarea>
                        </div>
                    </div>
                    
                    <!-- Advanced Settings -->
                    <div class="border-top pt-3 mt-4">
                        <h6 class="mb-3"><i class="fas fa-sliders-h me-2"></i>Advanced Settings</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="enableLogging" checked>
                                    <label class="form-check-label" for="enableLogging">
                                        Enable Logging
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="enableErrorHandling" checked>
                                    <label class="form-check-label" for="enableErrorHandling">
                                        Error Handling
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="enableCaching">
                                    <label class="form-check-label" for="enableCaching">
                                        Enable Caching
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveMachineConfig()">
                    <i class="fas fa-save me-2"></i>Save Machine
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Machine configuration modal functions
document.addEventListener('DOMContentLoaded', function() {
    // Show/hide configuration sections based on machine type
    $('#machineType').on('change', function() {
        const selectedType = $(this).val();
        
        // Hide all config sections
        $('.machine-config-section').hide();
        
        // Show relevant config section
        if (selectedType) {
            $(`#${selectedType}MachineConfig`).show();
        }
    });
});

function saveMachineConfig() {
    const formData = {
        name: $('#machineName').val(),
        type: $('#machineType').val(),
        description: $('#machineDescription').val(),
        status: $('#machineStatus').val(),
        priority: $('#machinePriority').val(),
        config: getMachineTypeConfig()
    };
    
    // Validate required fields
    if (!formData.name || !formData.type) {
        showAlert('warning', 'Validation Error', 'Please fill in all required fields.');
        return;
    }
    
    $.ajax({
        url: '/api/automation/machines',
        method: 'POST',
        data: JSON.stringify(formData),
        contentType: 'application/json',
        success: function(response) {
            if (response.success) {
                $('#machineConfigModal').modal('hide');
                resetMachineConfigForm();
                loadMachines(); // Reload machines list
                showAlert('success', 'Machine Created', 'Machine has been created successfully.');
            }
        },
        error: function() {
            showAlert('error', 'Save Failed', 'Failed to save machine configuration.');
        }
    });
}

function getMachineTypeConfig() {
    const machineType = $('#machineType').val();
    
    switch(machineType) {
        case 'ai':
            return {
                model: $('#aiModel').val(),
                temperature: parseFloat($('#aiTemperature').val()),
                prompt: $('#aiPrompt').val()
            };
        case 'function':
            return {
                code: $('#functionCode').val(),
                timeout: parseInt($('#functionTimeout').val()),
                retries: parseInt($('#functionRetries').val())
            };
        case 'datatable':
            return {
                source: $('#dataSource').val(),
                table: $('#dataTable').val(),
                query: $('#dataQuery').val()
            };
        case 'template':
            return {
                content: $('#templateContent').val(),
                language: $('#templateLanguage').val(),
                category: $('#templateCategory').val()
            };
        case 'visualization':
            return {
                type: $('#chartType').val(),
                dataSource: $('#chartDataSource').val(),
                config: $('#chartConfig').val()
            };
        default:
            return {};
    }
}

function resetMachineConfigForm() {
    $('#machineConfigForm')[0].reset();
    $('.machine-config-section').hide();
}

// Export functions
window.saveMachineConfig = saveMachineConfig;
window.resetMachineConfigForm = resetMachineConfigForm;
</script>
