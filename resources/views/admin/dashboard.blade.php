@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

  {{-- Stat cards --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

    <div class="bg-white rounded-xl border border-slate-200 p-5">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-slate-500">Total Pemilih</p>
          <p class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($stats['total_voters']) }}</p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-blue-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4z"/></svg>
        </div>
      </div>
      <p class="text-xs text-green-600 mt-3">↑ 3,2% dari minggu lalu</p>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-5">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-slate-500">Sudah Memilih</p>
          <p class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($stats['voted']) }}</p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-green-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
      </div>
      <p class="text-xs text-slate-500 mt-3">{{ $stats['participation_pct'] }}% partisipasi</p>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-5">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-slate-500">Belum Memilih</p>
          <p class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($stats['not_voted']) }}</p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-amber-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
      </div>
      <p class="text-xs text-slate-500 mt-3">{{ $stats['remaining_pct'] }}% sisa</p>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-5">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-slate-500">Jumlah Kandidat</p>
          <p class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['total_candidates'] }}</p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-brand-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-brand-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
        </div>
      </div>
      <p class="text-xs text-slate-500 mt-3">Pemilihan aktif</p>
    </div>

  </div>

  {{-- Two-column section --}}
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Vote tally --}}
    <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 p-5">
      <div class="flex items-center justify-between mb-5">
        <h2 class="font-semibold text-slate-800">Perolehan Suara Sementara</h2>
        <span class="text-xs bg-green-50 text-green-700 px-2.5 py-1 rounded-full font-medium">● Live</span>
      </div>

      <div class="space-y-4">
        @foreach ($candidates as $candidate)
          <div>
            <div class="flex justify-between text-sm mb-1.5">
              <span class="font-medium text-slate-700">Kandidat {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }} — {{ $candidate['name'] }}</span>
              <span class="text-slate-500">{{ number_format($candidate['votes']) }} ({{ $candidate['pct'] }}%)</span>
            </div>
            <div class="h-2.5 bg-slate-100 rounded-full overflow-hidden">
              <div class="h-full rounded-full {{ $candidate['color'] }}" style="width: {{ $candidate['pct'] }}%"></div>
            </div>
          </div>
        @endforeach
      </div>
    </div>

    {{-- Election status --}}
    <div class="bg-white rounded-xl border border-slate-200 p-5">
      <h2 class="font-semibold text-slate-800 mb-5">Status Pemilihan</h2>
      <div class="space-y-4 text-sm">
        <div class="flex items-center justify-between">
          <span class="text-slate-500">Status</span>
          @if ($election['status'] === 'active')
            <span class="bg-green-50 text-green-700 px-2.5 py-1 rounded-full text-xs font-medium">Berlangsung</span>
          @elseif ($election['status'] === 'upcoming')
            <span class="bg-blue-50 text-blue-700 px-2.5 py-1 rounded-full text-xs font-medium">Akan Datang</span>
          @else
            <span class="bg-slate-100 text-slate-600 px-2.5 py-1 rounded-full text-xs font-medium">Selesai</span>
          @endif
        </div>
        <div class="flex items-center justify-between">
          <span class="text-slate-500">Mulai</span>
          <span class="font-medium text-slate-700">{{ $election['start'] }}</span>
        </div>
        <div class="flex items-center justify-between">
          <span class="text-slate-500">Berakhir</span>
          <span class="font-medium text-slate-700">{{ $election['end'] }}</span>
        </div>
        @if ($election['status'] === 'active')
          <div class="pt-3 border-t border-slate-100">
            <p class="text-slate-500 mb-2">Sisa waktu</p>
            <p id="countdown" class="text-2xl font-bold text-brand-700">{{ $election['remaining'] }}</p>
          </div>
        @endif
      </div>
      <a href="{{ route('admin.results') }}"
         class="block w-full mt-5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium py-2.5 rounded-lg transition text-center">
        Lihat Detail Hasil
      </a>
    </div>

  </div>

  {{-- Recent activity --}}
  <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
      <h2 class="font-semibold text-slate-800">Aktivitas Pemilihan Terbaru</h2>
      <a href="{{ route('admin.voters') }}" class="text-xs text-brand-600 hover:underline">Lihat semua</a>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-500 text-left">
          <tr>
            <th class="px-5 py-3 font-medium">ID Pemilih</th>
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
                @if ($activity['status'] === 'success')
                  <span class="bg-green-50 text-green-700 px-2 py-0.5 rounded-full text-xs">Berhasil</span>
                @else
                  <span class="bg-red-50 text-red-700 px-2 py-0.5 rounded-full text-xs">Gagal</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="px-5 py-8 text-center text-slate-400">Belum ada aktivitas.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

@endsection

@push('scripts')
<script>
  // Live countdown — synced to election end time from server
  @if ($election['status'] === 'active' && isset($election['end_timestamp']))
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
