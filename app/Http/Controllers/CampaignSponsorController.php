<?php

namespace App\Http\Controllers;

use App\Models\CampaignSponsor;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\SponsorThankYou;

class CampaignSponsorController extends Controller
{
    /**
     * Display a listing of sponsors.
     */
    public function index(Request $request)
    {
        $query = CampaignSponsor::with(['campaign', 'campaign.doctor']);
        
        // Apply filters
        if ($request->filled('campaign_id')) {
            $query->where('campaign_id', $request->campaign_id);
        }
        
        if ($request->filled('location')) {
            $query->whereHas('campaign', function($q) use ($request) {
                $q->where('location', 'like', '%' . $request->location . '%');
            });
        }
        
        if ($request->filled('min_amount')) {
            $query->where('amount', '>=', $request->min_amount);
        }
        
        if ($request->filled('max_amount')) {
            $query->where('amount', '<=', $request->max_amount);
        }
        
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        
        $sponsors = $query->latest()->get();
        
        // Get unique locations and campaigns for filters
        $locations = Campaign::distinct()->pluck('location')->filter()->sort()->values();
        $campaigns = Campaign::select('id', 'title')->orderBy('title')->get();
        
        // If this is an AJAX request, return JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $sponsors
            ]);
        }
        
        return view('admin.pages.campaign-sponsors', compact('sponsors', 'locations', 'campaigns'));
    }

    /**
     * Show the form for creating a sponsor.
     */
    public function create($campaignId = null)
    {
        $campaign = null;
        if ($campaignId) {
            $campaign = Campaign::with('doctor')->findOrFail($campaignId);
        }
        
        return view('sponsor.form', compact('campaign'));
    }

    /**
     * Store a newly created sponsor.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string',
            'message' => 'nullable|string',
            'amount' => 'required|numeric|min:1',
            'campaign_id' => 'required|exists:campaigns,id',
        ]);

        $sponsor = CampaignSponsor::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Sponsor registration successful!',
            'data' => $sponsor,
            'redirect_url' => route('sponsor.payment', $sponsor->id)
        ]);
    }

    /**
     * Display the specified sponsor.
     */
    public function show($id)
    {
        $sponsor = CampaignSponsor::with(['campaign', 'campaign.doctor'])->findOrFail($id);
        return response()->json($sponsor);
    }

    /**
     * Update the specified sponsor.
     */
    public function update(Request $request, $id)
    {
        $sponsor = CampaignSponsor::findOrFail($id);
        
        $validated = $request->validate([
            'payment_status' => 'required|in:pending,success,failed'
        ]);

        $sponsor->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Sponsor updated successfully!'
        ]);
    }

    /**
     * Remove the specified sponsor.
     */
    public function destroy($id)
    {
        $sponsor = CampaignSponsor::findOrFail($id);
        $sponsor->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sponsor deleted successfully!'
        ]);
    }

    /**
     * Show payment page.
     */
    public function payment($id)
    {
        $sponsor = CampaignSponsor::with(['campaign', 'campaign.doctor'])->findOrFail($id);
        return view('sponsor.payment', compact('sponsor'));
    }

    /**
     * Handle payment success.
     */
    public function paymentSuccess(Request $request, $id)
    {
        $sponsor = CampaignSponsor::with(['campaign', 'campaign.doctor'])->findOrFail($id);
        
        // Update payment details
        $sponsor->update([
            'payment_status' => 'success',
            'payment_id' => $request->payment_id,
            'payment_details' => $request->all(),
            'payment_date' => now()
        ]);

        // Send thank you email
        try {
            Mail::to($sponsor->email ?? 'info@freedoctor.com')->send(new SponsorThankYou($sponsor));
        } catch (\Exception $e) {
            Log::error('Failed to send sponsor thank you email: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment successful! Thank you for your sponsorship.',
            'redirect_url' => route('sponsor.thank-you', $sponsor->id)
        ]);
    }

    /**
     * Show thank you page.
     */
    public function thankYou($id)
    {
        $sponsor = CampaignSponsor::with(['campaign', 'campaign.doctor'])->findOrFail($id);
        return view('sponsor.thank-you', compact('sponsor'));
    }

    /**
     * Export sponsors data.
     */
    public function export(Request $request)
    {
        $query = CampaignSponsor::with(['campaign', 'campaign.doctor']);
        
        // Apply same filters as index
        if ($request->filled('campaign_id')) {
            $query->where('campaign_id', $request->campaign_id);
        }
        
        if ($request->filled('location')) {
            $query->whereHas('campaign', function($q) use ($request) {
                $q->where('location', 'like', '%' . $request->location . '%');
            });
        }
        
        if ($request->filled('min_amount')) {
            $query->where('amount', '>=', $request->min_amount);
        }
        
        if ($request->filled('max_amount')) {
            $query->where('amount', '<=', $request->max_amount);
        }
        
        $sponsors = $query->latest()->get();
        
        $filename = 'campaign_sponsors_' . date('Y_m_d_H_i_s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($sponsors) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'ID', 'Name', 'Phone', 'Address', 'Amount', 'Campaign', 'Location', 
                'Doctor', 'Payment Status', 'Payment Date', 'Registration Date'
            ]);
            
            foreach ($sponsors as $sponsor) {
                fputcsv($file, [
                    $sponsor->id,
                    $sponsor->name,
                    $sponsor->phone_number,
                    $sponsor->address,
                    $sponsor->amount,
                    $sponsor->campaign->title ?? 'N/A',
                    $sponsor->campaign->location ?? 'N/A',
                    $sponsor->campaign->doctor->doctor_name ?? 'N/A',
                    $sponsor->payment_status_label,
                    $sponsor->payment_date ? $sponsor->payment_date->format('Y-m-d H:i:s') : 'N/A',
                    $sponsor->created_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
