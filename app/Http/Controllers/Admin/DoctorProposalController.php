<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DoctorProposal;
use App\Models\BusinessOrganizationRequest;
use App\Models\UserMessage;
use App\Models\DoctorMessage;
use App\Events\MessageReceived;
use App\Events\DoctorMessageSent;
use App\Events\UserMessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorProposalController extends Controller
{
    public function index()
    {
        $proposals = DoctorProposal::with(['doctor', 'doctor.specialty', 'approvedBy', 'businessOrganizationRequest'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.pages.doctor-proposals', compact('proposals'));
    }

    public function show(DoctorProposal $proposal)
    {
        $proposal->load(['doctor', 'doctor.specialty', 'approvedBy', 'businessOrganizationRequest']);
        
        return response()->json([
            'proposal' => $proposal,
        ]);
    }

    public function approve(Request $request, DoctorProposal $proposal)
    {
        $request->validate([
            'admin_remarks' => 'nullable|string|max:1000',
        ]);

        if ($proposal->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This proposal has already been reviewed.',
            ]);
        }

        $proposal->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::guard('admin')->id(),
            'admin_remarks' => $request->admin_remarks,
        ]);

        // When a proposal is approved, we can create a business organization request
        // that associates the doctor with business opportunities
        $this->createBusinessOrganizationRequest($proposal);

        // Send notification to doctor
        $doctorMessage = DoctorMessage::create([
            'doctor_id' => $proposal->doctor_id,
            'type' => 'approval',
            'message' => "Congratulations! Your business proposal has been approved by the admin. You can now proceed with your business activities." . ($request->admin_remarks ? " Admin remarks: " . $request->admin_remarks : ""),
            'is_read' => false
        ]);

        // Send notification to user (if there's a related user)
        if ($proposal->business_organization_request_id) {
            $businessRequest = BusinessOrganizationRequest::find($proposal->business_organization_request_id);
            if ($businessRequest && $businessRequest->user_id) {
                $userMessage = UserMessage::create([
                    'user_id' => $businessRequest->user_id,
                    'type' => 'approval',
                    'message' => "Great news! The business proposal from Dr. {$proposal->doctor->doctor_name} has been approved and is now available for collaboration.",
                    'is_read' => false
                ]);
                
                // Broadcast real-time notification to user
                event(new UserMessageSent($userMessage));
            }
        }

        // Broadcast to doctor
        event(new DoctorMessageSent($doctorMessage));

        return response()->json([
            'success' => true,
            'message' => 'Proposal approved successfully!',
        ]);
    }

    public function reject(Request $request, DoctorProposal $proposal)
    {
        $request->validate([
            'admin_remarks' => 'required|string|max:1000',
        ], [
            'admin_remarks.required' => 'Please provide a reason for rejection.',
        ]);

        if ($proposal->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This proposal has already been reviewed.',
            ]);
        }

        $proposal->update([
            'status' => 'rejected',
            'approved_by' => Auth::guard('admin')->id(),
            'admin_remarks' => $request->admin_remarks,
        ]);

        // Send notification to doctor
        $doctorMessage = DoctorMessage::create([
            'doctor_id' => $proposal->doctor_id,
            'type' => 'rejection',
            'message' => "Unfortunately, your business proposal has been rejected by the admin. Reason: " . $request->admin_remarks,
            'is_read' => false
        ]);

        // Send notification to user (if there's a related user)
        if ($proposal->business_organization_request_id) {
            $businessRequest = BusinessOrganizationRequest::find($proposal->business_organization_request_id);
            if ($businessRequest && $businessRequest->user_id) {
                $userMessage = UserMessage::create([
                    'user_id' => $businessRequest->user_id,
                    'type' => 'rejection',
                    'message' => "The business proposal from Dr. {$proposal->doctor->doctor_name} has been declined. We are looking for alternative solutions for your request.",
                    'is_read' => false
                ]);
                
                // Broadcast real-time notification to user
                event(new UserMessageSent($userMessage));
            }
        }

        // Broadcast to doctor
        event(new DoctorMessageSent($doctorMessage));

        return response()->json([
            'success' => true,
            'message' => 'Proposal rejected successfully.',
        ]);
    }

    private function createBusinessOrganizationRequest(DoctorProposal $proposal)
    {
        // If the proposal was for a specific business request, update that request
        if ($proposal->business_organization_request_id) {
            $businessRequest = BusinessOrganizationRequest::find($proposal->business_organization_request_id);
            if ($businessRequest) {
                $businessRequest->update([
                    'hired_doctor_id' => $proposal->doctor_id,
                    'status' => 'completed', // Mark as completed since doctor is approved
                ]);
                return;
            }
        }
        
        // Create a new business organization request for general proposals
        BusinessOrganizationRequest::create([
            'user_id' => null, // Admin created
            'organization_name' => 'Admin Approved Business Partnership',
            'email' => 'admin@freedoctor.com',
            'phone_number' => '0000000000',
            'camp_request_type' => 'medical', // Use valid enum value
            'specialty_id' => $proposal->doctor->specialty_id,
            'date_from' => now()->addDays(30),
            'date_to' => now()->addDays(37),
            'number_of_people' => 100,
            'location' => 'Various Locations',
            'description' => 'Approved business partnership proposal for Dr. ' . $proposal->doctor->doctor_name,
            'hired_doctor_id' => $proposal->doctor_id,
            'status' => 'completed', // Mark as completed since doctor is already approved
        ]);
    }

    public function export()
    {
        // Export functionality - can be implemented later
        $proposals = DoctorProposal::with(['doctor', 'doctor.specialty', 'approvedBy'])->get();
        
        // For now, return a simple CSV format
        $csvData = "ID,Doctor Name,Doctor Email,Specialty,Message,Status,Submitted Date,Admin Remarks\n";
        
        foreach ($proposals as $proposal) {
            $csvData .= sprintf(
                "%d,%s,%s,%s,%s,%s,%s,%s\n",
                $proposal->id,
                $proposal->doctor->doctor_name,
                $proposal->doctor->email,
                $proposal->doctor->specialty->name ?? 'N/A',
                str_replace(['"', "\n", "\r"], ['""', ' ', ' '], $proposal->message),
                $proposal->status,
                $proposal->created_at->format('Y-m-d H:i:s'),
                str_replace(['"', "\n", "\r"], ['""', ' ', ' '], $proposal->admin_remarks ?? '')
            );
        }
        
        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="doctor-proposals.csv"');
    }
}
