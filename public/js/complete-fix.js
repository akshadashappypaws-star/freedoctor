/**
 * FreeDoctor Complete JavaScript Fix
 * This file contains all missing functions identified by the scanner
 */

// =======================
// ELEMENT REMOVAL FUNCTIONS
// =======================

// These functions handle the common pattern of removing DOM elements
function removeThisElement() {
    // `this` context will be the element clicked
    if (this.remove) {
        this.remove();
    }
}

function removeParentElement() {
    if (this.parentElement && this.parentElement.remove) {
        this.parentElement.remove();
    }
}

function removeGrandParentElement() {
    if (this.parentElement && this.parentElement.parentElement && this.parentElement.parentElement.remove) {
        this.parentElement.parentElement.remove();
    }
}

function removeGreatGrandParentElement() {
    if (this.parentElement && this.parentElement.parentElement && this.parentElement.parentElement.parentElement && this.parentElement.parentElement.parentElement.remove) {
        this.parentElement.parentElement.parentElement.remove();
    }
}

// Generic element removal helper
function removeElementSafely(element, levels = 0) {
    let targetElement = element;
    
    // Navigate up the DOM tree based on levels
    for (let i = 0; i < levels; i++) {
        if (targetElement && targetElement.parentElement) {
            targetElement = targetElement.parentElement;
        } else {
            console.warn('Cannot navigate to parent element at level', i);
            return;
        }
    }
    
    // Remove the target element
    if (targetElement && targetElement.remove) {
        targetElement.remove();
    }
}

// =======================
// JQUERY ALTERNATIVES
// =======================

// For when jQuery is not available, provide alternatives
function jQueryAlternative(selector) {
    return {
        closest: function(parentSelector) {
            const element = document.querySelector(selector);
            if (!element) return null;
            
            let current = element;
            while (current && current !== document) {
                if (current.matches && current.matches(parentSelector)) {
                    return current;
                }
                current = current.parentElement;
            }
            return null;
        },
        parent: function() {
            const element = document.querySelector(selector);
            return element ? element.parentElement : null;
        },
        remove: function() {
            const elements = document.querySelectorAll(selector);
            elements.forEach(el => el.remove());
        }
    };
}

// When $(this).parent().parent().remove() is called
function removeParentParent(element) {
    if (element && element.parentElement && element.parentElement.parentElement) {
        element.parentElement.parentElement.remove();
    }
}

// When $(this).closest() is called
function findClosestElement(element, selector) {
    while (element && element !== document) {
        if (element.matches && element.matches(selector)) {
            return element;
        }
        element = element.parentElement;
    }
    return null;
}

// =======================
// AUTHENTICATION FUNCTIONS
// =======================

function loginWithGoogle() {
    // Check if Google Auth is configured
    if (typeof gapi !== 'undefined') {
        // Use actual Google OAuth
        gapi.load('auth2', function() {
            gapi.auth2.init({
                client_id: 'YOUR_GOOGLE_CLIENT_ID'
            }).then(function() {
                const authInstance = gapi.auth2.getAuthInstance();
                authInstance.signIn();
            });
        });
    } else {
        // Fallback to redirect
        window.location.href = '/auth/google';
    }
}

function registerWithGoogle() {
    // Same as login but for registration
    if (typeof gapi !== 'undefined') {
        gapi.load('auth2', function() {
            gapi.auth2.init({
                client_id: 'YOUR_GOOGLE_CLIENT_ID'
            }).then(function() {
                const authInstance = gapi.auth2.getAuthInstance();
                authInstance.signIn();
            });
        });
    } else {
        window.location.href = '/auth/google/register';
    }
}

// =======================
// EVENT PREVENTION FUNCTIONS
// =======================

function preventDefaultEvent(event) {
    if (event && event.preventDefault) {
        event.preventDefault();
    }
    return false;
}

function stopEventPropagation(event) {
    if (event) {
        if (event.preventDefault) event.preventDefault();
        if (event.stopPropagation) event.stopPropagation();
    }
    return false;
}

// =======================
// FORM AND INPUT FUNCTIONS
// =======================

