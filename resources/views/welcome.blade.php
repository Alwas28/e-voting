<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
@php
  $siteName = \App\Models\Setting::get('site_name', 'E-Voting');
  $siteDesc = \App\Models\Setting::get('site_description', 'Sistem Pemilihan Digital Alumni — Aman, Transparan, dan Terpercaya');
  $logoUrl  = asset('images/Logo2.png');
  $siteUrl  = url('/');
@endphp
<title>{{ $siteName }} — Pemilihan Digital yang Aman &amp; Transparan</title>
<meta name="description" content="{{ $siteDesc }}" />
{{-- Open Graph --}}
<meta property="og:type"        content="website" />
<meta property="og:url"         content="{{ $siteUrl }}" />
<meta property="og:title"       content="{{ $siteName }} — Pemilihan Digital yang Aman &amp; Transparan" />
<meta property="og:description" content="{{ $siteDesc }}" />
<meta property="og:image"       content="{{ $logoUrl }}" />
<meta property="og:image:width"  content="512" />
<meta property="og:image:height" content="512" />
<meta property="og:locale"      content="id_ID" />
<meta property="og:site_name"   content="{{ $siteName }}" />
{{-- Twitter Card --}}
<meta name="twitter:card"        content="summary" />
<meta name="twitter:title"       content="{{ $siteName }} — Pemilihan Digital" />
<meta name="twitter:description" content="{{ $siteDesc }}" />
<meta name="twitter:image"       content="{{ $logoUrl }}" />
<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          brand: {
            50:'var(--brand-50)',100:'var(--brand-100)',500:'var(--brand-500)',
            600:'var(--brand-600)',700:'var(--brand-700)',900:'var(--brand-900)'
          }
        }
      }
    }
  }
