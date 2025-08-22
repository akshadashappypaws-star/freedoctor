
// Define search functions globally first
let searchTimeout;

// Universal search functionality - Define immediately for global access
window.performUniversalSearch = function performUniversalSearch() {
    const searchInput = document.getElementById('campaignSearch');
    const locationInput = document.getElementById('locationSearch');
    const suggestionsDiv = document.getElementById('searchSuggestions');
    
    const searchTerm = searchInput ? searchInput.value.trim() : '';
    const location = locationInput ? locationInput.value.trim() : '';
    
    // Clear previous timeout
    clearTimeout(searchTimeout);
    
    // Validate inputs
    if (searchTerm.length < 1 && location.length < 1) {
        if (window.hideSuggestions) window.hideSuggestions();
        return;
    }

    // Show loading state
    if (window.showLoadingState) window.showLoadingState();

    // Make AJAX request to search campaigns
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
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Search response received:', data);
        console.log('Campaigns in response:', data.campaigns ? data.campaigns.length : 0);
        if (data && data.campaigns && data.campaigns.length > 0) {
            console.log('Found campaigns:', data.campaigns.length);
            console.log('Campaign titles:', data.campaigns.map(c => c.title));
            
            // Use search type from backend response, or fallback to frontend detection
            const searchType = data.search_type || ((searchTerm.length === 0 && location.length > 0) ? 'location' : 'general');
            
            if (window.displaySearchResults) window.displaySearchResults(data.campaigns, searchType);
        } else {
            console.log('No campaigns found in response');
            if (window.showNoResults) window.showNoResults();
        }
    })
    .catch(error => {
        console.error('Search error:', error);
        if (window.showErrorState) window.showErrorState();
    });
}

// Helper functions
window.showLoadingState = function showLoadingState() {
    const suggestionsDiv = document.getElementById('searchSuggestions');
    if (suggestionsDiv) {
        suggestionsDiv.innerHTML = '<div class="search-loading"><i class="fas fa-spinner fa-spin"></i> Searching campaigns...</div>';
        suggestionsDiv.style.display = 'block';
    }
}

window.showErrorState = function showErrorState() {
    const suggestionsDiv = document.getElementById('searchSuggestions');
    if (suggestionsDiv) {
        suggestionsDiv.innerHTML = '<div class="search-error"><i class="fas fa-exclamation-triangle"></i> Error searching campaigns. Please try again.</div>';
    }
}

window.showNoResults = function showNoResults() {
    const suggestionsDiv = document.getElementById('searchSuggestions');
    if (suggestionsDiv) {
        suggestionsDiv.innerHTML = '<div class="no-search-results"><i class="fas fa-search"></i> No campaigns found for your search.</div>';
    }
}

window.hideSuggestions = function hideSuggestions() {
    const suggestionsDiv = document.getElementById('searchSuggestions');
    if (suggestionsDiv) {
        suggestionsDiv.style.display = 'none';
    }
}

