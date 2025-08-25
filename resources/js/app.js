import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Import all the UI libraries locally instead of CDN
import Swal from 'sweetalert2';
import { Notyf } from 'notyf';
import { Chart, registerables } from 'chart.js';
import Sortable from 'sortablejs';
import Typed from 'typed.js';
import { CountUp } from 'countup.js';
import AOS from 'aos';

// Import CSS files
import 'notyf/notyf.min.css';
import 'aos/dist/aos.css';
import 'sweetalert2/dist/sweetalert2.min.css';

// jQuery is already included via Laravel Mix, but let's make sure
import jQuery from 'jquery';
window.$ = window.jQuery = jQuery;

// Register Chart.js components
Chart.register(...registerables);

// Make libraries globally available
window.Swal = Swal;
window.Notyf = Notyf;
window.Chart = Chart;
window.Sortable = Sortable;
window.Typed = Typed;
window.CountUp = CountUp;
window.AOS = AOS;

// Initialize AOS
AOS.init({
    duration: 800,
    easing: 'ease-in-out',
    once: true,
    mirror: false
});

// Initialize global notification system
const notyf = new Notyf({
    duration: 4000,
    position: {
        x: 'right',
        y: 'top',
    },
    types: [
        {
            type: 'success',
            background: 'linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%)',
            icon: {
                className: 'fas fa-check-circle',
                tagName: 'i',
                color: 'white'
            }
        },
        {
            type: 'error',
            background: 'linear-gradient(135deg, #ff6b6b 0%, #ffa8a8 100%)',
            icon: {
                className: 'fas fa-times-circle',
                tagName: 'i',
                color: 'white'
            }
        },
        {
            type: 'warning',
            background: 'linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%)',
            icon: {
                className: 'fas fa-exclamation-triangle',
                tagName: 'i',
                color: '#8b5a00'
            }
        },
        {
            type: 'info',
            background: 'linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)',
            icon: {
                className: 'fas fa-info-circle',
                tagName: 'i',
                color: '#0369a1'
            }
        }
    ]
});

window.notyf = notyf;

// Global utility functions
window.showSuccess = function(message, title = 'Success!') {
    notyf.success(message);
    if (title !== 'Success!') {
        Swal.fire({
            icon: 'success',
            title: title,
            text: message,
            timer: 3000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    }
};

window.showError = function(message, title = 'Error!') {
    notyf.error(message);
    Swal.fire({
        icon: 'error',
        title: title,
        text: message,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'OK'
    });
};

window.showWarning = function(message, title = 'Warning!') {
    notyf.open({
        type: 'warning',
        message: message
    });
};

window.showInfo = function(message, title = 'Info') {
    notyf.open({
        type: 'info',
        message: message
    });
};

window.confirmAction = function(message, callback, title = 'Are you sure?') {
    Swal.fire({
        title: title,
        text: message,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3b82f6',
        cancelButtonColor: '#ef4444',
        confirmButtonText: 'Yes, proceed!',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        focusCancel: true
    }).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
    });
};

window.animateCounter = function(element, finalValue, duration = 2000) {
    const countUp = new CountUp(element, finalValue, {
        duration: duration / 1000,
        useEasing: true,
        useGrouping: true,
        separator: ',',
        decimal: '.'
    });
    
    if (!countUp.error) {
        countUp.start();
    }
};

// Pusher setup
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    encrypted: true,
    authEndpoint: '/broadcasting/auth',
    auth: {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    }
});

// Enhanced DOM ready functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize counters
    document.querySelectorAll('[data-counter]').forEach(el => {
        const value = parseInt(el.getAttribute('data-counter')) || parseInt(el.textContent);
        if (value) {
            el.textContent = '0';
            animateCounter(el, value);
        }
    });
    
    // Add ripple effects to buttons
    document.addEventListener('click', function(e) {
        if (e.target.matches('.btn, button')) {
            const btn = e.target;
            const rect = btn.getBoundingClientRect();
            const ripple = document.createElement('span');
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.cssText = `
                position: absolute;
                width: ${size}px;
                height: ${size}px;
                left: ${x}px;
                top: ${y}px;
                background: rgba(255, 255, 255, 0.5);
                border-radius: 50%;
                transform: scale(0);
                animation: ripple 0.6s linear;
                pointer-events: none;
            `;
            
            // Add ripple animation CSS if not already added
            if (!document.querySelector('#ripple-styles')) {
                const style = document.createElement('style');
                style.id = 'ripple-styles';
                style.textContent = `
                    @keyframes ripple {
                        to {
                            transform: scale(4);
                            opacity: 0;
                        }
                    }
                    .btn, button {
                        position: relative;
                        overflow: hidden;
                    }
                `;
                document.head.appendChild(style);
            }
            
            btn.appendChild(ripple);
            setTimeout(() => ripple.remove(), 600);
        }
    });
    
    // Enhanced form handling
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                showError('Please fill in all required fields');
                return false;
            }
        });
    });
    
    // Auto-hide alerts
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            if (alert.classList.contains('alert-success') || alert.classList.contains('alert-info')) {
                alert.style.transition = 'opacity 0.5s ease-out';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        });
    }, 5000);
});
