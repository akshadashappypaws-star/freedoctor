@extends('layouts.user')

@section('title', 'Browse Campaign Categories')

@section('content')
<style>
/* Search & Filter Section - Same as home.blade.php */
.search-header {
    color: #208E9E;
    font-weight: 700;
    text-align: center;
    margin-bottom: 2rem;
}

.sticky-search-container {
    position: sticky;
    top: 80px;
    background: rgba(252, 252, 253, 0.95);
    backdrop-filter: blur(10px);
    padding: 1rem 0;
    margin-bottom: 2rem;
    border-radius: 15px;
    z-index: 100;
    transition: all 0.3s ease;
}

.sticky-search-container.not-sticky {
    position: relative;
    top: auto;
    background: transparent;
    backdrop-filter: none;
}

.search-bar-container {
    max-width: 600px;
    margin: 0 auto;
    padding: 0 1rem;
}

.search-input-wrapper {
    position: relative;
    width: 100%;
}

.search-input-wrapper .form-control {
    padding: 15px 20px 15px 50px;
    border-radius: 25px;
    border: 2px solid #e0e7ff;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.search-input-wrapper .search-icon {
    position: absolute;
    left: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: #6b7280;
    font-size: 1.1rem;
}

.search-input-wrapper .form-control:focus {
    border-color: #208E9E;
    box-shadow: 0 0 0 3px rgba(32, 142, 158, 0.1);
}

/* Campaign Cards - Same as home.blade.php */
.campaign-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    border: 1px solid #f1f5f9;
    margin-bottom: 2rem;
    position: relative;
}

.campaign-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 50px rgba(32, 142, 158, 0.15);
    border-color: rgba(32, 142, 158, 0.2);
}