window.displaySearchResults = function displaySearchResults(campaigns, searchType = 'general') {
    const campaignsGrid = document.getElementById('campaignsGrid');
    if (!campaignsGrid) {
        console.error('Campaigns grid not found');
        return;
    }

    if (campaigns.length === 0) {
        campaignsGrid.innerHTML = `
            <div class="col-12">
                <div class="no-results">
                    <i class="fas fa-calendar-times"></i>
                    <h3>No Campaigns Found</h3>
                    <p>No campaigns match your search criteria</p>
                </div>
            </div>
        `;
        return;
    }

    // Add location-based search info if applicable
    let locationInfo = '';
    if (searchType === 'location') {
        locationInfo = `
            <div class="col-12 mb-3">
                <div class="alert alert-info">
                    <i class="fas fa-map-marker-alt me-2"></i>
                    <strong>Location-based Results:</strong> Campaigns are sorted by proximity to your search location, with nearest matches first.
                </div>
            </div>
        `;
    }

    const campaignCards = campaigns.map(campaign => `
        <div class="col-md-6 col-lg-4 mb-3 campaign-item"
            data-title="${campaign.title.toLowerCase()}"
            data-location="${campaign.location.toLowerCase()}"
            data-type="${campaign.camp_type || ''}"
            data-specializations="${campaign.specialty || ''}">

            <div class="campaign-card compact-layout">
                <!-- Top Section: 45% Image + 55% Content -->
                <div class="card-top-section">
                    <!-- Campaign Image (45% width) -->
                    <div class="campaign-image compact-image" onclick="navigateToCampaign(${campaign.id})" style="cursor: pointer;">
                        ${campaign.thumbnail ? 
                            `<img src="${campaign.thumbnail}" alt="${campaign.title}">` :
                            `<div class="d-flex align-items-center justify-content-center h-100">
                                <div class="text-center">
                                    <i class="fas fa-calendar-medical" style="font-size: 2rem; margin-bottom: 0.5rem; color: #667eea;"></i>
                                    <p style="color: #6c757d; margin: 0; font-size: 0.7rem;">Campaign Image</p>
                                </div>
                            </div>`
                        }

                        <!-- Distance Badge (top right - highest priority) -->
                        ${campaign.distance_text ? 
                            `<span class="distance-badge compact-badge-distance" data-distance="${campaign.distance_text.toLowerCase().replace(/\\s+/g, '-').replace('away', '').trim()}">
                                <i class="fas fa-map-marker-alt"></i>${campaign.distance_text}
                            </span>` : ''
                        }
                        
                        <!-- Registration Cost Badge (below distance badge) -->
                        ${campaign.registration_payment && campaign.registration_payment > 0 ? 
                            `<span class="cost-badge compact-badge ${campaign.registration_payment > 5000 ? 'cost-big-discount' : 'cost-paid'}" style="${campaign.distance_text ? 'top: 2.2rem !important;' : ''}">
                                ${campaign.registration_payment > 5000 ? 
                                    '<i class="fas fa-percentage me-1"></i>BIG DISCOUNT' : 
                                    '<i class="fas fa-rupee-sign me-1"></i>₹' + campaign.registration_payment
                                }
                            </span>` :
                            `<span class="cost-badge compact-badge cost-free" style="${campaign.distance_text ? 'top: 2.2rem !important;' : ''}"><i class="fas fa-gift me-1"></i>FREE</span>`
                        }
                    </div>

                    <!-- Campaign Content (55% width) -->
                    <div class="campaign-content compact-content">
                        <!-- Title (2 lines max) -->
                        <h4 class="campaign-title compact-title">${campaign.title}</h4>
                        
                        <!-- Description (2 lines max, with ellipsis) -->
                        ${campaign.description ? 
                            `<div class="campaign-description compact-description">
                                ${campaign.description.length > 80 ? campaign.description.substring(0, 80) + '...' : campaign.description}
                            </div>` : ''
                        }
                        
                        <!-- Doctor Name -->
                        <div class="detail-item compact-detail">
                            <i class="fas fa-user-md detail-icon"></i>
                            <span>Dr. ${campaign.doctor_name}</span>
                        </div>
                        
                        <!-- Category (left) and Timing (right) -->
                        <div class="detail-row">
                            ${campaign.category ? 
                                `<div class="detail-item compact-detail detail-left">
                                    <i class="material-icons detail-icon" style="font-size: 14px;">${campaign.category_icon || 'local_hospital'}</i>
                                    <span>${campaign.category}</span>
                                </div>` : ''
                            }
                            ${campaign.timings ? 
                                `<div class="detail-item compact-detail detail-right">
                                    <i class="fas fa-clock detail-icon"></i>
                                    <span>${campaign.timings}</span>
                                </div>` : ''
                            }
                        </div>
                        
                        <!-- Start and End Date -->
                        <div class="detail-item compact-detail">
                            <i class="fas fa-calendar detail-icon"></i>
                            <span>${campaign.start_date} - ${campaign.end_date}</span>
                        </div>
                        
                        <!-- Location -->
                        ${campaign.location ? 
                            `<div class="detail-item compact-detail">
                                <i class="fas fa-map-marker-alt detail-icon"></i>
                                <span>${campaign.location}</span>
                            </div>` : ''
                        }

                        <!-- Compact Progress Bars -->
                        <div class="compact-progress-section">
                            <!-- Registration Progress -->
                            <div class="compact-progress">
                                <div class="progress-info">
                                    <span class="progress-label-small">Registered: ${campaign.total_registered || 0}/${campaign.expected_patients || 100}</span>
                                    <span class="progress-percent-small">${campaign.registration_progress || 0}%</span>
                                </div>
                                <div class="progress-bar-mini">
                                    <div class="progress-fill-mini registration-mini" style="width: ${campaign.registration_progress || 0}%"></div>
                                </div>
                            </div>
                            
                            <!-- Sponsorship Progress -->
                            <div class="compact-progress">
                                <div class="progress-info">
                                    <span class="progress-label-small">Funded: ₹${campaign.total_sponsored ? new Intl.NumberFormat('en-IN').format(campaign.total_sponsored) : 0}</span>
                                    <span class="progress-percent-small">${campaign.sponsorship_progress || 0}%</span>
                                </div>
                                <div class="progress-bar-mini">
                                    <div class="progress-fill-mini sponsorship-mini" style="width: ${campaign.sponsorship_progress || 0}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Section: 100% Action Buttons -->
                <div class="card-bottom-section">
                    <div class="campaign-actions compact-actions"style="padding:0!important;margin:0!important;">
                        ${campaign.end_date && new Date(campaign.end_date) < new Date() 
                            ? `<button class="btn-register compact-btn" disabled style="opacity: 0.6; cursor: not-allowed;">
                                <i class="fas fa-clock"></i><span class="btn-text-small">Closed</span>
                               </button>`
                            : `<button onclick="handleRegistration(${campaign.id}, ${campaign.registration_payment || 0})" class="btn-register compact-btn">
                                <i class="fas fa-user-plus"></i><span class="btn-text-small">Register</span>
                               </button>`
                        }
                        <button onclick="navigateToSponsors()" class="btn-sponsor compact-btn">
                            <i class="fas fa-hand-holding-heart"></i><span class="btn-text-small">Sponsor</span>
                        </button>
                        <button onclick="navigateToCampaign(${campaign.id})" class="btn-view compact-btn">
                            <i class="fas fa-eye"></i><span class="btn-text-small">View</span>
                        </button>
                        <button onclick="shareCampaign(${campaign.id})" class="btn-share compact-btn">
                            <i class="fas fa-share-alt"></i><span class="btn-text-small">Share</span>
                        </button>
                    </div>
                </div>
            </div>
            </div>
        </div>
    `).join('');

    campaignsGrid.innerHTML = locationInfo + campaignCards;
    console.log('Search results displayed in campaignsGrid:', campaigns.length, 'campaigns');
}

console.log('Global search functions defined:', {
    performUniversalSearch: typeof window.performUniversalSearch,
    hideSuggestions: typeof window.hideSuggestions,
    showLoadingState: typeof window.showLoadingState,
    displaySearchResults: typeof window.displaySearchResults
});

// Define searchByCategory function globally before DOM is ready
window.searchByCategory = function(categoryName) {
    console.log('Searching by category:', categoryName);
    try {
        // Redirect to campaigns page with category as search parameter
        const campaignsUrl = "{{ route('user.campaigns') }}";
        console.log('Campaigns URL:', campaignsUrl);
        const searchParams = new URLSearchParams({ search: categoryName });
        const fullUrl = `${campaignsUrl}?${searchParams.toString()}`;
        console.log('Full URL:', fullUrl);
        window.location.href = fullUrl;
    } catch (error) {
        console.error('Error in searchByCategory:', error);
        alert('Error redirecting to campaigns page: ' + error.message);
    }
};

console.log('searchByCategory function defined:', typeof window.searchByCategory);

