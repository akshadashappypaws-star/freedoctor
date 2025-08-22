<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Specialty;
class SpecialtyController extends Controller
{
    public function index()
{
    $specialties = Specialty::all();
    return view('admin.specialties.index', compact('specialties'));
}

public function store(Request $request)
{
    $request->validate(['name' => 'required|unique:specialties']);
    Specialty::create($request->only('name'));
    return back()->with('success', 'Specialty added.');
}

}
