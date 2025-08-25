{{-- 
    Enhanced Campaigns List Partial
    
    This partial extracts the campaigns listing functionality from the main campaigns page
    and provides a reusable component for displaying campaign cards with search functionality.
    
    Usage:
    @include('user.partials.campaigns-list-enhanced', [
        'campaigns' => $campaigns,
        'showSearch' => true,
        'showFilters' => true,
        'layoutMode' => 'grid', // or 'list'
        'specialties' => $specialties ?? []
    ])
--}}

@php
    // Set default values if not provided
    $showSearch = $showSearch ?? true;
    $showFilters = $showFilters ?? true;
    $layoutMode = $layoutMode ?? 'grid';
    $specialties = $specialties ?? [];
    $campaigns = $campaigns ?? collect();
    $uniqueId = 'campaigns_' . uniqid(); // Unique ID for this instance
@endphp

<style>
/* ===== CAMPAIGNS LIST PARTIAL STYLES ===== */
.campaigns-partial-container {
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

/* Search Section Styles */
.campaigns-search-section {
    background: linear-gradient(135deg, var(--primary-color) 0%, #3a3564 100%);
    padding: 1.5rem;
    border-radius: var(--border-radius);
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
}

.campaigns-search-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
    opacity: 0.3;
}

.search-card-partial {
    background: var(--surface-color);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    box-shadow: 0 8px 32px var(--shadow-color);
    backdrop-filter: blur(10px);
    position: relative;
    z-index: 2;
}

.search-inputs-row-partial {
    display: flex;
    gap: 1rem;
    align-items: center;
    margin-bottom: 1rem;
}

.search-input-partial {
    flex: 1;
    position: relative;
}

.search-input-partial input {
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

.search-input-partial input:focus {
    border-color: var(--secondary-color);
    box-shadow: 0 0 0 3px rgba(231, 165, 27, 0.1);
}

.location-btn-partial,
.filter-btn-partial {
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
}

.location-btn-partial:hover,
.filter-btn-partial:hover {
    background: var(--secondary-color);
    color: var(--primary-color);
    transform: translateY(-1px);
}

/* Campaign Card Styles */
.campaign-card-partial {
    background: var(--white);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
    border: 1px solid var(--border-color);
    margin-bottom: 2rem;
}

.campaign-card-partial:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary-color);
}

.campaign-image-partial {
    position: relative;
    height: 200px;
    overflow: hidden;
    cursor: pointer;
}

