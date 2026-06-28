@extends('layouts.admin')

@section('title', 'Tambah Kandidat')

@push('styles')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
  .ql-container { font-family:'Inter',sans-serif; font-size:14px; border-radius:0 0 .5rem .5rem; border-color:#e2e8f0; }
  .ql-toolbar  { border-radius:.5rem .5rem 0 0; border-color:#e2e8f0; background:#f8fafc; }
  .ql-editor   { min-height:200px; }
  .ql-editor.ql-blank::before { color:#94a3b8; font-style:normal; }
</style>
@endpush

@section('content')
<div class="p-6 max-w-3xl mx-auto space-y-6">

  {{-- Header --}}
  <div class="flex items-center gap-4">
    <a href="{{ route('admin.candidates') }}"
       class="w-9 h-9 rounded-lg border border-slate-200 hover:bg-slate-100 flex items-center justify-center text-slate-500 transition">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
      </svg>
    </a>
    <div>
      <h1 class="text-2xl font-bold text-slate-800">Tambah Kandidat</h1>
      <p class="text-slate-500 text-sm mt-0.5">Periode: <span class="font-semibold text-brand-600">{{ $activePeriod->name }}</span></p>
    </div>
  </div>

  @if($errors->any())
  <div class="rounded-xl bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm space-y-1">
    @foreach($errors->all() as $e)<p>• {{ $e }}</p>@endforeach
  </div>
  @endif

  <form action="{{ route('admin.candidates.store') }}" method="POST" enctype="multipart/form-data"
        onsubmit="syncQuill()" class="space-y-6">
    @csrf
    <input type="hidden" name="election_period_id" value="{{ $activePeriod->id }}">
    <input type="hidden" name="profile" id="profileInput">

    {{-- ── CARD 1: Data Alumni ───────────────────────────────────────────────── --}}
    <div class="relative z-20 bg-white rounded-2xl border border-slate-200 shadow-sm">
      <div class="px-6 py-4 border-b border-slate-100">
        <h2 class="font-semibold text-slate-700">Data Alumni</h2>
        <p class="text-xs text-slate-400 mt-0.5">Cari alumni yang akan dijadikan kandidat</p>
      </div>
      <div class="px-6 py-5">
        @include('admin.candidates.partials.alumni-search', ['selectedAlumni' => null])
      </div>
    </div>

    {{-- ── CARD 2: Info Kandidat ─────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
      <div class="px-6 py-4 border-b border-slate-100">
        <h2 class="font-semibold text-slate-700">Info Kandidat</h2>
      </div>
      <div class="px-6 py-5 space-y-5">

        {{-- Nomor urut + Foto --}}
        <div class="flex items-start gap-6">
          {{-- Foto --}}
          <div class="shrink-0 flex flex-col items-center gap-2">
            <div id="fotoWrap"
                 class="w-28 h-28 rounded-full border-4 border-slate-200 bg-slate-100 overflow-hidden
                        flex items-center justify-center cursor-pointer hover:border-brand-400 transition"
                 onclick="document.getElementById('fotoInput').click()">
              <svg id="fotoIcon" class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              <img id="fotoPreview" class="w-full h-full object-cover hidden" src="" alt="">
            </div>
            <input type="file" id="fotoInput" name="photo" accept="image/*" class="hidden"
                   onchange="previewFoto(this)">
            <p class="text-xs text-slate-400 text-center">Foto kandidat<br>(maks. 2MB)</p>
          </div>

          {{-- Nomor urut --}}
          <div class="flex-1">
            <label class="block text-xs font-medium text-slate-600 mb-1.5">Nomor Urut <span class="text-red-500">*</span></label>
            <input type="number" name="number" value="{{ old('number') }}" min="1" max="99"
                   class="w-28 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-slate-800
                          focus:outline-none focus:ring-2 focus:ring-brand-200 transition text-center text-lg font-bold"
                   placeholder="1" required>
            <p class="text-xs text-slate-400 mt-1.5">Nomor urut tampil di surat suara</p>
          </div>
        </div>

        {{-- Status --}}
        <label class="flex items-center gap-2.5 cursor-pointer select-none">
          <input type="hidden" name="is_active" value="0">
          <input type="checkbox" name="is_active" value="1" checked
                 class="w-4 h-4 rounded border-slate-300 text-brand-600 focus:ring-brand-200">
          <span class="text-sm text-slate-600">Kandidat aktif (ditampilkan kepada pemilih)</span>
        </label>

      </div>
    </div>

    {{-- ── CARD 3: Visi & Misi ───────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
      <div class="px-6 py-4 border-b border-slate-100">
        <h2 class="font-semibold text-slate-700">Visi &amp; Misi</h2>
      </div>
      <div class="px-6 py-5 space-y-4">
        <div>
          <label class="block text-xs font-medium text-slate-600 mb-1.5">Visi</label>
          <textarea name="vision" rows="2" placeholder="Visi singkat kandidat..."
                    class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800
                           focus:outline-none focus:ring-2 focus:ring-brand-200 transition resize-none">{{ old('vision') }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-medium text-slate-600 mb-1.5">Misi</label>
          <textarea name="mission" rows="4" placeholder="Tuliskan misi kandidat..."
                    class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800
                           focus:outline-none focus:ring-2 focus:ring-brand-200 transition resize-none">{{ old('mission') }}</textarea>
        </div>
      </div>
    </div>

    {{-- ── CARD 4: Profil (Quill) ────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
      <div class="px-6 py-4 border-b border-slate-100">
        <h2 class="font-semibold text-slate-700">Profil &amp; Perkenalan</h2>
        <p class="text-xs text-slate-400 mt-0.5">Bisa gunakan format teks: heading, tebal, miring, daftar, tautan</p>
      </div>
      <div class="px-6 py-5">
        <div id="quillEditor" class="bg-white min-h-[220px]"></div>
      </div>
    </div>

    {{-- Tombol --}}
    <div class="flex gap-3">
      <a href="{{ route('admin.candidates') }}"
         class="flex-1 border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-semibold py-3 rounded-xl transition text-center">
        Batal
      </a>
      <button type="submit"
              class="flex-1 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold py-3 rounded-xl transition flex items-center justify-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        Simpan Kandidat
      </button>
    </div>

  </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
const quill = new Quill('#quillEditor', {
  theme: 'snow',
  placeholder: 'Tulis profil dan perkenalan kandidat di sini...',
  modules: {
    toolbar: [
      [{ header: [2, 3, false] }],
      ['bold', 'italic', 'underline'],
      [{ list: 'ordered' }, { list: 'bullet' }],
      ['link', 'clean'],
    ],
  },
});

@if(old('profile'))
quill.clipboard.dangerouslyPasteHTML({!! json_encode(old('profile')) !!});
@endif

function syncQuill() {
  const html = quill.root.innerHTML;
  document.getElementById('profileInput').value = (html === '<p><br></p>') ? '' : html;
}

function previewFoto(input) {
  const file = input.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = e => {
    const img  = document.getElementById('fotoPreview');
    const icon = document.getElementById('fotoIcon');
    img.src = e.target.result;
    img.classList.remove('hidden');
    icon.classList.add('hidden');
  };
  reader.readAsDataURL(file);
}
</script>
@endpush
