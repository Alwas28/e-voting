<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Formateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FormateurController extends Controller
{
    public function index()
    {
        $formateurs = Formateur::with('alumni')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $alumni = Alumni::orderBy('name')->get(['id', 'nim', 'name', 'faculty', 'department']);

        return view('admin.formateurs.index', compact('formateurs', 'alumni'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'alumni_id'  => 'required|exists:alumni,id',
            'jabatan'    => 'required|string|max:100',
            'deskripsi'  => 'nullable|string|max:1000',
            'photo'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('formateurs', 'public');
        }

        $data['sort_order'] = $data['sort_order'] ?? 0;

        Formateur::create($data);

        return back()->with('success', 'Anggota Tim Formatur berhasil ditambahkan.');
    }

    public function update(Request $request, Formateur $formateur)
    {
        $data = $request->validate([
            'alumni_id'  => 'required|exists:alumni,id',
            'jabatan'    => 'required|string|max:100',
            'deskripsi'  => 'nullable|string|max:1000',
            'photo'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('photo')) {
            $formateur->deletePhoto();
            $data['photo'] = $request->file('photo')->store('formateurs', 'public');
        } else {
            unset($data['photo']);
        }

        $data['sort_order'] = $data['sort_order'] ?? $formateur->sort_order;

        $formateur->update($data);

        return back()->with('success', 'Data Tim Formatur berhasil diperbarui.');
    }

    public function destroy(Formateur $formateur)
    {
        $formateur->deletePhoto();
        $formateur->delete();

        return back()->with('success', 'Anggota Tim Formatur berhasil dihapus.');
    }

    public function toggleStatus(Formateur $formateur)
    {
        $formateur->update(['is_active' => !$formateur->is_active]);
        return back()->with('success', 'Status berhasil diperbarui.');
    }
}
