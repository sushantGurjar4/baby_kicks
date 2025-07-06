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
        $user = auth()->user();
        $today = Carbon::today();

        // ——— Pregnancy Calculations ———
        $lmpDate = $user->lmp_date;  // null or Carbon
        $dueDate = $currentWeeks = $currentDays = $remainingWeeks = $remainingDays = null;

        if ($lmpDate) {
            $lmp    = $lmpDate->copy()->startOfDay();
            $dueDate = $lmp->copy()->addDays(280);         // Naegele’s rule
            $diff    = $lmp->diffInDays($today);           // days since LMP

            $currentWeeks = intdiv($diff, 7);
            $currentDays  = $diff % 7;

            $remainingTotal = $today->diffInDays($dueDate);
            $remainingWeeks = intdiv($remainingTotal, 7);
            $remainingDays  = $remainingTotal % 7;
        }

        // ——— Today’s Kicks ———
        $todayKicks = Kick::where('user_id', $user->id)
            ->where('is_active', true)
            ->whereDate('kick_time', $today)
            ->orderBy('kick_time', 'desc')
            ->get();

        $countToday = $todayKicks->count();

        return view('kicks.index', compact(
            'todayKicks',
            'countToday',
            'lmpDate',
            'dueDate',
            'currentWeeks',
            'currentDays',
            'remainingWeeks',
            'remainingDays'
        ));
    }

    /**
     * Store the last period date (LMP).
     */
    public function recordLmp(Request $request)
    {
        $request->validate([
            'lmp_date' => 'required|date|before:today',
        ]);

        $user = auth()->user();
        $user->lmp_date = Carbon::parse($request->lmp_date)->toDateString();
        $user->save();

        return redirect()->route('kicks.index')
                         ->with('success', 'Last period date recorded!');
    }

    /**
     * Display all kicks (lifetime).
     */
    public function all(Request $request)
    {
        // Build base query
        $query = Kick::where('user_id', auth()->id())
                     ->where('is_active', true);

        // If user supplied a start_date, filter kicks on or after it
        if ($request->filled('start_date')) {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $query->where('kick_time', '>=', $start);
        }

        // If user supplied an end_date, filter kicks on or before it
        if ($request->filled('end_date')) {
            $end = Carbon::parse($request->end_date)->endOfDay();
            $query->where('kick_time', '<=', $end);
        }

        // Execute, newest first
        $allKicks = $query->orderBy('kick_time', 'desc')->get();
        $countAll = $allKicks->count();

        // Pass the selected dates back to the view so the inputs stay filled
        return view('kicks.all', [
            'allKicks'   => $allKicks,
            'countAll'   => $countAll,
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
        ]);
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