.campaign-image {
    position: relative;
    height: 220px;
    overflow: hidden;
    background: linear-gradient(135deg, #f8fafc, #e2e8f0);
}

.campaign-card:hover .campaign-image img {
    transform: scale(1.05);
}

.campaign-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.campaign-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: linear-gradient(135deg, #208E9E, #E7A51B);
    color: white;
    padding: 8px 15px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.campaign-info {
    padding: 1.5rem;
}

.campaign-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.75rem;
    line-height: 1.4;
}

.campaign-description {
    color: #64748b;
    font-size: 0.95rem;
    line-height: 1.6;
    margin-bottom: 1.5rem;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.campaign-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 12px;
}

.meta-item {
    text-align: center;
    flex: 1;
}

.meta-value {
    display: block;
    font-size: 1.1rem;
    font-weight: 700;
    color: #208E9E;
    margin-bottom: 0.25rem;
}

.meta-label {
    font-size: 0.8rem;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.campaign-actions {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.btn-register {
    background: linear-gradient(135deg, #208E9E, #E7A51B);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 25px;
    font-weight: 600;
    font-size: 0.9rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    cursor: pointer;
    flex: 1;
    justify-content: center;
}

.btn-register:hover {
    background: linear-gradient(135deg, #E7A51B, #208E9E);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(231, 165, 27, 0.3);
}

.btn-register:disabled {
    background: #9CA3AF !important;
    color: #6B7280 !important;
    cursor: not-allowed !important;
    transform: none !important;
    box-shadow: none !important;
}

.btn-register:disabled:hover {
    background: #9CA3AF !important;
    transform: none !important;
    box-shadow: none !important;
}

.btn-view, .btn-share {
    background: white;
    color: #208E9E;
    border: 2px solid #208E9E;
    padding: 10px 20px;
    border-radius: 25px;
    font-weight: 600;
    font-size: 0.9rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-view:hover, .btn-share:hover {
    background: #208E9E;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(32, 142, 158, 0.3);
}

/* Loading and No Results */
.loading-spinner {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 3rem;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #f1f5f9;
    border-top: 4px solid #208E9E;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.no-campaigns {
    text-align: center;
    padding: 4rem 2rem;
    color: #64748b;
}

.no-campaigns i {
    font-size: 4rem;
    color: #cbd5e1;
    margin-bottom: 1rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .search-bar-container {
        padding: 0 0.5rem;
    }
    
    .campaign-card {
        margin-bottom: 1.5rem;
    }
    
    .campaign-image {
        height: 180px;
    }
    
    .campaign-info {
        padding: 1rem;
    }
    
    .campaign-actions {
        flex-direction: column;
    }
    
    .btn-register, .btn-view, .btn-share {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .campaign-meta {
        flex-direction: column;
        gap: 1rem;
    }
    
    .meta-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        text-align: left;
    }
}
</style>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Search Header -->
            <h3 class="search-header"><i class="fas fa-search me-2"></i>Browse Healthcare Campaigns by Category</h3>

            <!-- Sticky Search Container -->
            <div class="sticky-search-container not-sticky" id="stickySearchContainer">
                <div class="container">
                    <div class="search-bar-container">
                        <div class="search-input-wrapper">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" class="form-control" id="campaignSearch" 
                                   placeholder="Search campaigns, specialties, treatments..." 
                                   autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Campaigns Grid -->
            <div class="row" id="campaignsGrid">
                <div class="col-12">
                    <div class="loading-spinner" id="loadingSpinner">
                        <div class="spinner"></div>
                    </div>
                </div>
            </div>

            <!-- Load More Button -->
            <div class="text-center mt-4" id="loadMoreContainer" style="display: none;">
                <button class="btn btn-outline-primary" id="loadMoreBtn">
                    <i class="fas fa-arrow-down me-2"></i>Load More Campaigns
                </button>
            </div>

            <!-- No Results -->
            <div class="no-campaigns" id="noCampaigns" style="display: none;">
                <i class="fas fa-search"></i>
                <h4>No campaigns found</h4>
                <p>Try adjusting your search criteria or browse all available campaigns.</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentPage = 1;
    let isLoading = false;
    let hasMoreData = true;
    let searchQuery = '';
    let searchTimeout = null;

    const campaignsGrid = document.getElementById('campaignsGrid');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    const loadMoreContainer = document.getElementById('loadMoreContainer');
    const noCampaigns = document.getElementById('noCampaigns');
    const searchInput = document.getElementById('campaignSearch');
    const stickySearchContainer = document.getElementById('stickySearchContainer');

    // Load initial campaigns
    loadCampaigns();

    // Search functionality
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            searchQuery = this.value.trim();
            currentPage = 1;
            hasMoreData = true;
            campaignsGrid.innerHTML = '';
            loadCampaigns();
        }, 500);
    });

    // Load more button
    loadMoreBtn.addEventListener('click', function() {
        currentPage++;
        loadCampaigns();
    });

    // Infinite scroll
    window.addEventListener('scroll', function() {
        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 1000) {
            if (!isLoading && hasMoreData) {
                currentPage++;
                loadCampaigns();
            }
        }

        // Sticky search
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        if (scrollTop > 200) {
            stickySearchContainer.classList.remove('not-sticky');
        } else {
            stickySearchContainer.classList.add('not-sticky');
        }
    });

    // Load campaigns function
    function loadCampaigns() {
        if (isLoading) return;
        
        isLoading = true;
        
        if (currentPage === 1) {
            loadingSpinner.style.display = 'flex';
            noCampaigns.style.display = 'none';
            loadMoreContainer.style.display = 'none';
        } else {
            loadMoreBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
            loadMoreBtn.disabled = true;
        }

        // Simulate API call - replace with actual endpoint
        const params = new URLSearchParams({
            page: currentPage,
            search: searchQuery,
            per_page: 12
        });

        fetch(`/api/campaigns/category?${params}`)
            .then(response => response.json())
            .then(data => {
                if (currentPage === 1) {
                    loadingSpinner.style.display = 'none';
                }

                if (data.campaigns && data.campaigns.length > 0) {
                    displayCampaigns(data.campaigns, currentPage === 1);
                    
                    hasMoreData = data.has_more || false;
                    
                    if (hasMoreData) {
                        loadMoreContainer.style.display = 'block';
                    } else {
                        loadMoreContainer.style.display = 'none';
                    }
                } else {
                    if (currentPage === 1) {
                        noCampaigns.style.display = 'block';
                    }
                    hasMoreData = false;
                    loadMoreContainer.style.display = 'none';
                }

                loadMoreBtn.innerHTML = '<i class="fas fa-arrow-down me-2"></i>Load More Campaigns';
                loadMoreBtn.disabled = false;
                isLoading = false;
            })
            .catch(error => {
                console.error('Error loading campaigns:', error);
                if (currentPage === 1) {
                    loadingSpinner.style.display = 'none';
                    noCampaigns.style.display = 'block';
                }
                loadMoreBtn.innerHTML = '<i class="fas fa-arrow-down me-2"></i>Load More Campaigns';
                loadMoreBtn.disabled = false;
                isLoading = false;
            });
    }

    // Display campaigns function
    function displayCampaigns(campaigns, clearFirst = false) {
        if (clearFirst) {
            const existingCards = campaignsGrid.querySelectorAll('.col-lg-4');
            existingCards.forEach(card => card.remove());
        }

        campaigns.forEach(campaign => {
            const campaignCard = createCampaignCard(campaign);
            campaignsGrid.appendChild(campaignCard);
        });
    }

    // Create campaign card function
    function createCampaignCard(campaign) {
        const col = document.createElement('div');
        col.className = 'col-lg-4 col-md-6 col-sm-12 mb-4';

        const isExpired = campaign.end_date && new Date(campaign.end_date) < new Date();
        
        col.innerHTML = `
            <div class="campaign-card">
                <div class="campaign-image" onclick="navigateToCampaign(${campaign.id})" style="cursor: pointer;">
                    <img src="${campaign.image || '/images/default-campaign.jpg'}" 
                         alt="${campaign.title}" 
                         onerror="this.src='/images/default-campaign.jpg'">
                    <div class="campaign-badge">${campaign.category || 'Healthcare'}</div>
                </div>
                
                <div class="campaign-info" onclick="navigateToCampaign(${campaign.id})" style="cursor: pointer;">
                    <h5 class="campaign-title">${campaign.title}</h5>
                    <p class="campaign-description">${campaign.description || 'Comprehensive healthcare campaign designed to provide quality medical services.'}</p>
                    
                    <div class="campaign-meta">
                        <div class="meta-item">
                            <span class="meta-value">${campaign.location || 'TBA'}</span>
                            <span class="meta-label">Location</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-value">₹${campaign.registration_payment || 'Free'}</span>
                            <span class="meta-label">Registration</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-value">${campaign.registered_count || 0}/${campaign.expected_patients || 100}</span>
                            <span class="meta-label">Registered</span>
                        </div>
                    </div>
                </div>
                
                <div class="campaign-actions" style="padding: 0 1.5rem 1.5rem;">
                    ${isExpired 
                        ? `<button class="btn-register" disabled style="opacity: 0.6; cursor: not-allowed;">
                            <i class="fas fa-clock"></i> Closed
                           </button>`
                        : `<button onclick="handleRegistration(${campaign.id}, ${campaign.registration_payment || 0})" class="btn-register">
                            <i class="fas fa-user-plus"></i> Register
                           </button>`
                    }
                    <button onclick="navigateToCampaign(${campaign.id})" class="btn-view">
                        <i class="fas fa-eye"></i> View Details
                    </button>
                    <button onclick="shareCampaign(${campaign.id})" class="btn-share">
                        <i class="fas fa-share"></i> Share
                    </button>
                </div>
            </div>
        `;

        return col;
    }

    // Navigation functions
    window.navigateToCampaign = function(campaignId) {
        window.location.href = `/user/campaigns/${campaignId}`;
    };

    window.handleRegistration = function(campaignId, amount) {
        // Registration logic here - same as other pages
        console.log('Register for campaign:', campaignId, 'Amount:', amount);
        // Implement registration modal or redirect
    };

    window.shareCampaign = function(campaignId) {
        if (navigator.share) {
            navigator.share({
                title: 'Healthcare Campaign',
                text: 'Check out this healthcare campaign!',
                url: window.location.origin + `/user/campaigns/${campaignId}`
            });
        } else {
            // Fallback for browsers that don't support Web Share API
            const url = window.location.origin + `/user/campaigns/${campaignId}`;
            navigator.clipboard.writeText(url).then(() => {
                alert('Campaign link copied to clipboard!');
            });
        }
    };
});
</script>
@endsection
