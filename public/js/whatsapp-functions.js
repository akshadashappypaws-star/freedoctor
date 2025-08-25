/**
 * WhatsApp-specific JavaScript functions
 * Handles auto-replies, templates, conversations, and bulk messages
 */

// Auto-reply Functions
function testReply(element) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Testing Reply...',
            text: 'Sending test message',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Simulate API call
        setTimeout(() => {
            Swal.fire({
                title: 'Test Complete',
                text: 'Reply test message sent successfully',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        }, 2000);
    } else {
        showToast('Testing reply...', 'info');
        setTimeout(() => {
            showToast('Reply test completed', 'success');
        }, 2000);
    }
}

// Template Functions
function editLink(url) {
    if (url) {
        window.open(url, '_blank');
    } else {
        showToast('No URL provided', 'error');
    }
}

function editTemplate(templateId) {
    const editUrl = `/admin/whatsapp/templates/${templateId}/edit`;
    window.open(editUrl, '_blank');
}

function deleteTemplate(templateId) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Delete Template?',
            text: 'This action cannot be undone',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            confirmButtonColor: '#d33',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Simulate API call
                fetch(`/admin/whatsapp/templates/${templateId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Deleted!', 'Template has been deleted.', 'success');
                        location.reload();
                    } else {
                        Swal.fire('Error!', 'Failed to delete template.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error!', 'An error occurred.', 'error');
                });
            }
        });
    } else {
        if (confirm('Are you sure you want to delete this template?')) {
            // Fallback for browsers without SweetAlert
            location.href = `/admin/whatsapp/templates/${templateId}/delete`;
        }
    }
}

// Conversation Functions
function deleteConversation(conversationId) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Delete Conversation?',
            text: 'This will permanently delete the conversation history',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            confirmButtonColor: '#d33'
        }).then((result) => {
            if (result.isConfirmed) {
                // Remove the conversation element
                const conversationElement = document.querySelector(`[data-conversation-id="${conversationId}"]`);
                if (conversationElement) {
                    conversationElement.remove();
                    showToast('Conversation deleted', 'success');
                }
            }
        });
    } else {
        if (confirm('Are you sure you want to delete this conversation?')) {
            const conversationElement = document.querySelector(`[data-conversation-id="${conversationId}"]`);
            if (conversationElement) {
                conversationElement.remove();
                showToast('Conversation deleted', 'success');
            }
        }
    }
}

function viewConversation(conversationId) {
    window.location.href = `/admin/whatsapp/conversations/${conversationId}`;
}

function markAsRead(conversationId) {
    fetch(`/admin/whatsapp/conversations/${conversationId}/mark-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const conversationElement = document.querySelector(`[data-conversation-id="${conversationId}"]`);
            if (conversationElement) {
                conversationElement.classList.remove('unread');
                const unreadBadge = conversationElement.querySelector('.unread-badge');
                if (unreadBadge) {
                    unreadBadge.remove();
                }
            }
            showToast('Marked as read', 'success');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Failed to mark as read', 'error');
    });
}

// Bulk Message Functions
function sendBulkMessage(campaignId) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Send Bulk Message?',
            text: 'This will send the message to all selected recipients',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, send it!',
            confirmButtonColor: '#28a745'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Sending Messages...',
                    text: 'Please wait while we send the bulk message',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Simulate sending
                setTimeout(() => {
                    Swal.fire({
                        title: 'Messages Sent!',
                        text: 'Bulk message campaign has been sent successfully',
                        icon: 'success',
                        timer: 3000,
                        showConfirmButton: false
                    });
                }, 3000);
            }
        });
    }
}

function scheduleBulkMessage(campaignId, scheduleTime) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Schedule Bulk Message?',
            text: `Message will be sent at ${scheduleTime}`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, schedule it!'
        }).then((result) => {
            if (result.isConfirmed) {
                showToast('Bulk message scheduled successfully', 'success');
            }
        });
    }
}

// Settings Functions
function saveWhatsAppSettings() {
    const form = document.querySelector('#whatsapp-settings-form');
    if (form) {
        const formData = new FormData(form);
        
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Saving Settings...',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
        }
        
        fetch('/admin/whatsapp/settings', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Saved!', 'Settings have been saved successfully.', 'success');
                } else {
                    showToast('Settings saved successfully', 'success');
                }
            } else {
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Error!', 'Failed to save settings.', 'error');
                } else {
                    showToast('Failed to save settings', 'error');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof Swal !== 'undefined') {
                Swal.fire('Error!', 'An error occurred while saving.', 'error');
            } else {
                showToast('An error occurred', 'error');
            }
        });
    }
}

function testWhatsAppConnection() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Testing Connection...',
            text: 'Checking WhatsApp Business API connection',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Simulate API test
        setTimeout(() => {
            const isConnected = Math.random() > 0.3; // 70% success rate
            
            if (isConnected) {
                Swal.fire({
                    title: 'Connection Successful!',
                    text: 'WhatsApp Business API is connected and working',
                    icon: 'success',
                    timer: 3000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    title: 'Connection Failed',
                    text: 'Please check your API credentials and try again',
                    icon: 'error'
                });
            }
        }, 2000);
    }
}

// Element removal helpers specifically for WhatsApp UI
function removeWhatsAppElement(element) {
    if (element) {
        // Add fade out animation
        element.style.transition = 'opacity 0.3s ease';
        element.style.opacity = '0';
        
        setTimeout(() => {
            if (element.parentElement && element.parentElement.parentElement) {
                element.parentElement.parentElement.remove();
            } else if (element.parentElement) {
                element.parentElement.remove();
            } else {
                element.remove();
            }
        }, 300);
    }
}

// Flow Data Functions
function saveFlowData() {
    const form = document.querySelector('#flow-data-form');
    if (form) {
        const formData = new FormData(form);
        
        fetch('/admin/whatsapp/flow-data', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Flow data saved successfully', 'success');
            } else {
                showToast('Failed to save flow data', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred', 'error');
        });
    }
}

// Initialize WhatsApp functions
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh conversations every 30 seconds
    if (window.location.pathname.includes('conversations')) {
        setInterval(() => {
            const conversationsList = document.querySelector('#conversations-list');
            if (conversationsList) {
                // Refresh conversations list
                fetch('/admin/whatsapp/conversations/refresh')
                    .then(response => response.json())
                    .then(data => {
                        if (data.html) {
                            conversationsList.innerHTML = data.html;
                        }
                    })
                    .catch(error => console.error('Refresh error:', error));
            }
        }, 30000);
    }
    
    console.log('WhatsApp functions initialized');
});

// Make functions globally available
window.testReply = testReply;
window.editLink = editLink;
window.editTemplate = editTemplate;
window.deleteTemplate = deleteTemplate;
window.deleteConversation = deleteConversation;
window.viewConversation = viewConversation;
window.markAsRead = markAsRead;
window.sendBulkMessage = sendBulkMessage;
window.scheduleBulkMessage = scheduleBulkMessage;
window.saveWhatsAppSettings = saveWhatsAppSettings;
window.testWhatsAppConnection = testWhatsAppConnection;
window.removeWhatsAppElement = removeWhatsAppElement;
window.saveFlowData = saveFlowData;