document.addEventListener('DOMContentLoaded', function() {
    // Categories are now loaded server-side using Blade templates




    // Get current location
    document.getElementById('getCurrentLocation').addEventListener('click', function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                // Use reverse geocoding to get location name
                fetch(`https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${lat}&longitude=${lng}&localityLanguage=en`)
                    .then(response => response.json())
                    .then(data => {
                        const locationInput = document.getElementById('locationSearch');
                        locationInput.value = data.city + ', ' + data.principalSubdivision;
                    })
                    .catch(error => {
                        console.error('Error getting location:', error);
                        alert('Unable to get your location. Please enter manually.');
                    });
            }, function(error) {
                console.error('Geolocation error:', error);
                alert('Location access denied. Please enter your location manually.');
            });
        } else {
            alert('Geolocation is not supported by this browser.');
        }
    });

    // Search functionality
    document.getElementById('searchBtn').addEventListener('click', function() {
        const searchTerm = document.getElementById('campaignSearch').value;
        const location = document.getElementById('locationSearch').value;
        
        const url = new URL('{{ route("user.campaigns") }}');
        if (searchTerm) url.searchParams.set('search', searchTerm);
        if (location) url.searchParams.set('location', location);
        
        window.location.href = url.toString();
    });

    // Define variables for event listeners
    const searchInput = document.getElementById('campaignSearch');
    const locationInput = document.getElementById('locationSearch');

    // Enhanced event listeners for search inputs
    if (searchInput) {
        // Campaign search input events
        searchInput.addEventListener('input', function(event) {
            clearTimeout(searchTimeout);
            const value = this.value.trim();
            
            if (value.length === 0) {
                window.hideSuggestions();
                return;
            }
            
            searchTimeout = setTimeout(window.performUniversalSearch, 300);
        });

        searchInput.addEventListener('focus', function() {
            if (this.value.trim().length > 0) {
                searchTimeout = setTimeout(window.performUniversalSearch, 100);
            }
        });

        searchInput.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                const searchTerm = this.value.trim();
                const location = locationInput ? locationInput.value.trim() : '';
                
                if (searchTerm || location) {
                    // Navigate to full campaigns page with search
                    const url = new URL('{{ route("user.campaigns") }}');
                    if (searchTerm) url.searchParams.set('search', searchTerm);
                    if (location) url.searchParams.set('location', location);
                    window.location.href = url.toString();
                }
            }
        });
    }

    if (locationInput) {
        // Location search input events
        locationInput.addEventListener('input', function(event) {
            clearTimeout(searchTimeout);
            const value = this.value.trim();
            
            if (value.length === 0 && (!searchInput || searchInput.value.trim().length === 0)) {
                hideSuggestions();
                return;
            }
            
            searchTimeout = setTimeout(window.performUniversalSearch, 300);
        });

        locationInput.addEventListener('focus', function() {
            const searchTerm = searchInput ? searchInput.value.trim() : '';
            if (this.value.trim().length > 0 || searchTerm.length > 0) {
                searchTimeout = setTimeout(window.performUniversalSearch, 100);
            }
        });

        locationInput.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                const location = this.value.trim();
                const searchTerm = searchInput ? searchInput.value.trim() : '';
                
                if (searchTerm || location) {
                    // Navigate to full campaigns page with search
                    const url = new URL('{{ route("user.campaigns") }}');
                    if (searchTerm) url.searchParams.set('search', searchTerm);
                    if (location) url.searchParams.set('location', location);
                    window.location.href = url.toString();
                }
            }
        });
    }

    // Hide suggestions when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.search-location-section')) {
            suggestionsDiv.style.display = 'none';
        }
    });

    // Enhanced search button functionality
    const searchBtn = document.getElementById('searchBtn');
    if (searchBtn) {
        searchBtn.addEventListener('click', function(event) {
            event.preventDefault();
            
            const searchTerm = searchInput ? searchInput.value.trim() : '';
            const location = locationInput ? locationInput.value.trim() : '';
            
            if (searchTerm || location) {
                // Navigate to full campaigns page with search parameters
                const url = new URL('{{ route("user.campaigns") }}');
                if (searchTerm) url.searchParams.set('search', searchTerm);
                if (location) url.searchParams.set('location', location);
                window.location.href = url.toString();
            } else {
                // If no search terms, just go to campaigns page
                window.location.href = '{{ route("user.campaigns") }}';
            }
        });
    }

    // Select campaign from search results
    window.selectCampaign = function(campaignUrl) {
        if (campaignUrl) {
            window.location.href = campaignUrl;
        }
    };

    // Clear search function
    window.clearSearch = function() {
        if (searchInput) searchInput.value = '';
        if (locationInput) locationInput.value = '';
        hideSuggestions();
    };

    // Advanced search function for external use
    window.performSearch = function(term, loc) {
        if (searchInput && term) searchInput.value = term;
        if (locationInput && loc) locationInput.value = loc;
        window.performUniversalSearch();
    };

    // Image slider
    const sliderImages = [
        'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="800" height="400" viewBox="0 0 800 400"%3E%3Crect width="800" height="400" fill="%23667eea"/%3E%3Ctext x="400" y="200" text-anchor="middle" fill="white" font-family="Roboto" font-size="32"%3EHealthcare Innovation%3C/text%3E%3C/svg%3E',
        'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="800" height="400" viewBox="0 0 800 400"%3E%3Crect width="800" height="400" fill="%234CAF50"/%3E%3Ctext x="400" y="200" text-anchor="middle" fill="white" font-family="Roboto" font-size="32"%3ECommunity Health%3C/text%3E%3C/svg%3E',
        'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="800" height="400" viewBox="0 0 800 400"%3E%3Crect width="800" height="400" fill="%23E7A51B"/%3E%3Ctext x="400" y="200" text-anchor="middle" fill="white" font-family="Roboto" font-size="32"%3EAffordable Care%3C/text%3E%3C/svg%3E'
    ];

    function initializeSlider() {
        const sliderWrapper = document.getElementById('imageSlider');
        const indicatorsContainer = document.getElementById('sliderIndicators');
        
        sliderWrapper.innerHTML = sliderImages.map(img => 
            `<div class="slider-slide"><img src="${img}" alt="Healthcare"></div>`
        ).join('');
        
        indicatorsContainer.innerHTML = sliderImages.map((_, index) => 
            `<div class="slider-indicator ${index === 0 ? 'active' : ''}" onclick="goToSlide(${index})"></div>`
        ).join('');
        
        let currentSlide = 0;
        
        window.goToSlide = function(index) {
            currentSlide = index;
            sliderWrapper.style.transform = `translateX(-${index * 100}%)`;
            
            document.querySelectorAll('.slider-indicator').forEach((indicator, i) => {
                indicator.classList.toggle('active', i === index);
            });
        };
        
        // Auto-play slider
        setInterval(() => {
            currentSlide = (currentSlide + 1) % sliderImages.length;
            goToSlide(currentSlide);
        }, 5000);
        
        // Navigation buttons
        document.querySelector('.prev-btn').addEventListener('click', () => {
            currentSlide = (currentSlide - 1 + sliderImages.length) % sliderImages.length;
            goToSlide(currentSlide);
        });
        
        document.querySelector('.next-btn').addEventListener('click', () => {
            currentSlide = (currentSlide + 1) % sliderImages.length;
            goToSlide(currentSlide);
        });
    }

    // Initialize everything
    updateLiveStats();
    initializeSlider();
    
    // Add click handlers for offering cards
    document.querySelectorAll('.offering-card[data-link]').forEach(card => {
        card.addEventListener('click', function() {
            window.location.href = this.getAttribute('data-link');
        });
    });
    
    // Update stats every 30 seconds
    setInterval(updateLiveStats, 30000);
    
    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.search-box')) {
            document.getElementById('searchSuggestions').style.display = 'none';
        }
    });
});



