/* ===== MOBILE SIDEBAR FUNCTIONALITY ===== */

document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const sidebar = document.querySelector('.sidebar');
    const sidebarOverlay = document.querySelector('.sidebar-overlay');
    const body = document.body;
    
    // Create overlay if it doesn't exist
    if (!sidebarOverlay) {
        const overlay = document.createElement('div');
        overlay.className = 'sidebar-overlay';
        document.body.appendChild(overlay);
        sidebarOverlay = overlay;
    }
    
    // Toggle sidebar function
    function toggleSidebar() {
        const isOpen = sidebar.classList.contains('sidebar-open');
        
        if (isOpen) {
            closeSidebar();
        } else {
            openSidebar();
        }
    }
    
    // Open sidebar
    function openSidebar() {
        sidebar.classList.add('sidebar-open');
        sidebarOverlay.classList.add('active');
        body.classList.add('sidebar-open');
        
        // Add accessibility
        sidebar.setAttribute('aria-hidden', 'false');
        mobileMenuBtn.setAttribute('aria-expanded', 'true');
    }
    
    // Close sidebar
    function closeSidebar() {
        sidebar.classList.remove('sidebar-open');
        sidebarOverlay.classList.remove('active');
        body.classList.remove('sidebar-open');
        
        // Add accessibility
        sidebar.setAttribute('aria-hidden', 'true');
        mobileMenuBtn.setAttribute('aria-expanded', 'false');
    }
    
    // Event listeners
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', toggleSidebar);
    }
    
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', closeSidebar);
    }
    
    // Close sidebar on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && sidebar.classList.contains('sidebar-open')) {
            closeSidebar();
        }
    });
    
    // Close sidebar when clicking nav links (mobile only)
    const navLinks = document.querySelectorAll('.nav-item');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                closeSidebar();
            }
        });
    });
    
    // Handle resize events
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            closeSidebar();
        }
    });
    
    // Initialize accessibility attributes
    sidebar.setAttribute('aria-hidden', 'true');
    mobileMenuBtn.setAttribute('aria-expanded', 'false');
    mobileMenuBtn.setAttribute('aria-label', 'Toggle navigation menu');
});

/* ===== ENHANCED TOUCH GESTURES FOR MOBILE ===== */
let touchStartX = 0;
let touchEndX = 0;

// Swipe to open/close sidebar
document.addEventListener('touchstart', function(e) {
    touchStartX = e.changedTouches[0].screenX;
});

document.addEventListener('touchend', function(e) {
    touchEndX = e.changedTouches[0].screenX;
    handleGesture();
});

function handleGesture() {
    const swipeThreshold = 50;
    const sidebar = document.querySelector('.sidebar');
    
    if (window.innerWidth <= 768) {
        // Swipe right from left edge to open
        if (touchStartX < 50 && touchEndX - touchStartX > swipeThreshold) {
            if (!sidebar.classList.contains('sidebar-open')) {
                sidebar.classList.add('sidebar-open');
                document.querySelector('.sidebar-overlay').classList.add('active');
                document.body.classList.add('sidebar-open');
            }
        }
        
        // Swipe left to close
        if (touchStartX - touchEndX > swipeThreshold) {
            if (sidebar.classList.contains('sidebar-open')) {
                sidebar.classList.remove('sidebar-open');
                document.querySelector('.sidebar-overlay').classList.remove('active');
                document.body.classList.remove('sidebar-open');
            }
        }
    }
}
