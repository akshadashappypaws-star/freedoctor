<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrganicLead;

class OrganicLeadController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20|unique:organic_leads,phone',
            'email' => 'nullable|email|max:100',
            'message' => 'nullable|string|max:500',
            'source' => 'nullable|string|max:50',
            'lead_type' => 'nullable|string|max:50',
        ]);

        // Set default values if not provided
        $validated['source'] = $validated['source'] ?? 'website';
        $validated['lead_type'] = $validated['lead_type'] ?? 'inquiry';
        $validated['status'] = 'new';

        OrganicLead::create($validated);
        
        return back()->with('success', 'Thank you! We will notify you when a camp is available.');
    }

    public function index()
    {
        $leads = OrganicLead::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.organic-leads.index', compact('leads'));
    }

    public function show(OrganicLead $organicLead)
    {
        return view('admin.organic-leads.show', compact('organicLead'));
    }

    public function update(Request $request, OrganicLead $organicLead)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20|unique:organic_leads,phone,' . $organicLead->id,
            'email' => 'nullable|email|max:100',
            'message' => 'nullable|string|max:500',
            'status' => 'required|in:new,contacted,converted,closed',
        ]);

        $organicLead->update($validated);
        
        return back()->with('success', 'Lead updated successfully.');
    }
}
