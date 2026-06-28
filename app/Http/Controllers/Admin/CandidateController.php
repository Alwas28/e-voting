<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Candidate;
use App\Models\ElectionPeriod;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    public function index()
    {
        $activePeriod = ElectionPeriod::active();
        $candidates   = $activePeriod
            ? Candidate::where('election_period_id', $activePeriod->id)
                       ->with('alumni')
                       ->orderBy('number')
                       ->get()
            : collect();

        return view('admin.candidates.index', compact('candidates', 'activePeriod'));
    }

    public function create()
    {
        $activePeriod = ElectionPeriod::active();
        if (!$activePeriod) {
            return redirect()->route('admin.candidates')
                ->with('error', 'Tidak ada periode pemilihan aktif.');
        }

        return view('admin.candidates.create', compact('activePeriod'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'election_period_id' => 'required|exists:election_periods,id',
            'alumni_id'          => 'required|exists:alumni,id',
            'number'             => 'required|integer|min:1|max:99',
            'photo'              => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'vision'             => 'nullable|string|max:2000',
            'mission'            => 'nullable|string|max:2000',
            'profile'            => 'nullable|string',
            'is_active'          => 'boolean',
        ]);

        $alumni = Alumni::findOrFail($data['alumni_id']);
        $data['name']       = $alumni->name;
        $data['faculty']    = $alumni->faculty;
        $data['department'] = $alumni->department;
        $data['is_active']  = $request->boolean('is_active', true);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('candidates', 'public');
        }

        Candidate::create($data);

        return redirect()->route('admin.candidates')
            ->with('success', "Kandidat {$alumni->name} berhasil ditambahkan.");
    }

    public function edit(Candidate $candidate)
    {
        $candidate->load('alumni');
        $activePeriod = ElectionPeriod::active();

        return view('admin.candidates.edit', compact('candidate', 'activePeriod'));
    }

    public function update(Request $request, Candidate $candidate)
    {
        $data = $request->validate([
            'alumni_id'  => 'required|exists:alumni,id',
            'number'     => 'required|integer|min:1|max:99',
            'photo'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'vision'     => 'nullable|string|max:2000',
            'mission'    => 'nullable|string|max:2000',
            'profile'    => 'nullable|string',
            'is_active'  => 'boolean',
        ]);

        $alumni = Alumni::findOrFail($data['alumni_id']);
        $data['name']       = $alumni->name;
        $data['faculty']    = $alumni->faculty;
        $data['department'] = $alumni->department;
        $data['is_active']  = $request->boolean('is_active');

        if ($request->hasFile('photo')) {
            $candidate->deletePhoto();
            $data['photo'] = $request->file('photo')->store('candidates', 'public');
        }

        $candidate->update($data);

        return redirect()->route('admin.candidates')
            ->with('success', "Data kandidat {$alumni->name} berhasil diperbarui.");
    }

    public function destroy(Candidate $candidate)
    {
        $name = $candidate->name;
        $candidate->deletePhoto();
        $candidate->delete();

        return back()->with('success', "Kandidat {$name} berhasil dihapus.");
    }

    public function searchAlumni(Request $request)
    {
        $q      = trim($request->query('q', ''));
        $except = (int) $request->query('except', 0);

        // Alumni yang sudah jadi kandidat di periode aktif tidak ditampilkan
        $activePeriod = ElectionPeriod::active();
        $usedAlumniIds = $activePeriod
            ? Candidate::where('election_period_id', $activePeriod->id)
                       ->whereNotNull('alumni_id')
                       ->when($except, fn($qb) => $qb->where('alumni_id', '!=', $except))
                       ->pluck('alumni_id')
            : collect();

        $alumni = Alumni::where('is_active', true)
            ->whereNotIn('id', $usedAlumniIds)
            ->when($q, fn($query) =>
                $query->where(function ($qb) use ($q) {
                    $qb->where('name', 'like', "%{$q}%")
                       ->orWhere('nim',  'like', "%{$q}%");
                })
            )
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'nim', 'name', 'faculty', 'department']);

        return response()->json($alumni);
    }
}
