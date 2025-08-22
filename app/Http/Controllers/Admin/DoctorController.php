<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class DoctorController extends Controller
{
    /**
     * Display a listing of doctors
     */
 
    /**
     * Store a newly created doctor
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'doctor_name' => 'required|string|max:255',
            'email' => 'required|email|unique:doctors,email',
            'phone' => 'required|string|max:20|unique:doctors,phone',
            'phone_verified' => 'nullable|in:0,1',
            'location' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'specialty_id' => 'required|exists:specialties,id',
            'hospital_name' => 'required|string|max:255',
            'experience' => 'required|integer|min:0|max:50',
            'description' => 'nullable|string',
            'intro_video' => 'nullable|file|mimes:mp4,avi,mov,wmv,flv|max:30720',
            'password' => 'nullable|string|min:8',
            'status' => 'nullable|in:0,1',
            'approved_by_admin' => 'in:yes,no',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $doctorData = $request->except(['image', 'password', 'intro_video']);
            $doctorData['status'] = $request->boolean('status', true);
            $doctorData['phone_verified'] = $request->boolean('phone_verified', false);
            
            // Only add password if provided
            if ($request->filled('password')) {
                $doctorData['password'] = bcrypt($request->password);
            } else {
                // Set a default password if none provided
                $doctorData['password'] = bcrypt('password123');
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('doctors', $imageName, 'public');
                $doctorData['image'] = $imagePath;
            }

            // Handle video upload
            if ($request->hasFile('intro_video')) {
                $video = $request->file('intro_video');
                $videoName = time() . '_video_' . uniqid() . '.' . $video->getClientOriginalExtension();
                $videoPath = $video->storeAs('doctors/videos', $videoName, 'public');
                $doctorData['intro_video'] = $videoPath;
            }

            $doctor = Doctor::create($doctorData);
            $doctor->load('specialty');

            return response()->json([
                'success' => true,
                'message' => 'Doctor added successfully',
                'doctor' => $doctor
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add doctor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified doctor
     */
    public function show($id)
    {
        try {
            $doctor = Doctor::with('specialty')->findOrFail($id);
            return response()->json($doctor);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Doctor not found'
            ], 404);
        }
    }

    /**
     * Update the specified doctor
     */
    public function update(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'doctor_name' => 'required|string|max:255',
            'email' => 'required|email|unique:doctors,email,' . $id,
            'phone' => 'required|string|max:20|unique:doctors,phone,' . $id,
            'phone_verified' => 'nullable|in:0,1',
            'location' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'specialty_id' => 'required|exists:specialties,id',
            'hospital_name' => 'required|string|max:255',
            'experience' => 'required|integer|min:0|max:50',
            'description' => 'nullable|string',
            'intro_video' => 'nullable|file|mimes:mp4,avi,mov,wmv,flv|max:30720',
            'password' => 'nullable|string|min:8',
            'status' => 'nullable|in:0,1',
            'approved_by_admin' => 'in:yes,no',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $doctorData = $request->except(['image', 'password', 'intro_video']);
            $doctorData['status'] = $request->boolean('status', true);
            $doctorData['phone_verified'] = $request->boolean('phone_verified', false);
            
            // Only update password if provided
            if ($request->filled('password')) {
                $doctorData['password'] = bcrypt($request->password);
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($doctor->image) {
                    Storage::disk('public')->delete($doctor->image);
                }

                $image = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('doctors', $imageName, 'public');
                $doctorData['image'] = $imagePath;
            }

            // Handle video upload
            if ($request->hasFile('intro_video')) {
                // Delete old video if exists
                if ($doctor->intro_video) {
                    Storage::disk('public')->delete($doctor->intro_video);
                }

                $video = $request->file('intro_video');
                $videoName = time() . '_video_' . uniqid() . '.' . $video->getClientOriginalExtension();
                $videoPath = $video->storeAs('doctors/videos', $videoName, 'public');
                $doctorData['intro_video'] = $videoPath;
            }

            $doctor->update($doctorData);
            $doctor->load('specialty');

            return response()->json([
                'success' => true,
                'message' => 'Doctor updated successfully',
                'doctor' => $doctor
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update doctor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified doctor
     */
    public function destroy($id)
    {
        try {
            $doctor = Doctor::findOrFail($id);

            // Delete associated image
            if ($doctor->image) {
                Storage::disk('public')->delete($doctor->image);
            }

            $doctor->delete();

            return response()->json([
                'success' => true,
                'message' => 'Doctor deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete doctor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show detailed view of a doctor
     */
    public function detail($id)
    {
        $doctor = Doctor::with('specialty')->findOrFail($id);
        return view('admin.pages.doctor_detail', compact('doctor'));
    }

    /**
     * Approve a doctor
     */
    public function approve($id)
    {
        try {
            $doctor = Doctor::findOrFail($id);
            $doctor->update(['approved' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Doctor approved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve doctor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle doctor status
     */
    public function toggleStatus($id)
    {
        try {
            $doctor = Doctor::findOrFail($id);
            $doctor->update([
                'status' => $doctor->status === 'active' ? 'inactive' : 'active'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Doctor status updated successfully',
                'status' => $doctor->status
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get doctors by specialty
     */
    public function getBySpecialty($specialtyId)
    {
        try {
            $doctors = Doctor::where('specialty_id', $specialtyId)
                           ->where('status', 'active')
                           ->where('approved', true)
                           ->with('specialty')
                           ->get();

            return response()->json([
                'success' => true,
                'doctors' => $doctors
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch doctors: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search doctors
     */
    public function search(Request $request)
    {
        $query = Doctor::with('specialty');

        // Search by name
        if ($request->has('name') && !empty($request->name)) {
            $query->where('doctor_name', 'like', '%' . $request->name . '%');
        }

        // Filter by specialty
        if ($request->has('specialty_id') && !empty($request->specialty_id)) {
            $query->where('specialty_id', $request->specialty_id);
        }

        // Filter by location
        if ($request->has('location') && !empty($request->location)) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Filter by approval status
        if ($request->has('approved') && $request->approved !== '') {
            $query->where('approved', $request->boolean('approved'));
        }

        // Filter by gender
        if ($request->has('gender') && !empty($request->gender)) {
            $query->where('gender', $request->gender);
        }

        $doctors = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'doctors' => $doctors
        ]);
    }

    /**
     * Get dashboard statistics
     */
    public function getStats()
    {
        $stats = [
            'total_doctors' => Doctor::count(),
            'active_doctors' => Doctor::where('status', 'active')->count(),
            'approved_doctors' => Doctor::where('approved', true)->count(),
            'pending_approval' => Doctor::where('approved', false)->count(),
            'male_doctors' => Doctor::where('gender', 'male')->count(),
            'female_doctors' => Doctor::where('gender', 'female')->count(),
            'doctors_by_specialty' => Doctor::join('specialties', 'doctors.specialty_id', '=', 'specialties.id')
                                          ->selectRaw('specialties.name, COUNT(*) as count')
                                          ->groupBy('specialties.name')
                                          ->get()
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }


    
}
