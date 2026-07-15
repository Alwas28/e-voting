@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
@php
  $u       = auth()->user();
  $isAdmin = $u->hasRole('admin') || $u->hasRole('superadmin');
@endphp

{{-- ═══ STAT CARDS — admin saja ═══ --}}
@if($isAdmin)
<div class="grid grid-cols-2 xl:grid-cols-4 gap-4">

  <div class="bg-white rounded-xl border border-slate-200 p-5">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-sm text-slate-500">Total DPT</p>
        <p class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($stats['total_voters']) }}</p>
      </div>
      <div class="w-11 h-11 rounded-lg bg-blue-50 flex items-center justify-center">
        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4z"/>
        </svg>
      </div>
    </div>
    <p class="text-xs text-slate-400 mt-3">Pemilih terdaftar aktif</p>
  </div>

  <div class="bg-white rounded-xl border border-slate-200 p-5">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-sm text-slate-500">Sudah Memilih</p>
        <p class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($stats['voted']) }}</p>
      </div>
      <div class="w-11 h-11 rounded-lg bg-green-50 flex items-center justify-center">
        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>
    </div>
    <p class="text-xs text-green-600 mt-3">{{ $stats['participation_pct'] }}% partisipasi</p>
  </div>

  <div class="bg-white rounded-xl border border-slate-200 p-5">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-sm text-slate-500">Belum Memilih</p>
        <p class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($stats['not_voted']) }}</p>
      </div>
      <div class="w-11 h-11 rounded-lg bg-amber-50 flex items-center justify-center">
        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>
    </div>
    <p class="text-xs text-slate-400 mt-3">{{ $stats['remaining_pct'] }}% sisa</p>
  </div>

  <div class="bg-white rounded-xl border border-slate-200 p-5">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-sm text-slate-500">Kandidat Aktif</p>
        <p class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['total_candidates'] }}</p>
      </div>
      <div class="w-11 h-11 rounded-lg bg-brand-50 flex items-center justify-center">
        <svg class="w-6 h-6 text-brand-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
        </svg>
      </div>
    </div>
    <p class="text-xs text-slate-400 mt-3">Pemilihan periode ini</p>
  </div>

</div>
@endif

