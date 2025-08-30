@extends('doctor.master')

@section('title', 'My Profile - FreeDoctor')

@push('styles')
<!-- Material UI CDN -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/material-design-icons/4.0.0/iconfont/material-icons.min.css" rel="stylesheet">

<style>
    :root {
        --primary-color: #383F45;
        --secondary-color: #E7A51B;
        --background-color: #f8fafc;
        --surface-color: #ffffff;
        --text-primary: #212121;
        --text-secondary: #757575;
        --shadow-color: rgba(0, 0, 0, 0.12);
        --accent-color: #F7C873;
        --success-color: #4CAF50;
        --danger-color: #E53935;
        --border-radius: 16px;
        --sidebar-width: 260px;
    }

    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', 'Roboto', 'Segoe UI', system-ui, sans-serif;
        background: var(--background-color);
        color: var(--text-primary);
        line-height: 1.6;
    }

    .profile-container {
        min-height: 100vh;
        padding: 1.5rem;
        margin-top: 0;
        background: var(--background-color);
        max-width: 1200px;
        margin-left: auto;
        margin-right: auto;
    }

    .section-spacing {
        margin-bottom: 2rem;
    }

    .profile-card {
        background: var(--surface-color);
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .profile-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 28px rgba(0, 0, 0, 0.12), 0 10px 10px rgba(0, 0, 0, 0.08);
    }

    .profile-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, #4a5259 100%);
        color: var(--surface-color);
        padding: 1.5rem 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .profile-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
        opacity: 0.3;
    }

    .profile-image-container {
        position: relative;
        width: 100px;
        height: 100px;
        margin: 0 auto 1rem;
        transition: all 0.3s ease;
    }

    .profile-image-container:hover {
        transform: scale(1.05);
    }

    .profile-image {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid rgba(231, 165, 27, 0.3);
        box-shadow: var(--shadow-color) 0 8px 25px;
    }

    .default-avatar {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, var(--secondary-color) 0%, var(--accent-color) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--surface-color);
        font-size: 3rem;
        box-shadow: var(--shadow-color) 0 8px 25px;
    }

    .profile-image-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        opacity: 0;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--surface-color);
    }

    .profile-image-container:hover .profile-image-overlay {
        opacity: 1;
    }

    .profile-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        color: var(--surface-color) !important;
        text-align: center;
        position: relative;
        z-index: 2;
    }

    .profile-subtitle {
        font-size: 1.25rem;
        opacity: 0.9;
        margin-bottom: 1.5rem;
        color: rgba(255, 255, 255, 0.8) !important;
        text-align: center;
        position: relative;
        z-index: 2;
    }

    .profile-contact {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        justify-content: center;
        margin-bottom: 2rem;
        position: relative;
        z-index: 2;
    }

    .contact-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: rgba(255, 255, 255, 0.9) !important;
        font-size: 0.875rem;
    }

    .status-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        justify-content: center;
        position: relative;
        z-index: 2;
    }

    .status-badge {
        padding: 0.5rem 0.875rem;
        border-radius: 16px;
        font-size: 0.75rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border: 1px solid transparent;
        transition: all 0.2s ease;
    }

    .status-verified {
        background: rgba(76, 175, 80, 0.2);
        color: var(--success-color);
        border: 1px solid rgba(76, 175, 80, 0.3);
    }

    .status-pending {
        background: rgba(229, 57, 53, 0.2);
        color: var(--danger-color);
        border: 1px solid rgba(229, 57, 53, 0.3);
    }

    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1.5rem;
        background: linear-gradient(135deg, var(--primary-color) 0%, #4a5259 100%);
        margin: 0;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--surface-color) !important;
        margin: 0;
    }

    .section-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.125rem;
        color: var(--surface-color);
        background: linear-gradient(135deg, var(--secondary-color) 0%, var(--accent-color) 100%);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .card-body {
        padding: 1.5rem;
        background: var(--surface-color);
    }

    /* Profile Grid Layout */
    .profile-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.25rem;
        margin-bottom: 1rem;
    }

    .profile-item {
        background: rgba(231, 165, 27, 0.03);
        border: 1px solid rgba(231, 165, 27, 0.08);
        border-radius: 8px;
        padding: 1rem;
        transition: all 0.2s ease;
    }

    .profile-item:hover {
        background: rgba(231, 165, 27, 0.05);
        border-color: rgba(231, 165, 27, 0.15);
        transform: translateY(-1px);
    }

    .profile-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .profile-value {
        font-size: 0.95rem;
        font-weight: 500;
        color: var(--text-primary);
        line-height: 1.5;
    }

    .info-grid {
        display: grid;
        gap: 1.5rem;
    }

    .info-item {
        background: rgba(231, 165, 27, 0.05);
        border: 1px solid rgba(231, 165, 27, 0.1);
        border-radius: 12px;
        padding: 1rem;
        transition: all 0.3s ease;
    }

    .info-item:hover {
        background: rgba(231, 165, 27, 0.08);
        border-color: rgba(231, 165, 27, 0.2);
        transform: translateY(-2px);
    }

    .info-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-secondary) !important;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-value {
        font-size: 1rem;
        font-weight: 500;
        color: var(--text-primary) !important;
        line-height: 1.5;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .stat-card {
        background: var(--surface-color);
        border-radius: var(--border-radius);
        padding: 2rem;
        box-shadow: var(--shadow-color) 0 2px 8px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }

    .stat-card.campaigns::before {
        background: linear-gradient(135deg, var(--success-color) 0%, #66BB6A 100%);
    }

    .stat-card.patients::before {
        background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
    }

    .stat-card.pending::before {
        background: linear-gradient(135deg, #9C27B0 0%, #7B1FA2 100%);
    }

    .stat-card.status::before {
        background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-color) 0 8px 25px;
    }

    .stat-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .stat-text h3 {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--text-primary) !important;
        margin: 0;
        line-height: 1;
    }

    .stat-text p {
        font-size: 1rem;
        color: var(--text-secondary) !important;
        margin: 0.5rem 0 0 0;
        font-weight: 500;
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        color: var(--surface-color);
    }

    .stat-icon.campaigns {
        background: linear-gradient(135deg, var(--success-color) 0%, #66BB6A 100%);
    }

    .stat-icon.patients {
        background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
    }

    .stat-icon.pending {
        background: linear-gradient(135deg, #9C27B0 0%, #7B1FA2 100%);
    }

    .stat-icon.status {
        background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        border: none;
        font-family: inherit;
        letter-spacing: 0.025em;
        text-transform: none;
        min-width: 64px;
        box-sizing: border-box;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--secondary-color) 0%, var(--accent-color) 100%);
        color: var(--surface-color) !important;
        box-shadow: 0 2px 4px rgba(231, 165, 27, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(231, 165, 27, 0.4);
        background: linear-gradient(135deg, #d49619 0%, #f0c766 100%);
    }

    .btn-outline {
        background: transparent;
        border: 2px solid var(--secondary-color);
        color: var(--secondary-color) !important;
    }

    .btn-outline:hover {
        background: var(--secondary-color);
        color: var(--surface-color) !important;
        transform: translateY(-2px);
    }

    .video-container {
        position: relative;
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: var(--shadow-color) 0 8px 25px;
        background: #000;
    }

    .video-controls {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-top: 1rem;
    }

    .control-group {
        display: flex;
        gap: 0.75rem;
    }

    .time-display {
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    /* Modal Styles */
    .modal {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(5px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .modal.show {
        opacity: 1;
        visibility: visible;
    }

    .modal-content {
        background: var(--surface-color);
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-color) 0 25px 50px;
        width: 100%;
        max-width: 600px;
        max-height: 90vh;
        overflow-y: auto;
        margin: 1rem;
        transform: translateY(50px);
        transition: all 0.3s ease;
    }

    .modal.show .modal-content {
        transform: translateY(0);
    }

    .modal-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, #4a5259 100%);
        color: var(--surface-color);
        padding: 1.5rem 2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--surface-color) !important;
        margin: 0;
    }

    .modal-body {
        padding: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: var(--text-primary) !important;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .form-input {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: var(--surface-color);
        color: var(--text-primary) !important;
    }

    .form-input:focus {
        outline: none;
        border-color: var(--secondary-color);
        box-shadow: 0 0 0 3px rgba(231, 165, 27, 0.1);
    }

    .form-select {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: var(--surface-color);
        color: var(--text-primary) !important;
    }

    .form-textarea {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: var(--surface-color);
        color: var(--text-primary) !important;
        resize: vertical;
        min-height: 100px;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .animate-fade-in {
        animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Material UI Enhancements */
    .btn:active {
        transform: scale(0.98);
    }

    /* Enhanced Typography */
    h1, h2, h3, h4, h5, h6 {
        font-weight: 600;
        line-height: 1.2;
        margin: 0;
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
    }

    ::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.05);
    }

    ::-webkit-scrollbar-thumb {
        background: var(--secondary-color);
        border-radius: 3px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #d49619;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .profile-container {
            padding: 1rem;
            margin-top: 70px;
        }

        .profile-header {
            padding: 2rem 1.5rem;
        }

        .profile-title {
            font-size: 2rem;
        }

        .profile-contact {
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        .card-body {
            padding: 1rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .stat-text h3 {
            font-size: 2rem;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
        }

        .video-controls {
            flex-direction: column;
            align-items: stretch;
        }

        .control-group {
            justify-content: center;
        }

        .modal-content {
            margin: 0.5rem;
        }

        .modal-header {
            padding: 1rem 1.5rem;
        }

        .modal-body {
            padding: 1.5rem;
        }
    }

    @media (max-width: 480px) {
        .profile-container {
            padding: 0.5rem;
        }

        .profile-header {
            padding: 1.5rem 1rem;
        }

        .profile-title {
            font-size: 1.75rem;
        }

        .profile-container {
            padding: 0.75rem;
        }

        .section-header {
            padding: 1rem 1.5rem;
        }

        .card-body {
            padding: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="profile-container">
    <!-- Profile Header -->
    <div class="profile-card animate-fade-in section-spacing">
        <div class="profile-header">
            <div class="profile-image-container">
                @if(auth('doctor')->user()->image)
                    <img src="{{ asset('storage/' . auth('doctor')->user()->image) }}" 
                         alt="Profile" class="profile-image">
                @else
                    <div class="default-avatar">
                        <i class="fas fa-user-md"></i>
                    </div>
                @endif
                <div class="profile-image-overlay" onclick="showEditModal()">
                    <i class="fas fa-camera text-xl"></i>
                </div>
            </div>
            
            <h1 class="profile-title">Dr. {{ auth('doctor')->user()->doctor_name }}</h1>
            <p class="profile-subtitle">{{ auth('doctor')->user()->specialty->name ?? 'Medical Professional' }}</p>
            
            <div class="profile-contact">
                <div class="contact-item">
                    <i class="fas fa-envelope text-blue-400"></i>
                    <span>{{ auth('doctor')->user()->email }}</span>
                </div>
                @if(auth('doctor')->user()->phone)
                <div class="contact-item">
                    <i class="fas fa-phone text-green-400"></i>
                    <span>{{ auth('doctor')->user()->phone }}</span>
                </div>
                @endif
                @if(auth('doctor')->user()->location)
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt text-orange-400"></i>
                    <span>{{ auth('doctor')->user()->location }}</span>
                </div>
                @endif
            </div>
            
            <div class="status-badges">
                @if(auth('doctor')->user()->email_verified_at)
                    <span class="status-badge status-verified">
                        <i class="fas fa-check-circle"></i>Email Verified
                    </span>
                @else
                    <span class="status-badge status-pending">
                        <i class="fas fa-clock"></i>Email Pending
                    </span>
                @endif
                
                @if(auth('doctor')->user()->approved_by_admin)
                    <span class="status-badge status-verified">
                        <i class="fas fa-shield-check"></i>Admin Approved
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Doctor Intro Video Section -->
    <div class="section-spacing">
    @if(auth('doctor')->user()->intro_video)
    <div class="profile-card animate-fade-in">
        <div class="section-header">
            <div class="section-title">
                <div class="section-icon">
                    <i class="fas fa-video"></i>
                </div>
                <div>
                    <h2>Doctor Introduction</h2>
                    <p style="font-size: 0.875rem; margin: 0; opacity: 0.8;">Get to know Dr. {{ auth('doctor')->user()->doctor_name }}</p>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            <div class="video-container">
                <video 
                    id="introVideo"
                    class="w-full h-auto max-h-96" 
                    controls 
                    autoplay 
                    muted
                    playsinline
                    poster="{{ auth('doctor')->user()->image ? asset('storage/' . auth('doctor')->user()->image) : '' }}"
                >
                    <source src="{{ asset('storage/' . auth('doctor')->user()->intro_video) }}" type="video/mp4">
                    <source src="{{ asset('storage/' . auth('doctor')->user()->intro_video) }}" type="video/webm">
                    <p style="color: white;">Your browser does not support the video tag.</p>
                </video>
            </div>
            
            <div class="video-controls">
                <div class="control-group">
                    <button id="playPauseBtn" class="btn btn-outline">
                        <i class="fas fa-pause"></i>Pause
                    </button>
                    <button id="muteBtn" class="btn btn-outline">
                        <i class="fas fa-volume-up"></i>Mute
                    </button>
                    <button id="fullscreenBtn" class="btn btn-outline">
                        <i class="fas fa-expand"></i>Fullscreen
                    </button>
                </div>
                <div class="time-display">
                    <span id="currentTime">0:00</span> / <span id="duration">0:00</span>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- No Intro Video -->
    <div class="profile-card animate-fade-in" style="border: 2px dashed rgba(231, 165, 27, 0.3);">
        <div class="card-body" style="text-align: center; padding: 3rem 2rem;">
            <div class="section-icon" style="width: 80px; height: 80px; margin: 0 auto 2rem;">
                <i class="fas fa-video" style="font-size: 2rem;"></i>
            </div>
            <h2 style="color: var(--text-primary); margin-bottom: 1rem; font-size: 1.75rem;">Add Your Introduction Video</h2>
            <p style="color: var(--text-secondary); margin-bottom: 2rem; font-size: 1.125rem;">Let patients get to know you better with a personal introduction video</p>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                <div style="background: rgba(231, 165, 27, 0.05); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(231, 165, 27, 0.1);">
                    <i class="fas fa-user-md" style="color: var(--secondary-color); font-size: 1.5rem; margin-bottom: 1rem;"></i>
                    <h3 style="color: var(--text-primary); margin-bottom: 0.5rem;">Build Trust</h3>
                    <p style="color: var(--text-secondary); font-size: 0.875rem; margin: 0;">Help patients connect with you personally</p>
                </div>
                <div style="background: rgba(231, 165, 27, 0.05); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(231, 165, 27, 0.1);">
                    <i class="fas fa-heart" style="color: var(--secondary-color); font-size: 1.5rem; margin-bottom: 1rem;"></i>
                    <h3 style="color: var(--text-primary); margin-bottom: 0.5rem;">Show Care</h3>
                    <p style="color: var(--text-secondary); font-size: 0.875rem; margin: 0;">Express your commitment to healthcare</p>
                </div>
                <div style="background: rgba(231, 165, 27, 0.05); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(231, 165, 27, 0.1);">
                    <i class="fas fa-star" style="color: var(--secondary-color); font-size: 1.5rem; margin-bottom: 1rem;"></i>
                    <h3 style="color: var(--text-primary); margin-bottom: 0.5rem;">Stand Out</h3>
                    <p style="color: var(--text-secondary); font-size: 0.875rem; margin: 0;">Differentiate yourself from other doctors</p>
                </div>
            </div>
            
            <button onclick="showEditModal()" class="btn btn-primary" style="font-size: 1rem; padding: 1rem 2rem;">
                <i class="fas fa-video"></i>Upload Introduction Video
            </button>
            
            <div style="margin-top: 1.5rem;">
                <p style="color: var(--text-secondary); font-size: 0.875rem; margin: 0;">
                    <i class="fas fa-info-circle" style="margin-right: 0.5rem;"></i>Video should be 30-60 seconds, MP4 format, max 10MB
                </p>
            </div>
        </div>
    </div>
    @endif
    </div>

    <!-- Profile Information Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 section-spacing">
        <!-- Professional Details Card -->
        <div class="profile-card animate-fade-in">
            <div class="section-header">
                <div class="section-title">
                    <div class="section-icon">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <div>
                        <h2>Professional Details</h2>
                        <p style="font-size: 0.875rem; margin: 0; opacity: 0.8;">Medical credentials and specialty information</p>
                    </div>
                </div>
                <button id="editProfessionalBtn" class="btn btn-outline">
                    <i class="fas fa-edit"></i>
                </button>
            </div>
            
            <div class="card-body">
                @if(auth('doctor')->user()->specialty)
                <div class="profile-item featured" style="margin-bottom: 2rem;">
                    <div class="profile-label">
                        <i class="fas fa-stethoscope"></i>Specialization
                    </div>
                    <div class="profile-value">
                        <div style="font-size: 1.125rem; font-weight: 600; color: var(--text-primary); margin-bottom: 0.25rem;">
                            {{ auth('doctor')->user()->specialty->name ?? 'Not specified' }}
                        </div>
                        <div style="color: var(--secondary-color); font-size: 0.875rem; margin-bottom: 0.25rem;">
                            Specialty Code: #{{ auth('doctor')->user()->specialty->id }}
                        </div>
                        <div style="color: var(--text-secondary); font-size: 0.875rem;">
                            Since: {{ \Carbon\Carbon::parse(auth('doctor')->user()->specialty->created_at)->format('M Y') }}
                        </div>
                    </div>
                </div>
                @endif
                
                <div class="profile-grid" style="grid-template-columns: 1fr 1fr; margin-bottom: 1.5rem;">
                    <div class="profile-item">
                        <div class="profile-label">
                            <i class="fas fa-id-badge"></i>Doctor ID
                        </div>
                        <div class="profile-value">
                            <span style="font-weight: 700; color: var(--secondary-color); font-size: 1.125rem;">
                                #{{ str_pad(auth('doctor')->user()->id ?? '000', 3, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>
                    </div>
                    <div class="profile-item">
                        <div class="profile-label">
                            <i class="fas fa-circle" style="color: #10B981;"></i>Status
                        </div>
                        <div class="profile-value">
                            <span class="status-badge status-available">Active</span>
                        </div>
                    </div>
                </div>
                
                <div class="profile-grid">
                    <div class="profile-item">
                        <div class="profile-label">
                            <i class="fas fa-hospital"></i>Hospital Name
                        </div>
                        <div class="profile-value">
                            {{ auth('doctor')->user()->hospital_name ?? 'Not provided' }}
                        </div>
                    </div>
                    
                    <div class="profile-item">
                        <div class="profile-label">
                            <i class="fas fa-clock"></i>Experience
                        </div>
                        <div class="profile-value">
                            {{ auth('doctor')->user()->experience ?? 'Not specified' }} years
                        </div>
                    </div>
                    
                    <div class="profile-item">
                        <div class="profile-label">
                            <i class="fas fa-venus-mars"></i>Gender
                        </div>
                        <div class="profile-value">
                            {{ ucfirst(auth('doctor')->user()->gender ?? 'Not specified') }}
                        </div>
                    </div>
                    
                    <div class="profile-item">
                        <div class="profile-label">
                            <i class="fas fa-map-marker-alt"></i>Location
                        </div>
                        <div class="profile-value">
                            {{ auth('doctor')->user()->location ?? 'Not provided' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information Card -->
        <div class="profile-card">
            <div class="section-header">
                <div class="section-title">
                    <div class="section-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <h2>Contact Information</h2>
                        <p style="font-size: 0.875rem; margin: 0; opacity: 0.8;">Communication and contact details</p>
                    </div>
                </div>
                <button id="editContactBtn" class="btn btn-primary">
                    <i class="fas fa-edit"></i>Edit
                </button>
            </div>
            
            <div class="card-body">
                <div class="profile-grid">
                    <div class="profile-item">
                        <div class="profile-label">
                            <i class="fas fa-user-md"></i>Full Name
                        </div>
                        <div class="profile-value" style="font-weight: 600;">
                            Dr. {{ auth('doctor')->user()->doctor_name }}
                        </div>
                    </div>
                    
                    <div class="profile-item">
                        <div class="profile-label">
                            <i class="fas fa-envelope"></i>Email Address
                        </div>
                        <div class="profile-value">
                            {{ auth('doctor')->user()->email }}
                            <div style="margin-top: 0.5rem;">
                                @if(auth('doctor')->user()->email_verified_at)
                                    <span class="status-badge status-available">
                                        <i class="fas fa-check-circle"></i>Email Verified
                                    </span>
                                @else
                                    <span class="status-badge status-pending">
                                        <i class="fas fa-times-circle"></i>Email Not Verified
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    @if(auth('doctor')->user()->phone)
                    <div class="profile-item">
                        <div class="profile-label">
                            <i class="fas fa-phone"></i>Phone Number
                        </div>
                        <div class="profile-value">
                            {{ auth('doctor')->user()->phone }}
                        </div>
                    </div>
                    @endif
                    
                    @if(auth('doctor')->user()->location)
                    <div class="profile-item">
                        <div class="profile-label">
                            <i class="fas fa-map-marker-alt"></i>Location
                        </div>
                        <div class="profile-value">
                            {{ auth('doctor')->user()->location }}
                        </div>
                    </div>
                    @endif
                    
                    @if(auth('doctor')->user()->description)
                    <div class="profile-item" style="grid-column: 1 / -1;">
                        <div class="profile-label">
                            <i class="fas fa-info-circle"></i>About
                        </div>
                        <div class="profile-value">
                            <div style="background: rgba(231, 165, 27, 0.05); padding: 1rem; border-radius: 8px; border-left: 3px solid var(--secondary-color);">
                                {{ auth('doctor')->user()->description }}
                            </div>
                        </div>
                    </div>
                    @endif
            
            </div>
        </div>
    </div>

    <!-- Account Information & Verification Status -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 section-spacing">
        <!-- Account Information Card -->
        <div class="profile-card">
            <div class="section-header">
                <div class="section-title">
                    <div class="section-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div>
                        <h2>Account Information</h2>
                        <p style="font-size: 0.875rem; margin: 0; opacity: 0.8;">Membership and timeline details</p>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <div class="profile-grid">
                    <div class="profile-item">
                        <div class="profile-label">
                            <i class="fas fa-user-plus"></i>Member Since
                        </div>
                        <div class="profile-value" style="font-weight: 600;">
                            {{ \Carbon\Carbon::parse(auth('doctor')->user()->created_at)->format('M j, Y') }}
                        </div>
                    </div>
                    
                    <div class="profile-item">
                        <div class="profile-label">
                            <i class="fas fa-clock"></i>Platform Experience
                        </div>
                        <div class="profile-value">
                            {{ \Carbon\Carbon::parse(auth('doctor')->user()->created_at)->diffForHumans(null, true) }}
                        </div>
                    </div>
                    
                    @if(auth('doctor')->user()->updated_at)
                    <div class="profile-item">
                        <div class="profile-label">
                            <i class="fas fa-edit"></i>Last Profile Update
                        </div>
                        <div class="profile-value">
                            {{ \Carbon\Carbon::parse(auth('doctor')->user()->updated_at)->format('M j, Y') }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Verification Status Card -->
        <div class="profile-card">
            <div class="section-header">
                <div class="section-title">
                    <div class="section-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div>
                        <h2>Verification Status</h2>
                        <p style="font-size: 0.875rem; margin: 0; opacity: 0.8;">Account security and verification</p>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div class="verification-item">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                            <span style="color: var(--text-primary); font-weight: 500;">Email Verification</span>
                            @if(auth('doctor')->user()->email_verified_at)
                                <span class="status-badge status-available">
                                    <i class="fas fa-check-circle"></i>Verified
                                </span>
                            @else
                                <span class="status-badge status-pending">
                                    <i class="fas fa-times-circle"></i>Pending
                                </span>
                            @endif
                        </div>
                        @if(auth('doctor')->user()->email_verified_at)
                            <div style="color: var(--text-secondary); font-size: 0.875rem;">
                                Verified on {{ \Carbon\Carbon::parse(auth('doctor')->user()->email_verified_at)->format('M j, Y') }}
                            </div>
                        @endif
                    </div>
                    
                    <div class="verification-item">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: var(--text-primary); font-weight: 500;">Profile Completion</span>
                            <span class="status-badge status-available">
                                <i class="fas fa-user-check"></i>Complete
                            </span>
                        </div>
                    </div>
                    
                    <div class="verification-item">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: var(--text-primary); font-weight: 500;">Account Status</span>
                            <span class="status-badge status-available">
                                <i class="fas fa-check-circle"></i>Active
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Account Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 section-spacing">
        <div class="stats-card stats-success">
            <div class="stats-content">
                <div class="stats-text">
                    <div class="stats-label">Total Campaigns</div>
                    <div class="stats-value">{{ auth('doctor')->user()->campaigns()->count() }}</div>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-calendar-plus"></i>
                </div>
            </div>
        </div>
        
        <div class="stats-card stats-primary">
            <div class="stats-content">
                <div class="stats-text">
                    <div class="stats-label">Total Patients</div>
                    <div class="stats-value">{{ auth('doctor')->user()->campaigns()->withCount('patientRegistrations')->get()->sum('patient_registrations_count') }}</div>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        
        <div class="stats-card stats-warning">
            <div class="stats-content">
                <div class="stats-text">
                    <div class="stats-label">Pending Approvals</div>
                    <div class="stats-value">{{ auth('doctor')->user()->campaigns()->where('approval_status', 'pending')->count() }}</div>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
        
        <div class="stats-card stats-secondary">
            <div class="stats-content">
                <div class="stats-text">
                    <div class="stats-label">Account Status</div>
                    <div class="stats-value" style="font-size: 1rem;">
                        @if(auth('doctor')->user()->email_verified_at)
                            <span style="color: #10B981;">Verified</span>
                        @else
                            <span style="color: #EF4444;">Unverified</span>
                        @endif
                    </div>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modals -->

<!-- Edit Profile Modal -->
<div id="editProfileModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-title">
                <div class="modal-icon">
                    <i class="fas fa-edit"></i>
                </div>
                <h2>Edit Profile</h2>
            </div>
            <button onclick="closeEditModal()" class="modal-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="editProfileForm" action="{{ route('doctor.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Doctor Name</label>
                        <input type="text" name="doctor_name" value="{{ auth('doctor')->user()->doctor_name }}" 
                               class="form-input" placeholder="Enter your full name">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ auth('doctor')->user()->email }}" 
                               class="form-input" placeholder="Enter your email address">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" value="{{ auth('doctor')->user()->phone }}" 
                               class="form-input" placeholder="Enter your phone number">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-input">
                            <option value="">Select Gender</option>
                            <option value="male" {{ auth('doctor')->user()->gender == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ auth('doctor')->user()->gender == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ auth('doctor')->user()->gender == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Years of Experience</label>
                        <input type="number" name="experience" value="{{ auth('doctor')->user()->experience }}" min="0"
                               class="form-input" placeholder="Years of experience">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Specialty</label>
                        <select name="specialty_id" class="form-input">
                            <option value="">Select Specialty</option>
                            @foreach(\App\Models\Specialty::all() as $specialty)
                                <option value="{{ $specialty->id }}" {{ auth('doctor')->user()->specialty_id == $specialty->id ? 'selected' : '' }}>
                                    {{ $specialty->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Hospital Name</label>
                        <input type="text" name="hospital_name" value="{{ auth('doctor')->user()->hospital_name }}"
                               class="form-input" placeholder="Current hospital or clinic">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" value="{{ auth('doctor')->user()->location }}"
                               class="form-input" placeholder="City, State">
                    </div>
                    
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="form-label">About Me / Description</label>
                        <textarea name="description" rows="4" class="form-input" 
                                  placeholder="Tell patients about yourself, your experience, and your approach to healthcare">{{ auth('doctor')->user()->description }}</textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Profile Image</label>
                        <input type="file" name="image" accept="image/*" class="form-input">
                        <p style="color: var(--text-secondary); font-size: 0.875rem; margin-top: 0.5rem;">Leave empty to keep current image</p>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Intro Video (Optional)</label>
                        <input type="file" name="intro_video" accept="video/*" class="form-input">
                        <p style="color: var(--text-secondary); font-size: 0.875rem; margin-top: 0.5rem;">Upload an introduction video (optional)</p>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-full">
                    <i class="fas fa-save"></i>Save Changes
                </button>
                <button type="button" onclick="closeEditModal()" class="btn btn-outline btn-full">
                    <i class="fas fa-times"></i>Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Change Password Modal -->
<div id="passwordModal" class="modal-overlay">
    <div class="modal-container" style="max-width: 400px;">
        <div class="modal-header">
            <div class="modal-title">
                <div class="modal-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <h2>Change Password</h2>
            </div>
            <button onclick="closePasswordModal()" class="modal-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        </div>
        
        <form id="passwordForm" action="{{ route('doctor.profile.password.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Current Password</label>
                    <input type="password" name="current_password" required
                           class="form-input" placeholder="Enter your current password">
                </div>
                
                <div class="form-group">
                    <label class="form-label">New Password</label>
                    <input type="password" name="new_password" required
                           class="form-input" placeholder="Enter new password">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="new_password_confirmation" required
                           class="form-input" placeholder="Confirm new password">
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-full">
                    <i class="fas fa-key"></i>Update Password
                </button>
                <button type="button" onclick="closePasswordModal()" class="btn btn-outline btn-full">
                    <i class="fas fa-times"></i>Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showEditModal() {
    document.getElementById('editProfileModal').classList.remove('hidden');
    document.getElementById('editProfileModal').classList.add('active');
}

function closeEditModal() {
    document.getElementById('editProfileModal').classList.add('hidden');
    document.getElementById('editProfileModal').classList.remove('active');
}

function showPasswordModal() {
    document.getElementById('passwordModal').classList.remove('hidden');
    document.getElementById('passwordModal').classList.add('active');
}

function closePasswordModal() {
    document.getElementById('passwordModal').classList.add('hidden');
    document.getElementById('passwordModal').classList.remove('active');
}

// Make all edit buttons open the same modal
document.addEventListener('DOMContentLoaded', function() {
    const editButtons = document.querySelectorAll('#editProfessionalBtn, #editContactBtn');
    editButtons.forEach(button => {
        if (button) {
            button.addEventListener('click', function() {
                showEditModal();
            });
        }
    });
});

// Video controls functionality
document.addEventListener('DOMContentLoaded', function() {
    const video = document.getElementById('introVideo');
    const playPauseBtn = document.getElementById('playPauseBtn');
    const muteBtn = document.getElementById('muteBtn');
    const fullscreenBtn = document.getElementById('fullscreenBtn');
    const currentTimeSpan = document.getElementById('currentTime');
    const durationSpan = document.getElementById('duration');
    const videoOverlay = document.getElementById('videoOverlay');
    
    if (video) {
        // Auto-play with sound after user interaction
        video.addEventListener('canplay', function() {
            setTimeout(() => {
                video.muted = false; // Unmute after autoplay starts
            }, 1000);
        });
        
        // Update time display
        video.addEventListener('timeupdate', function() {
            if (currentTimeSpan) {
                currentTimeSpan.textContent = formatTime(video.currentTime);
            }
        });
        
        // Set duration when metadata loads
        video.addEventListener('loadedmetadata', function() {
            if (durationSpan) {
                durationSpan.textContent = formatTime(video.duration);
            }
        });
        
        // Play/Pause button
        if (playPauseBtn) {
            playPauseBtn.addEventListener('click', function() {
                if (video.paused) {
                    video.play();
                    this.innerHTML = '<i class="fas fa-pause"></i>Pause';
                } else {
                    video.pause();
                    this.innerHTML = '<i class="fas fa-play"></i>Play';
                }
            });
        }
        
        // Mute button
        if (muteBtn) {
            muteBtn.addEventListener('click', function() {
                video.muted = !video.muted;
                this.innerHTML = video.muted 
                    ? '<i class="fas fa-volume-mute"></i>Unmute'
                    : '<i class="fas fa-volume-up"></i>Mute';
            });
        }
        
        // Fullscreen button
        if (fullscreenBtn) {
            fullscreenBtn.addEventListener('click', function() {
                if (video.requestFullscreen) {
                    video.requestFullscreen();
                } else if (video.webkitRequestFullscreen) {
                    video.webkitRequestFullscreen();
                } else if (video.mozRequestFullScreen) {
                    video.mozRequestFullScreen();
                } else if (video.msRequestFullscreen) {
                    video.msRequestFullscreen();
                }
            });
        }
        
        // Video click to play/pause
        video.addEventListener('click', function() {
            if (video.paused) {
                video.play();
                if (playPauseBtn) {
                    playPauseBtn.innerHTML = '<i class="fas fa-pause"></i>Pause';
                }
            } else {
                video.pause();
                if (playPauseBtn) {
                    playPauseBtn.innerHTML = '<i class="fas fa-play"></i>Play';
                }
            }
        });
        
        // Show overlay when video ends
        video.addEventListener('ended', function() {
            if (videoOverlay) {
                videoOverlay.classList.remove('hidden');
            }
            if (playPauseBtn) {
                playPauseBtn.innerHTML = '<i class="fas fa-play"></i>Play';
            }
        });
        
        // Hide overlay when video plays
        video.addEventListener('play', function() {
            if (videoOverlay) {
                videoOverlay.classList.add('hidden');
            }
        });
        
        // Overlay click to play
        if (videoOverlay) {
            videoOverlay.addEventListener('click', function() {
                video.play();
                this.classList.add('hidden');
            });
        }
    }
    
    // Format time helper function
    function formatTime(seconds) {
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = Math.floor(seconds % 60);
        return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
    }
});

// Handle form submissions with AJAX for better UX
document.getElementById('editProfileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const button = this.querySelector('button[type="submit"]');
    const originalText = button.innerHTML;
    
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>Saving...';
    button.disabled = true;
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Profile Updated!',
                text: 'Your profile has been updated successfully.',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        } else {
            throw new Error(data.message || 'Failed to update profile');
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: error.message || 'Failed to update profile. Please try again.',
        });
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
});

document.getElementById('passwordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const button = this.querySelector('button[type="submit"]');
    const originalText = button.innerHTML;
    
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>Updating...';
    button.disabled = true;
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Password Updated!',
                text: 'Your password has been updated successfully.',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                closePasswordModal();
                this.reset();
            });
        } else {
            throw new Error(data.message || 'Failed to update password');
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: error.message || 'Failed to update password. Please try again.',
        });
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection
