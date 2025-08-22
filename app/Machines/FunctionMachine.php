<?php

namespace App\Machines;

use App\Models\Campaign;
use App\Models\Doctor;
use App\Models\User;
use App\Models\PatientRegistration;
use App\Models\DoctorPayment;
use Exception;
use Illuminate\Support\Facades\DB;

class FunctionMachine extends BaseMachine
{
    protected string $machineType = 'function';

    /**
     * Get default settings for Function machine
     */
    public static function getDefaultSettings(): array
    {
        return [
            'timeout' => 60,
            'max_retries' => 3,
            'retry_delay' => 2,
            'available_functions' => [
                'searchNearbyDoctors',
                'findHealthCamps',
                'checkDoctorAvailability',
                'registerPatient',
                'processPayment',
                'sendNotification',
                'calculateDistance',
                'fetchUserProfile'
            ],
            'database_timeout' => 30,
            'cache_enabled' => true,
            'cache_duration' => 300,
            'error_handling' => 'graceful',
            'logging_enabled' => true
        ];
    }

    public function execute(array $input, int $stepNumber): array
    {
        return $this->safeExecute(function () use ($input) {
            $this->validateInput($input, ['function_name']);

            $functionName = $input['function_name'];
            $parameters = $input['parameters'] ?? [];

            return $this->callFunction($functionName, $parameters);
        }, $stepNumber, $input['function_name'] ?? 'unknown', $input);
    }

    /**
     * Route function calls to appropriate methods
     */
    private function callFunction(string $functionName, array $parameters): array
    {
        switch ($functionName) {
            case 'searchNearbyDoctors':
                return $this->searchNearbyDoctors($parameters);
            
            case 'findHealthCamps':
                return $this->findHealthCamps($parameters);
            
            case 'checkDoctorAvailability':
                return $this->checkDoctorAvailability($parameters);
            
            case 'processPatientRegistration':
                return $this->processPatientRegistration($parameters);
            
            case 'calculateDistance':
                return $this->calculateDistance($parameters);
            
            case 'getLocationInfo':
                return $this->getLocationInfo($parameters);
            
            case 'validatePayment':
                return $this->validatePayment($parameters);
            
            case 'sendNotification':
                return $this->sendNotification($parameters);
            
            case 'getDoctorStats':
                return $this->getDoctorStats($parameters);
            
            case 'getPatientHistory':
                return $this->getPatientHistory($parameters);
            
            default:
                throw new Exception("Function '{$functionName}' not found");
        }
    }

    /**
     * Search for nearby doctors
     */
    private function searchNearbyDoctors(array $params): array
    {
        $specialty = $params['specialty'] ?? null;
        $latitude = $params['latitude'] ?? null;
        $longitude = $params['longitude'] ?? null;
        $radius = $params['radius'] ?? 10; // km
        $limit = $params['limit'] ?? 10;

        $query = Doctor::with(['user', 'specialty'])
            ->where('approved_by_admin', true)
            ->where('is_active', true);

        // Filter by specialty
        if ($specialty) {
            $query->whereHas('specialty', function ($q) use ($specialty) {
                $q->where('name', 'like', "%{$specialty}%");
            });
        }

        // Filter by location if coordinates provided
        if ($latitude && $longitude) {
            $query->whereRaw("
                (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * 
                cos(radians(longitude) - radians(?)) + sin(radians(?)) * 
                sin(radians(latitude)))) <= ?
            ", [$latitude, $longitude, $latitude, $radius]);
        }

        $doctors = $query->limit($limit)->get();

        return [
            'doctors' => $doctors->map(function ($doctor) use ($latitude, $longitude) {
                $distance = null;
                if ($latitude && $longitude && $doctor->latitude && $doctor->longitude) {
                    $distance = $this->calculateDistanceBetweenPoints(
                        $latitude, $longitude,
                        $doctor->latitude, $doctor->longitude
                    );
                }

                return [
                    'id' => $doctor->id,
                    'name' => $doctor->user->name,
                    'specialty' => $doctor->specialty->name ?? 'General',
                    'qualification' => $doctor->qualification,
                    'experience' => $doctor->experience,
                    'consultation_fee' => $doctor->consultation_fee,
                    'address' => $doctor->address,
                    'phone' => $doctor->phone,
                    'distance_km' => $distance ? round($distance, 2) : null,
                    'rating' => $doctor->rating ?? 0,
                    'is_available' => $doctor->is_available_today ?? true
                ];
            })->toArray(),
            'total_found' => $doctors->count(),
            'search_radius_km' => $radius,
            'specialty_filter' => $specialty
        ];
    }