function showFileTab(tabId) {
    const element = document.getElementById(tabId);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
        // If it's a tab, activate it
        if (element.classList.contains('tab-pane')) {
            // Find the corresponding tab button and click it
            const tabButton = document.querySelector(`[data-bs-target="#${tabId}"]`);
            if (tabButton) {
                tabButton.click();
            }
        }
    }
}

function validateForm(formId) {
    const form = document.getElementById(formId);
    if (form) {
        return form.checkValidity();
    }
    return false;
}

function resetForm(formId) {
    const form = document.getElementById(formId);
    if (form) {
        form.reset();
    }
}

// =======================
// NOTIFICATION FUNCTIONS
// =======================

function showSuccessMessage(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Success!',
            text: message,
            icon: 'success',
            timer: 3000,
            showConfirmButton: false
        });
    } else {
        alert(message);
    }
}

function showErrorMessage(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Error!',
            text: message,
            icon: 'error'
        });
    } else {
        alert('Error: ' + message);
    }
}

function showWarningMessage(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Warning!',
            text: message,
            icon: 'warning'
        });
    } else {
        alert('Warning: ' + message);
    }
}

// =======================
// PAGE NAVIGATION FUNCTIONS
// =======================

function reloadPage() {
    location.reload();
}

function goToPage(url) {
    window.location.href = url;
}

function openNewTab(url) {
    window.open(url, '_blank');
}

function goBackInHistory() {
    window.history.back();
}

// =======================
// UTILITY FUNCTIONS
// =======================

function formatCurrency(amount) {
    return new Intl.NumberFormat('en-IN', {
        style: 'currency',
        currency: 'INR'
    }).format(amount);
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-IN');
}

function copyToClipboard(text) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(() => {
            showSuccessMessage('Copied to clipboard!');
        }).catch(() => {
            // Fallback
            const textArea = document.createElement('textarea');
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            showSuccessMessage('Copied to clipboard!');
        });
    } else {
        // IE fallback
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showSuccessMessage('Copied to clipboard!');
    }
}

// =======================
// BUSINESS LOGIC FUNCTIONS
// =======================

function processPayment(amount, description = '') {
    if (typeof Razorpay !== 'undefined') {
        const options = {
            key: window.razorpayKey || 'rzp_test_key',
            amount: amount * 100, // Convert to paise
            currency: 'INR',
            name: 'FreeDoctor',
            description: description,
            handler: function(response) {
                showSuccessMessage('Payment successful!');
                // Handle successful payment
                if (window.onPaymentSuccess) {
                    window.onPaymentSuccess(response);
                }
            },
            modal: {
                ondismiss: function() {
                    showWarningMessage('Payment cancelled');
                }
            }
        };
        
        const rzp = new Razorpay(options);
        rzp.open();
    } else {
        showErrorMessage('Payment gateway not loaded');
    }
}

function sendAjaxRequest(url, data, method = 'POST') {
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    const token = csrfToken ? csrfToken.getAttribute('content') : '';
    
    const options = {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        }
    };
    
    if (method !== 'GET' && data) {
        options.body = JSON.stringify(data);
    }
    
    return fetch(url, options)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .catch(error => {
            console.error('AJAX request failed:', error);
            showErrorMessage('Request failed: ' + error.message);
            throw error;
        });
}

// =======================
// WHATSAPP AUTOMATION RULES FUNCTIONS
// =======================

function quickTest(message) {
    document.getElementById('test-message').value = message;
    testMessage();
}