// Global variables for location services
let userLatitude = null;
let userLongitude = null;
let autocomplete = null;

// Initialize Google Maps location services
function initLocationSearch() {
    console.log('Google Maps API loaded, initializing location search...');
    
    // Initialize autocomplete for location input
    const locationInput = document.getElementById('locationSearch');
    if (locationInput) {
        autocomplete = new google.maps.places.Autocomplete(locationInput, {
            types: ['(cities)'],
            componentRestrictions: { country: 'IN' } // Restrict to India
        });

        // Listen for place selection
        autocomplete.addListener('place_changed', function() {
            const place = autocomplete.getPlace();
            
            if (place.geometry && place.geometry.location) {
                userLatitude = place.geometry.location.lat();
                userLongitude = place.geometry.location.lng();
                
                console.log('Location selected:', place.formatted_address);
                console.log('Coordinates:', userLatitude, userLongitude);
                
                // Trigger search with new location
                performLocationBasedSearch();
            }
        });
    }

    // Initialize current location button
    const getCurrentLocationBtn = document.getElementById('getCurrentLocation');
    if (getCurrentLocationBtn) {
        getCurrentLocationBtn.addEventListener('click', function() {
            getCurrentLocation();
        });
    }

    // Automatically detect location on page load
    setTimeout(() => {
        detectLocationOnLoad();
    }, 1000); // Wait 1 second for page to fully load
}

// Automatically detect user's location on page load
function detectLocationOnLoad() {
    const locationInput = document.getElementById('locationSearch');
    const locationBtn = document.getElementById('getCurrentLocation');
    
    // Only auto-detect if location input is empty
    if (locationInput && !locationInput.value.trim()) {
        console.log('Auto-detecting location on page load...');
        
        if (navigator.geolocation) {
            // Show subtle loading indicator in placeholder and button
            locationInput.placeholder = 'Detecting your location...';
            if (locationBtn) {
                locationBtn.innerHTML = '<span class="material-icons">hourglass_top</span>';
                locationBtn.disabled = true;
            }
            
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    userLatitude = position.coords.latitude;
                    userLongitude = position.coords.longitude;
                    
                    console.log('Auto-detected location:', userLatitude, userLongitude);
                    
                    // Reverse geocode to get address
                    const geocoder = new google.maps.Geocoder();
                    const latlng = new google.maps.LatLng(userLatitude, userLongitude);
                    
                    geocoder.geocode({ location: latlng }, function(results, status) {
                        if (status === 'OK' && results[0]) {
                            // Get a shorter, more readable address
                            let address = results[0].formatted_address;
                            
                            // Try to get city/area name instead of full address
                            for (let i = 0; i < results.length; i++) {
                                const result = results[i];
                                if (result.types.includes('locality') || result.types.includes('administrative_area_level_2')) {
                                    address = result.formatted_address;
                                    break;
                                }
                            }
                            
                            locationInput.value = address;
                            locationInput.placeholder = 'location';
                            
                            // Show success indicator briefly
                            if (locationBtn) {
                                locationBtn.innerHTML = '<span class="material-icons" style="color: #4CAF50;">check_circle</span>';
                                setTimeout(() => {
                                    locationBtn.innerHTML = '<span class="material-icons">my_location</span>';
                                    locationBtn.disabled = false;
                                }, 1500);
                            }
                            
                            console.log('Auto-detected address:', address);
                            
                            // Optional: Show a subtle notification
                            if (typeof toastr !== 'undefined') {
                                toastr.success('Location detected automatically', '', {
                                    timeOut: 2000,
                                    positionClass: 'toast-bottom-right',
                                    closeButton: false
                                });
                            }
                            
                            // Optional: Automatically trigger search with detected location
                            // performLocationBasedSearch();
                        } else {
                            console.error('Auto-geocoding failed:', status);
                            resetLocationUI();
                        }
                    });
                },
                function(error) {
                    console.log('Auto-location detection failed (silent):', error.message);
                    resetLocationUI();
                },
                {
                    enableHighAccuracy: false, // Use less accurate but faster detection
                    timeout: 8000,
                    maximumAge: 600000 // 10 minutes cache
                }
            );
        } else {
            console.log('Geolocation not supported for auto-detection');
        }
    }
}