    /**
     * Find health camps
     */
    private function findHealthCamps(array $params): array
    {
        $latitude = $params['latitude'] ?? null;
        $longitude = $params['longitude'] ?? null;
        $radius = $params['radius'] ?? 20; // km
        $category = $params['category'] ?? null;
        $status = $params['status'] ?? 'active';
        $limit = $params['limit'] ?? 15;

        $query = Campaign::with(['category', 'doctor.user'])
            ->where('status', $status)
            ->where('start_date', '>=', now())
            ->orderBy('start_date');

        // Filter by category
        if ($category) {
            $query->whereHas('category', function ($q) use ($category) {
                $q->where('name', 'like', "%{$category}%");
            });
        }

        // Filter by location if coordinates provided
        if ($latitude && $longitude) {
            $query->whereRaw("
                (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * 
                cos(radians(longitude) - radians(?)) + sin(radians(?)) * 
                sin(radians(latitude)))) <= ?
            ", [$latitude, $longitude, $latitude, $radius]);
        }

        $campaigns = $query->limit($limit)->get();

        return [
            'health_camps' => $campaigns->map(function ($campaign) use ($latitude, $longitude) {
                $distance = null;
                if ($latitude && $longitude && $campaign->latitude && $campaign->longitude) {
                    $distance = $this->calculateDistanceBetweenPoints(
                        $latitude, $longitude,
                        $campaign->latitude, $campaign->longitude
                    );
                }

                return [
                    'id' => $campaign->id,
                    'title' => $campaign->title,
                    'description' => $campaign->description,
                    'category' => $campaign->category->name ?? 'General',
                    'doctor_name' => $campaign->doctor->user->name ?? 'Not assigned',
                    'location' => $campaign->location,
                    'start_date' => $campaign->start_date->format('Y-m-d'),
                    'start_time' => $campaign->start_time,
                    'end_time' => $campaign->end_time,
                    'cost' => $campaign->cost,
                    'discount_percentage' => $campaign->discount_percentage,
                    'final_cost' => $campaign->final_cost,
                    'max_participants' => $campaign->max_participants,
                    'current_registrations' => $campaign->registrations_count ?? 0,
                    'distance_km' => $distance ? round($distance, 2) : null,
                    'registration_open' => $campaign->registration_open ?? true
                ];
            })->toArray(),
            'total_found' => $campaigns->count(),
            'search_radius_km' => $radius,
            'category_filter' => $category
        ];
    }

    /**
     * Check doctor availability
     */
    private function checkDoctorAvailability(array $params): array
    {
        $doctorId = $params['doctor_id'];
        $date = $params['date'] ?? now()->format('Y-m-d');

        $doctor = Doctor::with('user')->find($doctorId);
        
        if (!$doctor) {
            throw new Exception("Doctor not found with ID: {$doctorId}");
        }

        // Check if doctor is approved and active
        $isAvailable = $doctor->approved_by_admin && 
                      $doctor->is_active && 
                      ($doctor->is_available_today ?? true);

        // Check existing appointments for the date (if you have appointments table)
        $existingAppointments = 0; // This would query your appointments table

        return [
            'doctor_id' => $doctor->id,
            'doctor_name' => $doctor->user->name,
            'is_available' => $isAvailable,
            'date' => $date,
            'consultation_fee' => $doctor->consultation_fee,
            'available_slots' => $isAvailable ? $this->generateAvailableSlots($doctor, $date) : [],
            'existing_appointments' => $existingAppointments,
            'next_available_date' => $isAvailable ? $date : $this->getNextAvailableDate($doctor)
        ];
    }

    /**
     * Process patient registration
     */
    private function processPatientRegistration(array $params): array
    {
        $campaignId = $params['campaign_id'];
        $patientData = $params['patient_data'];

        $campaign = Campaign::find($campaignId);
        
        if (!$campaign) {
            throw new Exception("Campaign not found with ID: {$campaignId}");
        }

        // Check if registration is still open
        if (!($campaign->registration_open ?? true)) {
            throw new Exception("Registration is closed for this campaign");
        }

        // Check if campaign is full
        $currentRegistrations = PatientRegistration::where('campaign_id', $campaignId)->count();
        if ($campaign->max_participants && $currentRegistrations >= $campaign->max_participants) {
            throw new Exception("Campaign is fully booked");
        }

        // Create patient registration
        $registration = PatientRegistration::create([
            'campaign_id' => $campaignId,
            'patient_name' => $patientData['name'],
            'patient_phone' => $patientData['phone'],
            'patient_email' => $patientData['email'] ?? null,
            'patient_age' => $patientData['age'] ?? null,
            'patient_gender' => $patientData['gender'] ?? null,
            'medical_conditions' => $patientData['medical_conditions'] ?? null,
            'emergency_contact' => $patientData['emergency_contact'] ?? null,
            'amount' => $campaign->final_cost ?? $campaign->cost,
            'status' => 'pending_payment'
        ]);

        return [
            'registration_id' => $registration->id,
            'campaign_title' => $campaign->title,
            'patient_name' => $registration->patient_name,
            'amount_due' => $registration->amount,
            'status' => $registration->status,
            'registration_date' => $registration->created_at->format('Y-m-d H:i:s'),
            'payment_required' => $registration->amount > 0
        ];
    }

