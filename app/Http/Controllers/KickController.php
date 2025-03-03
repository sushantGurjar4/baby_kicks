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
        // Get only kicks belonging to the authenticated user, newest first
        $kicks = Kick::where('user_id', auth()->id())
                    ->where('is_active', true)
                    ->orderBy('kick_time', 'desc')
                    ->get();

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
            'user_id'     => auth()->id(), // or $request->user()->id
        ]);

        return redirect()->route('kicks.index')
                     ->with('success', 'Kick logged successfully!');
    }

    public function destroy($id)
    {
        $kick = Kick::findOrFail($id);

        // Optional: Ensure the kick belongs to the current user
        if ($kick->user_id !== auth()->id()) {
            return redirect()->route('kicks.index')
                ->with('error', 'Unauthorized action.');
        }

        // Instead of $kick->delete(), do:
        $kick->is_active = false;
        $kick->save();

        return redirect()->route('kicks.index')
            ->with('success', 'Kick entry marked inactive!');
    }

}
