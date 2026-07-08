@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
@php
  $u       = auth()->user();
  $isAdmin = $u->hasRole('admin') || $u->hasRole('superadmin');
@endphp

{{-- ═══ STAT CARDS — tampil untuk semua role ═══ --}}
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
        <p class="text-sm text-slate-500">{{ $isAdmin ? 'Kandidat Aktif' : 'Partisipasi' }}</p>
        <p class="text-2xl font-bold text-slate-800 mt-1">
          @if($isAdmin)
            {{ $stats['total_candidates'] }}
          @else
            {{ $stats['participation_pct'] }}%
          @endif
        </p>
      </div>
      <div class="w-11 h-11 rounded-lg bg-brand-50 flex items-center justify-center">
        @if($isAdmin)
          <svg class="w-6 h-6 text-brand-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
          </svg>
        @else
          <svg class="w-6 h-6 text-brand-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
          </svg>
        @endif
      </div>
    </div>
    <p class="text-xs text-slate-400 mt-3">
      @if($isAdmin) Pemilihan periode ini @else Dari total pemilih terdaftar @endif
    </p>
  </div>

</div>

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

  @else
  {{-- Partisipasi Pemilih — alumni/kandidat --}}
  <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 p-5">
    <h2 class="font-semibold text-slate-800 mb-5">Partisipasi Pemilih</h2>

    {{-- Angka besar --}}
    <div class="flex items-end gap-3 mb-1">
      <span class="text-5xl font-black text-brand-600">{{ $stats['participation_pct'] }}%</span>
      <span class="text-sm text-slate-400 mb-2">dari total pemilih terdaftar</span>
    </div>

    {{-- Progress bar --}}
    <div class="mt-4 h-5 bg-slate-100 rounded-full overflow-hidden">
      <div class="h-full rounded-full bg-brand-500 transition-all duration-700"
           style="width: {{ $stats['participation_pct'] }}%"></div>
    </div>

    {{-- Breakdown --}}
    <div class="grid grid-cols-2 gap-4 mt-6">
      <div class="flex items-center gap-3 bg-green-50 rounded-xl p-4">
        <div class="w-3 h-3 rounded-full bg-green-500 shrink-0"></div>
        <div>
          <p class="text-xs text-green-600 font-medium">Sudah Memilih</p>
          <p class="text-xl font-bold text-green-700 mt-0.5">{{ number_format($stats['voted']) }}</p>
        </div>
      </div>
      <div class="flex items-center gap-3 bg-amber-50 rounded-xl p-4">
        <div class="w-3 h-3 rounded-full bg-amber-400 shrink-0"></div>
        <div>
          <p class="text-xs text-amber-600 font-medium">Belum Memilih</p>
          <p class="text-xl font-bold text-amber-700 mt-0.5">{{ number_format($stats['not_voted']) }}</p>
        </div>
      </div>
    </div>

    {{-- Total --}}
    <p class="text-xs text-slate-400 mt-4 text-right">
      Total DPT terdaftar: <span class="font-semibold text-slate-600">{{ number_format($stats['total_voters']) }}</span> pemilih
    </p>
  </div>
  @endif

  {{-- Status Pemilihan — semua role --}}
  <div class="bg-white rounded-xl border border-slate-200 p-5">
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

@push('scripts')
<script>
@if ($election['status'] === 'active' && !empty($election['end_timestamp']))
(function () {
  const endTime = {{ $election['end_timestamp'] }} * 1000;
  const el = document.getElementById('countdown');
  if (!el) return;
  function tick() {
    const diff = Math.max(0, endTime - Date.now());
    const h = String(Math.floor(diff / 3600000)).padStart(2, '0');
    const m = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
    const s = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');
    el.textContent = h + ' : ' + m + ' : ' + s;
    if (diff > 0) setTimeout(tick, 1000);
  }
  tick();
})();
@endif
</script>
@endpush
