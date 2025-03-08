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
        $user = auth()->user();

        // If there's no birth date, default to "today"
        $birthDate = $user->birth_date ? \Carbon\Carbon::parse($user->birth_date) : \Carbon\Carbon::today();

        // Retrieve all active kicks
        $kicks = Kick::where('user_id', auth()->id())
            ->where('is_active', true)
            ->get();

        if ($kicks->count() === 0) {
            return view('kicks.stats', [
                'labels' => [],
                'data' => [],
                'average' => 0,
            ]);
        }

        // earliest date among kicks
        $earliestDate = $kicks->min('kick_time');
        $start = \Carbon\Carbon::parse($earliestDate)->startOfDay();

        // end is the min between the birth date (startOfDay) and today
        // but if we want to ensure we don't go beyond birth date:
        $birthStart = $birthDate->copy()->startOfDay();
        $end = \Carbon\Carbon::today()->startOfDay();
        // whichever is earlier
        $end = $end->gt($birthStart) ? $birthStart : $end;

        // group by day
        $grouped = $kicks->groupBy(function($item) {
            return \Carbon\Carbon::parse($item->kick_time)->format('d/m');
        });

        $labels = [];
        $data = [];

        $current = $start->copy();
        while ($current->lte($end)) {
            $label = $current->format('d/m');
            $count = isset($grouped[$label]) ? $grouped[$label]->count() : 0;

            $labels[] = $label;
            $data[]   = $count;

            $current->addDay();
        }

        $totalDays = count($data);
        $totalKicks = array_sum($data);
        $average = $totalDays > 0 ? round($totalKicks / $totalDays, 2) : 0;

        return view('kicks.stats', compact('labels', 'data', 'average'));
    }

    //Record Birth Date
    public function recordBirth(Request $request)
    {
        $request->validate([
            'birth_date' => 'required|date',
        ]);

        $user = auth()->user();
        // Save the selected birth date
        $user->birth_date = Carbon::parse($request->birth_date)->toDateString();
        $user->save();

        return redirect()->route('kicks.index')
            ->with('success', 'Baby birth date recorded!');
    }

    /**
     * Store a newly created kick in storage.
     */
    public function store(Request $request)
    {
        // Check if baby's birth date is set
        $user = auth()->user();
        if (!is_null($user->birth_date)) {
            return redirect()->route('kicks.index')
                ->with('success', 'Congratulations on your baby being born! Kick recording is now disabled.');
        }

        $request->validate([
            'description' => 'nullable|string|max:255',
        ]);
        
        Kick::create([
            'kick_time'   => now(),
            'description' => $request->description,
            'user_id'     => $user->id,
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
