<!-- Template Editor Modal -->
<div class="modal fade" id="templateEditorModal" tabindex="-1" aria-labelledby="templateEditorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="templateEditorModalLabel">
                    <i class="fas fa-file-alt me-2"></i>Template Editor
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <form id="templateEditorForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="templateName" class="form-label">Template Name</label>
                                        <input type="text" class="form-control" id="templateName" placeholder="Enter template name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="templateLanguage" class="form-label">Language</label>
                                        <select class="form-select" id="templateLanguage" required>
                                            <option value="en">English</option>
                                            <option value="es">Spanish</option>
                                            <option value="fr">French</option>
                                            <option value="de">German</option>
                                            <option value="hi">Hindi</option>
                                            <option value="ar">Arabic</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="templateCategory" class="form-label">Category</label>
                                        <select class="form-select" id="templateCategory" required>
                                            <option value="">Select Category</option>
                                            <option value="marketing">Marketing</option>
                                            <option value="transactional">Transactional</option>
                                            <option value="otp">OTP</option>
                                            <option value="authentication">Authentication</option>
                                            <option value="notification">Notification</option>
                                            <option value="reminder">Reminder</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="templateType" class="form-label">Template Type</label>
                                        <select class="form-select" id="templateType" required>
                                            <option value="text">Text Only</option>
                                            <option value="media">Media</option>
                                            <option value="interactive">Interactive</option>
                                            <option value="location">Location</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="templateDescription" class="form-label">Description</label>
                                <textarea class="form-control" id="templateDescription" rows="2" placeholder="Enter template description"></textarea>
                            </div>
                            
                            <!-- Template Content Tabs -->
                            <ul class="nav nav-tabs" id="templateContentTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="content-tab" data-bs-toggle="tab" data-bs-target="#content" type="button" role="tab">
                                        <i class="fas fa-edit me-2"></i>Content
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="variables-tab" data-bs-toggle="tab" data-bs-target="#variables" type="button" role="tab">
                                        <i class="fas fa-code me-2"></i>Variables
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="media-tab" data-bs-toggle="tab" data-bs-target="#media" type="button" role="tab">
                                        <i class="fas fa-image me-2"></i>Media
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="buttons-tab" data-bs-toggle="tab" data-bs-target="#buttons" type="button" role="tab">
                                        <i class="fas fa-mouse-pointer me-2"></i>Buttons
                                    </button>
                                </li>
                            </ul>
                            
                            <div class="tab-content mt-3" id="templateContentTabContent">
                                <!-- Content Tab -->
                                <div class="tab-pane fade show active" id="content" role="tabpanel">
                                    <div class="mb-3">
                                        <label for="templateHeader" class="form-label">Header (Optional)</label>
                                        <input type="text" class="form-control" id="templateHeader" placeholder="Enter header text">
                                    </div>
                                    <div class="mb-3">
                                        <label for="templateBody" class="form-label">Body Text</label>
                                        <textarea class="form-control" id="templateBody" rows="6" placeholder="Enter your message content. Use {{variable}} for dynamic content." required></textarea>
                                        <div class="form-text">Use variables like {{name}}, {{date}}, {{order_id}} for dynamic content</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="templateFooter" class="form-label">Footer (Optional)</label>
                                        <input type="text" class="form-control" id="templateFooter" placeholder="Enter footer text">
                                    </div>
                                </div>
                                
                                <!-- Variables Tab -->
                                <div class="tab-pane fade" id="variables" role="tabpanel">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6>Template Variables</h6>
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addVariable()">
                                            <i class="fas fa-plus me-1"></i>Add Variable
                                        </button>
                                    </div>
                                    <div id="variablesList">
                                        <!-- Variables will be added here -->
                                    </div>
                                    <div class="alert alert-info">
                                        <small><strong>Common Variables:</strong> {{name}}, {{phone}}, {{email}}, {{date}}, {{time}}, {{order_id}}, {{amount}}</small>
                                    </div>
                                </div>
                                
                                <!-- Media Tab -->
                                <div class="tab-pane fade" id="media" role="tabpanel">
                                    <div class="mb-3">
                                        <label for="mediaType" class="form-label">Media Type</label>
                                        <select class="form-select" id="mediaType">
                                            <option value="">No Media</option>
                                            <option value="image">Image</option>
                                            <option value="document">Document</option>
                                            <option value="video">Video</option>
                                        </select>
                                    </div>
                                    <div id="mediaUpload" style="display: none;">
                                        <div class="mb-3">
                                            <label for="mediaFile" class="form-label">Upload Media</label>
                                            <input type="file" class="form-control" id="mediaFile" accept="image/*,video/*,.pdf,.doc,.docx">
                                        </div>
                                        <div class="mb-3">
                                            <label for="mediaUrl" class="form-label">Or Enter Media URL</label>
                                            <input type="url" class="form-control" id="mediaUrl" placeholder="https://example.com/media.jpg">
                                        </div>
                                        <div class="mb-3">
                                            <label for="mediaCaption" class="form-label">Caption (Optional)</label>
                                            <input type="text" class="form-control" id="mediaCaption" placeholder="Enter media caption">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Buttons Tab -->
                                <div class="tab-pane fade" id="buttons" role="tabpanel">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6>Interactive Buttons</h6>
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addButton()">
                                            <i class="fas fa-plus me-1"></i>Add Button
                                        </button>
                                    </div>
                                    <div id="buttonsList">
                                        <!-- Buttons will be added here -->
                                    </div>
                                    <div class="alert alert-info">
                                        <small><strong>Note:</strong> Maximum 3 buttons allowed. Button types: URL, Phone, Quick Reply</small>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Preview Panel -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-eye me-2"></i>Live Preview
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="whatsapp-preview">
                                    <div class="preview-container">
                                        <div class="preview-header" id="previewHeader" style="display: none;"></div>
                                        <div class="preview-media" id="previewMedia" style="display: none;"></div>
                                        <div class="preview-body" id="previewBody">Enter your message content...</div>
                                        <div class="preview-footer" id="previewFooter" style="display: none;"></div>
                                        <div class="preview-buttons" id="previewButtons"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>Template Info
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-6">
                                        <small class="text-muted d-block">Characters</small>
                                        <strong id="characterCount">0</strong>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">Variables</small>
                                        <strong id="variableCount">0</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-info" onclick="testTemplate()">
                    <i class="fas fa-play me-2"></i>Test Template
                </button>
                <button type="button" class="btn btn-primary" onclick="saveTemplate()">
                    <i class="fas fa-save me-2"></i>Save Template
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* WhatsApp Preview Styles */
.whatsapp-preview {
    background: #e5ddd5;
    border-radius: 10px;
    padding: 1rem;
    min-height: 300px;
}

