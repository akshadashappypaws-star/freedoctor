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
            'mobile' => 'required|string|max:20',
            'location' => 'required|string|max:100',
            'category' => 'required|string|max:50',
        ]);
        OrganicLead::create($validated);
        return back()->with('success', 'Thank you! We will notify you when a camp is available.');
    }
}
