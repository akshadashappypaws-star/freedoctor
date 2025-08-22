<?php
namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
 use App\Models\AdminSetting;
 use App\Models\PatientRegistration;
 use App\Models\CampaignSponsor;
 use App\Models\DoctorPayment;
use App\Models\BusinessOrganizationRequest;

 use Illuminate\Support\Carbon;
 use DB;
class DashboardController extends Controller
{
 public function index()
    {
return view('doctor.pages.home');
    }


      public function profit()
    {
        $doctor = Auth::guard('doctor')->user();
          $sponsorCommission = AdminSetting::where('setting_key', 'sponsor_fee_percentage')->value('percentage_value') ?? 0;
    $registrationCommission = AdminSetting::where('setting_key', 'registration_fee_percentage')->value('percentage_value') ?? 0;

        // Doctor-specific statistics - real data from database
        $doctorCampaigns = $doctor->campaigns()->count();
        $doctorPatients = \App\Models\PatientRegistration::whereIn('campaign_id', 
            $doctor->campaigns()->pluck('id'))->count();
        $pendingCampaigns = $doctor->campaigns()->count();
        
        // Calculate earnings from doctor's campaigns
        $campaignPatientamount = \App\Models\PatientRegistration::whereIn('campaign_id', 
            $doctor->campaigns()->pluck('id'))->sum('amount'); // Assuming ₹500 per patient registration
            
            $decductedAmountregister = $campaignPatientamount * ($registrationCommission / 100); // ₹500 per patient registration
        $doctorEarnings = $campaignPatientamount - $decductedAmountregister; // ₹500 per patient registration
        

        $userRegistrationEarnings = $doctorEarnings ; // Doctor's share from user registrations
        $decductedAmountsponsor = \App\Models\CampaignSponsor::whereIn('campaign_id', 
            $doctor->campaigns()->pluck('id'))->sum('amount') *  ($sponsorCommission / 100);;
            
        $sponsorEarnings = \App\Models\CampaignSponsor::whereIn('campaign_id', 
            $doctor->campaigns()->pluck('id'))->sum('amount') - $decductedAmountsponsor; // 10% commission from sponsors
        $totalEarnings = $userRegistrationEarnings + $sponsorEarnings;
        
    
        
        // Chart data - based on doctor's actual campaigns
    $campaignIds = $doctor->campaigns()->pluck('id');

$past10DaysEarnings = [];
for ($i = 9; $i >= 0; $i--) {
    $date = now()->subDays($i)->toDateString();

    $dailyPatients = \App\Models\PatientRegistration::whereIn('campaign_id', $campaignIds)
        ->whereDate('created_at', $date)
        ->count();

    $dailyAmount = \App\Models\PatientRegistration::whereIn('campaign_id', $campaignIds)
        ->whereDate('created_at', $date)
        ->sum('amount');
$doctorEarningsdeduction = $dailyAmount * ($registrationCommission / 100); // Deduct registration commission
    $doctorEarnings = $dailyAmount - $doctorEarningsdeduction;

    $past10DaysEarnings[] = [
        'date' => $date,
        'patients' => $dailyPatients,
        'earnings' => round($doctorEarnings, 2)
    ];
}
$monthlyEarnings = [];

for ($i = 6; $i >= 0; $i--) {
    $month = now()->subMonths($i);
    $monthStart = $month->copy()->startOfMonth();
    $monthEnd = $month->copy()->endOfMonth();

    $monthKey = $month->format('Y-m'); // Ensure uniqueness like "2025-07"

    if (isset($monthlyEarnings[$monthKey])) {
        continue; // Skip if already processed
    }

    $monthlyPatients = \App\Models\PatientRegistration::whereIn('campaign_id', $campaignIds)
        ->whereBetween('created_at', [$monthStart, $monthEnd])
        ->count();

    $monthlyAmount = \App\Models\PatientRegistration::whereIn('campaign_id', $campaignIds)
        ->whereBetween('created_at', [$monthStart, $monthEnd])
        ->sum('amount');

    $doctorEarningsdeduction = $monthlyAmount * ($registrationCommission / 100);
    $doctorEarningsregister = $monthlyAmount - $doctorEarningsdeduction;

    $monthlyEarnings[$monthKey] = [
        'month' => $month->format('M'),
        'patients' => $monthlyPatients,
        'earnings' => round($doctorEarningsregister, 2)
    ];
}

// If you want a simple indexed array:
$monthlyEarnings = array_values($monthlyEarnings);




        
        // Recent activities for this doctor only
       $recentPayments = \App\Models\PatientRegistration::whereIn('campaign_id', $campaignIds)
    ->with('campaign')
    ->latest()
    ->take(5)
    ->get()
    ->map(function($registration) use ($registrationCommission) {
        $amount = $registration->amount ?? 0;
     $doctorEarningsdeduction = $amount * ($registrationCommission / 100); // Deduct registration commission
    $doctorCommission = $amount - $doctorEarningsdeduction;
        return (object)[
            'id' => $registration->id,
            'name' => $registration->name,
            'type' => 'user',
            'description' => 'Patient registration for ' . optional($registration->campaign)->title,
            'amount' => $amount,
            'admin_commission' => round($doctorCommission, 2),
            'created_at' => $registration->created_at
        ];
    });

        $recentCampaigns = $doctor->campaigns()->latest()->take(5)->get();
        
        return view('doctor.pages.profit', compact(
            'doctorCampaigns',
            'doctorPatients', 
            'pendingCampaigns',
            'doctorEarnings',
            'totalEarnings',  
            'userRegistrationEarnings',  
            'sponsorEarnings',
            'past10DaysEarnings',
            'monthlyEarnings',
            'recentPayments',
            'recentCampaigns'
        ));
    }
    
    
    // Add additional methods for other doctor portal functionality
    public function campaigns()
    {
        $doctor = Auth::guard('doctor')->user();
        $campaigns = $doctor->campaigns()->with('patientRegistrations')->get();
        $specialties = \App\Models\Specialty::all();
        $categories = \App\Models\Category::where('is_active', true)->get();
        
        return view('doctor.pages.campaigns', compact('doctor', 'campaigns', 'specialties', 'categories'));
    }
    