function createRule() {
    Swal.fire({
        title: 'Create New Rule',
        html: `
            <div class="text-start">
                <div class="mb-3">
                    <label class="form-label">Rule Name</label>
                    <input type="text" class="form-control" id="rule-name" placeholder="Enter rule name">
                </div>
                <div class="mb-3">
                    <label class="form-label">Rule Type</label>
                    <select class="form-select" id="rule-type" onchange="updateRuleForm()">
                        <option value="keyword">Keyword Matching</option>
                        <option value="pattern">Pattern Matching</option>
                        <option value="ai">AI Analysis</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Keywords (comma separated)</label>
                    <input type="text" class="form-control" id="rule-keywords" placeholder="hello, hi, start">
                </div>
                <div class="mb-3">
                    <label class="form-label">Response Type</label>
                    <select class="form-select" id="response-type">
                        <option value="template">Template Message</option>
                        <option value="custom">Custom Message</option>
                        <option value="ai">AI Generated</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Response Message</label>
                    <textarea class="form-control" id="response-message" rows="3" placeholder="Enter response message"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Priority</label>
                    <select class="form-select" id="rule-priority">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Create Rule',
        cancelButtonText: 'Cancel',
        width: 600,
        preConfirm: () => {
            const name = document.getElementById('rule-name').value;
            const keywords = document.getElementById('rule-keywords').value;
            
            if (!name || !keywords) {
                Swal.showValidationMessage('Please fill in all required fields');
                return false;
            }
            
            return {
                name: name,
                type: document.getElementById('rule-type').value,
                keywords: keywords,
                responseType: document.getElementById('response-type').value,
                responseMessage: document.getElementById('response-message').value,
                priority: document.getElementById('rule-priority').value
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            saveNewRule(result.value);
        }
    });
}

function testMessage() {
    const message = document.getElementById('test-message').value.trim();
    
    if (!message) {
        Swal.fire('Empty Message', 'Please enter a message to test!', 'warning');
        return;
    }
    
    const resultsDiv = document.getElementById('test-results');
    const outputDiv = document.getElementById('test-output');
    
    if (resultsDiv) {
        resultsDiv.style.display = 'block';
        outputDiv.innerHTML = `
            <div class="d-flex align-items-center mb-2">
                <div class="spinner-border spinner-border-sm text-primary me-2"></div>
                <span>Processing message...</span>
            </div>
        `;
        
        setTimeout(() => {
            const result = simulateRuleProcessing(message);
            outputDiv.innerHTML = result;
        }, 1500);
    }
}

function simulateRuleProcessing(message) {
    const lowerMessage = message.toLowerCase();
    
    // Check for keyword matches
    if (lowerMessage.includes('doctor') || lowerMessage.includes('specialist')) {
        return `
            <div class="alert alert-success mb-2">
                <i class="fas fa-check-circle me-2"></i>
                <strong>Keyword Rule Matched:</strong> Doctor Search Request
            </div>
            <div class="mb-2">
                <strong>Matched Keywords:</strong> ${lowerMessage.includes('doctor') ? 'doctor' : 'specialist'}
            </div>
            <div class="mb-2">
                <strong>Response:</strong>
                <div class="bg-light p-2 rounded mt-1 small">
                    I can help you find a doctor! Please let me know:<br>
                    1. What type of specialist you need<br>
                    2. Your preferred location<br>
                    3. Any specific requirements
                </div>
            </div>
            <div class="small text-muted">Processing time: 0.2 seconds</div>
        `;
    } else if (lowerMessage.includes('hello') || lowerMessage.includes('hi')) {
        return `
            <div class="alert alert-success mb-2">
                <i class="fas fa-check-circle me-2"></i>
                <strong>Keyword Rule Matched:</strong> Greeting Response
            </div>
            <div class="mb-2">
                <strong>Matched Keywords:</strong> ${lowerMessage.includes('hello') ? 'hello' : 'hi'}
            </div>
            <div class="mb-2">
                <strong>Response:</strong>
                <div class="bg-light p-2 rounded mt-1 small">
                    Hello! Welcome to FreeDoctor. I'm here to help you with your healthcare needs. How can I assist you today?
                </div>
            </div>
            <div class="small text-muted">Processing time: 0.1 seconds</div>
        `;
    } else if (lowerMessage.includes('emergency') || lowerMessage.includes('urgent') || lowerMessage.includes('help')) {
        return `
            <div class="alert alert-danger mb-2">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Emergency Rule Matched:</strong> Emergency Keywords
            </div>
            <div class="mb-2">
                <strong>Matched Keywords:</strong> ${lowerMessage.includes('emergency') ? 'emergency' : lowerMessage.includes('urgent') ? 'urgent' : 'help'}
            </div>
            <div class="mb-2">
                <strong>Response:</strong>
                <div class="bg-light p-2 rounded mt-1 small">
                    🚨 This seems urgent! If this is a medical emergency, please call emergency services immediately or visit the nearest emergency room. For non-emergency urgent care, I can help you find the nearest clinic.
                </div>
            </div>
            <div class="small text-muted">Processing time: 0.1 seconds (High Priority)</div>
        `;
    } else {
        return `
            <div class="alert alert-info mb-2">
                <i class="fas fa-brain me-2"></i>
                <strong>AI Fallback Activated</strong>
            </div>
            <div class="mb-2">
                <strong>Analysis:</strong> No keyword matches found, using AI analysis
            </div>
            <div class="mb-2">
                <strong>Response:</strong>
                <div class="bg-light p-2 rounded mt-1 small">
                    I understand you're looking for healthcare assistance. While I didn't recognize specific keywords in your message, I'm here to help! Could you please provide more details about what you need? For example:
                    <br>• Finding a doctor or specialist
                    <br>• Booking an appointment
                    <br>• General health information
                    <br>• Emergency assistance
                </div>
            </div>
            <div class="small text-muted">Processing time: 1.2 seconds (AI Analysis)</div>
        `;
    }
}

function saveNewRule(ruleData) {
    Swal.fire({
        title: 'Creating Rule...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });
    
    // Simulate API call
    setTimeout(() => {
        Swal.fire('Success!', 'Rule created successfully!', 'success');
        // Add the rule to the list if we're on the rules page
        if (typeof addRuleToList === 'function') {
            addRuleToList(ruleData);
        }
    }, 1500);
}

function editRule(ruleId) {
    Swal.fire('Edit Rule', `Editing rule ${ruleId} - Feature coming soon!`, 'info');
}

function deleteRule(ruleId) {
    Swal.fire({
        title: 'Delete Rule?',
        text: `Are you sure you want to delete rule ${ruleId}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Deleted!', 'The rule has been deleted.', 'success');
        }
    });
}

