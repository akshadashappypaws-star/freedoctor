<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\DoctorProposal;
use App\Models\BusinessOrganizationRequest;
use App\Models\AdminMessage;
use App\Events\AdminMessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProposalController extends Controller
{
    public function index()
    {
        $doctor = Auth::guard('doctor')->user();
        
        // Get doctor's proposals
        $doctorProposals = DoctorProposal::where('doctor_id', $doctor->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get business organization requests for doctor's specialty
        $businessOrgRequests = BusinessOrganizationRequest::where('specialty_id', $doctor->specialty_id)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get statistics
        $totalRequests = $businessOrgRequests->count();
        $totalProposals = $doctorProposals->count();
        
        return view('doctor.pages.business-reach-out', compact(
            'doctor',
            'doctorProposals',
            'businessOrgRequests',
            'totalRequests',
            'totalProposals'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'business_organization_request_id' => 'nullable|exists:business_organization_requests,id',
            'message' => 'required|string|min:50|max:2000',
        ], [
            'business_organization_request_id.exists' => 'Selected business request does not exist.',
            'message.required' => 'Please provide your proposal message.',
            'message.min' => 'Your proposal message must be at least 50 characters long.',
            'message.max' => 'Your proposal message cannot exceed 2000 characters.',
        ]);

        $doctor = Auth::guard('doctor')->user();

        // Check if doctor has submitted a proposal in the last 24 hours
        $recentProposal = DoctorProposal::where('doctor_id', $doctor->id)
            ->where('created_at', '>=', now()->subDay())
            ->first();

        if ($recentProposal) {
            return redirect()->back()->withErrors([
                'message' => 'You can only submit one proposal per day. Please wait before submitting another proposal.'
            ]);
        }

        // If business request is selected, check if doctor already applied for it
        if ($request->business_organization_request_id) {
            $existingProposal = DoctorProposal::where('doctor_id', $doctor->id)
                ->where('business_organization_request_id', $request->business_organization_request_id)
                ->first();

            if ($existingProposal) {
                return redirect()->back()->withErrors([
                    'business_organization_request_id' => 'You have already submitted a proposal for this business request.'
                ]);
            }
        }

        $proposal = DoctorProposal::create([
            'doctor_id' => $doctor->id,
            'business_organization_request_id' => $request->business_organization_request_id,
            'message' => $request->message,
        ]);

        // Send notification to admin about new proposal submission
        $businessRequestInfo = '';
        if ($request->business_organization_request_id) {
            $businessRequest = BusinessOrganizationRequest::find($request->business_organization_request_id);
            if ($businessRequest) {
                $businessRequestInfo = " for business request '{$businessRequest->organization_name}'";
            }
        }

        $adminMessage = AdminMessage::create([
            'admin_id' => null, // System message to all admins
            'type' => 'proposal',
            'message' => "New business proposal submitted by Dr. {$doctor->doctor_name}{$businessRequestInfo}. Please review and take action.",
            'data' => [
                'doctor_id' => $doctor->id,
                'proposal_id' => $proposal->id,
                'doctor_name' => $doctor->doctor_name,
                'doctor_email' => $doctor->email,
                'business_request_id' => $request->business_organization_request_id
            ],
            'read' => false
        ]);

        // Broadcast to admin
        event(new AdminMessageSent($adminMessage));

        return redirect()->back()->with('success', 'Your proposal has been submitted successfully! The admin will review it and get back to you.');
    }

    public function show(DoctorProposal $proposal)
    {
        $doctor = Auth::guard('doctor')->user();
        
        // Ensure doctor can only view their own proposals
        if ($proposal->doctor_id !== $doctor->id) {
            abort(403, 'You are not authorized to view this proposal.');
        }

        return response()->json([
            'proposal' => $proposal->load('doctor'),
        ]);
    }
}
