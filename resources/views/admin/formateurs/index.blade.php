@extends('layouts.admin')

@section('title', 'Tim Formatur')
@section('page-title', 'Tim Formatur')

@section('content')
@php $canManage = auth()->user()->hasPermission('settings.manage'); @endphp

{{-- Flash --}}
@if(session('success'))
<div id="flashMsg" class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-xl mb-4">
  <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  {{ session('success') }}
</div>
@endif

{{-- Toolbar --}}
<div class="flex items-center justify-between mb-5">
  <p class="text-sm text-slate-500">{{ $formateurs->count() }} anggota terdaftar</p>
  @if($canManage)
  <button onclick="openCreate()"
          class="flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
    </svg>
    Tambah Anggota
  </button>
  @endif
</div>

{{-- Grid kartu --}}
@if($formateurs->isEmpty())
<div class="bg-white rounded-2xl border border-slate-200 flex flex-col items-center justify-center py-20 text-slate-400">
  <svg class="w-14 h-14 mb-4 opacity-30" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
  </svg>
  <p class="text-sm font-medium">Belum ada anggota Tim Formatur</p>
  @if($canManage)
  <button onclick="openCreate()" class="mt-4 text-sm text-brand-600 hover:underline">Tambahkan anggota pertama</button>
  @endif
