<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user()->load(['alumni.voter', 'roles']);
        return view('profile.edit', compact('user'));
    }

    public function password(Request $request): View
    {
        return view('profile.password');
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function updateAlumniData(Request $request): RedirectResponse
    {
        $user   = $request->user();
        $alumni = $user->alumni;

        if (!$alumni) {
            return Redirect::route('profile.edit')->with('error', 'Data alumni tidak ditemukan.');
        }

        $data = $request->validate([
            'place_of_birth' => 'nullable|string|max:100',
            'date_of_birth'  => 'nullable|date|before:today',
            'phone'          => 'nullable|string|max:20',
            'address'        => 'nullable|string|max:255',
        ]);

        // Sinkronkan email alumni dengan email akun
        $data['email'] = $user->email;

        $alumni->update($data);

        return Redirect::route('profile.edit')->with('status', 'alumni-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
