<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\ElectionPeriod;
use App\Models\Vote;
use App\Models\Voter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VotingController extends Controller
{
    /** Euclidean distance antara dua face descriptor (128-dim) */
    private function faceDistance(array $d1, array $d2): float
    {
        $sum = 0.0;
        foreach ($d1 as $i => $v) {
            $sum += ($v - ($d2[$i] ?? 0)) ** 2;
        }
        return sqrt($sum);
    }

    /** Verifikasi wajah via AJAX — set session jika cocok */
    public function verifyFace(Request $request)
    {
        $request->validate(['descriptor' => 'required|array|min:128|max:128']);

        $user  = Auth::user();
        $voter = $user->alumni_id
            ? Voter::where('alumni_id', $user->alumni_id)->where('is_active', true)->first()
            : null;

        if (!$voter || !$voter->face_descriptor) {
            return response()->json(['ok' => false, 'message' => 'Data wajah tidak ditemukan.'], 422);
        }

        $dist = $this->faceDistance($voter->face_descriptor, $request->descriptor);

        if ($dist > 0.55) {
            return response()->json(['ok' => false, 'message' => 'Wajah tidak cocok. Jarak: ' . round($dist, 3)], 422);
        }

        session(['face_verified_voter' => $voter->id, 'face_verified_at' => now()->timestamp]);

        return response()->json(['ok' => true, 'message' => 'Verifikasi berhasil.']);
    }

    public function index()
    {
        $user = Auth::user();

        // Cari voter record berdasarkan alumni_id user
        $voter = $user->alumni_id
            ? Voter::where('alumni_id', $user->alumni_id)->where('is_active', true)->first()
            : null;

        $activePeriod     = ElectionPeriod::active();
        $electionSchedule = $activePeriod?->electionSchedule();

        // Status kondisi
        $notInDpt      = !$voter;
        $electionStatus = $electionSchedule?->status ?? 'belum_diatur';
        $notOpen       = !in_array($electionStatus, ['berlangsung']);
        $alreadyVoted  = $voter && $voter->has_voted;

        if ($notInDpt || $notOpen || $alreadyVoted) {
            $candidates = collect();
        } else {
            $candidates = Candidate::with('alumni')
                ->where('election_period_id', $activePeriod->id)
                ->where('is_active', true)
                ->orderBy('number')
                ->get();
        }

        return view('voting.index', compact(
            'voter', 'activePeriod', 'electionSchedule',
            'notInDpt', 'electionStatus', 'alreadyVoted', 'candidates'
        ));
    }

    public function cast(Request $request)
    {
        $request->validate(['candidate_id' => 'required|integer|exists:candidates,id']);

        $user  = Auth::user();
        $voter = $user->alumni_id
            ? Voter::where('alumni_id', $user->alumni_id)->where('is_active', true)->first()
            : null;

        abort_if(!$voter, 403, 'Anda tidak terdaftar sebagai pemilih.');
        abort_if($voter->has_voted, 403, 'Anda sudah memberikan suara.');

        // Cek sesi verifikasi wajah (berlaku 30 menit)
        $verifiedVoter = session('face_verified_voter');
        $verifiedAt    = session('face_verified_at');
        abort_if(
            $verifiedVoter !== $voter->id || !$verifiedAt || (now()->timestamp - $verifiedAt) > 1800,
            403,
            'Verifikasi wajah diperlukan sebelum memilih.'
        );

        $activePeriod     = ElectionPeriod::active();
        abort_if(!$activePeriod, 403, 'Tidak ada periode pemilihan aktif.');

        $electionSchedule = $activePeriod->electionSchedule();
        abort_if($electionSchedule?->status !== 'berlangsung', 403, 'Waktu pemilihan belum/sudah berakhir.');

        $candidate = Candidate::where('id', $request->candidate_id)
            ->where('election_period_id', $activePeriod->id)
            ->where('is_active', true)
            ->firstOrFail();

        DB::transaction(function () use ($voter, $candidate, $activePeriod) {
            Vote::create([
                'voter_id'           => $voter->id,
                'candidate_id'       => $candidate->id,
                'election_period_id' => $activePeriod->id,
                'voted_at'           => now(),
            ]);

            $voter->update([
                'has_voted' => true,
                'voted_at'  => now(),
            ]);
        });

        session()->forget(['face_verified_voter', 'face_verified_at']);

        return redirect()->route('voting')->with('success_voted', true);
    }
}