.campaign-image-partial img,
.campaign-image-partial .campaign-video,
.campaign-image-partial iframe.campaign-video {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.campaign-card-partial:hover .campaign-image-partial img,
.campaign-card-partial:hover .campaign-image-partial .campaign-video {
    transform: scale(1.05);
}

.campaign-status-partial {
    position: absolute;
    top: 1rem;
    right: 1rem;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-ongoing { background: var(--success-color); color: var(--white); }
.status-upcoming { background: var(--info-color); color: var(--white); }
.status-completed { background: var(--text-muted); color: var(--white); }

.campaign-type-partial {
    position: absolute;
    top: 1rem;
    left: 1rem;
    background: rgba(0,0,0,0.8);
    color: var(--white);
    padding: 0.25rem 0.75rem;
    border-radius: 50px;
    font-size: 0.75rem;
    text-transform: capitalize;
}

.campaign-content-partial {
    padding: 1.5rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.campaign-title-partial {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 0.75rem;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.campaign-meta-partial {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1rem;
    font-size: 0.9rem;
    color: var(--text-muted);
}

.meta-item-partial {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.meta-item-partial i {
    color: var(--primary-color);
    width: 16px;
}

.campaign-description-partial {
    color: var(--text-muted);
    font-size: 0.95rem;
    line-height: 1.6;
    margin-bottom: 1.5rem;
    flex: 1;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.campaign-actions-partial {
    display: flex;
    gap: 0.75rem;
    margin-top: auto;
}

.btn-primary-partial {
    background: linear-gradient(135deg, var(--primary-color) 0%, #4a47a3 100%);
    color: var(--white);
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: var(--radius-sm);
    font-weight: 600;
    text-decoration: none;
    text-align: center;
    transition: all 0.3s ease;
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-primary-partial:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    color: var(--white);
    text-decoration: none;
}

.btn-secondary-partial {
    background: var(--white);
    color: var(--primary-color);
    border: 2px solid var(--primary-color);
    padding: 0.75rem 1.5rem;
    border-radius: var(--radius-sm);
    font-weight: 600;
    text-decoration: none;
    text-align: center;
    transition: all 0.3s ease;
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-secondary-partial:hover {
    background: var(--primary-color);
    color: var(--white);
    text-decoration: none;
    transform: translateY(-2px);
}

/* No Results */
.no-results-partial {
    text-align: center;
    padding: 4rem 2rem;
    color: var(--text-muted);
}

.no-results-partial i {
    font-size: 4rem;
    margin-bottom: 1.5rem;
    color: var(--border-color);
}

.no-results-partial h3 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
    color: var(--text-dark);
}

/* Responsive Design */
@media (max-width: 768px) {
    .search-inputs-row-partial {
        flex-direction: column;
        align-items: stretch;
    }
    
    .campaign-actions-partial {
        flex-direction: column;
    }
}

/* Progress Bars */
.progress-section-partial {
    background: var(--light-gray);
    padding: 1rem;
    border-radius: var(--radius-sm);
    margin-bottom: 1rem;
}

.progress-item-partial {
    margin-bottom: 0.75rem;
}

.progress-item-partial:last-child {
    margin-bottom: 0;
}

.progress-header-partial {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.progress-label-partial {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-dark);
}

.progress-value-partial {
    font-size: 0.875rem;
    font-weight: 700;
    color: var(--primary-color);
}

.progress-bar-partial {
    height: 8px;
    background: #e9ecef;
    border-radius: 4px;
    overflow: hidden;
}

.progress-fill-partial {
    height: 100%;
    border-radius: 4px;
    transition: width 0.8s ease;
}

.registration-progress { background: linear-gradient(90deg, #28a745, #20c997); }
.sponsorship-progress { background: linear-gradient(90deg, #fd7e14, #ffc107); }
</style>

<div class="campaigns-partial-container" id="{{ $uniqueId }}">
    @if($showSearch)
    <!-- Search Section -->
    <div class="campaigns-search-section">
        <div class="search-card-partial">
            <div class="search-inputs-row-partial">
                <!-- Search Input -->
                <div class="search-input-partial">
                    <input type="text" 
                           id="campaignSearch_{{ $uniqueId }}"
                           placeholder="Search campaigns by title, doctor, or specialty..."
                           autocomplete="off">
                    <i class="fas fa-search search-icon-partial" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-secondary); pointer-events: none;"></i>
                </div>
                
                <!-- Location Input -->
                <div class="search-input-partial">
                    <input type="text" 
                           id="locationSearch_{{ $uniqueId }}"
                           placeholder="Your location for distance calculation..."
                           autocomplete="off">
                    <i class="fas fa-map-marker-alt location-icon-partial" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-secondary); pointer-events: none;"></i>
                </div>
                
                @if($showFilters)
                <!-- Filter Button -->
                <button type="button" class="filter-btn-partial" onclick="openFilterModal_{{ $uniqueId }}()">
                    <i class="fas fa-filter"></i>
                    <span>Filters</span>
                </button>
                @endif
            </div>
            
            <!-- Active Filters Display -->
            <div id="activeFilters_{{ $uniqueId }}" class="active-filters" style="display: none;"></div>
        </div>
    </div>
    @endif

    @if($showFilters)
    <!-- Filter Modal -->
    <div id="filterModal_{{ $uniqueId }}" class="filter-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 10000; align-items: center; justify-content: center;">
        <div class="modal-content-compact" style="background: var(--white); border-radius: var(--border-radius); width: 90%; max-width: 500px; max-height: 90vh; overflow-y: auto;">
            <div class="modal-header-compact" style="padding: 1.5rem; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; background: var(--primary-color); color: var(--white); border-radius: var(--border-radius) var(--border-radius) 0 0;">
                <h3 style="margin: 0; font-size: 1.25rem; font-weight: 600;"><i class="fas fa-filter me-2"></i>Filter Campaigns</h3>
                <button type="button" class="modal-close" onclick="closeFilterModal_{{ $uniqueId }}()" style="background: none; border: none; color: var(--white); font-size: 1.5rem; cursor: pointer; padding: 0; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; transition: background 0.3s ease;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body-compact" style="padding: 1.5rem;">
                <form id="filterForm_{{ $uniqueId }}">
                    <div class="filter-grid" style="display: grid; gap: 1rem;">
                        <div class="filter-item" style="display: flex; flex-direction: column; gap: 0.5rem;">
                            <label for="filterSpecialty_{{ $uniqueId }}" style="font-weight: 600; color: var(--text-dark); font-size: 0.9rem;">Specialty</label>
                            <select id="filterSpecialty_{{ $uniqueId }}" name="specialty" style="padding: 0.75rem; border: 1px solid var(--border-color); border-radius: var(--radius-sm); background: var(--white); font-size: 0.9rem;">
                                <option value="">All Specialties</option>
                                @foreach($specialties as $specialty)
                                    <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="filter-item" style="display: flex; flex-direction: column; gap: 0.5rem;">
                            <label for="filterType_{{ $uniqueId }}" style="font-weight: 600; color: var(--text-dark); font-size: 0.9rem;">Campaign Type</label>
                            <select id="filterType_{{ $uniqueId }}" name="type" style="padding: 0.75rem; border: 1px solid var(--border-color); border-radius: var(--radius-sm); background: var(--white); font-size: 0.9rem;">
                                <option value="">All Types</option>
                                <option value="medical">Medical</option>
                                <option value="surgical">Surgical</option>
                            </select>
                        </div>
                        
                        <div class="filter-item" style="display: flex; flex-direction: column; gap: 0.5rem;">
                            <label for="filterStatus_{{ $uniqueId }}" style="font-weight: 600; color: var(--text-dark); font-size: 0.9rem;">Status</label>
                            <select id="filterStatus_{{ $uniqueId }}" name="status" style="padding: 0.75rem; border: 1px solid var(--border-color); border-radius: var(--radius-sm); background: var(--white); font-size: 0.9rem;">
                                <option value="">All Status</option>
                                <option value="upcoming">Upcoming</option>
                                <option value="ongoing">Ongoing</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        
                        <div class="filter-item" style="display: flex; flex-direction: column; gap: 0.5rem;">
                            <label for="filterRegistration_{{ $uniqueId }}" style="font-weight: 600; color: var(--text-dark); font-size: 0.9rem;">Registration</label>
                            <select id="filterRegistration_{{ $uniqueId }}" name="registration" style="padding: 0.75rem; border: 1px solid var(--border-color); border-radius: var(--radius-sm); background: var(--white); font-size: 0.9rem;">
                                <option value="">All Campaigns</option>
                                <option value="free">Free</option>
                                <option value="paid">Paid</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer-compact" style="padding: 1rem 1.5rem; border-top: 1px solid var(--border-color); display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" onclick="clearFilters_{{ $uniqueId }}()" class="btn btn-outline-danger" style="background: transparent; color: var(--danger-color); border: 2px solid var(--danger-color); padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">Clear All</button>
                <button type="button" onclick="closeFilterModal_{{ $uniqueId }}()" class="btn btn-secondary" style="background: var(--text-secondary); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">Cancel</button>
                <button type="button" onclick="applyFilters_{{ $uniqueId }}()" class="btn btn-primary" style="background: var(--primary-color); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">Apply Filters</button>
            </div>
        </div>
    </div>
    @endif

    <!-- Campaigns Grid -->
    <div class="campaigns-grid-partial">
        @if($campaigns && $campaigns->count() > 0)
            <!-- Results Info -->
            @if(method_exists($campaigns, 'total'))
            <div class="row mb-3">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            Showing {{ $campaigns->firstItem() }} - {{ $campaigns->lastItem() }} of {{ $campaigns->total() }} campaigns
                        </h5>
                        <div class="text-muted">
                            Page {{ $campaigns->currentPage() }} of {{ $campaigns->lastPage() }}
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="row" id="campaignsGrid_{{ $uniqueId }}">
                @forelse($campaigns as $campaign)
                    @php
                        $today = now();
                        $status = 'upcoming';
                        if ($campaign->start_date <= $today && $campaign->end_date >= $today) {
                            $status = 'ongoing';
                        } elseif ($campaign->end_date < $today) {
                            $status = 'completed';
                        }
                        
                        // Calculate registration progress
                        $totalRegistered = $campaign->total_registered ?? ($campaign->patientRegistrations ? $campaign->patientRegistrations->count() : 0);
                        $expectedPatients = $campaign->expected_patients ?? 100;
                        $registrationProgress = $expectedPatients > 0 ? round(($totalRegistered / $expectedPatients) * 100) : 0;
                        
                        // Calculate sponsorship progress
                        $sponsorCollection = $campaign->campaignSponsors ?? $campaign->sponsors ?? collect();
                        $totalSponsored = $campaign->total_sponsored ?? $sponsorCollection->where('payment_status', 'success')->sum('amount');
                        $sponsorshipTarget = $campaign->target_amount ?? $campaign->amount ?? 10000;
                        $sponsorshipProgress = $sponsorshipTarget > 0 ? round(($totalSponsored / $sponsorshipTarget) * 100) : 0;
                        
                        $registrationFee = $campaign->registration_payment ?? 0;
                        $isFree = $registrationFee == 0;
                    @endphp
                    
                    <div class="col-md-6 col-lg-4 mb-4 campaign-item-partial"
                         data-title="{{ strtolower($campaign->title) }}"
                         data-location="{{ strtolower($campaign->location) }}"
                         data-type="{{ $campaign->camp_type ?? '' }}"
                         data-doctor="{{ strtolower($campaign->doctor->doctor_name ?? '') }}"
                         data-specializations="{{ $campaign->doctor && $campaign->doctor->specialty ? strtolower($campaign->doctor->specialty->name) : '' }}">

                        <div class="campaign-card-partial">
                            <!-- Campaign Image -->
                            <div class="campaign-image-partial" onclick="window.location.href='{{ route('user.campaigns.view', $campaign->id) }}'">
                                @if($campaign->campaign_image)
                                    <img src="{{ asset('storage/' . $campaign->campaign_image) }}" 
                                         alt="{{ $campaign->title }}"
                                         class="campaign-image-responsive">
                                @elseif($campaign->campaign_video_url)
                                    @if(strpos($campaign->campaign_video_url, 'youtube.com') !== false || strpos($campaign->campaign_video_url, 'youtu.be') !== false)
                                        @php
                                            $videoId = '';
                                            if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\n?#]+)/', $campaign->campaign_video_url, $matches)) {
                                                $videoId = $matches[1];
                                            }
                                        @endphp
                                        @if($videoId)
                                            <div class="video-container-partial" style="position: relative; width: 100%; height: 100%;">
                                                <img src="https://img.youtube.com/vi/{{ $videoId }}/hqdefault.jpg" 
                                                     alt="{{ $campaign->title }}"
                                                     style="width: 100%; height: 100%; object-fit: cover;">
                                                <div class="video-overlay" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; font-size: 3rem; opacity: 0.9; text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);">
                                                    <i class="fas fa-play-circle"></i>
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                        <video style="width: 100%; height: 100%; object-fit: cover;" muted>
                                            <source src="{{ $campaign->campaign_video_url }}" type="video/mp4">
                                        </video>
                                    @endif
                                @else
                                    <div class="campaign-placeholder" style="height: 100%; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; display: flex; align-items: center; justify-content: center; font-size: 2rem;">
                                        <i class="fas fa-medical-bag"></i>
                                    </div>
                                @endif

                                <!-- Status Badge -->
                                <div class="campaign-status-partial status-{{ $status }}">{{ ucfirst($status) }}</div>
                                
                                <!-- Campaign Type Badge -->
                                @if($campaign->camp_type)
                                    <div class="campaign-type-partial">{{ $campaign->camp_type }}</div>
                                @endif
                            </div>

                            <!-- Campaign Content -->
                            <div class="campaign-content-partial">
                                <h3 class="campaign-title-partial">{{ $campaign->title }}</h3>
                                
                                <!-- Campaign Meta Information -->
                                <div class="campaign-meta-partial">
                                    @if($campaign->doctor)
                                        <div class="meta-item-partial">
                                            <i class="fas fa-user-md"></i>
                                            <span>Dr. {{ $campaign->doctor->doctor_name }}</span>
                                        </div>
                                    @endif
                                    
                                    @if($campaign->location)
                                        <div class="meta-item-partial">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span>{{ $campaign->location }}</span>
                                        </div>
                                    @endif
                                    
                                    @if($campaign->start_date)
                                        <div class="meta-item-partial">
                                            <i class="fas fa-calendar"></i>
                                            <span>{{ \Carbon\Carbon::parse($campaign->start_date)->format('M d, Y') }}</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Campaign Description -->
                                @if($campaign->description)
                                    <p class="campaign-description-partial">{{ Str::limit($campaign->description, 150) }}</p>
                                @endif

                                <!-- Progress Section -->
                                <div class="progress-section-partial">
                                    @if($registrationProgress > 0)
                                        <div class="progress-item-partial">
                                            <div class="progress-header-partial">
                                                <span class="progress-label-partial">Registrations</span>
                                                <span class="progress-value-partial">{{ $totalRegistered }}/{{ $expectedPatients }}</span>
                                            </div>
                                            <div class="progress-bar-partial">
                                                <div class="progress-fill-partial registration-progress" style="width: {{ min($registrationProgress, 100) }}%"></div>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if($sponsorshipProgress > 0)
                                        <div class="progress-item-partial">
                                            <div class="progress-header-partial">
                                                <span class="progress-label-partial">Sponsorship</span>
                                                <span class="progress-value-partial">₹{{ number_format($totalSponsored) }}/₹{{ number_format($sponsorshipTarget) }}</span>
                                            </div>
                                            <div class="progress-bar-partial">
                                                <div class="progress-fill-partial sponsorship-progress" style="width: {{ min($sponsorshipProgress, 100) }}%"></div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Campaign Actions -->
                                <div class="campaign-actions-partial">
                                    <a href="{{ route('user.campaigns.view', $campaign->id) }}" class="btn-primary-partial">
                                        <i class="fas fa-eye"></i>
                                        View Details
                                    </a>
                                    
                                    @if($isFree)
                                        <a href="{{ route('user.campaigns.view', $campaign->id) }}" class="btn-secondary-partial">
                                            <i class="fas fa-user-plus"></i>
                                            Register Free
                                        </a>
                                    @else
                                        <a href="{{ route('user.campaigns.view', $campaign->id) }}" class="btn-secondary-partial">
                                            <i class="fas fa-rupee-sign"></i>
                                            ₹{{ number_format($registrationFee) }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="no-results-partial">
                            <i class="fas fa-search"></i>
                            <h3>No campaigns found</h3>
                            <p>We couldn't find any campaigns matching your criteria. Try adjusting your search or filters.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if(method_exists($campaigns, 'links'))
                <div class="row mt-4">
                    <div class="col-12 d-flex justify-content-center">
                        {{ $campaigns->links() }}
                    </div>
                </div>
            @endif
        @else
            <div class="no-results-partial">
                <i class="fas fa-medical-bag"></i>
                <h3>No campaigns available</h3>
                <p>There are currently no medical campaigns available. Please check back later.</p>
            </div>
        @endif
    </div>
</div>

<script>
// JavaScript for partial functionality
document.addEventListener('DOMContentLoaded', function() {
    const uniqueId = '{{ $uniqueId }}';
    
    // Filter Modal Functions
    window['openFilterModal_' + uniqueId] = function() {
        document.getElementById('filterModal_' + uniqueId).style.display = 'flex';
    };
    
    window['closeFilterModal_' + uniqueId] = function() {
        document.getElementById('filterModal_' + uniqueId).style.display = 'none';
    };
    
    window['clearFilters_' + uniqueId] = function() {
        const form = document.getElementById('filterForm_' + uniqueId);
        form.reset();
        document.getElementById('activeFilters_' + uniqueId).style.display = 'none';
        document.getElementById('activeFilters_' + uniqueId).innerHTML = '';
    };
    
    window['applyFilters_' + uniqueId] = function() {
        const form = document.getElementById('filterForm_' + uniqueId);
        const formData = new FormData(form);
        const filters = {};
        
        for (let [key, value] of formData.entries()) {
            if (value) {
                filters[key] = value;
            }
        }
        
        // Apply filters (you can customize this based on your needs)
        filterCampaigns_{{ $uniqueId }}(filters);
        
        closeFilterModal_{{ $uniqueId }}();
    };
    
    // Search functionality
    const searchInput = document.getElementById('campaignSearch_' + uniqueId);
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            filterCampaignsBySearch_{{ $uniqueId }}(searchTerm);
        });
    }
    
    // Filter campaigns by search term
    window['filterCampaignsBySearch_' + uniqueId] = function(searchTerm) {
        const campaignItems = document.querySelectorAll('#' + uniqueId + ' .campaign-item-partial');
        
        campaignItems.forEach(function(item) {
            const title = item.getAttribute('data-title') || '';
            const doctor = item.getAttribute('data-doctor') || '';
            const specializations = item.getAttribute('data-specializations') || '';
            const location = item.getAttribute('data-location') || '';
            
            const matches = title.includes(searchTerm) || 
                          doctor.includes(searchTerm) || 
                          specializations.includes(searchTerm) || 
                          location.includes(searchTerm);
            
            item.style.display = matches ? 'block' : 'none';
        });
    };
    
    // Filter campaigns by criteria
    window['filterCampaigns_' + uniqueId] = function(filters) {
        const campaignItems = document.querySelectorAll('#' + uniqueId + ' .campaign-item-partial');
        
        campaignItems.forEach(function(item) {
            let matches = true;
            
            // Apply each filter
            for (let [key, value] of Object.entries(filters)) {
                if (key === 'type' && value) {
                    const campaignType = item.getAttribute('data-type') || '';
                    if (campaignType !== value) {
                        matches = false;
                        break;
                    }
                }
                // Add more filter logic as needed
            }
            
            item.style.display = matches ? 'block' : 'none';
        });
        
        // Update active filters display
        updateActiveFiltersDisplay_{{ $uniqueId }}(filters);
    };
    
    // Update active filters display
    window['updateActiveFiltersDisplay_' + uniqueId] = function(filters) {
        const activeFiltersContainer = document.getElementById('activeFilters_' + uniqueId);
        
        if (Object.keys(filters).length === 0) {
            activeFiltersContainer.style.display = 'none';
            activeFiltersContainer.innerHTML = '';
            return;
        }
        
        let filtersHTML = '';
        for (let [key, value] of Object.entries(filters)) {
            filtersHTML += `
                <span class="filter-badge-compact" style="background: var(--primary-color); color: var(--white); padding: 0.25rem 0.75rem; border-radius: 50px; font-size: 0.8rem; font-weight: 500; display: inline-flex; align-items: center; gap: 0.25rem; margin-right: 0.5rem;">
                    ${key}: ${value}
                    <i class="fas fa-times" onclick="removeFilter_${uniqueId}('${key}')" style="cursor: pointer; margin-left: 0.25rem;"></i>
                </span>
            `;
        }
        
        if (Object.keys(filters).length > 1) {
            filtersHTML += `
                <button class="clear-all-filters" onclick="clearFilters_${uniqueId}()" style="background: var(--danger-color); color: var(--white); border: none; padding: 0.5rem 1rem; border-radius: var(--radius-sm); font-size: 0.85rem; font-weight: 500; cursor: pointer; transition: all 0.3s ease;">
                    Clear All
                </button>
            `;
        }
        
        activeFiltersContainer.innerHTML = filtersHTML;
        activeFiltersContainer.style.display = 'flex';
    };
    
    // Close modal when clicking outside
    document.getElementById('filterModal_' + uniqueId).addEventListener('click', function(e) {
        if (e.target === this) {
            closeFilterModal_{{ $uniqueId }}();
        }
    });
});
</script>