.preview-container {
    background: white;
    border-radius: 8px;
    padding: 1rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    position: relative;
}

.preview-container::before {
    content: '';
    position: absolute;
    top: 15px;
    right: -8px;
    width: 0;
    height: 0;
    border-left: 8px solid white;
    border-top: 8px solid transparent;
    border-bottom: 8px solid transparent;
}

.preview-header {
    font-weight: bold;
    margin-bottom: 0.5rem;
    color: #075e54;
}

.preview-media {
    margin-bottom: 0.5rem;
    text-align: center;
}

.preview-media img {
    max-width: 100%;
    border-radius: 5px;
}

.preview-body {
    margin-bottom: 0.5rem;
    line-height: 1.4;
}

.preview-footer {
    font-size: 0.9rem;
    color: #666;
    margin-top: 0.5rem;
}

.preview-buttons {
    margin-top: 1rem;
}

.preview-button {
    display: block;
    width: 100%;
    padding: 0.5rem;
    margin-bottom: 0.25rem;
    background: #f0f0f0;
    border: 1px solid #ddd;
    border-radius: 5px;
    text-align: center;
    color: #075e54;
    text-decoration: none;
    font-size: 0.9rem;
}

.preview-button:hover {
    background: #e0e0e0;
}

/* Variable and Button Management */
.variable-item, .button-item {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 5px;
    padding: 1rem;
    margin-bottom: 0.5rem;
}

