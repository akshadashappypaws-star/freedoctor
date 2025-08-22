<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Doctor;
use App\Models\DoctorMessage;
use App\Models\Specialty;
use App\Events\DoctorMessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $campaigns = Campaign::with('doctor')->latest()->get();
        $doctors = Doctor::all();
        $specialties = Specialty::all();
        return view('admin.pages.campaigns', compact('campaigns', 'doctors', 'specialties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'location' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'camp_type' => 'required|in:medical,surgical',
            'amount' => 'nullable|numeric|min:0',
            'registration_payment' => 'nullable|numeric|min:0',
            'per_refer_cost' => 'nullable|numeric|min:0',
            'doctor_id' => 'required|exists:doctors,id',
            'category_id' => 'nullable|exists:categories,id',
            'specializations' => 'required|array|min:1',
            'specializations.*' => 'exists:specialties,id',
            'contact_number' => 'required|string|min:10|max:15|regex:/^[0-9+\-\s()]+$/',
            'expected_patients' => 'required|integer|min:10|max:10000',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|mimes:mp4,mov,avi,wmv|max:30720',
            'service_in_camp' => 'required|string|min:20',
            'approval_status' => 'nullable|in:pending,approved,rejected',
        ], [
            'title.required' => 'Campaign title is required',
            'title.max' => 'Campaign title cannot exceed 255 characters',
            'description.required' => 'Campaign description is required',
            'description.min' => 'Campaign description must be at least 10 characters',
            'location.required' => 'Campaign location is required',
            'latitude.required' => 'Location coordinates are required. Please select a valid location.',
            'latitude.numeric' => 'Invalid latitude coordinate',
            'latitude.between' => 'Latitude must be between -90 and 90 degrees',
            'longitude.required' => 'Location coordinates are required. Please select a valid location.',
            'longitude.numeric' => 'Invalid longitude coordinate',
            'longitude.between' => 'Longitude must be between -180 and 180 degrees',
            'start_date.required' => 'Start date is required',
            'start_date.after_or_equal' => 'Start date cannot be in the past',
            'end_date.required' => 'End date is required',
            'end_date.after_or_equal' => 'End date must be same or after start date',
            'start_time.required' => 'Start time is required',
            'start_time.date_format' => 'Start time must be in valid format (HH:MM)',
            'end_time.required' => 'End time is required',
            'end_time.date_format' => 'End time must be in valid format (HH:MM)',
            'end_time.after' => 'End time must be after start time',
            'camp_type.required' => 'Camp type is required',
            'camp_type.in' => 'Camp type must be either medical or surgical',
            'doctor_id.required' => 'Doctor selection is required',
            'doctor_id.exists' => 'Selected doctor does not exist',
            'specializations.required' => 'At least one specialization is required',
            'specializations.min' => 'Please select at least one specialization',
            'contact_number.required' => 'Contact number is required',
            'contact_number.min' => 'Contact number must be at least 10 digits',
            'contact_number.max' => 'Contact number cannot exceed 15 characters',
            'contact_number.regex' => 'Contact number format is invalid',
            'expected_patients.required' => 'Expected patients count is required',
            'expected_patients.min' => 'Expected patients must be at least 10',
            'expected_patients.max' => 'Expected patients cannot exceed 10,000',
            'images.*.image' => 'All uploaded files must be images',
            'images.*.mimes' => 'Images must be jpeg, png, jpg, or gif format',
            'images.*.max' => 'Each image must not exceed 2MB',
            'video.mimes' => 'Video must be mp4, mov, avi, or wmv format',
            'video.max' => 'Video file must not exceed 30MB',
            'service_in_camp.required' => 'Services description is required',
            'service_in_camp.min' => 'Services description must be at least 20 characters',
        ]);

        $validated['images'] = $request->file('images') ? array_map(function ($image) {
            return $image->store('campaigns/images', 'public');
        }, $request->file('images')) : [];

        $validated['thumbnail'] = $request->file('thumbnail') ? $request->file('thumbnail')->store('campaigns/thumbnails', 'public') : null;

        $validated['video'] = $request->file('video') ? $request->file('video')->store('campaigns/videos', 'public') : null;

        Campaign::create($validated);

        return response()->json(['success' => true, 'message' => 'Campaign created successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $campaign = Campaign::with('doctor.specialty')->findOrFail($id);
        
        // // If this is the /show route (AJAX request for edit modal), return JSON
        // if (request()->is('*/show')) {
        //     return response()->json($campaign);
        // }
        
        // Otherwise, return the detail view
        return view('admin.pages.campaign_detail', compact('campaign'));
    }

    /**
     * Show the form for editing the specified resource.
     */
  
    public function Campaignapprove($id)
    {
        $campaign = Campaign::with('doctor')->findOrFail($id);
        $campaign->approval_status = 'approved';
        $campaign->save();

        // Send notification to the doctor
        if ($campaign->doctor) {
            $doctorMessage = DoctorMessage::create([
                'doctor_id' => $campaign->doctor_id,
                'campaign_id' => $campaign->id,
                'type' => 'campaign_approved',
                'message' => "🎉 Great news! Your campaign '{$campaign->title}' has been approved by the admin. You can now start promoting it and expect patient registrations soon!",
                'read' => false,
                'data' => [
                    'campaign_id' => $campaign->id,
                    'campaign_title' => $campaign->title,
                    'approval_status' => 'approved',
                    'action_type' => 'campaign_approval'
                ]
            ]);

            // Broadcast real-time notification
            try {
                event(new DoctorMessageSent($doctorMessage));
            } catch (\Exception $e) {
                // Log error but don't fail the approval process
                Log::error('Failed to broadcast doctor notification: ' . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Campaign approved successfully and doctor has been notified.'
        ]);
    }

    public function Campaignreject($id)
    {
        $campaign = Campaign::with('doctor')->findOrFail($id);
        $campaign->approval_status = 'rejected';
        $campaign->save();

        // Send notification to the doctor
        if ($campaign->doctor) {
            $doctorMessage = DoctorMessage::create([
                'doctor_id' => $campaign->doctor_id,
                'campaign_id' => $campaign->id,
                'type' => 'campaign_rejected',
                'message' => "❌ Your campaign '{$campaign->title}' has been rejected by the admin. Please review the campaign details and contact support if you need assistance.",
                'read' => false,
                'data' => [
                    'campaign_id' => $campaign->id,
                    'campaign_title' => $campaign->title,
                    'approval_status' => 'rejected',
                    'action_type' => 'campaign_rejection'
                ]
            ]);

            // Broadcast real-time notification
            try {
                event(new DoctorMessageSent($doctorMessage));
            } catch (\Exception $e) {
                // Log error but don't fail the rejection process
                Log::error('Failed to broadcast doctor notification: ' . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Campaign rejected successfully and doctor has been notified.'
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'camp_type' => 'required|in:medical,surgical',
            'amount' => 'nullable|numeric|min:0',
            'registration_payment' => 'nullable|numeric|min:0',
            'per_refer_cost' => 'nullable|numeric|min:0',
            'doctor_id' => 'required|exists:doctors,id',
            'category_id' => 'nullable|exists:categories,id',
            'specializations' => 'required|array|min:1',
            'specializations.*' => 'exists:specialties,id',
            'contact_number' => 'required|string|min:10|max:15|regex:/^[0-9+\-\s()]+$/',
            'expected_patients' => 'required|integer|min:10|max:10000',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|mimes:mp4,mov,avi,wmv|max:30720',
            'service_in_camp' => 'required|string|min:20',
            'approval_status' => 'nullable|in:pending,approved,rejected',
        ]);

        if ($request->file('images')) {
            foreach ($campaign->images as $image) {
                Storage::disk('public')->delete($image);
            }
            $validated['images'] = array_map(function ($image) {
                return $image->store('campaigns/images', 'public');
            }, $request->file('images'));
        }

        if ($request->file('thumbnail')) {
            if ($campaign->thumbnail) {
                Storage::disk('public')->delete($campaign->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('campaigns/thumbnails', 'public');
        }

        if ($request->file('video')) {
            Storage::disk('public')->delete($campaign->video);
            $validated['video'] = $request->file('video')->store('campaigns/videos', 'public');
        }

        $campaign->update($validated);

        return response()->json(['success' => true, 'message' => 'Campaign updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campaign $campaign)
    {
        foreach ($campaign->images as $image) {
            Storage::disk('public')->delete($image);
        }

        if ($campaign->thumbnail) {
            Storage::disk('public')->delete($campaign->thumbnail);
        }

        if ($campaign->video) {
            Storage::disk('public')->delete($campaign->video);
        }

        $campaign->delete();

        return response()->json(['success' => true, 'message' => 'Campaign deleted successfully.']);
    }

    /**
     * Get campaign details for AJAX requests
     */
    public function getDetails($id)
    {
        $campaign = Campaign::with('doctor')->find($id);
        
        if (!$campaign) {
            return response()->json(['error' => 'Campaign not found'], 404);
        }
        
        return response()->json($campaign);
    }
}
