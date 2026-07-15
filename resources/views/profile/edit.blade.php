@extends('layouts.admin')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@section('content')
@php
  $alumni = $user->alumni;
  $voter  = $alumni?->voter;
@endphp

<div class="max-w-3xl mx-auto space-y-6">

  {{-- Header profil --}}
  <div class="bg-white rounded-2xl border border-slate-200 p-6 flex items-center gap-5">
    <div class="w-16 h-16 rounded-2xl bg-brand-600 text-white flex items-center justify-center text-2xl font-extrabold shrink-0">
      {{ strtoupper(substr($user->name, 0, 1)) }}
    </div>
    <div class="min-w-0">
      <h2 class="text-xl font-bold text-slate-900 truncate">{{ $user->name }}</h2>
      <p class="text-sm text-slate-500 mt-0.5">{{ $user->email }}</p>
      <div class="flex flex-wrap gap-2 mt-2">
        @foreach($user->roles as $role)
          <span class="inline-block text-xs font-semibold bg-brand-100 text-brand-700 px-2.5 py-0.5 rounded-full capitalize">
            {{ $role->name }}
          </span>
        @endforeach
        @if($voter)
          <span class="inline-block text-xs font-semibold px-2.5 py-0.5 rounded-full
            {{ $voter->has_voted ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
            {{ $voter->has_voted ? '✓ Sudah Memilih' : '○ Belum Memilih' }}
          </span>
        @endif
      </div>
    </div>
  </div>

  {{-- Flash alumni-updated --}}
  @if(session('status') === 'alumni-updated')
    <div id="flashAlumni" class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-xl">
      <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      Data pribadi berhasil diperbarui.
    </div>
  @endif

  {{-- Data Alumni --}}
  @if($alumni)
  <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
      <div>
        <h3 class="font-semibold text-slate-900">Data Alumni</h3>
        <p class="text-xs text-slate-400 mt-0.5">Informasi akademik dari data resmi kampus</p>
      </div>
      <button onclick="openAlumniEdit()"
              class="flex items-center gap-1.5 text-sm font-medium text-brand-600 hover:text-brand-700 border border-brand-200 hover:border-brand-400 bg-brand-50 hover:bg-brand-100 px-3 py-1.5 rounded-lg transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>
        Edit Data Pribadi
      </button>
    </div>
    <div class="p-6 grid sm:grid-cols-2 gap-x-8 gap-y-5">
      @php
        $fields = [
          ['label' => 'NIM',              'value' => $alumni->nim,             'locked' => true],
          ['label' => 'Nama Lengkap',     'value' => $alumni->name,            'locked' => true],
          ['label' => 'Fakultas',         'value' => $alumni->faculty,         'locked' => true],
          ['label' => 'Program Studi',    'value' => $alumni->department,      'locked' => true],
          ['label' => 'Tahun Lulus',      'value' => $alumni->graduation_year, 'locked' => true],
          ['label' => 'Tempat Lahir',     'value' => $alumni->place_of_birth],
          ['label' => 'Tanggal Lahir',    'value' => $alumni->date_of_birth?->translatedFormat('d F Y')],
          ['label' => 'Email',            'value' => $user->email,             'locked' => true, 'note' => 'Terhubung dengan akun'],
          ['label' => 'No. Telepon',      'value' => $alumni->phone],
          ['label' => 'Alamat',           'value' => $alumni->address, 'full' => true],
        ];
      @endphp
      @foreach($fields as $f)
      <div class="{{ ($f['full'] ?? false) ? 'sm:col-span-2' : '' }}">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1 flex items-center gap-1.5">
          {{ $f['label'] }}
          @if($f['locked'] ?? false)
            <svg class="w-3 h-3 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
            </svg>
          @endif
        </p>
        <p class="text-sm font-medium text-slate-800">{{ $f['value'] ?: '—' }}</p>
        @if(isset($f['note']))
          <p class="text-xs text-slate-400 mt-0.5">{{ $f['note'] }}</p>
        @endif
      </div>
      @endforeach
    </div>
  </div>

  {{-- Modal: Edit Data Pribadi Alumni --}}
  <div id="modalAlumniEdit" class="fixed inset-0 z-50" style="display:none!important">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/50" onclick="closeAlumniEdit()"></div>
    {{-- Card: memenuhi layar dikurangi margin, scroll di dalam card sendiri --}}
    <div class="absolute inset-4 sm:inset-y-8 sm:inset-x-0 sm:mx-auto sm:max-w-md
                bg-white rounded-2xl shadow-2xl overflow-y-auto">

      {{-- Header — sticky di atas --}}
      <div class="sticky top-0 z-10 bg-white flex items-center justify-between px-6 py-4 border-b border-slate-100">
        <div>
          <h3 class="font-semibold text-slate-900">Edit Data Pribadi</h3>
          <p class="text-xs text-slate-400 mt-0.5">Data akademik tidak dapat diubah di sini</p>
        </div>
        <button onclick="closeAlumniEdit()" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-400">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      <form method="POST" action="{{ route('profile.alumni-data') }}">
        @csrf @method('PATCH')

        <div class="px-6 py-5 space-y-4">

          {{-- Email — read only --}}
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">
              Email
              <span class="ml-1.5 text-xs font-normal text-brand-600 bg-brand-50 px-1.5 py-0.5 rounded">Terhubung dengan akun</span>
            </label>
            <input type="email" value="{{ $user->email }}" disabled
                   class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm text-slate-500 cursor-not-allowed" />
            <p class="text-xs text-slate-400 mt-1">Untuk mengubah email, gunakan menu Pengaturan Akun.</p>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1.5">Tempat Lahir</label>
              <input type="text" name="place_of_birth" value="{{ old('place_of_birth', $alumni->place_of_birth) }}"
                     placeholder="Kota kelahiran"
                     class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1.5">Tanggal Lahir</label>
              <input type="date" name="date_of_birth"
                     value="{{ old('date_of_birth', $alumni->date_of_birth?->format('Y-m-d')) }}"
                     max="{{ date('Y-m-d') }}"
                     class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">No. Telepon</label>
            <input type="text" name="phone" value="{{ old('phone', $alumni->phone) }}"
                   placeholder="Contoh: 08123456789"
                   class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
          </div>

          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Alamat</label>
            <textarea name="address" rows="3" placeholder="Alamat lengkap"
                      class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent resize-none">{{ old('address', $alumni->address) }}</textarea>
          </div>

        </div>

        {{-- Footer — sticky di bawah --}}
        <div class="sticky bottom-0 bg-white border-t border-slate-100 px-6 py-4 flex justify-end gap-3">
          <button type="button" onclick="closeAlumniEdit()"
                  class="px-4 py-2 text-sm text-slate-600 border border-slate-300 rounded-xl hover:bg-slate-50 transition">Batal</button>
          <button type="submit"
                  class="px-5 py-2 text-sm font-medium text-white bg-brand-600 hover:bg-brand-700 rounded-xl transition">Simpan</button>
        </div>

      </form>
    </div>
  </div>
  @endif

  {{-- Validasi error alumni-data --}}
  @if($errors->any() && old('_method') === 'PATCH' && request()->routeIs('profile.alumni-data'))
    <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl">
      <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      <ul class="list-disc list-inside space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

  {{-- Status DPT --}}
  @if($voter)
  <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100">
      <h3 class="font-semibold text-slate-900">Status DPT</h3>
    </div>
    <div class="p-6 grid sm:grid-cols-3 gap-4">
      <div class="bg-slate-50 rounded-xl p-4 text-center">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Status</p>
        <span class="inline-block text-sm font-bold {{ $voter->is_active ? 'text-green-600' : 'text-red-500' }}">
          {{ $voter->is_active ? 'Aktif' : 'Tidak Aktif' }}
        </span>
      </div>
      <div class="bg-slate-50 rounded-xl p-4 text-center">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Rekam Wajah</p>
        <span class="inline-block text-sm font-bold {{ $voter->face_descriptor ? 'text-green-600' : 'text-amber-500' }}">
          {{ $voter->face_descriptor ? 'Terdaftar' : 'Belum' }}
        </span>
      </div>
      <div class="bg-slate-50 rounded-xl p-4 text-center">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Voting</p>
        <span class="inline-block text-sm font-bold {{ $voter->has_voted ? 'text-green-600' : 'text-slate-500' }}">
          {{ $voter->has_voted ? 'Sudah Memilih' : 'Belum Memilih' }}
        </span>
      </div>
    </div>
  </div>
  @endif

</div>
@endsection

@push('scripts')
<script>
  function openAlumniEdit() {
    document.getElementById('modalAlumniEdit').style.cssText = 'display:flex!important';
    document.body.style.overflow = 'hidden';
  }
  function closeAlumniEdit() {
    document.getElementById('modalAlumniEdit').style.cssText = 'display:none!important';
    document.body.style.overflow = '';
  }

  const flash = document.getElementById('flashAlumni');
  if (flash) setTimeout(() => flash.remove(), 4000);

  @if($errors->any())
    document.addEventListener('DOMContentLoaded', () => openAlumniEdit());
  @endif
</script>
@endpush
