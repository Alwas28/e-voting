<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
@php
  $siteName   = \App\Models\Setting::get('site_name', 'E-Voting');
  $pageTitle  = 'Profil ' . $candidate->name . ' — ' . $siteName;
  $pageDesc   = 'Kenali kandidat ' . $candidate->name . ($candidate->vision ? ': ' . \Illuminate\Support\Str::limit($candidate->vision, 120) : '');
  $thumbUrl   = $candidate->photo ? asset('storage/' . $candidate->photo) : asset('images/Logo2.png');
  $pageUrl    = url('/kandidat/' . $candidate->id);
@endphp
<title>{{ $pageTitle }}</title>
<meta name="description" content="{{ $pageDesc }}" />
{{-- Open Graph --}}
<meta property="og:type"        content="profile" />
<meta property="og:url"         content="{{ $pageUrl }}" />
<meta property="og:title"       content="{{ $pageTitle }}" />
<meta property="og:description" content="{{ $pageDesc }}" />
<meta property="og:image"       content="{{ $thumbUrl }}" />
<meta property="og:locale"      content="id_ID" />
<meta property="og:site_name"   content="{{ $siteName }}" />
{{-- Twitter Card --}}
<meta name="twitter:card"        content="summary_large_image" />
<meta name="twitter:title"       content="{{ $pageTitle }}" />
<meta name="twitter:description" content="{{ $pageDesc }}" />
<meta name="twitter:image"       content="{{ $thumbUrl }}" />
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
  body { font-family: 'Inter', sans-serif; }
  :root {
    --brand-50:#eef2ff;--brand-100:#e0e7ff;--brand-500:#6366f1;
    --brand-600:#4f46e5;--brand-700:#4338ca;--brand-900:#312e81;
  }
  html { scroll-behavior: smooth; }
  .profile-html h1, .profile-html h2 { font-weight: 700; margin-bottom: .5rem; }
  .profile-html h2 { font-size: 1.05rem; }
  .profile-html p  { margin-bottom: .75rem; line-height: 1.8; }
  .profile-html ul { list-style: disc; padding-left: 1.25rem; margin-bottom: .75rem; }
  .profile-html ol { list-style: decimal; padding-left: 1.25rem; margin-bottom: .75rem; }
  .profile-html li { margin-bottom: .25rem; }
  .profile-html strong { font-weight: 600; }
  .profile-html em  { font-style: italic; }
  .profile-html a   { color: #4f46e5; text-decoration: underline; }
</style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

<!-- Navbar (sama dengan halaman utama) -->
<header id="nav" class="sticky top-0 z-50 bg-white/90 backdrop-blur border-b border-slate-200">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">

    <a href="{{ url('/') }}" class="flex items-center gap-2.5">
      <div class="w-9 h-9 rounded-lg bg-brand-600 text-white flex items-center justify-center">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>
      <span class="font-bold text-lg text-slate-900">E-Voting</span>
    </a>

    <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-600">
      <a href="{{ url('/') }}#kandidat" class="hover:text-brand-600 transition">Kandidat</a>
      <a href="{{ url('/') }}#fitur"    class="hover:text-brand-600 transition">Fitur</a>
      <a href="{{ url('/') }}#alur"     class="hover:text-brand-600 transition">Cara Kerja</a>
      <a href="{{ url('/') }}#faq"      class="hover:text-brand-600 transition">FAQ</a>
    </nav>

    <div class="flex items-center gap-2">
      <a href="{{ route('login') }}" class="hidden sm:inline-flex text-sm font-medium text-slate-600 hover:text-brand-600 px-4 py-2 transition">Masuk</a>
      <a href="{{ route('login') }}" class="inline-flex text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 px-4 py-2 rounded-lg transition">Mulai Voting</a>
      <button onclick="toggleMobileNav()" class="md:hidden p-2 -mr-1 rounded-lg hover:bg-slate-100">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
    </div>
  </div>

  <!-- Mobile nav -->
  <div id="mobileNav" class="hidden md:hidden border-t border-slate-200 bg-white px-4 py-3 space-y-1">
    <a href="{{ url('/') }}#kandidat" onclick="toggleMobileNav()" class="block px-3 py-2.5 rounded-lg text-slate-700 hover:bg-slate-50">Kandidat</a>
    <a href="{{ url('/') }}#fitur"    onclick="toggleMobileNav()" class="block px-3 py-2.5 rounded-lg text-slate-700 hover:bg-slate-50">Fitur</a>
    <a href="{{ url('/') }}#alur"     onclick="toggleMobileNav()" class="block px-3 py-2.5 rounded-lg text-slate-700 hover:bg-slate-50">Cara Kerja</a>
    <a href="{{ url('/') }}#faq"      onclick="toggleMobileNav()" class="block px-3 py-2.5 rounded-lg text-slate-700 hover:bg-slate-50">FAQ</a>
    <a href="{{ route('login') }}"    onclick="toggleMobileNav()" class="block px-3 py-2.5 rounded-lg text-slate-700 hover:bg-slate-50">Masuk</a>
  </div>
</header>

<main class="max-w-4xl mx-auto px-4 sm:px-6 py-10 space-y-6">

  {{-- Card atas: KOP + foto + identitas --}}
  <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

    {{-- ── KOP Banner ───────────────────────────────── --}}
    <div class="bg-gradient-to-br from-indigo-700 to-indigo-950 relative overflow-hidden">

      {{-- Dekorasi background --}}
      <div class="absolute inset-0 opacity-10 pointer-events-none">
        <div class="absolute -top-10 -right-10 w-64 h-64 rounded-full border-8 border-white"></div>
        <div class="absolute -bottom-6 right-40 w-28 h-28 rounded-full border-4 border-white"></div>
      </div>

      {{-- Isi KOP --}}
      <div class="relative z-10 flex items-center gap-4 px-7 py-5">

        {{-- Logo --}}
        <img src="{{ asset('images/Logo2.png') }}" alt="Logo UMK"
             class="w-16 h-16 object-contain drop-shadow-lg shrink-0">

        {{-- Garis pemisah vertikal --}}
        <div class="w-px h-14 bg-white/30 shrink-0"></div>

        {{-- Teks KOP --}}
        <div class="flex-1 min-w-0">
          <p class="text-white/60 text-[10px] font-semibold uppercase tracking-[0.2em] leading-none">
            Pemilihan Umum Alumni
          </p>
          <h2 class="text-white font-extrabold text-xl sm:text-2xl leading-snug mt-1">
            Calon Ketua Alumni
          </h2>
          <p class="text-indigo-200 text-xs font-medium mt-0.5">
            Universitas Muhammadiyah Kendari
          </p>
          <p class="text-indigo-300/60 text-[10px] mt-0.5">
            Periode {{ date('Y') }} / {{ date('Y') + 1 }}
          </p>
        </div>

        {{-- Nomor urut --}}
        <div class="shrink-0">
          <div class="w-14 h-14 rounded-xl bg-white text-indigo-700 flex flex-col items-center justify-center shadow-lg">
            <p class="text-[9px] font-bold uppercase tracking-wider leading-none">No.</p>
            <p class="text-[1.6rem] font-extrabold leading-none mt-0.5">
              {{ str_pad($candidate->number, 2, '0', STR_PAD_LEFT) }}
            </p>
          </div>
        </div>

      </div>

      {{-- Garis bawah dekoratif --}}
      <div class="h-[3px] bg-gradient-to-r from-indigo-500 via-white/50 to-indigo-500"></div>
    </div>

    {{-- ── Area Identitas (di bawah KOP, tidak overlap) ── --}}
    <div class="flex items-center gap-6 px-7 py-6">

      {{-- Foto persegi besar di kiri --}}
      <div class="w-44 h-44 rounded-2xl overflow-hidden border-4 border-indigo-100 shadow-xl shrink-0 bg-indigo-50">
        @if($candidate->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($candidate->photo))
          <img src="{{ $candidate->photo_url }}" alt="{{ $candidate->name }}"
               class="w-full h-full object-cover object-top">
        @else
          @php
            $parts    = explode(' ', $candidate->name);
            $initials = strtoupper(substr($parts[0],0,1)) . strtoupper(substr($parts[1] ?? '',0,1));
          @endphp
          <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-indigo-800 flex items-center justify-center">
            <span class="text-white font-extrabold text-5xl">{{ $initials }}</span>
          </div>
        @endif
      </div>

      {{-- Identitas --}}
      <div class="min-w-0">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-tight">
          {{ $candidate->name }}
        </h1>
        @if($candidate->faculty || $candidate->department)
        <p class="text-slate-500 mt-1 text-sm">
          {{ implode(' · ', array_filter([$candidate->faculty, $candidate->department])) }}
        </p>
        @endif
        <div class="flex flex-wrap gap-2 mt-3">
          <span class="inline-flex items-center gap-1.5 bg-indigo-50 text-indigo-700 text-xs font-semibold px-3 py-1.5 rounded-full">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Kandidat
          </span>
          <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 text-xs font-semibold px-3 py-1.5 rounded-full">
            <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
            Aktif
          </span>
        </div>
      </div>

    </div>
  </div>

  {{-- Visi & Misi dalam 1 card --}}
  <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-6">

    {{-- Visi --}}
    <div>
      <div class="flex items-center gap-2.5 mb-3">
        <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
          </svg>
        </div>
        <h2 class="font-bold text-slate-900">Visi</h2>
      </div>
      @if($candidate->vision)
        <p class="text-slate-700 leading-relaxed">{{ $candidate->vision }}</p>
      @else
        <p class="text-slate-300 italic text-sm">Belum diisi</p>
      @endif
    </div>

    <div class="h-px bg-slate-100"></div>

    {{-- Misi --}}
    <div>
      <div class="flex items-center gap-2.5 mb-3">
        <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
          </svg>
        </div>
        <h2 class="font-bold text-slate-900">Misi</h2>
      </div>
      @if($candidate->mission)
        <p class="text-slate-700 leading-relaxed whitespace-pre-line">{{ $candidate->mission }}</p>
      @else
        <p class="text-slate-300 italic text-sm">Belum diisi</p>
      @endif
    </div>

  </div>

  {{-- Profil & Perkenalan --}}
  @if($candidate->profile)
  <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
    <div class="flex items-center gap-2.5 mb-5">
      <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
      </div>
      <h2 class="font-bold text-slate-900">Profil &amp; Perkenalan</h2>
    </div>
    <div class="profile-html text-slate-700">
      {!! $candidate->profile !!}
    </div>
  </div>
  @endif

  {{-- Navigasi antar kandidat --}}
  @php
    $allCandidates = \App\Models\Candidate::whereHas('period', fn($q) => $q->where('is_active', true))
        ->where('is_active', true)->orderBy('number')->get(['id','name','number','photo']);
    $currentIdx = $allCandidates->search(fn($c) => $c->id === $candidate->id);
    $prev = $currentIdx > 0 ? $allCandidates[$currentIdx - 1] : null;
    $next = $currentIdx < $allCandidates->count() - 1 ? $allCandidates[$currentIdx + 1] : null;
  @endphp

  @if($prev || $next)
  <div class="flex gap-4">
    @if($prev)
    <a href="{{ route('candidate.profile', $prev) }}"
       class="flex-1 bg-white rounded-2xl border border-slate-200 shadow-sm px-5 py-4 flex items-center gap-3 hover:border-indigo-300 hover:shadow-md transition group">
      <svg class="w-5 h-5 text-slate-400 group-hover:text-indigo-600 transition shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
      </svg>
      <div class="min-w-0">
        <p class="text-xs text-slate-400">Sebelumnya</p>
        <p class="text-sm font-semibold text-slate-800 truncate">{{ $prev->name }}</p>
      </div>
    </a>
    @else
    <div class="flex-1"></div>
    @endif

    @if($next)
    <a href="{{ route('candidate.profile', $next) }}"
       class="flex-1 bg-white rounded-2xl border border-slate-200 shadow-sm px-5 py-4 flex items-center justify-end gap-3 hover:border-indigo-300 hover:shadow-md transition group text-right">
      <div class="min-w-0">
        <p class="text-xs text-slate-400">Berikutnya</p>
        <p class="text-sm font-semibold text-slate-800 truncate">{{ $next->name }}</p>
      </div>
      <svg class="w-5 h-5 text-slate-400 group-hover:text-indigo-600 transition shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
      </svg>
    </a>
    @else
    <div class="flex-1"></div>
    @endif
  </div>
  @endif

</main>

<footer class="text-center text-slate-400 text-xs py-8 mt-4">
  &copy; {{ date('Y') }} E-Voting System — <a href="{{ url('/') }}" class="hover:text-slate-600 transition">Halaman Utama</a>
</footer>

<script>
function toggleMobileNav() {
  document.getElementById('mobileNav').classList.toggle('hidden');
}
</script>
</body>
</html>
