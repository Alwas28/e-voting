<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Candidate;
use Illuminate\Http\Request;

class AlumniController extends Controller
{
    public function index(Request $request)
    {
        $query = Alumni::query();

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($qb) use ($q) {
                $qb->where('name', 'like', "%$q%")
                   ->orWhere('nim', 'like', "%$q%")
                   ->orWhere('email', 'like', "%$q%");
            });
        }

        if ($request->filled('faculty')) {
            $query->where('faculty', $request->faculty);
        }

        if ($request->filled('year')) {
            $query->where('graduation_year', $request->year);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $alumni    = $query->orderBy('name')->paginate(15)->withQueryString();
        $faculties = Alumni::distinct()->orderBy('faculty')->pluck('faculty');
        $years     = Alumni::distinct()->orderByDesc('graduation_year')->pluck('graduation_year');

        $stats = [
            'total'    => Alumni::count(),
            'active'   => Alumni::where('is_active', true)->count(),
            'inactive' => Alumni::where('is_active', false)->count(),
            'faculties' => Alumni::distinct('faculty')->count('faculty'),
        ];

        return view('admin.alumni.index', compact('alumni', 'faculties', 'years', 'stats'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nim'             => 'required|string|max:20|unique:alumni,nim',
            'name'            => 'required|string|max:100',
            'faculty'         => 'required|string|max:100',
            'department'      => 'required|string|max:100',
            'place_of_birth'  => 'nullable|string|max:100',
            'date_of_birth'   => 'nullable|date|before:today',
            'graduation_year' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'email'           => 'nullable|email|max:100',
            'phone'           => 'nullable|string|max:20',
            'address'         => 'nullable|string|max:255',
        ]);

        Alumni::create($data);

        return back()->with('success', 'Data alumni berhasil ditambahkan.');
    }

    public function update(Request $request, Alumni $alumnus)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:100',
            'faculty'         => 'required|string|max:100',
            'department'      => 'required|string|max:100',
            'place_of_birth'  => 'nullable|string|max:100',
            'date_of_birth'   => 'nullable|date|before:today',
            'graduation_year' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'email'           => 'nullable|email|max:100',
            'phone'           => 'nullable|string|max:20',
            'address'         => 'nullable|string|max:255',
            'is_active'       => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $alumnus->update($data);

        // Sync nama/fakultas/prodi ke kandidat yang terhubung
        Candidate::where('alumni_id', $alumnus->id)->update([
            'name'       => $data['name'],
            'faculty'    => $data['faculty'],
            'department' => $data['department'],
        ]);

        return back()->with('success', 'Data alumni berhasil diperbarui.');
    }

    public function destroy(Alumni $alumnus)
    {
        $alumnus->delete();
        return back()->with('success', 'Data alumni berhasil dihapus.');
    }

    public function toggleStatus(Alumni $alumnus)
    {
        $alumnus->update(['is_active' => !$alumnus->is_active]);
        $status = $alumnus->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Alumni \"{$alumnus->name}\" berhasil {$status}.");
    }
}
