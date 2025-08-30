<?php
namespace App\Http\Controllers\User;
use App\Events\MessageReceived;
use App\Events\DoctorMessageSent;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ReferralController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\User;
use App\Models\PatientRegistration;
use App\Models\PatientPayment;
use App\Models\CampaignSponsor;
use App\Models\BusinessOrganizationRequest;
use App\Models\Doctor;
use App\Models\DoctorMessage;
use App\Models\AdminSetting;
use App\Models\Specialty;
use Carbon\Carbon;
use App\Models\UserMessage;
class DashboardController extends Controller
{
    /**
     * Calculate distance between two points using the Haversine formula
     * Returns distance in kilometers
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return round($distance, 1); // Round to 1 decimal place
    }

    /**
     * Get distance text based on distance in kilometers
     */
    private function formatDistance($distance)
    {
        if ($distance < 1) {
            return 'Very Near (' . $distance . ' km)';
        } elseif ($distance < 5) {
            return 'Nearby (' . $distance . ' km)';
        } elseif ($distance < 15) {
            return 'Close (' . $distance . ' km)';
        } elseif ($distance < 30) {
            return 'Moderate (' . $distance . ' km)';
        } elseif ($distance < 100) {
            return 'Far (' . $distance . ' km)';
        } else {
            return 'Very Far (' . $distance . ' km)';
        }
    }

    /**
     * Get distance CSS class based on distance in kilometers
     */
    private function getDistanceClass($distance)
    {
        if ($distance < 1) {
            return 'distance-very-near';
        } elseif ($distance < 5) {
            return 'distance-nearby';
        } elseif ($distance < 15) {
            return 'distance-close';
        } elseif ($distance < 30) {
            return 'distance-moderate';
        } elseif ($distance < 100) {
            return 'distance-far';
        } else {
            return 'distance-very-far';
        }
    }

    /**
     * Get distance text based on distance in kilometers (legacy method)
     */
    private function getDistanceText($distance)
    {
        return $this->formatDistance($distance);
    }



    /**
     * Show the user dashboard with comprehensive statistics (PUBLIC)
     */
   

    /**
     * Show the home page with offerings and search functionality (PUBLIC)
     */
        public function home()
    {

        // Get categories with campaign counts
        $categories = \App\Models\Category::withCount('campaigns')
            ->where('is_active', true)
            ->get();
        
        $data = [
      
      
            'categories' => $categories,
        ];
        
        return view('user.pages.home', $data);
    }
    public function index()
    {

        // Get categories with campaign counts
        $categories = \App\Models\Category::withCount('campaigns')
            ->where('is_active', true)
            ->get();
        
        $data = [
      
      
            'categories' => $categories,
        ];
        
        return view('user.pages.home', $data);
    }

    /**
     * Show campaigns page with pagination (PUBLIC)
     */
    /**
     * Show campaign details page (PUBLIC)
     */
    /**
     * Show campaign registration form
     */
    public function showCampaignRegistrationForm($id)
    {
        $campaign = Campaign::findOrFail($id);
        
        // Check if campaign is still accepting registrations
        if ($campaign->end_date && \Carbon\Carbon::parse($campaign->end_date)->isPast()) {
            return redirect()->route('user.campaigns.view', $id)
                ->with('error', 'Registration for this campaign has ended.');
        }
        
        // Check if user is already registered (if authenticated)
        if (auth('user')->check()) {
            $exists = \App\Models\PatientRegistration::where('email', auth('user')->user()->email)
                ->where('campaign_id', $id)
                ->exists();
                
            if ($exists) {
                return redirect()->route('user.campaigns.view', $id)
                    ->with('info', 'You are already registered for this campaign.');
            }
        }
        
        return view('user.pages.campaign-register', compact('campaign'));
    }

