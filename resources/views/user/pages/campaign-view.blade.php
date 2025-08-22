@extends('../user.master')

@section('meta')
    <meta name="description" content="{{ $metaData['short_description'] }}">
    <meta name="keywords" content="medical campaign, healthcare, donation, {{ $metaData['specialty'] }}, {{ $metaData['campaign_location'] }}, {{ $metaData['doctor_name'] }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="{{ $metaData['type'] }}">
    <meta property="og:url" content="{{ $metaData['url'] }}">
    <meta property="og:title" content="{{ $metaData['title'] }}">
    <meta property="og:description" content="{{ $metaData['description'] }}">
    <meta property="og:image" content="{{ $metaData['image'] }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="Medical Campaign - {{ $metaData['title'] }}">
    <meta property="og:site_name" content="{{ $metaData['site_name'] }}">
    <meta property="og:locale" content="en_US">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ $metaData['url'] }}">
    <meta property="twitter:title" content="{{ $metaData['title'] }}">
    <meta property="twitter:description" content="{{ $metaData['description'] }}">
    <meta property="twitter:image" content="{{ $metaData['image'] }}">
    <meta property="twitter:image:alt" content="Medical Campaign - {{ $metaData['title'] }}">
    <meta property="twitter:site" content="@freedoctor">
    <meta property="twitter:creator" content="@freedoctor">
    
    <!-- WhatsApp / Telegram sharing optimization -->
    <meta property="og:image:type" content="image/svg+xml">
    <meta property="og:description" content="{{ $metaData['description'] }}">
    
    <!-- Additional campaign specific meta -->
    <meta name="campaign:goal" content="{{ $metaData['campaign_goal'] }}">
    <meta name="campaign:location" content="{{ $metaData['campaign_location'] }}">
    <meta name="campaign:deadline" content="{{ $metaData['campaign_deadline'] }}">
    <meta name="campaign:type" content="{{ $metaData['campaign_type'] }}">
    <meta name="campaign:doctor" content="{{ $metaData['doctor_name'] }}">
    <meta name="campaign:specialty" content="{{ $metaData['specialty'] }}">
    <meta name="campaign:fee" content="{{ $metaData['registration_fee'] }}">
    
    <!-- Structured Data for rich snippets -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Event",
        "name": "{{ $campaign->title }}",
        "description": "{{ $metaData['short_description'] }}",
        "image": "{{ $metaData['image'] }}",
        "startDate": "{{ $campaign->start_date ? \Carbon\Carbon::parse($campaign->start_date)->toISOString() : '' }}",
        "endDate": "{{ $campaign->end_date ? \Carbon\Carbon::parse($campaign->end_date)->toISOString() : '' }}",
        "location": {
            "@type": "Place",
            "name": "{{ $metaData['campaign_location'] }}",
            "address": "{{ $metaData['campaign_location'] }}"
        },
        "organizer": {
            "@type": "Person",
            "name": "{{ $metaData['doctor_name'] }}"
        },
        "offers": {
            "@type": "Offer",
            "price": "{{ $campaign->registration_payment ?? 0 }}",
            "priceCurrency": "INR",
            "availability": "https://schema.org/InStock",
            "url": "{{ $metaData['url'] }}"
        }
    }
    </script>
@endsection

@section('title', $metaData['title'])

@section('content')