// Reset location UI elements
function resetLocationUI() {
    const locationInput = document.getElementById('locationSearch');
    const locationBtn = document.getElementById('getCurrentLocation');
    
    if (locationInput) {
        locationInput.placeholder = 'location';
    }
    if (locationBtn) {
        locationBtn.innerHTML = '<span class="material-icons">my_location</span>';
        locationBtn.disabled = false;
    }
}

// Get user's current location
function getCurrentLocation() {
    const btn = document.getElementById('getCurrentLocation');
    const locationInput = document.getElementById('locationSearch');
    
    if (navigator.geolocation) {
        // Show loading state
        btn.innerHTML = '<span class="material-icons">hourglass_top</span>';
        btn.disabled = true;
        
        navigator.geolocation.getCurrentPosition(
            function(position) {
                userLatitude = position.coords.latitude;
                userLongitude = position.coords.longitude;
                
                console.log('Current location obtained:', userLatitude, userLongitude);
                
                // Reverse geocode to get address
                const geocoder = new google.maps.Geocoder();
                const latlng = new google.maps.LatLng(userLatitude, userLongitude);
                
                geocoder.geocode({ location: latlng }, function(results, status) {
                    if (status === 'OK' && results[0]) {
                        locationInput.value = results[0].formatted_address;
                        
                        // Trigger search with current location
                        performLocationBasedSearch();
                    } else {
                        console.error('Geocoding failed:', status);
                    }
                    
                    // Reset button state
                    btn.innerHTML = '<span class="material-icons">my_location</span>';
                    btn.disabled = false;
                });
            },
            function(error) {
                console.error('Geolocation error:', error);
                alert('Unable to get your location. Please enter manually.');
                
                // Reset button state
                btn.innerHTML = '<span class="material-icons">my_location</span>';
                btn.disabled = false;
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 300000 // 5 minutes
            }
        );
    } else {
        alert('Geolocation is not supported by this browser.');
    }
}

// Perform location-based search
function performLocationBasedSearch() {
    const searchTerm = document.getElementById('campaignSearch').value;
    const locationTerm = document.getElementById('locationSearch').value;
    
    if (userLatitude && userLongitude) {
        console.log('Performing location-based search with coordinates:', userLatitude, userLongitude);
        
        // Call the enhanced search function with coordinates
        searchCampaigns(searchTerm, locationTerm, userLatitude, userLongitude);
    } else {
        // Fallback to text-based search
        searchCampaigns(searchTerm, locationTerm);
    }
}

// Calculate distance between two points using Haversine formula
function calculateDistance(lat1, lon1, lat2, lon2) {
    const R = 6371; // Radius of the Earth in kilometers
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    
    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
              Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
              Math.sin(dLon / 2) * Math.sin(dLon / 2);
    
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    const distance = R * c; // Distance in kilometers
    
    return Math.round(distance * 10) / 10; // Round to 1 decimal place
}

