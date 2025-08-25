{{-- 
    Simple Campaigns List Partial
    
    A lightweight partial for displaying campaign cards without search functionality.
    Perfect for embedding in other pages or sections.
    
    Usage:
    @include('user.partials.campaigns-list-simple', [
        'campaigns' => $campaigns,
        'showActions' => true,
        'showProgress' => true,
        'columns' => 3 // Bootstrap columns (1-6)
    ])
--}}

@php
    // Set default values if not provided
    $showActions = $showActions ?? true;
    $showProgress = $showProgress ?? true;
    $columns = $columns ?? 3; // Default to 3 columns (col-lg-4)
    $campaigns = $campaigns ?? collect();
    $uniqueId = 'simple_campaigns_' . uniqid();
@endphp

<style>
/* ===== SIMPLE CAMPAIGNS LIST STYLES ===== */
.campaigns-simple-container {
    --primary-color: #2C2A4C;
    --secondary-color: #E7A51B;
    --surface-color: #ffffff;
    --text-primary: #212121;
    --text-secondary: #757575;
    --success-color: #4CAF50;
    --danger-color: #E53935;
    --info-color: #2196F3;
    --text-dark: #2d3748;
    --text-muted: #6c757d;
    --shadow-sm: 0 2px 4px rgba(0,0,0,0.1);
    --shadow-md: 0 4px 8px rgba(0,0,0,0.15);
    --shadow-lg: 0 8px 16px rgba(0,0,0,0.2);
    --radius-sm: 8px;
    --radius-md: 12px;
    --radius-lg: 16px;
    --border-color: #e9ecef;
    --light-gray: #f8f9fa;
}

.simple-campaign-card {
    background: var(--surface-color);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
    border: 1px solid var(--border-color);
    margin-bottom: 1.5rem;
}

.simple-campaign-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary-color);
}

.simple-campaign-image {
    position: relative;
    height: 180px;
    overflow: hidden;
    cursor: pointer;
}

.simple-campaign-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.simple-campaign-card:hover .simple-campaign-image img {
    transform: scale(1.05);
}

.simple-campaign-status {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
    padding: 0.25rem 0.75rem;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    z-index: 2;
}

.simple-status-ongoing { background: var(--success-color); color: white; }
.simple-status-upcoming { background: var(--info-color); color: white; }
.simple-status-completed { background: var(--text-muted); color: white; }

.simple-campaign-content {
    padding: 1.25rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.simple-campaign-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 0.75rem;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.simple-campaign-meta {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
    font-size: 0.85rem;
    color: var(--text-muted);
}

.simple-meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.simple-meta-item i {
    color: var(--primary-color);
    width: 14px;
    font-size: 0.85rem;
}

.simple-campaign-description {
    color: var(--text-muted);
    font-size: 0.9rem;
    line-height: 1.5;
    margin-bottom: 1rem;
    flex: 1;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.simple-progress-section {
    background: var(--light-gray);
    padding: 0.75rem;
    border-radius: var(--radius-sm);
    margin-bottom: 1rem;
}

.simple-progress-item {
    margin-bottom: 0.5rem;
}

.simple-progress-item:last-child {
    margin-bottom: 0;
}

.simple-progress-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.25rem;
}

.simple-progress-label {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--text-dark);
}

.simple-progress-value {
    font-size: 0.8rem;
    font-weight: 700;
    color: var(--primary-color);
}

.simple-progress-bar {
    height: 6px;
    background: #e9ecef;
    border-radius: 3px;
    overflow: hidden;
}

.simple-progress-fill {
    height: 100%;
    border-radius: 3px;
    transition: width 0.8s ease;
}

