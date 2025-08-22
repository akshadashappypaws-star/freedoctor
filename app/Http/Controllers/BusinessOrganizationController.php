<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\BusinessOrganizationRequest;
use App\Models\BusinessRequest;
use App\Models\Specialty;
use App\Models\Doctor;
use App\Models\DoctorMessage;
use App\Events\DoctorMessageSent;

class BusinessOrganizationController extends Controller
{
    public function index()
    {
        $requests = BusinessOrganizationRequest::with(['specialty', 'hiredDoctor.specialty'])->latest()->paginate(10);
        $specialties = Specialty::all();
        return view('admin.pages.business-organization', compact('requests', 'specialties'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'camp_request_type' => 'required|in:medical,surgical',
            'specialty_id' => 'required|exists:specialties,id',
            'date_from' => 'required|date|after_or_equal:today',
            'date_to' => 'required|date|after_or_equal:date_from',
            'number_of_people' => 'required|integer|min:1',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $businessRequest = BusinessOrganizationRequest::create($validated);

        // Send notifications to doctors with matching specialty
        try {
            $specialty = Specialty::find($validated['specialty_id']);
            $specialtyName = $specialty ? $specialty->name : 'Unknown Specialty';
            
            $matchingDoctors = Doctor::where('specialty_id', $validated['specialty_id'])
                ->where('approved_by_admin', true)
                ->where('status', true)
                ->get();

            Log::info('BusinessOrganizationController: Found ' . $matchingDoctors->count() . ' doctors for specialty: ' . $specialtyName);

            foreach ($matchingDoctors as $doctor) {
                $doctorMessage = DoctorMessage::create([
                    'doctor_id' => $doctor->id,
                    'type' => 'business_request',
                    'message' => "🎯 New Business Opportunity! {$businessRequest->organization_name} is looking for {$specialtyName} doctors for a {$businessRequest->camp_request_type} camp in {$businessRequest->location} from " . 
                               date('M d, Y', strtotime($businessRequest->date_from)) . " to " . date('M d, Y', strtotime($businessRequest->date_to)) . 
                               ". Expected participants: {$businessRequest->number_of_people}. Submit your proposal now!",
                    'is_read' => false
                ]);

                Log::info('BusinessOrganizationController: Created message ID ' . $doctorMessage->id . ' for doctor ' . $doctor->id);

                // Broadcast real-time notification to doctor
                try {
                    event(new DoctorMessageSent($doctorMessage));
                    Log::info('BusinessOrganizationController: Broadcasted notification to doctor ' . $doctor->id);
                } catch (\Exception $e) {
                    Log::error('BusinessOrganizationController: Broadcast failed for doctor ' . $doctor->id . ': ' . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            Log::error('BusinessOrganizationController: Error sending notifications: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Business organization request submitted successfully!');
    }

    public function doctorRequests()
    {
        $requests = BusinessRequest::with([
            'businessOrganizationRequest.specialty', 
            'doctor.specialty'
        ])->latest()->paginate(10);
        
        return view('admin.pages.doctor-business-requests', compact('requests'));
    }

    public function approveDoctor(Request $request, $businessRequestId)
    {
        $businessRequest = BusinessRequest::findOrFail($businessRequestId);
        
        // Update the business request status
        $businessRequest->update([
            'status' => 'approved',
            'reviewed_at' => now()
        ]);

        // Update the organization request with hired doctor
        $businessRequest->businessOrganizationRequest->update([
            'hired_doctor_id' => $businessRequest->doctor_id,
            'status' => 'doctor_hired'
        ]);

        // Reject other pending requests for the same organization request
        BusinessRequest::where('business_organization_request_id', $businessRequest->business_organization_request_id)
            ->where('id', '!=', $businessRequestId)
            ->where('status', 'pending')
            ->update(['status' => 'rejected', 'reviewed_at' => now()]);

        return response()->json(['success' => true, 'message' => 'Doctor approved successfully!']);
    }
}
