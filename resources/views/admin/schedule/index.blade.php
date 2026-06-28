@extends('layouts.admin')

@section('title', 'Jadwal Pemilihan')

@section('content')
<div class="p-6 max-w-4xl mx-auto space-y-6">

  {{-- Header --}}
  <div class="flex items-start justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-slate-800">Jadwal Pemilihan</h1>
      <p class="text-slate-500 text-sm mt-1">Kelola periode dan jadwal pelaksanaan pemilihan</p>
    </div>
    <button onclick="openModal('modalBuatPeriode')"
            class="shrink-0 inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
      </svg>
      Buat Periode
    </button>
  </div>

  {{-- Flash messages --}}
  @if(session('success'))
  <div class="rounded-xl bg-green-50 border border-green-200 text-green-800 px-4 py-3 flex items-center gap-3">
    <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    {{ session('success') }}
  </div>
  @endif
  @if(session('error'))
  <div class="rounded-xl bg-red-50 border border-red-200 text-red-800 px-4 py-3 flex items-center gap-3">
    <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    {{ session('error') }}
  </div>
  @endif

  {{-- ── PERIODE PEMILIHAN ─────────────────────────────────────────────────── --}}
  <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
      <div class="w-9 h-9 rounded-lg bg-brand-100 text-brand-600 flex items-center justify-center shrink-0">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
        </svg>
      </div>
      <div>
        <h2 class="font-bold text-slate-800">Periode Pemilihan</h2>
        <p class="text-xs text-slate-400">Pilih atau buat periode untuk mengatur jadwal</p>
      </div>
    </div>

    @if($periods->isEmpty())
    <div class="px-6 py-12 text-center">
      <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-3">
        <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
      </div>
      <p class="font-semibold text-slate-600">Belum ada periode pemilihan</p>
      <p class="text-slate-400 text-sm mt-1">Buat periode terlebih dahulu sebelum mengatur jadwal</p>
      <button onclick="openModal('modalBuatPeriode')"
              class="mt-4 inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Buat Periode Baru
      </button>
    </div>
    @else
    <div class="divide-y divide-slate-100">
      @foreach($periods as $period)
      @php $isActive = $period->is_active; @endphp
      <div class="px-6 py-4 flex items-center gap-4 {{ $isActive ? 'bg-brand-50/40' : '' }}">
        <div class="shrink-0">
          @if($isActive)
            <span class="relative flex w-3 h-3">
              <span class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-green-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
            </span>
          @else
            <span class="inline-flex w-3 h-3 rounded-full bg-slate-300"></span>
          @endif
        </div>
        <div class="flex-1 min-w-0">
          <div class="flex items-center gap-2 flex-wrap">
            <p class="font-semibold text-slate-800 text-sm">{{ $period->name }}</p>
            @if($isActive)
              <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">Aktif</span>
            @endif
          </div>
          <p class="text-xs text-slate-400 mt-0.5">
            Tahun {{ $period->year }}
            @if($period->description) &bull; {{ Str::limit($period->description, 60) }}@endif
          </p>
        </div>
        <div class="shrink-0 flex items-center gap-2">
          @if(!$isActive)
          <form action="{{ route('admin.schedule.periods.activate', $period) }}" method="POST">
            @csrf @method('PATCH')
            <button type="submit" class="text-xs font-semibold text-brand-600 hover:text-brand-700 border border-brand-200 hover:border-brand-400 px-3 py-1.5 rounded-lg transition">
              Aktifkan
            </button>
          </form>
          @endif
          <button onclick="openEditPeriode({{ $period->id }}, '{{ addslashes($period->name) }}', {{ $period->year }}, '{{ addslashes($period->description ?? '') }}')"
                  class="text-xs font-medium text-slate-500 hover:text-slate-700 border border-slate-200 hover:border-slate-300 px-3 py-1.5 rounded-lg transition">
            Edit
          </button>
          @if(!$isActive)
          <form action="{{ route('admin.schedule.periods.destroy', $period) }}" method="POST"
                onsubmit="return confirm('Hapus periode ini?')">
            @csrf @method('DELETE')
            <button type="submit" class="text-xs font-medium text-red-500 hover:text-red-600 border border-red-200 hover:border-red-300 px-3 py-1.5 rounded-lg transition">
              Hapus
            </button>
          </form>
          @endif
        </div>
      </div>
      @endforeach
    </div>
    @endif
  </div>

  {{-- ── RINGKASAN JADWAL ──────────────────────────────────────────────────── --}}
  @if($activePeriod)
  @php
    $phases = [
      [
        'key'      => 'dpt',
        'schedule' => $dpt,
        'label'    => 'Pendaftaran DPT',
        'desc'     => 'Periode alumni mendaftarkan data wajah',
        'icon'     => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 17h.01',
        'color'    => 'blue',
        'modal'    => 'modalJadwalDpt',
      ],
      [
        'key'      => 'election',
        'schedule' => $election,
        'label'    => 'Pemilihan Calon',
        'desc'     => 'Periode pelaksanaan voting',
        'icon'     => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
        'color'    => 'indigo',
        'modal'    => 'modalJadwalElection',
      ],
    ];
    $cmap = [
      'blue'  => ['iconBg'=>'bg-blue-100 text-blue-600',  'run'=>'bg-blue-100 text-blue-700',   'wait'=>'bg-amber-100 text-amber-700','done'=>'bg-slate-100 text-slate-500','none'=>'bg-slate-100 text-slate-500','btn'=>'bg-blue-600 hover:bg-blue-700',  'bar'=>'bg-blue-500',  'ring'=>'focus:ring-blue-200',  'border'=>'border-blue-200'],
      'indigo'=> ['iconBg'=>'bg-indigo-100 text-indigo-600','run'=>'bg-indigo-100 text-indigo-700','wait'=>'bg-amber-100 text-amber-700','done'=>'bg-slate-100 text-slate-500','none'=>'bg-slate-100 text-slate-500','btn'=>'bg-indigo-600 hover:bg-indigo-700','bar'=>'bg-indigo-500','ring'=>'focus:ring-indigo-200','border'=>'border-indigo-200'],
    ];
  @endphp

  <div class="space-y-3">
    <div class="flex items-center gap-3">
      <h2 class="font-bold text-slate-700 text-sm uppercase tracking-wide">
        Ringkasan Jadwal — <span class="text-brand-600 normal-case font-semibold">{{ $activePeriod->name }}</span>
      </h2>
      <div class="h-px flex-1 bg-slate-200"></div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      @foreach($phases as $p)
        @php
          $s  = $p['schedule'];
          $c  = $cmap[$p['color']];
          $st = $s->status;
          $dot = match($st) {'berlangsung'=>'bg-green-500','selesai'=>'bg-slate-400','belum_dimulai'=>'bg-amber-400',default=>'bg-slate-300'};
          $badgeCls = match($st) {'berlangsung'=>$c['run'],'selesai'=>$c['done'],'belum_dimulai'=>$c['wait'],default=>$c['none']};
        @endphp
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">

          {{-- Card top --}}
          <div class="flex items-center gap-3 px-5 py-4 border-b border-slate-100">
            <div class="w-10 h-10 rounded-xl {{ $c['iconBg'] }} flex items-center justify-center shrink-0">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $p['icon'] }}"/>
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <p class="font-bold text-slate-800 text-sm">{{ $s->name }}</p>
              <p class="text-xs text-slate-400">{{ $p['desc'] }}</p>
            </div>
            <span class="shrink-0 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $badgeCls }}">
              <span class="w-1.5 h-1.5 rounded-full {{ $dot }} {{ $st==='berlangsung' ? 'animate-pulse' : '' }}"></span>
              {{ $s->status_label }}
            </span>
          </div>

          {{-- Dates --}}
          <div class="px-5 py-4 flex-1 space-y-1">
            @if($s->start_date && $s->end_date)
              <div class="flex items-center gap-2 text-sm">
                <span class="text-slate-400 w-16 shrink-0 text-xs">Mulai</span>
                <span class="font-medium text-slate-700">{{ $s->start_date->translatedFormat('d F Y, H:i') }} WIB</span>
              </div>
              <div class="flex items-center gap-2 text-sm">
                <span class="text-slate-400 w-16 shrink-0 text-xs">Selesai</span>
                <span class="font-medium text-slate-700">{{ $s->end_date->translatedFormat('d F Y, H:i') }} WIB</span>
              </div>
              @php
                $total   = max(1, $s->end_date->diffInMinutes($s->start_date));
                $elapsed = max(0, $s->start_date->diffInMinutes(now(), false));
                $pct     = min(100, max(0, round($elapsed / $total * 100)));
                $barCol  = match($st){'berlangsung'=>$c['bar'],'selesai'=>'bg-slate-400',default=>'bg-slate-200'};
              @endphp
              <div class="pt-2">
                <div class="flex justify-between text-xs text-slate-400 mb-1">
                  <span>{{ $st === 'berlangsung' ? 'Berlangsung' : ($st === 'selesai' ? 'Selesai' : 'Belum dimulai') }}</span>
                  <span>{{ $pct }}%</span>
                </div>
                <div class="h-1.5 rounded-full bg-slate-100 overflow-hidden">
                  <div class="h-full rounded-full {{ $barCol }} transition-all" style="width:{{ $pct }}%"></div>
                </div>
              </div>
              @if($st === 'berlangsung')
              <p class="text-xs text-green-600 pt-1">Berakhir {{ $s->end_date->diffForHumans() }}</p>
              @elseif($st === 'belum_dimulai')
              <p class="text-xs text-amber-600 pt-1">Dimulai {{ $s->start_date->diffForHumans() }}</p>
              @endif
            @else
              <p class="text-sm text-slate-400 py-2">Jadwal belum diatur</p>
            @endif
          </div>

          {{-- Button Atur Jadwal --}}
          <div class="px-5 py-3 border-t border-slate-100 bg-slate-50/50">
            <button onclick="openJadwal('{{ $p['modal'] }}', {{ $s->id }}, '{{ addslashes($s->name) }}', '{{ $s->start_date ? $s->start_date->format('Y-m-d\TH:i') : '' }}', '{{ $s->end_date ? $s->end_date->format('Y-m-d\TH:i') : '' }}', '{{ addslashes($s->description ?? '') }}')"
                    class="w-full {{ $c['btn'] }} text-white text-sm font-semibold py-2 rounded-lg transition flex items-center justify-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
              </svg>
              Atur Jadwal
            </button>
          </div>
        </div>
      @endforeach
    </div>
  </div>
  @endif

