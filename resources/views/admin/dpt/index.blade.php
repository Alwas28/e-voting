@extends('layouts.admin')

@section('title', 'Pendaftaran DPT')
@section('page-title', 'Pendaftaran DPT')

@section('content')

@if(isset($error))
  <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-4 rounded-xl">
    <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ $error }}
  </div>
@else

  {{-- Flash --}}
  @if(session('success'))
    <div id="flashMsg" class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-xl">
      <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      {{ session('success') }}
    </div>
  @endif

  {{-- Stat cards --}}
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="bg-white rounded-xl border border-slate-200 p-5">
      <p class="text-sm text-slate-500">Total Alumni Aktif</p>
      <p class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($stats['total']) }}</p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5">
      <p class="text-sm text-slate-500">Sudah Daftar DPT</p>
      <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format($stats['registered']) }}</p>
      @if($stats['total'] > 0)
        <p class="text-xs text-slate-400 mt-0.5">{{ round($stats['registered'] / $stats['total'] * 100) }}%</p>
      @endif
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5">
      <p class="text-sm text-slate-500">Wajah Terekam</p>
      <p class="text-2xl font-bold text-brand-600 mt-1">{{ number_format($stats['face_ok']) }}</p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5">
      <p class="text-sm text-slate-500">Belum Mendaftar</p>
      <p class="text-2xl font-bold text-amber-500 mt-1">{{ number_format($stats['pending']) }}</p>
    </div>
  </div>

  {{-- Progress bar --}}
  @if($stats['total'] > 0)
  <div class="bg-white rounded-xl border border-slate-200 p-5">
    <div class="flex items-center justify-between mb-2">
      <p class="text-sm font-medium text-slate-700">Progress Pendaftaran DPT</p>
      <p class="text-sm text-slate-500">{{ $stats['registered'] }} / {{ $stats['total'] }}</p>
    </div>
    <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden">
      <div class="h-full bg-brand-600 rounded-full transition-all"
           style="width: {{ round($stats['registered'] / $stats['total'] * 100) }}%"></div>
    </div>
    <div class="flex items-center justify-between mt-1.5 text-xs text-slate-400">
      <span>{{ round($stats['registered'] / $stats['total'] * 100) }}% terdaftar</span>
      <span>{{ $stats['face_ok'] }} wajah terekam</span>
    </div>
  </div>
  @endif

  {{-- Tabel alumni --}}
  <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">

    <div class="px-5 py-4 border-b border-slate-100 flex flex-col gap-3">
      <div class="flex items-center justify-between">
        <h2 class="font-semibold text-slate-800">Daftar Alumni</h2>
        <a href="{{ route('admin.alumni.index') }}"
           class="text-sm text-brand-600 hover:underline flex items-center gap-1">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
          Kelola Data Alumni
        </a>
      </div>

      <form method="GET" action="{{ route('admin.dpt.register') }}" class="flex flex-wrap gap-2">
        <div class="flex items-center bg-slate-100 rounded-lg px-3 py-2 flex-1 min-w-48">
          <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          <input type="text" name="search" value="{{ request('search') }}"
                 placeholder="Cari nama atau NIM..."
                 class="bg-transparent outline-none text-sm ml-2 w-full" />
        </div>
        <select name="faculty"
                class="border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
          <option value="">Semua Fakultas</option>
          @foreach($faculties as $f)
            <option value="{{ $f }}" {{ request('faculty') === $f ? 'selected' : '' }}>{{ $f }}</option>
          @endforeach
        </select>
        <select name="dpt"
                class="border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
          <option value="">Semua Status DPT</option>
          <option value="registered" {{ request('dpt') === 'registered' ? 'selected' : '' }}>Sudah Terdaftar</option>
          <option value="pending" {{ request('dpt') === 'pending' ? 'selected' : '' }}>Belum Terdaftar</option>
        </select>
        <button type="submit"
                class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium rounded-lg transition">
          Filter
        </button>
        @if(request()->hasAny(['search','faculty','dpt']))
          <a href="{{ route('admin.dpt.register') }}"
             class="px-4 py-2 border border-slate-300 text-slate-600 hover:bg-slate-50 text-sm rounded-lg transition">Reset</a>
        @endif
      </form>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-500 text-left">
          <tr>
            <th class="px-5 py-3 font-medium">#</th>
            <th class="px-5 py-3 font-medium">Alumni</th>
            <th class="px-5 py-3 font-medium">NIM</th>
            <th class="px-5 py-3 font-medium">Fakultas / Prodi</th>
            <th class="px-5 py-3 font-medium text-center">Status DPT</th>
            <th class="px-5 py-3 font-medium text-center">Wajah</th>
            <th class="px-5 py-3 font-medium text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse($alumni as $a)
            <tr class="hover:bg-slate-50">
              <td class="px-5 py-3 text-slate-400 text-xs">{{ $alumni->firstItem() + $loop->index }}</td>

              <td class="px-5 py-3">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 rounded-full bg-brand-100 flex items-center justify-center shrink-0">
                    @if($a->voter && $a->voter->face_photo)
                      <img src="{{ $a->voter->face_photo }}" class="w-full h-full rounded-full object-cover" />
                    @else
                      <span class="text-brand-700 text-xs font-bold">{{ $a->initials }}</span>
                    @endif
                  </div>
                  <div>
                    <p class="font-medium text-slate-800">{{ $a->name }}</p>
                    <p class="text-xs text-slate-400">{{ $a->email ?? $a->phone ?? '—' }}</p>
                  </div>
                </div>
              </td>

              <td class="px-5 py-3 font-mono text-slate-700 text-xs">{{ $a->nim }}</td>

              <td class="px-5 py-3">
                <p class="text-slate-700">{{ $a->faculty ?? '—' }}</p>
                <p class="text-xs text-slate-400">{{ $a->department }}</p>
              </td>

              <td class="px-5 py-3 text-center">
                @if($a->voter)
                  <div>
                    <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 text-xs font-medium px-2.5 py-0.5 rounded-full">
                      <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                      {{ $a->voter->voter_code }}
                    </span>
                    <p class="text-xs text-slate-400 mt-0.5">{{ $a->voter->registered_at?->format('d M Y') }}</p>
                  </div>
                @else
                  <span class="inline-flex items-center bg-slate-100 text-slate-500 text-xs font-medium px-2.5 py-0.5 rounded-full">
                    Belum Daftar
                  </span>
                @endif
              </td>

              <td class="px-5 py-3 text-center">
                @if($a->voter?->hasFace())
                  <span class="inline-flex items-center gap-1 bg-brand-50 text-brand-700 text-xs font-medium px-2.5 py-0.5 rounded-full">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/></svg>
                    Terekam
                  </span>
                @else
                  <span class="inline-flex items-center bg-amber-50 text-amber-600 text-xs font-medium px-2.5 py-0.5 rounded-full">
                    Belum
                  </span>
                @endif
              </td>

              <td class="px-5 py-3 text-center">
                <a href="{{ route('admin.dpt.register.alumni', $a) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium transition
                          {{ $a->voter?->hasFace()
                             ? 'bg-slate-100 text-slate-600 hover:bg-slate-200'
                             : 'bg-brand-600 text-white hover:bg-brand-700' }}">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/></svg>
                  {{ $a->voter?->hasFace() ? 'Perbarui' : 'Rekam Wajah' }}
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-5 py-14 text-center">
                <div class="flex flex-col items-center gap-2 text-slate-400">
                  <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0v6m0-6l-3.5 2m3.5-2l3.5 2"/></svg>
                  <p class="text-sm">Tidak ada data alumni aktif.</p>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    @if($alumni->hasPages())
      <div class="px-5 py-4 border-t border-slate-100 flex items-center justify-between text-sm text-slate-500">
        <p>Menampilkan {{ $alumni->firstItem() }}–{{ $alumni->lastItem() }} dari {{ $alumni->total() }}</p>
        <div class="flex items-center gap-1">
          @if($alumni->onFirstPage())
            <span class="px-3 py-1.5 rounded-lg text-slate-300 border border-slate-200">‹</span>
          @else
            <a href="{{ $alumni->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 transition">‹</a>
          @endif
          @foreach($alumni->getUrlRange(max(1,$alumni->currentPage()-2), min($alumni->lastPage(),$alumni->currentPage()+2)) as $page => $url)
            <a href="{{ $url }}"
               class="px-3 py-1.5 rounded-lg border transition {{ $page === $alumni->currentPage() ? 'bg-brand-600 text-white border-brand-600' : 'border-slate-200 hover:bg-slate-50' }}">
              {{ $page }}
            </a>
          @endforeach
          @if($alumni->hasMorePages())
            <a href="{{ $alumni->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 transition">›</a>
          @else
            <span class="px-3 py-1.5 rounded-lg text-slate-300 border border-slate-200">›</span>
          @endif
        </div>
      </div>
    @else
      <div class="px-5 py-3 border-t border-slate-100 text-xs text-slate-400">
        Total {{ $alumni->total() }} alumni aktif
      </div>
    @endif

  </div>

@endif

@endsection

@push('scripts')
<script>
  const flash = document.getElementById('flashMsg');
  if (flash) setTimeout(() => flash.remove(), 4000);
</script>
@endpush