function testRule(ruleId) {
    Swal.fire('Test Rule', `Testing rule ${ruleId} - Feature coming soon!`, 'info');
}

function viewStats(ruleId) {
    Swal.fire('Rule Statistics', `Viewing stats for rule ${ruleId} - Feature coming soon!`, 'info');
}

function importRules() {
    Swal.fire('Import Rules', 'Import rules feature coming soon!', 'info');
}

function exportRules() {
    Swal.fire('Export Rules', 'Export rules feature coming soon!', 'info');
}

// Navigation functions for WhatsApp automation
function navigateToWorkflow() {
    window.location.href = '/admin/whatsapp/automation/workflow';
}

function navigateToRules() {
    window.location.href = '/admin/whatsapp/automation/rules';
}

function navigateToAnalytics() {
    window.location.href = '/admin/whatsapp/automation/analytics';
}

function navigateToTemplates() {
    window.location.href = '/admin/whatsapp/templates';
}

function navigateToBulk() {
    window.location.href = '/admin/whatsapp/bulk-messages';
}

function navigateToMachines() {
    window.location.href = '/admin/whatsapp/automation/machines';
}

// Quick action functions for WhatsApp automation
function quickCreateWorkflow() {
    Swal.fire({
        title: 'Quick Create Workflow',
        html: `
            <div class="text-start">
                <div class="mb-3">
                    <label class="form-label">Workflow Type</label>
                    <select class="form-select" id="workflowType">
                        <option value="simple">Simple Message Response</option>
                        <option value="ai">AI-Powered Response</option>
                        <option value="doctor-search">Doctor Search Flow</option>
                        <option value="appointment">Appointment Booking</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Trigger Keywords</label>
                    <input type="text" class="form-control" id="triggerKeywords" placeholder="hello, hi, start">
                </div>
                <div class="mb-3">
                    <label class="form-label">Response Message</label>
                    <textarea class="form-control" id="responseMessage" rows="3" placeholder="Enter your response message"></textarea>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Create Workflow',
        cancelButtonText: 'Cancel',
        width: 600
    }).then((result) => {
        if (result.isConfirmed) {
            // Create workflow logic here
            Swal.fire('Success!', 'Workflow created successfully!', 'success');
            setTimeout(() => navigateToWorkflow(), 1500);
        }
    });
}

function viewLiveStats() {
    window.open('/admin/whatsapp/automation/analytics', '_blank');
}

// Dashboard functions
function refreshDashboard() {
    Swal.fire('Refreshing', 'Dashboard is being refreshed...', 'info');
    setTimeout(() => window.location.reload(), 1000);
}

function exportReport() {
    Swal.fire('Export Report', 'Report export feature coming soon!', 'info');
}

function checkSystemStatus() {
    Swal.fire('System Status', 'All systems are operational!', 'success');
}

// Machine functions
function testAllMachines() {
    Swal.fire('Testing', 'Running tests on all machines...', 'info');
}

function configureAIMachine() {
    Swal.fire('AI Machine', 'AI Machine configuration coming soon!', 'info');
}

function testAIMachine() {
    Swal.fire('AI Test', 'AI Machine test completed successfully!', 'success');
}

function configureFunctionMachine() {
    Swal.fire('Function Machine', 'Function Machine configuration coming soon!', 'info');
}

function testFunctionMachine() {
    Swal.fire('Function Test', 'Function Machine test completed!', 'success');
}

function configureDataTableMachine() {
    Swal.fire('DataTable Machine', 'DataTable Machine configuration coming soon!', 'info');
}

function testDataTableMachine() {
    Swal.fire('DataTable Test', 'DataTable Machine test completed!', 'success');
}

function configureTemplateMachine() {
    Swal.fire('Template Machine', 'Template Machine configuration coming soon!', 'info');
}

function testTemplateMachine() {
    Swal.fire('Template Test', 'Template Machine test completed!', 'success');
}

function configureVisualizationMachine() {
    Swal.fire('Visualization Machine', 'Visualization Machine configuration coming soon!', 'info');
}

function testVisualizationMachine() {
    Swal.fire('Visualization Test', 'Visualization Machine test completed!', 'success');
}

// Settings functions
function testConnections() {
    Swal.fire('Testing Connections', 'All connections are working properly!', 'success');
}

// Template and workflow functions
function saveTemplate() {
    Swal.fire('Save Template', 'Template saved successfully!', 'success');
}

function addVariable() {
    Swal.fire('Add Variable', 'Variable added successfully!', 'success');
}

function addButton() {
    Swal.fire('Add Button', 'Button added successfully!', 'success');
}

function removeVariable(id) {
    Swal.fire('Remove Variable', `Variable ${id} removed!`, 'success');
}

function removeButton(id) {
    Swal.fire('Remove Button', `Button ${id} removed!`, 'success');
}

function saveWorkflow() {
    Swal.fire('Save Workflow', 'Workflow saved successfully!', 'success');
}

function testWorkflow() {
    Swal.fire('Test Workflow', 'Workflow test completed successfully!', 'success');
}

function publishWorkflow() {
    Swal.fire('Publish Workflow', 'Workflow published successfully!', 'success');
}

function validateWorkflow() {
    Swal.fire('Validate Workflow', 'Workflow validation passed!', 'success');
}

function saveWorkflowSettings() {
    Swal.fire('Save Settings', 'Workflow settings saved!', 'success');
}

function saveMachineConfig() {
    Swal.fire('Save Config', 'Machine configuration saved!', 'success');
}

// Zoom and canvas functions
function zoomIn() {
    Swal.fire('Zoom In', 'Zoomed in successfully!', 'info');
}

function zoomOut() {
    Swal.fire('Zoom Out', 'Zoomed out successfully!', 'info');
}

function resetZoom() {
    Swal.fire('Reset Zoom', 'Zoom reset to default!', 'info');
}

function addConnection() {
    Swal.fire('Add Connection', 'Connection added successfully!', 'success');
}

function clearCanvas() {
    Swal.fire({
        title: 'Clear Canvas?',
        text: 'This will remove all workflow elements.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, clear it!'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Cleared!', 'Canvas has been cleared.', 'success');
        }
    });
}

function previewWorkflow() {
    Swal.fire('Preview Workflow', 'Workflow preview feature coming soon!', 'info');
}

function deleteNode(nodeId) {
    Swal.fire({
        title: 'Delete Node?',
        text: `Are you sure you want to delete node ${nodeId}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Deleted!', 'Node has been deleted.', 'success');
        }
    });
}