    public function patients()
    {
        $doctor = Auth::guard('doctor')->user();
        // Get patient registrations for all campaigns belonging to this doctor
        $patientRegistrations = \App\Models\PatientRegistration::whereIn('campaign_id', 
            $doctor->campaigns()->pluck('id')
        )->with('campaign')->get();
        $campaigns = $doctor->campaigns()->get();
        $locations = $campaigns->pluck('location')->unique()->values();
       

$registrationFeePercentage = AdminSetting::where('setting_key', 'registration_fee_percentage')->value('percentage_value') ?? 0;

$totalAmount = $patientRegistrations->sum('amount');
$finalAmount = $totalAmount - ($totalAmount * $registrationFeePercentage / 100);
        return view('doctor.pages.patients', compact('doctor', 'patientRegistrations','finalAmount', 'campaigns', 'locations'));
    }
    
    public function sponsors()
    {
        $doctor = Auth::guard('doctor')->user();
        $campaignIds = $doctor->campaigns()->pluck('id'); 
        
        // Get sponsorships for all campaigns belonging to this doctor
        $sponsorships = \App\Models\CampaignSponsor::whereIn('campaign_id', $campaignIds)
            ->with(['campaign' => function($query) {
                $query->with('doctor.specialty');
            }])
            ->get();
        
        // Filter sponsors by doctor's medical specialty (if campaigns have specializations)
        $doctorSpecialty = $doctor->specialty ? $doctor->specialty->name : null;
        $specialtyRelatedSponsors = collect([]);
        
        if ($doctorSpecialty) {
            $specialtyRelatedSponsors = $sponsorships->filter(function($sponsorship) use ($doctorSpecialty) {
                // Check if campaign specializations contain doctor's specialty
                $campaignSpecializations = $sponsorship->campaign->specializations ?? [];
                if (is_array($campaignSpecializations)) {
                    return in_array($doctorSpecialty, $campaignSpecializations);
                }
                return false;
            });
        }
        
        // Calculate comprehensive sponsor statistics
        $totalSponsors = $sponsorships->count();
        $specialtySponsors = $specialtyRelatedSponsors->count();
        $totalFunding = $sponsorships->sum('amount');
        $specialtyFunding = $specialtyRelatedSponsors->sum('amount');
        
        // Get pending sponsorships count from database query
        $pendingSponsorships = \App\Models\CampaignSponsor::whereIn('campaign_id', $campaignIds)
            ->where('payment_status', 'pending')->count();
            
        // Get successful sponsorships for earnings calculation
        $successfulSponsorships = \App\Models\CampaignSponsor::whereIn('campaign_id', $campaignIds)
            ->where('payment_status', 'success')->count();
            
        $successfulFunding = \App\Models\CampaignSponsor::whereIn('campaign_id', $campaignIds)
            ->sum('amount');
        
        // Get failed sponsorships count
        $paidSponsorships = $successfulSponsorships;
        $failedSponsorships = \App\Models\CampaignSponsor::whereIn('campaign_id', $campaignIds)
            ->where('payment_status', 'failed')->count();
        
        // Calculate doctor's earnings from sponsors (10% commission)
  // Get the setting value (default to 10% if not found)
$sponsorFeeSetting = \App\Models\AdminSetting::where('setting_key', 'sponsor_fee_percentage')->first();
$sponsorFeePercentage = $sponsorFeeSetting ? $sponsorFeeSetting->percentage_value : 10;

// Assume $successfulFunding is total amount funded by sponsors
$doctorEarningsFromSponsors = $successfulFunding * ((100 - $sponsorFeePercentage) / 100);

        
        $sponsoredCampaigns = $sponsorships->pluck('campaign_id')->unique()->count();
        
        // Get Business Organization Requests related to doctor's specialty
        $businessOrgRequests = \App\Models\BusinessOrganizationRequest::where('specialty_id', $doctor->specialty_id)
            ->with(['specialty', 'hiredDoctor', 'businessRequests' => function($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id);
            }])
            ->orderBy('created_at', direction: 'desc')
            ->get();
        
        // Get doctor's business request applications
        $doctorBusinessRequests = \App\Models\BusinessRequest::where('doctor_id', $doctor->id)
            ->with(['businessOrganizationRequest.specialty'])
            ->orderBy('applied_at', 'desc')
            ->get();
        
        // Business organization statistics
        $totalBusinessRequests = $businessOrgRequests->count();
        $appliedRequests = $doctorBusinessRequests->count();
        $approvedRequests = $doctorBusinessRequests->where('status', 'approved')->count();
        $pendingRequests = $doctorBusinessRequests->where('status', 'pending')->count();
        $rejectedRequests = $doctorBusinessRequests->where('status', 'rejected')->count();
        
        // Calculate potential earnings from business requests
        $potentialBusinessEarnings = $approvedRequests * 5000; // Assuming ₹5000 per approved business request
        
        // Group business requests by status for charts
        $businessRequestsByStatus = $doctorBusinessRequests->groupBy('status');
        
        // Get all specialties for filtering
        $specialties = \App\Models\Specialty::all();
        
        // Group sponsors by payment status for better visualization
        $sponsorsByStatus = $sponsorships->groupBy('payment_status');
        
        // Get campaigns for filter dropdown
        $campaigns = $doctor->campaigns()->select('id', 'title')->orderBy('title')->get();
        