    /**
     * Register a user for a campaign
     */
    public function registerForCampaign(Request $request, $id)
    {
        $campaign = Campaign::findOrFail($id);
        
        // Log incoming request data
        \Log::info('Registration request received', [
            'campaign_id' => $id,
            'request_data' => $request->all(),
            'user_id' => auth('user')->id(),
            'has_payment_id' => $request->has('razorpay_payment_id')
        ]);
        
        // Validate the request
        $request->validate([
            'patient_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'age' => 'required|integer|min:1|max:120',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string|max:500',
            'medical_history' => 'nullable|string',
            'registration_reason' => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:20',
            'agree_terms' => 'required|accepted',
        ], [
            'patient_name.required' => 'The full name field is required.',
            'agree_terms.required' => 'You must agree to the terms and conditions.',
            'agree_terms.accepted' => 'You must agree to the terms and conditions.',
        ]);

        \Log::info('Validation passed for registration', [
            'campaign_id' => $id,
            'email' => $request->email
        ]);

        try {
            // Check if user is already registered
            $exists = \App\Models\PatientRegistration::where('email', $request->email)
                ->where('campaign_id', $id)
                ->exists();

            if ($exists) {
                return back()->with('error', 'You are already registered for this campaign.');
            }

            // Create the registration
            $registration = new \App\Models\PatientRegistration();
            $registration->campaign_id = $id;
            $registration->name = $request->patient_name; // Use 'name' column for patient name
            $registration->patient_name = $request->patient_name; // Also set patient_name if column exists
            $registration->email = $request->email;
            $registration->phone_number = $request->phone_number;
            $registration->age = (int) $request->age;
            $registration->gender = $request->gender;
            $registration->address = $request->address;
            $registration->medical_history = $request->medical_history ?? '';
            $registration->registration_reason = $request->registration_reason ?? '';
            $registration->emergency_contact = $request->emergency_contact ?? '';
            $registration->user_id = auth('user')->id();
            
            \Log::info('Registration object created', [
                'name' => $registration->name,
                'patient_name' => $registration->patient_name,
                'email' => $registration->email,
                'phone_number' => $registration->phone_number,
                'age' => $registration->age,
                'gender' => $registration->gender
            ]);
            
            // Handle payment information
            if ($request->razorpay_payment_id) {
                $registration->payment_status = 'completed';
                $registration->payment_id = $request->razorpay_payment_id;
                $registration->payment_date = now();
                $registration->payment_amount = $campaign->registration_payment ?? 0;
                \Log::info('Payment data saved', [
                    'payment_id' => $request->razorpay_payment_id,
                    'amount' => $campaign->registration_payment,
                    'email' => $request->email
                ]);
            } else {
                $registration->payment_status = $campaign->registration_payment > 0 ? 'pending' : 'free';
                $registration->payment_amount = $campaign->registration_payment ?? 0;
            }
            
            // Save registration
            $registration->save();
            \Log::info('Registration saved successfully', [
                'registration_id' => $registration->id,
                'campaign_id' => $id,
                'email' => $request->email,
                'payment_status' => $registration->payment_status
            ]);

            // Send notification to admin/doctor
            if ($registration->payment_status === 'completed' || $registration->payment_status === 'free') {
                try {
                    // Process referral for both paid and free registrations
                    $referralController = new \App\Http\Controllers\ReferralController();
                    $referralResult = $referralController->processReferral($registration);
                    
                    if ($referralResult) {
                        // Update referrer's total earnings and available balance (already done in processReferral)
                        $referrer = User::find($referralResult->referrer_user_id);
                        if ($referrer) {
                            $earningAmount = $referralResult->per_campaign_refer_cost ?? 0;
                            
                            Log::info('Referrer earnings updated for registration', [
                                'referrer_id' => $referrer->id,
                                'earning_amount' => $earningAmount,
                                'new_total_earnings' => $referrer->total_earnings,
                                'new_available_balance' => $referrer->available_balance,
                                'registration_payment_status' => $registration->payment_status
                            ]);
                            
                            // Send real-time notification to referrer about successful referral
                            $referrerMsg = "🎉 Congratulations! You earned ₹{$earningAmount} for referring a user to '{$campaign->title}'. Your total earnings: ₹" . number_format($referrer->total_earnings, 2);
                            UserMessage::create([
                                'user_id' => $referralResult->referrer_user_id,
                                'message' => $referrerMsg,
                                'type' => 'referral_earning',
                                'is_read' => false,
                            ]);
                            event(new MessageReceived($referrerMsg, $referralResult->referrer_user_id));
                            
                            // Send notification to the referred user about their registration
                            $referredUser = auth()->user();
                            $referredUserMsg = "✅ Your registration for '{$campaign->title}' is confirmed! Thanks to your referral connection, both you and your referrer have benefited.";
                            UserMessage::create([
                                'user_id' => $referredUser->id,
                                'message' => $referredUserMsg,
                                'type' => 'registration_confirmed',
                                'is_read' => false,
                            ]);
                            event(new MessageReceived($referredUserMsg, $referredUser->id));
                        }
                        
                        Log::info('Referral processed for campaign registration', [
                            'registration_id' => $registration->id,
                            'referral_id' => $referralResult->id,
                            'referrer_user_id' => $referralResult->referrer_user_id,
                            'refer_cost' => $referralResult->per_campaign_refer_cost
                        ]);
                    }
                    
                    // Notify admin
                    $adminMessage = new \App\Models\AdminMessage();
                    $adminMessage->title = 'New Campaign Registration';
                    $adminMessage->message = "New registration for campaign: {$campaign->title}. Patient: {$registration->patient_name} ({$registration->email})";
                    $adminMessage->type = 'campaign_registration';
                    $adminMessage->save();

                    // Notify doctor if assigned
                    if ($campaign->doctor) {
                        $doctorMessage = new \App\Models\DoctorMessage();
                        $doctorMessage->doctor_id = $campaign->doctor->id;
                        $doctorMessage->title = 'New Patient Registration';
                        $doctorMessage->message = "New patient registered for your campaign: {$campaign->title}. Patient: {$registration->patient_name}";
                        $doctorMessage->type = 'campaign_registration';
                        $doctorMessage->save();
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to send registration notifications: ' . $e->getMessage());
                }
            }

            // Create detailed registration data for response
            $registrationData = [
                'registration_id' => 'REG-' . str_pad($registration->id, 6, '0', STR_PAD_LEFT),
                'patient_name' => $registration->name,
                'email' => $registration->email,
                'phone_number' => $registration->phone_number,
                'campaign_title' => $campaign->title,
                'campaign_date' => \Carbon\Carbon::parse($campaign->start_date)->format('M j, Y'),
                'campaign_time' => $campaign->timings ?? 'TBD',
                'campaign_location' => $campaign->location,
                'payment_status' => $registration->payment_status,
                'payment_amount' => $registration->payment_amount,
                'payment_id' => $registration->payment_id ?? null,
                'doctor_name' => $campaign->doctor->doctor_name ?? 'TBD',
                'specialty' => $campaign->doctor && $campaign->doctor->specialty 
                    ? $campaign->doctor->specialty->name 
                    : 'General'
            ];

            // Return JSON response with registration details
            return response()->json([
                'success' => true,
                'message' => 'Registration successful!',
                'registration' => $registrationData
            ]);

        } catch (\Exception $e) {
            \Log::error('Campaign registration error: ' . $e->getMessage(), [
                'campaign_id' => $id,
                'email' => $request->email ?? 'unknown',
                'payment_id' => $request->razorpay_payment_id ?? 'none',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Registration failed. Please try again. Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function campaignDetails($id, Request $request)
    {
        $campaign = Campaign::with(['doctor.specialty', 'patientRegistrations'])
            ->where('approval_status', 'approved')
            ->findOrFail($id);
            
        $totalPatients = $campaign->patientRegistrations()->count();
        $totalSponsors = $campaign->sponsors()->count();
        $totalSponsorAmount = $campaign->sponsors()->where('payment_status', 'success')->sum('amount');
        $totalEarnings = $campaign->registration_payment * $totalPatients;
        
        // Handle referral link if present and store in session for registration
        $referralData = null;
        $referralBy = null;
        
        if ($request->has('ref')) {
            $referralBy = $request->get('ref');
            
            // Store referral information in session for registration process
            session(['referral_data' => [
                'referred_by' => $referralBy,
                'campaign_id' => $id,
                'timestamp' => now()->toISOString()
            ]]);
            
            $referralController = new \App\Http\Controllers\ReferralController();
            $referralData = $referralController->handleCleanReferralLink($request, $id);
            
            // Log referral tracking
            \Log::info('Referral link detected in campaign view', [
                'campaign_id' => $id,
                'referred_by' => $referralBy,
                'user_ip' => $request->ip()
            ]);
        } elseif ($request->has('s')) {
            // Handle encoded referral parameter (short links)
            try {
                // Decode the referral ID from the 's' parameter
                $encodedRef = $request->get('s');
                // Pad the base64 string if needed
                $paddedRef = str_pad($encodedRef, ceil(strlen($encodedRef) / 4) * 4, '=', STR_PAD_RIGHT);
                $decodedRef = base64_decode($paddedRef);
                
                if ($decodedRef !== false && is_numeric($decodedRef)) {
                    $referralBy = $decodedRef;
                    
                    // Store referral information in session for registration process
                    session(['referral_data' => [
                        'referred_by' => $referralBy,
                        'campaign_id' => $id,
                        'timestamp' => now()->toISOString()
                    ]]);
                    
                    // Create a new request with the decoded ref parameter
                    $newRequest = clone $request;
                    $newRequest->merge(['ref' => $decodedRef]);
                    
                    $referralController = new \App\Http\Controllers\ReferralController();
                    $referralData = $referralController->handleCleanReferralLink($newRequest, $id);
                    
                    // Log referral tracking
                    \Log::info('Encoded referral link detected in campaign view', [
                        'campaign_id' => $id,
                        'referred_by' => $referralBy,
                        'encoded_ref' => $encodedRef,
                        'user_ip' => $request->ip()
                    ]);
                }
            } catch (\Exception $e) {
                // Log the error but continue without referral data
                \Log::warning('Failed to decode referral parameter', [
                    'encoded_ref' => $request->get('s'),
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        // Check if authenticated user is registered
        $userRegistered = false;
        if (auth()->guard('user')->check()) {
            $userRegistered = $campaign->patientRegistrations()
                ->where('email', auth()->guard('user')->user()->email)
                ->exists();
        }
        
        // Generate campaign URL with referral if needed
        $campaignUrl = route('user.campaigns.view', $campaign->id);
        if ($referralBy && !str_contains($campaignUrl, '?ref=') && !str_contains($campaignUrl, '&ref=')) {
            $separator = str_contains($campaignUrl, '?') ? '&' : '?';
            $campaignUrl .= $separator . 'ref=' . $referralBy;
        }
        
        // Meta data for social sharing and SEO
        $imageUrl = $campaign->thumbnail ? asset('storage/' . $campaign->thumbnail) : asset('storage/campaigns/default-campaign.svg');
        
        // Enhanced sharing description
        $sharingDescription = "💡 Special Limited-Time Offer! 💡\n" .
            "Step into a healthier tomorrow!\n\n" .
            "✨ Why Join?\n" .
            "✔ Top specialists in one place\n" .
            "✔ Affordable health checks\n" .
            "✔ Personalized guidance\n" .
            "✔ Instant registration confirmation\n\n" .
            "📍 Location: {$campaign->location}\n" .
            "🩺 Doctor: " . ($campaign->doctor ? $campaign->doctor->doctor_name : 'TBD') . "\n" .
            "💰 Registration Fee: " . ($campaign->registration_payment > 0 ? '₹' . number_format($campaign->registration_payment) : 'Free') . "\n" .
            "📅 Date: " . ($campaign->start_date ? \Carbon\Carbon::parse($campaign->start_date)->format('M d, Y') : 'TBD') . "\n\n" .
            "💚 Because your health can't wait.";
        
        $metaData = [
            'title' => $campaign->title . ' - Medical Campaign | FreeDoctor',
            'description' => $sharingDescription,
            'short_description' => \Illuminate\Support\Str::limit(strip_tags($campaign->description), 160),
            'image' => $imageUrl,
            'url' => $campaignUrl,
            'type' => 'website',
            'site_name' => config('app.name', 'FreeDoctor'),
            'campaign_goal' => number_format($campaign->expected_patients ?? 0),
            'campaign_location' => $campaign->location,
            'campaign_deadline' => $campaign->end_date ? \Carbon\Carbon::parse($campaign->end_date)->format('M d, Y') : 'TBD',
            'campaign_type' => ucfirst($campaign->camp_type ?? 'Medical'),
            'doctor_name' => $campaign->doctor ? $campaign->doctor->doctor_name : 'TBD',
            'specialty' => $campaign->doctor && $campaign->doctor->specialty ? $campaign->doctor->specialty->name : 'General Medicine',
            'registration_fee' => $campaign->registration_payment > 0 ? '₹' . number_format($campaign->registration_payment) : 'Free',
        ];
        
        return view('user.pages.campaign-view', compact(
            'campaign',
            'totalPatients',
            'totalSponsors',
            'totalSponsorAmount',
            'totalEarnings',
            'userRegistered',
            'referralData',
            'campaignUrl',
            'metaData',
            'referralBy'
        ));
    }

    public function campaigns(Request $request)
    {
        $query = Campaign::with(['doctor.specialty', 'patientRegistrations', 'doctor', 'category', 'campaignSponsors', 'sponsors'])
            ->where('approval_status', 'approved');
            // Remove date restrictions to show all campaigns initially
            // ->whereDate('start_date', '<=', now())
            // ->whereDate('end_date', '>=', now());

        // Apply filters
        if ($request->has('specialty') && $request->specialty != '') {
            $query->whereHas('doctor.specialty', function($q) use ($request) {
                $q->where('id', $request->specialty);
            });
        }
        
        if ($request->has('location') && $request->location != '') {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->has('doctor') && $request->doctor != '') {
            $query->whereHas('doctor', function($q) use ($request) {
                $q->where('doctor_name', 'like', '%' . $request->doctor . '%');
            });
        }
        
        if ($request->has('type') && $request->type != '') {
            $query->where('camp_type', $request->type);
        }
        
        if ($request->has('status') && $request->status != '') {
            $today = now();
            switch($request->status) {
                case 'upcoming':
                    $query->where('start_date', '>', $today);
                    break;
                case 'ongoing':
                    $query->where('start_date', '<=', $today)
                          ->where('end_date', '>=', $today);
                    break;
                case 'completed':
                    $query->where('end_date', '<', $today);
                    break;
            }
        }

        if ($request->has('registration') && $request->registration != '') {
            if ($request->registration === 'free') {
                $query->where(function($q) {
                    $q->whereNull('registration_payment')
                      ->orWhere('registration_payment', 0);
                });
            } else if ($request->registration === 'paid') {
                $query->where('registration_payment', '>', 0);
            }
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('location', 'like', '%' . $search . '%')
                  ->orWhere('camp_type', 'like', '%' . $search . '%')
                  ->orWhereHas('doctor', function($q) use ($search) {
                      $q->where('doctor_name', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('doctor.specialty', function($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('category', function($q) use ($search) {
                      $q->where('category_name', 'like', '%' . $search . '%');
                  });
            });
        }
        
        // Sort campaigns
        if ($request->has('sort')) {
            switch($request->sort) {
                case 'latest':
                    $query->latest();
                    break;
                case 'upcoming':
                    $query->where('start_date', '>', now())
                          ->orderBy('start_date', 'asc');
                    break;
                case 'popular':
                    $query->withCount('patientRegistrations')
                          ->orderBy('patient_registrations_count', 'desc');
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }
        
        // Get paginated campaigns with distance calculation
        $campaigns = $query->paginate(20); // Increased pagination to 20 per page
        
        // Debug: Log campaign count
        \Log::info('Campaigns loaded for user portal', [
            'total_campaigns' => Campaign::count(),
            'approved_campaigns' => Campaign::where('approval_status', 'approved')->count(),
            'filtered_campaigns' => $campaigns->total(),
            'current_page_count' => $campaigns->count()
        ]);
        
        // Add coordinates and distance calculation for all campaigns
        $userLat = 18.5204; // Default Pune coordinates
        $userLng = 73.8567;
        
        // Try to get user's stored location if logged in
        if (auth('user')->check()) {
            $user = auth('user')->user();
            if ($user->latitude && $user->longitude) {
                $userLat = $user->latitude;
                $userLng = $user->longitude;
            }
        }
        
        foreach($campaigns as $campaign) {
            // Assign coordinates if campaign doesn't have them
            if (!$campaign->latitude || !$campaign->longitude) {
                $coords = $this->assignMockCoordinates($campaign->location);
                $campaign->latitude = $coords['lat'];
                $campaign->longitude = $coords['lng'];
            }
            
            // Calculate distance for all campaigns
            $distance = $this->calculateDistance($userLat, $userLng, $campaign->latitude, $campaign->longitude);
            $campaign->setAttribute('distance_km', $distance);
            $campaign->setAttribute('distance_text', $this->formatDistance($distance));
            $campaign->setAttribute('distance_class', $this->getDistanceClass($distance));
            
            // Add video URL detection helper
            $campaign->is_video_external = false;
            if ($campaign->video_link) {
                $campaign->is_video_external = (
                    str_contains($campaign->video_link, 'youtube.com') ||
                    str_contains($campaign->video_link, 'youtu.be') ||
                    str_contains($campaign->video_link, 'vimeo.com') ||
                    str_contains($campaign->video_link, 'http://') ||
                    str_contains($campaign->video_link, 'https://')
                );
            }
        }
        
        // Get specialties for filter
        $specialties = \App\Models\Specialty::all();
        
        // Get all doctors for the doctor filter
        $doctors = \App\Models\Doctor::with('specialty')->get();
        
        // If it's an AJAX request, return only the campaigns partial
        if ($request->ajax()) {
            return view('user.partials.campaigns-list', compact('campaigns'))->render();
        }
            
        // Add empty campaign variable for compatibility
        $campaign = null;
        
        return view('user.pages.campaigns', compact('campaigns', 'specialties', 'doctors', 'campaign'));
    }

    /**
     * Advanced search campaigns with location-aware filtering (AJAX)
     */
    public function searchCampaigns(Request $request)
    {
        try {
            $searchTerm = $request->input('search', '');
            $location = $request->input('location', '');
            $userLatitude = $request->input('latitude');
            $userLongitude = $request->input('longitude');
            $updateUserLocation = $request->input('update_user_location', false);
            $validateLocation = $request->input('validate_location', true);
            
            Log::info('Advanced location-aware search request:', [
                'search' => $searchTerm,
                'location' => $location,
                'latitude' => $userLatitude,
                'longitude' => $userLongitude,
                'user_id' => auth('user')->id(),
                'update_user_location' => $updateUserLocation,
                'validate_location' => $validateLocation
            ]);

            // Validate location if provided and validation is requested
            if (!empty($location) && $validateLocation) {
                $locationValidation = $this->validateLocationWithGoogleAPI($location);
                if (!$locationValidation['valid']) {
                    return response()->json([
                        'success' => false,
                        'error_type' => 'invalid_location',
                        'message' => 'Please select a valid location from the dropdown suggestions.',
                        'location_error' => $locationValidation['error'],
                        'campaigns' => [],
                        'categories' => [],
                        'doctors' => [],
                        'specialties' => [],
                        'total' => 0
                    ], 400);
                }
                
                // Use validated coordinates if available
                if ($locationValidation['coordinates']) {
                    $userLatitude = $locationValidation['coordinates']['lat'];
                    $userLongitude = $locationValidation['coordinates']['lng'];
                }
            }

            // Update user location if logged in and coordinates provided
            if (auth('user')->check() && $userLatitude && $userLongitude && $updateUserLocation) {
                $this->updateUserLocationCoordinates($userLatitude, $userLongitude, $location, 'gps');
            }

            // Get default coordinates (Pune fallback)
            $defaultCoords = $this->getDefaultCoordinates();
            $currentUserLat = $userLatitude ?: $this->getUserStoredLatitude() ?: $defaultCoords['lat'];
            $currentUserLng = $userLongitude ?: $this->getUserStoredLongitude() ?: $defaultCoords['lng'];
            
            Log::info('Using coordinates:', [
                'user_provided' => [$userLatitude, $userLongitude],
                'final_coords' => [$currentUserLat, $currentUserLng],
                'source' => $userLatitude ? 'provided' : ($this->getUserStoredLatitude() ? 'stored' : 'default')
            ]);

            // Build base query
            $campaignQuery = Campaign::with(['doctor.specialty', 'patientRegistrations', 'doctor', 'category', 'campaignSponsors'])
                ->where('approval_status', 'approved');

            // Modified: Always show all campaigns with distance calculation
            // Only filter by search term if provided, location is used for distance calculation only
            if (!empty($searchTerm)) {
                $campaigns = $this->searchByTextOnly($campaignQuery, $searchTerm, $currentUserLat, $currentUserLng);
                $searchType = 'text_search_with_distance';
            } else {
                // Show all campaigns sorted by distance from user's location
                $campaigns = $this->getAllCampaignsSortedByDistance($campaignQuery, $currentUserLat, $currentUserLng);
                $searchType = 'all_campaigns_by_distance';
            }
            
            // Location is now only used for distance calculation and display, not filtering

            // Add additional search results (categories, doctors, specialties) only if there's a search term
            $categories = collect();
            $doctors = collect();
            $specialties = collect();

            if (!empty($searchTerm)) {
                $categories = $this->searchCategories($searchTerm);
                $doctors = $this->searchDoctors($searchTerm);
                $specialties = $this->searchSpecialties($searchTerm);
            }

            // Format response
            $formattedCampaigns = $this->formatCampaignsResponse($campaigns, $currentUserLat, $currentUserLng);
            $formattedCategories = $this->formatCategoriesResponse($categories);
            $formattedDoctors = $this->formatDoctorsResponse($doctors);
            $formattedSpecialties = $this->formatSpecialtiesResponse($specialties);

            return response()->json([
                'success' => true,
                'search_type' => $searchType,
                'campaigns' => $formattedCampaigns,
                'categories' => $formattedCategories,
                'doctors' => $formattedDoctors,
                'specialties' => $formattedSpecialties,
                'total' => count($formattedCampaigns) + count($formattedCategories) + count($formattedDoctors) + count($formattedSpecialties),
                'location_info' => [
                    'current_coords' => [$currentUserLat, $currentUserLng],
                    'coords_source' => $userLatitude ? 'provided' : ($this->getUserStoredLatitude() ? 'stored' : 'default'),
                    'location_text' => $location ?: ($this->getUserStoredLocation() ?: 'Default (Pune)'),
                    'user_logged_in' => auth('user')->check(),
                    'location_updated' => $updateUserLocation && auth('user')->check()
                ],
                'debug_info' => [
                    'search_term' => $searchTerm,
                    'location_filter' => $location,
                    'campaigns_found' => count($formattedCampaigns),
                    'search_type' => $searchType,
                    'coordinates_used' => [$currentUserLat, $currentUserLng]
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Advanced search error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Search failed: ' . $e->getMessage(),
                'campaigns' => [],
                'categories' => [],
                'doctors' => [],
                'specialties' => [],
                'total' => 0
            ], 500);
        }
    }

    /**
     * Update user location coordinates (for logged-in users)
     */
    private function updateUserLocationCoordinates($latitude, $longitude, $address = null, $source = 'manual')
    {
        if (!auth('user')->check()) return;

        $user = auth('user')->user();
        $user->update([
            'latitude' => $latitude,
            'longitude' => $longitude,
            'location_address' => $address,
            'location_source' => $source,
            'location_updated_at' => now(),
            'location_permission_granted' => true,
            'ip_address' => request()->ip()
        ]);

        Log::info('Updated user location:', [
            'user_id' => $user->id,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'source' => $source
        ]);
    }

    /**
     * Get default coordinates (Pune)
     */
    private function getDefaultCoordinates()
    {
        return ['lat' => 18.5204, 'lng' => 73.8567];
    }

    /**
     * Get user's stored latitude
     */
    private function getUserStoredLatitude()
    {
        return auth('user')->check() ? auth('user')->user()->latitude : null;
    }

    /**
     * Get user's stored longitude
     */
    private function getUserStoredLongitude()
    {
        return auth('user')->check() ? auth('user')->user()->longitude : null;
    }

    /**
     * Validate location using Google Maps API
     */
    private function validateLocationWithGoogleAPI($location)
    {
        try {
            // Check if location contains basic Indian location keywords
            $indianKeywords = ['india', 'mumbai', 'delhi', 'bangalore', 'pune', 'chennai', 'hyderabad', 'kolkata', 'ahmedabad', 'jaipur', 'lucknow', 'kanpur', 'nagpur', 'indore', 'bhopal', 'visakhapatnam', 'pimpri', 'patna', 'vadodara', 'ghaziabad'];
            
            $locationLower = strtolower($location);
            $isValidIndianLocation = false;
            
            foreach ($indianKeywords as $keyword) {
                if (str_contains($locationLower, $keyword)) {
                    $isValidIndianLocation = true;
                    break;
                }
            }
            
            // Check for basic location patterns (city names, pin codes, etc.)
            $hasValidPattern = preg_match('/^[a-zA-Z\s,.-]+$/', $location) && strlen($location) >= 3;
            
            if (!$isValidIndianLocation && !$hasValidPattern) {
                return [
                    'valid' => false,
                    'error' => 'Please enter a valid Indian location name.',
                    'coordinates' => null
                ];
            }
            
            // Get coordinates for the location
            $coordinates = $this->geocodeLocation($location);
            
            return [
                'valid' => true,
                'error' => null,
                'coordinates' => $coordinates
            ];
            
        } catch (\Exception $e) {
            Log::error('Location validation error: ' . $e->getMessage());
            
            return [
                'valid' => false,
                'error' => 'Unable to validate location. Please try again.',
                'coordinates' => null
            ];
        }
    }

    /**
     * Get user's stored location address
     */
    private function getUserStoredLocation()
    {
        return auth('user')->check() ? auth('user')->user()->location_address : null;
    }

    /**
     * Search campaigns by text only, sorted by distance from user
     */
    private function searchByTextOnly($query, $searchTerm, $userLat, $userLng)
    {
        // More strict text search - require campaigns to actually match the search term
        $campaigns = $query->where(function($q) use ($searchTerm) {
            $q->where('title', 'like', '%' . $searchTerm . '%')
              ->orWhere('description', 'like', '%' . $searchTerm . '%')
              ->orWhere('location', 'like', '%' . $searchTerm . '%')
              ->orWhere('camp_type', 'like', '%' . $searchTerm . '%')
              ->orWhere('specializations', 'like', '%' . $searchTerm . '%')
              ->orWhere('service_in_camp', 'like', '%' . $searchTerm . '%')
              ->orWhereHas('doctor', function($q) use ($searchTerm) {
                  $q->where('doctor_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('hospital_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('experience', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%');
              })
              ->orWhereHas('doctor.specialty', function($q) use ($searchTerm) {
                  $q->where('name', 'like', '%' . $searchTerm . '%');
              })
              ->orWhereHas('category', function($q) use ($searchTerm) {
                  $q->where('category_name', 'like', '%' . $searchTerm . '%');
              });
        })->get();

        Log::info('Text search results:', [
            'search_term' => $searchTerm,
            'total_found' => $campaigns->count(),
            'campaign_titles' => $campaigns->pluck('title')->take(5)->toArray()
        ]);

        return $this->sortCampaignsByDistance($campaigns, $userLat, $userLng);
    }

    /**
     * Search campaigns by location only, sorted by distance
     */
    private function searchByLocationOnly($query, $location, $userLat, $userLng)
    {
        // First try exact location matches
        $exactMatches = $query->where('location', 'like', '%' . $location . '%')->get();
        
        if ($exactMatches->count() > 0) {
            return $this->sortCampaignsByDistance($exactMatches, $userLat, $userLng);
        }

        // If no exact matches found, return empty collection instead of all campaigns
        // This ensures we only show relevant results
        Log::info('No campaigns found for location filter: ' . $location);
        return collect(); // Return empty collection instead of all campaigns
    }

    /**
     * Search campaigns by both text and location
     */
    private function searchByTextAndLocation($query, $searchTerm, $location, $userLat, $userLng)
    {
        $campaigns = $query->where(function($q) use ($searchTerm) {
            $q->where('title', 'like', '%' . $searchTerm . '%')
              ->orWhere('description', 'like', '%' . $searchTerm . '%')
              ->orWhere('camp_type', 'like', '%' . $searchTerm . '%')
              ->orWhere('specializations', 'like', '%' . $searchTerm . '%')
              ->orWhereHas('doctor', function($q) use ($searchTerm) {
                  $q->where('doctor_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('hospital_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('experience', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%');
              })
              ->orWhereHas('doctor.specialty', function($q) use ($searchTerm) {
                  $q->where('name', 'like', '%' . $searchTerm . '%');
              })
              ->orWhereHas('category', function($q) use ($searchTerm) {
                  $q->where('category_name', 'like', '%' . $searchTerm . '%');
              });
        })->where('location', 'like', '%' . $location . '%')->get();

        return $this->sortCampaignsByDistance($campaigns, $userLat, $userLng);
    }

    /**
     * Get all campaigns sorted by distance from user
     */
    private function getAllCampaignsSortedByDistance($query, $userLat, $userLng)
    {
        $campaigns = $query->get();
        return $this->sortCampaignsByDistance($campaigns, $userLat, $userLng);
    }

    /**
     * Sort campaigns by distance from given coordinates
     */
    private function sortCampaignsByDistance($campaigns, $userLat, $userLng)
    {
        return $campaigns->map(function($campaign) use ($userLat, $userLng) {
            // Add mock coordinates if campaign doesn't have them
            if (!$campaign->latitude || !$campaign->longitude) {
                $coords = $this->assignMockCoordinates($campaign->location);
                $campaign->latitude = $coords['lat'];
                $campaign->longitude = $coords['lng'];
            }

            $distance = $this->calculateDistance($userLat, $userLng, $campaign->latitude, $campaign->longitude);
            $campaign->setAttribute('distance_km', $distance);
            $campaign->setAttribute('distance_text', $this->formatDistance($distance));
            $campaign->setAttribute('distance_class', $this->getDistanceClass($distance));
            
            return $campaign;
        })->sortBy('distance_km')->values();
    }

    /**
     * Assign mock coordinates based on location text
     */
    private function assignMockCoordinates($location)
    {
        $cityCoordinates = [
            'mumbai' => ['lat' => 19.0760, 'lng' => 72.8777],
            'delhi' => ['lat' => 28.7041, 'lng' => 77.1025],
            'bangalore' => ['lat' => 12.9716, 'lng' => 77.5946],
            'pune' => ['lat' => 18.5204, 'lng' => 73.8567],
            'chennai' => ['lat' => 13.0827, 'lng' => 80.2707],
            'hyderabad' => ['lat' => 17.3850, 'lng' => 78.4867],
            'kolkata' => ['lat' => 22.5726, 'lng' => 88.3639],
            'ahmedabad' => ['lat' => 23.0225, 'lng' => 72.5714],
        ];

        $locationLower = strtolower($location ?? 'pune');
        
        foreach ($cityCoordinates as $city => $coords) {
            if (str_contains($locationLower, $city)) {
                // Add small random variation
                return [
                    'lat' => $coords['lat'] + (mt_rand(-20, 20) / 1000),
                    'lng' => $coords['lng'] + (mt_rand(-20, 20) / 1000)
                ];
            }
        }

        // Default to Pune with variation
        return [
            'lat' => 18.5204 + (mt_rand(-50, 50) / 1000),
            'lng' => 73.8567 + (mt_rand(-50, 50) / 1000)
        ];
    }

    /**
     * Search categories
     */
    private function searchCategories($searchTerm)
    {
        return \App\Models\Category::where('category_name', 'like', '%' . $searchTerm . '%')
            ->where('is_active', true)
            ->withCount('campaigns')
            ->take(5)
            ->get();
    }

    /**
     * Search doctors
     */
    private function searchDoctors($searchTerm)
    {
        return \App\Models\Doctor::with('specialty')
            ->where(function($q) use ($searchTerm) {
                $q->where('doctor_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('hospital_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('experience', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%');
            })
            ->orWhereHas('specialty', function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%');
            })
            ->where('status', true)
            ->where('approved_by_admin', true)
            ->take(5)
            ->get();
    }

    /**
     * Search specialties
     */
    private function searchSpecialties($searchTerm)
    {
        return \App\Models\Specialty::where('name', 'like', '%' . $searchTerm . '%')
            ->take(5)
            ->get();
    }

    /**
     * Format campaigns response
     */
    private function formatCampaignsResponse($campaigns, $userLat, $userLng)
    {
        return $campaigns->map(function($campaign) {
            $totalRegistered = $campaign->patientRegistrations ? $campaign->patientRegistrations->count() : 0;
            $expectedPatients = $campaign->expected_patients ?? 100;
            $registrationProgress = min(($totalRegistered / $expectedPatients) * 100, 100);

            $totalSponsored = $campaign->campaignSponsors ? $campaign->campaignSponsors->sum('amount') : 0;
            $targetAmount = $campaign->target_amount ?? ($campaign->amount ?? 1000);
            $sponsorshipProgress = $targetAmount > 0 ? ($totalSponsored / $targetAmount) * 100 : 0;

            return [
                'id' => $campaign->id,
                'title' => $campaign->title ?? 'Untitled Campaign',
                'description' => $campaign->description ?? '',
                'location' => $campaign->location ?? 'Location TBD',
                'doctor_name' => $campaign->doctor ? $campaign->doctor->doctor_name : 'TBD',
                'specialty' => $campaign->doctor && $campaign->doctor->specialty 
                    ? $campaign->doctor->specialty->name 
                    : 'General',
                'category' => $campaign->category ? $campaign->category->category_name : 'General',
                'category_icon' => $campaign->category ? $campaign->category->icon_class : 'local_hospital',
                'camp_type' => ucfirst($campaign->camp_type ?? 'healthcare'),
                'start_date' => $campaign->start_date 
                    ? \Carbon\Carbon::parse($campaign->start_date)->format('M j, Y') 
                    : 'TBD',
                'end_date' => $campaign->end_date 
                    ? \Carbon\Carbon::parse($campaign->end_date)->format('M j, Y') 
                    : 'TBD',
                'thumbnail' => $campaign->thumbnail && Storage::exists('public/' . $campaign->thumbnail) 
                    ? asset('storage/' . $campaign->thumbnail) 
                    : null,
                'view_url' => route('user.campaigns.view', $campaign->id),
                'total_registered' => $totalRegistered,
                'expected_patients' => $expectedPatients,
                'registration_progress' => round($registrationProgress, 1),
                'total_sponsored' => $totalSponsored,
                'target_amount' => $targetAmount,
                'sponsorship_progress' => round($sponsorshipProgress, 1),
                'distance_km' => $campaign->distance_km ?? null,
                'distance_text' => $campaign->distance_text ?? null,
                'distance_class' => $campaign->distance_class ?? null,
                'coordinates' => [
                    'latitude' => $campaign->latitude,
                    'longitude' => $campaign->longitude
                ],
                'registration_payment' => $campaign->registration_payment ?? 0,
                'contact_number' => $campaign->contact_number ?? ''
            ];
        })->toArray();
    }

    /**
     * Format categories response
     */
    private function formatCategoriesResponse($categories)
    {
        return $categories->map(function($category) {
            return [
                'id' => $category->id,
                'category_name' => $category->category_name,
                'icon_class' => $category->icon_class ?? 'local_hospital',
                'campaigns_count' => $category->campaigns_count ?? 0,
                'description' => $category->description ?? ''
            ];
        })->toArray();
    }

    /**
     * Format doctors response
     */
    private function formatDoctorsResponse($doctors)
    {
        return $doctors->map(function($doctor) {
            return [
                'id' => $doctor->id,
                'name' => $doctor->doctor_name,
                'hospital_name' => $doctor->hospital_name ?? 'Not specified',
                'specialty' => $doctor->specialty ? $doctor->specialty->name : 'General',
                'experience' => $doctor->experience ?? 'Not specified',
                'description' => $doctor->description ?? '',
                'location' => $doctor->location ?? ''
            ];
        })->toArray();
    }

    /**
     * Format specialties response
     */
    private function formatSpecialtiesResponse($specialties)
    {
        return $specialties->map(function($specialty) {
            return [
                'id' => $specialty->id,
                'name' => $specialty->name,
                'description' => $specialty->description ?? ''
            ];
        })->toArray();
    }

    /**
     * Geocode location text to coordinates
     */
    private function geocodeLocation($location)
    {
        // Mock geocoding - in production, use Google Geocoding API
        $mockCoordinates = [
            'mumbai' => ['lat' => 19.0760, 'lng' => 72.8777],
            'delhi' => ['lat' => 28.7041, 'lng' => 77.1025],
            'bangalore' => ['lat' => 12.9716, 'lng' => 77.5946],
            'pune' => ['lat' => 18.5204, 'lng' => 73.8567],
            'chennai' => ['lat' => 13.0827, 'lng' => 80.2707],
            'hyderabad' => ['lat' => 17.3850, 'lng' => 78.4867],
            'kolkata' => ['lat' => 22.5726, 'lng' => 88.3639],
            'ahmedabad' => ['lat' => 23.0225, 'lng' => 72.5714],
        ];

        $locationKey = strtolower(trim($location));
        
        foreach ($mockCoordinates as $city => $coords) {
            if (str_contains($locationKey, $city) || str_contains($city, $locationKey)) {
                return $coords;
            }
        }
        
        return null;
    }

    /**
     * Show sponsors page with campaigns (PUBLIC)
     */
    public function sponsors(Request $request)
    {
        $query = Campaign::with(['doctor.specialty', 'patientRegistrations', 'campaignSponsors', 'category'])
            ->where('approval_status', 'approved');
            
        // Apply filters
        if ($request->has('specialty') && $request->specialty != '') {
            $query->whereHas('doctor.specialty', function($q) use ($request) {
                $q->where('id', $request->specialty);
            });
        }
        
        if ($request->has('location') && $request->location != '') {
            $query->where('location', 'like', '%' . $request->location . '%');
        }
        
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%')
                  ->orWhere('camp_type', 'like', '%' . $request->search . '%')
                  ->orWhereHas('category', function($q) use ($request) {
                      $q->where('category_name', 'like', '%' . $request->search . '%');
                  })
                  ->orWhereHas('doctor', function($q) use ($request) {
                      $q->where('doctor_name', 'like', '%' . $request->search . '%');
                  });
            });
        }
        
        // Get paginated campaigns
        $campaigns = $query->latest()->paginate(12);
        
        // Get specialties for filter
        $specialties = \App\Models\Specialty::all();
        
        return view('user.pages.sponsors', compact('campaigns', 'specialties'));
    }

    /**
     * Show organization camp request page (PUBLIC)
     */
    public function organizationCampRequest()
    {
        return view('user.pages.organization-camp-request');
    }

    /**
     * Store organization camp request (PUBLIC - no auth required)
     */
  public function storeOrganizationCampRequest(Request $request)
    {
   
        $request->validate([
            'organization_name' => 'required|string|max:255',
            'email'             => 'required|email|max:255',
            'user_id'  => 'required|integer',
            'phone_number'      => 'required|string|max:20',
            'camp_request_type' => 'required|string',
            'specialty_id'      => 'required|exists:specialties,id',
            'date_from'         => 'required|date|after:today',
            'date_to'           => 'required|date|after_or_equal:date_from',
            'number_of_people'  => 'required|integer|min:10|max:1000',
            'location'          => 'required|string|max:500',
            'description'       => 'required|string|max:1000',
        ]);



        $campRequest = BusinessOrganizationRequest::create([
            'user_id'            => $request->user_id ?? 0,
            'organization_name'  => $request->organization_name,
            'email'              => $request->email,
            'phone_number'       => $request->phone_number,
            'camp_request_type'  => $request->camp_request_type,
            'specialty_id'       => $request->specialty_id,
            'date_from'          => $request->date_from,
            'date_to'            => $request->date_to,
            'number_of_people'   => $request->number_of_people,
            'location'           => $request->location,
            'description'        => $request->description,
            'status'             => 'pending',
        ]);

        // Send notifications to doctors with matching specialty
        try {
            // First, let's get the specialty name for better messaging
            $specialty = \App\Models\Specialty::find($request->specialty_id);
            $specialtyName = $specialty ? $specialty->name : 'Unknown Specialty';
            
            // Find doctors with matching specialty - try both approaches
            $matchingDoctors = Doctor::where('specialty_id', $request->specialty_id)
                ->where('status', true) // status = true means approved
                ->where('approved_by_admin', true)
                ->get();
            
            Log::info('Looking for doctors with specialty_id: ' . $request->specialty_id);
            Log::info('Found ' . $matchingDoctors->count() . ' matching doctors');
            
            // If no doctors found with specialty_id, try alternative approach
            if ($matchingDoctors->isEmpty()) {
                // Check if there's a specialities column (text field)
                $matchingDoctors = Doctor::where('specialities', 'like', '%' . $specialtyName . '%')
                    ->where('status', true)
                    ->where('approved_by_admin', true)
                    ->get();
                    
                Log::info('Alternative search with specialities column found: ' . $matchingDoctors->count() . ' doctors');
            }

            foreach ($matchingDoctors as $doctor) {
                $doctorMessage = DoctorMessage::create([
                    'doctor_id' => $doctor->id,
                    'type' => 'business_request',
                    'message' => "🎯 New Business Opportunity! {$campRequest->organization_name} is looking for {$specialtyName} doctors for a {$campRequest->camp_request_type} camp in {$campRequest->location} from " . 
                               date('M d, Y', strtotime($campRequest->date_from)) . " to " . date('M d, Y', strtotime($campRequest->date_to)) . 
                               ". Expected participants: {$campRequest->number_of_people}. Submit your proposal now!",
                    'is_read' => false
                ]);

                Log::info('Created doctor message for doctor ID: ' . $doctor->id . ', Message ID: ' . $doctorMessage->id);

                // Broadcast real-time notification to doctor
                try {
                    event(new DoctorMessageSent($doctorMessage));
                    Log::info('Broadcasted notification to doctor: ' . $doctor->id);
                } catch (\Exception $e) {
                    Log::error('Failed to broadcast to doctor ' . $doctor->id . ': ' . $e->getMessage());
                }
            }
            
            Log::info('Successfully sent notifications to ' . $matchingDoctors->count() . ' doctors');
            
        } catch (\Exception $e) {
            Log::error('Error sending doctor notifications: ' . $e->getMessage());
        }

        // If the user is logged in, record a message and broadcast it
        if ($request->user_id) {
            $msgText = "Your organization request “{$campRequest->organization_name}” has been submitted and is pending approval.";
            $userMessage = UserMessage::create([
                'user_id' => $request->user_id,
                'message' => $msgText,
            ]);

            // Broadcast in real-time
            event(new MessageReceived($userMessage->message, $request->user_id));
        }

        return redirect()
            ->back()
            ->with('success', 'Camp request submitted successfully! We will contact you within 48 hours.');
    }

    /**
     * Register for campaign (requires login)
     */
   

    /**
     * Show user profile (REQUIRES AUTH)
     */
    public function profile()
    {
        $user = Auth::user();
        return view('user.pages.profile', compact('user'));
    }

    /**
     * Show user's registrations (REQUIRES AUTH)
     */
public function myRegistrations()
{
    $userId = auth()->id();

    $registrations = PatientRegistration::with('campaign')
        ->where('user_id', $userId)
        ->latest() // show latest registrations first
        ->get();

    $patientRegistrations = PatientRegistration::where('user_id', $userId)
        ->latest() // fix here too
        ->get();

    $sponsorRequests = CampaignSponsor::where('user_id', $userId)
        ->latest() // show latest sponsor requests first
        ->get();

    $organizationNotices = BusinessOrganizationRequest::with(['hiredDoctor.specialty'])
        ->where('user_id', $userId)
        ->latest() // already applied
        ->get();

    return view('user.pages.my-registrations', compact(
        'registrations',
        'patientRegistrations',
        'sponsorRequests',
        'organizationNotices'
    ));
}

    /**
     * Get doctor details for modal display
     */
    public function getDoctorDetails(Doctor $doctor)
    {
        $doctor->load('specialty');
        
        return response()->json([
            'id' => $doctor->id,
            'doctor_name' => $doctor->doctor_name,
            'email' => $doctor->email,
            'phone' => $doctor->phone,
            'location' => $doctor->location,
            'hospital_name' => $doctor->hospital_name,
            'experience' => $doctor->experience,
            'description' => $doctor->description,
            'specialty' => $doctor->specialty ? [
                'id' => $doctor->specialty->id,
                'name' => $doctor->specialty->name
            ] : null,
            'verified_at' => $doctor->email_verified_at
        ]);
    }



    /**
     * Show notifications (REQUIRES AUTH)
     */
public function notifications()
{
    $user = Auth::guard('user')->user(); // Ensure correct guard for multi-auth

    if (!$user) {
        return redirect()->route('user.login')->with('error', 'Please log in to view notifications.');
    }

    // Get recent campaign registrations


    // Get recent user messages
    $messages = UserMessage::where('user_id', $user->id)
        ->latest()
        ->take(10)
        ->get();

    return view('user.pages.notifications', compact( 'messages'));
}

    /**
     * Mark a notification as read (REQUIRES AUTH)
     */
    public function markNotificationRead(Request $request, $id)
    {
        try {
            $user = Auth::guard('user')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access.'
                ], 401);
            }

            // Find the user message by ID and ensure it belongs to the authenticated user
            $message = UserMessage::where('id', $id)
                ->where('user_id', $user->id)
                ->first();

            if (!$message) {
                return response()->json([
                    'success' => false,
                    'message' => 'Message not found or you do not have permission to access it.'
                ], 404);
            }

            // Mark as read by updating the read field
            $message->read = true;
            $message->save();

            return response()->json([
                'success' => true,
                'message' => 'Message marked as read successfully.'
            ]);

        } catch (\Exception $e) {
            Log::error('Mark notification as read error: ' . $e->getMessage(), [
                'user_id' => auth()->guard('user')->id(),
                'message_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while marking the message as read.'
            ], 500);
        }
    }

    /**
     * Show settings page (REQUIRES AUTH)
     */
    public function settings()
    {
        $user = Auth::user();
        return view('user.pages.settings', compact('user'));
    }

    /**
     * Update user profile (REQUIRES AUTH)
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female,other,prefer_not_to_say',
            'address' => 'nullable|string|max:500',
        ]);
        
        $user = Auth::user();
        $user->username = $request->username;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->gender = $request->gender;
        $user->address = $request->address;
        $user->save();
        
        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Update user profile picture (REQUIRES AUTH)
     */
    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $user = Auth::user();
        
        // Delete old profile picture if exists
        if ($user->profile_picture) {
            Storage::delete('public/' . $user->profile_picture);
        }
        
        // Store new profile picture
        $path = $request->file('profile_picture')->store('profile_pictures', 'public');
        $user->profile_picture = $path;
        $user->save();
        
        return redirect()->back()->with('success', 'Profile picture updated successfully!');
    }

    /**
     * Update user password (REQUIRES AUTH)
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }
        
        $user->password = Hash::make($request->password);
        $user->save();
        
        return redirect()->back()->with('success', 'Password updated successfully!');
    }

    /**
     * Update user preferences (REQUIRES AUTH)
     */
    public function updatePreferences(Request $request)
    {
        $user = Auth::user();
        
        // Handle preferences update (you can add preference fields to users table)
        // For now, just return success
        
        return redirect()->back()->with('success', 'Preferences updated successfully!');
    }

    /**
     * Get campaigns for category page with lazy loading
     */
    public function getCategoryCampaigns(Request $request)
    {
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 12);
        $search = $request->get('search', '');
        $category = $request->get('category', '');

        // Start building the query
        $campaignsQuery = Campaign::where('status', 'approved')
            ->where('end_date', '>', Carbon::now());

        // Apply search filter
        if ($search) {
            $campaignsQuery->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('location', 'LIKE', "%{$search}%")
                  ->orWhere('category', 'LIKE', "%{$search}%");
            });
        }

        // Apply category filter
        if ($category) {
            $campaignsQuery->where('category', 'LIKE', "%{$category}%");
        }

        // Get paginated campaigns
        $campaigns = $campaignsQuery
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        // Format the response
        $responseData = [
            'campaigns' => $campaigns->map(function($campaign) {
                return [
                    'id' => $campaign->id,
                    'title' => $campaign->title,
                    'description' => $campaign->description,
                    'image' => $campaign->image ? asset('storage/' . $campaign->image) : asset('images/default-campaign.jpg'),
                    'location' => $campaign->location,
                    'category' => $campaign->category,
                    'registration_payment' => $campaign->registration_payment,
                    'expected_patients' => $campaign->expected_patients,
                    'start_date' => $campaign->start_date,
                    'end_date' => $campaign->end_date,
                    'registered_count' => $campaign->registrations ? $campaign->registrations->count() : 0,
                ];
            }),
            'has_more' => $campaigns->hasMorePages(),
            'current_page' => $campaigns->currentPage(),
            'last_page' => $campaigns->lastPage(),
            'total' => $campaigns->total()
        ];

        return response()->json($responseData);
    }

    /**
     * Show the category page
     */
    public function categoryPage()
    {
        return view('user.pages.category');
    }

    /**
     * Delete user account permanently
     */
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        $user = Auth::guard('user')->user();

        // Verify the user's password
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'The provided password is incorrect.']);
        }

        try {
            DB::beginTransaction();

            // Delete related records first to maintain referential integrity
            
            // Delete patient registrations
            PatientRegistration::where('email', $user->email)->delete();
            
            // Delete campaign sponsors
            CampaignSponsor::where('user_id', $user->id)->delete();
            
            // Delete business organization requests
            BusinessOrganizationRequest::where('email', $user->email)->delete();
            
            // Delete user messages
            UserMessage::where('user_id', $user->id)->delete();
            
            // Delete any referral records
            DB::table('referrals')->where('user_id', $user->id)->orWhere('referred_user_id', $user->id)->delete();
            
            // Delete user payments
            DB::table('patient_payments')->where('user_id', $user->id)->delete();
            
            // Finally delete the user account
            $user->delete();

            DB::commit();

            // Log the user out
            Auth::guard('user')->logout();

            // Invalidate the session
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/')->with('success', 'Your account has been permanently deleted. We\'re sorry to see you go.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Account deletion failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to delete account. Please try again or contact support.']);
        }
    }

    /**
     * Check for new user notifications (for polling fallback)
     */
    public function checkNewNotifications()
    {
        try {
            $user = Auth::guard('web')->user();
            if (!$user) {
                return response()->json(['notifications' => []]);
            }

            // Get notifications from the last 2 minutes for toast display
            $notifications = UserMessage::where('user_id', $user->id)
                ->where('created_at', '>=', Carbon::now()->subMinutes(2))
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($message) {
                    return [
                        'id' => $message->id,
                        'message' => $message->message,
                        'type' => $message->type,
                        'created_at' => $message->created_at->format('Y-m-d H:i:s')
                    ];
                });

            return response()->json([
                'notifications' => $notifications,
                'count' => $notifications->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Error checking user notifications: ' . $e->getMessage());
            return response()->json(['notifications' => []]);
        }
    }

    /**
     * Mark notifications as read
     */
    public function markNotificationsAsRead(Request $request)
    {
        try {
            $user = Auth::guard('web')->user();
            if (!$user) {
                return response()->json(['error' => 'Not authenticated'], 401);
            }

            if ($request->has('notification_ids')) {
                UserMessage::where('user_id', $user->id)
                    ->whereIn('id', $request->notification_ids)
                    ->update(['is_read' => true]);
            } else {
                UserMessage::where('user_id', $user->id)
                    ->where('is_read', false)
                    ->update(['is_read' => true]);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error marking user notifications as read: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to mark notifications as read'], 500);
        }
    }

    /**
     * Show campaign registration page
     */
    public function campaignRegister($id)
    {
        $campaign = Campaign::with(['doctor.specialty', 'patientRegistrations'])
            ->where('approval_status', 'approved')
            ->findOrFail($id);
            
        return view('user.pages.campaign_register', compact('campaign'));
    }

    /**
     * Store campaign registration
     */
public function campaignRegisterStore(Request $request, $id)
{
    $campaign = Campaign::findOrFail($id);
    
    // Add logging to debug the incoming request
    \Log::info('Registration request received', [
        'campaign_id' => $id,
        'request_data' => $request->all(),
        'user_id' => auth()->id(),
    ]);
    
    $request->validate([
        'patient_name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone_number' => 'required|string|max:20',
        'age' => 'required|integer|min:1|max:120',
        'gender' => 'required|in:male,female,other',
        'address' => 'required|string|max:1000',
        'agree_terms' => 'required|accepted',
    ]);

    try {
        DB::beginTransaction();

        // Create patient registration with correct field mapping
        $registration = PatientRegistration::create([
            'campaign_id' => $campaign->id,
            'user_id' => auth()->id(),
            'name' => $request->patient_name,                    // Maps to 'name' column
            'patient_name' => $request->patient_name,            // Maps to 'patient_name' column
            'email' => $request->email,
            'phone_number' => $request->phone_number,                // Maps to 'phone_number' column
            'age' => $request->age,
            'gender' => $request->gender,
            'address' => $request->address,
            'emergency_contact' => $request->emergency_contact,
            'medical_history' => $request->medical_history,
            'registration_reason' => $request->registration_reason,
            'description' => $request->registration_reason,   // Maps to 'description' column
            'amount' => $campaign->registration_payment ?? 0, // Maps to 'amount' column
            'status' => 'confirmed',
            
            // Payment related fields (stored in same table)
            'payment_status' => $request->razorpay_payment_id ? 'completed' : 
                               ($campaign->registration_payment > 0 ? 'pending' : 'free'),
            'payment_id' => $request->razorpay_payment_id,
            'payment_amount' => $campaign->registration_payment ?? 0,
            'payment_date' => $request->razorpay_payment_id ? now() : null,
        ]);

        \Log::info('Registration created successfully', [
            'registration_id' => $registration->id,
            'name' => $registration->name,
            'email' => $registration->email,
            'payment_status' => $registration->payment_status,
        ]);

        // Send notification to doctor
        if ($campaign->doctor) {
            $doctorMessage = \App\Models\DoctorMessage::create([
                'doctor_id' => $campaign->doctor->id,
                'message' => "📋 New Registration! {$request->patient_name} has registered for your campaign '{$campaign->title}'. Check your campaign dashboard for details.",
                'type' => 'new_registration',
                'is_read' => false,
            ]);

            // Trigger real-time event
            event(new \App\Events\DoctorMessageSent($doctorMessage));
        }

        // Process referral if exists
        $referralController = new \App\Http\Controllers\ReferralController();
        $referralResult = $referralController->processReferral($registration);
        
        if ($referralResult) {
            // Referrer earnings are already updated in processReferral method
            $referrer = User::find($referralResult->referrer_user_id);
            if ($referrer) {
                $earningAmount = $referralResult->per_campaign_refer_cost ?? 0;
                
                Log::info('Referrer earnings updated for campaign registration', [
                    'referrer_id' => $referrer->id,
                    'earning_amount' => $earningAmount,
                    'new_total_earnings' => $referrer->total_earnings,
                    'new_available_balance' => $referrer->available_balance,
                    'registration_payment_status' => $registration->payment_status
                ]);
                
                // Send real-time notification to referrer about successful referral
                $referrerMsg = "🎉 Congratulations! You earned ₹{$earningAmount} for referring {$request->patient_name} to '{$campaign->title}'. Your total earnings: ₹" . number_format($referrer->total_earnings, 2);
                UserMessage::create([
                    'user_id' => $referralResult->referrer_user_id,
                    'message' => $referrerMsg,
                    'type' => 'referral_earning',
                    'is_read' => false,
                ]);
                event(new MessageReceived($referrerMsg, $referralResult->referrer_user_id));
                
                // Send notification to the referred user about their registration
                $referredUser = auth()->user();
                $referredUserMsg = "✅ Your registration for '{$campaign->title}' is confirmed! Thanks to your referral connection, both you and your referrer have benefited.";
                UserMessage::create([
                    'user_id' => $referredUser->id,
                    'message' => $referredUserMsg,
                    'type' => 'registration_confirmed',
                    'is_read' => false,
                ]);
                event(new MessageReceived($referredUserMsg, $referredUser->id));
            }
            
            Log::info('Referral processed for campaign registration store', [
                'registration_id' => $registration->id,
                'referral_id' => $referralResult->id,
                'referrer_user_id' => $referralResult->referrer_user_id,
                'refer_cost' => $referralResult->per_campaign_refer_cost
            ]);
        }

        // Send notification to admin
        $admin = \App\Models\Admin::first();
        if ($admin) {
            $doctorName = $campaign->doctor->doctor_name ?? 'TBD';
            $adminMessage = \App\Models\AdminMessage::create([
                'admin_id' => $admin->id,
                'message' => "👤 New Campaign Registration! {$request->patient_name} registered for campaign '{$campaign->title}' by Dr. {$doctorName}.",
                'type' => 'new_registration',
                'is_read' => false,
            ]);

            // Trigger real-time event
            event(new \App\Events\AdminMessageSent($adminMessage));
        }

        DB::commit();

        // Create detailed registration data for success response
        $registrationData = [
            'registration_id' => 'REG-' . str_pad($registration->id, 6, '0', STR_PAD_LEFT),
            'patient_name' => $registration->patient_name,
            'email' => $registration->email,
            'phone_number' => $registration->phone_number,
            'campaign_title' => $campaign->title,
            'campaign_date' => \Carbon\Carbon::parse($campaign->start_date)->format('M j, Y'),
            'campaign_time' => $campaign->timings ?? 'TBD',
            'campaign_location' => $campaign->location,
            'payment_status' => $registration->payment_status,
            'payment_amount' => $registration->payment_amount,
            'payment_id' => $registration->payment_id ?? null,
            'doctor_name' => $campaign->doctor->doctor_name ?? 'TBD',
            'specialty' => $campaign->doctor && $campaign->doctor->specialty 
                ? $campaign->doctor->specialty->name 
                : 'General'
        ];

        // Return JSON response for AJAX handling
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Registration successful!',
                'registration' => $registrationData
            ]);
        }

        // For regular form submission
        return redirect()->route('user.campaigns')
            ->with('success', 'Registration submitted successfully! We will contact you soon with further details. You can download your registration receipt from the "My Registrations" section.');

    } catch (\Exception $e) {
        DB::rollback();
        \Log::error('Campaign registration error', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'request_data' => $request->all()
        ]);
        
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage()
            ], 500);
        }
        
