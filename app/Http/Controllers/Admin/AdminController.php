<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Document;
use App\Models\ElectionPeriod;
use App\Models\ElectionSchedule;
use App\Models\Setting;
use App\Models\Vote;
use App\Models\Voter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        // Real DPT stats
        $totalVoters      = Voter::where('is_active', true)->count();
        $voted            = Voter::where('is_active', true)->where('has_voted', true)->count();
        $notVoted         = $totalVoters - $voted;
        $participationPct = $totalVoters > 0 ? round($voted / $totalVoters * 100, 1) : 0;
        $totalCandidates  = Candidate::whereHas('period', fn($q) => $q->where('is_active', true))
            ->where('is_active', true)->count();

        $stats = [
            'total_voters'      => $totalVoters,
            'voted'             => $voted,
            'not_voted'         => $notVoted,
            'participation_pct' => $participationPct,
            'remaining_pct'     => round(100 - $participationPct, 1),
            'total_candidates'  => $totalCandidates,
        ];

        // Voter record milik user yang sedang login (alumni/kandidat)
        $userVoter = $user->alumni?->voter;

        // Vote tally — hanya admin/superadmin, data dari DB
        $candidates = [];
        if ($user->hasRole('admin') || $user->hasRole('superadmin')) {
            $colors = ['bg-brand-600', 'bg-blue-500', 'bg-amber-500', 'bg-pink-500', 'bg-teal-500'];
            $totalVotes = Vote::count();
            $candidates = Candidate::whereHas('period', fn($q) => $q->where('is_active', true))
                ->where('is_active', true)
                ->withCount('votes')
                ->orderBy('number')
                ->get()
                ->map(function ($c, $i) use ($totalVotes, $colors) {
                    $pct = $totalVotes > 0 ? round($c->votes_count / $totalVotes * 100, 1) : 0;
                    return [
                        'name'  => $c->name,
                        'votes' => $c->votes_count,
                        'pct'   => $pct,
                        'color' => $colors[$i % count($colors)],
                    ];
                })
                ->toArray();
        }

        // Status pemilihan dari jadwal aktif
        $activePeriod = ElectionPeriod::active();
        $activePeriod?->load('schedules');
        $electionSchedule = $activePeriod?->electionSchedule();

        $election = ['status' => 'none', 'start' => '—', 'end' => '—', 'end_timestamp' => null];
        if ($electionSchedule) {
            $election['status']        = match ($electionSchedule->status) {
                'berlangsung'   => 'active',
                'belum_dimulai' => 'upcoming',
                'selesai'       => 'ended',
                default         => 'none',
            };
            $election['start']         = $electionSchedule->start_date?->translatedFormat('d M Y, H:i') ?? '—';
            $election['end']           = $electionSchedule->end_date?->translatedFormat('d M Y, H:i') ?? '—';
            $election['end_timestamp'] = $electionSchedule->end_date?->timestamp;
        }

        // Aktivitas terbaru — hanya admin/superadmin
        $recentActivity = [];
        if ($user->hasRole('admin') || $user->hasRole('superadmin')) {
            $recentActivity = Voter::where('has_voted', true)
                ->orderByDesc('voted_at')
                ->limit(5)
                ->get()
                ->map(fn($v) => [
                    'voter_id' => $v->voter_code,
                    'name'     => $v->name,
                    'time'     => $v->voted_at?->format('H:i') ?? '—',
                    'status'   => 'success',
                ])
                ->toArray();
        }

        // DPT schedule — untuk popup reminder alumni
        $dptSchedule = null;
        if ($activePeriod) {
            $dptSchedule = ElectionSchedule::forPeriodAndType($activePeriod, 'dpt_registration');
        }

        // Rekapitulasi per fakultas & program studi — hanya admin/superadmin
        $byFaculty    = [];
        $byDepartment = [];
        if ($user->hasRole('admin') || $user->hasRole('superadmin')) {
            $byFaculty = Voter::where('is_active', true)
                ->selectRaw('faculty, COUNT(*) as total, SUM(has_voted) as voted')
                ->groupBy('faculty')
                ->orderBy('faculty')
                ->get()
                ->map(fn($r) => [
                    'name'  => $r->faculty ?: 'Tidak Diketahui',
                    'total' => (int) $r->total,
                    'voted' => (int) $r->voted,
                    'pct'   => $r->total > 0 ? round($r->voted / $r->total * 100, 1) : 0,
                ])
                ->toArray();

            $byDepartment = Voter::where('is_active', true)
                ->selectRaw('department, faculty, COUNT(*) as total, SUM(has_voted) as voted')
                ->groupBy('department', 'faculty')
                ->orderBy('faculty')
                ->orderBy('department')
                ->get()
                ->map(fn($r) => [
                    'name'    => $r->department ?: 'Tidak Diketahui',
                    'faculty' => $r->faculty ?: '—',
                    'total'   => (int) $r->total,
                    'voted'   => (int) $r->voted,
                    'pct'     => $r->total > 0 ? round($r->voted / $r->total * 100, 1) : 0,
                ])
                ->toArray();
        }

        return view('admin.dashboard', compact(
            'stats', 'candidates', 'election', 'recentActivity',
            'userVoter', 'dptSchedule', 'byFaculty', 'byDepartment'
        ));
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
        $settings = [
            'google_form_url'  => Setting::get('google_form_url', ''),
            'site_name'        => Setting::get('site_name', 'E-Voting'),
            'site_description' => Setting::get('site_description', 'Sistem Pemilihan Digital Alumni'),
            'youtube_url'      => Setting::get('youtube_url', ''),
            'youtube_title'    => Setting::get('youtube_title', 'Panduan Video'),
        ];
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'google_form_url'  => 'nullable|url|max:500',
            'site_name'        => 'nullable|string|max:100',
            'site_description' => 'nullable|string|max:255',
            'youtube_url'      => 'nullable|url|max:500',
            'youtube_title'    => 'nullable|string|max:100',
        ]);

        foreach ($data as $key => $value) {
            Setting::set($key, $value ?: null);
        }

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }

    public function documents()
    {
        $documents = Document::orderBy('sort_order')->orderByDesc('created_at')->get();
        return view('admin.documents', compact('documents'));
    }

    public function storeDocument(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:200',
            'description'  => 'nullable|string|max:500',
            'file'         => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip|max:20480',
            'sort_order'   => 'nullable|integer',
        ]);

        $file = $request->file('file');
        $path = $file->store('documents', 'public');

        Document::create([
            'title'        => $request->title,
            'description'  => $request->description,
            'file_path'    => $path,
            'file_name'    => $file->getClientOriginalName(),
            'file_size'    => $this->formatFileSize($file->getSize()),
            'is_published' => $request->boolean('is_published', true),
            'sort_order'   => $request->input('sort_order', 0),
        ]);

        return back()->with('success', 'Dokumen berhasil diunggah.');
    }

    public function toggleDocument(Document $document)
    {
        $document->update(['is_published' => !$document->is_published]);
        return back()->with('success', 'Status dokumen diperbarui.');
    }

    public function destroyDocument(Document $document)
    {
        Storage::disk('public')->delete($document->file_path);
        $document->delete();
        return back()->with('success', 'Dokumen berhasil dihapus.');
    }

    private function formatFileSize(int $bytes): string
    {
        if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024)    return round($bytes / 1024, 1) . ' KB';
        return $bytes . ' B';
    }
}
