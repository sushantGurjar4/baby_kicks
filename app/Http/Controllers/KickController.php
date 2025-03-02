<?php

namespace App\Http\Controllers;

use App\Models\Kick;
use Illuminate\Http\Request;

class KickController extends Controller
{
    /**
     * Display a listing of the kicks.
     */
    public function index()
    {
        // Get all kicks, newest first
        $kicks = Kick::orderBy('kick_time', 'desc')->get();
        return view('kicks.index', compact('kicks'));
    }

    /**
     * Store a newly created kick in storage.
     */
    public function store(Request $request)
    {
        // Validate input if needed (e.g., 'description' => 'nullable|string|max:255')
        // For a quick example:
        $request->validate([
            'description' => 'nullable|string|max:255',
        ]);

        // Create a new Kick
        Kick::create([
            'kick_time'   => now(),
            'description' => $request->description,
        ]);

        return redirect()->route('kicks.index')
                     ->with('success', 'Kick logged successfully!');
    }
}