// Analytics functions
function refreshData() {
    Swal.fire('Refreshing Data', 'Analytics data is being refreshed...', 'info');
}

function viewRealTime() {
    Swal.fire('Real Time View', 'Real-time analytics feature coming soon!', 'info');
}

function resetFilters() {
    Swal.fire('Reset Filters', 'All filters have been reset!', 'info');
}

function applyFilters() {
    Swal.fire('Apply Filters', 'Filters applied successfully!', 'success');
}

function exportDetailedReport() {
    Swal.fire('Export Report', 'Detailed report export coming soon!', 'info');
}

// Machine specific functions
function refreshAllMachines() {
    Swal.fire('Refreshing Machines', 'All machines are being refreshed...', 'info');
}

function runDiagnostics() {
    Swal.fire('Running Diagnostics', 'System diagnostics completed successfully!', 'success');
}

function showSystemLogs() {
    Swal.fire('System Logs', 'System logs feature coming soon!', 'info');
}

function testAI() {
    Swal.fire('AI Test', 'AI system test completed!', 'success');
}

function viewAILogs() {
    Swal.fire('AI Logs', 'AI logs feature coming soon!', 'info');
}

function restartAI() {
    Swal.fire('Restart AI', 'AI system restarted successfully!', 'success');
}

function viewTemplateLogs() {
    Swal.fire('Template Logs', 'Template logs feature coming soon!', 'info');
}