</div>

{{-- ═══════════════════════ MODALS ═══════════════════════ --}}

{{-- Modal: Buat Periode --}}
<div id="modalBuatPeriode" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
  <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('modalBuatPeriode')"></div>
  <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
      <h3 class="font-bold text-slate-800">Buat Periode Pemilihan</h3>
      <button onclick="closeModal('modalBuatPeriode')" class="text-slate-400 hover:text-slate-600 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
    <form action="{{ route('admin.schedule.periods.store') }}" method="POST" class="px-6 py-5 space-y-4">
      @csrf
      <div>
        <label class="block text-xs font-medium text-slate-600 mb-1.5">Nama Pemilihan <span class="text-red-500">*</span></label>
        <input type="text" name="name" value="{{ old('name') }}" autofocus
               class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-200 focus:border-transparent transition"
               placeholder="Contoh: Pemilihan Umum BEM 2026" required>
        @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
      </div>
      <div>
        <label class="block text-xs font-medium text-slate-600 mb-1.5">Tahun <span class="text-red-500">*</span></label>
        <input type="number" name="year" value="{{ old('year', date('Y')) }}" min="2000" max="2100"
               class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-200 focus:border-transparent transition"
               required>
        @error('year')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
      </div>
      <div>
        <label class="block text-xs font-medium text-slate-600 mb-1.5">Deskripsi <span class="text-slate-400">(opsional)</span></label>
        <textarea name="description" rows="2"
                  class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-200 focus:border-transparent transition resize-none"
                  placeholder="Keterangan singkat...">{{ old('description') }}</textarea>
      </div>
      <p class="text-xs text-slate-400">Periode baru akan otomatis diaktifkan.</p>
      <div class="flex gap-3 pt-1">
        <button type="button" onclick="closeModal('modalBuatPeriode')"
                class="flex-1 border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-semibold py-2.5 rounded-lg transition">Batal</button>
        <button type="submit"
                class="flex-1 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold py-2.5 rounded-lg transition">Buat &amp; Aktifkan</button>
      </div>
    </form>
  </div>
