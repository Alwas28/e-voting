<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ElectionPeriod;
use App\Models\ElectionSchedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $periods       = ElectionPeriod::orderByDesc('year')->orderByDesc('id')->get();
        $activePeriod  = ElectionPeriod::active();

        $dpt      = $activePeriod ? ElectionSchedule::forPeriodAndType($activePeriod, 'dpt_registration') : null;
        $election = $activePeriod ? ElectionSchedule::forPeriodAndType($activePeriod, 'election')         : null;

        return view('admin.schedule.index', compact('periods', 'activePeriod', 'dpt', 'election'));
    }

    public function storePeriod(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:150',
            'year'        => 'required|integer|min:2000|max:2100',
            'description' => 'nullable|string|max:500',
        ]);

        $period = ElectionPeriod::create($data);
        $period->activate();

        return back()->with('success', 'Periode pemilihan "' . $period->name . '" berhasil dibuat dan diaktifkan.');
    }

    public function updatePeriod(Request $request, ElectionPeriod $period)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:150',
            'year'        => 'required|integer|min:2000|max:2100',
            'description' => 'nullable|string|max:500',
        ]);

        $period->update($data);

        return back()->with('success', 'Periode pemilihan berhasil diperbarui.');
    }

    public function activatePeriod(ElectionPeriod $period)
    {
        $period->activate();
        return back()->with('success', 'Periode "' . $period->name . '" diaktifkan.');
    }

    public function destroyPeriod(ElectionPeriod $period)
    {
        if ($period->is_active) {
            return back()->with('error', 'Tidak dapat menghapus periode yang sedang aktif.');
        }

        $period->delete();
        return back()->with('success', 'Periode pemilihan berhasil dihapus.');
    }

    public function updateSchedule(Request $request, ElectionSchedule $schedule)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after:start_date',
            'description' => 'nullable|string|max:500',
        ]);

        $schedule->update($data);

        return back()->with('success', 'Jadwal ' . $schedule->name . ' berhasil disimpan.');
    }
}
