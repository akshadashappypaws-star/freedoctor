# Campaigns List Partials - Usage Guide

This document explains how to use the two campaign listing partials that have been created to provide reusable campaign display functionality without affecting the existing campaigns page.

## Available Partials

### 1. Enhanced Campaigns List (`campaigns-list-enhanced.blade.php`)
A full-featured partial with search, filtering, and location-based functionality.

### 2. Simple Campaigns List (`campaigns-list-simple.blade.php`) 
A lightweight partial for basic campaign card display.

## Installation & Usage

### Enhanced Campaigns List

```php
@include('user.partials.campaigns-list-enhanced', [
    'campaigns' => $campaigns,
    'showSearch' => true,
    'showFilters' => true,
    'layoutMode' => 'grid',
    'specialties' => $specialties ?? []
])
```

**Parameters:**
- `campaigns` (required): Collection or paginated collection of campaigns
- `showSearch` (optional, default: true): Enable/disable search functionality
- `showFilters` (optional, default: true): Enable/disable filter modal
- `layoutMode` (optional, default: 'grid'): Layout mode ('grid' or 'list')
- `specialties` (optional, default: []): Collection of specialties for filter dropdown

**Features:**
- ✅ Real-time search by title, doctor, specialty, location
- ✅ Location-based search with Google Places Autocomplete
- ✅ Advanced filtering (specialty, type, status, registration fee)
- ✅ Progress bars for registrations and sponsorship
- ✅ Responsive design with mobile optimization
- ✅ Modal-based filtering system
- ✅ Active filters display with clear options
- ✅ Pagination support

### Simple Campaigns List

```php
@include('user.partials.campaigns-list-simple', [
    'campaigns' => $campaigns,
    'showActions' => true,
    'showProgress' => true,
    'columns' => 3
])
```

**Parameters:**
- `campaigns` (required): Collection of campaigns
- `showActions` (optional, default: true): Show action buttons
- `showProgress` (optional, default: true): Show progress bars
- `columns` (optional, default: 3): Number of columns (1-6)

**Features:**
- ✅ Clean, minimal campaign cards
- ✅ Configurable column layout (1-6 columns)
- ✅ Optional progress bars
- ✅ Optional action buttons
- ✅ Responsive design
- ✅ Fast loading with minimal JavaScript

## Usage Examples

### 1. Full-Featured Campaigns Page
```php
<!-- In your blade view -->
@include('user.partials.campaigns-list-enhanced', [
    'campaigns' => $campaigns,
    'showSearch' => true,
    'showFilters' => true,
    'specialties' => $specialties
])
```

### 2. Homepage Campaign Showcase
```php
<!-- Show featured campaigns without search -->
@include('user.partials.campaigns-list-simple', [
    'campaigns' => $featuredCampaigns,
    'showActions' => true,
    'showProgress' => false,
    'columns' => 4
])
```

### 3. Doctor Profile Campaigns
```php
<!-- Show doctor's campaigns in 2 columns -->
@include('user.partials.campaigns-list-simple', [
    'campaigns' => $doctor->campaigns,
    'showActions' => false,
    'showProgress' => true,
    'columns' => 2
])
```

### 4. Sidebar Widget
```php
<!-- Single column layout for sidebar -->
@include('user.partials.campaigns-list-simple', [
    'campaigns' => $recentCampaigns->take(3),
    'showActions' => false,
    'showProgress' => false,
    'columns' => 1
])
```

### 5. Search Results Page
```php
<!-- With search but without filters -->
@include('user.partials.campaigns-list-enhanced', [
    'campaigns' => $searchResults,
    'showSearch' => true,
    'showFilters' => false,
    'specialties' => []
])
```

## Controller Requirements

### For Enhanced Partial
Your controller should provide:

```php
public function index()
{
    $campaigns = Campaign::with(['doctor', 'specialty', 'patientRegistrations', 'campaignSponsors'])
        ->paginate(12);
    
    $specialties = Specialty::all();
    
    return view('your-view', compact('campaigns', 'specialties'));
}
```

### For Simple Partial
Your controller should provide:

```php
public function index()
{
    $campaigns = Campaign::with(['doctor'])
        ->latest()
        ->take(8)
        ->get();
    
    return view('your-view', compact('campaigns'));
}
```

## Database Requirements

### Required Campaign Fields
- `id`, `title`, `description`
- `start_date`, `end_date`
- `location`, `latitude`, `longitude`
- `campaign_image`, `campaign_video_url`
- `registration_payment`, `expected_patients`
- `target_amount`, `amount`
- `camp_type` (medical, surgical, etc.)

### Required Relationships
```php
// In Campaign model
public function doctor()
{
    return $this->belongsTo(Doctor::class);
}

public function patientRegistrations()
{
    return $this->hasMany(PatientRegistration::class);
}

public function campaignSponsors()
{
    return $this->hasMany(CampaignSponsor::class);
}

public function specialty()
{
    return $this->belongsTo(Specialty::class);
}
```

## Styling & Customization

### CSS Variables
Both partials use CSS custom properties that can be overridden:

```css
.campaigns-partial-container {
    --primary-color: #2C2A4C;
    --secondary-color: #E7A51B;
    --success-color: #4CAF50;
    /* ... other variables */
}
```

### Custom Styling
```css
/* Override specific components */
.campaign-card-partial {
    /* Custom card styling */
}

.simple-campaign-card {
    /* Custom simple card styling */
}
```

## JavaScript Integration

### Enhanced Partial
The enhanced partial includes:
- Google Places Autocomplete integration
- Real-time search functionality
- Filter management
- Modal handling

### Simple Partial
The simple partial includes:
- Basic animations
- No external dependencies

## SEO Considerations

### Enhanced Partial
- Structured data support through schema markup
- Meta tag optimization for search results
- Clean URL structure for filtered results

### Simple Partial
- Semantic HTML structure
- Proper heading hierarchy
- Alt text for images

## Performance Notes

### Enhanced Partial
- Includes caching mechanisms for search results
- Lazy loading for images
- Debounced search input
- Optimized for large datasets

### Simple Partial
- Minimal JavaScript footprint
- CSS-only animations
- Optimized for fast rendering
- Perfect for static content

## Browser Support

Both partials support:
- ✅ Chrome 60+
- ✅ Firefox 55+
- ✅ Safari 12+
- ✅ Edge 79+
- ✅ Mobile browsers (iOS Safari, Chrome Mobile)

## Migration from Original Campaigns Page

### Step 1: Update Existing Views
Replace campaign listing sections with partials:

```php
// Before
<div class="campaigns-grid">
    <!-- Complex campaign display code -->
</div>

// After
@include('user.partials.campaigns-list-enhanced', [
    'campaigns' => $campaigns,
    'specialties' => $specialties
])
```

### Step 2: Controller Optimization
Ensure your controllers provide the required data structure.

### Step 3: Testing
Test the partial in isolation before integrating into existing pages.

## Troubleshooting

### Common Issues

1. **Campaigns not displaying**
   - Check that `$campaigns` is properly passed
   - Verify campaign relationships are loaded

2. **Search not working**
   - Ensure CSRF token is available
   - Check search route exists

3. **Styling conflicts**
   - Use unique container classes
   - Override CSS variables if needed

4. **JavaScript errors**
   - Check for jQuery dependency
   - Verify Google Maps API key

### Debug Mode
Enable debug logging by adding to your .env:
```
CAMPAIGNS_PARTIAL_DEBUG=true
```

## Support & Updates

These partials are designed to be:
- Self-contained and independent
- Easy to update without affecting main code
- Backwards compatible with existing data structures
- Extensible for future requirements

For issues or enhancements, check the implementation details in the partial files themselves.