</div>

{{-- Modal: Edit Periode --}}
<div id="modalEditPeriode" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
  <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('modalEditPeriode')"></div>
  <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
      <h3 class="font-bold text-slate-800">Edit Periode Pemilihan</h3>
      <button onclick="closeModal('modalEditPeriode')" class="text-slate-400 hover:text-slate-600 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
    <form id="formEditPeriode" method="POST" class="px-6 py-5 space-y-4">
      @csrf @method('PUT')
      <div>
        <label class="block text-xs font-medium text-slate-600 mb-1.5">Nama Pemilihan <span class="text-red-500">*</span></label>
        <input type="text" name="name" id="editPeriodeName"
               class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-200 focus:border-transparent transition" required>
      </div>
      <div>
        <label class="block text-xs font-medium text-slate-600 mb-1.5">Tahun <span class="text-red-500">*</span></label>
        <input type="number" name="year" id="editPeriodeYear" min="2000" max="2100"
               class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-200 focus:border-transparent transition" required>
      </div>
      <div>
        <label class="block text-xs font-medium text-slate-600 mb-1.5">Deskripsi <span class="text-slate-400">(opsional)</span></label>
        <textarea name="description" id="editPeriodeDesc" rows="2"
                  class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-200 focus:border-transparent transition resize-none"></textarea>
      </div>
      <div class="flex gap-3 pt-1">
        <button type="button" onclick="closeModal('modalEditPeriode')"
                class="flex-1 border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-semibold py-2.5 rounded-lg transition">Batal</button>
        <button type="submit"
                class="flex-1 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold py-2.5 rounded-lg transition">Simpan</button>
      </div>
    </form>
  </div>