.variable-item .form-control, .button-item .form-control {
    margin-bottom: 0.5rem;
}
</style>

<script>
let templateVariables = [];
let templateButtons = [];

document.addEventListener('DOMContentLoaded', function() {
    // Setup event listeners for template editor
    setupTemplateEditor();
});

function setupTemplateEditor() {
    // Live preview updates
    $('#templateHeader, #templateBody, #templateFooter').on('input', updatePreview);
    $('#mediaType').on('change', handleMediaTypeChange);
    
    // Character count
    $('#templateBody').on('input', updateCharacterCount);
    
    // Initialize preview
    updatePreview();
}

function updatePreview() {
    const header = $('#templateHeader').val();
    const body = $('#templateBody').val();
    const footer = $('#templateFooter').val();
    
    // Update header
    if (header) {
        $('#previewHeader').text(header).show();
    } else {
        $('#previewHeader').hide();
    }
    
    // Update body
    $('#previewBody').text(body || 'Enter your message content...');
    
    // Update footer
    if (footer) {
        $('#previewFooter').text(footer).show();
    } else {
        $('#previewFooter').hide();
    }
    
    // Update buttons
    updateButtonsPreview();
    
    // Update character count
    updateCharacterCount();
    
    // Update variable count
    updateVariableCount();
}

function updateCharacterCount() {
    const content = $('#templateBody').val();
    $('#characterCount').text(content.length);
}

function updateVariableCount() {
    const content = $('#templateBody').val();
    const matches = content.match(/\{\{[^}]+\}\}/g);
    $('#variableCount').text(matches ? matches.length : 0);
}

function handleMediaTypeChange() {
    const mediaType = $('#mediaType').val();
    const mediaUpload = $('#mediaUpload');
    
    if (mediaType) {
        mediaUpload.show();
        // Update file input accept attribute
        const acceptTypes = {
            'image': 'image/*',
            'video': 'video/*',
            'document': '.pdf,.doc,.docx,.txt'
        };
        $('#mediaFile').attr('accept', acceptTypes[mediaType] || '*');
    } else {
        mediaUpload.hide();
    }
}

function addVariable() {
    const variableId = 'var_' + Date.now();
    const variableHtml = `
        <div class="variable-item" id="${variableId}">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Variable name" 
                           onchange="updateVariablePreview()">
                </div>
                <div class="col-md-4">
                    <select class="form-select" onchange="updateVariablePreview()">
                        <option value="text">Text</option>
                        <option value="number">Number</option>
                        <option value="date">Date</option>
                        <option value="url">URL</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" placeholder="Default value" 
                           onchange="updateVariablePreview()">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-sm btn-outline-danger" 
                            onclick="removeVariable('${variableId}')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    $('#variablesList').append(variableHtml);
}

function removeVariable(variableId) {
    $(`#${variableId}`).remove();
    updateVariablePreview();
}

function updateVariablePreview() {
    // Update the preview with variable information
    updatePreview();
}

function addButton() {
    if (templateButtons.length >= 3) {
        showAlert('warning', 'Button Limit', 'Maximum 3 buttons allowed per template.');
        return;
    }
    
    const buttonId = 'btn_' + Date.now();
    const buttonHtml = `
        <div class="button-item" id="${buttonId}">
            <div class="row">
                <div class="col-md-3">
                    <select class="form-select" onchange="updateButtonsPreview()">
                        <option value="url">URL</option>
                        <option value="phone">Phone</option>
                        <option value="quick_reply">Quick Reply</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Button text" 
                           maxlength="20" onchange="updateButtonsPreview()">
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="URL/Phone/Payload" 
                           onchange="updateButtonsPreview()">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-sm btn-outline-danger" 
                            onclick="removeButton('${buttonId}')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    $('#buttonsList').append(buttonHtml);
    templateButtons.push({ id: buttonId });
    updateButtonsPreview();
}

function removeButton(buttonId) {
    $(`#${buttonId}`).remove();
    templateButtons = templateButtons.filter(btn => btn.id !== buttonId);
    updateButtonsPreview();
}