        return view('doctor.pages.sponsors', compact(
            'doctor', 
            'sponsorships', 
            'specialtyRelatedSponsors',
            'totalSponsors', 
            'specialtySponsors',
            'sponsorFeePercentage',
            'totalFunding', 
            'specialtyFunding',
            'pendingSponsorships', 
            'successfulSponsorships',
            'paidSponsorships',
            'failedSponsorships',
            'successfulFunding',
            'doctorEarningsFromSponsors',
            'sponsoredCampaigns',
            'doctorSpecialty',
            'specialties',
            'sponsorsByStatus',
            'businessOrgRequests',
            'doctorBusinessRequests', 
            'totalBusinessRequests',
            'appliedRequests',
            'approvedRequests', 
            'pendingRequests',
            'rejectedRequests',
            'potentialBusinessEarnings',
            'businessRequestsByStatus',
            'campaigns'
        ));
    }
    
    public function businessReachOut()
    {
        $doctor = Auth::guard('doctor')->user();
        
        // Get actual business organization requests for this doctor's specialty
        $businessOrgRequests = \App\Models\BusinessOrganizationRequest::with(['specialty', 'hiredDoctor'])
            ->where('specialty_id', $doctor->specialty_id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get doctor's business request applications
        $doctorBusinessRequests = \App\Models\BusinessRequest::where('doctor_id', $doctor->id)
            ->with(['businessOrganizationRequest.specialty'])
            ->orderBy('applied_at', 'desc')
            ->get();
            
        // Get doctor's proposals
        $doctorProposals = \App\Models\DoctorProposal::where('doctor_id', $doctor->id)
            ->with('businessOrganizationRequest')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calculate real statistics
        $totalRequests = $businessOrgRequests->count();
        $totalProposals = $doctorProposals->count();
        $appliedRequests = $doctorBusinessRequests->count();
        $approvedRequests = $doctorBusinessRequests->where('status', 'approved')->count();
        $pendingRequests = $doctorBusinessRequests->where('status', 'pending')->count();
        $rejectedRequests = $doctorBusinessRequests->where('status', 'rejected')->count();
        $activePartnerships = $approvedRequests; // Approved requests are active partnerships
        
        // Calculate earnings from approved business requests
        $potentialEarnings = $approvedRequests * 5000; // ₹5000 per approved request
        
        // Get sponsor statistics for this doctor's campaigns
        $campaignIds = $doctor->campaigns()->pluck('id');
        $totalSponsors = \App\Models\CampaignSponsor::whereIn('campaign_id', $campaignIds)->count();
        $successfulSponsors = \App\Models\CampaignSponsor::whereIn('campaign_id', $campaignIds)
            ->where('payment_status', 'success')->count();
        $totalSponsorAmount = \App\Models\CampaignSponsor::whereIn('campaign_id', $campaignIds)
            ->where('payment_status', 'success')->sum('amount');
        $sponsorEarnings = $totalSponsorAmount * 0.1; // 10% commission
        
        // Get patient registration statistics
        $totalPatients = \App\Models\PatientRegistration::whereIn('campaign_id', $campaignIds)->count();
        $patientEarnings = $totalPatients * 300; // ₹300 per patient registration
        
        // Calculate total earnings
        $totalEarnings = $potentialEarnings + $sponsorEarnings + $patientEarnings;
        
        return view('doctor.pages.business-reach-out', compact(
            'doctor', 
            'businessOrgRequests',
            'doctorBusinessRequests',
            'doctorProposals',
            'totalRequests',
            'totalProposals',
            'appliedRequests',
            'approvedRequests', 
            'pendingRequests', 
            'rejectedRequests',
            'activePartnerships',
            'potentialEarnings',
            'totalSponsors',
            'successfulSponsors',
            'totalSponsorAmount',
            'sponsorEarnings',
            'totalPatients',
            'patientEarnings',
            'totalEarnings'
        ));
    }
    
    public function profile()
    {
        $doctor = Auth::guard('doctor')->user();
        
        return view('doctor.pages.profile', compact('doctor'));
    }
    

public function notifications() 
{
    $doctor = Auth::guard('doctor')->user();
    
    // Get notifications with relationships
    $notifications = DB::table('doctor_messages as dm')
        ->leftJoin('campaigns as c', 'dm.campaign_id', '=', 'c.id')
        ->leftJoin('users as u', 'dm.user_id', '=', 'u.id')
        ->select(
            'dm.*',
            'c.title as campaign_name',
            'c.location as campaign_location',
            'u.username as user_name',
            'u.email as user_email'
        )
        ->where('dm.doctor_id', $doctor->id)
        ->orderBy('dm.created_at', 'desc')
        ->get()
        ->map(function ($notification) {
            // Add formatted data
            $notification->created_at = \Carbon\Carbon::parse($notification->created_at);
            $notification->read_at = $notification->read ? $notification->updated_at : null;
            
            // Generate title based on type
            switch ($notification->type) {
                case 'campaign':
                    $notification->title = 'New Campaign: ' . $notification->campaign_name;
                    break;
                case 'patient':
                    $notification->title = 'Patient Registration: ' . $notification->user_name;
                    break;
                case 'approval':
                    $notification->title = 'Campaign Approved';
                    break;
                case 'payment':
                    $notification->title = 'Payment Received: ₹' . number_format($notification->amount);
                    break;
                case 'business_request':
                    $notification->title = 'New Business Opportunity';
                    break;
                case 'rejection':
                    $notification->title = 'Proposal Rejected';
                    break;
                default:
                    $notification->title = 'Notification';
            }
            
            return $notification;
        });
    
    return view('doctor.pages.notifications', compact('doctor', 'notifications'));
}

public function markAllNotificationsRead()
{
    $doctor = Auth::guard('doctor')->user();
    
    DB::table('doctor_messages')
        ->where('doctor_id', $doctor->id)
        ->where('read', 0)
        ->update([
            'read' => 1,
            'updated_at' => now()
        ]);
    
    return response()->json(['success' => true]);
}

public function markNotificationRead($id)
{
    $doctor = Auth::guard('doctor')->user();
    
    DB::table('doctor_messages')
        ->where('id', $id)
        ->where('doctor_id', $doctor->id)
        ->update([
            'read' => 1,
            'updated_at' => now()
        ]);
    
    return response()->json(['success' => true]);
}

public function deleteNotification($id)
{
    $doctor = Auth::guard('doctor')->user();
    
    DB::table('doctor_messages')
        ->where('id', $id)
        ->where('doctor_id', $doctor->id)
        ->delete();
    
    return response()->json(['success' => true]);
}

public function checkNewNotifications()
{
    try {
        $doctor = Auth::guard('doctor')->user();
        if (!$doctor) {
            return response()->json(['notifications' => []]);
        }
        
        // Get unread notifications using the correct 'read' column
        $newNotifications = \App\Models\DoctorMessage::where('doctor_id', $doctor->id)
            ->where(function($query) {
                $query->where('read', false)->orWhereNull('read');
            })
            ->where('created_at', '>=', now()->subHours(24))
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($message) {
                return [
                    'id' => $message->id,
                    'type' => $message->type ?? 'info',
                    'message' => $message->message,
                    'data' => $message->data,
                    'time_ago' => $message->created_at->diffForHumans(),
                    'created_at' => $message->created_at->format('Y-m-d H:i:s')
                ];
            });
        
        \Log::info('Doctor ' . $doctor->id . ' checking notifications - found ' . $newNotifications->count() . ' unread messages');
        
        return response()->json([
            'notifications' => $newNotifications,
            'count' => $newNotifications->count(),
            'doctor_id' => $doctor->id,
            'timestamp' => now()->format('Y-m-d H:i:s')
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Error checking doctor notifications: ' . $e->getMessage());
        return response()->json([
            'notifications' => [],
            'error' => $e->getMessage()
        ]);
    }
}

    /**
     * Mark doctor notifications as read
     */
    public function markNotificationsAsRead(Request $request)
    {
        try {
            $doctor = Auth::guard('doctor')->user();
            if (!$doctor) {
                return response()->json(['success' => false, 'message' => 'Doctor not authenticated']);
            }

            $notificationIds = $request->input('notification_ids', []);
            
            if (empty($notificationIds)) {
                // Mark all unread notifications as read if no specific IDs provided
                \App\Models\DoctorMessage::where('doctor_id', $doctor->id)
                    ->where(function($query) {
                        $query->where('read', false)->orWhereNull('read');
                    })
                    ->update(['read' => true]);
                    
                \Log::info('Marked all unread notifications as read for doctor: ' . $doctor->id);
            } else {
                // Mark specific notifications as read
                \App\Models\DoctorMessage::where('doctor_id', $doctor->id)
                    ->whereIn('id', $notificationIds)
                    ->update(['read' => true]);
                    
                \Log::info('Marked notifications as read for doctor ' . $doctor->id . ': ' . implode(',', $notificationIds));
            }

            return response()->json([
                'success' => true,
                'message' => 'Notifications marked as read',
                'doctor_id' => $doctor->id
            ]);

        } catch (\Exception $e) {
            \Log::error('Error marking doctor notifications as read: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark notifications as read'
            ], 500);
        }
    }

    // Additional methods for routes
    public function getDashboardData()
    {
        $doctor = Auth::guard('doctor')->user();
        return response()->json([
            'totalCampaigns' => $doctor->campaigns()->count(),
            'totalPatients' => \App\Models\PatientRegistration::whereIn('campaign_id', 
                $doctor->campaigns()->pluck('id'))->count(),
            'pendingCampaigns' => $doctor->campaigns()->where('approval_status', 'pending')->count(),
            'totalEarnings' => 45000 // Calculate from actual payments
        ]);
    }

    public function storeCampaign()
    {
        $doctor = Auth::guard('doctor')->user();
        
        $validatedData = request()->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:500',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'camp_type' => 'required|in:medical,surgical',
            'category_id' => 'required|exists:categories,id',
            'registration_payment' => 'nullable|numeric|min:0',
            'per_refer_cost' => 'nullable|numeric|min:0|max:10000',
            'amount' => 'nullable|numeric|min:0',
            'specializations' => 'required|array',
            'specializations.*' => 'exists:specialties,id',
            'contact_number' => 'required|string|max:20',
            'expected_patients' => 'required|integer|min:1',
            'service_in_camp' => 'required|string',
            'approval_status' => 'in:pending,approved,rejected',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'thumbnail' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'mimes:mp4,avi,mov,wmv|max:30720', // 30MB max
        ], [
            'title.required' => 'Campaign title is required',
            'description.required' => 'Campaign description is required',
            'location.required' => 'Campaign location is required',
            'latitude.required' => 'Please select a valid location with coordinates',
            'latitude.numeric' => 'Invalid latitude coordinate',
            'latitude.between' => 'Latitude must be between -90 and 90 degrees',
            'longitude.required' => 'Please select a valid location with coordinates',
            'longitude.numeric' => 'Invalid longitude coordinate',
            'longitude.between' => 'Longitude must be between -180 and 180 degrees',
            'start_date.required' => 'Start date is required',
            'end_date.required' => 'End date is required',
            'end_date.after_or_equal' => 'End date must be same or after start date',
            'start_time.required' => 'Start time is required',
            'start_time.date_format' => 'Start time must be in valid format (HH:MM)',
            'end_time.required' => 'End time is required',
            'end_time.date_format' => 'End time must be in valid format (HH:MM)',
            'end_time.after' => 'End time must be after start time',
            'camp_type.required' => 'Camp type is required',
            'camp_type.in' => 'Camp type must be either medical or surgical',
            'category_id.required' => 'Please select a category',
            'specializations.required' => 'Please select at least one specialization',
            'contact_number.required' => 'Contact number is required',
            'expected_patients.required' => 'Expected patients count is required',
            'expected_patients.min' => 'Expected patients must be at least 1',
            'service_in_camp.required' => 'Service description is required',
        ]);

        // Automatically set the doctor_id to the current logged-in doctor
        $validatedData['doctor_id'] = $doctor->id;
        
        // Set default approval status to pending for doctor-created campaigns
        if (!isset($validatedData['approval_status'])) {
            $validatedData['approval_status'] = 'pending';
        }

        try {
            // Log location data for tracking and validation
            Log::info('Campaign creation with location tracking', [
                'doctor_id' => $doctor->id,
                'doctor_name' => $doctor->doctor_name,
                'campaign_title' => $validatedData['title'],
                'location_text' => $validatedData['location'],
                'latitude' => $validatedData['latitude'],
                'longitude' => $validatedData['longitude'],
                'coordinates_precision' => [
                    'lat_precision' => strlen(substr(strrchr($validatedData['latitude'], "."), 1)),
                    'lng_precision' => strlen(substr(strrchr($validatedData['longitude'], "."), 1))
                ],
                'location_validation' => 'COORDINATES_PROVIDED'
            ]);
            
            // Create the campaign
            $campaign = \App\Models\Campaign::create($validatedData);

            // Handle file uploads
            $imagePaths = [];
            if (request()->hasFile('images')) {
                foreach (request()->file('images') as $image) {
                    $path = $image->store('campaigns/images', 'public');
                    $imagePaths[] = $path;
                }
            }

            if (!empty($imagePaths)) {
                $campaign->update(['images' => json_encode($imagePaths)]);
            }

            if (request()->hasFile('thumbnail')) {
                $thumbnailPath = request()->file('thumbnail')->store('campaigns/thumbnails', 'public');
                $campaign->update(['thumbnail' => $thumbnailPath]);
            }

            if (request()->hasFile('video')) {
                $videoPath = request()->file('video')->store('campaigns/videos', 'public');
                $campaign->update(['video' => $videoPath]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Campaign created successfully! It will be reviewed by administrators.',
                'campaign' => $campaign
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create campaign: ' . $e->getMessage()
            ], 500);
        }
    }

    public function showCampaign($id)
    {
        $doctor = Auth::guard('doctor')->user();
        $campaign = $doctor->campaigns()->findOrFail($id);
        
        // Return JSON for AJAX requests
        if (request()->ajax()) {
            // Ensure proper formatting for frontend
            $campaignData = $campaign->toArray();
            
            // Properly decode JSON fields if they're strings
            if (is_string($campaignData['images'])) {
                $campaignData['images'] = json_decode($campaignData['images'], true) ?? [];
            }
            if (is_string($campaignData['specializations'])) {
                $campaignData['specializations'] = json_decode($campaignData['specializations'], true) ?? [];
            }
            
            return response()->json($campaignData);
        }
        
        // Return view for regular requests
        return view('doctor.pages.campaign-details', compact('campaign'));
    }

    public function viewCampaign($id)
    {
        $doctor = Auth::guard('doctor')->user();
        $campaign = $doctor->campaigns()->with(['patients', 'sponsors'])->findOrFail($id);
        
        // Get campaign statistics
        $totalPatients = $campaign->patients()->count();
        $totalSponsors = $campaign->sponsors()->count();
        $totalSponsorAmount = $campaign->sponsors()->where('payment_status', 'success')->sum('amount');
        $registrationEarnings = $totalPatients * 300; // Doctor's share from registrations
        $sponsorEarnings = $totalSponsorAmount * 0.1; // 10% commission from sponsors
        $totalEarnings = $registrationEarnings + $sponsorEarnings;
        
        return view('doctor.pages.campaign-view', compact(
            'campaign', 
            'totalPatients', 
            'totalSponsors', 
            'totalSponsorAmount', 
            'totalEarnings',
            'registrationEarnings',
            'sponsorEarnings'
        ));
    }

    public function editCampaign($id)
    {
        $doctor = Auth::guard('doctor')->user();
        $campaign = $doctor->campaigns()->findOrFail($id);
        $specialties = \App\Models\Specialty::all();
        return view('doctor.pages.campaign-edit', compact('campaign', 'specialties'));
    }

    public function updateCampaign(Request $request, $id)
    {
        try {
            $doctor = Auth::guard('doctor')->user();
            $campaign = $doctor->campaigns()->findOrFail($id);

            // Validation rules
            $rules = [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'location' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'timings' => 'required|string|max:255',
                'camp_type' => 'required|in:medical,surgical',
                'amount' => 'nullable|numeric|min:0',
                'registration_payment' => 'nullable|numeric|min:0',
                'contact_number' => 'required|string|max:20',
                'expected_patients' => 'required|integer|min:1',
                'service_in_camp' => 'required|string',
                'specializations' => 'required|array',
                'specializations.*' => 'exists:specialties,id',
                'approval_status' => 'in:pending,approved,rejected',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'video' => 'nullable|file|mimes:mp4,avi,mov,wmv|max:30720',
            ];

            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Prepare update data
            $updateData = [
                'title' => $request->title,
                'description' => $request->description,
                'location' => $request->location,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'timings' => $request->timings,
                'camp_type' => $request->camp_type,
                'amount' => $request->amount,
                'registration_payment' => $request->registration_payment,
                'contact_number' => $request->contact_number,
                'expected_patients' => $request->expected_patients,
                'service_in_camp' => $request->service_in_camp,
                'specializations' => json_encode($request->specializations),
                'approval_status' => $request->approval_status ?? 'pending',
            ];

            // Handle file uploads only if new files are provided
            
            // Handle multiple images
            if ($request->hasFile('images')) {
                $imagePaths = [];
                foreach ($request->file('images') as $image) {
                    $imagePath = $image->store('campaigns/images', 'public');
                    $imagePaths[] = $imagePath;
                }
                $updateData['images'] = json_encode($imagePaths);
            }
            // If no new images provided, keep existing ones (don't update images field)

            // Handle thumbnail
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('campaigns/thumbnails', 'public');
                $updateData['thumbnail'] = $thumbnailPath;
            }
            // If no new thumbnail provided, keep existing one

            // Handle video
            if ($request->hasFile('video')) {
                $videoPath = $request->file('video')->store('campaigns/videos', 'public');
                $updateData['video'] = $videoPath;
            }
            // If no new video provided, keep existing one

            // Update the campaign
            $campaign->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Campaign updated successfully',
                'campaign' => $campaign
            ]);

        } catch (\Exception $e) {
            \Log::error('Campaign update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update campaign: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyCampaign($id)
    {
        try {
            $doctor = Auth::guard('doctor')->user();
            $campaign = $doctor->campaigns()->findOrFail($id);
            
            // Delete associated files if they exist
            if ($campaign->thumbnail) {
                \Storage::disk('public')->delete($campaign->thumbnail);
            }
            if ($campaign->images) {
                foreach ($campaign->images as $image) {
                    \Storage::disk('public')->delete($image);
                }
            }
            if ($campaign->video) {
                \Storage::disk('public')->delete($campaign->video);
            }
            
            $campaign->delete();
            
            // Return JSON for AJAX requests
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Campaign deleted successfully'
                ]);
            }
            
            return redirect()->route('doctor.campaigns')->with('success', 'Campaign deleted successfully');
        } catch (\Exception $e) {
            \Log::error('Campaign deletion failed: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete campaign: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('doctor.campaigns')->with('error', 'Failed to delete campaign');
        }
    }

    public function getCampaignDetails($id)
    {
        $doctor = Auth::guard('doctor')->user();
        $campaign = $doctor->campaigns()->with('patientRegistrations')->findOrFail($id);
        return response()->json($campaign);
    }

    public function showPatient($id)
    {
        $doctor = Auth::guard('doctor')->user();
        $patient = \App\Models\PatientRegistration::whereIn('campaign_id', 
            $doctor->campaigns()->pluck('id'))->findOrFail($id);
        return view('doctor.pages.patient-details', compact('patient'));
    }

    public function updatePatient($id)
    {
        // Update patient logic
        return redirect()->route('doctor.patients')->with('success', 'Patient updated successfully');
    }

    public function destroyPatient($id)
    {
        $doctor = Auth::guard('doctor')->user();
        $patient = \App\Models\PatientRegistration::whereIn('campaign_id', 
            $doctor->campaigns()->pluck('id'))->findOrFail($id);
        $patient->delete();
        return redirect()->route('doctor.patients')->with('success', 'Patient registration deleted successfully');
    }

    public function exportPatients()
    {
        // Export patients logic
        return response()->download('path/to/file.csv');
    }

    // Add other missing methods as stubs for now
    public function showSponsor($id) { /* Implementation needed */ }
    public function updateSponsor($id) { /* Implementation needed */ }
    public function destroySponsor($id) { /* Implementation needed */ }
    
    public function storeBusinessRequest() { /* Implementation needed */ }
    public function showBusinessRequest($id) { /* Implementation needed */ }
    public function editBusinessRequest($id) { /* Implementation needed */ }
    public function updateBusinessRequest($id) { /* Implementation needed */ }
    public function destroyBusinessRequest($id) { /* Implementation needed */ }
    
    public function updateProfile(Request $request) 
    { 
        $doctor = Auth::guard('doctor')->user();
        
        $validatedData = $request->validate([
            'doctor_name' => 'required|string|max:255',
            'email' => 'required|email|unique:doctors,email,' . $doctor->id,
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female,other',
            'specialty_id' => 'nullable|exists:specialties,id',
            'hospital_name' => 'nullable|string|max:255',
            'experience' => 'nullable|integer|min:0',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'intro_video' => 'nullable|file|mimes:mp4,avi,mov,wmv|max:10240'
        ]);
        
        try {
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($doctor->image) {
                    Storage::disk('public')->delete($doctor->image);
                }
                $validatedData['image'] = $request->file('image')->store('doctors/images', 'public');
            }
            
            // Handle intro video upload
            if ($request->hasFile('intro_video')) {
                // Delete old video if exists
                if ($doctor->intro_video) {
                    Storage::disk('public')->delete($doctor->intro_video);
                }
                $validatedData['intro_video'] = $request->file('intro_video')->store('doctors/videos', 'public');
            }
            
            $doctor->update($validatedData);
            
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile: ' . $e->getMessage()
            ], 500);
        }
    }
    public function updatePersonalInfo() { /* Implementation needed */ }
    public function updateProfessionalInfo() { /* Implementation needed */ }
    public function updateAvatar() { /* Implementation needed */ }
    public function updatePassword(Request $request) 
    { 
        $doctor = Auth::guard('doctor')->user();
        
        $validatedData = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);
        
        // Check if current password is correct
        if (!Hash::check($validatedData['current_password'], $doctor->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect.'
            ], 422);
        }
        
        try {
            $doctor->update([
                'password' => Hash::make($validatedData['new_password'])
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update password: ' . $e->getMessage()
            ], 500);
        }
    }
    

    public function destroyNotification($id) { /* Implementation needed */ }
    public function getUnreadNotificationCount() { /* Implementation needed */ }
    
    public function settings() { /* Implementation needed */ }
    public function updateSettings() { /* Implementation needed */ }
    public function updatePreferences() { /* Implementation needed */ }
    public function updateNotificationSettings() { /* Implementation needed */ }
    
    public function earnings() { /* Implementation needed */ }
    public function exportEarnings() { /* Implementation needed */ }
    public function paymentHistory() { /* Implementation needed */ }
    
    public function analytics() { /* Implementation needed */ }
    public function campaignPerformance() { /* Implementation needed */ }
    public function patientStatistics() { /* Implementation needed */ }
    public function earningsReport() { /* Implementation needed */ }
    
    public function uploadCampaignImage() { /* Implementation needed */ }
    public function uploadProfileImage() { /* Implementation needed */ }
    public function uploadDocument() { /* Implementation needed */ }
    public function deleteUpload($id) { /* Implementation needed */ }
    
    public function exportCampaigns() { /* Implementation needed */ }
    public function exportSponsors() { /* Implementation needed */ }
    public function exportBusinessRequests() { /* Implementation needed */ }
    
    public function sendMessage() { /* Implementation needed */ }
    public function getMessages() { /* Implementation needed */ }
    public function markMessageRead($id) { /* Implementation needed */ }
    
