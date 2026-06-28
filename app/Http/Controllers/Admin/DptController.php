<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\ElectionPeriod;
use App\Models\ElectionSchedule;
use App\Models\Voter;
use Illuminate\Http\Request;

class DptController extends Controller
{
    /**
     * Halaman utama DPT:
     * - Alumni  → langsung ke halaman rekam wajah diri sendiri
     * - Admin   → tampilkan daftar alumni untuk didaftarkan
     */
    public function showRegister()
    {
        $user = auth()->user();

        // Alumni: daftar diri sendiri
        if ($user->hasRole('alumni') && $user->alumni) {
            $alumni = $user->alumni;

            if (!$alumni->is_active) {
                return view('admin.dpt.blocked', [
                    'message' => 'Akun alumni Anda tidak aktif. Hubungi administrator.',
                ]);
            }

            // Cek jadwal Pendaftaran DPT
            $period      = ElectionPeriod::active();
            $dptSchedule = $period ? ElectionSchedule::forPeriodAndType($period, 'dpt_registration') : null;

            if (!$dptSchedule || $dptSchedule->status !== 'berlangsung') {
                $msg = match($dptSchedule?->status) {
                    'belum_dimulai' => 'Pendaftaran DPT belum dibuka. Dimulai ' . $dptSchedule->start_date->translatedFormat('d F Y, H:i') . ' WIB.',
                    'selesai'       => 'Periode Pendaftaran DPT telah berakhir.',
                    default         => 'Pendaftaran DPT belum dijadwalkan. Hubungi administrator.',
                };
                return view('admin.dpt.blocked', ['message' => $msg]);
            }

            $voter = Voter::where('alumni_id', $alumni->id)->first();
            return view('admin.dpt.capture', compact('alumni', 'voter'));
        }

        // Admin / operator: tampilkan manajemen DPT
        return $this->showIndex();
    }

    /** Daftar alumni + status DPT (untuk admin/operator) */
    private function showIndex(Request $request = null)
    {
        $request ??= request();

        $query = Alumni::query()->where('is_active', true);

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($qb) use ($q) {
                $qb->where('name', 'like', "%$q%")
                   ->orWhere('nim',  'like', "%$q%");
            });
        }

        if ($request->filled('faculty')) {
            $query->where('faculty', $request->faculty);
        }

        if ($request->filled('dpt')) {
            if ($request->dpt === 'registered') {
                $query->whereHas('voter');
            } else {
                $query->whereDoesntHave('voter');
            }
        }

        $alumni    = $query->with('voter')->orderBy('name')->paginate(20)->withQueryString();
        $faculties = Alumni::where('is_active', true)->distinct()->orderBy('faculty')->pluck('faculty');

        $stats = [
            'total'      => Alumni::where('is_active', true)->count(),
            'registered' => Voter::count(),
            'pending'    => Alumni::where('is_active', true)->whereDoesntHave('voter')->count(),
            'face_ok'    => Voter::whereNotNull('face_descriptor')->count(),
        ];

        return view('admin.dpt.index', compact('alumni', 'faculties', 'stats'));
    }

    /** Admin mendaftarkan alumni tertentu — tampilkan halaman rekam wajah */
    public function showRegisterAlumni(Alumni $alumnus)
    {
        $voter = Voter::where('alumni_id', $alumnus->id)->first();
        return view('admin.dpt.capture', [
            'alumni'   => $alumnus,
            'voter'    => $voter,
            'byAdmin'  => true,
        ]);
    }

    /** Simpan rekaman wajah via AJAX */
    public function storeCapture(Request $request)
    {
        $request->validate([
            'face_descriptor'   => 'required|array|min:128|max:128',
            'face_descriptor.*' => 'required|numeric',
            'face_photo'        => 'required|string',
            'alumni_id'         => 'nullable|exists:alumni,id',
        ]);

        $user   = auth()->user();
        $alumni = null;

        if ($request->filled('alumni_id') && $user->hasPermission('voters.create')) {
            $alumni = Alumni::find($request->alumni_id);
        }

        if (!$alumni) {
            $alumni = $user->alumni;
        }

        if (!$alumni) {
            return response()->json(['success' => false, 'message' => 'Data alumni tidak ditemukan.'], 422);
        }

        // Alumni hanya bisa menyimpan dalam periode pendaftaran DPT
        if ($user->hasRole('alumni') && !$user->hasPermission('voters.create')) {
            $period      = ElectionPeriod::active();
            $dptSchedule = $period ? ElectionSchedule::forPeriodAndType($period, 'dpt_registration') : null;
            if (!$dptSchedule || $dptSchedule->status !== 'berlangsung') {
                return response()->json(['success' => false, 'message' => 'Periode Pendaftaran DPT tidak sedang berlangsung.'], 403);
            }
        }

        $voter = Voter::updateOrCreate(
            ['alumni_id' => $alumni->id],
            [
                'nim'             => $alumni->nim,
                'name'            => $alumni->name,
                'faculty'         => $alumni->faculty,
                'department'      => $alumni->department,
                'email'           => $alumni->email,
                'phone'           => $alumni->phone,
                'face_descriptor' => $request->face_descriptor,
                'face_photo'      => $request->face_photo,
                'registered_at'   => now(),
                'is_active'       => true,
            ]
        );

        return response()->json([
            'success'    => true,
            'message'    => "Wajah {$alumni->name} berhasil direkam dan terdaftar sebagai DPT.",
            'voter_code' => $voter->voter_code,
        ]);
    }

    /** Reset data wajah */
    public function resetFace(Voter $voter)
    {
        $voter->update([
            'face_descriptor' => null,
            'face_photo'      => null,
            'registered_at'   => null,
        ]);

        return back()->with('success', "Data wajah {$voter->name} berhasil direset.");
    }
}
