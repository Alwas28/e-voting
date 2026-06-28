<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AlumniRegisterController extends Controller
{
    /** Langkah 1 — form verifikasi NIM & tanggal lahir */
    public function showStep1()
    {
        return view('auth.alumni.step1');
    }

    /** Langkah 1 — proses verifikasi */
    public function verifyStep1(Request $request)
    {
        $request->validate([
            'nim'           => 'required|string',
            'date_of_birth' => 'required|date',
        ], [
            'nim.required'           => 'NIM wajib diisi.',
            'date_of_birth.required' => 'Tanggal lahir wajib diisi.',
            'date_of_birth.date'     => 'Format tanggal tidak valid.',
        ]);

        $alumni = Alumni::where('nim', $request->nim)
                        ->whereDate('date_of_birth', $request->date_of_birth)
                        ->where('is_active', true)
                        ->first();

        if (!$alumni) {
            return back()
                ->withInput()
                ->withErrors(['nim' => 'Data tidak ditemukan. Pastikan NIM dan tanggal lahir sesuai data yang terdaftar.']);
        }

        // Cek apakah sudah punya akun
        if (User::where('alumni_id', $alumni->id)->exists()) {
            return back()
                ->withInput()
                ->withErrors(['nim' => 'Alumni ini sudah memiliki akun. Silakan login.']);
        }

        // Simpan ke session untuk langkah 2
        session(['alumni_register_id' => $alumni->id]);

        return redirect()->route('alumni.register.step2');
    }

    /** Langkah 2 — form pembuatan akun */
    public function showStep2()
    {
        $alumniId = session('alumni_register_id');
        if (!$alumniId) {
            return redirect()->route('alumni.register.step1')
                ->with('error', 'Sesi habis. Silakan ulangi verifikasi.');
        }

        $alumni = Alumni::findOrFail($alumniId);
        return view('auth.alumni.step2', compact('alumni'));
    }

    /** Langkah 2 — proses pembuatan akun */
    public function register(Request $request)
    {
        $alumniId = session('alumni_register_id');
        if (!$alumniId) {
            return redirect()->route('alumni.register.step1')
                ->with('error', 'Sesi habis. Silakan ulangi verifikasi.');
        }

        $alumni = Alumni::findOrFail($alumniId);

        $request->validate([
            'name'                  => 'required|string|max:100',
            'email'                 => 'required|email|unique:users,email',
            'password'              => ['required', 'confirmed', Password::min(8)],
        ], [
            'name.required'         => 'Username wajib diisi.',
            'email.required'        => 'Email wajib diisi.',
            'email.unique'          => 'Email sudah digunakan.',
            'password.required'     => 'Password wajib diisi.',
            'password.confirmed'    => 'Konfirmasi password tidak cocok.',
            'password.min'          => 'Password minimal 8 karakter.',
        ]);

        $user = User::create([
            'alumni_id' => $alumni->id,
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'is_active' => true,
        ]);

        // Assign role alumni — buat jika belum ada
        $role = Role::firstOrCreate(
            ['name' => 'alumni'],
            [
                'label'       => 'Alumni',
                'description' => 'Akun alumni — dapat melihat hasil pemilihan.',
                'is_system'   => true,
            ]
        );
        $user->roles()->syncWithoutDetaching([$role->id]);

        // Hapus session verifikasi
        session()->forget('alumni_register_id');

        // Login otomatis
        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', "Selamat datang, {$user->name}! Akun Anda berhasil dibuat.");
    }
}
