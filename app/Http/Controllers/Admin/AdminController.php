<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\ElectionPeriod;
use App\Models\Vote;
use App\Models\Voter;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_voters'      => 12480,
            'voted'             => 8215,
            'not_voted'         => 4265,
            'participation_pct' => 65.8,
            'remaining_pct'     => 34.2,
            'total_candidates'  => 5,
        ];

        $candidates = [
            ['name' => 'Budi Santoso',  'votes' => 3420, 'pct' => 41.6, 'color' => 'bg-brand-600'],
            ['name' => 'Siti Aminah',   'votes' => 2890, 'pct' => 35.2, 'color' => 'bg-blue-500'],
            ['name' => 'Andi Wijaya',   'votes' => 1205, 'pct' => 14.7, 'color' => 'bg-amber-500'],
            ['name' => 'Dewi Lestari',  'votes' =>  700, 'pct' =>  8.5, 'color' => 'bg-pink-500'],
        ];

        $electionEnd = strtotime('today 17:00');
        $election = [
            'status'        => 'active',
            'start'         => '26 Jun 2026, 08:00',
            'end'           => '26 Jun 2026, 17:00',
            'remaining'     => '04 : 32 : 18',
            'end_timestamp' => $electionEnd,
        ];

        $recentActivity = [
            ['voter_id' => 'VT-10482', 'name' => 'Rahmat Hidayat', 'time' => '12:28', 'status' => 'success'],
            ['voter_id' => 'VT-10481', 'name' => 'Nur Fadilah',    'time' => '12:26', 'status' => 'success'],
            ['voter_id' => 'VT-10480', 'name' => 'Joko Prabowo',   'time' => '12:24', 'status' => 'failed'],
            ['voter_id' => 'VT-10479', 'name' => 'Maria Ulfa',     'time' => '12:21', 'status' => 'success'],
        ];

        return view('admin.dashboard', compact('stats', 'candidates', 'election', 'recentActivity'));
    }

    public function voters(Request $request)
    {
        $query = Voter::query();

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

        if ($request->filled('face')) {
            if ($request->face === 'registered') {
                $query->whereNotNull('face_descriptor');
            } else {
                $query->whereNull('face_descriptor');
            }
        }

        if ($request->filled('voted')) {
            $query->where('has_voted', $request->voted === 'yes');
        }

        $voters    = $query->orderBy('name')->paginate(15)->withQueryString();
        $faculties = Voter::distinct()->orderBy('faculty')->pluck('faculty')->filter();

        $stats = [
            'total'        => Voter::count(),
            'face_ok'      => Voter::whereNotNull('face_descriptor')->count(),
            'voted'        => Voter::where('has_voted', true)->count(),
            'not_voted'    => Voter::where('has_voted', false)->count(),
        ];

        return view('admin.voters', compact('voters', 'stats', 'faculties'));
    }

    public function storeVoter(Request $request)
    {
        $data = $request->validate([
            'nim'        => 'required|string|max:30|unique:voters,nim',
            'name'       => 'required|string|max:100',
            'faculty'    => 'nullable|string|max:100',
            'department' => 'nullable|string|max:100',
            'email'      => 'nullable|email|max:100',
            'phone'      => 'nullable|string|max:20',
        ]);

        Voter::create($data);
        return back()->with('success', 'Pemilih berhasil ditambahkan.');
    }

    public function updateVoter(Request $request, Voter $voter)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:100',
            'faculty'    => 'nullable|string|max:100',
            'department' => 'nullable|string|max:100',
            'email'      => 'nullable|email|max:100',
            'phone'      => 'nullable|string|max:20',
            'is_active'  => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $voter->update($data);
        return back()->with('success', 'Data pemilih berhasil diperbarui.');
    }

    public function destroyVoter(Voter $voter)
    {
        $voter->delete();
        return back()->with('success', 'Pemilih berhasil dihapus.');
    }

    public function candidates()
    {
        return view('admin.candidates');
    }

    public function results()
    {
        $activePeriod = ElectionPeriod::active();
        return view('admin.results', compact('activePeriod'));
    }

    public function resultsData()
    {
        $activePeriod = ElectionPeriod::active();

        if (!$activePeriod) {
            return response()->json(['error' => 'Tidak ada periode aktif'], 404);
        }

        $candidates = Candidate::where('election_period_id', $activePeriod->id)
            ->where('is_active', true)
            ->orderBy('number')
            ->get()
            ->map(function ($c) use ($activePeriod) {
                $votes = Vote::where('candidate_id', $c->id)
                    ->where('election_period_id', $activePeriod->id)
                    ->count();
                return [
                    'id'    => $c->id,
                    'name'  => $c->name,
                    'no'    => str_pad($c->number, 2, '0', STR_PAD_LEFT),
                    'photo' => $c->photo ? asset('storage/' . $c->photo) : null,
                    'votes' => $votes,
                ];
            });

        $totalVotes = $candidates->sum('votes');

        $candidates = $candidates->map(fn ($c) => array_merge($c, [
            'pct' => $totalVotes > 0 ? round($c['votes'] / $totalVotes * 100, 1) : 0,
        ]));

        $recentVotes = Vote::with(['voter', 'candidate'])
            ->where('election_period_id', $activePeriod->id)
            ->orderByDesc('voted_at')
            ->limit(30)
            ->get()
            ->map(fn ($v) => [
                'name'      => $v->voter?->name ?? 'Anonim',
                'nim'       => $v->voter?->nim ?? '',
                'faculty'   => $v->voter?->faculty ?? '',
                'candidate' => $v->candidate?->name ?? '—',
                'no'        => $v->candidate ? str_pad($v->candidate->number, 2, '0', STR_PAD_LEFT) : '—',
                'time'      => $v->voted_at->format('H:i:s'),
                'ago'       => $v->voted_at->diffForHumans(),
            ]);

        $totalDpt   = Voter::where('is_active', true)->count();
        $totalVoted = Voter::where('is_active', true)->where('has_voted', true)->count();
        $notVoted   = $totalDpt - $totalVoted;
        $partPct    = $totalDpt > 0 ? round($totalVoted / $totalDpt * 100, 1) : 0;

        return response()->json([
            'candidates'   => $candidates->values(),
            'recent_votes' => $recentVotes,
            'total_votes'  => $totalVotes,
            'dpt'          => [
                'total'     => $totalDpt,
                'voted'     => $totalVoted,
                'not_voted' => $notVoted,
                'part_pct'  => $partPct,
            ],
            'period_name' => $activePeriod->name,
            'updated_at'  => now()->format('H:i:s'),
        ]);
    }

    public function schedule()
    {
        return view('admin.schedule');
    }

    public function settings()
    {
        return view('admin.settings');
    }
}