// public function profit()
// {
//     $doctor = Auth::guard('doctor')->user();
//     $campaignIds = $doctor->campaigns()->pluck('id');

//     // Commission Rates
//     $registrationCommission = AdminSetting::where('setting_key', 'registration_fee_percentage')->value('percentage_value') ?? 0;
//     $sponsorCommission = AdminSetting::where('setting_key', 'sponsor_fee_percentage')->value('percentage_value') ?? 0;

//     // Total Patient Registration Earnings
//     $totalPatientAmount = PatientRegistration::whereIn('campaign_id', $campaignIds)->sum('amount');
//     $registrationDeducted = $totalPatientAmount * ($registrationCommission / 100);
//     $userRegistrationEarnings = $totalPatientAmount - $registrationDeducted;

//     // Sponsor Earnings
//     $totalSponsorAmount = CampaignSponsor::whereIn('campaign_id', $campaignIds)->sum('amount');
//     $sponsorDeducted = $totalSponsorAmount * ($sponsorCommission / 100);
//     $sponsorEarnings = $totalSponsorAmount - $sponsorDeducted;

//     // Totals
//     $totalEarnings = $userRegistrationEarnings + $sponsorEarnings;
//     $totalPatients = PatientRegistration::whereIn('campaign_id', $campaignIds)->count();
//     $totalSponsors = CampaignSponsor::whereIn('campaign_id', $campaignIds)->count();
//     $pendingPayments = CampaignSponsor::whereIn('campaign_id', $campaignIds)->sum('amount') * ($sponsorCommission / 100);