function updateButtonsPreview() {
    const buttonsContainer = $('#previewButtons');
    buttonsContainer.empty();
    
    $('.button-item').each(function() {
        const buttonText = $(this).find('input[placeholder="Button text"]').val();
        if (buttonText) {
            buttonsContainer.append(`
                <div class="preview-button">${buttonText}</div>
            `);
        }
    });
}

function testTemplate() {
    const templateData = getTemplateData();
    
    if (!templateData.name || !templateData.body) {
        showAlert('warning', 'Validation Error', 'Please fill in template name and body text.');
        return;
    }
    
    // Simulate template test
    showAlert('info', 'Testing Template', 'Sending test message...');
    
    setTimeout(() => {
        showAlert('success', 'Test Complete', 'Test message sent successfully!');
    }, 2000);
}

function saveTemplate() {
    const templateData = getTemplateData();
    
    // Validate required fields
    if (!templateData.name || !templateData.body || !templateData.category || !templateData.language) {
        showAlert('warning', 'Validation Error', 'Please fill in all required fields.');
        return;
    }
    
    $.ajax({
        url: '/api/automation/templates',
        method: 'POST',
        data: JSON.stringify(templateData),
        contentType: 'application/json',
        success: function(response) {
            if (response.success) {
                $('#templateEditorModal').modal('hide');
                resetTemplateEditor();
                loadTemplates(); // Reload templates list
                showAlert('success', 'Template Saved', 'Template has been saved successfully.');
            }
        },
        error: function() {
            showAlert('error', 'Save Failed', 'Failed to save template. Please try again.');
        }
    });
}

function getTemplateData() {
    // Collect variables
    const variables = [];
    $('.variable-item').each(function() {
        const name = $(this).find('input[placeholder="Variable name"]').val();
        const type = $(this).find('select').val();
        const defaultValue = $(this).find('input[placeholder="Default value"]').val();
        
        if (name) {
            variables.push({ name, type, defaultValue });
        }
    });
    
    // Collect buttons
    const buttons = [];
    $('.button-item').each(function() {
        const type = $(this).find('select').val();
        const text = $(this).find('input[placeholder="Button text"]').val();
        const value = $(this).find('input[placeholder="URL/Phone/Payload"]').val();
        
        if (text) {
            buttons.push({ type, text, value });
        }
    });
    
    return {
        name: $('#templateName').val(),
        description: $('#templateDescription').val(),
        language: $('#templateLanguage').val(),
        category: $('#templateCategory').val(),
        type: $('#templateType').val(),
        header: $('#templateHeader').val(),
        body: $('#templateBody').val(),
        footer: $('#templateFooter').val(),
        media: {
            type: $('#mediaType').val(),
            url: $('#mediaUrl').val(),
            caption: $('#mediaCaption').val()
        },
        variables: variables,
        buttons: buttons
    };
}

function resetTemplateEditor() {
    $('#templateEditorForm')[0].reset();
    $('#variablesList').empty();
    $('#buttonsList').empty();
    templateVariables = [];
    templateButtons = [];
    updatePreview();
}

// Export functions
window.addVariable = addVariable;
window.removeVariable = removeVariable;
window.updateVariablePreview = updateVariablePreview;
window.addButton = addButton;
window.removeButton = removeButton;
window.updateButtonsPreview = updateButtonsPreview;
window.testTemplate = testTemplate;
window.saveTemplate = saveTemplate;
window.resetTemplateEditor = resetTemplateEditor;
</script>