    /**
     * Calculate distance between two points
     */
    private function calculateDistance(array $params): array
    {
        $lat1 = $params['lat1'];
        $lon1 = $params['lon1'];
        $lat2 = $params['lat2'];
        $lon2 = $params['lon2'];

        $distance = $this->calculateDistanceBetweenPoints($lat1, $lon1, $lat2, $lon2);

        return [
            'distance_km' => round($distance, 2),
            'distance_miles' => round($distance * 0.621371, 2),
            'coordinates_from' => ['lat' => $lat1, 'lon' => $lon1],
            'coordinates_to' => ['lat' => $lat2, 'lon' => $lon2]
        ];
    }

    /**
     * Get location information
     */
    private function getLocationInfo(array $params): array
    {
        $location = $params['location'] ?? $params['address'];

        // This would integrate with a geocoding service
        // For now, returning mock data
        return [
            'address' => $location,
            'latitude' => 28.6139, // Delhi coordinates as example
            'longitude' => 77.2090,
            'city' => 'New Delhi',
            'state' => 'Delhi',
            'country' => 'India',
            'postal_code' => '110001'
        ];
    }

    /**
     * Validate payment
     */
    private function validatePayment(array $params): array
    {
        $paymentId = $params['payment_id'];
        $amount = $params['amount'];

        // This would integrate with your payment system (Razorpay)
        return [
            'payment_id' => $paymentId,
            'status' => 'verified',
            'amount' => $amount,
            'currency' => 'INR',
            'verified_at' => now()->toISOString()
        ];
    }

    /**
     * Send notification
     */
    private function sendNotification(array $params): array
    {
        $type = $params['type']; // sms, email, whatsapp, push
        $recipient = $params['recipient'];
        $message = $params['message'];

        // This would integrate with your notification system
        return [
            'notification_id' => uniqid(),
            'type' => $type,
            'recipient' => $recipient,
            'status' => 'sent',
            'sent_at' => now()->toISOString()
        ];
    }

    /**
     * Get doctor statistics
     */
    private function getDoctorStats(array $params): array
    {
        $doctorId = $params['doctor_id'];

        $doctor = Doctor::find($doctorId);
        if (!$doctor) {
            throw new Exception("Doctor not found");
        }

        $totalEarnings = DoctorPayment::where('doctor_id', $doctorId)
            ->where('status', 'completed')
            ->sum('amount');

        $totalPatients = PatientRegistration::whereHas('campaign', function ($q) use ($doctorId) {
            $q->where('doctor_id', $doctorId);
        })->count();

        $activeCampaigns = Campaign::where('doctor_id', $doctorId)
            ->where('status', 'active')
            ->count();

        return [
            'doctor_id' => $doctorId,
            'total_earnings' => $totalEarnings,
            'total_patients' => $totalPatients,
            'active_campaigns' => $activeCampaigns,
            'rating' => $doctor->rating ?? 0,
            'experience_years' => $doctor->experience
        ];
    }

    /**
     * Get patient history
     */
    private function getPatientHistory(array $params): array
    {
        $patientPhone = $params['patient_phone'];

        $registrations = PatientRegistration::with('campaign')
            ->where('patient_phone', $patientPhone)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return [
            'patient_phone' => $patientPhone,
            'total_registrations' => $registrations->count(),
            'registrations' => $registrations->map(function ($reg) {
                return [
                    'campaign_title' => $reg->campaign->title,
                    'registration_date' => $reg->created_at->format('Y-m-d'),
                    'amount' => $reg->amount,
                    'status' => $reg->status
                ];
            })->toArray()
        ];
    }

    /**
     * Calculate distance between two geographical points
     */
    private function calculateDistanceBetweenPoints($lat1, $lon1, $lat2, $lon2): float
    {
        $earthRadius = 6371; // km

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon/2) * sin($dLon/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $earthRadius * $c;
    }

    /**
     * Generate available time slots for doctor
     */
    private function generateAvailableSlots(Doctor $doctor, string $date): array
    {
        // This would check doctor's schedule and existing appointments
        $slots = [];
        $startTime = 9; // 9 AM
        $endTime = 17; // 5 PM

        for ($hour = $startTime; $hour < $endTime; $hour++) {
            $slots[] = sprintf('%02d:00', $hour);
            $slots[] = sprintf('%02d:30', $hour);
        }

        return $slots;
    }

    /**
     * Get next available date for doctor
     */
    private function getNextAvailableDate(Doctor $doctor): string
    {
        // This would check doctor's schedule
        return now()->addDay()->format('Y-m-d');
    }
}