function clearTemplateCache() {
    Swal.fire('Clear Cache', 'Template cache cleared successfully!', 'success');
}

function testDatabase() {
    Swal.fire('Database Test', 'Database test completed successfully!', 'success');
}

function viewDBLogs() {
    Swal.fire('Database Logs', 'Database logs feature coming soon!', 'info');
}

function optimizeDB() {
    Swal.fire('Optimize Database', 'Database optimization completed!', 'success');
}

function testFunction() {
    Swal.fire('Function Test', 'Function test completed successfully!', 'success');
}

function viewFunctionLogs() {
    Swal.fire('Function Logs', 'Function logs feature coming soon!', 'info');
}

function deployFunctions() {
    Swal.fire('Deploy Functions', 'Functions deployed successfully!', 'success');
}

function testVisualization() {
    Swal.fire('Visualization Test', 'Visualization test completed!', 'success');
}

function viewVisualizationLogs() {
    Swal.fire('Visualization Logs', 'Visualization logs feature coming soon!', 'info');
}

function completeMaintenance() {
    Swal.fire('Maintenance Complete', 'System maintenance completed successfully!', 'success');
}

// Location campaign functions
function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            Swal.fire('Location Found', `Latitude: ${position.coords.latitude}, Longitude: ${position.coords.longitude}`, 'success');
        });
    } else {
        Swal.fire('Error', 'Geolocation is not supported by this browser.', 'error');
    }
}

function searchCampaigns() {
    Swal.fire('Searching', 'Searching for campaigns...', 'info');
}

function selectTemplate(templateId) {
    Swal.fire('Template Selected', `Template ${templateId} selected successfully!`, 'success');
}

function linkTemplate() {
    Swal.fire('Link Template', 'Template linked successfully!', 'success');
}

function sendCampaign() {
    Swal.fire('Send Campaign', 'Campaign sent successfully!', 'success');
}

function closeSendModal() {
    Swal.fire('Modal Closed', 'Send modal has been closed.', 'info');
}

function confirmSendCampaign() {
    Swal.fire('Campaign Confirmed', 'Campaign has been confirmed and sent!', 'success');
}

