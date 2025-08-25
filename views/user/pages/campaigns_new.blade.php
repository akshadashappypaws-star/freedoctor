@extends('../user.master')

@section('title', 'Medical Campaigns - FreeDoctor')

@section('content')

<!-- Campaign Page Styles -->
<style>
/* ===== CAMPAIGNS PAGE STYLES ===== */
:root {
    --primary-color: #2C2A4C;
    --secondary-color: #E7A51B;
    --success-color: #4CAF50;
    --danger-color: #f44336;
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
    background: var(--white);
    padding: 2rem 0;
    border-bottom: 1px solid var(--border-color);
}

.search-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-lg);
    padding: 2rem;
    margin-top: -3rem;
    position: relative;
    z-index: 10;
}

.search-input {
    position: relative;
    margin-bottom: 1rem;
}

.search-input input {
    width: 100%;
    padding: 1rem 1rem 1rem 3rem;
    border: 2px solid var(--border-color);
    border-radius: var(--radius-md);
    font-size: 1rem;
    transition: all 0.3s ease;
    background: var(--white);
}

.search-input input:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(44, 42, 76, 0.1);
}

.search-input .input-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 1.2rem;
}

.search-btn {
    background: linear-gradient(135deg, var(--primary-color) 0%, #4a47a3 100%);
    color: var(--white);
    border: none;
    padding: 1rem 2rem;
    border-radius: var(--radius-md);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-md);
}

.search-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
    background: linear-gradient(135deg, #4a47a3 0%, var(--primary-color) 100%);
}

.location-btn {
    background: var(--secondary-color);
    color: var(--white);
    border: none;
    padding: 1rem;
    border-radius: var(--radius-md);
    transition: all 0.3s ease;
    margin-left: 0.5rem;
}

.location-btn:hover {
    background: #d4751a;
    transform: translateY(-2px);
}

/* Filter Section */
.filters-section {
    background: var(--light-gray);
    padding: 1.5rem 0;
    border-bottom: 1px solid var(--border-color);
}

.filter-row {
    display: flex;
    gap: 1rem;
    align-items: center;
    flex-wrap: wrap;
}

.filter-select {
    min-width: 150px;
    padding: 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: var(--radius-sm);
    background: var(--white);
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.filter-select:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 2px rgba(44, 42, 76, 0.1);
}