//     // Chart - Monthly Earnings
//     $monthlyEarnings = [];
//     for ($i = 6; $i >= 0; $i--) {
//         $month = now()->subMonths($i);
//         $monthStart = $month->copy()->startOfMonth();
//         $monthEnd = $month->copy()->endOfMonth();
//         $monthKey = $month->format('Y-m');

//         if (isset($monthlyEarnings[$monthKey])) continue;

//         $monthlyAmount = PatientRegistration::whereIn('campaign_id', $campaignIds)
//             ->whereBetween('created_at', [$monthStart, $monthEnd])
//             ->sum('amount');

//         $monthlyPatients = PatientRegistration::whereIn('campaign_id', $campaignIds)
//             ->whereBetween('created_at', [$monthStart, $monthEnd])
//             ->count();

//         $doctorEarnings = $monthlyAmount - ($monthlyAmount * ($registrationCommission / 100));
//         $monthlyEarnings[$monthKey] = [
//             'month' => $month->format('M'),
//             'patients' => $monthlyPatients,
//             'earnings' => round($doctorEarnings, 2)
//         ];
//     }
//     $monthlyEarnings = array_values($monthlyEarnings);

//     // Unified earnings table (registration + sponsor)
//     $registrationEarnings = PatientRegistration::with('campaign')
//         ->whereIn('campaign_id', $campaignIds)
//         ->get()
//         ->map(function ($item) use ($registrationCommission) {
//             $commission = $item->amount * ($registrationCommission / 100);
//             return (object)[
//                 'earning_type' => 'registration',
//                 'original_amount' => $item->amount,
//                 'commission_amount' => $commission,
//                 'net_earning' => $item->amount - $commission,
//                 'percentage_rate' => $registrationCommission,
//                 'title' => optional($item->campaign)->title ?? 'No campaign',
//                 'created_at' => $item->created_at,
//             ];
//         });