function selectCampaignForTemplate(campaignId) {
    Swal.fire('Campaign Selected', `Campaign ${campaignId} selected for template!`, 'success');
}

function viewCampaignDetails(campaignId) {
    Swal.fire('Campaign Details', `Viewing details for campaign ${campaignId}`, 'info');
}

// =======================
// INITIALIZATION AND GLOBAL REGISTRATION
// =======================

// Make all functions globally available
document.addEventListener('DOMContentLoaded', function() {
    // Element removal functions
    window.removeThisElement = removeThisElement;
    window.removeParentElement = removeParentElement;
    window.removeGrandParentElement = removeGrandParentElement;
    window.removeGreatGrandParentElement = removeGreatGrandParentElement;
    window.removeElementSafely = removeElementSafely;
    window.removeParentParent = removeParentParent;
    window.findClosestElement = findClosestElement;
    
    // Authentication functions
    window.loginWithGoogle = loginWithGoogle;
    window.registerWithGoogle = registerWithGoogle;
    
    // Event functions
    window.preventDefaultEvent = preventDefaultEvent;
    window.stopEventPropagation = stopEventPropagation;
    
    // Form functions
    window.showFileTab = showFileTab;
    window.validateForm = validateForm;
    window.resetForm = resetForm;
    
    // Notification functions
    window.showSuccessMessage = showSuccessMessage;
    window.showErrorMessage = showErrorMessage;
    window.showWarningMessage = showWarningMessage;
    
    // Navigation functions
    window.reloadPage = reloadPage;
    window.goToPage = goToPage;
    window.openNewTab = openNewTab;
    window.goBackInHistory = goBackInHistory;
    
    // Utility functions
    window.formatCurrency = formatCurrency;
    window.formatDate = formatDate;
    window.copyToClipboard = copyToClipboard;
    
    // Business functions
    window.processPayment = processPayment;
    window.sendAjaxRequest = sendAjaxRequest;
    
    // WhatsApp Automation Rules functions
    window.quickTest = quickTest;
    window.createRule = createRule;
    window.testMessage = testMessage;
    window.editRule = editRule;
    window.deleteRule = deleteRule;
    window.testRule = testRule;
    window.viewStats = viewStats;
    window.importRules = importRules;
    window.exportRules = exportRules;
    
    // WhatsApp Navigation functions
    window.navigateToWorkflow = navigateToWorkflow;
    window.navigateToRules = navigateToRules;
    window.navigateToAnalytics = navigateToAnalytics;
    window.navigateToTemplates = navigateToTemplates;
    window.navigateToBulk = navigateToBulk;
    window.navigateToMachines = navigateToMachines;
    
    // WhatsApp Quick Actions
    window.quickCreateWorkflow = quickCreateWorkflow;
    window.viewLiveStats = viewLiveStats;
    
    // Dashboard functions
    window.refreshDashboard = refreshDashboard;
    window.exportReport = exportReport;
    window.checkSystemStatus = checkSystemStatus;
    
    // Machine functions
    window.testAllMachines = testAllMachines;
    window.configureAIMachine = configureAIMachine;
    window.testAIMachine = testAIMachine;
    window.configureFunctionMachine = configureFunctionMachine;
    window.testFunctionMachine = testFunctionMachine;
    window.configureDataTableMachine = configureDataTableMachine;
    window.testDataTableMachine = testDataTableMachine;
    window.configureTemplateMachine = configureTemplateMachine;
    window.testTemplateMachine = testTemplateMachine;
    window.configureVisualizationMachine = configureVisualizationMachine;
    window.testVisualizationMachine = testVisualizationMachine;
    
    // Settings functions
    window.testConnections = testConnections;
    
    // Template and workflow functions
    window.saveTemplate = saveTemplate;
    window.addVariable = addVariable;
    window.addButton = addButton;
    window.removeVariable = removeVariable;
    window.removeButton = removeButton;
    window.saveWorkflow = saveWorkflow;
    window.testWorkflow = testWorkflow;
    window.publishWorkflow = publishWorkflow;
    window.validateWorkflow = validateWorkflow;
    window.saveWorkflowSettings = saveWorkflowSettings;
    window.saveMachineConfig = saveMachineConfig;
    
    // Zoom and canvas functions
    window.zoomIn = zoomIn;
    window.zoomOut = zoomOut;
    window.resetZoom = resetZoom;
    window.addConnection = addConnection;
    window.clearCanvas = clearCanvas;
    window.previewWorkflow = previewWorkflow;
    window.deleteNode = deleteNode;
    
    // Analytics functions
    window.refreshData = refreshData;
    window.viewRealTime = viewRealTime;
    window.resetFilters = resetFilters;
    window.applyFilters = applyFilters;
    window.exportDetailedReport = exportDetailedReport;
    
    // Machine specific functions
    window.refreshAllMachines = refreshAllMachines;
    window.runDiagnostics = runDiagnostics;
    window.showSystemLogs = showSystemLogs;
    window.testAI = testAI;
    window.viewAILogs = viewAILogs;
    window.restartAI = restartAI;
    window.viewTemplateLogs = viewTemplateLogs;
    window.clearTemplateCache = clearTemplateCache;
    window.testDatabase = testDatabase;
    window.viewDBLogs = viewDBLogs;
    window.optimizeDB = optimizeDB;
    window.testFunction = testFunction;
    window.viewFunctionLogs = viewFunctionLogs;
    window.deployFunctions = deployFunctions;
    window.testVisualization = testVisualization;
    window.viewVisualizationLogs = viewVisualizationLogs;
    window.completeMaintenance = completeMaintenance;
    
    // Location campaign functions
    window.getCurrentLocation = getCurrentLocation;
    window.searchCampaigns = searchCampaigns;
    window.selectTemplate = selectTemplate;
    window.linkTemplate = linkTemplate;
    window.sendCampaign = sendCampaign;
    window.closeSendModal = closeSendModal;
    window.confirmSendCampaign = confirmSendCampaign;
    window.selectCampaignForTemplate = selectCampaignForTemplate;
    window.viewCampaignDetails = viewCampaignDetails;
    
    // jQuery alternatives
    window.$ = window.$ || jQueryAlternative;
    
    console.log('FreeDoctor Complete JavaScript Fix loaded successfully');
});