.simple-registration-progress { background: linear-gradient(90deg, #28a745, #20c997); }
.simple-sponsorship-progress { background: linear-gradient(90deg, #fd7e14, #ffc107); }

.simple-campaign-actions {
    display: flex;
    gap: 0.5rem;
    margin-top: auto;
}

.simple-btn-primary {
    background: linear-gradient(135deg, var(--primary-color) 0%, #4a47a3 100%);
    color: white;
    border: none;
    padding: 0.65rem 1.25rem;
    border-radius: var(--radius-sm);
    font-weight: 600;
    text-decoration: none;
    text-align: center;
    transition: all 0.3s ease;
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    font-size: 0.85rem;
}

.simple-btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
    color: white;
    text-decoration: none;
}

.simple-btn-secondary {
    background: white;
    color: var(--primary-color);
    border: 2px solid var(--primary-color);
    padding: 0.65rem 1.25rem;
    border-radius: var(--radius-sm);
    font-weight: 600;
    text-decoration: none;
    text-align: center;
    transition: all 0.3s ease;
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    font-size: 0.85rem;
}

.simple-btn-secondary:hover {
    background: var(--primary-color);
    color: white;
    text-decoration: none;
    transform: translateY(-1px);
}

.simple-no-campaigns {
    text-align: center;
    padding: 3rem 1.5rem;
    color: var(--text-muted);
}

.simple-no-campaigns i {
    font-size: 3rem;
    margin-bottom: 1rem;
    color: var(--border-color);
}

.simple-no-campaigns h4 {
    font-size: 1.25rem;
    margin-bottom: 0.5rem;
    color: var(--text-dark);
}

.simple-no-campaigns p {
    font-size: 0.95rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .simple-campaign-actions {
        flex-direction: column;
    }
    
    .simple-campaign-meta {
        gap: 0.25rem;
    }
    
    .simple-meta-item {
        font-size: 0.8rem;
    }
}

/* Animation */
.simple-fade-in {
    animation: simpleFadeIn 0.5s ease-out;
}

@keyframes simpleFadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<div class="campaigns-simple-container" id="{{ $uniqueId }}">
    @if($campaigns && $campaigns->count() > 0)
        <div class="row">
            @foreach($campaigns as $campaign)
                @php
                    $today = now();
                    $status = 'upcoming';
                    if ($campaign->start_date <= $today && $campaign->end_date >= $today) {
                        $status = 'ongoing';
                    } elseif ($campaign->end_date < $today) {
                        $status = 'completed';
                    }
                    
                    // Calculate progress
                    $totalRegistered = $campaign->total_registered ?? ($campaign->patientRegistrations ? $campaign->patientRegistrations->count() : 0);
                    $expectedPatients = $campaign->expected_patients ?? 100;
                    $registrationProgress = $expectedPatients > 0 ? round(($totalRegistered / $expectedPatients) * 100) : 0;
                    
                    $sponsorCollection = $campaign->campaignSponsors ?? $campaign->sponsors ?? collect();
                    $totalSponsored = $campaign->total_sponsored ?? $sponsorCollection->where('payment_status', 'success')->sum('amount');
                    $sponsorshipTarget = $campaign->target_amount ?? $campaign->amount ?? 10000;
                    $sponsorshipProgress = $sponsorshipTarget > 0 ? round(($totalSponsored / $sponsorshipTarget) * 100) : 0;
                    
                    $registrationFee = $campaign->registration_payment ?? 0;
                    $isFree = $registrationFee == 0;
                    
                    // Determine column class
                    $colClass = match($columns) {
                        1 => 'col-12',
                        2 => 'col-md-6',
                        3 => 'col-md-6 col-lg-4',
                        4 => 'col-md-6 col-lg-3',
                        5 => 'col-md-6 col-lg-2-4', // Custom class for 5 columns
                        6 => 'col-md-4 col-lg-2',
                        default => 'col-md-6 col-lg-4'
                    };
                @endphp
                
                <div class="{{ $colClass }} simple-fade-in">
                    <div class="simple-campaign-card">
                        <!-- Campaign Image -->
                        <div class="simple-campaign-image" onclick="window.location.href='{{ route('user.campaigns.view', $campaign->id) }}'">
                            @if($campaign->campaign_image)
                                <img src="{{ asset('storage/' . $campaign->campaign_image) }}" 
                                     alt="{{ $campaign->title }}">
                            @elseif($campaign->campaign_video_url && strpos($campaign->campaign_video_url, 'youtube.com') !== false)
                                @php
                                    $videoId = '';
                                    if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\n?#]+)/', $campaign->campaign_video_url, $matches)) {
                                        $videoId = $matches[1];
                                    }
                                @endphp
                                @if($videoId)
                                    <img src="https://img.youtube.com/vi/{{ $videoId }}/hqdefault.jpg" alt="{{ $campaign->title }}">
                                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; font-size: 2.5rem; opacity: 0.9;">
                                        <i class="fas fa-play-circle"></i>
                                    </div>
                                @endif
                            @else
                                <div style="height: 100%; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; display: flex; align-items: center; justify-content: center; font-size: 2rem;">
                                    <i class="fas fa-medical-bag"></i>
                                </div>
                            @endif

                            <!-- Status Badge -->
                            <div class="simple-campaign-status simple-status-{{ $status }}">{{ ucfirst($status) }}</div>
                        </div>

                        <!-- Campaign Content -->
                        <div class="simple-campaign-content">
                            <h4 class="simple-campaign-title">{{ $campaign->title }}</h4>
                            
                            <!-- Campaign Meta -->
                            <div class="simple-campaign-meta">
                                @if($campaign->doctor)
                                    <div class="simple-meta-item">
                                        <i class="fas fa-user-md"></i>
                                        <span>Dr. {{ $campaign->doctor->doctor_name }}</span>
                                    </div>
                                @endif
                                
                                @if($campaign->location)
                                    <div class="simple-meta-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>{{ Str::limit($campaign->location, 30) }}</span>
                                    </div>
                                @endif
                                
                                @if($campaign->start_date)
                                    <div class="simple-meta-item">
                                        <i class="fas fa-calendar"></i>
                                        <span>{{ \Carbon\Carbon::parse($campaign->start_date)->format('M d, Y') }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Campaign Description -->
                            @if($campaign->description)
                                <p class="simple-campaign-description">{{ Str::limit($campaign->description, 100) }}</p>
                            @endif

                            @if($showProgress && ($registrationProgress > 0 || $sponsorshipProgress > 0))
                                <!-- Progress Section -->
                                <div class="simple-progress-section">
                                    @if($registrationProgress > 0)
                                        <div class="simple-progress-item">
                                            <div class="simple-progress-header">
                                                <span class="simple-progress-label">Registrations</span>
                                                <span class="simple-progress-value">{{ $totalRegistered }}/{{ $expectedPatients }}</span>
                                            </div>
                                            <div class="simple-progress-bar">
                                                <div class="simple-progress-fill simple-registration-progress" style="width: {{ min($registrationProgress, 100) }}%"></div>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if($sponsorshipProgress > 0)
                                        <div class="simple-progress-item">
                                            <div class="simple-progress-header">
                                                <span class="simple-progress-label">Sponsorship</span>
                                                <span class="simple-progress-value">₹{{ number_format($totalSponsored/1000, 1) }}k/₹{{ number_format($sponsorshipTarget/1000, 1) }}k</span>
                                            </div>
                                            <div class="simple-progress-bar">
                                                <div class="simple-progress-fill simple-sponsorship-progress" style="width: {{ min($sponsorshipProgress, 100) }}%"></div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            @if($showActions)
                                <!-- Campaign Actions -->
                                <div class="simple-campaign-actions">
                                    <a href="{{ route('user.campaigns.view', $campaign->id) }}" class="simple-btn-primary">
                                        <i class="fas fa-eye"></i>
                                        View Details
                                    </a>
                                    
                                    @if($isFree)
                                        <a href="{{ route('user.campaigns.view', $campaign->id) }}" class="simple-btn-secondary">
                                            <i class="fas fa-user-plus"></i>
                                            Register Free
                                        </a>
                                    @else
                                        <a href="{{ route('user.campaigns.view', $campaign->id) }}" class="simple-btn-secondary">
                                            <i class="fas fa-rupee-sign"></i>
                                            ₹{{ number_format($registrationFee) }}
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="simple-no-campaigns">
            <i class="fas fa-medical-bag"></i>
            <h4>No campaigns available</h4>
            <p>There are currently no medical campaigns to display.</p>
        </div>
    @endif
</div>

@if($columns == 5)
<style>
/* Custom 5-column layout */
@media (min-width: 992px) {
    .col-lg-2-4 {
        flex: 0 0 20%;
        max-width: 20%;
    }
}
</style>
@endif