</div>

{{-- Modal: Atur Jadwal (shared untuk DPT & Election) --}}
@foreach([['id'=>'modalJadwalDpt','title'=>'Atur Jadwal Pendaftaran DPT','color'=>'blue'],['id'=>'modalJadwalElection','title'=>'Atur Jadwal Pemilihan Calon','color'=>'indigo']] as $m)
@php
  $mRing = $m['color']==='blue' ? 'focus:ring-blue-200' : 'focus:ring-indigo-200';
  $mBtn  = $m['color']==='blue' ? 'bg-blue-600 hover:bg-blue-700' : 'bg-indigo-600 hover:bg-indigo-700';
@endphp
<div id="{{ $m['id'] }}" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
  <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('{{ $m['id'] }}')"></div>
  <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
      <h3 class="font-bold text-slate-800">{{ $m['title'] }}</h3>
      <button onclick="closeModal('{{ $m['id'] }}')" class="text-slate-400 hover:text-slate-600 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
    <form id="form{{ $m['id'] }}" method="POST" class="px-6 py-5 space-y-4">
      @csrf @method('PUT')
      <div>
        <label class="block text-xs font-medium text-slate-600 mb-1.5">Nama Jadwal</label>
        <input type="text" name="name" id="{{ $m['id'] }}Name"
               class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 {{ $mRing }} focus:border-transparent transition"
               required>
      </div>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-medium text-slate-600 mb-1.5">Tanggal &amp; Jam Mulai <span class="text-red-500">*</span></label>
          <input type="datetime-local" name="start_date" id="{{ $m['id'] }}Start"
                 class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 {{ $mRing }} focus:border-transparent transition"
                 required>
        </div>
        <div>
          <label class="block text-xs font-medium text-slate-600 mb-1.5">Tanggal &amp; Jam Selesai <span class="text-red-500">*</span></label>
          <input type="datetime-local" name="end_date" id="{{ $m['id'] }}End"
                 class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 {{ $mRing }} focus:border-transparent transition"
                 required>
        </div>
      </div>
      <div>
        <label class="block text-xs font-medium text-slate-600 mb-1.5">Keterangan <span class="text-slate-400">(opsional)</span></label>
        <textarea name="description" id="{{ $m['id'] }}Desc" rows="2"
                  class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 {{ $mRing }} focus:border-transparent transition resize-none"
                  placeholder="Catatan tambahan..."></textarea>
      </div>
      <div class="flex gap-3 pt-1">
        <button type="button" onclick="closeModal('{{ $m['id'] }}')"
                class="flex-1 border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-semibold py-2.5 rounded-lg transition">Batal</button>
        <button type="submit"
                class="flex-1 {{ $mBtn }} text-white text-sm font-semibold py-2.5 rounded-lg transition flex items-center justify-center gap-2">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
          </svg>
          Simpan Jadwal
        </button>
      </div>
    </form>
  </div>
</div>
@endforeach

@push('scripts')
<script>
function openEditPeriode(id, name, year, desc) {
  document.getElementById('formEditPeriode').action = '/admin/schedule/periods/' + id;
  document.getElementById('editPeriodeName').value  = name;
  document.getElementById('editPeriodeYear').value  = year;
  document.getElementById('editPeriodeDesc').value  = desc;
  openModal('modalEditPeriode');
}

function openJadwal(modalId, scheduleId, name, start, end, desc) {
  document.getElementById('form' + modalId).action = '/admin/schedule/schedules/' + scheduleId;
  document.getElementById(modalId + 'Name').value  = name;
  document.getElementById(modalId + 'Start').value = start;
  document.getElementById(modalId + 'End').value   = end;
  document.getElementById(modalId + 'Desc').value  = desc;
  openModal(modalId);
}
</script>
@endpush

@endsection