<style>
    :root {
        --primary-bg: #FCFCFD;
        --accent-color: #E7A51B;
        --dark-text: #2C3E50;
        --light-text: #6C757D;
        --danger-red: #EF4444;
        --border-color: #E5E5E5;
        --success-color: #28A745;
        --info-color: #17A2B8;
        --warning-color: #FFC107;
        --danger-color: #DC3545;
        --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        --hover-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    /* Global Styles */
    body {
        background-color: var(--primary-bg);
        color: var(--dark-text);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        line-height: 1.6;
    }

    /* Medical-themed containers */
    .medical-container {
        background: linear-gradient(135deg, var(--primary-bg) 0%, #F8F9FA 100%);
        border: 2px solid var(--border-color);
        border-radius: 16px;
        box-shadow: var(--card-shadow);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .medical-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--accent-color), #F39C12);
    }

    /* Professional hover effects with lighter colors */
    .medical-container:hover {
        box-shadow: 0 12px 40px rgba(231, 165, 27, 0.15);
        transform: translateY(-3px);
        border-color: rgba(231, 165, 27, 0.3);
    }

    .info-card:hover {
        box-shadow: 0 8px 25px rgba(231, 165, 27, 0.12);
        transform: translateY(-2px);
        border-color: rgba(231, 165, 27, 0.2);
    }

    .btn-medical:hover {
        background: linear-gradient(135deg, #F4C430, #DAA520);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(231, 165, 27, 0.25);
    }

    .btn-secondary-medical:hover {
        background: linear-gradient(135deg, rgba(231, 165, 27, 0.1), rgba(231, 165, 27, 0.2));
        border-color: #F4C430;
        color: #DAA520;
        transform: translateY(-1px);
    }

    /* Smaller, more professional video container */
    .medical-video-container {
        background: white;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--card-shadow);
        max-width: 600px;
        margin: 0 auto;
    }

    .medical-video-container video {
        width: 100%;
        height: 300px;
        object-fit: cover;
    }

    /* Professional typography */
    .campaign-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark-text);
        line-height: 1.3;
        margin-bottom: 0.5rem;
    }

    .campaign-subtitle {
        font-size: 1rem;
        color: var(--light-text);
        font-weight: 500;
    }

    /* Trust and credibility indicators */
    .trust-indicator {
        background: linear-gradient(135deg, #F0F8F0, #E8F5E8);
        border: 1px solid var(--success-color);
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        text-align: center;
    }

    .trust-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.4rem 0.8rem;
        background: linear-gradient(135deg, var(--success-color), #20C997);
        color: white;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        margin: 0.2rem;
    }

    /* Professional statistics display */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 1rem;
        margin: 1.5rem 0;
    }

    .stat-item {
        text-align: center;
        padding: 1rem;
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .stat-item:hover {
        box-shadow: 0 4px 15px rgba(231, 165, 27, 0.1);
        border-color: rgba(231, 165, 27, 0.3);
    }

    .stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--accent-color);
        display: block;
    }

    .stat-label {
        font-size: 0.75rem;
        color: var(--light-text);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    @media (max-width: 768px) {
        .campaign-title {
            font-size: 1.5rem;
        }
        
        .medical-video-container video {
            height: 250px;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    /* Professional Header */
    .campaign-header {
        background: linear-gradient(135deg, #FFFFFF 0%, var(--primary-bg) 100%);
        border-left: 5px solid var(--accent-color);
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .campaign-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-approved {
        background: linear-gradient(135deg, var(--success-color), #20C997);
        color: white;
    }

    .badge-pending {
        background: linear-gradient(135deg, var(--warning-color), #FFD700);
        color: var(--dark-text);
    }

    .badge-medical {
        background: linear-gradient(135deg, var(--info-color), #138496);
        color: white;
    }

    .badge-surgical {
        background: linear-gradient(135deg, #6F42C1, #8E44AD);
        color: white;
    }

    /* Medical Information Cards */
    .info-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
        position: relative;
    }

    .info-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--accent-color);
        border-radius: 2px 0 0 2px;
    }

    .info-card:hover {
        box-shadow: var(--hover-shadow);
        transform: translateY(-1px);
    }

    .info-label {
        color: var(--light-text);
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-value {
        color: var(--dark-text);
        font-weight: 600;
        font-size: 1rem;
    }

    /* Professional Buttons */
    .btn-medical {
        background: linear-gradient(135deg, var(--accent-color), #D4AC0D);
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(231, 165, 27, 0.3);
    }

    .btn-medical:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(231, 165, 27, 0.4);
        color: white;
    }

    .btn-medical::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .btn-medical:hover::before {
        left: 100%;
    }

    .btn-secondary-medical {
        background: white;
        color: var(--accent-color);
        border: 2px solid var(--accent-color);
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-secondary-medical:hover {
        background: var(--accent-color);
        color: white;
        transform: translateY(-1px);
    }

    /* Professional Modal */
    .medical-modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(44, 62, 80, 0.8);
        backdrop-filter: blur(5px);
    }

    .medical-modal-content {
        background: white;
        margin: 3% auto;
        padding: 0;
        border-radius: 16px;
        width: 90%;
        max-width: 600px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        border: 1px solid var(--border-color);
    }

    .medical-modal-header {
        background: linear-gradient(135deg, var(--accent-color), #D4AC0D);
        color: white;
        padding: 1.5rem 2rem;
        border-radius: 16px 16px 0 0;
        display: flex;
        justify-content: between;
        align-items: center;
    }

    .medical-modal-header h2 {
        margin: 0;
        font-weight: 700;
        font-size: 1.5rem;
    }

    .modal-close {
        background: none;
        border: none;
        color: white;
        font-size: 1.5rem;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 50%;
        transition: all 0.3s ease;
    }

    .modal-close:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: rotate(90deg);
    }

    .medical-form-group {
        margin-bottom: 1.5rem;
    }

    .medical-form-label {
        display: block;
        color: var(--dark-text);
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .medical-form-control {
        width: 100%;
        padding: 0.875rem 1rem;
        background: white;
        border: 2px solid var(--border-color);
        border-radius: 8px;
        color: var(--dark-text);
        font-size: 0.875rem;
        transition: all 0.3s ease;
    }

    .medical-form-control:focus {
        outline: none;
        border-color: var(--accent-color);
        box-shadow: 0 0 0 3px rgba(231, 165, 27, 0.1);
    }

    .medical-form-control::placeholder {
        color: var(--light-text);
    }

    /* Registration Info Sections */
    .registration-info {
        background: linear-gradient(135deg, #E8F5E8, #F0F8F0);
        border: 2px solid var(--success-color);
        border-radius: 12px;
        padding: 1.5rem;
        margin: 1rem 0;
    }

    .payment-info {
        background: linear-gradient(135deg, #FFF9E6, #FFFBF0);
        border: 2px solid var(--warning-color);
        border-radius: 12px;
        padding: 1.5rem;
        margin: 1rem 0;
    }

    .free-registration-badge {
        background: linear-gradient(135deg, var(--success-color), #20C997);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
    }

    /* Image Gallery */
    .medical-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .medical-gallery img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 12px;
        border: 2px solid var(--border-color);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .medical-gallery img:hover {
        transform: scale(1.02);
        box-shadow: var(--hover-shadow);
        border-color: var(--accent-color);
    }

    /* Professional Video Player */
    .medical-video-container {
        background: white;
        border: 2px solid var(--border-color);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--card-shadow);
    }

    .medical-video-container video {
        width: 100%;
        height: auto;
    }

    /* Sidebar Enhancement */
    .medical-sidebar {
        background: white;
        border-radius: 16px;
        border: 1px solid var(--border-color);
        overflow: hidden;
        box-shadow: var(--card-shadow);
    }

    .sidebar-header {
        background: linear-gradient(135deg, var(--accent-color), #D4AC0D);
        color: white;
        padding: 1rem 1.5rem;
        font-weight: 700;
        font-size: 1.1rem;
    }

    .sidebar-content {
        padding: 1.5rem;
    }

    /* Professional Stats */
    .medical-stat {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--border-color);
    }

    .medical-stat:last-child {
        border-bottom: none;
    }

    .stat-label {
        color: var(--light-text);
        font-weight: 500;
    }

    .stat-value {
        color: var(--dark-text);
        font-weight: 700;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .medical-modal-content {
            width: 95%;
            margin: 5% auto;
        }

        .campaign-header {
            padding: 1rem;
        }

        .medical-gallery {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }
    }

    /* Accessibility & Professional Touch */
    .medical-icon {
        width: 20px;
        height: 20px;
        color: var(--accent-color);
    }

    .professional-divider {
        height: 2px;
        background: linear-gradient(90deg, var(--accent-color), transparent);
        border: none;
        margin: 2rem 0;
    }

    /* Enhanced Action Buttons */
    .campaign-actions-row {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        justify-content: center;
        margin: 1rem 0;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.875rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        cursor: pointer;
        font-size: 0.9rem;
        min-width: 140px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-register-full {
        background: linear-gradient(135deg, var(--accent-color), #D4AC0D);
        color: white;
    }

    .btn-register-full:hover:not(:disabled) {
        background: linear-gradient(135deg, #F4C430, #DAA520);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(231, 165, 27, 0.25);
        color: white;
    }

    .btn-sponsor-full {
        background: linear-gradient(135deg, var(--success-color), #20C997);
        color: white;
    }

    .btn-sponsor-full:hover {
        background: linear-gradient(135deg, #218838, #1e7e34);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.25);
        color: white;
    }

    .btn-share-full {
        background: linear-gradient(135deg, var(--info-color), #138496);
        color: white;
    }

    .btn-share-full:hover {
        background: linear-gradient(135deg, #17a2b8, #1b6ec2);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(23, 162, 184, 0.25);
        color: white;
    }

    .btn-action:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        background: #6c757d !important;
    }

    .btn-action i {
        font-size: 1rem;
    }

    @media (max-width: 768px) {
        .campaign-actions-row {
            flex-direction: column;
            align-items: stretch;
        }
        
        .btn-action {
            min-width: auto;
            width: 100%;
        }
    }

    /* Scrollbar Styling */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: var(--border-color);
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb {
        background: var(--accent-color);
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #D4AC0D;
    }
</style>

<!-- Professional Medical Campaign Container -->
<div class="container-fluid" style="background: linear-gradient(135deg, var(--primary-bg) 0%, #F8F9FA 100%); min-height: 100vh; padding: 2rem 0;">
    <div class="container">
        
        <!-- Medical Campaign Header -->
        <div class="medical-container campaign-header">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center mb-3">
                        <div style="width: 64px; height: 64px; background: linear-gradient(135deg, var(--accent-color), #D4AC0D); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-right: 1rem; box-shadow: 0 8px 25px rgba(231, 165, 27, 0.3);">
                            <i class="fas fa-heartbeat" style="font-size: 24px; color: white;"></i>
                        </div>
                        <div>
                            <h1 style="color: var(--dark-text); font-weight: 700; font-size: 2.5rem; margin: 0; line-height: 1.2;">{{ $campaign->title }}</h1>
                            <p style="color: var(--light-text); margin: 0.5rem 0 0 0; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-map-marker-alt medical-icon"></i>{{ $campaign->location }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @php
                        $statusClass = match($campaign->approval_status) {
                            'approved' => 'badge-approved',
                            'pending' => 'badge-pending',
                            'rejected' => 'badge-danger',
                            default => 'badge-secondary'
                        };
                        @endphp
                        <span class="campaign-badge {{ $statusClass }}">
                            <i class="fas fa-check-circle"></i>
                            {{ ucfirst($campaign->approval_status ?? 'pending') }}
                        </span>

                        @if($campaign->camp_type === 'medical')
                        <span class="campaign-badge badge-medical">
                            <i class="fas fa-stethoscope"></i>Medical Camp
                        </span>
                        @elseif($campaign->camp_type === 'surgical')
                        <span class="campaign-badge badge-surgical">
                            <i class="fas fa-user-md"></i>Surgical Camp
                        </span>
                        @endif
                    </div>
                </div>
                
                <div class="col-lg-4 text-lg-end">
                    <div class="d-flex justify-content-lg-end justify-content-start align-items-center gap-3 mb-3">
                        <div class="text-center">
                            <div style="color: var(--light-text); font-size: 0.875rem; font-weight: 500;">Start Date</div>
                            <div style="color: var(--dark-text); font-weight: 700; font-size: 1rem;">{{ \Carbon\Carbon::parse($campaign->start_date)->format('M j, Y') }}</div>
                        </div>
                        <div style="width: 2px; height: 40px; background: var(--border-color);"></div>
                        <div class="text-center">
                            <div style="color: var(--light-text); font-size: 0.875rem; font-weight: 500;">End Date</div>
                            <div style="color: var(--dark-text); font-weight: 700; font-size: 1rem;">{{ \Carbon\Carbon::parse($campaign->end_date)->format('M j, Y') }}</div>
                        </div>
                    </div>

                    <!-- Enhanced Professional Action Buttons -->
                    <div class="campaign-actions-row">
                        @php
                            $status = $campaign->end_date && \Carbon\Carbon::parse($campaign->end_date)->isPast() ? 'completed' : 'active';
                            $campaignUrl = route('user.campaigns.view', $campaign->id);
                            
                            // Add referral ID to URLs if user is authenticated
                            $referralId = null;
                            if (auth('user')->check() && auth('user')->user()->your_referral_id) {
                                $referralId = auth('user')->user()->your_referral_id;
                            }
                            
                            // Check for referral in URL (for dynamic meta handling)
                            if (request()->has('ref')) {
                                $referralId = request()->get('ref');
                                $campaignUrl .= '?ref=' . $referralId;
                            }
                        @endphp
                        
                        @if($status === 'completed')
                            <button class="btn-action btn-register-full" disabled>
                                <i class="fas fa-clock"></i>
                                <span>Closed</span>
                            </button>
                        @else
                            @auth('user')
                                @php
                                    $registerUrl = route('user.campaigns.register', $campaign->id);
                                    if($referralId) {
                                        $registerUrl .= '?ref=' . $referralId;
                                    } elseif(auth('user')->user()->your_referral_id) {
                                        $registerUrl .= '?ref=' . auth('user')->user()->your_referral_id;
                                    }
                                @endphp
                                <a href="{{ $registerUrl }}" class="btn-action btn-register-full">
                                    <i class="fas fa-user-plus"></i>
                                    <span>Register</span>
                                </a>
                            @else
                                @php
                                    $registerUrl = route('user.campaigns.register', $campaign->id);
                                    if($referralId) {
                                        $registerUrl .= '?ref=' . $referralId;
                                    }
                                @endphp
                                <button data-action="login-prompt" 
                                        data-register-url="{{ $registerUrl }}" 
                                        class="btn-action btn-register-full">
                                    <i class="fas fa-user-plus"></i>
                                    <span>Register</span>
                                </button>
                            @endauth
                        @endif
                        
                        @php
                            $sponsorUrl = route('user.campaigns.sponsor', $campaign->id);
                            if(auth('user')->check() && auth('user')->user()->your_referral_id) {
                                $sponsorUrl .= '?ref=' . auth('user')->user()->your_referral_id;
                            } elseif($referralId) {
                                $sponsorUrl .= '?ref=' . $referralId;
                            }
                        @endphp
                        <a href="{{ $sponsorUrl }}" class="btn-action btn-sponsor-full">
                            <i class="fas fa-hand-holding-heart"></i>
                            <span>Sponsor</span>
                        </a>

                        @php
                            $shareUrl = $campaignUrl;
                            if(auth('user')->check() && auth('user')->user()->your_referral_id) {
                                $shareUrl .= (strpos($shareUrl, '?') !== false ? '&' : '?') . 'ref=' . auth('user')->user()->your_referral_id;
                            }
                            
                            $shareMessage = "💡 Special Limited-Time Offer! 💡\n" .
                                           "Step into a healthier tomorrow!\n\n" .
                                           "Join our upcoming medical camp: {$campaign->title}\n\n" .
                                           "✨ Why Join?\n" .
                                           "✔ Top specialists in one place\n" .
                                           "✔ Affordable health checks\n" .
                                           "✔ Personalized guidance\n" .
                                           "✔ Instant registration confirmation\n\n" .
                                           "📍 Location: {$campaign->location}\n" .
                                           "🩺 Doctor: " . ($campaign->doctor ? $campaign->doctor->doctor_name : 'TBD') . "\n" .
                                           "💰 Registration Fee: " . ($campaign->registration_payment > 0 ? '₹' . number_format($campaign->registration_payment) : 'Free') . "\n" .
                                           "📅 Date: " . ($campaign->start_date ? \Carbon\Carbon::parse($campaign->start_date)->format('M d, Y') : 'TBD') . "\n\n" .
                                           "🩺 Tell us which camp you're interested in and we'll send you a custom information pack with everything you need to know.\n\n" .
                                           "💚 Because your health can't wait.\n\n" .
                                           "Register Now: {$shareUrl}";
                        @endphp
                        <button onclick="shareCleanCampaign('{{ $shareUrl }}', '{{ addslashes($shareMessage) }}')" 
                                class="btn-action btn-share-full">
                            <i class="fas fa-share-alt"></i>
                            <span>Share</span>
                        </button>
                    </div>
                    
                    @if($campaign->end_date)
                    <div style="font-size: 0.8rem; color: var(--light-text); margin-top: 0.5rem; text-align: center;">
                        <i class="fas fa-calendar me-1"></i>Registration ends: {{ \Carbon\Carbon::parse($campaign->end_date)->format('M j, Y') }}
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <hr class="professional-divider">

        <!-- Main Content Grid -->
        <div class="row">
            <!-- Main Content Column -->
            <div class="col-xl-8 mb-4">
                
                <!-- Campaign Information Section -->
                <div class="medical-container mb-4">
                    <div class="p-4">
                        <h2 style="color: var(--dark-text); font-weight: 700; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
                            <i class="fas fa-info-circle medical-icon"></i>
                            Medical Campaign Details
                        </h2>

                        <!-- Trust and Verification Badges -->
                        <div class="trust-indicator mb-4">
                            <h4 style="color: var(--dark-text); margin-bottom: 1rem; font-size: 1rem;">
                                <i class="fas fa-shield-check medical-icon me-2"></i>Verified Healthcare Campaign
                            </h4>
                            <div class="d-flex justify-content-center flex-wrap gap-1">
                                <span class="trust-badge">
                                    <i class="fas fa-user-md"></i>Licensed Doctors
                                </span>
                                <span class="trust-badge">
                                    <i class="fas fa-hospital"></i>Accredited Facility
                                </span>
                                <span class="trust-badge">
                                    <i class="fas fa-certificate"></i>Government Approved
                                </span>
                                <span class="trust-badge">
                                    <i class="fas fa-lock"></i>Secure Platform
                                </span>
                            </div>
                        </div>

                        <!-- Campaign Statistics -->
                        <div class="stats-grid">
                            <div class="stat-item">
                                <span class="stat-number">{{ $campaign->expected_patients }}</span>
                                <span class="stat-label">Expected Patients</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">
                                    {{ \Carbon\Carbon::parse($campaign->end_date)->diffInDays(\Carbon\Carbon::parse($campaign->start_date)) + 1 }}
                                </span>
                                <span class="stat-label">Days Duration</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">
                                    @if($campaign->registration_payment && $campaign->registration_payment > 0)
                                        ₹{{ number_format($campaign->registration_payment) }}
                                    @else
                                        FREE
                                    @endif
                                </span>
                                <span class="stat-label">Registration</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">{{ ucfirst($campaign->camp_type ?? 'Medical') }}</span>
                                <span class="stat-label">Camp Type</span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="info-card">
                                    <div class="info-label">
                                        <i class="fas fa-map-marker-alt medical-icon"></i>Campaign Location
                                    </div>
                                    <div class="info-value">{{ $campaign->location }}</div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="info-card">
                                    <div class="info-label">
                                        <i class="fas fa-calendar medical-icon"></i>Campaign Duration
                                    </div>
                                    <div class="info-value">
                                        {{ \Carbon\Carbon::parse($campaign->start_date)->format('M j') }} - {{ \Carbon\Carbon::parse($campaign->end_date)->format('M j, Y') }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="info-card">
                                    <div class="info-label">
                                        <i class="fas fa-clock medical-icon"></i>Operating Hours
                                    </div>
                                    <div class="info-value">{{ $campaign->timings }}</div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="info-card">
                                    <div class="info-label">
                                        <i class="fas fa-phone medical-icon"></i>Contact Information
                                    </div>
                                    <div class="info-value">{{ $campaign->contact_number }}</div>
                                </div>
                            </div>

                            @php
                            $specializations = is_string($campaign->specializations)
                            ? json_decode($campaign->specializations, true)
                            : $campaign->specializations;
                            @endphp

                            @if($specializations)
                            <div class="col-12 mb-3">
                                <div class="info-card">
                                    <div class="info-label">
                                        <i class="fas fa-stethoscope medical-icon"></i>Medical Specializations Available
                                    </div>
                                    <div class="info-value">
                                        {{ is_array($specializations) ? implode(', ', array_column($specializations, 'name')) : 'General Medicine & Health Checkup' }}
                                    </div>
                                </div>
                            </div>
                            @endif

                        </div>

                        @if($campaign->description)
                        <hr class="professional-divider">
                        <div class="info-card">
                            <div class="info-label">
                                <i class="fas fa-align-left medical-icon"></i>About This Campaign
                            </div>
                            <div class="info-value" style="line-height: 1.6;">{{ $campaign->description }}</div>
                        </div>
                        @endif

                        @if($campaign->service_in_camp)
                        <div class="info-card">
                            <div class="info-label">
                                <i class="fas fa-medical-kit medical-icon"></i>Medical Services Included
                            </div>
                            <div class="info-value" style="line-height: 1.6;">{{ $campaign->service_in_camp }}</div>
                        </div>
                        @endif

                        <!-- Additional Healthcare Information -->
                        <div class="info-card" style="background: linear-gradient(135deg, #F8F9FA, #E9ECEF);">
                            <div class="info-label">
                                <i class="fas fa-heart medical-icon"></i>What to Expect
                            </div>
                            <div class="info-value">
                                <ul style="margin: 0; padding-left: 1.2rem; line-height: 1.6;">
                                    <li>Professional medical consultation by qualified doctors</li>
                                    <li>Basic health screening and vital signs check</li>
                                    <li>Prescription medicines (if required) at subsidized rates</li>
                                    <li>Health awareness and preventive care guidance</li>
                                    <li>Follow-up consultation recommendations</li>
                                    <li>Digital health record maintenance</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Campaign Video Section -->
                @if($campaign->video && Storage::exists('public/' . $campaign->video))
                <div class="medical-container mb-4">
                    <div class="p-4">
                        <h2 style="color: var(--dark-text); font-weight: 700; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
                            <i class="fas fa-video medical-icon"></i>
                            Campaign Introduction Video
                        </h2>
                        <div class="text-center">
                            <div class="medical-video-container">
                                <video controls preload="metadata" style="width: 100%; height: 300px; object-fit: cover;">
                                    <source src="{{ asset('storage/' . $campaign->video) }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                            <div style="margin-top: 1rem; font-size: 0.85rem; color: var(--light-text);">
                                <i class="fas fa-info-circle me-1"></i>Learn more about this healthcare campaign and what to expect during your visit.
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Campaign Images Gallery -->
                @if($campaign->images)
                @php
                $images = is_string($campaign->images) ? json_decode($campaign->images, true) : $campaign->images;
                @endphp
                @if(is_array($images) && count($images) > 0)
                <div class="medical-container mb-4">
                    <div class="p-4">
                        <h2 style="color: var(--dark-text); font-weight: 700; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
                            <i class="fas fa-images medical-icon"></i>
                            Campaign Gallery
                        </h2>
                        <div class="medical-gallery">
                            @foreach($images as $image)
                            <img src="{{ asset('storage/' . $image) }}"
                                alt="Medical Campaign Image"
                                onclick="openImageModal(this.src)">
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
                @endif

                <!-- About FreeDoctor Medical Camps  -->
                <div class="medical-container mb-4">
                    <div class="p-4">
                        <h2 style="color: var(--dark-text); font-weight: 700; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
                            <i class="fas fa-heart medical-icon"></i>
                            About FreeDoctor Medical Camps 
                        </h2>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="info-card" style="height: 100%;">
                                    <div class="info-label">
                                        <i class="fas fa-bullseye medical-icon"></i>Our Mission
                                    </div>
                                    <div class="info-value" style="font-size: 0.9rem; line-height: 1.6;">
                                        To democratize healthcare by connecting communities with qualified medical professionals through organized health campaigns, making quality healthcare accessible and affordable for everyone.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="info-card" style="height: 100%;">
                                    <div class="info-label">
                                        <i class="fas fa-eye medical-icon"></i>Our Vision
                                    </div>
                                    <div class="info-value" style="font-size: 0.9rem; line-height: 1.6;">
                                        To create a healthier society where preventive healthcare reaches every corner of India, reducing healthcare disparities and empowering communities with medical knowledge.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="trust-indicator">
                            <h4 style="color: var(--dark-text); margin-bottom: 1rem; font-size: 1.1rem;">
                                <i class="fas fa-award medical-icon me-2"></i>Why Choose FreeDoctor?
                            </h4>
                            <div class="row text-start">
                                <div class="col-md-6 mb-2">
                                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                        <i class="fas fa-check-circle" style="color: var(--success-color);"></i>
                                        <span style="font-size: 0.9rem; font-weight: 500;">Over 500+ Verified Doctors</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                        <i class="fas fa-check-circle" style="color: var(--success-color);"></i>
                                        <span style="font-size: 0.9rem; font-weight: 500;">Government Recognized Platform</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                        <i class="fas fa-check-circle" style="color: var(--success-color);"></i>
                                        <span style="font-size: 0.9rem; font-weight: 500;">50,000+ Patients Served</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                        <i class="fas fa-check-circle" style="color: var(--success-color);"></i>
                                        <span style="font-size: 0.9rem; font-weight: 500;">24/7 Support Available</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                        <i class="fas fa-check-circle" style="color: var(--success-color);"></i>
                                        <span style="font-size: 0.9rem; font-weight: 500;">Digital Health Records</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                        <i class="fas fa-check-circle" style="color: var(--success-color);"></i>
                                        <span style="font-size: 0.9rem; font-weight: 500;">Secure Payment Gateway</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="info-card" style="background: linear-gradient(135deg, #FFF9E6, #FFFBF0);">
                            <div class="info-label">
                                <i class="fas fa-handshake medical-icon"></i>Our Commitment to Quality
                            </div>
                            <div class="info-value" style="font-size: 0.9rem; line-height: 1.6;">
                                Every doctor on our platform is thoroughly verified with valid medical licenses and credentials. 
                                All healthcare facilities are inspected and approved by local health authorities. We maintain 
                                strict quality standards and patient safety protocols to ensure you receive the best possible care.
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Enhanced Professional Sidebar -->
            <div class="col-xl-4">
                
                <!-- Quick Registration Card -->
                <div class="medical-sidebar mb-4">
                    <div class="sidebar-header">
                        <i class="fas fa-rocket me-2"></i>Quick Registration
                    </div>
                    <div class="sidebar-content text-center">
                        <div style="margin-bottom: 1rem;">
                            <i class="fas fa-clock" style="font-size: 2rem; color: var(--accent-color); margin-bottom: 0.5rem;"></i>
                            <div style="font-size: 0.9rem; color: var(--light-text);">Registration takes only</div>
                            <div style="font-size: 1.2rem; font-weight: 700; color: var(--dark-text);">2 Minutes</div>
                        </div>
                        @if($campaign->end_date && \Carbon\Carbon::parse($campaign->end_date)->isPast())
                        <button class="btn-medical w-100 mb-2" disabled style="opacity: 0.6; cursor: not-allowed;">
                            <i class="fas fa-clock me-2"></i>Registration Closed
                        </button>
                        <div style="font-size: 0.75rem; color: var(--danger-red); text-align: center; margin-bottom: 0.5rem;">
                            <i class="fas fa-exclamation-triangle me-1"></i>Registration deadline passed
                        </div>
                        @else
                        <button onclick="handleRegistration({{ $campaign->id }}, {{ $campaign->registration_payment ?? 0 }})" 
                                class="btn-medical w-100 mb-2">
                            <i class="fas fa-user-plus me-2"></i>Register Now
                        </button>
                        @if($campaign->end_date)
                        <div style="font-size: 0.75rem; color: var(--light-text); text-align: center; margin-bottom: 0.5rem;">
                            <i class="fas fa-calendar me-1"></i>Ends: {{ \Carbon\Carbon::parse($campaign->end_date)->format('M j, Y') }}
                        </div>
                        @endif
                        @endif
                        <div style="font-size: 0.75rem; color: var(--light-text);">
                            <i class="fas fa-shield-alt me-1"></i>Your data is 100% secure
                        </div>
                    </div>
                </div>
                
                <!-- Campaign Summary Card -->
                <div class="medical-sidebar mb-4">
                    <div class="sidebar-header">
                        <i class="fas fa-chart-line me-2"></i>Campaign Overview
                    </div>
                    <div class="sidebar-content">
                        <div class="medical-stat">
                            <span class="stat-label">Campaign Created:</span>
                            <span class="stat-value">{{ \Carbon\Carbon::parse($campaign->created_at)->diffForHumans() }}</span>
                        </div>
                        <div class="medical-stat">
                            <span class="stat-label">Last Updated:</span>
                            <span class="stat-value">{{ \Carbon\Carbon::parse($campaign->updated_at)->diffForHumans() }}</span>
                        </div>
                        <div class="medical-stat">
                            <span class="stat-label">Medical Category:</span>
                            <span class="stat-value">{{ ucfirst($campaign->camp_type ?? 'General Health') }}</span>
                        </div>
                        <div class="medical-stat">
                            <span class="stat-label">Approval Status:</span>
                            <span class="stat-value" style="color: var(--success-color);">{{ ucfirst($campaign->approval_status ?? 'Verified') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Health Safety Guidelines -->
                <div class="medical-sidebar mb-4">
                    <div class="sidebar-header">
                        <i class="fas fa-shield-virus me-2"></i>Health & Safety
                    </div>
                    <div class="sidebar-content">
                        <div style="font-size: 0.85rem; line-height: 1.5; color: var(--dark-text);">
                            <div style="margin-bottom: 0.8rem;">
                                <strong style="color: var(--accent-color);">COVID-19 Protocols:</strong>
                                <ul style="margin: 0.3rem 0 0 1rem; padding: 0;">
                                    <li>Mandatory mask wearing</li>
                                    <li>Social distancing maintained</li>
                                    <li>Regular sanitization</li>
                                    <li>Temperature screening</li>
                                </ul>
                            </div>
                            <div style="margin-bottom: 0.8rem;">
                                <strong style="color: var(--accent-color);">What to Bring:</strong>
                                <ul style="margin: 0.3rem 0 0 1rem; padding: 0;">
                                    <li>Valid ID proof</li>
                                    <li>Previous medical reports</li>
                                    <li>Current medications list</li>
                                    <li>Registration confirmation</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Campaign Thumbnail -->
                @if($campaign->thumbnail)
                <div class="medical-sidebar mb-4">
                    <div class="sidebar-header">
                        <i class="fas fa-image me-2"></i>Campaign Preview
                    </div>
                    <div class="sidebar-content">
                        <img src="{{ asset('storage/' . $campaign->thumbnail) }}"
                            alt="Campaign Preview"
                            style="width: 100%; height: auto; border-radius: 8px; border: 2px solid var(--border-color);">
                        <div style="text-align: center; margin-top: 0.5rem; font-size: 0.75rem; color: var(--light-text);">
                            Official campaign thumbnail
                        </div>
                    </div>
                </div>
                @endif

                <!-- Patient Testimonials -->
                <div class="medical-sidebar mb-4">
                    <div class="sidebar-header">
                        <i class="fas fa-quote-left me-2"></i>Patient Reviews
                    </div>
                    <div class="sidebar-content">
                        <div style="background: #F8F9FA; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border-left: 4px solid var(--accent-color);">
                            <div style="font-size: 0.85rem; line-height: 1.5; color: var(--dark-text); margin-bottom: 0.5rem;">
                                "Excellent healthcare service! The doctors were professional and the facility was clean and well-organized."
                            </div>
                            <div style="font-size: 0.75rem; color: var(--light-text);">
                                <i class="fas fa-user-circle me-1"></i>Sarah M. - Previous Participant
                            </div>
                            <div style="color: var(--accent-color); font-size: 0.8rem;">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                        </div>
                        
                        <div style="background: #F8F9FA; padding: 1rem; border-radius: 8px; border-left: 4px solid var(--accent-color);">
                            <div style="font-size: 0.85rem; line-height: 1.5; color: var(--dark-text); margin-bottom: 0.5rem;">
                                "Free quality healthcare for my family. Highly recommend FreeDoctor campaigns to everyone."
                            </div>
                            <div style="font-size: 0.75rem; color: var(--light-text);">
                                <i class="fas fa-user-circle me-1"></i>Raj K. - Community Member
                            </div>
                            <div style="color: var(--accent-color); font-size: 0.8rem;">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Registration Information -->
                <div class="medical-sidebar mb-4">
                    <div class="sidebar-header">
                        <i class="fas fa-clipboard-check me-2"></i>Registration Details
                    </div>
                    <div class="sidebar-content">
                        <div class="medical-stat">
                            <span class="stat-label">Registration Type:</span>
                            <span class="stat-value">
                                @if($campaign->registration_payment && $campaign->registration_payment > 0)
                                Paid (₹{{ number_format($campaign->registration_payment) }})
                                @else
                                Free Healthcare
                                @endif
                            </span>
                        </div>
                        <div class="medical-stat">
                            <span class="stat-label">Current Status:</span>
                            <span class="stat-value" style="color: var(--success-color);">Open for Registration</span>
                        </div>
                        <div class="medical-stat">
                            <span class="stat-label">Expected Participants:</span>
                            <span class="stat-value">{{ $campaign->expected_patients }} patients</span>
                        </div>
                        @if($campaign->per_refer_cost > 0)
                        <div class="medical-stat">
                            <span class="stat-label">Referral Bonus:</span>
                            <span class="stat-value" style="color: var(--accent-color);">₹{{ number_format($campaign->per_refer_cost) }} per referral</span>
                        </div>
                        @endif
                        
                        <div style="background: linear-gradient(135deg, #E8F5E8, #F0F8F0); padding: 1rem; border-radius: 8px; margin-top: 1rem; text-align: center;">
                            <div style="font-size: 0.85rem; color: var(--dark-text); margin-bottom: 0.5rem;">
                                <i class="fas fa-clock me-1"></i>Registration Deadline
                            </div>
                            <div style="font-weight: 700; color: var(--accent-color);">
                                {{ \Carbon\Carbon::parse($campaign->start_date)->subDay()->format('M j, Y') }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Emergency Contact -->
                <div class="medical-sidebar mb-4">
                    <div class="sidebar-header">
                        <i class="fas fa-phone-alt me-2"></i>Emergency Support
                    </div>
                    <div class="sidebar-content text-center">
                        <div style="margin-bottom: 1rem;">
                            <i class="fas fa-headset" style="font-size: 2rem; color: var(--accent-color); margin-bottom: 0.5rem;"></i>
                            <div style="font-size: 0.9rem; color: var(--dark-text); font-weight: 600;">24/7 Healthcare Support</div>
                            <div style="font-size: 0.8rem; color: var(--light-text);">Available for all registered patients</div>
                        </div>
                        
                        <div style="background: #FFF9E6; padding: 1rem; border-radius: 8px; border: 1px solid var(--warning-color);">
                            <div style="font-size: 0.85rem; color: var(--dark-text); margin-bottom: 0.5rem;">
                                <strong>Emergency Helpline:</strong>
                            </div>
                            <div style="font-size: 1.1rem; font-weight: 700; color: var(--accent-color); margin-bottom: 0.5rem;">
                                +91 99999 88888
                            </div>
                            <div style="font-size: 0.75rem; color: var(--light-text);">
                                For medical emergencies & urgent queries
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


    <!-- Professional Medical Footer -->
    <section style="background: var(--light-bg); padding: 2rem 0;">
        <div class="container">
            <div class="medical-container">
                <div style="background: linear-gradient(135deg, var(--primary-blue), var(--primary-gold)); color: white; padding: 2rem; border-radius: 12px;">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 style="font-weight: 700; margin-bottom: 1rem;">
                                <i class="fas fa-stethoscope me-2"></i>Join the FreeDoctor Family
                            </h3>
                            <p style="font-size: 1rem; margin-bottom: 1.5rem; opacity: 0.95;">
                                Experience healthcare with compassion, expertise, and genuine care. Our platform connects you with trusted medical professionals committed to your well-being.
                            </p>
                            <div class="d-flex flex-wrap gap-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-shield-alt me-2"></i>
                                    <span>100% Secure Platform</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-md me-2"></i>
                                    <span>Verified Professionals</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-heart me-2"></i>
                                    <span>Patient-Centered Care</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div style="background: rgba(255,255,255,0.15); padding: 1.5rem; border-radius: 10px;">
                                <h4 style="font-weight: 600; margin-bottom: 1rem;">Get Started Today</h4>
                                <p style="font-size: 0.9rem; margin-bottom: 1.5rem; opacity: 0.9;">
                                    Join thousands of patients who trust FreeDoctor for their healthcare needs.
                                </p>
                                <button class="btn btn-light btn-lg fw-bold" style="color: var(--primary-blue); border-radius: 25px; padding: 0.75rem 2rem;">
                                    <i class="fas fa-arrow-right me-2"></i>Explore Campaigns
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
  @include('user.partials.footer')
<!-- Professional Medical Registration Modal -->
<div id="registrationModal" class="medical-modal">
    <div class="medical-modal-content">
        <div class="medical-modal-header">
            <h2><i class="fas fa-user-plus me-2"></i>Register for Medical Campaign</h2>
            <button class="modal-close" onclick="closeRegistrationModal()">&times;</button>
        </div>
        <div style="padding: 2rem;">
            <form id="registrationForm">
                @csrf
                <input type="hidden" name="campaign_id" id="campaignId">
                <input type="hidden" name="user_id" id="userId" value="{{ auth('user')->id() }}">
                <input type="hidden" name="amount" id="amount">

                <div class="row">
                    <div class="col-md-6">
                        <div class="medical-form-group">
                            <label class="medical-form-label">
                                <i class="fas fa-user medical-icon me-2"></i>Full Name *
                            </label>
                            <input type="text" name="name" class="medical-form-control" placeholder="Enter your full name" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="medical-form-group">
                            <label class="medical-form-label">
                                <i class="fas fa-envelope medical-icon me-2"></i>Email Address *
                            </label>
                            <input type="email" name="email" class="medical-form-control" placeholder="Enter your email address" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-phone me-2"></i>Phone Number *
                            </label>
                            <input type="tel" name="phone_number" class="form-control" placeholder="Enter your phone number" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-map-marker-alt me-2"></i>Address *
                            </label>
                            <input type="text" name="address" class="form-control" placeholder="Enter your address" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-comment me-2"></i>Additional Information
                    </label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Any additional information or special requirements (optional)"></textarea>
                </div>

                <!-- Payment Information -->
                <div id="paymentInfo" class="payment-info" style="display: none;">
                    <h4><i class="fas fa-credit-card me-2"></i>Payment Required</h4>
                    <div>
                        <p>Registration Fee: <span id="paymentAmount" class="fw-bold"></span></p>
                        <p class="mb-0"><small>Secure payment via Razorpay</small></p>
                    </div>
                </div>

                <div id="freeInfo" class="payment-info" style="display: none;">
                    <h4><i class="fas fa-gift me-2"></i>Free Registration</h4>
                    <p class="mb-0">This campaign offers free registration for all participants!</p>
                </div>

                <div class="d-flex gap-3 mt-4">
                    <button type="button" id="paymentBtn" class="btn-success flex-fill" style="display: none;">
                        <i class="fas fa-credit-card me-2"></i>
                        <span id="paymentBtnText">Pay Now</span>
                    </button>

                    <button type="submit" id="freeRegisterBtn" class="btn-primary flex-fill" style="display: none;">
                        <i class="fas fa-check me-2"></i>
                        Register Now
                    </button>

                    <button type="button" onclick="closeRegistrationModal()" class="btn-secondary flex-fill">
                        <i class="fas fa-times me-2"></i>
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Place this in your main layout (e.g. layouts/app.blade.php) just before your other scripts --}}
<script>
    // Expose user-authenticated flag for the “user” guard
    window.isUserLoggedIn = {{ auth('user')->check() ? 'true' : 'false' }};
</script>
@endsection
@push('scripts')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.0/dist/echo.iife.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
$(function() {
        // Authentication and CSRF setup
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        const loginUrl = '{{ route("user.login") }}';
        window.isUserLoggedIn = @json(auth('user')->check());
        const currentUserId = {{ auth()->guard('user')->id() ?? 'null' }};
        const campaignId = {{ $campaign->id }};

        // Check for referral link in URL and store in localStorage
        function checkReferralFromURL() {
            const urlParams = new URLSearchParams(window.location.search);
            const ref = urlParams.get('ref'); // Plain text referral ID
            const s = urlParams.get('s'); // Encoded referral ID
            
            let referralCode = null;
            let paramToRemove = null;
            
            if (ref) {
                // Handle plain text referral ID (new format)
                referralCode = ref;
                paramToRemove = 'ref';
                console.log('🔗 Found plain text referral:', ref);
            } else if (s) {
                // Handle encoded referral ID (old format)
                try {
                    referralCode = atob(s);
                    paramToRemove = 's';
                    console.log('🔗 Found encoded referral:', s, '-> decoded:', referralCode);
                } catch (e) {
                    console.error('❌ Error decoding referral link:', e);
                    return;
                }
            }
            
            if (referralCode) {
                try {
                    // Store referral data in localStorage
                    const referralData = {
                        referral_code: referralCode,
                        campaign_id: campaignId,
                        timestamp: Date.now(),
                        clicked_at: new Date().toISOString()
                    };
                    
                    localStorage.setItem('pending_referral', JSON.stringify(referralData));
                    
                    // Log for debugging
                    console.log('✅ Referral data stored in localStorage:', referralData);
                    
                    // Clean URL by removing referral parameter
                    const currentUrl = new URL(window.location);
                    currentUrl.searchParams.delete(paramToRemove);
                    window.history.replaceState({}, document.title, currentUrl.toString());
                    
                    // Show referral notification
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Welcome!',
                            text: 'You were referred to this campaign. Register now to help your referrer earn rewards!',
                            icon: 'info',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    }
                } catch (e) {
                    console.error('❌ Error processing referral link:', e);
                }
            }
        }
        
        // Call referral check on page load
        checkReferralFromURL();

        // Login prompt functionality for register buttons
        document.addEventListener('DOMContentLoaded', function() {
            // Handle login prompt for register button
            document.querySelectorAll('[data-action="login-prompt"]').forEach(button => {
                button.addEventListener('click', function() {
                    const registerUrl = this.dataset.registerUrl;
                    
                    Swal.fire({
                        title: 'Login Required',
                        text: 'Please login to register for this campaign',
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonText: 'Login',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Store the intended destination
                            sessionStorage.setItem('redirect_after_login', registerUrl);
                            window.location.href = loginUrl;
                        }
                    });
                });
            });
        });

        // Enhanced share function
        window.shareCleanCampaign = function(url, message) {
            // Check if Web Share API is supported
            if (navigator.share) {
                navigator.share({
                    title: 'Medical Campaign Support - {{ $campaign->title }}',
                    text: message,
                    url: url
                }).catch(console.error);
            } else {
                // Fallback: Copy to clipboard with message
                const fullContent = message;
                
                navigator.clipboard.writeText(fullContent).then(() => {
                    Swal.fire({
                        title: 'Copied!',
                        text: 'Campaign details and link copied to clipboard',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }).catch(() => {
                    // Manual copy fallback
                    const textArea = document.createElement('textarea');
                    textArea.value = fullContent;
                    document.body.appendChild(textArea);
                    textArea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textArea);
                    
                    Swal.fire({
                        title: 'Copied!',
                        text: 'Campaign details and link copied to clipboard',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                });
            }
        };

        // Pusher & Echo setup
        window.Pusher = Pusher;
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: '{{ config("broadcasting.connections.pusher.key") }}',
            cluster: '{{ config("broadcasting.connections.pusher.options.cluster") }}',
            forceTLS: true,
            auth: {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            }
        });

        @auth('user')
        Echo.private(`user.{{ auth('user')->id() }}`)
            .listen('.message.received', e => toastr.success(e.message, '📨 New Message'));
        @endauth

        // Modal functions
        function openRegistrationModal(id, fee = 0) {
            if (!window.isUserLoggedIn) {
                return Swal.fire({
                    title: 'Login Required',
                    text: 'Please log in to register for campaigns.',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Login Now',
                    cancelButtonText: 'Cancel'
                }).then(r => r.isConfirmed && (window.location = loginUrl));
            }

            $('#campaignId').val(id);
            $('#userId').val('{{ auth("user")->id() }}');
            $('#amount').val(fee);

            if (fee > 0) {
                $('#paymentInfo').show();
                $('#freeInfo').hide();
                $('#paymentBtn').show();
                $('#freeRegisterBtn').hide();
                $('#paymentAmount').text(`₹${new Intl.NumberFormat('en-IN').format(fee)}`);
                $('#paymentBtnText').text(`Pay ₹${new Intl.NumberFormat('en-IN').format(fee)}`);
            } else {
                $('#paymentInfo').hide();
                $('#freeInfo').show();
                $('#paymentBtn').hide();
                $('#freeRegisterBtn').show();
            }

            $('#registrationModal').show();
            $('body').css('overflow', 'hidden');
        }

        function closeRegistrationModal() {
            $('#registrationModal').hide();
            $('body').css('overflow', 'auto');
            $('#registrationForm')[0].reset();
            $('#paymentInfo, #freeInfo, #paymentBtn, #freeRegisterBtn').hide();
        }

        // Global functions
        window.handleRegistration = openRegistrationModal;
        window.closeRegistrationModal = closeRegistrationModal;

        // Card navigation function
        window.navigateToCampaign = function(campaignId) {
            let url = '{{ route("user.campaigns.view", ":id") }}';
            url = url.replace(':id', campaignId);
            window.location.href = url;
        };

        // Sponsor navigation function
        window.navigateToSponsors = function() {
            window.location.href = '{{ route("user.sponsors") }}';
        };

        // Modal event listeners
        $('.close').on('click', closeRegistrationModal);
        $(window).on('click', e => {
            if (e.target.id === 'registrationModal') closeRegistrationModal();
        });
        $(document).on('keydown', e => {
            if (e.key === 'Escape') closeRegistrationModal();
        });

        // Sticky Search Functionality
        function handleStickySearch() {
            const stickyContainer = $('#stickySearchContainer');
            const searchHeader = $('.search-header');
            
            // Check if elements exist before proceeding
            if (!searchHeader.length || !stickyContainer.length) {
                return; // Exit if required elements don't exist
            }
            
            const searchHeaderOffset = searchHeader.offset();
            if (!searchHeaderOffset) {
                return; // Exit if offset is undefined
            }
            
            const searchHeaderTop = searchHeaderOffset.top + searchHeader.outerHeight();
            const scrollTop = $(window).scrollTop();

            if (scrollTop > searchHeaderTop) {
                stickyContainer.removeClass('not-sticky');
            } else {
                stickyContainer.addClass('not-sticky');
            }
        }

        // Filter Toggle Functionality
        window.toggleFilters = function() {
            const filtersSection = $('#advancedFilters');
            const toggleBtn = $('#filterToggleBtn');
            const toggleIcon = $('#filterToggleIcon');

            if (filtersSection.hasClass('collapsed')) {
                // Show filters
                filtersSection.removeClass('collapsed').addClass('expanded');
                toggleBtn.addClass('active');
                toggleIcon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
            } else {
                // Hide filters
                filtersSection.removeClass('expanded').addClass('collapsed');
                toggleBtn.removeClass('active');
                toggleIcon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
            }
        };

        // Enhanced search functionality for main search bar
        function enhancedSearch() {
            const searchValue = $('#searchTitle').val().toLowerCase();

            let visibleCount = 0;
            $('.campaign-item').each(function() {
                const card = $(this);
                const title = (card.data('title') || '').toLowerCase();
                const location = (card.data('location') || '').toLowerCase();
                const specializations = (card.data('specializations') || '').toLowerCase();

                // Search in title, location, and specializations
                const matchesSearch = title.includes(searchValue) ||
                    location.includes(searchValue) ||
                    specializations.includes(searchValue);

                if (matchesSearch || searchValue === '') {
                    card.closest('.col-md-6').show();
                    visibleCount++;
                } else {
                    card.closest('.col-md-6').hide();
                }
            });

            // Apply additional filters if any are set
            if ($('#searchLocation').val() || $('#typeFilter').val() || $('#specialtyFilter').val()) {
                filterCampaigns();
            } else {
                if (visibleCount > 0) {
                    $('#noResults').hide();
                } else {
                    $('#noResults').show();
                }
            }
        }

        // Event listeners for sticky search (only if elements exist)
        if ($('#stickySearchContainer').length && $('.search-header').length) {
            $(window).on('scroll', handleStickySearch);
            $(window).on('resize', handleStickySearch);
            
            // Initialize sticky search on page load
            handleStickySearch();
        }

        // Enhanced search input event
        $('#searchTitle').on('input', enhancedSearch);

        // Filter campaigns function
        function filterCampaigns() {
            const titleSearch = $('#searchTitle').val().toLowerCase();
            const locationSearch = $('#searchLocation').val().toLowerCase();
            const typeFilter = $('#typeFilter').val();
            const specialtyFilter = $('#specialtyFilter').val();

            let visibleCount = 0;
            $('.campaign-item').each(function() {
                const card = $(this);
                const title = (card.data('title') || '').toLowerCase();
                const location = (card.data('location') || '').toLowerCase();
                const type = card.data('type');
                const specializations = (card.data('specializations') || '').toLowerCase();

                const matchesTitle = title.includes(titleSearch);
                const matchesLocation = location.includes(locationSearch);
                const matchesType = !typeFilter || type === typeFilter;
                const matchesSpecialty = !specialtyFilter || specializations.includes(specialtyFilter);

                if (matchesTitle && matchesLocation && matchesType && matchesSpecialty) {
                    card.closest('.col-md-6').show();
                    visibleCount++;
                } else {
                    card.closest('.col-md-6').hide();
                }
            });

            if (visibleCount > 0) {
                $('#noResults').hide();
            } else {
                $('#noResults').show();
            }
        }

        // Clear filters function
        window.clearFilters = function() {
            $('#searchTitle').val('');
            $('#searchLocation').val('');
            $('#typeFilter').val('');
            $('#specialtyFilter').val('');
            $('.campaign-item').closest('.col-md-6').show();
            $('#noResults').hide();

            // Close filters if they're open
            const filtersSection = $('#advancedFilters');
            const toggleBtn = $('#filterToggleBtn');
            const toggleIcon = $('#filterToggleIcon');

            if (filtersSection.hasClass('expanded')) {
                filtersSection.removeClass('expanded').addClass('collapsed');
                toggleBtn.removeClass('active');
                toggleIcon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
            }
        }

        // Filter event listeners - updated for new structure
        $('#searchLocation').on('input', filterCampaigns);
        $('#typeFilter, #specialtyFilter').on('change', filterCampaigns);

        // Free registration form submission
        $('#registrationForm').on('submit', function(e) {
            e.preventDefault();
            if ($('#paymentBtn').is(':visible')) return; // Skip if payment flow

            const fd = new FormData(this);
            Swal.fire({
                title: 'Processing Registration...',
                text: 'Please wait while we process your registration.',
                didOpen: Swal.showLoading,
                allowOutsideClick: false
            });

            fetch('{{ route("user.patient.campaigns.register") }}', {
                    method: 'POST',
                    credentials: 'include',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: fd
                })
                .then(res => {
                    if ([401, 419].includes(res.status)) throw new Error('login');
                    return res.json();
                })
                .then(d => {
                    Swal.close();
                    if (d.success) {
                        closeRegistrationModal();
                        Swal.fire({
                            title: 'Registration Successful!',
                            text: 'You have been successfully registered for the campaign.',
                            icon: 'success',
                            confirmButtonText: 'Great!'
                        });
                    } else {
                        Swal.fire('Registration Failed', d.message || 'Registration failed. Please try again.', 'error');
                    }
                })
                .catch(err => {
                    Swal.close();
                    if (err.message === 'login') {
                        Swal.fire('Session Expired', 'Please log in again to continue.', 'warning')
                            .then(() => window.location = loginUrl);
                    } else {
                        Swal.fire('Error', 'An error occurred during registration. Please try again.', 'error');
                    }
                });
        });

        // Paid registration via Razorpay
        $('#paymentBtn').on('click', function(e) {
            e.preventDefault();

            const form = $('#registrationForm')[0];
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const fd = new FormData(form);
            const campaignId = $('#campaignId').val();
            const userId = $('#userId').val();
            const amountPaise = parseFloat($('#amount').val() || 0) * 100;

            fd.set('campaign_id', campaignId);
            fd.set('user_id', userId);

            const options = {
                key: '{{ config("services.razorpay.key") }}',
                amount: amountPaise,
                currency: 'INR',
                name: 'FreeDoctor Healthcare',
                description: 'Campaign Registration Fee',
                image: '{{ asset("storage/PngVectordeisgn.png") }}',
                prefill: {
                    name: $('input[name="name"]').val(),
                    email: $('input[name="email"]').val(),
                    contact: $('input[name="phone_number"]').val(),
                },
                theme: {
                    color: '#667eea'
                },
                handler(response) {
                    fd.set('razorpay_payment_id', response.razorpay_payment_id);

                    $.ajax({
                        url: '{{ route("user.campaigns.payment.create") }}',
                        method: 'POST',
                        data: fd,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        processData: false,
                        contentType: false,
                        success(data) {
                            // Process referral from localStorage if present
                            processReferralFromLocalStorage();
                            
                            Swal.fire({
                                title: 'Payment Successful!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonText: 'Continue'
                            }).then(() => {
                                closeRegistrationModal();
                                if (data.redirect_url) {
                                    window.location = data.redirect_url;
                                }
                            });
                        },
                        error(xhr) {
                            Swal.fire('Payment Error', 'Something went wrong with the payment. Please try again.', 'error');
                        }
                    });
                },
                modal: {
                    ondismiss() {
                        Swal.fire('Payment Cancelled', 'You cancelled the payment process.', 'warning');
                    }
                }
            };

            new Razorpay(options).open();
        });

        // Auto-fill user information if logged in
        if (window.isUserLoggedIn) {
            const user = @json(auth('user')->user());

            $('#registrationForm input[name="name"]').val(user.name || '');
            $('#registrationForm input[name="email"]').val(user.email || '');
            $('#registrationForm input[name="phone_number"]').val(user.phone_number || '');
            $('#registrationForm input[name="address"]').val(user.address || '');
        }

        // Share campaign function
        window.shareCampaign = function(campaignId) {
            const campaignCard = $(`.campaign-item[data-id="${campaignId}"]`);
            const title = campaignCard.find('.campaign-title').text();
            const location = campaignCard.find('.campaign-location').text();
            const url = window.location.href;

            const shareText = `🏥 ${title}\n📍 ${location}\n\nJoin this healthcare campaign on FreeDoctor!\n\n${url}`;

            if (navigator.share) {
                navigator.share({
                    title: title,
                    text: shareText,
                    url: url
                }).catch((error) => console.log('Share failed:', error));
            } else {
                navigator.clipboard.writeText(shareText).then(() => {
                    Swal.fire({
                        title: 'Copied to Clipboard!',
                        text: 'Campaign details copied. You can now share it on WhatsApp, Instagram, or other platforms.',
                        icon: 'success',
                        timer: 3000
                    });
                }).catch(() => {
                    Swal.fire({
                        title: 'Share Campaign',
                        html: `<textarea readonly style="width: 100%; height: 120px; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">${shareText}</textarea>`,
                        text: 'Copy the text above and share it wherever you like!',
                        icon: 'info'
                    });
                });
            }
        }

        // Referral link generation function
        function generateReferralLink(campaignId) {
            fetch('/user/referral/generate-link', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ 
                    campaign_id: campaignId,
                    user_id: currentUserId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    Swal.fire({
                        title: 'Error',
                        text: data.error,
                        icon: 'error'
                    });
                } else {
                    const earnAmount = parseFloat(data.per_refer_cost).toFixed(2);
                    const referralLink = data.referral_link;
                    
                    Swal.fire({
                        title: 'Your Referral Link',
                        html: `
                            <div class="space-y-4">
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                    <div class="flex items-center justify-center space-x-2 text-green-700">
                                        <i class="fas fa-coins"></i>
                                        <span class="font-semibold">Earn ₹${earnAmount} per successful referral!</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Your Referral Link:</label>
                                    <div class="flex items-center space-x-2">
                                        <input type="text" value="${referralLink}" readonly 
                                               class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm"
                                               id="referralLinkInput">
                                        <button onclick="copyReferralLink()" 
                                                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-md text-sm">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <button onclick="shareOnWhatsApp('${referralLink}')" 
                                            class="flex-1 bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-md">
                                        <i class="fab fa-whatsapp mr-2"></i>WhatsApp
                                    </button>
                                    <button onclick="shareOnTwitter('${referralLink}')" 
                                            class="flex-1 bg-blue-400 hover:bg-blue-500 text-white py-2 px-4 rounded-md">
                                        <i class="fab fa-twitter mr-2"></i>Twitter
                                    </button>
                                    <button onclick="shareGeneral('${referralLink}')" 
                                            class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-md">
                                        <i class="fas fa-share mr-2"></i>More
                                    </button>
                                </div>
                            </div>
                        `,
                        showCloseButton: true,
                        showConfirmButton: false,
                        width: '600px'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'Failed to generate referral link',
                    icon: 'error'
                });
            });
        }
        
        function copyReferralLink() {
            const input = document.getElementById('referralLinkInput');
            input.select();
            input.setSelectionRange(0, 99999);
            document.execCommand('copy');
            
            Swal.fire({
                title: 'Copied!',
                text: 'Referral link copied to clipboard',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        }
        
        function shareOnWhatsApp(link) {
            const message = `🏥 Check out this amazing healthcare campaign on FreeDoctor! Join now and get quality medical care. ${link}`;
            const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(message)}`;
            window.open(whatsappUrl, '_blank');
        }
        
        function shareOnTwitter(link) {
            const message = `🏥 Amazing healthcare campaign on @FreeDoctor! Join now for quality medical care. ${link}`;
            const twitterUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(message)}`;
            window.open(twitterUrl, '_blank');
        }
        
        function shareGeneral(link) {
            const message = `🏥 Check out this healthcare campaign on FreeDoctor! ${link}`;
            
            if (navigator.share) {
                navigator.share({
                    title: 'Healthcare Campaign - FreeDoctor',
                    text: message,
                    url: link
                }).catch((error) => console.log('Share failed:', error));
            } else {
                copyReferralLink();
            }
        }
        
        function processReferralFromLocalStorage() {
            const referralData = localStorage.getItem('freedoctor_referral');
            
            if (referralData) {
                try {
                    const parsedData = JSON.parse(referralData);
                    
                    $.ajax({
                        url: '/api/process-referral-from-localstorage',
                        method: 'POST',
                        data: {
                            referral_code: parsedData.referral_code,
                            campaign_id: parsedData.campaign_id,
                            _token: '{{ csrf_token() }}'
                        },
                        headers: {
                            'Authorization': 'Bearer ' + $('meta[name="csrf-token"]').attr('content'),
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                // Clear localStorage after successful processing
                                localStorage.removeItem('freedoctor_referral');
                                console.log('Referral processed successfully');
                            }
                        },
                        error: function(xhr) {
                            console.error('Failed to process referral from localStorage:', xhr.responseText);
                        }
                    });
                } catch (e) {
                    console.error('Error parsing referral data from localStorage:', e);
                    localStorage.removeItem('freedoctor_referral');
                }
            }
        }

        // Initialize tooltips and other UI enhancements
        $('[data-toggle="tooltip"]').tooltip();

        // Add loading animation to buttons on click
        $('.btn-register, .btn-view').on('click', function() {
            $(this).addClass('loading');
            setTimeout(() => $(this).removeClass('loading'), 2000);
        });
    });
</script>
@endpush