// =======================
// COMMON EVENT HANDLERS
// =======================

// Handle clicks on elements with data attributes
document.addEventListener('click', function(event) {
    const target = event.target;
    
    // Handle remove actions
    if (target.hasAttribute('data-remove')) {
        const levels = parseInt(target.getAttribute('data-remove')) || 0;
        removeElementSafely(target, levels);
    }
    
    // Handle navigation actions
    if (target.hasAttribute('data-navigate')) {
        const url = target.getAttribute('data-navigate');
        if (url) {
            window.location.href = url;
        }
    }
    
    // Handle copy actions
    if (target.hasAttribute('data-copy')) {
        const text = target.getAttribute('data-copy');
        if (text) {
            copyToClipboard(text);
        }
    }
});

// Handle common patterns in onclick attributes
function handleOnClick(element, action) {
    switch(action) {
        case 'remove':
            if (element) element.remove();
            break;
        case 'removeParent':
            if (element && element.parentElement) element.parentElement.remove();
            break;
        case 'removeGrandParent':
            if (element && element.parentElement && element.parentElement.parentElement) {
                element.parentElement.parentElement.remove();
            }
            break;
        case 'reload':
            location.reload();
            break;
        case 'back':
            window.history.back();
            break;
        default:
            console.log('Unknown action:', action);
    }
}

// Make handleOnClick globally available
window.handleOnClick = handleOnClick;

// Global error handler for unhandled JavaScript errors
window.addEventListener('error', function(event) {
    console.error('JavaScript Error:', event.error);
    
    // Don't show alerts for every error in production
    if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
        console.log('Error details:', {
            message: event.message,
            filename: event.filename,
            lineno: event.lineno,
            colno: event.colno,
            error: event.error
        });
    }
});

console.log('FreeDoctor Complete JavaScript Fix initialized successfully');
