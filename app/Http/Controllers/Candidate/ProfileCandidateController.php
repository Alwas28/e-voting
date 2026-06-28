<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileCandidateController extends Controller
{
    private function getCandidate(): ?Candidate
    {
        $user = Auth::user();

        if (!$user->alumni_id) {
            return null;
        }

        return Candidate::where('alumni_id', $user->alumni_id)->first();
    }

    public function edit()
    {
        $candidate = $this->getCandidate();

        if (!$candidate) {
            return view('candidate.profile.not-found');
        }

        return view('candidate.profile.edit', compact('candidate'));
    }

    public function update(Request $request)
    {
        $candidate = $this->getCandidate();

        if (!$candidate) {
            abort(403);
        }

        $data = $request->validate([
            'photo'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'vision'  => 'nullable|string|max:2000',
            'mission' => 'nullable|string|max:2000',
            'profile' => 'nullable|string',
        ]);

        if ($request->hasFile('photo')) {
            $candidate->deletePhoto();
            $data['photo'] = $request->file('photo')->store('candidates', 'public');
        } else {
            unset($data['photo']);
        }

        $candidate->update($data);

        return back()->with('success', 'Profil Anda berhasil diperbarui.');
    }
}
