@extends('../user.master')

@section('title', 'Medical Campaigns - FreeDoctor')

@section('content')

<!-- Campaign Page Styles -->
<style>
/* ===== CAMPAIGNS PAGE STYLES ===== */
:root {
    --primary-color: #2C2A4C;
    --secondary-color: #E7A51B;
    --background-color: #f5f5f5;
    --surface-color: #ffffff;
    --text-primary: #212121;
    --text-secondary: #757575;
    --shadow-color: rgba(0, 0, 0, 0.12);
    --accent-color: #F7C873;
    --success-color: #4CAF50;
    --danger-color: #E53935;
    --border-radius: 16px;
    --warning-color: #FF9800;
    --info-color: #2196F3;
    --white: #ffffff;
    --light-gray: #f8f9fa;
    --border-color: #e9ecef;
    --text-dark: #2d3748;
    --text-muted: #6c757d;
    --shadow-sm: 0 2px 4px rgba(0,0,0,0.1);
    --shadow-md: 0 4px 8px rgba(0,0,0,0.15);
    --shadow-lg: 0 8px 16px rgba(0,0,0,0.2);
    --radius-sm: 8px;
    --radius-md: 12px;
    --radius-lg: 16px;
}

/* Page Header */
.campaigns-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, #4a47a3 100%);
    color: var(--white);
    padding: 4rem 0 3rem;
    position: relative;
    overflow: hidden;
}

.campaigns-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
    animation: float 20s infinite linear;
}

@keyframes float {
    0% { transform: translateY(0); }
    100% { transform: translateY(-100px); }
}

.campaigns-header h1 {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 1rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.campaigns-header p {
    font-size: 1.25rem;
    opacity: 0.9;
    margin-bottom: 0;
}

/* Search Section */
.search-section {
    background: linear-gradient(135deg, var(--primary-color) 0%, #3a3564 100%);
    padding: 2rem 1rem;
    position: relative;
    overflow: hidden;
}

.search-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
    opacity: 0.3;
}

.search-card {
    background: var(--surface-color);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    box-shadow: 0 8px 32px var(--shadow-color);
    backdrop-filter: blur(10px);
    position: relative;
    z-index: 2;
    margin: 0 auto;
    max-width: 1200px;
}

.search-compact {
    display: flex;
    gap: 0.5rem;
    align-items: center;
    width: 100%;
    flex-wrap: nowrap;
}

.search-input-compact {
    flex: 1;
    position: relative;
    min-width: 0;
}

.search-input-compact input {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    font-size: 1rem;
    background: var(--surface-color);
    color: var(--text-primary);
    transition: all 0.3s ease;
    outline: none;
}

.search-input-compact input:focus {
    border-color: var(--secondary-color);
    box-shadow: 0 0 0 3px rgba(231, 165, 27, 0.1);
}

.search-input-compact input::placeholder {
    color: var(--text-secondary);
}

.location-btn-compact,
.reset-btn-compact {
    background: var(--primary-color);
    color: white;
    border: none;
    padding: 0.875rem 1rem;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
    white-space: nowrap;
    position: relative;
    min-width: 44px;
    justify-content: center;
    flex-shrink: 0;
}

.location-btn-compact:hover {
    background: var(--secondary-color);
    color: var(--primary-color);
    transform: translateY(-1px);
}

.reset-btn-compact {
    background: var(--danger-color);
}

.reset-btn-compact:hover {
    background: #c62828;
    transform: translateY(-1px);
}

.btn-text {
    font-size: 0.9rem;
    font-weight: 500;
}

.filter-count {
    background: var(--danger-color);
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    font-size: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    position: absolute;
    top: -5px;
    right: -5px;
    font-weight: 600;
}

/* Active Filters */
.active-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #e0e0e0;
}