</div>
@else
<div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
  @foreach($formateurs as $fm)
  <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden flex flex-col group hover:shadow-md transition">

    {{-- Foto --}}
    <div class="relative w-full aspect-[4/3] bg-slate-100 overflow-hidden">
      @if($fm->photo_url)
        <img src="{{ $fm->photo_url }}" alt="{{ $fm->alumni->name ?? '' }}"
             class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
      @else
        <div class="w-full h-full flex items-center justify-center bg-brand-50">
          <span class="text-brand-600 font-extrabold text-5xl leading-none">
            {{ strtoupper(substr($fm->alumni->name ?? 'F', 0, 1)) }}
          </span>
        </div>
      @endif
      <span class="absolute top-2 right-2 text-xs font-bold px-2 py-0.5 rounded-full
        {{ $fm->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-200 text-slate-500' }}">
        {{ $fm->is_active ? 'Aktif' : 'Nonaktif' }}
      </span>
    </div>

    {{-- Info --}}
    <div class="p-4 flex-1 flex flex-col">
      <p class="text-xs font-bold text-brand-600 uppercase tracking-wide mb-0.5">{{ $fm->jabatan }}</p>
      <p class="font-bold text-slate-900 leading-snug">{{ $fm->alumni->name ?? '—' }}</p>
      <p class="text-xs text-slate-400 mt-0.5">
        {{ $fm->alumni->nim ?? '' }}
        @if($fm->alumni?->faculty) · {{ $fm->alumni->faculty }} @endif
      </p>
      @if($fm->deskripsi)
      <p class="text-xs text-slate-500 mt-2 leading-relaxed line-clamp-3">{{ $fm->deskripsi }}</p>
      @endif

      @if($canManage)
      <div class="mt-auto pt-4 flex items-center gap-2">
        <button onclick="openEdit(
                  {{ $fm->id }},
                  {{ $fm->alumni_id }},
                  '{{ addslashes($fm->alumni->name ?? '') }}',
                  '{{ addslashes($fm->alumni->nim ?? '') }}',
                  '{{ addslashes($fm->jabatan) }}',
                  {{ $fm->sort_order }},
                  '{{ addslashes($fm->deskripsi ?? '') }}')"
                class="flex-1 text-center text-xs font-semibold border border-slate-300 text-slate-600 hover:bg-slate-50 py-1.5 rounded-lg transition">
          Edit
        </button>
        <form method="POST" action="{{ route('admin.formateurs.toggle', $fm) }}" class="shrink-0">
          @csrf @method('PATCH')
          <button type="submit" title="{{ $fm->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                  class="p-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 transition text-slate-400 hover:text-slate-700">
            @if($fm->is_active)
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
            @else
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            @endif
          </button>
        </form>
        <form method="POST" action="{{ route('admin.formateurs.destroy', $fm) }}" class="shrink-0"
              onsubmit="return confirm('Hapus anggota ini?')">
          @csrf @method('DELETE')
          <button type="submit" class="p-1.5 rounded-lg border border-red-200 hover:bg-red-50 transition text-red-400 hover:text-red-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
          </button>
        </form>
      </div>
      @endif
    </div>
  </div>
  @endforeach
</div>
@endif

@if($canManage)
{{-- ══════ MODAL TAMBAH ══════ --}}
<div id="modalCreate" class="fixed inset-0 z-50" style="display:none!important">
  <div class="absolute inset-0 bg-black/50" onclick="closeCreate()"></div>
  <div class="absolute inset-4 sm:inset-y-8 sm:inset-x-0 sm:mx-auto sm:max-w-lg bg-white rounded-2xl shadow-2xl overflow-y-auto">

    <div class="sticky top-0 z-10 bg-white flex items-center justify-between px-6 py-4 border-b border-slate-100">
      <h3 class="font-semibold text-slate-900">Tambah Anggota Tim Formatur</h3>
      <button onclick="closeCreate()" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-400">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>

    <form method="POST" action="{{ route('admin.formateurs.store') }}" enctype="multipart/form-data"
          onsubmit="return validateAlumni('createAlumniSearch')">
      @csrf
      <div class="px-6 py-5 space-y-4">

        {{-- Alumni searchable --}}
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">Alumni <span class="text-red-500">*</span></label>
          <div id="createAlumniSearch" x-data="alumniSearch()" class="relative" @click.outside="open = false">
            <input type="hidden" name="alumni_id" x-model="alumniId">
            <div class="relative">
              <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"
                   fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
              </svg>
              <input type="text" x-model="query"
                     @focus="open = true" @input="open = true" @keydown.escape="open = false"
                     placeholder="Cari nama atau NIM alumni..."
                     autocomplete="off"
                     class="w-full border border-slate-300 rounded-xl pl-9 pr-9 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500" />
              <button type="button" x-show="alumniId" @click="clear()"
                      class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>
            {{-- Dropdown hasil --}}
            <div x-show="open && results().length > 0"
                 class="absolute z-30 left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-lg overflow-y-auto max-h-56">
              <template x-for="a in results()" :key="a.id">
                <button type="button" @click="select(a)"
                        class="w-full text-left px-3 py-2.5 hover:bg-brand-50 flex items-center gap-3 border-b border-slate-50 last:border-0">
                  <span class="w-8 h-8 rounded-full bg-brand-100 text-brand-700 font-bold text-xs flex items-center justify-center shrink-0"
                        x-text="a.name.charAt(0).toUpperCase()"></span>
                  <span class="min-w-0">
                    <span class="block font-medium text-slate-800 text-sm truncate" x-text="a.name"></span>
                    <span class="block text-xs text-slate-400 truncate"
                          x-text="a.nim + (a.faculty ? ' · ' + a.faculty : '')"></span>
                  </span>
                </button>
              </template>
            </div>
            {{-- Tidak ditemukan --}}
            <div x-show="open && query.length >= 2 && results().length === 0"
                 class="absolute z-30 left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-lg px-4 py-3 text-sm text-slate-400 text-center">
              Tidak ditemukan
            </div>
          </div>
        </div>

        {{-- Jabatan --}}
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">Jabatan / Peran <span class="text-red-500">*</span></label>
          <input type="text" name="jabatan" required maxlength="100"
                 placeholder="Contoh: Ketua Formatur, Sekretaris, Anggota"
                 class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500" />
        </div>

        {{-- Deskripsi --}}
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">Deskripsi</label>
          <textarea name="deskripsi" rows="3" maxlength="1000"
                    placeholder="Deskripsi singkat peran atau kontribusi..."
                    class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 resize-none"></textarea>
        </div>

        {{-- Foto --}}
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">Foto</label>
          <input type="file" name="photo" accept="image/jpeg,image/png,image/webp"
                 class="w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer" />
          <p class="text-xs text-slate-400 mt-1">JPG, PNG, WebP · Maks. 2 MB</p>
        </div>

        {{-- Urutan --}}
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">Urutan Tampil</label>
          <input type="number" name="sort_order" value="0" min="0"
                 class="w-28 border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500" />
          <p class="text-xs text-slate-400 mt-1">Angka lebih kecil tampil lebih awal.</p>
        </div>

      </div>
      <div class="sticky bottom-0 bg-white border-t border-slate-100 px-6 py-4 flex justify-end gap-3">
        <button type="button" onclick="closeCreate()"
                class="px-4 py-2 text-sm text-slate-600 border border-slate-300 rounded-xl hover:bg-slate-50 transition">Batal</button>
        <button type="submit"
                class="px-5 py-2 text-sm font-medium text-white bg-brand-600 hover:bg-brand-700 rounded-xl transition">Simpan</button>
      </div>
    </form>
  </div>
</div>

{{-- ══════ MODAL EDIT ══════ --}}
<div id="modalEdit" class="fixed inset-0 z-50" style="display:none!important">
  <div class="absolute inset-0 bg-black/50" onclick="closeEdit()"></div>
  <div class="absolute inset-4 sm:inset-y-8 sm:inset-x-0 sm:mx-auto sm:max-w-lg bg-white rounded-2xl shadow-2xl overflow-y-auto">

    <div class="sticky top-0 z-10 bg-white flex items-center justify-between px-6 py-4 border-b border-slate-100">
      <h3 class="font-semibold text-slate-900">Edit Tim Formatur</h3>
      <button onclick="closeEdit()" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-400">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>

    <form id="formEdit" method="POST" enctype="multipart/form-data"
          onsubmit="return validateAlumni('editAlumniSearch')">
      @csrf @method('PUT')
      <div class="px-6 py-5 space-y-4">

        {{-- Alumni searchable --}}
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">Alumni <span class="text-red-500">*</span></label>
          <div id="editAlumniSearch" x-data="alumniSearch()" class="relative" @click.outside="open = false">
            <input type="hidden" name="alumni_id" x-model="alumniId">
            <div class="relative">
              <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"
                   fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
              </svg>
              <input type="text" x-model="query"
                     @focus="open = true" @input="open = true" @keydown.escape="open = false"
                     placeholder="Cari nama atau NIM alumni..."
                     autocomplete="off"
                     class="w-full border border-slate-300 rounded-xl pl-9 pr-9 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500" />
              <button type="button" x-show="alumniId" @click="clear()"
                      class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>
            <div x-show="open && results().length > 0"
                 class="absolute z-30 left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-lg overflow-y-auto max-h-56">
              <template x-for="a in results()" :key="a.id">
                <button type="button" @click="select(a)"
                        class="w-full text-left px-3 py-2.5 hover:bg-brand-50 flex items-center gap-3 border-b border-slate-50 last:border-0">
                  <span class="w-8 h-8 rounded-full bg-brand-100 text-brand-700 font-bold text-xs flex items-center justify-center shrink-0"
                        x-text="a.name.charAt(0).toUpperCase()"></span>
                  <span class="min-w-0">
                    <span class="block font-medium text-slate-800 text-sm truncate" x-text="a.name"></span>
                    <span class="block text-xs text-slate-400 truncate"
                          x-text="a.nim + (a.faculty ? ' · ' + a.faculty : '')"></span>
                  </span>
                </button>
              </template>
            </div>
            <div x-show="open && query.length >= 2 && results().length === 0"
                 class="absolute z-30 left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-lg px-4 py-3 text-sm text-slate-400 text-center">
              Tidak ditemukan
            </div>
          </div>
        </div>

        {{-- Jabatan --}}
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">Jabatan / Peran <span class="text-red-500">*</span></label>
          <input type="text" id="editJabatan" name="jabatan" required maxlength="100"
                 class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500" />
        </div>

        {{-- Deskripsi --}}
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">Deskripsi</label>
          <textarea id="editDeskripsi" name="deskripsi" rows="3" maxlength="1000"
                    class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 resize-none"></textarea>
        </div>

        {{-- Foto --}}
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">
            Ganti Foto
            <span class="text-xs font-normal text-slate-400">(kosongkan jika tidak ingin mengganti)</span>
          </label>
          <input type="file" name="photo" accept="image/jpeg,image/png,image/webp"
                 class="w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer" />
          <p class="text-xs text-slate-400 mt-1">JPG, PNG, WebP · Maks. 2 MB</p>
        </div>

        {{-- Urutan --}}
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">Urutan Tampil</label>
          <input type="number" id="editSortOrder" name="sort_order" min="0"
                 class="w-28 border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500" />
        </div>

      </div>
      <div class="sticky bottom-0 bg-white border-t border-slate-100 px-6 py-4 flex justify-end gap-3">
        <button type="button" onclick="closeEdit()"
                class="px-4 py-2 text-sm text-slate-600 border border-slate-300 rounded-xl hover:bg-slate-50 transition">Batal</button>
        <button type="submit"
                class="px-5 py-2 text-sm font-medium text-white bg-brand-600 hover:bg-brand-700 rounded-xl transition">Simpan</button>
      </div>
    </form>
  </div>
</div>
@endif

@endsection

@php
$alumniJson = $alumni->map(fn($a) => [
    'id'         => $a->id,
    'name'       => $a->name,
    'nim'        => $a->nim,
    'faculty'    => $a->faculty ?? '',
    'department' => $a->department ?? '',
])->toJson();
@endphp

@push('scripts')
<script>
// Data alumni sebagai JSON untuk pencarian client-side
const __alumniData = {!! $alumniJson !!};

// Alpine.js component: searchable alumni picker
function alumniSearch() {
  return {
    query:    '',
    alumniId: '',
    open:     false,

    results() {
      if (this.query.length < 1) return [];
      const q = this.query.toLowerCase();
      return __alumniData.filter(a =>
        a.name.toLowerCase().includes(q) || a.nim.toLowerCase().includes(q)
      ).slice(0, 10);
    },

    select(a) {
      this.alumniId = String(a.id);
      this.query    = a.name + ' (' + a.nim + ')';
      this.open     = false;
    },

    clear() {
      this.alumniId = '';
      this.query    = '';
      this.open     = false;
    },
  };
}

// Validasi alumni sebelum submit
function validateAlumni(searchId) {
  const el   = document.getElementById(searchId);
  const data = window.Alpine.$data(el);
  if (!data.alumniId) {
    // Highlight input
    el.querySelector('input[type="text"]').focus();
    el.querySelector('input[type="text"]').classList.add('ring-2', 'ring-red-400', 'border-red-400');
    setTimeout(() => {
      el.querySelector('input[type="text"]').classList.remove('ring-2', 'ring-red-400', 'border-red-400');
    }, 2000);
    return false;
  }
  return true;
}

// Modal Tambah
function openCreate() {
  document.getElementById('modalCreate').style.cssText = 'display:flex!important';
  document.body.style.overflow = 'hidden';
}
function closeCreate() {
  document.getElementById('modalCreate').style.cssText = 'display:none!important';
  document.body.style.overflow = '';
  // Reset search component
  const el   = document.getElementById('createAlumniSearch');
  if (el && window.Alpine) {
    const d  = window.Alpine.$data(el);
    d.query  = '';
    d.alumniId = '';
    d.open   = false;
  }
}

// Modal Edit
function openEdit(id, alumniId, alumniName, alumniNim, jabatan, sortOrder, deskripsi) {
  document.getElementById('formEdit').action = `/admin/formateurs/${id}`;
  document.getElementById('editJabatan').value   = jabatan;
  document.getElementById('editSortOrder').value = sortOrder;
  document.getElementById('editDeskripsi').value = deskripsi;

  // Pre-fill Alpine search component
  const el   = document.getElementById('editAlumniSearch');
  if (el && window.Alpine) {
    const d    = window.Alpine.$data(el);
    d.alumniId = String(alumniId);
    d.query    = alumniId ? (alumniName + ' (' + alumniNim + ')') : '';
    d.open     = false;
  }

  document.getElementById('modalEdit').style.cssText = 'display:flex!important';
  document.body.style.overflow = 'hidden';
}
function closeEdit() {
  document.getElementById('modalEdit').style.cssText = 'display:none!important';
  document.body.style.overflow = '';
}

// Auto-dismiss flash
const flash = document.getElementById('flashMsg');
if (flash) setTimeout(() => { flash.style.opacity = '0'; flash.style.transition = 'opacity .5s'; }, 4000);
</script>
@endpush
