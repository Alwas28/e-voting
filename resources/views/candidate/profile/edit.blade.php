@extends('layouts.admin')

@section('title', 'Profil Saya')

@push('styles')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
  .ql-container { font-family:'Inter',sans-serif; font-size:14px; border-radius:0 0 .5rem .5rem; border-color:#e2e8f0; }
  .ql-toolbar  { border-radius:.5rem .5rem 0 0; border-color:#e2e8f0; background:#f8fafc; }
  .ql-editor   { min-height:220px; }
  .ql-editor.ql-blank::before { color:#94a3b8; font-style:normal; }
</style>
@endpush

@section('content')
<div class="p-6 max-w-3xl mx-auto space-y-6">

  {{-- Header --}}
  <div class="flex items-center gap-4">
    <div class="w-12 h-12 rounded-2xl bg-brand-600 text-white flex items-center justify-center text-xl font-bold shadow">
      {{ $candidate->number }}
    </div>
    <div>
      <h1 class="text-2xl font-bold text-slate-800">Profil Saya</h1>
      <p class="text-slate-500 text-sm mt-0.5">
        Kandidat No. {{ $candidate->number }} &bull;
        <span class="font-semibold text-brand-600">{{ $candidate->name }}</span>
      </p>
    </div>
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

  @if($errors->any())
  <div class="rounded-xl bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm space-y-1">
    @foreach($errors->all() as $e)<p>• {{ $e }}</p>@endforeach
  </div>
  @endif

  <form action="{{ route('kandidat.profil.update') }}" method="POST" enctype="multipart/form-data"
        onsubmit="syncQuill()" class="space-y-6">
    @csrf @method('PUT')
    <input type="hidden" name="profile" id="profileInput">

    {{-- ── CARD: Foto ─────────────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
      <div class="px-6 py-4 border-b border-slate-100">
        <h2 class="font-semibold text-slate-700">Foto</h2>
        <p class="text-xs text-slate-400 mt-0.5">Foto resmi yang ditampilkan di surat suara</p>
      </div>
      <div class="px-6 py-5 flex items-center gap-6">
        <div class="shrink-0">
          <div class="w-28 h-28 rounded-full border-4 border-slate-200 bg-slate-100 overflow-hidden
                      flex items-center justify-center cursor-pointer hover:border-brand-400 transition"
               onclick="document.getElementById('fotoInput').click()">
            <svg id="fotoIcon" class="w-10 h-10 text-slate-300 {{ $candidate->photo ? 'hidden' : '' }}"
                 fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <img id="fotoPreview"
                 class="w-full h-full object-cover {{ $candidate->photo ? '' : 'hidden' }}"
                 src="{{ $candidate->photo ? $candidate->photo_url : '' }}" alt="">
          </div>
          <input type="file" id="fotoInput" name="photo" accept="image/*" class="hidden"
                 onchange="previewFoto(this)">
        </div>
        <div class="space-y-2">
          <button type="button" onclick="document.getElementById('fotoInput').click()"
                  class="inline-flex items-center gap-2 text-sm font-semibold text-brand-600 hover:text-brand-700
                         border border-brand-200 hover:border-brand-400 px-4 py-2 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            {{ $candidate->photo ? 'Ganti Foto' : 'Pilih Foto' }}
          </button>
          <p class="text-xs text-slate-400">Format: JPG, PNG, WebP &bull; Maks. 2MB<br>Kosongkan jika tidak ingin mengganti foto</p>
        </div>
      </div>
    </div>

    {{-- ── CARD: Visi & Misi ──────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
      <div class="px-6 py-4 border-b border-slate-100">
        <h2 class="font-semibold text-slate-700">Visi &amp; Misi</h2>
        <p class="text-xs text-slate-400 mt-0.5">Ditampilkan kepada pemilih sebagai bahan pertimbangan</p>
      </div>
      <div class="px-6 py-5 space-y-4">

        <div>
          <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Visi</label>
          <textarea name="vision" rows="3" placeholder="Tuliskan visi Anda secara singkat dan jelas..."
                    class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800
                           focus:outline-none focus:ring-2 focus:ring-brand-200 transition resize-none">{{ old('vision', $candidate->vision) }}</textarea>
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Misi</label>
          <textarea name="mission" rows="5" placeholder="Tuliskan misi-misi yang akan Anda jalankan..."
                    class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800
                           focus:outline-none focus:ring-2 focus:ring-brand-200 transition resize-none">{{ old('mission', $candidate->mission) }}</textarea>
        </div>

      </div>
    </div>

    {{-- ── CARD: Profil & Perkenalan (Quill) ─────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
      <div class="px-6 py-4 border-b border-slate-100">
        <h2 class="font-semibold text-slate-700">Profil &amp; Perkenalan</h2>
        <p class="text-xs text-slate-400 mt-0.5">
          Perkenalkan diri Anda kepada pemilih. Bisa memuat latar belakang, pengalaman, dan alasan mencalonkan diri.
          Gunakan format teks: heading, tebal, miring, daftar, tautan.
        </p>
      </div>
      <div class="px-6 py-5">
        <div id="quillEditor" class="bg-white"></div>
      </div>
    </div>

    {{-- ── Info readonly ──────────────────────────────────────────────────────── --}}
    <div class="bg-slate-50 rounded-2xl border border-slate-200 px-6 py-5">
      <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-3">Informasi Akun (tidak dapat diubah)</p>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
        <div>
          <p class="text-xs text-slate-400">Nama</p>
          <p class="font-semibold text-slate-700">{{ $candidate->name }}</p>
        </div>
        <div>
          <p class="text-xs text-slate-400">Nomor Urut</p>
          <p class="font-semibold text-slate-700">{{ $candidate->number }}</p>
        </div>
        @if($candidate->faculty)
        <div>
          <p class="text-xs text-slate-400">Fakultas</p>
          <p class="font-semibold text-slate-700">{{ $candidate->faculty }}</p>
        </div>
        @endif
        @if($candidate->department)
        <div>
          <p class="text-xs text-slate-400">Program Studi</p>
          <p class="font-semibold text-slate-700">{{ $candidate->department }}</p>
        </div>
        @endif
        @if($candidate->alumni)
        <div>
          <p class="text-xs text-slate-400">NIM</p>
          <p class="font-semibold text-slate-700">{{ $candidate->alumni->nim }}</p>
        </div>
        @endif
      </div>
    </div>

    {{-- Tombol simpan --}}
    <div class="flex justify-end">
      <button type="submit"
              class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white
                     text-sm font-semibold px-6 py-3 rounded-xl transition shadow-sm shadow-brand-200">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        Simpan Perubahan
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
  placeholder: 'Tuliskan profil dan perkenalan Anda di sini...',
  modules: {
    toolbar: [
      [{ header: [2, 3, false] }],
      ['bold', 'italic', 'underline'],
      [{ list: 'ordered' }, { list: 'bullet' }],
      ['link', 'clean'],
    ],
  },
});

@if($candidate->profile)
quill.clipboard.dangerouslyPasteHTML({!! json_encode($candidate->profile) !!});
@endif

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
