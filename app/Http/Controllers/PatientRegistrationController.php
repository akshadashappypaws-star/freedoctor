<?php

namespace App\Http\Controllers;

use App\Models\PatientRegistration;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PatientRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
        try {
            $query = PatientRegistration::with('campaign');

            // Apply filters
            if ($request->has('location') && $request->location) {
                $query->whereHas('campaign', function($q) use ($request) {
                    $q->where('location', 'like', '%' . $request->location . '%');
                });
            }

            if ($request->has('campaign_id') && $request->campaign_id) {
                $query->where('campaign_id', $request->campaign_id);
            }

            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            $patientRegistrations = $query->orderBy('created_at', 'desc')->get();
            $campaigns = Campaign::all();
            
            // Get unique locations from campaigns
            $locations = Campaign::distinct()->pluck('location');

            return view('admin.pages.patients', compact('patientRegistrations', 'campaigns', 'locations'));
        } catch (\Exception $e) {
            \Log::error('Error in PatientRegistrationController@index: ' . $e->getMessage());
            
            // Return view with empty collections if there's an error
            $patientRegistrations = collect();
            $campaigns = collect();
            $locations = collect();
            
            return view('admin.pages.patients', compact('patientRegistrations', 'campaigns', 'locations'))
                ->with('error', 'There was an issue loading the patients data. Please check your database connection.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string',
            'campaign_id' => 'required|exists:campaigns,id',
            'description' => 'nullable|string',
        ]);

        $patientRegistration = PatientRegistration::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Patient registered successfully!',
            'data' => $patientRegistration
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $patient = PatientRegistration::with('campaign.doctor')->findOrFail($id);
        return response()->json($patient);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $patient = PatientRegistration::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:yet_to_came,came,not_came'
        ]);

        $patient->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Patient status updated successfully!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $patient = PatientRegistration::findOrFail($id);
        $patient->delete();

        return response()->json([
            'success' => true,
            'message' => 'Patient registration deleted successfully!'
        ]);
    }

    /**
     * Export filtered data to Excel
     */
    public function export(Request $request)
    {
        $query = PatientRegistration::with('campaign');

        // Apply same filters as index
        if ($request->has('location') && $request->location) {
            $query->whereHas('campaign', function($q) use ($request) {
                $q->where('location', 'like', '%' . $request->location . '%');
            });
        }

        if ($request->has('campaign_id') && $request->campaign_id) {
            $query->where('campaign_id', $request->campaign_id);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $patientRegistrations = $query->get();

        // Create CSV content
        $csvContent = "Name,Phone Number,Email,Campaign,Location,Status,Registration Date\n";
        
        foreach ($patientRegistrations as $patient) {
            $csvContent .= sprintf(
                '"%s","%s","%s","%s","%s","%s","%s"' . "\n",
                $patient->name,
                $patient->phone_number,
                $patient->email,
                $patient->campaign->title ?? 'N/A',
                $patient->campaign->location ?? 'N/A',
                $patient->getStatusLabel(),
                $patient->created_at->format('Y-m-d H:i:s')
            );
        }

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="patient_registrations_' . date('Y_m_d_H_i_s') . '.csv"');
    }
}
