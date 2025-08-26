<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Campaign;
use App\Models\Doctor;
use App\Models\User;
use App\Models\DoctorPayment;
use App\Models\PatientRegistration;
use App\Models\BusinessOrganizationRequest;
use App\Models\AdminSetting;
use App\Models\AdminMessage;
use App\Events\DashboardUpdated;
use App\Events\ChartDataUpdated;
use App\Events\RecentActivitiesUpdated;
use Carbon\Carbon;
use App\Models\CampaignSponsor;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
  public function index()
{
    $admin = Auth::guard('admin')->user();

    // Get counts
    $totalCampaigns = Campaign::count();
    $totalDoctors = Doctor::count();
    $totalPatients = User::count();
    $totalPatientspaymnet = PatientRegistration::sum('amount');
    $totalSponsors = DB::table('campaign_sponsors')->distinct('sponsor_name')->count();
    $totalBusinessOpportunities = BusinessOrganizationRequest::count();

    // Admin settings
    $doctorSubscriptionFee = AdminSetting::where('setting_key', 'doctor_subscription_fee')->value('percentage_value') ?? 0;
    $sponsorCommission = AdminSetting::where('setting_key', 'sponsor_fee_percentage')->value('percentage_value') ?? 0;
    $registrationCommission = AdminSetting::where('setting_key', 'registration_fee_percentage')->value('percentage_value') ?? 0;

    // Total earnings
    $userRegistrationEarnings = $totalPatientspaymnet * ($registrationCommission / 100);
    // $doctorRegistrationEarnings = DoctorPayment::sum(DB::raw("amount * ($doctorSubscriptionFee / 100)"));
    $doctorRegistrationEarnings = 0;

    $patientCommissionEarnings = PatientRegistration::sum(DB::raw("amount * ($registrationCommission / 100)"));
    $sponsorEarnings = DB::table('campaign_sponsors')->sum(DB::raw("amount * ($sponsorCommission / 100)"));

    $totalEarnings = $userRegistrationEarnings + $doctorRegistrationEarnings + $patientCommissionEarnings + $sponsorEarnings;

    // Past 10 days earnings
$past10DaysEarnings = [];

for ($i = 9; $i >= 0; $i--) {
    $date = Carbon::now()->subDays($i)->toDateString();

    // Doctor earnings
    // $doctorAmount = DoctorPayment::whereDate('created_at', $date)->sum('amount');

    $doctorAmount = 0;
    $doctorCommission = $doctorAmount;

    // Patient earnings
    $patientAmount = PatientRegistration::whereDate('created_at', $date)->sum('amount');
    $patientCommission = ($patientAmount * $registrationCommission) / 100;

    // Sponsor earnings (per day)
    $dailySponsorAmount = CampaignSponsor::whereDate('created_at', $date)
        ->sum('amount');
    $dailySponsorCommission = ($dailySponsorAmount * $sponsorCommission) / 100;

    // Combine daily total
    $past10DaysEarnings[] = [
        'date' => Carbon::parse($date)->format('M d'),
        'amount' => $doctorCommission + $patientCommission + $dailySponsorCommission,
    ];
}



    // Monthly earnings
    $monthlyEarnings = [];
    for ($i = 11; $i >= 0; $i--) {
        $date = Carbon::now()->subMonths($i);
        $year = $date->year;
        $month = $date->month;

        // $doctorCount = DoctorPayment::whereYear('created_at', $year)->whereMonth('created_at', $month)->sum('amount');
        $doctorCount = 0;

        $doctorCommission = $doctorCount ;

        $patientAmount = PatientRegistration::whereYear('created_at', $year)->whereMonth('created_at', $month)->sum('amount');
        $patientCommission = ($patientAmount * $registrationCommission) / 100;
 $dailySponsorAmount = CampaignSponsor::whereDate('created_at', $date)
        ->sum('amount');
    $dailySponsorCommission = ($dailySponsorAmount * $sponsorCommission) / 100;

        $monthlyEarnings[] = [
            'month' => $date->format('M Y'),
            'amount' => $doctorCommission + $patientCommission + $dailySponsorCommission    ,
        ];
    }

    // Recent doctor payments

// $recentDoctorPayments = DoctorPayment::with('doctor')
//     ->latest()
//     ->take(5)
//     ->get()
//     ->map(function ($payment) use ($doctorSubscriptionFee) {
//         return (object)[
//             'type' => 'doctor',
//             'name' => optional($payment->doctor)->doctor_name ?? 'N/A',
//             'amount' => $payment->amount,
//             'admin_commission' => $doctorSubscriptionFee,
//             'created_at' => $payment->created_at,
//             'description' => 'Doctor Registration',
//         ];
//     });
$recentDoctorPayments = null;

    // Recent patient payments
  

$recentPatientRegistrations = PatientRegistration::with([ 'campaign'])
    ->latest()
    ->take(10)
    ->get()
    ->map(function ($registration) use ($registrationCommission) {
        $adminAmount = ($registration->amount * $registrationCommission) / 100;

        return (object)[
            'type' => 'patient',
            'name' => $registration->name ?? 'N/A',
            'amount' => $adminAmount,
            'created_at' => $registration->created_at,
            'description' => 'Patient Registration - ' . (optional($registration->campaign)->title ?? 'Unknown Campaign'),
        ];
    });


    // Combine and sort payments
    $recentPayments = $recentPatientRegistrations
        ->sortByDesc('created_at')
        ->take(20)
        ->values();

    // Recent campaigns
    $recentCampaigns = Campaign::latest()->take(10)->get();

    return view('admin.pages.home', compact(
        'admin',
        'totalCampaigns',
        'totalDoctors',
        'totalPatients',
        'totalSponsors',
        'totalBusinessOpportunities',
        'userRegistrationEarnings',
        'doctorRegistrationEarnings',
        'sponsorEarnings',
        'totalEarnings',
        'past10DaysEarnings',
        'monthlyEarnings',
        'recentPayments',
        'recentCampaigns'
    ));
}

    /**
     * Get real-time dashboard data via AJAX
     */
    public function getDashboardData()
    {
        // Get admin earnings settings
        $adminSettings = AdminSetting::first();
        $registrationCommission = $adminSettings->registration_commission ?? 0;
        $sponsorCommission = $adminSettings->sponsor_commission ?? 0;

        // Calculate all earnings in real-time
        $totalPatients = User::count();
        $userRegistrationEarnings = $totalPatients * $registrationCommission;
        
        // Doctor registration earnings (admin commission only)
        $totalDoctorPayments = DoctorPayment::sum('amount');
        $doctorRegistrationEarnings = ($totalDoctorPayments * AdminSetting::getPercentage('doctor_registration_commission')) / 100;
        
        // Patient payment earnings (direct admin commission)
        $patientCommissionEarnings = PatientRegistration::sum('amount');
        
        $sponsorEarnings = DB::table('campaign_sponsors')->sum('amount') * ($sponsorCommission / 100);
        $totalEarnings = $userRegistrationEarnings + $doctorRegistrationEarnings + $patientCommissionEarnings + $sponsorEarnings;
        
        $data = [
            'totalCampaigns' => Campaign::count(),
            'totalDoctors' => Doctor::count(),
            'totalPatients' => $totalPatients,
            'totalSponsors' => DB::table('campaign_sponsors')->distinct('sponsor_name')->count(),
            'totalBusinessOpportunities' => BusinessOrganizationRequest::count(),
            'userRegistrationEarnings' => $userRegistrationEarnings,
            'doctorRegistrationEarnings' => $doctorRegistrationEarnings,
            'sponsorEarnings' => $sponsorEarnings,
            'totalEarnings' => $totalEarnings,
            'timestamp' => now()->format('H:i:s')
        ];

        return response()->json($data);
    }

    /**
     * Get real-time chart data via AJAX
     */
    public function getChartData()
    {
        // Get earnings for the past 10 days
        $past10DaysEarnings = [];
        for ($i = 9; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            
            // Doctor payment earnings (admin commission)
            $doctorDayEarning = DoctorPayment::whereDate('created_at', $date)
                ->whereIn('payment_status', ['success', 'pending'])
                ->sum('amount');
            $doctorCommission = ($doctorDayEarning * AdminSetting::getPercentage('doctor_registration_commission')) / 100;
            
            // Patient payment earnings (direct admin commission)
            $patientDayEarning = PatientRegistration::sum('amount');
            
            $totalDayEarning = $doctorCommission + $patientDayEarning;
            
            $past10DaysEarnings[] = [
                'date' => $date->format('M d'),
                'amount' => $totalDayEarning
            ];
        }

        // Get monthly earnings for the past 12 months
        $monthlyEarnings = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            
            // Doctor payment earnings (admin commission)
            $doctorMonthEarning = DoctorPayment::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->whereIn('payment_status', ['success', 'pending'])
                ->sum('amount');
            $doctorCommission = ($doctorMonthEarning * AdminSetting::getPercentage('doctor_registration_commission')) / 100;
            
            // Patient payment earnings (direct admin commission)
            $patientMonthEarning = PatientRegistration::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->whereIn('payment_status', ['success', 'pending'])
                ->sum('amount');
            
            $totalMonthEarning = $doctorCommission + $patientMonthEarning;
            
            $monthlyEarnings[] = [
                'month' => $date->format('M Y'),
                'amount' => $totalMonthEarning
            ];
        }

        return response()->json([
            'past10Days' => $past10DaysEarnings,
            'monthly' => $monthlyEarnings
        ]);
    }

    /**
     * Get real-time recent activities data via AJAX
     */
    public function getRecentActivities()
    {
        // Recent activities - Combined doctor and patient payments
        $recentDoctorPayments = DoctorPayment::with('doctor')
            ->whereIn('payment_status', ['success', 'pending'])
            ->latest()
            ->take(3)
            ->get()
            ->map(function($payment) {
                return [
                    'type' => 'doctor',
                    'name' => $payment->doctor->doctor_name,
                    'amount' => $payment->amount,
                    'admin_commission' => ($payment->amount * AdminSetting::getPercentage('doctor_registration_commission')) / 100,
                    'payment_status' => $payment->payment_status,
                    'created_at' => $payment->created_at->diffForHumans(),
                    'created_timestamp' => $payment->created_at->timestamp,
                    'description' => 'Doctor Registration'
                ];
            });

        $recentPatientRegistrations = PatientRegistration::with(['patientRegistration.user', 'campaign'])
            ->whereIn('payment_status', ['success', 'pending'])
            ->latest()
            ->take(3)
            ->get()
            ->map(function($payment) {
                return [
                    'type' => 'patient', 
                    'name' => $payment->patientRegistration->user->name,
                    'amount' => $payment->amount,
                    'admin_commission' => $payment->admin_commission,
                    'payment_status' => $payment->payment_status,
                    'created_at' => $payment->created_at->diffForHumans(),
                    'created_timestamp' => $payment->created_at->timestamp,
                    'description' => 'Patient Registration - ' . $payment->campaign->campaign_name
                ];
            });

        // Combine and sort by creation timestamp
        $recentPayments = $recentDoctorPayments->concat($recentPatientRegistrations)
            ->sortByDesc('created_timestamp')
            ->take(5)
            ->values();
        
        $recentCampaigns = Campaign::latest()
            ->take(5)
            ->get()
            ->map(function($campaign) {
                $campaignDate = null;
                try {
                    if ($campaign->start_date) {
                        if ($campaign->start_date instanceof \Carbon\Carbon) {
                            $campaignDate = $campaign->start_date;
                        } else {
                            $campaignDate = \Carbon\Carbon::parse($campaign->start_date);
                        }
                    }
                } catch (\Exception $e) {
                    $campaignDate = null;
                }
                
                return [
                    'title' => $campaign->title,
                    'location' => $campaign->location,
                    'status' => $campaign->status,
                    'start_date' => $campaignDate ? $campaignDate->format('M d, Y') : 'Date not set'
                ];
            });

        return response()->json([
            'recentPayments' => $recentPayments,
            'recentCampaigns' => $recentCampaigns
        ]);
    }
    
    /**
     * Broadcast dashboard updates when data changes
     */
    public function broadcastDashboardUpdate($updateType = 'general')
    {
        $data = $this->calculateDashboardData();
        broadcast(new DashboardUpdated($data, $updateType));
        
        return response()->json([
            'success' => true,
            'message' => 'Dashboard update broadcasted',
            'type' => $updateType,
            'data' => $data
        ]);
    }
    
    /**
     * Broadcast chart data updates
     */
    public function broadcastChartUpdate()
    {
        $chartData = $this->calculateChartData();
        broadcast(new ChartDataUpdated($chartData));
    }
    
    /**
     * Broadcast recent activities updates
     */
    public function broadcastActivitiesUpdate()
    {
        $activitiesData = $this->calculateRecentActivities();
        broadcast(new RecentActivitiesUpdated($activitiesData));
    }
    
    /**
     * Calculate dashboard data for broadcasting
     */
    private function calculateDashboardData()
    {
        // Get counts for different entities
        $totalCampaigns = Campaign::count();
        $totalDoctors = Doctor::count();
        $totalPatients = User::count();
        $totalSponsors = DB::table('campaign_sponsors')->distinct('sponsor_name')->count();
        $totalBusinessOpportunities = BusinessOrganizationRequest::count();

        // Get admin earnings
        $adminSettings = AdminSetting::first();
        $registrationCommission = $adminSettings->registration_commission ?? 0;
        $sponsorCommission = $adminSettings->sponsor_commission ?? 0;

        // Calculate earnings
        $userRegistrationEarnings = $totalPatients * $registrationCommission;
        
        // Doctor registration earnings (admin commission only)
        $totalDoctorPayments = DoctorPayment::whereIn('payment_status', ['success', 'pending'])->sum('amount');
        $doctorRegistrationEarnings = ($totalDoctorPayments * AdminSetting::getPercentage('doctor_registration_commission')) / 100;
        
        // Patient payment earnings (direct admin commission)
        $patientCommissionEarnings = PatientRegistration::sum('amount');
        
        // Sponsor earnings (commission from campaigns)
        $sponsorEarnings = 0; // Placeholder for sponsor commissions
        
        // Total earnings
        $totalEarnings = $userRegistrationEarnings + $doctorRegistrationEarnings + $patientCommissionEarnings + $sponsorEarnings;

        return [
            'totalCampaigns' => $totalCampaigns,
            'totalDoctors' => $totalDoctors,
            'totalPatients' => $totalPatients,
            'totalSponsors' => $totalSponsors,
            'totalBusinessOpportunities' => $totalBusinessOpportunities,
            'userRegistrationEarnings' => $userRegistrationEarnings,
            'doctorRegistrationEarnings' => $doctorRegistrationEarnings,
            'sponsorEarnings' => $sponsorEarnings,
            'totalEarnings' => $totalEarnings,
            'timestamp' => now()->format('H:i:s')
        ];
    }
    
    /**
     * Calculate chart data for broadcasting
     */
    private function calculateChartData()
    {
        // Get earnings for the past 10 days
        $past10DaysEarnings = [];
        for ($i = 9; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            
            $doctorDayEarning = DoctorPayment::whereDate('created_at', $date)
                ->whereIn('payment_status', ['success', 'pending'])
                ->sum('amount');
            $doctorCommission = ($doctorDayEarning * AdminSetting::getPercentage('doctor_registration_commission')) / 100;
            
            $patientDayEarning = PatientRegistration::sum('amount');
            
            $totalDayEarning = $doctorCommission + $patientDayEarning;
            
            $past10DaysEarnings[] = [
                'date' => $date->format('M d'),
                'amount' => $totalDayEarning
            ];
        }

        // Get monthly earnings for the past 12 months
        $monthlyEarnings = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            
            $doctorMonthEarning = DoctorPayment::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->whereIn('payment_status', ['success', 'pending'])
                ->sum('amount');
            $doctorCommission = ($doctorMonthEarning * AdminSetting::getPercentage('doctor_registration_commission')) / 100;
            
            $patientMonthEarning = PatientRegistration::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->whereIn('payment_status', ['success', 'pending'])
                ->sum('amount');
            
            $totalMonthEarning = $doctorCommission + $patientMonthEarning;
            
            $monthlyEarnings[] = [
                'month' => $date->format('M Y'),
                'amount' => $totalMonthEarning
            ];
        }

        return [
            'past10Days' => $past10DaysEarnings,
            'monthly' => $monthlyEarnings
        ];
    }
    
    /**
     * Calculate recent activities for broadcasting
     */
    private function calculateRecentActivities()
    {
        $recentDoctorPayments = DoctorPayment::with('doctor')
            ->whereIn('payment_status', ['success', 'pending'])
            ->latest()
            ->take(3)
            ->get()
            ->map(function($payment) {
                return [
                    'type' => 'doctor',
                    'name' => $payment->doctor->doctor_name,
                    'amount' => $payment->amount,
                    'admin_commission' => ($payment->amount * AdminSetting::getPercentage('doctor_registration_commission')) / 100,
                    'payment_status' => $payment->payment_status,
                    'created_at' => $payment->created_at->diffForHumans(),
                    'created_timestamp' => $payment->created_at->timestamp,
                    'description' => 'Doctor Registration'
                ];
            });

        $recentPatientRegistrations = PatientRegistration::with(['patientRegistration.user', 'campaign'])
            ->whereIn('payment_status', ['success', 'pending'])
            ->latest()
            ->take(3)
            ->get()
            ->map(function($payment) {
                return [
                    'type' => 'patient', 
                    'name' => $payment->patientRegistration->user->name,
                    'amount' => $payment->amount,
                    'admin_commission' => $payment->admin_commission,
                    'payment_status' => $payment->payment_status,
                    'created_at' => $payment->created_at->diffForHumans(),
                    'created_timestamp' => $payment->created_at->timestamp,
                    'description' => 'Patient Registration - ' . $payment->campaign->campaign_name
                ];
            });

        $recentPayments = $recentDoctorPayments->concat($recentPatientRegistrations)
            ->sortByDesc('created_timestamp')
            ->take(5)
            ->values();
        
        $recentCampaigns = Campaign::latest()
            ->take(5)
            ->get()
            ->map(function($campaign) {
                $campaignDate = null;
                try {
                    if ($campaign->start_date) {
                        if ($campaign->start_date instanceof \Carbon\Carbon) {
                            $campaignDate = $campaign->start_date;
                        } else {
                            $campaignDate = \Carbon\Carbon::parse($campaign->start_date);
                        }
                    }
                } catch (\Exception $e) {
                    $campaignDate = null;
                }
                
                return [
                    'title' => $campaign->title,
                    'location' => $campaign->location,
                    'status' => $campaign->status,
                    'start_date' => $campaignDate ? $campaignDate->format('M d, Y') : 'Date not set'
                ];
            });

        return [
            'recentPayments' => $recentPayments,
            'recentCampaigns' => $recentCampaigns
        ];
    }

    /**
     * Check for new notifications for admin
     */
    public function checkNewNotifications()
    {
        try {
            $admin = Auth::guard('admin')->user();
            $newNotifications = AdminMessage::where(function($query) use ($admin) {
                $query->where('admin_id', $admin->id)
                      ->orWhereNull('admin_id'); // System messages for all admins
            })
            ->where('read', false)
            ->latest()
            ->take(10)
            ->get();

            return response()->json([
                'notifications' => $newNotifications->map(function($notification) {
                    return [
                        'id' => $notification->id,
                        'type' => $notification->type,
                        'message' => $notification->message,
                        'data' => $notification->data,
                        'created_at' => $notification->created_at->format('Y-m-d H:i:s'),
                        'time_ago' => $notification->created_at->diffForHumans()
                    ];
                }),
                'count' => $newNotifications->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Error checking admin notifications: ' . $e->getMessage());
            return response()->json(['notifications' => [], 'count' => 0]);
        }
    }

    /**
     * Mark admin notifications as read
     */
    public function markNotificationsAsRead(Request $request)
    {
        try {
            $admin = Auth::guard('admin')->user();
            
            if ($request->has('notification_ids')) {
                AdminMessage::where(function($query) use ($admin) {
                    $query->where('admin_id', $admin->id)
                          ->orWhereNull('admin_id');
                })
                ->whereIn('id', $request->notification_ids)
                ->update(['read' => true]);
            } else {
                AdminMessage::where(function($query) use ($admin) {
                    $query->where('admin_id', $admin->id)
                          ->orWhereNull('admin_id');
                })
                ->where('read', false)
                ->update(['read' => true]);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error marking admin notifications as read: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to mark notifications as read'], 500);
        }
    }
}