// Enhanced search function with coordinates
function searchCampaigns(searchTerm = '', locationTerm = '', latitude = null, longitude = null) {
    // Store coordinates for universal search
    if (latitude && longitude) {
        userLatitude = latitude;
        userLongitude = longitude;
    }
    
    // Set the input values
    const searchInput = document.getElementById('campaignSearch');
    const locationInput = document.getElementById('locationSearch');
    
    if (searchInput && searchTerm) searchInput.value = searchTerm;
    if (locationInput && locationTerm) locationInput.value = locationTerm;
    
    // Call the universal search function
    if (window.performUniversalSearch) {
        window.performUniversalSearch();
    }
}

    // Wait for jQuery to be available
    function waitForjQuery(callback) {
        if (typeof jQuery !== 'undefined') {
            callback();
        } else {
            setTimeout(function() {
                waitForjQuery(callback);
            }, 50);
        }
    }

    waitForjQuery(function() {
        $(function() {
            // Authentication and CSRF setup
            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            const loginUrl = '{{ route("user.login") }}';
           

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

        // Set up Echo listener for authenticated users
        if (window.isUserLoggedIn && window.currentUserId) {
            Echo.private(`user.${window.currentUserId}`)
                .listen('.message.received', e => toastr.success(e.message, '📨 New Message'));
        }

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

        // Enhanced search functionality for main search bar
        // Enhanced search functionality for home page (AJAX search suggestions)
        function enhancedSearch() {
            console.log('enhancedSearch called - checking window.performUniversalSearch:', typeof window.performUniversalSearch);
            const searchValue = $('#campaignSearch').val() ? $('#campaignSearch').val().toLowerCase().trim() : '';
            const locationValue = $('#locationSearch').val() ? $('#locationSearch').val().toLowerCase().trim() : '';

            console.log('Enhanced search called:', { searchValue, locationValue });

            // If both inputs are empty, hide suggestions
            if (searchValue === '' && locationValue === '') {
                hideSuggestions();
                return;
            }

            // Check if we're on campaigns page with actual campaign cards
            if ($('.campaign-item').length > 0) {
                console.log('Found campaign items, filtering locally');
                filterCampaignCards(searchValue, locationValue);
            } else {
                console.log('No campaign items found, using AJAX search');
                // This is the home page, use AJAX search for suggestions
                if (typeof window.performUniversalSearch === 'function') {
                    window.performUniversalSearch();
                } else {
                    console.error('window.performUniversalSearch is not available:', typeof window.performUniversalSearch);
                }
            }
        }

        // Function to filter campaign cards (for campaigns page)
        function filterCampaignCards(searchValue, locationValue) {
            let visibleCount = 0;
            
            $('.campaign-item').each(function() {
                const card = $(this);
                const title = (card.data('title') || '').toLowerCase();
                const location = (card.data('location') || '').toLowerCase();
                const specializations = (card.data('specializations') || '').toLowerCase();
                const doctorName = (card.data('doctor') || '').toLowerCase();
                const campType = (card.data('type') || '').toLowerCase();

                // Universal search - matches any field
                const matchesUniversalSearch = searchValue === '' || 
                    title.includes(searchValue) ||
                    location.includes(searchValue) ||
                    specializations.includes(searchValue) ||
                    doctorName.includes(searchValue) ||
                    campType.includes(searchValue);

                // Location search - matches location specifically
                const matchesLocationSearch = locationValue === '' || 
                    location.includes(locationValue);

                // Show card if both searches match
                if (matchesUniversalSearch && matchesLocationSearch) {
                    card.closest('.col-md-6').show();
                    visibleCount++;
                } else {
                    card.closest('.col-md-6').hide();
                }
            });

            // Show/hide no results message
            if (visibleCount > 0) {
                $('#noResults').hide();
            } else {
                showNoResultsMessage();
            }
        }

        // Function to show no results message
        function showNoResultsMessage() {
            if ($('#noResults').length === 0) {
                // Create no results element if it doesn't exist
                const noResultsHtml = `
                    <div id="noResults" class="no-results text-center py-5">
                        <i class="fas fa-search" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;"></i>
                        <h3>No Campaigns Found</h3>
                        <p>No campaigns match your search criteria. Try adjusting your search terms.</p>
                        <button onclick="clearSearch()" class="btn btn-primary mt-3">
                            <i class="fas fa-refresh me-2"></i>Clear Search
                        </button>
                    </div>
                `;
                $('.row').last().append(noResultsHtml);
            } else {
                $('#noResults').show();
            }
        }

        // Test function to debug search inputs
        function testSearchInputs() {
            console.log('Testing search inputs...');
            
            const campaignInput = document.getElementById('campaignSearch');
            const locationInput = document.getElementById('locationSearch');
            const suggestionsDiv = document.getElementById('searchSuggestions');
            
            console.log('Campaign input:', campaignInput);
            console.log('Location input:', locationInput);
            console.log('Suggestions div:', suggestionsDiv);
            
            if (campaignInput) {
                console.log('Campaign input value:', campaignInput.value);
            } else {
                console.error('Campaign input not found!');
            }
            
            if (locationInput) {
                console.log('Location input value:', locationInput.value);
            } else {
                console.error('Location input not found!');
            }

            if (suggestionsDiv) {
                console.log('Suggestions div found');
                // Test by showing a simple message
                suggestionsDiv.innerHTML = '<div style="padding: 10px; background: #f0f0f0;">Search is working! Type something...</div>';
                suggestionsDiv.style.display = 'block';
                setTimeout(() => {
                    suggestionsDiv.style.display = 'none';
                }, 3000);
            } else {
                console.error('Suggestions div not found!');
            }
        }

        // Event listeners for search inputs with debugging
        $('#campaignSearch').on('input', function() {
            console.log('Campaign search input event triggered:', $(this).val());
            enhancedSearch();
        });

        $('#locationSearch').on('input', function() {
            console.log('Location search input event triggered:', $(this).val());
            enhancedSearch();
        });

        // Test inputs on page load
        $(document).ready(function() {
            console.log('Document ready - testing inputs');
            testSearchInputs();
            enhancedSearch();
        });

        // Clear search function
        window.clearSearch = function() {
            console.log('Clearing search inputs');
            $('#campaignSearch').val('');
            $('#locationSearch').val('');
            enhancedSearch();
        };

        // Vanilla JavaScript fallback for search inputs
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOMContentLoaded - setting up vanilla JS listeners');
            
            const campaignInput = document.getElementById('campaignSearch');
            const locationInput = document.getElementById('locationSearch');
            
            if (campaignInput) {
                console.log('Adding vanilla JS listener to campaign input');
                campaignInput.addEventListener('input', function() {
                    console.log('Vanilla JS - Campaign input changed:', this.value);
                    // Trigger both universal search and enhanced search
                    if (typeof window.performUniversalSearch === 'function') {
                        clearTimeout(searchTimeout);
                        searchTimeout = setTimeout(window.performUniversalSearch, 300);
                    }
                    if (typeof enhancedSearch === 'function') {
                        enhancedSearch();
                    }
                });
            }
            
            if (locationInput) {
                console.log('Adding vanilla JS listener to location input');
                locationInput.addEventListener('input', function() {
                    console.log('Vanilla JS - Location input changed:', this.value);
                    // Trigger both universal search and enhanced search
                    if (typeof window.performUniversalSearch === 'function') {
                        clearTimeout(searchTimeout);
                        searchTimeout = setTimeout(window.performUniversalSearch, 300);
                    }
                    if (typeof enhancedSearch === 'function') {
                        enhancedSearch();
                    }
                });
            }
        });

   
        // Clear filters function
     
        // Filter event listeners - updated for new structure

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
        if (window.isUserLoggedIn && window.currentUserId) {
            // You can retrieve user data via AJAX if needed
            // For now, we'll skip auto-filling as the user object is not available
            // in pure JavaScript without making an API call
        }

        // Navigate to campaign details
        window.navigateToCampaign = function(campaignId) {
            window.location.href = `/user/campaigns/${campaignId}`;
        }
        
        // Navigate to sponsors page
        window.navigateToSponsors = function() {
            window.location.href = '/user/sponsors';
        }
                     
        
        // Share campaign function with referral tracking
window.shareCampaign = function(campaignId, referralId = '') {
    const baseUrl = window.location.origin;
    let shareUrl = `${baseUrl}/user/campaigns/${campaignId}`;

    if (window.isUserLoggedIn && referralId) {
        shareUrl = `${baseUrl}/user/register?ref=${referralId}&campaign=${campaignId}`;
    }

    const campaignCard = document.querySelector(`[data-campaign-id="${campaignId}"]`)
        ?.closest('.campaign-item');
    const title = campaignCard?.querySelector('.campaign-title')?.innerText || 'Healthcare Campaign';
    const location = campaignCard?.querySelector('.detail-item')?.innerText || 'Location TBA';

    const shareText = `🏥 ${title}\n📍 ${location}\n\nCheck out this healthcare campaign!\n\n${shareUrl}`;

    if (navigator.share) {
        navigator.share({
            title: title,
            text: shareText,
            url: shareUrl
        }).catch(err => console.log('Share failed:', err));
    } else {
        navigator.clipboard.writeText(shareText).then(() => {
            Swal.fire({
                title: 'Link Copied!',
                text: 'The campaign link has been copied to your clipboard.',
                icon: 'success',
                timer: 3000
            });
        });
    }
}

        
        // Social media sharing functions
        window.shareOnWhatsApp = function(text) {
            window.open(`https://wa.me/?text=${text}`, '_blank');
        }
        
        window.shareOnTwitter = function(text) {
            window.open(`https://twitter.com/intent/tweet?text=${text}`, '_blank');
        }
        
        window.shareOnFacebook = function(url) {
            window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`, '_blank');
        };

        // Check if user has clicked a referral link and store it
        window.checkReferralFromURL = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const ref = urlParams.get('ref');
            const campaign = urlParams.get('campaign');
            
            if (ref && campaign) {
                // Store referral data for when user registers
                const referralData = {
                    referral_id: ref,
                    campaign_id: campaign,
                    timestamp: Date.now(),
                    source_page: 'campaigns'
                };
                
                localStorage.setItem('referral_data', JSON.stringify(referralData));
                
                console.log('Referral link detected and stored:', referralData);
                
                // Show a subtle notification that they came through a referral
                if (typeof toastr !== 'undefined') {
                    toastr.info('You\'re visiting through a referral link! Register to help the referrer earn rewards.', 'Referral Detected', {
                        timeOut: 5000,
                        positionClass: 'toast-top-right'
                    });
                }
                
                // Clean URL by removing ref parameters (optional)
                const cleanUrl = window.location.pathname;
                window.history.replaceState({}, document.title, cleanUrl);
            }
        };

        // Check for any stored referral data and show register prompt
        window.checkStoredReferral = function() {
            const storedreferral = localStorage.getItem('referral_data');
            if (storedReferral) {
                try {
                    const referralData = JSON.parse(storedReferral);
                    // Only show if referral is recent (within 24 hours)
                    const hoursSinceStored = (Date.now() - referralData.timestamp) / (1000 * 60 * 60);
                    
                    if (hoursSinceStored < 24) {
                        console.log('Active referral data found:', referralData);
                        
                        // You could show a floating action button or banner to encourage registration
                        window.showReferralPrompt(referralData);
                    } else {
                        // Clear old referral data
                        localStorage.removeItem('referral_data');
                    }
                } catch (e) {
                    console.error('Error parsing stored referral data:', e);
                    localStorage.removeItem('referral_data');
                }
            }
        };

        // Show referral prompt for registration
        window.showReferralPrompt = function(referralData) {
            // Check if user is logged in via JavaScript variable
            const isUserLoggedIn = window.isUserLoggedIn || false;
            
            // Only show if user is not logged in
            if (!isUserLoggedIn && !document.getElementById('referralPrompt')) {
                const promptHtml = `
                    <div id="referralPrompt" style="
                        position: fixed;
                        bottom: 20px;
                        right: 20px;
                        background: linear-gradient(135deg, #E7A51B, #c5901a);
                        color: white;
                        padding: 15px 20px;
                        border-radius: 10px;
                        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
                        z-index: 1000;
                        max-width: 300px;
                        animation: slideInUp 0.5s ease-out;
                    ">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-gift" style="font-size: 1.5rem;"></i>
                            <div>
                                <strong>Referral Bonus!</strong>
                                <p style="margin: 5px 0 0 0; font-size: 1.1rem  !important;">
                                    Register now to help your referrer earn rewards!
                                </p>
                            </div>
                            <button onclick="closeReferralPrompt()" style="
                                background: none;
                                border: none;
                                color: white;
                                font-size: 1.2rem;
                                cursor: pointer;
                                padding: 0;
                                margin-left: auto;
                            ">&times;</button>
                        </div>
                        <div style="margin-top: 10px;">
                            <a href="/user/register" style="
                                background: rgba(255,255,255,0.2);
                                color: white;
                                padding: 8px 15px;
                                border-radius: 5px;
                                text-decoration: none;
                                font-weight: bold;
                                display: inline-block;
                                transition: all 0.3s ease;
                            " onmouseover="this.style.background='rgba(255,255,255,0.3)'" 
                               onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                                Register Now
                            </a>
                        </div>
                    </div>
                `;
                
                document.body.insertAdjacentHTML('beforeend', promptHtml);
            }
        };

        // Close referral prompt
        window.closeReferralPrompt = function() {
            const prompt = document.getElementById('referralPrompt');
            if (prompt) {
                prompt.style.animation = 'slideOutDown 0.5s ease-in';
                setTimeout(() => prompt.remove(), 500);
            }
        };

        // Add CSS animation for referral prompt
        const referralPromptStyles = `
            <style>
                @keyframes slideInUp {
                    from {
                        transform: translateY(100%);
                        opacity: 0;
                    }
                    to {
                        transform: translateY(0);
                        opacity: 1;
                    }
                }
                @keyframes slideOutDown {
                    from {
                        transform: translateY(0);
                        opacity: 1;
                    }
                    to {
                        transform: translateY(100%);
                        opacity: 0;
                    }
                }
            </style>
        `;
        document.head.insertAdjacentHTML('beforeend', referralPromptStyles);

        // Initialize referral checking on page load
        $(document).ready(function() {
            if (window.checkReferralFromURL) window.checkReferralFromURL();
            setTimeout(function() {
                if (window.checkStoredReferral) window.checkStoredReferral();
            }, 2000); // Delay to let page load
        });

        // Initialize tooltips and other UI enhancements
        $('[data-toggle="tooltip"]').tooltip();

        // Add loading animation to buttons on click
        $('.btn-register, .btn-view').on('click', function() {
            $(this).addClass('loading');
            setTimeout(() => $(this).removeClass('loading'), 2000);
        });
        
        // Sponsor Modal Functions
        window.openSponsorModal = function(campaignId, amount = 0) {
            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            const loginUrl  = '{{ route("user.login") }}';

            
            if (!window.isUserLoggedIn) {
                return Swal.fire({
                    title: 'Login Required',
                    text:  'Please log in to sponsor.',
                    icon:  'info',
                    showCancelButton: true,
                    confirmButtonText: 'Login'
                }).then(r => r.isConfirmed && (window.location = loginUrl));
            }

            // populate hidden fields
            $('#sponsorCampaignId').val(campaignId);
            $('#sponsorUserId').val('{{ auth("user")->id() }}');
            
            // If amount is provided, pre-fill it
            if (amount > 0) {
                $('input[name="sponsorship_amount"]').val(amount);
            }

            // show modal
            $('#sponsorRegistrationModal').show();
            $('body').css('overflow','hidden');
        };

        window.closeSponsorModal = function() {
            $('#sponsorRegistrationModal').hide();
            $('body').css('overflow','auto');
            // Reset form and button state
            $('#sponsorRegistrationForm')[0].reset();
            $('#sponsorSubmitBtn').prop('disabled', false).html('<i class="fas fa-hand-holding-heart me-2"></i>Sponsor Now');
        };

        // Enhanced success message function
        window.showSponsorSuccessMessage = function(message, redirectUrl) {
            return Swal.fire({
                title: '🎉 Payment Successful!',
                html: `
                    <div style="text-align: center; padding: 20px;">
                        <div style="font-size: 4rem; color: #F98900; margin-bottom: 20px;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <p style="font-size: 1.2rem; color: #2c3e50; margin-bottom: 15px; line-height: 1.5;">
                            ${message || 'Thank you for sponsoring this campaign!'}
                        </p>
                        <p style="font-size: 1rem; color: #7f8c8d;">
                            Your contribution makes a real difference in people's lives.
                        </p>
                    </div>
                `,
                icon: 'success',
                confirmButtonText: '🎯 Continue',
                confirmButtonColor: '#F98900',
                allowOutsideClick: false,
                allowEscapeKey: false,
                timer: 6000,
                timerProgressBar: true
            }).then(() => {
                // Redirect if provided, otherwise reload page
                if (redirectUrl) {
                    window.location.href = redirectUrl;
                } else {
                    window.location.reload();
                }
            });
        };

        // Event listeners for modal
        $('.close').on('click', function() {
            if ($(this).closest('#sponsorRegistrationModal').length) {
                closeSponsorModal();
            }
        });
        
        $(window).on('click', function(e) { 
            if (e.target.id === 'sponsorRegistrationModal') {
                closeSponsorModal();
            }
        });
        
        $(document).on('keydown', function(e) { 
            if (e.key === 'Escape' && $('#sponsorRegistrationModal').is(':visible')) {
                closeSponsorModal();
            }
        });

        // Form submission with Razorpay payment
        // Sponsor form submission with Razorpay payment (copied from home.blade.php)
        $('#sponsorRegistrationForm').on('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Get the amount from the form
            const amount = parseFloat($('input[name="sponsorship_amount"]').val() || 0);
            
            if (amount <= 0) {
                Swal.fire('Error', 'Please enter a valid sponsorship amount.', 'error');
                return;
            }

            const amountPaise = amount * 100; // Convert to paise for Razorpay
console.log('sponsor amount:', amountPaise);
            // Prepare form data
            const formData = new FormData(form);
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            const options = {
                key: '{{ config("services.razorpay.key") }}',
                amount: amountPaise,
                currency: 'INR',
                name: 'FreeDoctor',
                description: 'Campaign Sponsorship',
                image: '{{ asset("images/logo.png") }}',
                prefill: {
                    name: $('input[name="name"]').val(),
                    contact: $('input[name="phone_number"]').val()
                },
                theme: { 
                    color: '#10b981' 
                },
                handler: function(response) {
                    // Payment successful, add payment ID to form data
                    formData.append('razorpay_payment_id', response.razorpay_payment_id);
                    formData.append('payment_status', 'success');

                    // Submit the form with payment details
                    $.ajax({
                        url: '{{ route("user.campaigns.sponsors.payment") }}',
                        method: 'POST',
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        processData: false,
                        contentType: false,
                        beforeSend: function() {
                            $('#sponsorSubmitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Processing...');
                        },
                        success: function(data) {
                            // Close modal immediately
                            closeSponsorModal();
                            
                            // Show brief processing message
                            Swal.fire({
                                title: 'Processing Payment...',
                                html: '<div style="text-align: center;"><i class="fas fa-credit-card fa-3x" style="color: #F98900; margin-bottom: 15px; animation: pulse 1.5s infinite;"></i><p>Please wait while we confirm your payment...</p></div>',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                showConfirmButton: false,
                                timer: 1500,
                                timerProgressBar: true,
                                customClass: {
                                    popup: 'sponsor-processing-popup'
                                }
                            }).then(() => {
                                // Show enhanced success message
                                showSponsorSuccessMessage(
                                    data.message || 'Thank you for sponsoring this campaign! Your contribution makes a difference.',
                                    data.redirect_url
                                );
                            });
                        },
                        error: function(xhr) {
                            let errorMessage = 'Payment processing failed. Please try again.';
                            
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                                const errors = Object.values(xhr.responseJSON.errors).flat();
                                errorMessage = errors.join('\n');
                            }
                            
                            Swal.fire('Error', errorMessage, 'error');
                        },
                        complete: function() {
                            $('#sponsorSubmitBtn').prop('disabled', false).html('<i class="fas fa-hand-holding-heart me-2"></i>Sponsor Now');
                        }
                    });
                },
                modal: {
                    ondismiss: function() {
                        $('#sponsorSubmitBtn').prop('disabled', false).html('<i class="fas fa-hand-holding-heart me-2"></i>Sponsor Now');
                    }
                }
            };

            const rzp = new Razorpay(options);
            rzp.open();
        });
        
        }); // End of $(function(){
    }); // End of waitForjQuery