.filter-badge-compact {
    background: var(--primary-color);
    color: white;
    padding: 0.5rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.clear-all-filters {
    background: var(--danger-color);
    color: white;
    border: none;
    padding: 0.5rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    transition: all 0.3s ease;
}

.clear-all-filters:hover {
    background: #c62828;
    transform: translateY(-1px);
}

/* Search Suggestions */
.search-suggestions {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: var(--surface-color);
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    box-shadow: 0 4px 12px var(--shadow-color);
    z-index: 1000;
    max-height: 300px;
    overflow-y: auto;
    display: none;
}

.suggestion-item {
    padding: 0.75rem 1rem;
    cursor: pointer;
    border-bottom: 1px solid #f0f0f0;
    transition: background 0.2s ease;
}

.suggestion-item:hover {
    background: var(--background-color);
}

.suggestion-item:last-child {
    border-bottom: none;
}

/* Modal Styles - Only for location and filter modals */
.location-modal,
.filter-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 10000;
    backdrop-filter: blur(5px);
}

.modal-content-compact {
    background: var(--surface-color);
    border-radius: var(--border-radius);
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: modalSlideIn 0.3s ease;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-20px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.modal-header-compact {
    padding: 1.5rem 1.5rem 1rem;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.modal-header-compact h3 {
    color: var(--primary-color);
    margin: 0;
    font-size: 1.25rem;
    font-weight: 700;
}

.modal-close {
    background: none;
    border: none;
    color: var(--text-secondary);
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.modal-close:hover {
    background: var(--background-color);
    color: var(--text-primary);
}

.modal-body-compact {
    padding: 1.5rem;
}

.modal-footer-compact {
    padding: 1rem 1.5rem 1.5rem;
    border-top: 1px solid #e0e0e0;
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
}

/* Location Modal Specific */
.location-search-container {
    position: relative;
    margin-bottom: 1rem;
}

.location-search-input {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    font-size: 1rem;
    outline: none;
    transition: border-color 0.3s ease;
}

.location-search-input:focus {
    border-color: var(--secondary-color);
}

.location-suggestions {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: var(--surface-color);
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    box-shadow: 0 4px 12px var(--shadow-color);
    z-index: 1001;
    max-height: 200px;
    overflow-y: auto;
    display: none;
}

.current-location-btn {
    width: 100%;
    background: var(--accent-color);
    color: var(--primary-color);
    border: none;
    padding: 0.875rem 1rem;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.current-location-btn:hover {
    background: var(--secondary-color);
    transform: translateY(-1px);
}

/* Filter Modal Specific */
.filter-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1rem;
}

.filter-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.filter-item label {
    color: var(--text-primary);
    font-weight: 600;
    font-size: 0.9rem;
}

.filter-item select,
.filter-item input {
    padding: 0.75rem 1rem;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 1rem;
    outline: none;
    transition: border-color 0.3s ease;
}

.filter-item select:focus,
.filter-item input:focus {
    border-color: var(--secondary-color);
}

/* Button Styles */
.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    font-size: 0.9rem;
}

.btn-primary {
    background: var(--primary-color);
    color: white;
}

.btn-primary:hover {
    background: #3a3564;
    transform: translateY(-1px);
}

.btn-secondary {
    background: var(--text-secondary);
    color: white;
}

.btn-secondary:hover {
    background: #616161;
}

/* Campaign Layout Styles - Optimized for space */
.compact-layout {
    display: flex;
    flex-direction: column;
    height: 100%;
    border-radius: 16px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(255, 255, 255, 0.18);
}

.compact-layout:hover {
    transform: translateY(-8px);
    box-shadow: 0 16px 48px rgba(0, 0, 0, 0.2);
    border-color: #667eea;
}

/* Full Width Image Section */
.campaign-image-full {
    width: 100%;
    height: 160px;
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    cursor: pointer;
}

.campaign-image-responsive {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.campaign-image-full:hover .campaign-image-responsive {
    transform: scale(1.05);
}

.campaign-placeholder {
    height: 100%;
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}

.video-container-full {
    width: 100%;
    height: 100%;
    position: relative;
}

.video-container-full iframe,
.video-container-full video {
    width: 100%;
    height: 100%;
    border: none;
}

/* Content Section - Optimized */
.campaign-content-full {
    padding: 12px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.campaign-title-full {
    font-size: 1.2rem;
    font-weight: 700;
    color: #2C2A4C;
    line-height: 1.3;
    margin-bottom: 10px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Enhanced Detail Grid - Compact */
.campaign-detail-grid {
    margin-bottom: 10px;
}

.detail-row {
    display: flex;
    gap: 12px;
    margin-bottom: 4px;
}

.detail-item-full {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.75rem;
    color: #4a5568;
    font-weight: 500;
    flex: 1;
}

.detail-item-full.location-full {
    flex: 2;
}

.detail-item-full i {
    width: 14px;
    text-align: center;
    font-size: 0.85rem;
}

/* Description - Compact */
.campaign-description-full {
    font-size: 0.85rem;
    color: #6c757d;
    line-height: 1.4;
    margin-bottom: 12px;
    background: #f8f9fa;
    padding: 8px;
    border-radius: 6px;
    border-left: 3px solid #667eea;
}

/* Action Buttons Row - Compact */
.campaign-actions-row {
    display: flex;
    gap: 6px;
    margin-bottom: 12px;
}

.btn-action {
    flex: 1;
    padding: 8px 12px;
    border: none;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 600;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    text-decoration: none;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.btn-action:hover {
    transform: translateY(-2px);
    text-decoration: none;
}

.btn-register-full {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    box-shadow: 0 3px 12px rgba(40, 167, 69, 0.3);
}

.btn-register-full:hover {
    background: linear-gradient(135deg, #20c997, #28a745);
    box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
    color: white;
}

.btn-sponsor-full {
    background: linear-gradient(135deg, #fd7e14, #ffc107);
    color: white;
    box-shadow: 0 3px 12px rgba(253, 126, 20, 0.3);
}

.btn-sponsor-full:hover {
    background: linear-gradient(135deg, #ffc107, #fd7e14);
    box-shadow: 0 6px 20px rgba(253, 126, 20, 0.4);
    color: white;
}

.btn-share-full {
    background: linear-gradient(135deg, #6f42c1, #e83e8c);
    color: white;
    box-shadow: 0 3px 12px rgba(111, 66, 193, 0.3);
}

.btn-share-full:hover {
    background: linear-gradient(135deg, #e83e8c, #6f42c1);
    box-shadow: 0 6px 20px rgba(111, 66, 193, 0.4);
    color: white;
}

/* Progress Section - Compact */
.progress-section-full {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 10px;
    border-radius: 10px;
    border: 1px solid #e9ecef;
}

.progress-item-full {
    margin-bottom: 6px;
}

.progress-item-full:last-child {
    margin-bottom: 0;
}

.progress-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 4px;
}

.progress-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #2C2A4C;
}

.progress-value {
    font-size: 0.75rem;
    font-weight: 700;
    color: #667eea;
}

.progress-bar-full {
    height: 5px;
    background: #e9ecef;
    border-radius: 3px;
    overflow: hidden;
    margin-bottom: 3px;
}

.progress-fill {
    height: 100%;
    border-radius: 3px;
    transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
}

.registration-progress {
    background: linear-gradient(90deg, #28a745, #20c997);
}

.sponsorship-progress {
    background: linear-gradient(90deg, #fd7e14, #ffc107);
}

/* Enhanced Badges */
.full-width-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    padding: 4px 10px;
    border-radius: 15px;
    font-size: 0.7rem;
    font-weight: 600;
    background: rgba(255, 255, 255, 0.95);
    color: #2C2A4C;
    backdrop-filter: blur(10px);
    z-index: 2;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.full-width-cost-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 4px 10px;
    border-radius: 15px;
    font-size: 0.7rem;
    font-weight: 600;
    z-index: 2;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
}

.cost-free {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
}

.cost-paid {
    background: linear-gradient(135deg, #2C2A4C, #667eea);
    color: white;
}

/* Space Optimization */
.campaign-container .row {
    margin-bottom: 0;
    row-gap: 12px;
}

.campaign-container {
    padding-top: 1.5rem;
    padding-bottom: 0.5rem;
}

.campaigns-header {
    padding: 1.5rem 0 0.5rem;
}

.search-section {
    padding: 0.5rem 0 1.5rem;
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
    .campaigns-header h1 {
        font-size: 2rem;
    }
    
    .campaigns-header p {
        font-size: 1rem;
    }
    
    .search-card {
        margin-top: -2rem;
        padding: 1rem;
    }
    
    .search-compact {
        gap: 0.5rem;
        flex-wrap: nowrap;
    }
    
    .search-input-compact {
        flex: 1;
        min-width: 0;
    }
    
    .search-input-compact input {
        font-size: 16px;
        padding: 0.75rem 0.875rem;
    }
    
    .location-btn-compact,
    .reset-btn-compact {
        padding: 0.75rem 0.875rem;
        flex-shrink: 0;
        min-width: 44px;
    }
    
    .btn-text {
        font-size: 0.85rem;
    }
    
    .reset-btn-compact .btn-text {
        display: none;
    }
    
    .modal-content-compact {
        width: 95%;
        margin: 1rem;
    }
    
    .modal-header-compact,
    .modal-body-compact,
    .modal-footer-compact {
        padding: 1rem;
    }
    
    .filter-grid {
        grid-template-columns: 1fr;
    }
    
    .modal-footer-compact {
        flex-direction: column;
    }
    
    .modal-footer-compact .btn {
        width: 100%;
    }
    
    .detail-row {
        flex-direction: column;
        gap: 6px;
    }
    
    .btn-action span {
        display: none;
    }
}

@media (max-width: 576px) {
    .campaign-image-full {
        height: 150px;
    }
    
    .campaign-content-full {
        padding: 10px;
    }
    
    .campaign-title-full {
        font-size: 1.1rem;
    }
    
    .btn-action {
        padding: 6px 8px;
        font-size: 0.75rem;
    }
}

@media (min-width: 769px) {
    .search-compact {
        max-width: 1000px;
        margin: 0 auto;
    }
    
    .filter-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .btn-text {
        display: block;
    }
}

@media (min-width: 1024px) {
    .search-section {
        padding: 1rem 2rem;
    }
    
    .search-card {
        padding: 1.5rem;
    }
    
    .search-input-compact input {
        padding: 0.875rem 1rem;
        font-size: 1rem;
    }
    
    .location-btn-compact,
    .reset-btn-compact {
        padding: 0.875rem 1rem;
    }
}
</style>

<!-- Campaign Page Header -->
<section class="campaigns-header">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="fade-in">Medical Campaigns</h1>
                <p class="fade-in">Discover healthcare campaigns in your area and join thousands of patients receiving quality medical care</p>
            </div>
        </div>
    </div>
</section>

<!-- Search Section -->
<section class="search-section">
    <div class="container">
        <div class="search-card slide-up">
            <div class="search-compact">
                <!-- Main Search Input -->
                <div class="search-input-compact">
                    <input type="text" 
                           id="campaignSearchMain"
                           placeholder="Search campaigns, doctors, specialties..."
                           value="{{ request('search') }}"
                           autocomplete="off">
                    <div id="searchSuggestions" class="search-suggestions"></div>
                </div>
                
                <!-- Location Button -->
                <button type="button" id="openLocationModal" class="location-btn-compact" title="Set Location">
                    <i class="fas fa-search"></i>
                    <i class="fas fa-map-marker-alt"></i>
                    <span class="btn-text">
                        @if(request('location'))
                            {{ Str::limit(request('location'), 10) }}
                        @else
                            Location
                        @endif
                    </span>
                </button>
                
                <!-- Reset Button -->
                @if(request()->hasAny(['search', 'location', 'specialty', 'type', 'status', 'registration']))
                    <button type="button" id="resetAllFilters" class="reset-btn-compact" title="Clear All">
                        <i class="fas fa-times"></i>
                        <span class="btn-text">Reset</span>
                    </button>
                @endif
            </div>
            
            <!-- Active Filters Display -->
            @if(request()->hasAny(['search', 'location', 'specialty', 'type', 'status', 'registration']))
                <div class="active-filters">
                    @if(request('search'))
                        <span class="filter-badge-compact">
                            <i class="fas fa-search"></i>
                            Search: {{ request('search') }}
                        </span>
                    @endif
                    @if(request('location'))
                        <span class="filter-badge-compact">
                            <i class="fas fa-map-marker-alt"></i>
                            {{ request('location') }}
                        </span>
                    @endif
                    @if(request('specialty'))
                        <span class="filter-badge-compact">
                            <i class="fas fa-stethoscope"></i>
                            Specialty
                        </span>
                    @endif
                    @if(request('type'))
                        <span class="filter-badge-compact">
                            <i class="fas fa-tag"></i>
                            {{ ucfirst(request('type')) }}
                        </span>
                    @endif
                    @if(request('status'))
                        <span class="filter-badge-compact">
                            <i class="fas fa-clock"></i>
                            {{ ucfirst(request('status')) }}
                        </span>
                    @endif
                    @if(request('registration'))
                        <span class="filter-badge-compact">
                            <i class="fas fa-money-bill-wave"></i>
                            {{ ucfirst(request('registration')) }}
                        </span>
                    @endif
                    <button type="button" class="clear-all-filters">
                        <i class="fas fa-times"></i>
                        Clear All
                    </button>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- Campaigns Grid Section -->
<section class="campaign-container">
    <div class="container">
        @if($campaigns->count() > 0)
            <div class="row">
                @foreach($campaigns as $campaign)
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                        <div class="compact-layout">
                            <!-- Campaign Image Section -->
                            <div class="campaign-image-full" onclick="navigateToCampaign({{ $campaign->id }})">
                                <!-- Category Badge -->
                                @if($campaign->category)
                                    <div class="full-width-badge">
                                        <i class="{{ $campaign->category->icon_class ?? 'fas fa-medical-clinic' }}"></i>
                                        {{ $campaign->category->category_name }}
                                    </div>
                                @endif
                                
                                <!-- Cost Badge -->
                                <div class="full-width-cost-badge {{ $campaign->registration_payment > 0 ? 'cost-paid' : 'cost-free' }}">
                                    @if($campaign->registration_payment > 0)
                                        ₹{{ number_format($campaign->registration_payment) }}
                                    @else
                                        FREE
                                    @endif
                                </div>
                                
                                @if($campaign->thumbnail && \Storage::exists('public/' . $campaign->thumbnail))
                                    <img src="{{ asset('storage/' . $campaign->thumbnail) }}" 
                                         alt="{{ $campaign->title }}" 
                                         class="campaign-image-responsive">
                                @elseif($campaign->video_link && !empty($campaign->video_link))
                                    <div class="video-container-full">
                                        @if($campaign->is_video_external ?? false)
                                            @if(str_contains($campaign->video_link, 'youtube.com') || str_contains($campaign->video_link, 'youtu.be'))
                                                @php
                                                    $videoId = '';
                                                    if (str_contains($campaign->video_link, 'youtube.com/watch?v=')) {
                                                        $videoId = substr($campaign->video_link, strpos($campaign->video_link, 'v=') + 2);
                                                    } elseif (str_contains($campaign->video_link, 'youtu.be/')) {
                                                        $videoId = substr($campaign->video_link, strrpos($campaign->video_link, '/') + 1);
                                                    }
                                                    if (str_contains($videoId, '&')) {
                                                        $videoId = substr($videoId, 0, strpos($videoId, '&'));
                                                    }
                                                @endphp
                                                <img src="https://img.youtube.com/vi/{{ $videoId }}/maxresdefault.jpg" 
                                                     alt="{{ $campaign->title }}" 
                                                     class="campaign-image-responsive">
                                                <div class="video-overlay">
                                                    <i class="fas fa-play-circle"></i>
                                                </div>
                                            @else
                                                <div class="campaign-placeholder d-flex align-items-center justify-content-center">
                                                    <div class="text-center">
                                                        <i class="fas fa-video fa-3x mb-2"></i>
                                                        <p class="mb-0">Video Available</p>
                                                    </div>
                                                </div>
                                            @endif
                                        @else
                                            <video class="campaign-video" controls>
                                                <source src="{{ asset('storage/' . $campaign->video_link) }}" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        @endif
                                    </div>
                                @else
                                    <div class="campaign-placeholder d-flex align-items-center justify-content-center">
                                        <div class="text-center">
                                            <i class="fas fa-image fa-3x mb-2"></i>
                                            <p class="mb-0">No Image</p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Campaign Content -->
                            <div class="campaign-content-full">
                                <!-- Title -->
                                <h3 class="campaign-title-full">{{ $campaign->title }}</h3>

                                <!-- Campaign Details Grid -->
                                <div class="campaign-detail-grid">
                                    <div class="detail-row">
                                        <div class="detail-item-full">
                                            <i class="fas fa-user-md" style="color: #667eea;"></i>
                                            <span>{{ $campaign->doctor->doctor_name ?? 'TBD' }}</span>
                                        </div>
                                        <div class="detail-item-full">
                                            <i class="fas fa-stethoscope" style="color: #20c997;"></i>
                                            <span>{{ $campaign->doctor->specialty->name ?? 'General' }}</span>
                                        </div>
                                    </div>
                                    <div class="detail-row">
                                        <div class="detail-item-full">
                                            <i class="fas fa-calendar" style="color: #fd7e14;"></i>
                                            <span>{{ \Carbon\Carbon::parse($campaign->start_date)->format('M j, Y') }}</span>
                                        </div>
                                        <div class="detail-item-full">
                                            <i class="fas fa-clock" style="color: #6f42c1;"></i>
                                            <span>{{ $campaign->timings ?? 'TBD' }}</span>
                                        </div>
                                    </div>
                                    <div class="detail-row">
                                        <div class="detail-item-full location-full">
                                            <i class="fas fa-map-marker-alt" style="color: #e83e8c;"></i>
                                            <span>{{ $campaign->location }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="campaign-description-full">
                                    {{ Str::limit($campaign->description, 120) }}
                                </div>

                                <!-- Action Buttons -->
                                <div class="campaign-actions-row">
                                    @php
                                        $totalRegistered = $campaign->patientRegistrations ? $campaign->patientRegistrations->count() : 0;
                                        $expectedPatients = $campaign->expected_patients ?? 100;
                                        $isRegistrationOpen = $totalRegistered < $expectedPatients;
                                    @endphp
                                    
                                    @if($isRegistrationOpen)
                                        <a href="{{ route('user.campaigns.register', $campaign->id) }}" class="btn-action btn-register-full">
                                            <i class="fas fa-user-plus"></i>
                                            <span>Register</span>
                                        </a>
                                    @endif
                                    
                                    <a href="{{ route('user.campaigns.sponsor', $campaign->id) }}" class="btn-action btn-sponsor-full">
                                        <i class="fas fa-hand-holding-heart"></i>
                                        <span>Sponsor</span>
                                    </a>

                                    <button onclick="shareCampaign({{ $campaign->id }}, '{{ $referralId ?? 'guest' }}')" class="btn-action btn-share-full">
                                        <i class="fas fa-share-alt"></i>
                                        <span>Share</span>
                                    </button>
                                </div>

                                <!-- Progress Bars Section -->
                                <div class="progress-section-full">
                                    <!-- Registration Progress -->
                                    <div class="progress-item-full">
                                        <div class="progress-header">
                                            <span class="progress-label">Registration</span>
                                            <span class="progress-value">{{ $totalRegistered }}/{{ $expectedPatients }}</span>
                                        </div>
                                        <div class="progress-bar-full">
                                            @php
                                                $registrationProgress = min(($totalRegistered / $expectedPatients) * 100, 100);
                                            @endphp
                                            <div class="progress-fill registration-progress" style="width: {{ $registrationProgress }}%"></div>
                                        </div>
                                    </div>

                                    <!-- Sponsorship Progress -->
                                    <div class="progress-item-full">
                                        @php
                                            $totalSponsored = $campaign->campaignSponsors ? $campaign->campaignSponsors->sum('amount') : 0;
                                            $targetAmount = $campaign->target_amount ?? 10000;
                                            $sponsorshipProgress = min(($totalSponsored / $targetAmount) * 100, 100);
                                        @endphp
                                        <div class="progress-header">
                                            <span class="progress-label">Sponsorship</span>
                                            <span class="progress-value">₹{{ number_format($totalSponsored) }}/₹{{ number_format($targetAmount) }}</span>
                                        </div>
                                        <div class="progress-bar-full">
                                            <div class="progress-fill sponsorship-progress" style="width: {{ $sponsorshipProgress }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($campaigns->hasPages())
                <div class="row mt-4">
                    <div class="col-12 d-flex justify-content-center">
                        {{ $campaigns->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        @else
            <!-- No Results -->
            <div class="row">
                <div class="col-12">
                    <div class="no-results">
                        <i class="fas fa-search"></i>
                        <h3>No Campaigns Found</h3>
                        <p>We couldn't find any campaigns matching your criteria. Try adjusting your search filters or browse all available campaigns.</p>
                        <a href="{{ route('user.campaigns') }}" class="btn btn-primary">Browse All Campaigns</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Location Modal -->
<div id="locationModal" class="location-modal">
    <div class="modal-content-compact">
        <div class="modal-header-compact">
            <h3><i class="fas fa-map-marker-alt me-2"></i>Set Your Location</h3>
            <button class="modal-close" onclick="closeLocationModal()">&times;</button>
        </div>
        <div class="modal-body-compact">
            <div class="location-search-container">
                <input type="text" id="locationSearchInput" class="location-search-input" 
                       placeholder="Enter city, area, or address..."
                       value="{{ request('location') }}">
                <div id="locationSuggestions" class="location-suggestions"></div>
            </div>
            <button type="button" id="useCurrentLocation" class="current-location-btn">
                <i class="fas fa-crosshairs"></i>
                Use Current Location
            </button>
        </div>
        <div class="modal-footer-compact">
            <button type="button" onclick="closeLocationModal()" class="btn btn-secondary">Cancel</button>
            <button type="button" onclick="applyLocationFilter()" class="btn btn-primary">Apply</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Global variables
let searchTimeout;
let userLatitude = null;
let userLongitude = null;

// Navigation functions
function navigateToCampaign(campaignId) {
    window.location.href = `/user/campaigns/${campaignId}/view`;
}

// Share Campaign Function
function shareCampaign(campaignId, referralId = 'guest') {
    const baseUrl = window.location.origin;
    const campaignUrl = `${baseUrl}/user/campaigns/${campaignId}/view`;
    const referralUrl = referralId !== 'guest' ? `${campaignUrl}?ref=${referralId}` : campaignUrl;
    
    Swal.fire({
        title: 'Share Campaign',
        html: `
            <div class="share-content">
                <p class="mb-3">Share this campaign with others:</p>
                <div class="share-url-container mb-3">
                    <input type="text" id="shareUrl" class="form-control" value="${referralUrl}" readonly>
                    <button onclick="copyToClipboard()" class="btn btn-outline-primary btn-sm mt-2">
                        <i class="fas fa-copy"></i> Copy Link
                    </button>
                </div>
                <div class="social-share-buttons">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(referralUrl)}" 
                       target="_blank" class="btn btn-primary btn-sm me-2">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=${encodeURIComponent(referralUrl)}&text=Check out this amazing medical campaign!" 
                       target="_blank" class="btn btn-info btn-sm me-2">
                        <i class="fab fa-twitter"></i> Twitter
                    </a>
                    <a href="https://wa.me/?text=Check out this medical campaign: ${encodeURIComponent(referralUrl)}" 
                       target="_blank" class="btn btn-success btn-sm">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>
                </div>
            </div>
        `,
        showConfirmButton: false,
        showCloseButton: true,
        width: '500px'
    });
}

// Copy to clipboard function
function copyToClipboard() {
    const shareUrl = document.getElementById('shareUrl');
    shareUrl.select();
    shareUrl.setSelectionRange(0, 99999);
    
    try {
        document.execCommand('copy');
        Swal.fire({
            title: 'Copied!',
            text: 'Campaign link copied to clipboard',
            icon: 'success',
            timer: 1500,
            showConfirmButton: false
        });
    } catch (err) {
        console.error('Failed to copy: ', err);
        Swal.fire({
            title: 'Error',
            text: 'Failed to copy link. Please copy manually.',
            icon: 'error'
        });
    }
}

// Search functionality
document.getElementById('campaignSearchMain').addEventListener('input', function(e) {
    clearTimeout(searchTimeout);
    const query = e.target.value.trim();
    
    if (query.length > 0) {
        searchTimeout = setTimeout(() => {
            performSearch(query);
        }, 300);
    } else {
        hideSearchSuggestions();
    }
});

function performSearch(query) {
    // This would typically make an AJAX call to get suggestions
    // For now, we'll just redirect to search results
    if (query.length > 2) {
        const url = new URL(window.location.href);
        url.searchParams.set('search', query);
        window.location.href = url.toString();
    }
}

function hideSearchSuggestions() {
    document.getElementById('searchSuggestions').style.display = 'none';
}

// Location modal functions
function openLocationModal() {
    document.getElementById('locationModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeLocationModal() {
    document.getElementById('locationModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function applyLocationFilter() {
    const location = document.getElementById('locationSearchInput').value.trim();
    if (location) {
        const url = new URL(window.location.href);
        url.searchParams.set('location', location);
        window.location.href = url.toString();
    }
}

// Current location functionality
document.getElementById('useCurrentLocation').addEventListener('click', function() {
    if (navigator.geolocation) {
        Swal.fire({
            title: 'Getting Location...',
            text: 'Please allow location access to find campaigns near you.',
            didOpen: Swal.showLoading,
            allowOutsideClick: false
        });

        navigator.geolocation.getCurrentPosition(
            function(position) {
                userLatitude = position.coords.latitude;
                userLongitude = position.coords.longitude;
                
                // Reverse geocoding would happen here
                // For now, we'll just set a generic location
                document.getElementById('locationSearchInput').value = 'Current Location';
                
                Swal.close();
                Swal.fire({
                    title: 'Location Found!',
                    text: 'Your current location has been set.',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                });
            },
            function(error) {
                Swal.close();
                Swal.fire({
                    title: 'Location Error',
                    text: 'Unable to get your current location. Please enter manually.',
                    icon: 'error'
                });
            }
        );
    } else {
        Swal.fire({
            title: 'Location Not Supported',
            text: 'Your browser does not support location services.',
            icon: 'error'
        });
    }
});

// Reset filters
document.getElementById('resetAllFilters')?.addEventListener('click', function() {
    window.location.href = '{{ route("user.campaigns") }}';
});

// Clear all filters
document.querySelector('.clear-all-filters')?.addEventListener('click', function() {
    window.location.href = '{{ route("user.campaigns") }}';
});

// Close modals when clicking outside
window.addEventListener('click', function(e) {
    if (e.target.classList.contains('location-modal')) {
        closeLocationModal();
    }
});

// Close modals with escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeLocationModal();
    }
});

// Animation classes for elements as they come into view
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('fade-in');
        }
    });
}, observerOptions);

// Observe campaign cards for animation
document.querySelectorAll('.compact-layout').forEach(card => {
    observer.observe(card);
});

</script>
@endpush
