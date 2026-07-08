@extends('layouts.admin')

@section('title', 'Pengaturan')
@section('page-title', 'Pengaturan Sistem')

@section('content')
<div class="max-w-2xl space-y-5">

  @if(session('success'))
  <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm">
    <svg class="w-5 h-5 shrink-0 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    {{ session('success') }}
  </div>
  @endif

  <form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf

    {{-- ── Pendaftaran Alumni ── --}}
    <div class="bg-white rounded-2xl border border-slate-200 divide-y divide-slate-100">
      <div class="px-6 py-4">
        <h2 class="font-bold text-slate-800">Pendaftaran Alumni</h2>
        <p class="text-sm text-slate-500 mt-0.5">Pengaturan terkait proses registrasi alumni</p>
      </div>

      <div class="px-6 py-5 space-y-4">
        {{-- Google Form URL --}}
        <div>
          <label for="google_form_url" class="block text-sm font-medium text-slate-700 mb-1.5">
            Link Google Form Pendaftaran
          </label>
          <p class="text-xs text-slate-500 mb-2">
            Ditampilkan sebagai tombol saat alumni tidak ditemukan pada saat verifikasi data.
          </p>
          <div class="flex gap-2">
            <input type="url" id="google_form_url" name="google_form_url"
                   value="{{ old('google_form_url', $settings['google_form_url']) }}"
                   placeholder="https://docs.google.com/forms/..."
                   class="flex-1 border border-slate-300 rounded-xl px-4 py-2.5 text-sm
                          focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent
                          @error('google_form_url') border-red-400 bg-red-50 @enderror" />
            @if($settings['google_form_url'])
            <a href="{{ $settings['google_form_url'] }}" target="_blank"
               class="px-3 py-2.5 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-50 transition text-sm shrink-0 flex items-center gap-1">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
              </svg>
              Buka
            </a>
            @endif
          </div>
          @error('google_form_url')
          <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
          @enderror
          @if(!$settings['google_form_url'])
          <p class="text-xs text-amber-600 mt-1.5 flex items-center gap-1">
            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Belum diatur — tombol pendaftaran tidak akan muncul saat data alumni tidak ditemukan.
          </p>
          @endif
        </div>
      </div>
    </div>

    {{-- ── Identitas Situs ── --}}
    <div class="bg-white rounded-2xl border border-slate-200 divide-y divide-slate-100 mt-5">
      <div class="px-6 py-4">
        <h2 class="font-bold text-slate-800">Identitas Situs</h2>
        <p class="text-sm text-slate-500 mt-0.5">Nama dan deskripsi tampil di halaman publik & SEO</p>
      </div>

      <div class="px-6 py-5 space-y-4">
        <div>
          <label for="site_name" class="block text-sm font-medium text-slate-700 mb-1.5">Nama Situs</label>
          <input type="text" id="site_name" name="site_name"
                 value="{{ old('site_name', $settings['site_name']) }}"
                 placeholder="E-Voting"
                 class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm
                        focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
        </div>
        <div>
          <label for="site_description" class="block text-sm font-medium text-slate-700 mb-1.5">Deskripsi Singkat</label>
          <input type="text" id="site_description" name="site_description"
                 value="{{ old('site_description', $settings['site_description']) }}"
                 placeholder="Sistem Pemilihan Digital Alumni"
                 class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm
                        focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
          <p class="text-xs text-slate-400 mt-1">Muncul sebagai subtitle di mesin pencari dan preview media sosial.</p>
        </div>
      </div>
    </div>

    {{-- ── Video Panduan ── --}}
    <div class="bg-white rounded-2xl border border-slate-200 divide-y divide-slate-100 mt-5">
      <div class="px-6 py-4">
        <h2 class="font-bold text-slate-800">Video Panduan</h2>
        <p class="text-sm text-slate-500 mt-0.5">Embed video YouTube di halaman publik sebelum bagian FAQ</p>
      </div>

      <div class="px-6 py-5 space-y-4">

        {{-- Judul section --}}
        <div>
          <label for="youtube_title" class="block text-sm font-medium text-slate-700 mb-1.5">Judul Section</label>
          <input type="text" id="youtube_title" name="youtube_title"
                 value="{{ old('youtube_title', $settings['youtube_title']) }}"
                 placeholder="Panduan Video"
                 class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm
                        focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
          <p class="text-xs text-slate-400 mt-1">Judul yang ditampilkan di atas embed video di halaman publik.</p>
        </div>

        {{-- YouTube URL --}}
        <div>
          <label for="youtube_url" class="block text-sm font-medium text-slate-700 mb-1.5">Link Video YouTube</label>
          <p class="text-xs text-slate-500 mb-2">
            Mendukung format: <code class="bg-slate-100 px-1 py-0.5 rounded text-xs">youtube.com/watch?v=…</code>,
            <code class="bg-slate-100 px-1 py-0.5 rounded text-xs">youtu.be/…</code>, atau
            <code class="bg-slate-100 px-1 py-0.5 rounded text-xs">youtube.com/embed/…</code>
          </p>
          <div class="flex gap-2">
            <input type="url" id="youtube_url" name="youtube_url"
                   value="{{ old('youtube_url', $settings['youtube_url']) }}"
                   placeholder="https://www.youtube.com/watch?v=..."
                   class="flex-1 border rounded-xl px-4 py-2.5 text-sm font-mono
                          focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent
                          {{ $errors->has('youtube_url') ? 'border-red-400 bg-red-50' : 'border-slate-300' }}" />
            @if($settings['youtube_url'])
            <a href="{{ $settings['youtube_url'] }}" target="_blank"
               class="px-3 py-2.5 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-50 transition text-sm shrink-0 flex items-center gap-1">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
              </svg>
              Buka
            </a>
            @endif
          </div>
          @error('youtube_url')
          <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
          @enderror
          @if(!$settings['youtube_url'])
          <p class="text-xs text-amber-600 mt-1.5 flex items-center gap-1">
            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Belum diatur — section video tidak akan tampil di halaman publik.
          </p>
          @endif
        </div>

        {{-- Preview embed (jika ada) --}}
        @php
          $prevUrl = old('youtube_url', $settings['youtube_url']);
          $prevEmbed = null;
          if ($prevUrl) {
            if (preg_match('/[?&]v=([^&]+)/', $prevUrl, $m) ||
                preg_match('/youtu\.be\/([^?&\s]+)/', $prevUrl, $m) ||
                preg_match('/youtube\.com\/embed\/([^?&\s]+)/', $prevUrl, $m)) {
              $prevEmbed = 'https://www.youtube.com/embed/' . $m[1];
            }
          }
        @endphp
        @if($prevEmbed)
        <div>
          <p class="text-xs font-medium text-slate-500 mb-2">Preview</p>
          <div class="relative w-full rounded-xl overflow-hidden border border-slate-200" style="padding-top:56.25%">
            <iframe src="{{ $prevEmbed }}"
                    class="absolute inset-0 w-full h-full"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
          </div>
        </div>
        @endif

      </div>
    </div>

    {{-- ── Tombol simpan ── --}}
    <div class="flex justify-end mt-5">
      <button type="submit"
              class="flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-semibold px-6 py-2.5 rounded-xl transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        Simpan Pengaturan
      </button>
    </div>

  </form>
</div>
@endsection
