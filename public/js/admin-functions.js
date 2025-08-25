/**
 * Payment and Admin Functions
 * Handles payment processing, withdrawals, and admin UI interactions
 */

// Payment Detail Functions
function viewPaymentDetails(data) {
    if (typeof data === 'string') {
        try {
            data = JSON.parse(data);
        } catch (e) {
            console.error('Invalid payment data:', e);
            return;
        }
    }
    
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Payment Details',
            html: `
                <div class="text-start">
                    <p><strong>Payment ID:</strong> ${data.payment_id || 'N/A'}</p>
                    <p><strong>Amount:</strong> ₹${data.amount || '0'}</p>
                    <p><strong>Status:</strong> <span class="badge bg-${data.status === 'completed' ? 'success' : 'warning'}">${data.status || 'N/A'}</span></p>
                    <p><strong>Date:</strong> ${data.created_at || 'N/A'}</p>
                    <p><strong>Method:</strong> ${data.payment_method || 'N/A'}</p>
                    ${data.description ? `<p><strong>Description:</strong> ${data.description}</p>` : ''}
                </div>
            `,
            confirmButtonText: 'Close',
            width: '500px'
        });
    } else {
        console.log('Payment Details:', data);
    }
}

// Withdrawal Functions
function viewWithdrawalDetails(data) {
    if (typeof data === 'string') {
        try {
            data = JSON.parse(data);
        } catch (e) {
            console.error('Invalid withdrawal data:', e);
            return;
        }
    }
    
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Withdrawal Details',
            html: `
                <div class="text-start">
                    <p><strong>Withdrawal ID:</strong> ${data.withdrawal_id || 'N/A'}</p>
                    <p><strong>Amount:</strong> ₹${data.amount || '0'}</p>
                    <p><strong>Status:</strong> <span class="badge bg-${getWithdrawalStatusColor(data.status)}">${data.status || 'N/A'}</span></p>
                    <p><strong>Requested Date:</strong> ${data.created_at || 'N/A'}</p>
                    <p><strong>Bank Account:</strong> ${data.bank_account || 'N/A'}</p>
                    <p><strong>Processing Fee:</strong> ₹${data.processing_fee || '0'}</p>
                    <p><strong>Net Amount:</strong> ₹${data.net_amount || '0'}</p>
                    ${data.remarks ? `<p><strong>Remarks:</strong> ${data.remarks}</p>` : ''}
                </div>
            `,
            confirmButtonText: 'Close',
            width: '500px'
        });
    } else {
        console.log('Withdrawal Details:', data);
    }
}

function getWithdrawalStatusColor(status) {
    switch(status) {
        case 'completed': return 'success';
        case 'pending': return 'warning';
        case 'rejected': return 'danger';
        case 'processing': return 'info';
        default: return 'secondary';
    }
}

function processWithdrawal(withdrawalId) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Process Withdrawal?',
            text: 'This will initiate the withdrawal processing',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, process it!',
            confirmButtonColor: '#28a745'
        }).then((result) => {
            if (result.isConfirmed) {
                // Simulate API call
                Swal.fire({
                    title: 'Processing...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                setTimeout(() => {
                    Swal.fire('Processed!', 'Withdrawal has been processed successfully.', 'success');
                    location.reload();
                }, 2000);
            }
        });
    }
}

function rejectWithdrawal(withdrawalId) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Reject Withdrawal?',
            input: 'textarea',
            inputLabel: 'Reason for rejection',
            inputPlaceholder: 'Enter reason...',
            inputAttributes: {
                'aria-label': 'Enter reason for rejection'
            },
            showCancelButton: true,
            confirmButtonText: 'Reject',
            confirmButtonColor: '#dc3545'
        }).then((result) => {
            if (result.isConfirmed) {
                const reason = result.value;
                showToast('Withdrawal rejected successfully', 'success');
                location.reload();
            }
        });
    }
}

// Business Request Functions
function viewRequest(data) {
    if (typeof data === 'string') {
        try {
            data = JSON.parse(data);
        } catch (e) {
            console.error('Invalid request data:', e);
            return;
        }
    }
    
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Business Request Details',
            html: `
                <div class="text-start">
                    <p><strong>Company:</strong> ${data.company_name || 'N/A'}</p>
                    <p><strong>Contact Person:</strong> ${data.contact_person || 'N/A'}</p>
                    <p><strong>Email:</strong> ${data.email || 'N/A'}</p>
                    <p><strong>Phone:</strong> ${data.phone || 'N/A'}</p>
                    <p><strong>Request Type:</strong> ${data.request_type || 'N/A'}</p>
                    <p><strong>Message:</strong></p>
                    <div class="border p-2 rounded">${data.message || 'No message'}</div>
                    <p class="mt-2"><strong>Submitted:</strong> ${data.created_at || 'N/A'}</p>
                </div>
            `,
            confirmButtonText: 'Close',
            width: '600px'
        });
    } else {
        console.log('Business Request Details:', data);
    }
}

function approveBusinessRequest(requestId) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Approve Request?',
            text: 'This will approve the business request and notify the requester',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, approve it!',
            confirmButtonColor: '#28a745'
        }).then((result) => {
            if (result.isConfirmed) {
                showToast('Business request approved', 'success');
                location.reload();
            }
        });
    }
}

