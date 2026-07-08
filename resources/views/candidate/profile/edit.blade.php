@extends('layouts.admin')

@section('title', 'Visi & Misi')
@section('page-title', 'Visi & Misi')

@push('styles')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
  .ql-container { font-family:'Inter',sans-serif; font-size:14px; border-radius:0 0 .75rem .75rem; border-color:#e2e8f0; }
  .ql-toolbar  { border-radius:.75rem .75rem 0 0; border-color:#e2e8f0; background:#f8fafc; }
  .ql-editor   { min-height:200px; }
  .ql-editor.ql-blank::before { color:#94a3b8; font-style:normal; }
</style>
@endpush

@section('content')
@php $c = $candidate; @endphp

<div class="max-w-3xl mx-auto space-y-6">

  {{-- Header kandidat --}}
  <div class="bg-white rounded-2xl border border-slate-200 p-5 flex items-center gap-4">
    <div class="w-12 h-12 rounded-xl bg-brand-600 text-white flex items-center justify-center text-xl font-extrabold shrink-0">
      {{ str_pad($c->number, 2, '0', STR_PAD_LEFT) }}
    </div>
    <div class="min-w-0">
      <p class="font-bold text-slate-900 truncate">{{ $c->name }}</p>
      @if($c->alumni)
        <p class="text-xs text-slate-400 mt-0.5">{{ $c->alumni->faculty }} · {{ $c->alumni->department }}</p>
      @endif
    </div>
  </div>

  {{-- Flash --}}
  @if(session('success'))
  <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-xl">
    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    {{ session('success') }}
  </div>
  @endif

  @if($errors->any())
  <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl space-y-1">
    @foreach($errors->all() as $e)<p>• {{ $e }}</p>@endforeach
  </div>
  @endif

  {{-- ═══ FORM: VISI & MISI ═══ --}}
  <form action="{{ route('kandidat.profil.update') }}" method="POST"
        id="formVisiMisi" onsubmit="syncMisi()" class="space-y-6">
    @csrf @method('PUT')
    <input type="hidden" name="mission" id="missionInput">

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
      <div class="px-6 py-4 border-b border-slate-100">
        <h3 class="font-semibold text-slate-900">Visi &amp; Misi</h3>
        <p class="text-xs text-slate-400 mt-0.5">Ditampilkan kepada pemilih sebagai bahan pertimbangan</p>
      </div>
      <div class="p-6 space-y-6">

        {{-- Visi --}}
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-1.5">Visi</label>
          <textarea name="vision" rows="3"
                    placeholder="Tuliskan visi Anda secara singkat dan jelas..."
                    class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition resize-none">{{ old('vision', $c->vision) }}</textarea>
        </div>

        {{-- Misi — Quill editor --}}
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-1.5">Misi</label>
          <div id="misiEditor"></div>
        </div>

      </div>
      <div class="px-6 pb-5 flex justify-end">
        <button type="submit"
                class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
          </svg>
          Simpan Visi &amp; Misi
        </button>
      </div>
    </div>

  </form>

  {{-- ═══ FORM: PROFIL & PERKENALAN ═══ --}}
  <form action="{{ route('kandidat.profil.update') }}" method="POST"
        id="formProfil" onsubmit="return syncProfil()" class="space-y-0">
    @csrf @method('PUT')
    <input type="hidden" name="profile" id="profileInput">

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
      <div class="px-6 py-4 border-b border-slate-100">
        <h3 class="font-semibold text-slate-900">Profil &amp; Perkenalan</h3>
        <p class="text-xs text-slate-400 mt-0.5">Perkenalkan diri Anda kepada pemilih. Maks. <strong>500 kata</strong>.</p>
      </div>
      <div class="p-6">

        {{-- Quill editor --}}
        <div id="profileEditor"></div>

        {{-- Word counter + progress bar --}}
        <div class="flex items-center justify-between mt-2 px-1">
          <span id="wordWarning" class="text-xs text-red-500 hidden">Batas 500 kata tercapai. Kurangi isi sebelum menyimpan.</span>
          <span class="flex-1"></span>
          <span class="text-xs text-slate-400">
            <span id="wordCount">0</span> / 500 kata
          </span>
        </div>
        <div class="mt-2 h-1.5 bg-slate-100 rounded-full overflow-hidden">
          <div id="wordBar" class="h-full bg-brand-500 rounded-full transition-all duration-200" style="width:0%"></div>
        </div>

      </div>
      <div class="px-6 pb-5 flex justify-end">
        <button type="submit" id="profileSubmitBtn"
                class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
          </svg>
          Simpan Profil
        </button>
      </div>
    </div>

  </form>

</div>
@endsection

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
const TOOLBAR = [
  [{ header: [2, 3, false] }],
  ['bold', 'italic', 'underline'],
  [{ list: 'ordered' }, { list: 'bullet' }],
  ['link', 'clean'],
];

/* ── Quill: Misi ── */
const quillMisi = new Quill('#misiEditor', {
  theme: 'snow',
  placeholder: 'Tuliskan misi-misi yang akan Anda jalankan...',
  modules: { toolbar: TOOLBAR },
});

@if($c->mission)
quillMisi.clipboard.dangerouslyPasteHTML({!! json_encode($c->mission) !!});
@endif
@if(old('mission'))
quillMisi.clipboard.dangerouslyPasteHTML({!! json_encode(old('mission')) !!});
@endif

function syncMisi() {
  const html = quillMisi.root.innerHTML;
  document.getElementById('missionInput').value = (html === '<p><br></p>') ? '' : html;
}

/* ── Quill: Profil & Perkenalan ── */
const quillProfil = new Quill('#profileEditor', {
  theme: 'snow',
  placeholder: 'Tuliskan latar belakang, pengalaman, dan alasan mencalonkan diri Anda di sini...',
  modules: { toolbar: TOOLBAR },
});

@if($c->profile)
quillProfil.clipboard.dangerouslyPasteHTML({!! json_encode($c->profile) !!});
@endif
@if(old('profile'))
quillProfil.clipboard.dangerouslyPasteHTML({!! json_encode(old('profile')) !!});
@endif

function syncProfil() {
  const html = quillProfil.root.innerHTML;
  document.getElementById('profileInput').value = (html === '<p><br></p>') ? '' : html;
  return true;
}

/* ── Word counter (baca dari teks Quill) ── */
function countProfileWords() {
  const MAX     = 500;
  const text    = quillProfil.getText().trim();
  const words   = text === '' ? [] : text.split(/\s+/);
  const count   = words.length;
  const pct     = Math.min(count / MAX * 100, 100);

  document.getElementById('wordCount').textContent = count;
  document.getElementById('wordBar').style.width   = pct + '%';

  const bar     = document.getElementById('wordBar');
  const warning = document.getElementById('wordWarning');
  const btn     = document.getElementById('profileSubmitBtn');

  if (count > MAX) {
    bar.classList.remove('bg-brand-500', 'bg-amber-400');
    bar.classList.add('bg-red-500');
    warning.classList.remove('hidden');
    btn.disabled = true;
    btn.classList.add('opacity-50', 'cursor-not-allowed');
  } else if (count >= Math.floor(MAX * 0.85)) {
    bar.classList.remove('bg-brand-500', 'bg-red-500');
    bar.classList.add('bg-amber-400');
    warning.classList.add('hidden');
    btn.disabled = false;
    btn.classList.remove('opacity-50', 'cursor-not-allowed');
  } else {
    bar.classList.remove('bg-amber-400', 'bg-red-500');
    bar.classList.add('bg-brand-500');
    warning.classList.add('hidden');
    btn.disabled = false;
    btn.classList.remove('opacity-50', 'cursor-not-allowed');
  }
}

quillProfil.on('text-change', countProfileWords);
countProfileWords();
</script>
@endpush
