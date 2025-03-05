<?php

namespace App\Http\Controllers;

use App\Models\Kick;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KickController extends Controller
{
    /**
     * Display today's kicks.
     */
    public function index()
    {
        // Get today's date (midnight) using Carbon
        $today = Carbon::today();
        
        // Fetch kicks for the current user, active only, for today
        $todayKicks = Kick::where('user_id', auth()->id())
            ->where('is_active', true)
            ->whereDate('kick_time', $today)
            ->orderBy('kick_time', 'desc')
            ->get();
        
        // Count the total kicks for today
        $countToday = $todayKicks->count();
        
        // Return the view with today's kicks and count
        return view('kicks.index', compact('todayKicks', 'countToday'));
    }

    /**
     * Display all kicks (lifetime).
     */
    public function all()
    {
        // Fetch all active kicks for the current user
        $allKicks = Kick::where('user_id', auth()->id())
            ->where('is_active', true)
            ->orderBy('kick_time', 'desc')
            ->get();
        
        // Count total lifetime kicks
        $countAll = $allKicks->count();
        
        // Return the view with all kicks and total count
        return view('kicks.all', compact('allKicks', 'countAll'));
    }

    /**
     * Display statistics for kicks.
     */
    public function stats()
    {
        // Retrieve all active kicks for the current user
        $kicks = Kick::where('user_id', auth()->id())
            ->where('is_active', true)
            ->get();

        // Group kicks by day (formatted as d/m)
        $grouped = $kicks->groupBy(function($item) {
            return \Carbon\Carbon::parse($item->kick_time)->format('d/m');
        });

        $labels = [];
        $data = [];
        foreach ($grouped as $date => $group) {
            $labels[] = $date;
            $data[] = $group->count();
        }

        // Calculate average kicks per day
        $totalDays = count($grouped);
        $totalKicks = $kicks->count();
        $average = $totalDays > 0 ? round($totalKicks / $totalDays, 2) : 0;

        return view('kicks.stats', compact('labels', 'data', 'average'));
    }

    /**
     * Store a newly created kick in storage.
     */
    public function store(Request $request)
    {
        // Validate input (description is optional)
        $request->validate([
            'description' => 'nullable|string|max:255',
        ]);

        // Create a new Kick with current time, description, and user id
        Kick::create([
            'kick_time'   => now(),
            'description' => $request->description,
            'user_id'     => auth()->id(),
        ]);

        return redirect()->route('kicks.index')
            ->with('success', 'Kick logged successfully!');
    }

    /**
     * Mark a kick as inactive (soft-delete).
     */
    public function destroy($id)
    {
        $kick = Kick::findOrFail($id);

        // Ensure the kick belongs to the current user
        if ($kick->user_id !== auth()->id()) {
            return redirect()->route('kicks.index')
                ->with('error', 'Unauthorized action.');
        }

        // Mark the kick as inactive instead of deleting it
        $kick->is_active = false;
        $kick->save();

        return redirect()->route('kicks.index')
            ->with('success', 'Kick entry marked inactive!');
    }
}