</script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  body { font-family:'Inter',sans-serif; }
  :root{
    --brand-50:#eef2ff;--brand-100:#e0e7ff;--brand-500:#6366f1;
    --brand-600:#4f46e5;--brand-700:#4338ca;--brand-900:#312e81;
  }
  html { scroll-behavior:smooth; }
  /* Profile content dari Quill */
  .profile-html h1,.profile-html h2 { font-weight:700; margin-bottom:.5rem; }
  .profile-html h2 { font-size:1.05rem; }
  .profile-html p  { margin-bottom:.75rem; line-height:1.75; }
  .profile-html ul { list-style:disc; padding-left:1.25rem; margin-bottom:.75rem; }
  .profile-html ol { list-style:decimal; padding-left:1.25rem; margin-bottom:.75rem; }
  .profile-html li { margin-bottom:.25rem; }
  .profile-html strong { font-weight:600; }
  .profile-html em  { font-style:italic; }
  .profile-html a   { color:#4f46e5; text-decoration:underline; }
</style>
</head>
<body class="bg-white text-slate-800 antialiased">

<!-- Navbar -->
<header id="nav" class="sticky top-0 z-50 bg-white/90 backdrop-blur border-b border-slate-200">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
    <a href="#home" class="flex items-center gap-2.5">
      <img src="{{ asset('images/Logo2.png') }}" alt="Logo" class="w-9 h-9 object-contain" />
      <span class="font-bold text-lg text-slate-900">{{ $siteName }}</span>
    </a>

    <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-600">
      <a href="#fitur"    class="hover:text-brand-600 transition">Fitur</a>
      <a href="#alur"     class="hover:text-brand-600 transition">Cara Kerja</a>
      @if($formateurs->isNotEmpty())
      <a href="#formatur" class="hover:text-brand-600 transition">Tim Formatur</a>
      @endif
      @if($youtubeEmbed)
      <a href="#video"    class="hover:text-brand-600 transition">Video</a>
      @endif
      <a href="#faq"      class="hover:text-brand-600 transition">FAQ</a>
    </nav>

    <div class="flex items-center gap-2">
      @auth
        <a href="{{ route('admin.dashboard') }}" class="inline-flex text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 px-4 py-2 rounded-lg transition">Dashboard</a>
      @else
        <a href="{{ route('login') }}" class="hidden sm:inline-flex text-sm font-medium text-slate-600 hover:text-brand-600 px-4 py-2 transition">Masuk</a>
        <a href="{{ route('alumni.register.step1') }}" class="inline-flex text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 px-4 py-2 rounded-lg transition">Daftar</a>
      @endauth
      <button onclick="toggleMobileNav()" class="md:hidden p-2 -mr-1 rounded-lg hover:bg-slate-100">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
    </div>
  </div>

  <!-- Mobile nav -->
  <div id="mobileNav" class="hidden md:hidden border-t border-slate-200 bg-white px-4 py-3 space-y-1">
    <a href="#fitur"    onclick="toggleMobileNav()" class="block px-3 py-2.5 rounded-lg text-slate-700 hover:bg-slate-50">Fitur</a>
    <a href="#alur"     onclick="toggleMobileNav()" class="block px-3 py-2.5 rounded-lg text-slate-700 hover:bg-slate-50">Cara Kerja</a>
    @if($formateurs->isNotEmpty())
    <a href="#formatur" onclick="toggleMobileNav()" class="block px-3 py-2.5 rounded-lg text-slate-700 hover:bg-slate-50">Tim Formatur</a>
    @endif
    @if($youtubeEmbed)
    <a href="#video"    onclick="toggleMobileNav()" class="block px-3 py-2.5 rounded-lg text-slate-700 hover:bg-slate-50">Video</a>
    @endif
    <a href="#faq"      onclick="toggleMobileNav()" class="block px-3 py-2.5 rounded-lg text-slate-700 hover:bg-slate-50">FAQ</a>
    @auth
      <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2.5 rounded-lg text-brand-600 font-semibold hover:bg-brand-50">Dashboard</a>
    @else
      <a href="{{ route('login') }}" class="block px-3 py-2.5 rounded-lg text-slate-700 hover:bg-slate-50">Masuk</a>
    @endauth
  </div>
</header>

<!-- Hero -->
<section id="home" class="relative overflow-hidden">
  <div class="absolute inset-0 bg-gradient-to-b from-brand-50 to-white"></div>
  <div class="relative max-w-6xl mx-auto px-4 sm:px-6 py-16 sm:py-24 grid lg:grid-cols-2 gap-12 items-center">
    <div>
      <span class="inline-flex items-center gap-1.5 bg-brand-100 text-brand-700 text-xs font-semibold px-3 py-1.5 rounded-full">
        <span class="w-1.5 h-1.5 bg-brand-600 rounded-full animate-pulse"></span> Pemilihan 2026 sedang berlangsung
      </span>
      <h1 class="mt-5 text-4xl sm:text-5xl font-extrabold leading-tight text-slate-900">
        {{ $siteDesc }}
      </h1>
      <p class="mt-5 text-lg text-slate-600 leading-relaxed">
        Berikan suara Anda kapan saja, di mana saja. Sistem e-voting terenkripsi dengan hasil real-time yang dapat diverifikasi oleh semua pihak.
      </p>
      <div class="mt-8 flex flex-col sm:flex-row gap-3">
        <a href="#cta" class="inline-flex items-center justify-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-semibold px-6 py-3 rounded-lg transition">
          Mulai Memilih
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </a>
      </div>
    </div>

    <!-- Hero card kandidat -->
    <div class="relative">
      @php
        $heroCount = $candidates->count();
        $gridClass = $heroCount >= 4 ? 'grid-cols-2' : 'grid-cols-' . max(1, $heroCount);
      @endphp

      @if($candidates->isEmpty())
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xl p-8 text-center">
          <p class="text-sm text-slate-400">Belum ada kandidat terdaftar</p>
        </div>
      @else
        <div class="grid {{ $gridClass }} gap-3">
          @foreach($candidates as $c)
          <div class="bg-white rounded-2xl border border-slate-200 shadow-md shadow-slate-100/80 hover:shadow-lg hover:border-brand-200 transition p-4 flex flex-col items-center text-center gap-3">
            {{-- Foto --}}
            <div class="w-full aspect-square rounded-xl overflow-hidden bg-brand-100 shrink-0 flex items-center justify-center">
              @if($c->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($c->photo))
                <img src="{{ asset('storage/' . $c->photo) }}" alt="{{ $c->name }}" class="w-full h-full object-cover">
              @else
                <span class="text-brand-700 font-extrabold text-3xl leading-none">
                  {{ strtoupper(substr($c->name, 0, 1)) }}
                </span>
              @endif
            </div>
            {{-- Info --}}
            <div class="w-full min-w-0">
              <span class="inline-block text-xs font-bold bg-brand-600 text-white px-2 py-0.5 rounded-md mb-1.5">No. {{ str_pad($c->number, 2, '0', STR_PAD_LEFT) }}</span>
              <p class="text-xs font-bold text-slate-800 leading-snug line-clamp-2">{{ $c->name }}</p>
              @if($c->alumni && $c->alumni->faculty)
                <p class="text-xs text-slate-400 mt-0.5 truncate">{{ $c->alumni->faculty }}</p>
              @endif
            </div>
            {{-- Tombol detail --}}
            <a href="{{ route('candidate.profile', $c) }}"
               class="w-full mt-1 flex items-center justify-center gap-1.5 text-xs font-semibold text-brand-600 hover:text-white hover:bg-brand-600 border border-brand-200 hover:border-brand-600 py-2 rounded-lg transition">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
              Lihat Detail
            </a>
          </div>
          @endforeach
        </div>

      @endif

      <div class="absolute -z-10 -top-6 -right-6 w-32 h-32 bg-brand-100 rounded-full blur-2xl"></div>
    </div>
  </div>
</section>

<!-- ═══ JADWAL PEMILIHAN ═══ -->
@if($electionSchedule || $dptSchedule)
@php
  $eStatus = $electionSchedule?->status;
  $dStatus = $dptSchedule?->status;
@endphp
<section class="bg-slate-50 border-y border-slate-200">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-5 text-center">Jadwal Pemilihan</p>
    <div class="grid sm:grid-cols-2 gap-4 max-w-3xl mx-auto">

      {{-- Pendaftaran DPT --}}
      @if($dptSchedule && $dptSchedule->start_date)
      <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0
            {{ $dStatus === 'berlangsung' ? 'bg-blue-100 text-blue-600' : ($dStatus === 'selesai' ? 'bg-slate-100 text-slate-400' : 'bg-amber-50 text-amber-500') }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Pendaftaran DPT</p>
            <p class="text-sm font-bold text-slate-800">
              {{ $dptSchedule->start_date->format('d M Y') }}
              @if($dptSchedule->end_date) — {{ $dptSchedule->end_date->format('d M Y') }} @endif
            </p>
          </div>
          @if($dStatus === 'selesai')
            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 text-slate-500">Selesai</span>
          @elseif($dStatus === 'berlangsung')
            <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-blue-100 text-blue-700 animate-pulse">● Buka</span>
          @endif
        </div>

        @if($dStatus === 'selesai')
          <div class="flex items-center gap-2 bg-slate-50 rounded-xl px-3 py-2.5">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            <p class="text-sm font-semibold text-slate-500">Pendaftaran telah ditutup</p>
          </div>
        @elseif($dStatus === 'berlangsung' && $dptSchedule->end_date)
          <div class="bg-blue-50 rounded-xl px-3 py-2.5">
            <p class="text-xs text-blue-600 font-medium mb-1.5">Ditutup dalam:</p>
            <div id="dpt-cd" class="flex items-center gap-1.5 font-bold text-blue-700 text-lg tabular-nums">
              <span id="dpt-h">--</span><span class="text-blue-400 text-sm font-normal">j</span>
              <span id="dpt-m">--</span><span class="text-blue-400 text-sm font-normal">m</span>
              <span id="dpt-s">--</span><span class="text-blue-400 text-sm font-normal">d</span>
            </div>
          </div>
          <script>
          (function(){
            const t = new Date("{{ $dptSchedule->end_date->toIso8601String() }}");
            function tick(){
              const diff = t - new Date();
              const cd = document.getElementById('dpt-cd');
              if(diff <= 0){ cd.innerHTML='<span class="text-slate-500 text-sm font-semibold">Ditutup</span>'; return; }
              const ts = Math.floor(diff/1000), d = Math.floor(ts/86400);
              const h  = String(Math.floor((ts%86400)/3600)).padStart(2,'0');
              const m  = String(Math.floor((ts%3600)/60)).padStart(2,'0');
              const s  = String(ts%60).padStart(2,'0');
              const lc = 'text-blue-400 text-sm font-normal';
              cd.innerHTML = (d > 0 ? `<span>${d}</span><span class="${lc}">h</span>` : '')
                + `<span>${h}</span><span class="${lc}">j</span><span>${m}</span><span class="${lc}">m</span><span>${s}</span><span class="${lc}">d</span>`;
            }
            tick(); setInterval(tick,1000);
          })();
          </script>
        @else
          <div class="bg-amber-50 rounded-xl px-3 py-2.5">
            <p class="text-xs text-amber-600 font-medium mb-1.5">Dibuka dalam:</p>
            <div id="dpt-cd" class="flex items-center gap-1.5 font-bold text-amber-700 text-lg tabular-nums">
              <span id="dpt-h">--</span><span class="text-amber-400 text-sm font-normal">h</span>
              <span id="dpt-d">--</span><span class="text-amber-400 text-sm font-normal">j</span>
              <span id="dpt-m">--</span><span class="text-amber-400 text-sm font-normal">m</span>
              <span id="dpt-s">--</span><span class="text-amber-400 text-sm font-normal">d</span>
            </div>
          </div>
          @if($dptSchedule->start_date)
          <script>
          (function(){
            const t = new Date("{{ $dptSchedule->start_date->toIso8601String() }}");
            function tick(){
              const diff = t - new Date();
              const cd = document.getElementById('dpt-cd');
              if(diff <= 0){ cd.innerHTML='<span class="text-green-600 font-semibold">Segera dibuka!</span>'; return; }
              const ts = Math.floor(diff/1000), d = Math.floor(ts/86400);
              const h  = String(Math.floor((ts%86400)/3600)).padStart(2,'0');
              const m  = String(Math.floor((ts%3600)/60)).padStart(2,'0');
              const s  = String(ts%60).padStart(2,'0');
              const lc = 'text-amber-400 text-sm font-normal';
              cd.innerHTML = (d > 0 ? `<span>${d}</span><span class="${lc}">h</span>` : '')
                + `<span>${h}</span><span class="${lc}">j</span><span>${m}</span><span class="${lc}">m</span><span>${s}</span><span class="${lc}">d</span>`;
            }
            tick(); setInterval(tick,1000);
          })();
          </script>
          @endif
        @endif
      </div>
      @endif

      {{-- Hari Pemilihan --}}
      @if($electionSchedule && $electionSchedule->start_date)
      <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0
            {{ $eStatus === 'berlangsung' ? 'bg-green-100 text-green-600' : ($eStatus === 'selesai' ? 'bg-slate-100 text-slate-400' : 'bg-brand-100 text-brand-600') }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Hari Pemilihan</p>
            <p class="text-sm font-bold text-slate-800">
              {{ $electionSchedule->start_date->format('d M Y') }}
              @if($electionSchedule->end_date && $electionSchedule->end_date->ne($electionSchedule->start_date))
                — {{ $electionSchedule->end_date->format('d M Y') }}
              @endif
            </p>
          </div>
          @if($eStatus === 'selesai')
            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 text-slate-500">Selesai</span>
          @elseif($eStatus === 'berlangsung')
            <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-green-100 text-green-700 animate-pulse">● Live</span>
          @endif
        </div>

        @if($eStatus === 'selesai')
          <div class="flex items-center gap-2 bg-slate-50 rounded-xl px-3 py-2.5">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            <p class="text-sm font-semibold text-slate-500">Pemilihan telah selesai</p>
          </div>
        @elseif($eStatus === 'berlangsung' && $electionSchedule->end_date)
          <div class="bg-green-50 rounded-xl px-3 py-2.5">
            <p class="text-xs text-green-600 font-medium mb-1.5">Berakhir dalam:</p>
            <div id="elec-cd" class="flex items-center gap-1.5 font-bold text-green-700 text-lg tabular-nums">
              <span id="elec-h">--</span><span class="text-green-400 text-sm font-normal">j</span>
              <span id="elec-m">--</span><span class="text-green-400 text-sm font-normal">m</span>
              <span id="elec-s">--</span><span class="text-green-400 text-sm font-normal">d</span>
            </div>
          </div>
          <script>
          (function(){
            const t = new Date("{{ $electionSchedule->end_date->toIso8601String() }}");
            function tick(){
              const diff = t - new Date();
              const cd = document.getElementById('elec-cd');
              if(diff <= 0){ cd.innerHTML='<span class="text-slate-500 text-sm font-semibold">Selesai</span>'; return; }
              const ts = Math.floor(diff/1000), d = Math.floor(ts/86400);
              const h  = String(Math.floor((ts%86400)/3600)).padStart(2,'0');
              const m  = String(Math.floor((ts%3600)/60)).padStart(2,'0');
              const s  = String(ts%60).padStart(2,'0');
              const lc = 'text-green-400 text-sm font-normal';
              cd.innerHTML = (d > 0 ? `<span>${d}</span><span class="${lc}">h</span>` : '')
                + `<span>${h}</span><span class="${lc}">j</span><span>${m}</span><span class="${lc}">m</span><span>${s}</span><span class="${lc}">d</span>`;
            }
            tick(); setInterval(tick,1000);
          })();
          </script>
        @else
          <div class="bg-brand-50 rounded-xl px-3 py-2.5">
            <p class="text-xs text-brand-600 font-medium mb-1.5">Dimulai dalam:</p>
            <div id="elec-cd" class="flex items-center gap-1.5 font-bold text-brand-700 text-lg tabular-nums">
              <span id="elec-hari">--</span><span class="text-brand-400 text-sm font-normal">h</span>
              <span id="elec-h">--</span><span class="text-brand-400 text-sm font-normal">j</span>
              <span id="elec-m">--</span><span class="text-brand-400 text-sm font-normal">m</span>
              <span id="elec-s">--</span><span class="text-brand-400 text-sm font-normal">d</span>
            </div>
          </div>
          <script>
          (function(){
            const t = new Date("{{ $electionSchedule->start_date->toIso8601String() }}");
            function tick(){
              const diff = t - new Date();
              const cd = document.getElementById('elec-cd');
              if(diff <= 0){ cd.innerHTML='<span class="text-green-600 font-semibold">Segera dimulai!</span>'; return; }
              const ts = Math.floor(diff/1000), d = Math.floor(ts/86400);
              const h  = String(Math.floor((ts%86400)/3600)).padStart(2,'0');
              const m  = String(Math.floor((ts%3600)/60)).padStart(2,'0');
              const s  = String(ts%60).padStart(2,'0');
              const lc = 'text-brand-400 text-sm font-normal';
              cd.innerHTML = (d > 0 ? `<span>${d}</span><span class="${lc}">h</span>` : '')
                + `<span>${h}</span><span class="${lc}">j</span><span>${m}</span><span class="${lc}">m</span><span>${s}</span><span class="${lc}">d</span>`;
            }
            tick(); setInterval(tick,1000);
          })();
          </script>
        @endif
      </div>
      @endif

    </div>
  </div>
</section>
@endif

<!-- How it works -->
<section id="alur" class="max-w-5xl mx-auto px-4 sm:px-6 py-16 sm:py-24">
  <div class="text-center max-w-2xl mx-auto">
    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900">Cara Ikut Pemilihan</h2>
    <p class="mt-4 text-lg text-slate-600">Ikuti enam langkah berikut untuk berpartisipasi dalam pemilihan IKA UM Kendari.</p>
  </div>

  <div class="mt-12 relative">
    {{-- Garis penghubung (desktop) --}}
    <div class="hidden lg:block absolute top-8 left-0 right-0 h-0.5 bg-brand-100 mx-16 z-0"></div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5 relative z-10">

      {{-- Langkah 1 --}}
      <div class="bg-white rounded-2xl border border-slate-200 p-6 hover:border-brand-300 hover:shadow-sm transition">
        <div class="w-12 h-12 rounded-xl bg-brand-600 text-white font-extrabold text-lg flex items-center justify-center mb-4 shadow">01</div>
        <h3 class="font-bold text-slate-900">Klik Tombol Daftar</h3>
        <p class="mt-2 text-sm text-slate-600 leading-relaxed">Tekan tombol <strong>Daftar</strong> di pojok kanan atas halaman ini, lalu masukkan <strong>NIM</strong> dan <strong>Tanggal Lahir</strong> Anda untuk verifikasi data.</p>
      </div>

      {{-- Langkah 2 --}}
      <div class="bg-white rounded-2xl border border-slate-200 p-6 hover:border-brand-300 hover:shadow-sm transition">
        <div class="w-12 h-12 rounded-xl bg-brand-600 text-white font-extrabold text-lg flex items-center justify-center mb-4 shadow">02</div>
        <h3 class="font-bold text-slate-900">Buat Akun</h3>
        <p class="mt-2 text-sm text-slate-600 leading-relaxed">Jika data terdata, isi <strong>username</strong>, <strong>email</strong>, dan <strong>password</strong> untuk membuat akun. <span class="text-brand-600 font-medium">Catat akun Anda!</span></p>
      </div>

      {{-- Langkah 3 --}}
      <div class="bg-white rounded-2xl border border-slate-200 p-6 hover:border-brand-300 hover:shadow-sm transition">
        <div class="w-12 h-12 rounded-xl bg-brand-600 text-white font-extrabold text-lg flex items-center justify-center mb-4 shadow">03</div>
        <h3 class="font-bold text-slate-900">Login</h3>
        <p class="mt-2 text-sm text-slate-600 leading-relaxed">Masuk ke sistem menggunakan <strong>email</strong> dan <strong>password</strong> yang telah Anda daftarkan.</p>
      </div>

      {{-- Langkah 4 --}}
      <div class="bg-white rounded-2xl border border-slate-200 p-6 hover:border-brand-300 hover:shadow-sm transition">
        <div class="w-12 h-12 rounded-xl bg-indigo-700 text-white font-extrabold text-lg flex items-center justify-center mb-4 shadow">04</div>
        <h3 class="font-bold text-slate-900">Daftar DPT & Rekam Wajah</h3>
        <p class="mt-2 text-sm text-slate-600 leading-relaxed">Lakukan pendaftaran DPT dan rekam wajah Anda melalui menu <strong>Pendaftaran DPT</strong> agar dapat mengikuti voting.</p>
      </div>

      {{-- Langkah 5 --}}
      <div class="bg-white rounded-2xl border border-slate-200 p-6 hover:border-brand-300 hover:shadow-sm transition">
        <div class="w-12 h-12 rounded-xl bg-indigo-700 text-white font-extrabold text-lg flex items-center justify-center mb-4 shadow">05</div>
        <h3 class="font-bold text-slate-900">Verifikasi Wajah & Pilih</h3>
        <p class="mt-2 text-sm text-slate-600 leading-relaxed">Buka menu <strong>Pilih Kandidat</strong>, lakukan verifikasi wajah, lalu pilih kandidat sesuai pilihan Anda.</p>
      </div>

      {{-- Langkah 6 --}}
      <div class="bg-white rounded-2xl border border-slate-200 p-6 hover:border-green-300 hover:shadow-sm transition">
        <div class="w-12 h-12 rounded-xl bg-green-600 text-white font-extrabold text-lg flex items-center justify-center mb-4 shadow">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        </div>
        <h3 class="font-bold text-slate-900">Selesai!</h3>
        <p class="mt-2 text-sm text-slate-600 leading-relaxed">Pilihan tersimpan dan Anda telah berpartisipasi dalam <strong>Pemilihan IKA UM Kendari Tahun 2026</strong>. Terima kasih!</p>
      </div>

    </div>
  </div>
</section>

<!-- ═══ DOKUMEN ═══ -->
@if($documents->isNotEmpty())
<section class="max-w-6xl mx-auto px-4 sm:px-6 py-12 border-t border-slate-100">
  <div class="flex items-center justify-between mb-6">
    <div>
      <h2 class="text-xl font-extrabold text-slate-900">Dokumen &amp; Panduan</h2>
      <p class="text-sm text-slate-500 mt-0.5">Unduh dokumen resmi terkait pemilihan.</p>
    </div>
  </div>
  <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach($documents as $doc)
    @php
      $ext  = strtolower(pathinfo($doc->file_name, PATHINFO_EXTENSION));
      $icon = match($ext) {
        'pdf'         => ['bg' => 'bg-red-50',     'text' => 'text-red-500',    'label' => 'PDF'],
        'doc','docx'  => ['bg' => 'bg-blue-50',    'text' => 'text-blue-500',   'label' => 'DOCX'],
        'xls','xlsx'  => ['bg' => 'bg-green-50',   'text' => 'text-green-500',  'label' => 'XLSX'],
        'ppt','pptx'  => ['bg' => 'bg-orange-50',  'text' => 'text-orange-500', 'label' => 'PPTX'],
        'zip'         => ['bg' => 'bg-purple-50',  'text' => 'text-purple-500', 'label' => 'ZIP'],
        default       => ['bg' => 'bg-slate-100',  'text' => 'text-slate-400',  'label' => strtoupper($ext)],
      };
    @endphp
    <a href="{{ $doc->download_url }}" target="_blank"
       class="group flex items-center gap-4 bg-white border border-slate-200 hover:border-brand-300 hover:shadow-md rounded-2xl p-4 transition">
      <div class="w-12 h-12 rounded-xl {{ $icon['bg'] }} {{ $icon['text'] }} flex items-center justify-center shrink-0">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
      </div>
      <div class="flex-1 min-w-0">
        <p class="font-semibold text-slate-800 group-hover:text-brand-600 transition leading-snug">{{ $doc->title }}</p>
        @if($doc->description)
          <p class="text-xs text-slate-400 mt-0.5 leading-relaxed">{{ $doc->description }}</p>
        @endif
        <span class="inline-block mt-1 text-xs font-bold {{ $icon['text'] }}">{{ $icon['label'] }}{{ $doc->file_size ? ' · ' . $doc->file_size : '' }}</span>
      </div>
      <svg class="w-4 h-4 text-slate-400 group-hover:text-brand-600 transition shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
      </svg>
    </a>
    @endforeach
  </div>
</section>
@endif

<!-- CTA -->
<section id="cta" class="bg-brand-900">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 py-16 sm:py-20 text-center">
    <h2 class="text-3xl sm:text-4xl font-extrabold text-white">Suara Anda menentukan masa depan</h2>
    <p class="mt-4 text-lg text-brand-100">Jangan lewatkan kesempatan untuk berpartisipasi dalam pemilihan ini.</p>
    <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
      <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 bg-white text-brand-700 font-semibold px-6 py-3 rounded-lg hover:bg-brand-50 transition">
        Mulai Memilih Sekarang
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
      </a>
      <a href="#faq" class="inline-flex items-center justify-center gap-2 border border-white/30 text-white font-semibold px-6 py-3 rounded-lg hover:bg-white/10 transition">
        Punya Pertanyaan?
      </a>
    </div>
  </div>
</section>

<!-- Video Panduan -->
@if($youtubeEmbed)
<section id="video" class="py-16 sm:py-20 bg-slate-50">
  <div class="max-w-4xl mx-auto px-4 sm:px-6">
    <div class="text-center mb-10">
      <span class="inline-flex items-center gap-2 bg-red-50 text-red-600 text-xs font-semibold px-3 py-1.5 rounded-full mb-4">
        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
          <path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
        </svg>
        YouTube
      </span>
      <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900">{{ $youtubeTitle }}</h2>
      <p class="mt-3 text-slate-500">Tonton panduan penggunaan sistem e-voting sebelum mulai memilih.</p>
    </div>

    {{-- Embed 16:9 --}}
    <div class="relative w-full rounded-2xl overflow-hidden shadow-xl border border-slate-200"
         style="padding-top: 56.25%">
      <iframe src="{{ $youtubeEmbed }}"
              class="absolute inset-0 w-full h-full"
              frameborder="0"
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
              allowfullscreen
              loading="lazy"
              title="{{ $youtubeTitle }}"></iframe>
    </div>
  </div>
</section>
@endif

<!-- ═══ TIM FORMATUR ═══ -->
@if($formateurs->isNotEmpty())
<section id="formatur" class="bg-slate-50 border-t border-slate-200">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 py-16 sm:py-20">

    <div class="text-center max-w-2xl mx-auto mb-12">
      <span class="inline-flex items-center gap-2 bg-brand-100 text-brand-700 text-xs font-bold px-3 py-1.5 rounded-full mb-4 uppercase tracking-wide">Tim Formatur</span>
      <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900">Panitia Penyelenggara Pemilihan</h2>
      <p class="mt-4 text-lg text-slate-500 leading-relaxed">Orang-orang di balik terselenggaranya pemilihan ini.</p>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      @foreach($formateurs as $fm)
      <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md hover:border-brand-200 transition group">

        {{-- Foto --}}
        <div class="w-full aspect-square bg-brand-50 overflow-hidden">
          @if($fm->photo_url)
            <img src="{{ $fm->photo_url }}" alt="{{ $fm->alumni->name ?? '' }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
          @else
            <div class="w-full h-full flex items-center justify-center">
              <span class="text-brand-600 font-extrabold text-6xl leading-none">
                {{ strtoupper(substr($fm->alumni->name ?? 'F', 0, 1)) }}
              </span>
            </div>
          @endif
        </div>

        {{-- Info --}}
        <div class="p-5">
          <span class="inline-block text-xs font-bold text-brand-600 bg-brand-50 px-2.5 py-1 rounded-lg mb-2">
            {{ $fm->jabatan }}
          </span>
          <p class="font-bold text-slate-900 leading-snug">{{ $fm->alumni->name ?? '—' }}</p>
          @if($fm->alumni?->department || $fm->alumni?->faculty)
          <p class="text-xs text-slate-400 mt-1 leading-relaxed">
            {{ $fm->alumni->department ?? '' }}
            @if($fm->alumni?->department && $fm->alumni?->faculty) · @endif
            {{ $fm->alumni->faculty ?? '' }}
          </p>
          @endif
          @if($fm->deskripsi)
          <p class="text-xs text-slate-500 mt-3 leading-relaxed line-clamp-4">{{ $fm->deskripsi }}</p>
          @endif
        </div>

      </div>
      @endforeach
    </div>

  </div>
</section>
@endif

<!-- FAQ -->
<section id="faq" class="max-w-3xl mx-auto px-4 sm:px-6 py-16 sm:py-24">
  <div class="text-center">
    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900">Pertanyaan umum</h2>
    <p class="mt-4 text-lg text-slate-600">Hal-hal yang sering ditanyakan tentang E-Voting.</p>
  </div>
  <div class="mt-10 space-y-3">
    <details class="group bg-white border border-slate-200 rounded-xl px-5 py-4">
      <summary class="flex items-center justify-between cursor-pointer font-medium text-slate-900 list-none">
        Apakah suara saya benar-benar rahasia?
        <svg class="w-5 h-5 text-slate-400 group-open:rotate-180 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
      </summary>
      <p class="mt-3 text-slate-600 leading-relaxed">Ya. Suara Anda dienkripsi dan dipisahkan dari identitas. Tidak ada pihak, termasuk admin, yang dapat mengetahui pilihan Anda.</p>
    </details>
    <details class="group bg-white border border-slate-200 rounded-xl px-5 py-4">
      <summary class="flex items-center justify-between cursor-pointer font-medium text-slate-900 list-none">
        Bagaimana cara memastikan suara saya tercatat?
        <svg class="w-5 h-5 text-slate-400 group-open:rotate-180 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
      </summary>
      <p class="mt-3 text-slate-600 leading-relaxed">Setelah memilih, Anda menerima bukti digital berupa kode unik yang bisa diverifikasi pada jejak audit publik.</p>
    </details>
    <details class="group bg-white border border-slate-200 rounded-xl px-5 py-4">
      <summary class="flex items-center justify-between cursor-pointer font-medium text-slate-900 list-none">
        Perangkat apa yang bisa saya gunakan?
        <svg class="w-5 h-5 text-slate-400 group-open:rotate-180 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
      </summary>
      <p class="mt-3 text-slate-600 leading-relaxed">Ponsel, tablet, maupun komputer dengan browser modern. Tidak perlu menginstal aplikasi tambahan.</p>
    </details>
    <details class="group bg-white border border-slate-200 rounded-xl px-5 py-4">
      <summary class="flex items-center justify-between cursor-pointer font-medium text-slate-900 list-none">
        Bisakah saya mengubah pilihan setelah memilih?
        <svg class="w-5 h-5 text-slate-400 group-open:rotate-180 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
      </summary>
      <p class="mt-3 text-slate-600 leading-relaxed">Tidak. Demi menjaga integritas, suara bersifat final setelah dikonfirmasi. Pastikan pilihan Anda sebelum mengirim.</p>
    </details>
  </div>
</section>

<!-- Footer -->
<footer class="bg-slate-900 text-slate-400">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 py-12 grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
    <div>
      <div class="flex items-center gap-2.5 mb-3">
        <img src="{{ asset('images/Logo2.png') }}" alt="Logo" class="w-9 h-9 object-contain" />
        <span class="font-bold text-lg text-white">{{ $siteName }}</span>
      </div>
      <p class="text-sm leading-relaxed">Sistem pemilihan digital yang aman, transparan, dan dapat dipercaya.</p>
    </div>
    <div>
      <h4 class="font-semibold text-white mb-3">Navigasi</h4>
      <ul class="space-y-2 text-sm">
        <li><a href="#fitur"    class="hover:text-white transition">Fitur</a></li>
        <li><a href="#alur"    class="hover:text-white transition">Cara Kerja</a></li>
        @if($formateurs->isNotEmpty())
        <li><a href="#formatur" class="hover:text-white transition">Tim Formatur</a></li>
        @endif
        @if($youtubeEmbed)
        <li><a href="#video"   class="hover:text-white transition">Video Panduan</a></li>
        @endif
        <li><a href="#faq"     class="hover:text-white transition">FAQ</a></li>
      </ul>
    </div>
    <div>
      <h4 class="font-semibold text-white mb-3">Bantuan</h4>
      <ul class="space-y-2 text-sm">
        <li><a href="#" class="hover:text-white transition">Panduan Pemilih</a></li>
        <li><a href="#" class="hover:text-white transition">Hubungi Kami</a></li>
        <li><a href="#" class="hover:text-white transition">Kebijakan Privasi</a></li>
        <li><a href="#" class="hover:text-white transition">Syarat &amp; Ketentuan</a></li>
      </ul>
    </div>
    <div>
      <h4 class="font-semibold text-white mb-3">Akun</h4>
      <ul class="space-y-2 text-sm">
        <li><a href="{{ route('login') }}"                 class="hover:text-white transition">Masuk</a></li>
        <li><a href="{{ route('alumni.register.step1') }}" class="hover:text-white transition">Daftar Alumni</a></li>
      </ul>
    </div>
  </div>
  <div class="border-t border-slate-800">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-5 text-sm text-center sm:text-left">
      &copy; {{ date('Y') }} E-Voting System. Seluruh hak cipta dilindungi.
    </div>
  </div>
</footer>

<script>
function toggleMobileNav() {
  document.getElementById('mobileNav').classList.toggle('hidden');
}
</script>
</body>
</html>
