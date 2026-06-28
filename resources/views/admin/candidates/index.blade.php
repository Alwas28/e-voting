@extends('layouts.admin')

@section('title', 'Kandidat')

@push('styles')
<style>
  .profile-content h1 { font-size:1.2rem; font-weight:700; margin-bottom:.5rem; }
  .profile-content h2 { font-size:1.05rem; font-weight:700; margin-bottom:.5rem; }
  .profile-content p  { margin-bottom:.75rem; line-height:1.7; }
  .profile-content ul { list-style:disc; padding-left:1.25rem; margin-bottom:.75rem; }
  .profile-content ol { list-style:decimal; padding-left:1.25rem; margin-bottom:.75rem; }
  .profile-content li { margin-bottom:.25rem; }
  .profile-content strong { font-weight:600; }
  .profile-content em { font-style:italic; }
  .profile-content a  { color:#4f46e5; text-decoration:underline; }
</style>
@endpush

@section('content')
<div class="p-6 max-w-6xl mx-auto space-y-6">

  {{-- Header --}}
  <div class="flex items-start justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-slate-800">Kandidat</h1>
      <p class="text-slate-500 text-sm mt-1">
        @if($activePeriod)
          Periode aktif: <span class="font-semibold text-brand-600">{{ $activePeriod->name }}</span>
        @else
          Belum ada periode aktif
        @endif
      </p>
    </div>
    @if($activePeriod && auth()->user()->hasPermission('candidates.create'))
    <a href="{{ route('admin.candidates.create') }}"
       class="shrink-0 inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
      </svg>
      Tambah Kandidat
    </a>
    @endif
  </div>

  {{-- Flash --}}
  @if(session('success'))
  <div class="rounded-xl bg-green-50 border border-green-200 text-green-800 px-4 py-3 flex items-center gap-3">
    <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    {{ session('success') }}
  </div>
  @endif

  @if(!$activePeriod)
  <div class="bg-white rounded-2xl border border-slate-200 shadow-sm px-6 py-14 text-center">
    <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-3">
      <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
      </svg>
    </div>
    <p class="font-semibold text-slate-600">Belum ada periode pemilihan aktif</p>
    <p class="text-slate-400 text-sm mt-1">Aktifkan periode terlebih dahulu di menu Jadwal Pemilihan</p>
    <a href="{{ route('admin.schedule') }}" class="mt-4 inline-flex items-center gap-2 text-brand-600 hover:text-brand-700 text-sm font-semibold">
      Ke Jadwal Pemilihan
      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
      </svg>
    </a>
  </div>

  @elseif($candidates->isEmpty())
  <div class="bg-white rounded-2xl border border-slate-200 shadow-sm px-6 py-14 text-center">
    <div class="w-14 h-14 rounded-2xl bg-brand-50 flex items-center justify-center mx-auto mb-3">
      <svg class="w-7 h-7 text-brand-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
      </svg>
    </div>
    <p class="font-semibold text-slate-600">Belum ada kandidat</p>
    <p class="text-slate-400 text-sm mt-1">Tambahkan kandidat untuk periode <span class="font-medium text-slate-600">{{ $activePeriod->name }}</span></p>
    @if(auth()->user()->hasPermission('candidates.create'))
    <a href="{{ route('admin.candidates.create') }}"
       class="mt-4 inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
      </svg>
      Tambah Kandidat Pertama
    </a>
    @endif
  </div>

  @else
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
    @foreach($candidates as $c)
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col {{ !$c->is_active ? 'opacity-60' : '' }}">

      <div class="h-1.5 bg-brand-600"></div>

      <div class="px-5 pt-5 pb-3 flex flex-col items-center text-center gap-3 flex-1">

        {{-- Nomor urut --}}
        <div class="w-10 h-10 rounded-full bg-brand-600 text-white flex items-center justify-center font-bold text-lg shadow">
          {{ $c->number }}
        </div>

        {{-- Foto --}}
        <div class="w-24 h-24 rounded-full border-4 border-slate-100 shadow overflow-hidden bg-slate-100 flex items-center justify-center">
          @if($c->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($c->photo))
            <img src="{{ $c->photo_url }}" alt="{{ $c->name }}" class="w-full h-full object-cover">
          @else
            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
          @endif
        </div>

        {{-- Nama --}}
        <div>
          <h3 class="font-bold text-slate-800 text-base leading-tight">{{ $c->name }}</h3>
          @if($c->faculty || $c->department)
          <p class="text-xs text-slate-400 mt-0.5">{{ implode(' — ', array_filter([$c->faculty, $c->department])) }}</p>
          @endif
          @if($c->alumni)
          <p class="text-xs text-brand-500 mt-0.5">NIM: {{ $c->alumni->nim }}</p>
          @endif
        </div>

        {{-- Status --}}
        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold
          {{ $c->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
          <span class="w-1.5 h-1.5 rounded-full {{ $c->is_active ? 'bg-green-500' : 'bg-slate-400' }}"></span>
          {{ $c->is_active ? 'Aktif' : 'Nonaktif' }}
        </span>

        {{-- Visi singkat --}}
        @if($c->vision)
        <div class="w-full bg-slate-50 rounded-xl px-3 py-2.5 text-left">
          <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Visi</p>
          <p class="text-xs text-slate-600 leading-relaxed line-clamp-2">{{ $c->vision }}</p>
        </div>
        @endif

        {{-- Lihat profil --}}
        @if($c->profile)
        <button onclick="lihatProfil({{ $c->id }})"
                class="w-full text-xs font-semibold text-brand-600 hover:text-brand-700 border border-brand-200 hover:border-brand-400 py-2 rounded-lg transition flex items-center justify-center gap-1.5">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
          </svg>
          Lihat Profil Lengkap
        </button>
        @endif
      </div>

      {{-- Actions --}}
      @if(auth()->user()->hasPermission('candidates.edit') || auth()->user()->hasPermission('candidates.delete'))
      <div class="px-5 py-3 border-t border-slate-100 bg-slate-50/50 flex gap-2">
        @if(auth()->user()->hasPermission('candidates.edit'))
        <a href="{{ route('admin.candidates.edit', $c) }}"
           class="flex-1 text-xs font-semibold text-slate-600 hover:text-slate-800 border border-slate-200 hover:border-slate-300 bg-white py-2 rounded-lg transition flex items-center justify-center gap-1.5">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
          </svg>
          Edit
        </a>
        @endif
        @if(auth()->user()->hasPermission('candidates.delete'))
        <form action="{{ route('admin.candidates.destroy', $c) }}" method="POST"
              onsubmit="return confirm('Hapus kandidat {{ addslashes($c->name) }}?')">
          @csrf @method('DELETE')
          <button type="submit"
                  class="text-xs font-semibold text-red-500 hover:text-red-600 border border-red-200 hover:border-red-300 bg-white px-3 py-2 rounded-lg transition">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
          </button>
        </form>
        @endif
      </div>
      @endif
    </div>
    @endforeach
  </div>
  @endif

</div>

{{-- Data profil tersembunyi --}}
@foreach($candidates as $c)
  @if($c->profile)
  <script type="application/json" id="profile-{{ $c->id }}">{{ json_encode(['name'=>$c->name,'number'=>$c->number,'profile'=>$c->profile]) }}</script>
  @endif
@endforeach

{{-- Modal Profil --}}
<div id="modalProfil" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
  <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('modalProfil')"></div>
  <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[85vh] flex flex-col">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 shrink-0">
      <h3 class="font-bold text-slate-800" id="profilTitle">Profil Kandidat</h3>
      <button onclick="closeModal('modalProfil')" class="text-slate-400 hover:text-slate-600 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
    <div class="overflow-y-auto flex-1 px-6 py-5">
      <div id="profilContent" class="profile-content text-slate-700 text-sm leading-relaxed"></div>
    </div>
  </div>
</div>

@push('scripts')
<script>
function lihatProfil(id) {
  const el = document.getElementById('profile-' + id);
  if (!el) return;
  const data = JSON.parse(el.textContent);
  document.getElementById('profilTitle').textContent = 'No. ' + data.number + ' — ' + data.name;
  document.getElementById('profilContent').innerHTML = data.profile;
  openModal('modalProfil');
}
</script>
@endpush

@endsection