function rejectBusinessRequest(requestId) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Reject Request?',
            input: 'textarea',
            inputLabel: 'Reason for rejection',
            inputPlaceholder: 'Enter reason...',
            showCancelButton: true,
            confirmButtonText: 'Reject',
            confirmButtonColor: '#dc3545'
        }).then((result) => {
            if (result.isConfirmed) {
                showToast('Business request rejected', 'success');
                location.reload();
            }
        });
    }
}

// Lead Management Functions
function closeLeadModal() {
    closeModal('leadModal');
}

function viewLead(leadData) {
    if (typeof leadData === 'string') {
        try {
            leadData = JSON.parse(leadData);
        } catch (e) {
            console.error('Invalid lead data:', e);
            return;
        }
    }
    
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Lead Details',
            html: `
                <div class="text-start">
                    <p><strong>Name:</strong> ${leadData.name || 'N/A'}</p>
                    <p><strong>Email:</strong> ${leadData.email || 'N/A'}</p>
                    <p><strong>Phone:</strong> ${leadData.phone || 'N/A'}</p>
                    <p><strong>Source:</strong> ${leadData.source || 'N/A'}</p>
                    <p><strong>Status:</strong> <span class="badge bg-${getLeadStatusColor(leadData.status)}">${leadData.status || 'N/A'}</span></p>
                    <p><strong>Interest:</strong> ${leadData.interest || 'N/A'}</p>
                    <p><strong>Message:</strong></p>
                    <div class="border p-2 rounded">${leadData.message || 'No message'}</div>
                </div>
            `,
            confirmButtonText: 'Close',
            width: '500px'
        });
    } else {
        console.log('Lead Details:', leadData);
    }
}

function getLeadStatusColor(status) {
    switch(status) {
        case 'hot': return 'danger';
        case 'warm': return 'warning';
        case 'cold': return 'info';
        case 'converted': return 'success';
        default: return 'secondary';
    }
}

function convertLead(leadId) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Convert Lead?',
            text: 'This will mark the lead as converted',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, convert it!',
            confirmButtonColor: '#28a745'
        }).then((result) => {
            if (result.isConfirmed) {
                showToast('Lead converted successfully', 'success');
                location.reload();
            }
        });
    }
}

// Notification Functions
function markNotificationAsRead(notificationId) {
    fetch(`/admin/notifications/${notificationId}/mark-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const notificationElement = document.querySelector(`[data-notification-id="${notificationId}"]`);
            if (notificationElement) {
                notificationElement.classList.remove('unread');
                const unreadBadge = notificationElement.querySelector('.unread-badge');
                if (unreadBadge) {
                    unreadBadge.remove();
                }
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function markAllNotificationsAsRead() {
    fetch('/admin/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('All notifications marked as read', 'success');
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Failed to mark notifications as read', 'error');
    });
}

// Profit Management Functions
function exportProfitReport(format = 'pdf') {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Exporting Report...',
            text: `Generating ${format.toUpperCase()} report`,
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Simulate export
        setTimeout(() => {
            Swal.fire({
                title: 'Export Complete!',
                text: `Report has been generated in ${format.toUpperCase()} format`,
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
            
            // Trigger download
            const link = document.createElement('a');
            link.href = `/admin/reports/profit/export?format=${format}`;
            link.download = `profit_report.${format}`;
            link.click();
        }, 2000);
    }
}

// Element removal helpers
function removeNotification(element) {
    if (element && element.parentElement && element.parentElement.parentElement) {
        const notification = element.parentElement.parentElement;
        notification.style.transition = 'opacity 0.3s ease';
        notification.style.opacity = '0';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }
}

function removePayoutRow(element) {
    if (element && element.parentElement && element.parentElement.parentElement) {
        const row = element.parentElement.parentElement;
        row.style.transition = 'opacity 0.3s ease';
        row.style.opacity = '0';
        setTimeout(() => {
            row.remove();
        }, 300);
    }
}

// Initialize admin functions
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh notifications every 60 seconds
    if (document.querySelector('.notifications-container')) {
        setInterval(() => {
            fetch('/admin/notifications/count')
                .then(response => response.json())
                .then(data => {
                    const badge = document.querySelector('.notification-badge');
                    if (badge && data.count > 0) {
                        badge.textContent = data.count;
                        badge.style.display = 'inline';
                    } else if (badge) {
                        badge.style.display = 'none';
                    }
                })
                .catch(error => console.error('Notification refresh error:', error));
        }, 60000);
    }
    
    console.log('Admin functions initialized');
});

// Make functions globally available
window.viewPaymentDetails = viewPaymentDetails;
window.viewWithdrawalDetails = viewWithdrawalDetails;
window.processWithdrawal = processWithdrawal;
window.rejectWithdrawal = rejectWithdrawal;
window.viewRequest = viewRequest;
window.approveBusinessRequest = approveBusinessRequest;
window.rejectBusinessRequest = rejectBusinessRequest;
window.closeLeadModal = closeLeadModal;
window.viewLead = viewLead;
window.convertLead = convertLead;
window.markNotificationAsRead = markNotificationAsRead;
window.markAllNotificationsAsRead = markAllNotificationsAsRead;
window.exportProfitReport = exportProfitReport;
window.removeNotification = removeNotification;
window.removePayoutRow = removePayoutRow;