        return back()->withInput()
            ->with('error', 'Registration failed. Please try again.');
    }
}
    /**
     * Show campaign sponsor page
     */
    public function campaignSponsor($id)
    {
        $campaign = Campaign::with(['doctor.specialty', 'campaignSponsors'])
            ->where('approval_status', 'approved')
            ->findOrFail($id);
            
        return view('user.pages.campaign_sponsor', compact('campaign'));
    }

    /**
     * Store campaign sponsorship
     */
    public function campaignSponsorStore(Request $request, $id)
    {
        $campaign = Campaign::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone_number' => 'required|string|max:20',
            'amount' => 'required|numeric|min:100',
            'address' => 'required|string|max:1000',
            'message' => 'nullable|string|max:1000',
            'payment_method' => 'required|in:razorpay',
            'razorpay_payment_id' => 'required|string', // Make payment ID mandatory
            'agree_terms' => 'required|accepted',
        ], [
            'razorpay_payment_id.required' => 'Payment verification failed. Please complete the payment process.',
            'payment_method.in' => 'Only online payment is accepted for sponsorships.',
        ]);

        try {
            DB::beginTransaction();
            
            \Log::info('Creating campaign sponsor', [
                'campaign_id' => $campaign->id,
                'user_id' => auth()->id(),
                'payment_id' => $request->razorpay_payment_id,
                'amount' => $request->amount
            ]);

            // Create campaign sponsor with successful payment
            $sponsor = CampaignSponsor::create([
                'campaign_id' => $campaign->id,
                'user_id' => auth()->id(),
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'amount' => $request->amount,
                'payment_method' => 'razorpay', // Always Razorpay
                'payment_status' => 'success', // Always success since payment_id is required
                'payment_id' => $request->razorpay_payment_id,
                'message' => $request->message,
                'payment_date' => $request->razorpay_payment_id ? now() : null,
            ]);
            
            \Log::info('Campaign sponsor created successfully', [
                'sponsor_id' => $sponsor->id,
                'amount' => $sponsor->amount
            ]);

            // Send notification to doctor
            if ($campaign->doctor) {
                $doctorMessage = \App\Models\DoctorMessage::create([
                    'doctor_id' => $campaign->doctor->id,
                    'message' => "🎉 New Sponsor Alert! {$request->name} has sponsored ₹" . number_format($request->amount) . " for your campaign '{$campaign->title}'. Total sponsorship progress updated!",
                    'type' => 'new_sponsor',
                    'is_read' => false,
                ]);

                // Trigger real-time event
                event(new \App\Events\DoctorMessageSent($doctorMessage));
            }

            // Send notification to admin
            $admin = \App\Models\Admin::first();
            if ($admin) {
                $doctorName = $campaign->doctor->doctor_name ?? 'TBD';
                $adminMessage = \App\Models\AdminMessage::create([
                    'admin_id' => $admin->id,
                    'message' => "💰 New Campaign Sponsorship! {$request->name} sponsored ₹" . number_format($request->amount) . " for campaign '{$campaign->title}' by Dr. {$doctorName}.",
                    'type' => 'new_sponsorship',
                    'is_read' => false,
                ]);

                // Trigger real-time event
                event(new \App\Events\AdminMessageSent($adminMessage));
            }

            DB::commit();

            return redirect()->route('user.campaigns')
                ->with('success', 'Thank you for your sponsorship! Your payment of ₹' . number_format($request->amount) . ' has been successfully processed and confirmed. You will receive a sponsorship certificate and acknowledgment email shortly.');

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Campaign sponsorship error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return back()->withInput()
                ->with('error', 'Sponsorship failed: ' . $e->getMessage());
        }
    }

    /**
     * Handle referral earnings when a referred user makes a paid campaign registration
     */
    private function handleReferralEarnings($registration, $campaign)
    {
        try {
            // Get the user who made the registration
            $user = User::find($registration->user_id);
            
            if (!$user || !$user->referred_by) {
                return; // No referral to process
            }

            // Find the referrer
            $referrer = User::where('your_referral_id', $user->referred_by)->first();
            
            if (!$referrer) {
                \Log::warning('Referrer not found for referral code: ' . $user->referred_by);
                return;
            }

            // Get the per_refer_cost for this campaign
            $perReferCost = $campaign->per_refer_cost ?? 0;
            
            if ($perReferCost <= 0) {
                \Log::info('No referral cost set for campaign: ' . $campaign->id);
                return;
            }

            // Create campaign referral record
            $campaignReferral = \App\Models\CampaignReferral::create([
                'user_id' => $user->id,
                'campaign_id' => $campaign->id,
                'referrer_user_id' => $referrer->id,
                'per_campaign_refer_cost' => $perReferCost,
                'referral_code' => 'REF_' . $referrer->id . '_' . $campaign->id . '_' . time(),
                'registration_completed_at' => now(),
                'status' => 'completed',
                'notes' => 'Payment completed for campaign registration'
            ]);

            // Update referrer's earnings
            $referrer->total_earnings = ($referrer->total_earnings ?? 0) + $perReferCost;
            $referrer->available_balance = ($referrer->available_balance ?? 0) + $perReferCost;
            $referrer->save();

            // Send notification to referrer about earning
            \App\Models\UserMessage::create([
                'user_id' => $referrer->id,
                'message' => "💰 Congratulations! You earned ₹{$perReferCost} from {$user->username}'s registration in campaign '{$campaign->title}'. Your total earnings: ₹" . number_format($referrer->total_earnings, 2),
                'type' => 'referral_payment',
                'is_read' => false,
            ]);

            \Log::info('Referral earnings processed successfully', [
                'referrer_id' => $referrer->id,
                'referred_user_id' => $user->id,
                'campaign_id' => $campaign->id,
                'amount_earned' => $perReferCost,
                'total_earnings' => $referrer->total_earnings
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to process referral earnings', [
                'error' => $e->getMessage(),
                'registration_id' => $registration->id,
                'campaign_id' => $campaign->id
            ]);
        }
    }

    /**
     * Save user location coordinates (AJAX endpoint)
     */
    public function saveUserLocation(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'source' => 'nullable|string|in:gps,manual,api,automatic',
                'address' => 'nullable|string|max:500'
            ]);

            // Only save if user is logged in
            if (!auth('user')->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $user = auth('user')->user();
            
            // Update user location
            $user->update([
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'location_address' => $request->address,
                'location_source' => $request->source ?? 'manual',
                'location_updated_at' => now(),
                'location_permission_granted' => true,
                'ip_address' => $request->ip()
            ]);

            Log::info('User location saved successfully:', [
                'user_id' => $user->id,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'source' => $request->source,
                'address' => $request->address
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Location saved successfully',
                'data' => [
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'address' => $request->address,
                    'source' => $request->source
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Save user location error: ' . $e->getMessage(), [
                'user_id' => auth('user')->id(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to save location: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user location coordinates
     */
    public function updateUserLocation(Request $request)
    {
        try {
            $request->validate([
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'source' => 'string|in:gps,manual,api'
            ]);

            $user = auth('user')->user();
            
            $user->update([
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'location_source' => $request->source ?? 'manual',
                'location_updated_at' => now(),
                'location_permission_granted' => true,
                'ip_address' => $request->ip()
            ]);

            Log::info('User location updated via AJAX:', [
                'user_id' => $user->id,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'source' => $request->source
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Location updated successfully',
                'coordinates' => [$request->latitude, $request->longitude]
            ]);

        } catch (\Exception $e) {
            Log::error('User location update error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update location: ' . $e->getMessage()
            ], 500);
        }
    }
}
