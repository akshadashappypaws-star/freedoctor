@extends('admin.master')

@section('title', 'Enhanced UI Demo - Amazing Components')

@section('content')
<div class="container-fluid">
    <!-- Page Header with Gradient Background -->
    <div class="row mb-5" data-aos="fade-down">
        <div class="col-12">
            <div class="card border-0 shadow-epic" style="background: var(--gradient-primary); color: white; border-radius: 2rem;">
                <div class="card-body text-center p-5">
                    <h1 class="display-4 fw-bold mb-3 animate__animated animate__bounceInDown">
                        🚀 Enhanced UI Components
                    </h1>
                    <p class="lead mb-4 animate__animated animate__fadeInUp animate__delay-1s">
                        Experience the most beautiful and modern UI components for your WhatsApp management system
                    </p>
                    <div class="d-flex justify-content-center gap-3 animate__animated animate__fadeInUp animate__delay-2s">
                        <button class="btn btn-light btn-lg hvr-bounce-to-right" onclick="showSuccess('Welcome to the future of UI!', 'Amazing!')">
                            <i class="fas fa-rocket me-2"></i>Get Started
                        </button>
                        <button class="btn btn-outline-light btn-lg hvr-bounce-to-left" onclick="scrollToSection('components')">
                            <i class="fas fa-eye me-2"></i>View Components
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Animated Stats Section -->
    <div class="row g-4 mb-5" data-aos="fade-up">
        <div class="col-xl-3 col-lg-6">
            <div class="card border-0 shadow-strong h-100 hvr-float">
                <div class="card-body text-center p-4">
                    <div class="display-1 text-primary mb-3">
                        <i class="fas fa-palette"></i>
                    </div>
                    <h4 class="fw-bold mb-2" data-counter="15">0</h4>
                    <p class="text-muted mb-0">CSS Frameworks</p>
                    <div class="progress mt-3" style="height: 6px;">
                        <div class="progress-bar bg-gradient-primary" role="progressbar" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6">
            <div class="card border-0 shadow-strong h-100 hvr-float">
                <div class="card-body text-center p-4">
                    <div class="display-1 text-success mb-3">
                        <i class="fas fa-magic"></i>
                    </div>
                    <h4 class="fw-bold mb-2" data-counter="50">0</h4>
                    <p class="text-muted mb-0">Animation Effects</p>
                    <div class="progress mt-3" style="height: 6px;">
                        <div class="progress-bar bg-gradient-success" role="progressbar" style="width: 85%"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6">
            <div class="card border-0 shadow-strong h-100 hvr-float">
                <div class="card-body text-center p-4">
                    <div class="display-1 text-warning mb-3">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <h4 class="fw-bold mb-2" data-counter="25">0</h4>
                    <p class="text-muted mb-0">Interactive Components</p>
                    <div class="progress mt-3" style="height: 6px;">
                        <div class="progress-bar bg-gradient-warning" role="progressbar" style="width: 75%"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6">
            <div class="card border-0 shadow-strong h-100 hvr-float">
                <div class="card-body text-center p-4">
                    <div class="display-1 text-danger mb-3">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h4 class="fw-bold mb-2" data-counter="100">0</h4>
                    <p class="text-muted mb-0">User Satisfaction</p>
                    <div class="progress mt-3" style="height: 6px;">
                        <div class="progress-bar bg-gradient-danger" role="progressbar" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Buttons Section -->
    <div class="row mb-5" id="components" data-aos="fade-up">
        <div class="col-12">
            <div class="card border-0 shadow-strong">
                <div class="card-header bg-gradient-primary text-white">
                    <h3 class="card-title mb-0">
                        <i class="material-icons me-2" style="vertical-align: middle;">touch_app</i>
                        Interactive Buttons & Notifications
                    </h3>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <button class="btn btn-primary w-100 hvr-grow" onclick="showSuccess('Success notification with beautiful animation!')">
                                <i class="fas fa-check-circle me-2"></i>Success Alert
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-danger w-100 hvr-grow" onclick="showError('Error notification with enhanced styling!')">
                                <i class="fas fa-exclamation-triangle me-2"></i>Error Alert
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-warning w-100 hvr-grow" onclick="showWarning('Warning notification with modern design!')">
                                <i class="fas fa-warning me-2"></i>Warning Alert
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-info w-100 hvr-grow" onclick="showInfo('Info notification with professional look!')">
                                <i class="fas fa-info-circle me-2"></i>Info Alert
                            </button>
                        </div>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-4">
                            <button class="btn btn-outline-primary w-100 hvr-sweep-to-right" onclick="confirmAction('This is an enhanced confirmation dialog!', function(){ showSuccess('Action confirmed!'); })">
                                <i class="fas fa-question-circle me-2"></i>Confirm Dialog
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-outline-success w-100 hvr-sweep-to-right btn-loading" data-original-text="Loading Demo">
                                <i class="fas fa-spinner me-2"></i>Loading Demo
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-outline-danger w-100 hvr-sweep-to-right" onclick="demonstrateTyping()">
                                <i class="fas fa-keyboard me-2"></i>Typing Effect
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Forms Section -->
    <div class="row mb-5" data-aos="fade-up">
        <div class="col-lg-6">
            <div class="card border-0 shadow-strong h-100">
                <div class="card-header bg-gradient-success text-white">
                    <h4 class="card-title mb-0">
                        <i class="material-icons me-2" style="vertical-align: middle;">edit</i>
                        Enhanced Forms
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form class="space-y-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Enhanced Input Field</label>
                            <input type="text" class="form-control" placeholder="Type something amazing..." required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Beautiful Select</label>
                            <select class="form-select" required>
                                <option value="">Choose an option...</option>
                                <option value="1">WhatsApp Automation</option>
                                <option value="2">Campaign Management</option>
                                <option value="3">User Analytics</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Rich Textarea</label>
                            <textarea class="form-control" rows="3" placeholder="Enter your message here..." required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="demoCheck">
                                <label class="form-check-label fw-semibold" for="demoCheck">
                                    I agree to the enhanced terms and conditions
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-success w-100 hvr-bounce-to-right">
                            <i class="fas fa-paper-plane me-2"></i>Submit with Style
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card border-0 shadow-strong h-100">
                <div class="card-header bg-gradient-warning text-dark">
                    <h4 class="card-title mb-0">
                        <i class="material-icons me-2" style="vertical-align: middle;">table_chart</i>
                        Enhanced Data Table
                    </h4>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Campaign</th>
                                    <th>Status</th>
                                    <th>Messages</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="hvr-glow">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="p-2 rounded bg-primary bg-opacity-10 text-primary me-3">
                                                <i class="fas fa-bullhorn"></i>
                                            </div>
                                            <strong>Health Campaign</strong>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-success">Active</span></td>
                                    <td><span class="fw-bold" data-counter="1247">0</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary hvr-grow-shadow">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="hvr-glow">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="p-2 rounded bg-success bg-opacity-10 text-success me-3">
                                                <i class="fas fa-heart"></i>
                                            </div>
                                            <strong>Wellness Program</strong>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-warning">Pending</span></td>
                                    <td><span class="fw-bold" data-counter="892">0</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-success hvr-grow-shadow">
                                            <i class="fas fa-play"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="hvr-glow">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="p-2 rounded bg-info bg-opacity-10 text-info me-3">
                                                <i class="fas fa-users"></i>
                                            </div>
                                            <strong>Community Outreach</strong>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-info">Draft</span></td>
                                    <td><span class="fw-bold" data-counter="456">0</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-info hvr-grow-shadow">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Glass Morphism Cards -->
    <div class="row mb-5" data-aos="fade-up">
        <div class="col-12">
            <div class="card border-0 shadow-epic glass" style="backdrop-filter: blur(20px);">
                <div class="card-body p-5 text-center">
                    <h2 class="fw-bold mb-4">🎨 Glassmorphism Design</h2>
                    <p class="lead mb-4">Experience the modern glassmorphism effect with beautiful transparency and blur effects</p>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="glass p-4 rounded-3 h-100">
                                <i class="fas fa-rocket display-4 text-primary mb-3"></i>
                                <h5 class="fw-bold">Performance</h5>
                                <p class="mb-0">Lightning fast loading and smooth animations</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="glass p-4 rounded-3 h-100">
                                <i class="fas fa-shield-alt display-4 text-success mb-3"></i>
                                <h5 class="fw-bold">Security</h5>
                                <p class="mb-0">Enterprise-grade security and data protection</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="glass p-4 rounded-3 h-100">
                                <i class="fas fa-users display-4 text-warning mb-3"></i>
                                <h5 class="fw-bold">User Experience</h5>
                                <p class="mb-0">Intuitive design that users absolutely love</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Final CTA Section -->
    <div class="row" data-aos="zoom-in">
        <div class="col-12">
            <div class="card border-0 shadow-epic" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 2rem;">
                <div class="card-body text-center p-5">
                    <h2 class="fw-bold mb-3">Ready to Transform Your UI?</h2>
                    <p class="lead mb-4">Experience the power of modern web design with enhanced animations, beautiful components, and professional styling</p>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <button class="btn btn-light btn-lg hvr-bounce-to-right" onclick="window.location.href='/admin/whatsapp/templates'">
                            <i class="fas fa-file-alt me-2"></i>View Templates
                        </button>
                        <button class="btn btn-outline-light btn-lg hvr-bounce-to-left" onclick="window.location.href='/admin/whatsapp/automation'">
                            <i class="fas fa-cogs me-2"></i>Automation
                        </button>
                        <button class="btn btn-outline-light btn-lg hvr-bounce-to-right" onclick="window.location.href='/admin/whatsapp/conversations'">
                            <i class="fas fa-comments me-2"></i>Conversations
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="typing-demo" class="mt-4" style="display: none;">
    <div class="card border-0 shadow-strong">
        <div class="card-body">
            <h5>Typing Animation Demo:</h5>
            <div id="typed-text" class="h4 text-primary"></div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function demonstrateTyping() {
        const demoDiv = document.getElementById('typing-demo');
        demoDiv.style.display = 'block';
        
        const typed = new Typed('#typed-text', {
            strings: [
                'Welcome to Enhanced UI!',
                'Beautiful animations everywhere...',
                'Modern design components...',
                'Professional styling...',
                'Amazing user experience!'
            ],
            typeSpeed: 50,
            backSpeed: 30,
            backDelay: 1000,
            startDelay: 500,
            loop: true,
            showCursor: true,
            cursorChar: '|'
        });
    }
    
    function scrollToSection(id) {
        document.getElementById(id).scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
    
    // Add some interactive magic
    document.addEventListener('DOMContentLoaded', function() {
        // Add hover effects to cards
        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px) scale(1.01)';
                this.style.transition = 'all 0.3s ease';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
        
        // Initialize progress bars animation
        setTimeout(() => {
            document.querySelectorAll('.progress-bar').forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => {
                    bar.style.transition = 'width 2s ease-in-out';
                    bar.style.width = width;
                }, 100);
            });
        }, 1000);
    });
</script>
@endsection