//     $sponsorEarningList = CampaignSponsor::with('campaign')
//         ->whereIn('campaign_id', $campaignIds)

//         ->get()
//         ->map(function ($item) use ($sponsorCommission) {
//             $commission = $item->amount * ($sponsorCommission / 100);
//             return (object)[
//                 'earning_type' => 'sponsor',
//                 'original_amount' => $item->amount,
//                 'commission_amount' => $commission,
//                 'net_earning' => $item->amount - $commission,
//                 'percentage_rate' => $sponsorCommission,
//                 'title' => optional($item->campaign)->title ?? 'No campaign',
//                 'created_at' => $item->created_at,
//             ];
//         });

//     $earnings = $registrationEarnings->merge($sponsorEarningList)->sortByDesc('created_at');

//     return view('doctor.pages.profit', compact(
//         'userRegistrationEarnings',
//         'sponsorEarnings',
//         'totalEarnings',
//         'totalPatients',
//         'totalSponsors',
//         'pendingPayments',
//         'monthlyEarnings',
//         'earnings'
//     ));
// }

    
    public function publicProfile() { /* Implementation needed */ }
    public function updatePublicProfile() { /* Implementation needed */ }
    public function previewPublicProfile() { /* Implementation needed */ }
    
    public function getDashboardStats() { /* Implementation needed */ }
    public function getRecentActivities() { /* Implementation needed */ }
    public function getChartData($type) { /* Implementation needed */ }
    public function searchCampaigns() { /* Implementation needed */ }
    public function searchPatients() { /* Implementation needed */ }
    public function getSpecialties() { /* Implementation needed */ }
    public function getLocations() { /* Implementation needed */ }
    public function getCampaignTypes() { /* Implementation needed */ }

    /**
     * Doctor Wallet Dashboard
     */
    public function wallet()
    {
        $doctor = Auth::guard('doctor')->user();
        
        // Get registration and sponsor commission rates
        $registrationCommission = \App\Models\AdminSetting::where('setting_key', 'registration_fee_percentage')->value('percentage_value') ?? 10;
        $sponsorCommission = \App\Models\AdminSetting::where('setting_key', 'sponsor_fee_percentage')->value('percentage_value') ?? 5;
        
        // Calculate total earnings
        $campaignPatientAmount = \App\Models\PatientRegistration::whereIn('campaign_id', 
            $doctor->campaigns()->pluck('id'))->sum('amount');
            
        $deductedAmountRegister = $campaignPatientAmount * ($registrationCommission / 100);
        $registrationEarnings = $campaignPatientAmount - $deductedAmountRegister;
        
        $sponsorTotalAmount = \App\Models\CampaignSponsor::whereIn('campaign_id', 
            $doctor->campaigns()->pluck('id'))->sum('amount');
        $deductedAmountSponsor = $sponsorTotalAmount * ($sponsorCommission / 100);
        $sponsorEarnings = $sponsorTotalAmount - $deductedAmountSponsor;
        
        $totalEarnings = $registrationEarnings + $sponsorEarnings;
        
        // Get withdrawn amount (we'll need to create a doctor_withdrawals table)
        $withdrawnAmount = $doctor->withdrawn_amount ?? 0;
        $availableBalance = $totalEarnings - $withdrawnAmount;
        
        // Minimum withdrawal amount
        $minimumWithdrawal = 2000;
        
        // Get recent withdrawals from doctor_payments table only
        $recentWithdrawals = DoctorPayment::where('doctor_id', $doctor->id)
            ->where(function($query) {
                $query->whereJsonContains('payment_details->type', 'withdrawal_request')
                      ->orWhere('description', 'like', '%withdrawal%');
            })
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($payment) {
                $details = $payment->payment_details ?? [];
                return [
                    'id' => $payment->id,
                    'amount' => $payment->amount,
                    'status' => $payment->payment_status,
                    'bank_name' => $details['bank_name'] ?? 'N/A',
                    'account_number' => $details['account_number'] ?? 'N/A',
                    'ifsc_code' => $details['ifsc_code'] ?? 'N/A',
                    'account_holder_name' => $details['account_holder_name'] ?? 'N/A',
                    'order_id' => $payment->order_id,
                    'payment_id' => $payment->payment_id,
                    'receipt_number' => $payment->receipt_number,
                    'created_at' => $payment->created_at,
                    'type' => $details['type'] ?? 'withdrawal_request',
                    'description' => $payment->description
                ];
            });
        
        // Earnings breakdown
        $earningsBreakdown = [
            'total_registrations' => \App\Models\PatientRegistration::whereIn('campaign_id', 
                $doctor->campaigns()->pluck('id'))->count(),
            'registration_amount' => $campaignPatientAmount,
            'registration_commission' => $deductedAmountRegister,
            'registration_earnings' => $registrationEarnings,
            'total_sponsors' => \App\Models\CampaignSponsor::whereIn('campaign_id', 
                $doctor->campaigns()->pluck('id'))->count(),
            'sponsor_amount' => $sponsorTotalAmount,
            'sponsor_commission' => $deductedAmountSponsor,
            'sponsor_earnings' => $sponsorEarnings,
        ];
        
        return view('doctor.pages.wallet', compact(
            'doctor',
            'totalEarnings',
            'withdrawnAmount',
            'availableBalance',
            'minimumWithdrawal',
            'recentWithdrawals',
            'earningsBreakdown',
            'registrationCommission',
            'sponsorCommission'
        ));
    }

    /**
     * Process withdrawal request
     */
    public function processWithdrawal(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:2000',
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:20',
            'ifsc_code' => 'required|string|max:11',
            'account_holder_name' => 'required|string|max:255',
        ]);

        $doctor = Auth::guard('doctor')->user();
        
        // Calculate available balance
        $registrationCommission = \App\Models\AdminSetting::where('setting_key', 'registration_fee_percentage')->value('percentage_value') ?? 10;
        $sponsorCommission = \App\Models\AdminSetting::where('setting_key', 'sponsor_fee_percentage')->value('percentage_value') ?? 5;
        
        $campaignPatientAmount = \App\Models\PatientRegistration::whereIn('campaign_id', 
            $doctor->campaigns()->pluck('id'))->sum('amount');
        $deductedAmountRegister = $campaignPatientAmount * ($registrationCommission / 100);
        $registrationEarnings = $campaignPatientAmount - $deductedAmountRegister;
        
        $sponsorTotalAmount = \App\Models\CampaignSponsor::whereIn('campaign_id', 
            $doctor->campaigns()->pluck('id'))->sum('amount');
        $deductedAmountSponsor = $sponsorTotalAmount * ($sponsorCommission / 100);
        $sponsorEarnings = $sponsorTotalAmount - $deductedAmountSponsor;
        
        $totalEarnings = $registrationEarnings + $sponsorEarnings;
        $withdrawnAmount = $doctor->withdrawn_amount ?? 0;
        $availableBalance = $totalEarnings - $withdrawnAmount;
        
        // Validate withdrawal amount
        if ($request->amount > $availableBalance) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient balance. Available balance: ₹' . number_format($availableBalance, 2)
            ]);
        }
        
        if ($request->amount < 2000) {
            return response()->json([
                'success' => false,
                'message' => 'Minimum withdrawal amount is ₹2,000'
            ]);
        }
        
        try {
            // Initialize Razorpay API for order creation
            $api = new \Razorpay\Api\Api(config('services.razorpay.key'), config('services.razorpay.secret'));
            
            // Create Razorpay order for withdrawal tracking
            $orderData = [
                'amount' => $request->amount * 100, // Convert to paise
                'currency' => 'INR',
                'receipt' => 'doctor_withdrawal_' . $doctor->id . '_' . time(),
                'notes' => [
                    'purpose' => 'doctor_earnings_withdrawal',
                    'doctor_id' => $doctor->id,
                    'bank_account' => $request->account_number,
                    'ifsc' => $request->ifsc_code,
                    'account_holder' => $request->account_holder_name
                ]
            ];
            
            $order = $api->order->create($orderData);
            
            // Create withdrawal record in doctor_payments table only
            \App\Models\DoctorPayment::create([
                'doctor_id' => $doctor->id,
                'amount' => $request->amount, // Store as positive amount for withdrawal tracking
                'payment_id' => null, // Will be updated when admin processes payout
                'order_id' => $order['id'],
                'payment_status' => 'pending', // Will change to 'completed' when admin processes payout
                'payment_details' => [
                    'type' => 'withdrawal_request',
                    'bank_name' => $request->bank_name,
                    'account_number' => $request->account_number,
                    'ifsc_code' => $request->ifsc_code,
                    'account_holder_name' => $request->account_holder_name,
                    'withdrawal_request_time' => now()->toDateTimeString(),
                    'razorpay_order' => $order,
                    'earnings_breakdown' => [
                        'registration_earnings' => $registrationEarnings,
                        'sponsor_earnings' => $sponsorEarnings,
                        'total_earnings' => $totalEarnings,
                        'previous_withdrawn' => $withdrawnAmount,
                        'available_balance' => $availableBalance
                    ]
                ],
                'payment_date' => now(),
                'receipt_number' => 'WDR_' . $doctor->id . '_' . time(),
                'description' => 'Doctor Earnings Withdrawal Request - Pending Admin Payout'
            ]);
            
            // Update doctor's withdrawn amount and bank details
            $doctor->update([
                'withdrawn_amount' => $withdrawnAmount + $request->amount,
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'ifsc_code' => $request->ifsc_code,
                'account_holder_name' => $request->account_holder_name
            ]);
            
            // Log withdrawal request for admin processing
            \Log::info('Doctor withdrawal request created', [
                'doctor_id' => $doctor->id,
                'amount' => $request->amount,
                'order_id' => $order['id'],
                'bank_details' => [
                    'account_number' => '****' . substr($request->account_number, -4),
                    'ifsc' => $request->ifsc_code,
                    'bank_name' => $request->bank_name
                ]
            ]);
            
            // Send notification to doctor
            \App\Models\DoctorMessage::create([
                'doctor_id' => $doctor->id,
                'type' => 'withdrawal',
                'message' => "Your withdrawal request of ₹" . number_format($request->amount, 2) . " has been submitted successfully (Order ID: {$order['id']}). You will receive the amount in your bank account within 24 hours.",
                'is_read' => false
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Withdrawal request submitted successfully! You will receive the amount in your bank account within 24 hours.',
                'order_id' => $order['id'],
                'receipt_number' => 'WDR_' . $doctor->id . '_' . time()
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Doctor withdrawal error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to process withdrawal. Please try again.'
            ]);
        }
    }

    /**
     * Update bank account details
     */
    public function updateAccountDetails(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:20',
            'ifsc_code' => 'required|string|max:11',
            'account_holder_name' => 'required|string|max:255',
        ]);

        $doctor = Auth::guard('doctor')->user();
        
        try {
            $doctor->update([
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'ifsc_code' => $request->ifsc_code,
                'account_holder_name' => $request->account_holder_name,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Bank account details updated successfully!'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Doctor bank details update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update bank details. Please try again.'
            ]);
        }
    }
}