{{-- ═══ REKAPITULASI PARTISIPASI — admin saja ═══ --}}
@if($isAdmin)
<div class="space-y-4">

  {{-- Per Fakultas --}}
  <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
      <div>
        <h2 class="font-semibold text-slate-800">Partisipasi per Fakultas</h2>
        <p class="text-xs text-slate-400 mt-0.5">DPT aktif yang telah menggunakan hak suara</p>
      </div>
      <span class="text-xs font-semibold bg-brand-50 text-brand-700 px-2.5 py-1 rounded-full">
        {{ $stats['participation_pct'] }}% keseluruhan
      </span>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-500 text-left">
          <tr>
            <th class="px-5 py-3 font-medium">Fakultas</th>
            <th class="px-5 py-3 font-medium text-right">Total DPT</th>
            <th class="px-5 py-3 font-medium text-right">Sudah Memilih</th>
            <th class="px-5 py-3 font-medium text-right">Belum Memilih</th>
            <th class="px-5 py-3 font-medium min-w-[180px]">Partisipasi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse($byFaculty as $row)
          <tr class="hover:bg-slate-50">
            <td class="px-5 py-3 font-medium text-slate-700">{{ $row['name'] }}</td>
            <td class="px-5 py-3 text-right text-slate-600">{{ number_format($row['total']) }}</td>
            <td class="px-5 py-3 text-right text-green-600 font-medium">{{ number_format($row['voted']) }}</td>
            <td class="px-5 py-3 text-right text-amber-600">{{ number_format($row['total'] - $row['voted']) }}</td>
            <td class="px-5 py-3">
              <div class="flex items-center gap-2">
                <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                  <div class="h-full rounded-full {{ $row['pct'] >= 75 ? 'bg-green-500' : ($row['pct'] >= 40 ? 'bg-brand-600' : 'bg-amber-500') }}"
                       style="width: {{ $row['pct'] }}%"></div>
                </div>
                <span class="text-xs font-semibold text-slate-600 w-11 text-right tabular-nums">{{ $row['pct'] }}%</span>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="px-5 py-8 text-center text-slate-400 text-sm">Belum ada data DPT terdaftar.</td>
          </tr>
          @endforelse
        </tbody>
        @if(count($byFaculty))
        <tfoot class="bg-slate-50 border-t border-slate-200">
          <tr class="font-semibold text-slate-700">
            <td class="px-5 py-3">Total</td>
            <td class="px-5 py-3 text-right">{{ number_format($stats['total_voters']) }}</td>
            <td class="px-5 py-3 text-right text-green-700">{{ number_format($stats['voted']) }}</td>
            <td class="px-5 py-3 text-right text-amber-700">{{ number_format($stats['not_voted']) }}</td>
            <td class="px-5 py-3 text-xs text-slate-500">{{ $stats['participation_pct'] }}% partisipasi</td>
          </tr>
        </tfoot>
        @endif
      </table>
    </div>
  </div>

  {{-- Per Program Studi --}}
  <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100">
      <h2 class="font-semibold text-slate-800">Partisipasi per Program Studi</h2>
      <p class="text-xs text-slate-400 mt-0.5">Rincian DPT berdasarkan program studi</p>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-500 text-left">
          <tr>
            <th class="px-5 py-3 font-medium">Program Studi</th>
            <th class="px-5 py-3 font-medium">Fakultas</th>
            <th class="px-5 py-3 font-medium text-right">Total DPT</th>
            <th class="px-5 py-3 font-medium text-right">Sudah Memilih</th>
            <th class="px-5 py-3 font-medium text-right">Belum Memilih</th>
            <th class="px-5 py-3 font-medium min-w-[180px]">Partisipasi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse($byDepartment as $row)
          <tr class="hover:bg-slate-50">
            <td class="px-5 py-3 font-medium text-slate-700">{{ $row['name'] }}</td>
            <td class="px-5 py-3 text-slate-400 text-xs">{{ $row['faculty'] }}</td>
            <td class="px-5 py-3 text-right text-slate-600">{{ number_format($row['total']) }}</td>
            <td class="px-5 py-3 text-right text-green-600 font-medium">{{ number_format($row['voted']) }}</td>
            <td class="px-5 py-3 text-right text-amber-600">{{ number_format($row['total'] - $row['voted']) }}</td>
            <td class="px-5 py-3">
              <div class="flex items-center gap-2">
                <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                  <div class="h-full rounded-full {{ $row['pct'] >= 75 ? 'bg-green-500' : ($row['pct'] >= 40 ? 'bg-brand-600' : 'bg-amber-500') }}"
                       style="width: {{ $row['pct'] }}%"></div>
                </div>
                <span class="text-xs font-semibold text-slate-600 w-11 text-right tabular-nums">{{ $row['pct'] }}%</span>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="px-5 py-8 text-center text-slate-400 text-sm">Belum ada data DPT terdaftar.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</div>
@endif