.filter-badge {
    background: var(--primary-color);
    color: var(--white);
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.clear-filters {
    color: var(--danger-color);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.clear-filters:hover {
    color: #c82333;
    text-decoration: underline;
}

/* Campaign Grid */
.campaigns-grid {
    padding: 3rem 0;
    min-height: 60vh;
}

.campaign-card {
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

.campaign-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary-color);
}

.campaign-image {
    position: relative;
    height: 200px;
    overflow: hidden;
    cursor: pointer;
}

.campaign-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.campaign-card:hover .campaign-image img {
    transform: scale(1.05);
}

.campaign-status {
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

.campaign-type {
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

.campaign-content {
    padding: 1.5rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.campaign-title {
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

.campaign-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1rem;
    font-size: 0.9rem;
    color: var(--text-muted);
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.meta-item i {
    color: var(--primary-color);
    width: 16px;
}

.campaign-description {
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

.campaign-price {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: var(--light-gray);
    border-radius: var(--radius-sm);
}

.price-label {
    font-size: 0.9rem;
    color: var(--text-muted);
    font-weight: 500;
}

.price-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary-color);
}

.price-free {
    color: var(--success-color);
}

.campaign-actions {
    display: flex;
    gap: 0.75rem;
    margin-top: auto;
}

.btn-primary {
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

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    color: var(--white);
    text-decoration: none;
}

.btn-secondary {
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

.btn-secondary:hover {
    background: var(--primary-color);
    color: var(--white);
    text-decoration: none;
    transform: translateY(-2px);
}

/* Stats Section */
.stats-section {
    background: linear-gradient(135deg, var(--primary-color) 0%, #4a47a3 100%);
    color: var(--white);
    padding: 4rem 0;
}

.stat-card {
    text-align: center;
    padding: 2rem;
    background: rgba(255,255,255,0.1);
    border-radius: var(--radius-lg);
    backdrop-filter: blur(10px);
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-8px);
}

.stat-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    color: var(--secondary-color);
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 1.1rem;
    opacity: 0.9;
}

/* Pagination */
.pagination-wrapper {
    padding: 3rem 0;
    background: var(--light-gray);
    border-top: 1px solid var(--border-color);
}

.pagination {
    justify-content: center;
    margin: 0;
}

.page-link {
    color: var(--primary-color);
    border: 1px solid var(--border-color);
    padding: 0.75rem 1rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.page-link:hover {
    background: var(--primary-color);
    color: var(--white);
    border-color: var(--primary-color);
}

.page-item.active .page-link {
    background: var(--primary-color);
    border-color: var(--primary-color);
    color: var(--white);
}

/* No Results */
.no-results {
    text-align: center;
    padding: 4rem 2rem;
    color: var(--text-muted);
}

.no-results i {
    font-size: 4rem;
    margin-bottom: 1.5rem;
    color: var(--border-color);
}

.no-results h3 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
    color: var(--text-dark);
}

/* Search Suggestions */
.search-suggestions {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: var(--white);
    border: 1px solid var(--border-color);
    border-top: none;
    border-radius: 0 0 var(--radius-md) var(--radius-md);
    box-shadow: var(--shadow-lg);
    z-index: 1000;
    max-height: 300px;
    overflow-y: auto;
    display: none;
}

.suggestion-item {
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
    cursor: pointer;
    transition: background 0.3s ease;
}

.suggestion-item:hover {
    background: var(--light-gray);
}

.suggestion-item:last-child {
    border-bottom: none;
}

/* Responsive Design */
@media (max-width: 768px) {
    .campaigns-header h1 {
        font-size: 2rem;
    }
    
    .campaigns-header p {
        font-size: 1rem;
    }
    
    .search-card {
        margin-top: -2rem;
        padding: 1.5rem;
    }
    
    .filter-row {
        flex-direction: column;
        align-items: stretch;
    }
    
    .filter-select {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .campaign-actions {
        flex-direction: column;
    }
    
    .stat-card {
        margin-bottom: 2rem;
    }
}

@media (max-width: 576px) {
    .campaigns-header {
        padding: 3rem 0 2rem;
    }
    
    .search-input input {
        padding: 0.875rem 0.875rem 0.875rem 2.5rem;
        font-size: 0.9rem;
    }
    
    .campaign-card {
        margin-bottom: 1.5rem;
    }
    
    .btn-primary, .btn-secondary {
        padding: 0.625rem 1rem;
        font-size: 0.9rem;
    }
}

/* Animation Classes */
.fade-in {
    animation: fadeIn 0.6s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.slide-up {
    animation: slideUp 0.8s ease-out;
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(40px); }
    to { opacity: 1; transform: translateY(0); }
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
            <form method="GET" action="{{ route('user.campaigns') }}" id="searchForm">
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="search-input">
                            <i class="fas fa-search input-icon"></i>
                            <input type="text" 
                                   name="search" 
                                   id="campaignSearch"
                                   placeholder="Search campaigns, doctors, specialties..."
                                   value="{{ request('search') }}"
                                   autocomplete="off">
                            <div id="searchSuggestions" class="search-suggestions"></div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="search-input">
                            <i class="fas fa-map-marker-alt input-icon"></i>
                            <input type="text" 
                                   name="location" 
                                   id="locationSearch"
                                   placeholder="Enter your location"
                                   value="{{ request('location') }}"
                                   autocomplete="off">
                            <button type="button" id="getCurrentLocation" class="location-btn">
                                <i class="fas fa-crosshairs"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-4 col-12 mb-3">
                        <button type="submit" class="search-btn w-100">
                            <i class="fas fa-search me-2"></i>
                            Find Campaigns
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Filters Section -->
<section class="filters-section">
    <div class="container">
        <form method="GET" action="{{ route('user.campaigns') }}" id="filtersForm">
            <input type="hidden" name="search" value="{{ request('search') }}">
            <input type="hidden" name="location" value="{{ request('location') }}">
            
            <div class="filter-row">
                <select name="specialty" class="filter-select" onchange="this.form.submit()">
                    <option value="">All Specialties</option>
                    @foreach($specialties ?? [] as $specialty)
                        <option value="{{ $specialty->id }}" {{ request('specialty') == $specialty->id ? 'selected' : '' }}>
                            {{ $specialty->name }}
                        </option>
                    @endforeach
                </select>
                
                <select name="type" class="filter-select" onchange="this.form.submit()">
                    <option value="">All Types</option>
                    <option value="medical" {{ request('type') == 'medical' ? 'selected' : '' }}>Medical</option>
                    <option value="surgical" {{ request('type') == 'surgical' ? 'selected' : '' }}>Surgical</option>
                </select>
                
                <select name="status" class="filter-select" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                    <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
                
                <select name="registration" class="filter-select" onchange="this.form.submit()">
                    <option value="">All Campaigns</option>
                    <option value="free" {{ request('registration') == 'free' ? 'selected' : '' }}>Free</option>
                    <option value="paid" {{ request('registration') == 'paid' ? 'selected' : '' }}>Paid</option>
                </select>
                
                <select name="sort" class="filter-select" onchange="this.form.submit()">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                    <option value="upcoming" {{ request('sort') == 'upcoming' ? 'selected' : '' }}>Upcoming First</option>
                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                </select>
                
                @if(request()->hasAny(['specialty', 'type', 'status', 'registration', 'search', 'location']))
                    <a href="{{ route('user.campaigns') }}" class="clear-filters">
                        <i class="fas fa-times me-1"></i>
                        Clear Filters
                    </a>
                @endif
            </div>
            
            <!-- Active Filters -->
            @if(request()->hasAny(['specialty', 'type', 'status', 'registration', 'search', 'location']))
                <div class="mt-3">
                    <strong>Active Filters:</strong>
                    @if(request('search'))
                        <span class="filter-badge">
                            <i class="fas fa-search me-1"></i>
                            "{{ request('search') }}"
                        </span>
                    @endif
                    @if(request('location'))
                        <span class="filter-badge">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            {{ request('location') }}
                        </span>
                    @endif
                    @if(request('specialty'))
                        <span class="filter-badge">
                            <i class="fas fa-stethoscope me-1"></i>
                            {{ $specialties->find(request('specialty'))->name ?? 'Specialty' }}
                        </span>
                    @endif
                    @if(request('type'))
                        <span class="filter-badge">
                            <i class="fas fa-tag me-1"></i>
                            {{ ucfirst(request('type')) }}
                        </span>
                    @endif
                    @if(request('status'))
                        <span class="filter-badge">
                            <i class="fas fa-clock me-1"></i>
                            {{ ucfirst(request('status')) }}
                        </span>
                    @endif
                    @if(request('registration'))
                        <span class="filter-badge">
                            <i class="fas fa-money-bill me-1"></i>
                            {{ ucfirst(request('registration')) }}
                        </span>
                    @endif
                </div>
            @endif
        </form>
    </div>
</section>

<!-- Campaigns Grid -->
<section class="campaigns-grid">
    <div class="container">
        @if(isset($campaigns) && $campaigns->count() > 0)
            <!-- Results Info -->
            <div class="row mb-4">
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

            <div class="row" id="campaignsGrid">
                @foreach($campaigns as $campaign)
                    @php
                        $today = now();
                        $status = 'upcoming';
                        if ($campaign->start_date <= $today && $campaign->end_date >= $today) {
                            $status = 'ongoing';
                        } elseif ($campaign->end_date < $today) {
                            $status = 'completed';
                        }
                        
                        $registrationFee = $campaign->registration_payment ?? 0;
                        $isFree = $registrationFee == 0;
                    @endphp
                    
                    <div class="col-lg-4 col-md-6 mb-4 campaign-item fade-in"
                         data-title="{{ strtolower($campaign->title) }}"
                         data-location="{{ strtolower($campaign->location) }}"
                         data-type="{{ $campaign->camp_type }}"
                         data-doctor="{{ strtolower($campaign->doctor->doctor_name ?? '') }}"
                         data-specializations="{{ strtolower($campaign->doctor->specialty->name ?? '') }}">
                        
                        <div class="campaign-card">
                            <!-- Campaign Image -->
                            <div class="campaign-image" onclick="navigateToCampaign({{ $campaign->id }})">
                                @if($campaign->thumbnail)
                                    <img src="{{ asset('storage/' . $campaign->thumbnail) }}" alt="{{ $campaign->title }}">
                                @else
                                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='200' viewBox='0 0 400 200'%3E%3Crect width='400' height='200' fill='%23f8f9fa'/%3E%3Ctext x='200' y='100' text-anchor='middle' fill='%236c757d' font-family='Arial' font-size='24'%3E{{ $campaign->title }}%3C/text%3E%3C/svg%3E" alt="{{ $campaign->title }}">
                                @endif
                                
                                <!-- Campaign Status Badge -->
                                <div class="campaign-status status-{{ $status }}">
                                    {{ ucfirst($status) }}
                                </div>
                                
                                <!-- Campaign Type Badge -->
                                <div class="campaign-type">
                                    {{ ucfirst($campaign->camp_type) }}
                                </div>
                            </div>
                            
                            <!-- Campaign Content -->
                            <div class="campaign-content">
                                <h3 class="campaign-title">{{ $campaign->title }}</h3>
                                
                                <!-- Campaign Meta Information -->
                                <div class="campaign-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-user-md"></i>
                                        <span>{{ $campaign->doctor->doctor_name ?? 'TBA' }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>{{ $campaign->location }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-calendar"></i>
                                        <span>{{ \Carbon\Carbon::parse($campaign->start_date)->format('M d, Y') }}</span>
                                    </div>
                                    @if($campaign->doctor->specialty)
                                        <div class="meta-item">
                                            <i class="fas fa-stethoscope"></i>
                                            <span>{{ $campaign->doctor->specialty->name }}</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Campaign Description -->
                                <div class="campaign-description">
                                    {{ Str::limit($campaign->description, 120) }}
                                </div>
                                
                                <!-- Registration Fee -->
                                <div class="campaign-price">
                                    <span class="price-label">Registration Fee:</span>
                                    <span class="price-value {{ $isFree ? 'price-free' : '' }}">
                                        @if($isFree)
                                            FREE
                                        @else
                                            ₹{{ number_format($registrationFee) }}
                                        @endif
                                    </span>
                                </div>
                                
                                <!-- Campaign Actions -->
                                <div class="campaign-actions">
                                    <a href="{{ route('user.campaigns.view', $campaign->id) }}" class="btn-secondary">
                                        <i class="fas fa-eye"></i>
                                        View Details
                                    </a>
                                    
                                    @if($status === 'ongoing' || $status === 'upcoming')
                                        <button onclick="handleRegistration({{ $campaign->id }}, {{ $registrationFee }})" class="btn-primary">
                                            <i class="fas fa-user-plus"></i>
                                            Register Now
                                        </button>
                                    @else
                                        <span class="btn-secondary" style="opacity: 0.6; cursor: not-allowed;">
                                            <i class="fas fa-clock"></i>
                                            Completed
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                <div class="container">
                    <div class="row">
                        <div class="col-12 d-flex justify-content-center">
                            {{ $campaigns->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                    
                    <!-- Pagination Info -->
                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <div class="text-muted">
                                <small>
                                    Showing {{ $campaigns->firstItem() }} to {{ $campaigns->lastItem() }} of {{ $campaigns->total() }} results
                                    @if($campaigns->hasPages())
                                        (Page {{ $campaigns->currentPage() }} of {{ $campaigns->lastPage() }})
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @else
            <!-- No Results -->
            <div class="no-results">
                <i class="fas fa-search"></i>
                <h3>No Campaigns Found</h3>
                <p>
                    @if(request()->hasAny(['search', 'location', 'specialty', 'type', 'status', 'registration']))
                        No campaigns match your current search criteria. Try adjusting your filters.
                    @else
                        There are currently no active campaigns available.
                    @endif
                </p>
                @if(request()->hasAny(['search', 'location', 'specialty', 'type', 'status', 'registration']))
                    <a href="{{ route('user.campaigns') }}" class="btn-primary mt-3">
                        <i class="fas fa-refresh me-2"></i>
                        View All Campaigns
                    </a>
                @endif
            </div>
        @endif
    </div>
</section>

<!-- Healthcare Stats Section -->
<section class="stats-section">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-hospital"></i>
                    </div>
                    <div class="stat-number" id="totalCampaigns">{{ \App\Models\Campaign::count() }}</div>
                    <div class="stat-label">Total Campaigns</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <div class="stat-number" id="totalDoctors">{{ \App\Models\Doctor::count() }}</div>
                    <div class="stat-label">Verified Doctors</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number" id="totalPatients">{{ \App\Models\PatientRegistration::count() }}</div>
                    <div class="stat-label">Patients Served</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <div class="stat-number" id="activeCampaigns">{{ \App\Models\Campaign::where('approval_status', 'approved')->whereDate('end_date', '>=', now())->count() }}</div>
                    <div class="stat-label">Active Campaigns</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Registration Modal -->
<div id="registrationModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="fas fa-user-plus me-2"></i>Register for Campaign</h2>
            <button class="close" onclick="closeRegistrationModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="registrationForm">
                @csrf
                <input type="hidden" name="campaign_id" id="campaignId">
                <input type="hidden" name="user_id" id="userId" value="{{ auth('user')->id() }}">
                <input type="hidden" name="amount" id="amount">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label>Full Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label>Email Address</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label>Phone Number</label>
                            <input type="tel" name="phone_number" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label>Age</label>
                            <input type="number" name="age" class="form-control" min="1" max="120">
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label>Address</label>
                    <textarea name="address" class="form-control" rows="3" required></textarea>
                </div>

                <!-- Payment Information -->
                <div id="paymentInfo" class="payment-info" style="display: none;">
                    <div class="alert alert-info">
                        <h5><i class="fas fa-credit-card me-2"></i>Payment Required</h5>
                        <p>Registration fee: <strong id="paymentAmount">₹0</strong></p>
                        <p>You will be redirected to a secure payment gateway to complete your registration.</p>
                    </div>
                </div>

                <div id="freeInfo" class="payment-info" style="display: none;">
                    <div class="alert alert-success">
                        <h5><i class="fas fa-check-circle me-2"></i>Free Registration</h5>
                        <p>This campaign is free to join. Click below to complete your registration.</p>
                    </div>
                </div>

                <div class="d-flex gap-3 mt-4">
                    <button type="button" onclick="closeRegistrationModal()" class="btn btn-secondary flex-1">Cancel</button>
                    <button type="submit" id="freeRegisterBtn" class="btn btn-success flex-1" style="display: none;">
                        <i class="fas fa-user-plus me-2"></i>Register Free
                    </button>
                    <button type="button" id="paymentBtn" class="btn btn-primary flex-1" style="display: none;">
                        <i class="fas fa-credit-card me-2"></i><span id="paymentBtnText">Pay Now</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Global variables
let searchTimeout;
let userLatitude = null;
let userLongitude = null;

// Navigation functions
function navigateToCampaign(campaignId) {
    window.location.href = `/user/campaigns/${campaignId}`;
}

// Registration modal functions
function handleRegistration(campaignId, fee = 0) {
    if (!{{ auth('user')->check() ? 'true' : 'false' }}) {
        Swal.fire({
            title: 'Login Required',
            text: 'Please login to register for campaigns',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Login Now',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '{{ route("user.login") }}';
            }
        });
        return;
    }

    $('#campaignId').val(campaignId);
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

// Search functionality
function performUniversalSearch() {
    const searchInput = document.getElementById('campaignSearch');
    const locationInput = document.getElementById('locationSearch');
    const suggestionsDiv = document.getElementById('searchSuggestions');

    const searchTerm = searchInput ? searchInput.value.trim() : '';
    const location = locationInput ? locationInput.value.trim() : '';

    if (searchTerm.length < 1 && location.length < 1) {
        suggestionsDiv.style.display = 'none';
        return;
    }

    // Show loading state
    suggestionsDiv.innerHTML = '<div class="suggestion-item"><i class="fas fa-spinner fa-spin me-2"></i>Searching...</div>';
    suggestionsDiv.style.display = 'block';

    // Make AJAX request
    fetch('/user/campaigns/search', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            search: searchTerm,
            location: location,
            latitude: userLatitude,
            longitude: userLongitude
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data && data.campaigns && data.campaigns.length > 0) {
            displaySearchSuggestions(data.campaigns);
        } else {
            suggestionsDiv.innerHTML = '<div class="suggestion-item">No campaigns found</div>';
        }
    })
    .catch(error => {
        console.error('Search error:', error);
        suggestionsDiv.innerHTML = '<div class="suggestion-item">Error searching campaigns</div>';
    });
}

function displaySearchSuggestions(campaigns) {
    const suggestionsDiv = document.getElementById('searchSuggestions');
    
    const suggestions = campaigns.slice(0, 5).map(campaign => `
        <div class="suggestion-item" onclick="selectCampaign('${campaign.id}')">
            <strong>${campaign.title}</strong><br>
            <small class="text-muted">
                <i class="fas fa-map-marker-alt me-1"></i>${campaign.location} • 
                <i class="fas fa-user-md me-1"></i>${campaign.doctor?.doctor_name || 'TBA'}
            </small>
        </div>
    `).join('');
    
    suggestionsDiv.innerHTML = suggestions;
    suggestionsDiv.style.display = 'block';
}

function selectCampaign(campaignId) {
    window.location.href = `/user/campaigns/${campaignId}`;
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('campaignSearch');
    const locationInput = document.getElementById('locationSearch');
    const suggestionsDiv = document.getElementById('searchSuggestions');

    // Search input events
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(performUniversalSearch, 300);
        });

        searchInput.addEventListener('focus', function() {
            if (this.value.trim().length > 0) {
                performUniversalSearch();
            }
        });
    }

    if (locationInput) {
        locationInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(performUniversalSearch, 300);
        });

        locationInput.addEventListener('focus', function() {
            const searchTerm = searchInput ? searchInput.value.trim() : '';
            if (this.value.trim().length > 0 || searchTerm.length > 0) {
                performUniversalSearch();
            }
        });
    }

    // Hide suggestions when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.search-input')) {
            if (suggestionsDiv) {
                suggestionsDiv.style.display = 'none';
            }
        }
    });

    // Current location button
    const getCurrentLocationBtn = document.getElementById('getCurrentLocation');
    if (getCurrentLocationBtn) {
        getCurrentLocationBtn.addEventListener('click', function() {
            if (navigator.geolocation) {
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                this.disabled = true;

                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        userLatitude = position.coords.latitude;
                        userLongitude = position.coords.longitude;

                        // Use reverse geocoding API
                        fetch(`https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${userLatitude}&longitude=${userLongitude}&localityLanguage=en`)
                            .then(response => response.json())
                            .then(data => {
                                const locationName = data.city || data.locality || data.principalSubdivision || 'Current Location';
                                document.getElementById('locationSearch').value = locationName;
                                performUniversalSearch();
                            })
                            .catch(error => {
                                console.error('Geocoding error:', error);
                                document.getElementById('locationSearch').value = 'Current Location';
                            })
                            .finally(() => {
                                getCurrentLocationBtn.innerHTML = '<i class="fas fa-crosshairs"></i>';
                                getCurrentLocationBtn.disabled = false;
                            });
                    },
                    function(error) {
                        console.error('Geolocation error:', error);
                        alert('Unable to get your location. Please enter manually.');
                        getCurrentLocationBtn.innerHTML = '<i class="fas fa-crosshairs"></i>';
                        getCurrentLocationBtn.disabled = false;
                    }
                );
            } else {
                alert('Geolocation is not supported by this browser.');
            }
        });
    }

    // Modal event listeners
    $('.close').on('click', closeRegistrationModal);
    $(window).on('click', function(e) {
        if (e.target.id === 'registrationModal') closeRegistrationModal();
    });
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') closeRegistrationModal();
    });

    // Form submissions
    $('#registrationForm').on('submit', function(e) {
        e.preventDefault();
        if ($('#paymentBtn').is(':visible')) return; // Skip if payment flow

        const formData = new FormData(this);
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
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            Swal.close();
            if (data.success) {
                Swal.fire({
                    title: 'Registration Successful!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    closeRegistrationModal();
                    location.reload();
                });
            } else {
                Swal.fire({
                    title: 'Registration Failed',
                    text: data.message || 'Something went wrong',
                    icon: 'error'
                });
            }
        })
        .catch(error => {
            console.error('Registration error:', error);
            Swal.close();
            Swal.fire({
                title: 'Error',
                text: 'Failed to register. Please try again.',
                icon: 'error'
            });
        });
    });

    // Payment button
    $('#paymentBtn').on('click', function(e) {
        e.preventDefault();

        const form = $('#registrationForm')[0];
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const formData = new FormData(form);
        const amount = parseFloat($('#amount').val() || 0) * 100; // Convert to paise

        const options = {
            key: '{{ config("services.razorpay.key") }}',
            amount: amount,
            currency: 'INR',
            name: 'FreeDoctor Healthcare',
            description: 'Campaign Registration Fee',
            handler: function(response) {
                formData.append('razorpay_payment_id', response.razorpay_payment_id);
                
                fetch('{{ route("user.patient.campaigns.register") }}', {
                    method: 'POST',
                    credentials: 'include',
                    headers: {
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Registration Successful!',
                            text: 'Payment completed and registration confirmed.',
                            icon: 'success'
                        }).then(() => {
                            closeRegistrationModal();
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Registration Failed',
                            text: data.message || 'Something went wrong',
                            icon: 'error'
                        });
                    }
                });
            },
            modal: {
                ondismiss: function() {
                    console.log('Payment modal closed');
                }
            }
        };

        const rzp = new Razorpay(options);
        rzp.open();
    });

    // Add animation classes to elements as they come into view
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

    // Observe campaign cards
    document.querySelectorAll('.campaign-card').forEach(card => {
        observer.observe(card);
    });
});
</script>
@endpush