{{-- ═══ SECTION 2: Kolom utama + Status Pemilihan ═══ --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

  @if($isAdmin)
  {{-- Perolehan Suara — admin/superadmin saja --}}
  <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 p-5">
    <div class="flex items-center justify-between mb-5">
      <h2 class="font-semibold text-slate-800">Perolehan Suara Sementara</h2>
      <span class="text-xs bg-green-50 text-green-700 px-2.5 py-1 rounded-full font-medium">● Live</span>
    </div>
    @if(count($candidates))
    <div class="space-y-4">
      @foreach ($candidates as $candidate)
        <div>
          <div class="flex justify-between text-sm mb-1.5">
            <span class="font-medium text-slate-700">{{ $candidate['name'] }}</span>
            <span class="text-slate-500">{{ number_format($candidate['votes']) }} suara ({{ $candidate['pct'] }}%)</span>
          </div>
          <div class="h-2.5 bg-slate-100 rounded-full overflow-hidden">
            <div class="h-full rounded-full {{ $candidate['color'] }}" style="width: {{ $candidate['pct'] }}%"></div>
          </div>
        </div>
      @endforeach
    </div>
    @else
    <div class="flex flex-col items-center justify-center py-10 text-slate-400">
      <svg class="w-12 h-12 mb-3 opacity-30" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
      </svg>
      <p class="text-sm">Belum ada data suara</p>
    </div>
    @endif
  </div>
  @endif

  {{-- Status Pemilihan — semua role; full-width jika alumni --}}
  <div class="{{ $isAdmin ? '' : 'lg:col-span-3' }} bg-white rounded-xl border border-slate-200 p-5">
    <h2 class="font-semibold text-slate-800 mb-5">Status Pemilihan</h2>
    <div class="space-y-4 text-sm">
      <div class="flex items-center justify-between">
        <span class="text-slate-500">Status</span>
        @if ($election['status'] === 'active')
          <span class="bg-green-50 text-green-700 px-2.5 py-1 rounded-full text-xs font-medium">● Berlangsung</span>
        @elseif ($election['status'] === 'upcoming')
          <span class="bg-blue-50 text-blue-700 px-2.5 py-1 rounded-full text-xs font-medium">Akan Datang</span>
        @elseif ($election['status'] === 'ended')
          <span class="bg-slate-100 text-slate-600 px-2.5 py-1 rounded-full text-xs font-medium">Selesai</span>
        @else
          <span class="bg-slate-100 text-slate-400 px-2.5 py-1 rounded-full text-xs font-medium">Belum Diatur</span>
        @endif
      </div>
      <div class="flex items-center justify-between">
        <span class="text-slate-500">Mulai</span>
        <span class="font-medium text-slate-700 text-right">{{ $election['start'] }}</span>
      </div>
      <div class="flex items-center justify-between">
        <span class="text-slate-500">Berakhir</span>
        <span class="font-medium text-slate-700 text-right">{{ $election['end'] }}</span>
      </div>
      @if ($election['status'] === 'active')
        <div class="pt-3 border-t border-slate-100">
          <p class="text-slate-500 mb-2 text-xs">Sisa waktu pemilihan</p>
          <p id="countdown" class="text-2xl font-bold text-brand-700 tabular-nums">— : — : —</p>
        </div>
      @endif
    </div>

    {{-- Tombol Lihat Hasil — admin saja --}}
    @if($isAdmin)
    <a href="{{ route('admin.results') }}"
       class="block w-full mt-5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium py-2.5 rounded-lg transition text-center">
      Lihat Detail Hasil
    </a>
    @endif
  </div>

</div>

{{-- ═══ STATUS DPT PRIBADI — alumni/kandidat saja ═══ --}}
@if(!$isAdmin && $userVoter)
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
  <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
    <h2 class="font-semibold text-slate-800">Status DPT Saya</h2>
    <span class="text-xs font-mono text-slate-400">{{ $userVoter->voter_code }}</span>
  </div>
  <div class="p-5 grid grid-cols-1 sm:grid-cols-3 gap-4">

    <div class="flex items-center gap-4 bg-slate-50 rounded-xl p-4">
      <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0
                  {{ $userVoter->is_active ? 'bg-green-100' : 'bg-red-100' }}">
        @if($userVoter->is_active)
          <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        @else
          <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        @endif
      </div>
      <div>
        <p class="text-xs text-slate-400 font-medium">Status DPT</p>
        <p class="text-sm font-bold mt-0.5 {{ $userVoter->is_active ? 'text-green-700' : 'text-red-600' }}">
          {{ $userVoter->is_active ? 'Aktif' : 'Tidak Aktif' }}
        </p>
      </div>
    </div>

    <div class="flex items-center gap-4 bg-slate-50 rounded-xl p-4">
      <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0
                  {{ $userVoter->face_descriptor ? 'bg-brand-100' : 'bg-amber-100' }}">
        <svg class="w-5 h-5 {{ $userVoter->face_descriptor ? 'text-brand-600' : 'text-amber-500' }}"
             fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
        </svg>
      </div>
      <div>
        <p class="text-xs text-slate-400 font-medium">Rekam Wajah</p>
        <p class="text-sm font-bold mt-0.5 {{ $userVoter->face_descriptor ? 'text-brand-700' : 'text-amber-600' }}">
          {{ $userVoter->face_descriptor ? 'Sudah Direkam' : 'Belum Direkam' }}
        </p>
      </div>
    </div>

    <div class="flex items-center gap-4 bg-slate-50 rounded-xl p-4">
      <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0
                  {{ $userVoter->has_voted ? 'bg-green-100' : 'bg-slate-200' }}">
        <svg class="w-5 h-5 {{ $userVoter->has_voted ? 'text-green-600' : 'text-slate-400' }}"
             fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
        </svg>
      </div>
      <div>
        <p class="text-xs text-slate-400 font-medium">Status Voting</p>
        <p class="text-sm font-bold mt-0.5 {{ $userVoter->has_voted ? 'text-green-700' : 'text-slate-500' }}">
          {{ $userVoter->has_voted ? 'Sudah Memilih' : 'Belum Memilih' }}
        </p>
        @if($userVoter->voted_at)
          <p class="text-xs text-slate-400">{{ $userVoter->voted_at->translatedFormat('d M Y, H:i') }}</p>
        @endif
      </div>
    </div>

  </div>
</div>
@elseif(!$isAdmin && !$userVoter)
{{-- Belum terdaftar DPT --}}
<div class="bg-amber-50 border border-amber-200 rounded-xl p-5 flex items-start gap-4">
  <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center shrink-0">
    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
    </svg>
  </div>
  <div>
    <p class="font-semibold text-amber-800">Anda belum terdaftar sebagai pemilih (DPT)</p>
    <p class="text-sm text-amber-600 mt-0.5">Daftarkan diri Anda untuk dapat menggunakan hak pilih.</p>
    <a href="{{ route('admin.dpt.register') }}"
       class="inline-block mt-3 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
      Daftar DPT Sekarang
    </a>
  </div>
</div>
@endif

{{-- ═══ AKTIVITAS TERBARU — admin saja ═══ --}}
@if($isAdmin)
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
  <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
    <h2 class="font-semibold text-slate-800">Aktivitas Pemilihan Terbaru</h2>
    <a href="{{ route('admin.voters') }}" class="text-xs text-brand-600 hover:underline">Lihat semua</a>
  </div>
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-slate-50 text-slate-500 text-left">
        <tr>
          <th class="px-5 py-3 font-medium">Kode DPT</th>
          <th class="px-5 py-3 font-medium">Nama</th>
          <th class="px-5 py-3 font-medium">Waktu</th>
          <th class="px-5 py-3 font-medium">Status</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        @forelse ($recentActivity as $activity)
          <tr class="hover:bg-slate-50">
            <td class="px-5 py-3 font-mono text-slate-600">{{ $activity['voter_id'] }}</td>
            <td class="px-5 py-3 text-slate-700">{{ $activity['name'] }}</td>
            <td class="px-5 py-3 text-slate-500">{{ $activity['time'] }}</td>
            <td class="px-5 py-3">
              <span class="bg-green-50 text-green-700 px-2 py-0.5 rounded-full text-xs">Berhasil</span>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="px-5 py-8 text-center text-slate-400">Belum ada aktivitas pemilihan.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endif

@endsection

{{-- ═══ POPUP: Reminder DPT untuk alumni yang belum terdaftar ═══ --}}
@if(!$isAdmin && !$userVoter && isset($dptSchedule) && $dptSchedule?->status === 'berlangsung')
<div id="dptPopup" class="fixed inset-0 z-[80] flex items-center justify-center p-4" style="display:none!important">
  <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
  <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden">

    {{-- Top accent --}}
    <div class="h-2 bg-gradient-to-r from-red-500 via-amber-500 to-orange-400"></div>

    <div class="px-6 pt-6 pb-2 flex items-start gap-4">
      <div class="w-12 h-12 rounded-2xl bg-red-100 flex items-center justify-center shrink-0">
        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
      </div>
      <div>
        <p class="font-bold text-slate-900 text-base leading-tight">Segera Daftar DPT!</p>
        <p class="text-sm text-slate-500 mt-0.5">Pendaftaran pemilih masih terbuka</p>
      </div>
    </div>

    <div class="px-6 py-4 space-y-4">
      <p class="text-sm text-slate-600">
        Anda <span class="font-semibold text-red-600">belum terdaftar sebagai pemilih (DPT)</span>.
        Jika tidak mendaftar sebelum pendaftaran ditutup, Anda tidak dapat menyalurkan hak suara dalam pemilihan.
      </p>

      {{-- Deadline info --}}
      <div class="bg-amber-50 border border-amber-200 rounded-2xl px-4 py-3 space-y-2">
        <div class="flex items-center justify-between text-sm">
          <span class="text-amber-700 font-medium">Pendaftaran DPT ditutup:</span>
          <span class="text-amber-900 font-bold">
            {{ $dptSchedule->end_date?->translatedFormat('d M Y') ?? '—' }}
          </span>
        </div>
        <div class="flex items-center justify-between text-sm">
          <span class="text-amber-600 text-xs">Sisa waktu:</span>
          <span id="dptCountdown" class="font-mono font-bold text-red-600 text-sm tabular-nums">—</span>
        </div>
      </div>
    </div>

    <div class="px-6 pb-6 flex flex-col gap-2">
      <a href="{{ route('admin.dpt.register') }}"
         class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 rounded-xl transition flex items-center justify-center gap-2 text-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
        </svg>
        Daftar DPT Sekarang
      </a>
      <button onclick="dismissDptPopup()"
              class="w-full border border-slate-300 text-slate-500 hover:bg-slate-50 font-medium py-2.5 rounded-xl transition text-sm">
        Nanti Saja
      </button>
    </div>

  </div>
</div>

@push('scripts')
<script>
(function () {
  const key = 'dptPopupDismissed_{{ auth()->id() }}';
  if (!sessionStorage.getItem(key)) {
    const popup = document.getElementById('dptPopup');
    if (popup) popup.style.cssText = 'display:flex!important';
  }

  @if($dptSchedule?->end_date)
  const endTime = {{ $dptSchedule->end_date->timestamp }} * 1000;
  const el = document.getElementById('dptCountdown');
  function tickDpt() {
    const diff = Math.max(0, endTime - Date.now());
    if (!diff) { if (el) el.textContent = 'Ditutup'; return; }
    const ts = Math.floor(diff / 1000);
    const d  = Math.floor(ts / 86400);
    const h  = String(Math.floor((ts % 86400) / 3600)).padStart(2, '0');
    const m  = String(Math.floor((ts % 3600) / 60)).padStart(2, '0');
    const s  = String(ts % 60).padStart(2, '0');
    if (el) el.textContent = d > 0 ? `${d} hari ${h}:${m}:${s}` : `${h}:${m}:${s}`;
    setTimeout(tickDpt, 1000);
  }
  tickDpt();
  @endif
})();

function dismissDptPopup() {
  const popup = document.getElementById('dptPopup');
  if (popup) popup.style.cssText = 'display:none!important';
  sessionStorage.setItem('dptPopupDismissed_{{ auth()->id() }}', '1');
}
</script>
@endpush
@endif

@push('scripts')
<script>
@if ($election['status'] === 'active' && !empty($election['end_timestamp']))
(function () {
  const endTime = {{ $election['end_timestamp'] }} * 1000;
  const el = document.getElementById('countdown');
  if (!el) return;
  function tick() {
    const diff = Math.max(0, endTime - Date.now());
    const ts = Math.floor(diff / 1000);
    const d  = Math.floor(ts / 86400);
    const h  = String(Math.floor((ts % 86400) / 3600)).padStart(2, '0');
    const m  = String(Math.floor((ts % 3600) / 60)).padStart(2, '0');
    const s  = String(ts % 60).padStart(2, '0');
    el.textContent = d > 0 ? `${d} hari ${h}:${m}:${s}` : `${h}:${m}:${s}`;
    if (diff > 0) setTimeout(tick, 1000);
  }
  tick();
})();
@endif
</script>
@endpush
