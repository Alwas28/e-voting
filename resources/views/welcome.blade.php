<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>E-Voting — Pemilihan Digital yang Aman & Transparan</title>
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
      <div class="w-9 h-9 rounded-lg bg-brand-600 text-white flex items-center justify-center">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
      <span class="font-bold text-lg text-slate-900">E-Voting</span>
    </a>

    <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-600">
      <a href="#kandidat" class="hover:text-brand-600 transition">Kandidat</a>
      <a href="#fitur"    class="hover:text-brand-600 transition">Fitur</a>
      <a href="#alur"     class="hover:text-brand-600 transition">Cara Kerja</a>
      <a href="#faq"      class="hover:text-brand-600 transition">FAQ</a>
    </nav>

    <div class="flex items-center gap-2">
      <a href="{{ route('login') }}" class="hidden sm:inline-flex text-sm font-medium text-slate-600 hover:text-brand-600 px-4 py-2 transition">Masuk</a>
      <a href="#cta" class="inline-flex text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 px-4 py-2 rounded-lg transition">Mulai Voting</a>
      <button onclick="toggleMobileNav()" class="md:hidden p-2 -mr-1 rounded-lg hover:bg-slate-100">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
    </div>
  </div>

  <!-- Mobile nav -->
  <div id="mobileNav" class="hidden md:hidden border-t border-slate-200 bg-white px-4 py-3 space-y-1">
    <a href="#kandidat" onclick="toggleMobileNav()" class="block px-3 py-2.5 rounded-lg text-slate-700 hover:bg-slate-50">Kandidat</a>
    <a href="#fitur"    onclick="toggleMobileNav()" class="block px-3 py-2.5 rounded-lg text-slate-700 hover:bg-slate-50">Fitur</a>
    <a href="#alur"     onclick="toggleMobileNav()" class="block px-3 py-2.5 rounded-lg text-slate-700 hover:bg-slate-50">Cara Kerja</a>
    <a href="#faq"      onclick="toggleMobileNav()" class="block px-3 py-2.5 rounded-lg text-slate-700 hover:bg-slate-50">FAQ</a>
    <a href="{{ route('login') }}" class="block px-3 py-2.5 rounded-lg text-slate-700 hover:bg-slate-50">Masuk</a>
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
        Pemilihan Digital yang <span class="text-brand-600">Aman</span> & <span class="text-brand-600">Transparan</span>
      </h1>
      <p class="mt-5 text-lg text-slate-600 leading-relaxed">
        Berikan suara Anda kapan saja, di mana saja. Sistem e-voting terenkripsi dengan hasil real-time yang dapat diverifikasi oleh semua pihak.
      </p>
      <div class="mt-8 flex flex-col sm:flex-row gap-3">
        <a href="#cta" class="inline-flex items-center justify-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-semibold px-6 py-3 rounded-lg transition">
          Mulai Memilih
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </a>
        <a href="#kandidat" class="inline-flex items-center justify-center gap-2 border border-slate-300 hover:border-brand-600 hover:text-brand-600 text-slate-700 font-semibold px-6 py-3 rounded-lg transition">
          Kenali Kandidat
        </a>
      </div>
      {{-- Timeline / Countdown jika jadwal ada, fallback ke stats statis --}}
      @if($electionSchedule && $electionSchedule->start_date)
      @php
        $now   = now();
        $eStatus = $electionSchedule->status;
        $dStatus = $dptSchedule?->status;
      @endphp
      <div class="mt-10 space-y-3">

        {{-- Pendaftaran DPT --}}
        @if($dptSchedule && $dptSchedule->start_date)
        <div class="flex items-center gap-3 bg-slate-50 border border-slate-200 rounded-xl px-4 py-3">
          <div class="w-8 h-8 rounded-lg shrink-0 flex items-center justify-center
            {{ $dStatus === 'berlangsung' ? 'bg-blue-100 text-blue-600' : ($dStatus === 'selesai' ? 'bg-slate-100 text-slate-400' : 'bg-amber-50 text-amber-500') }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-xs font-semibold text-slate-500">Pendaftaran DPT</p>
            <p class="text-sm font-medium text-slate-800">
              {{ $dptSchedule->start_date->format('d M') }} — {{ $dptSchedule->end_date?->format('d M Y') ?? '—' }}
            </p>
          </div>
          <span class="text-xs font-semibold px-2.5 py-1 rounded-full shrink-0
            {{ $dStatus === 'berlangsung' ? 'bg-blue-100 text-blue-700' : ($dStatus === 'selesai' ? 'bg-slate-100 text-slate-500' : 'bg-amber-50 text-amber-700') }}">
            {{ $dptSchedule->status_label }}
          </span>
        </div>
        @endif

        {{-- Pemilihan: hitung mundur atau status --}}
        <div class="bg-brand-50 border border-brand-100 rounded-xl px-4 py-3">
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg shrink-0 flex items-center justify-center
              {{ $eStatus === 'berlangsung' ? 'bg-green-100 text-green-600' : ($eStatus === 'selesai' ? 'bg-slate-100 text-slate-400' : 'bg-brand-100 text-brand-600') }}">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-xs font-semibold text-slate-500">Hari Pemilihan</p>
              <p class="text-sm font-medium text-slate-800">
                {{ $electionSchedule->start_date->format('d M Y') }}
                @if($electionSchedule->end_date && $electionSchedule->end_date->ne($electionSchedule->start_date))
                  — {{ $electionSchedule->end_date->format('d M Y') }}
                @endif
              </p>
            </div>
            @if($eStatus === 'berlangsung')
              <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-green-100 text-green-700 animate-pulse shrink-0">● Berlangsung</span>
            @elseif($eStatus === 'selesai')
              <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 shrink-0">Selesai</span>
            @endif
          </div>

          {{-- Hitung mundur hanya jika belum mulai --}}
          @if($eStatus === 'belum_dimulai')
          <div class="mt-3 flex items-center gap-2">
            <p class="text-xs text-slate-400 shrink-0">Mulai dalam:</p>
            <div id="countdown" class="flex items-center gap-1.5 font-bold text-brand-700 text-sm">
              <span class="bg-white border border-brand-100 rounded-lg px-2 py-0.5" id="cd-hari">--</span>
              <span class="text-slate-400 text-xs">h</span>
              <span class="bg-white border border-brand-100 rounded-lg px-2 py-0.5" id="cd-jam">--</span>
              <span class="text-slate-400 text-xs">j</span>
              <span class="bg-white border border-brand-100 rounded-lg px-2 py-0.5" id="cd-menit">--</span>
              <span class="text-slate-400 text-xs">m</span>
              <span class="bg-white border border-brand-100 rounded-lg px-2 py-0.5" id="cd-detik">--</span>
              <span class="text-slate-400 text-xs">d</span>
            </div>
          </div>
          <script>
            (function(){
              const target = new Date("{{ $electionSchedule->start_date->toIso8601String() }}");
              function tick(){
                const diff = target - new Date();
                if(diff <= 0){ document.getElementById('countdown').innerHTML='<span class="text-green-600">Dimulai!</span>'; return; }
                const d = Math.floor(diff/86400000);
                const h = Math.floor((diff%86400000)/3600000);
                const m = Math.floor((diff%3600000)/60000);
                const s = Math.floor((diff%60000)/1000);
                document.getElementById('cd-hari').textContent  = String(d).padStart(2,'0');
                document.getElementById('cd-jam').textContent   = String(h).padStart(2,'0');
                document.getElementById('cd-menit').textContent = String(m).padStart(2,'0');
                document.getElementById('cd-detik').textContent = String(s).padStart(2,'0');
              }
              tick(); setInterval(tick,1000);
            })();
          </script>
          @endif
        </div>

      </div>
      @else
      {{-- Fallback: stats statis --}}
      <div class="mt-10 flex items-center gap-8">
        <div><p class="text-2xl font-bold text-slate-900">{{ $candidates->count() }}</p><p class="text-sm text-slate-500">Kandidat</p></div>
        <div class="w-px h-10 bg-slate-200"></div>
        <div><p class="text-2xl font-bold text-slate-900">100%</p><p class="text-sm text-slate-500">Terenkripsi</p></div>
        <div class="w-px h-10 bg-slate-200"></div>
        <div><p class="text-2xl font-bold text-slate-900">Real-Time</p><p class="text-sm text-slate-500">Hasil langsung</p></div>
      </div>
      @endif
    </div>

    <!-- Hero card -->
    <div class="relative">
      <div class="bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-200/60 p-6">
        <div class="flex items-center justify-between mb-5">
          <h3 class="font-semibold text-slate-900">Kandidat Periode Ini</h3>
          <span class="text-xs bg-green-50 text-green-700 px-2.5 py-1 rounded-full font-medium">● Aktif</span>
        </div>
        @if($candidates->isEmpty())
        <p class="text-sm text-slate-400 text-center py-4">Belum ada kandidat terdaftar</p>
        @else
        <div class="space-y-3">
          @foreach($candidates->take(4) as $c)
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full overflow-hidden bg-brand-100 shrink-0 flex items-center justify-center">
              @if($c->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($c->photo))
                <img src="{{ $c->photo_url }}" alt="{{ $c->name }}" class="w-full h-full object-cover">
              @else
                <span class="text-brand-700 font-bold text-xs">{{ strtoupper(substr($c->name,0,1)) }}{{ strtoupper(substr(explode(' ', $c->name)[1] ?? '',0,1)) }}</span>
              @endif
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-semibold text-slate-800 truncate">{{ $c->name }}</p>
              <div class="h-1.5 bg-slate-100 rounded-full mt-1 overflow-hidden">
                <div class="h-full bg-brand-500 rounded-full" style="width: {{ 20 + ($loop->index * 15) }}%"></div>
              </div>
            </div>
            <span class="text-xs font-bold text-brand-700 shrink-0">No. {{ $c->number }}</span>
          </div>
          @endforeach
        </div>
        @endif
        <a href="#kandidat" class="mt-5 flex items-center justify-center gap-2 text-sm text-brand-600 font-semibold hover:text-brand-700 transition pt-4 border-t border-slate-100">
          Lihat semua kandidat
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" transform="rotate(-90 12 12)"/></svg>
        </a>
      </div>
      <div class="absolute -z-10 -top-6 -right-6 w-32 h-32 bg-brand-100 rounded-full blur-2xl"></div>
    </div>
  </div>
</section>

<!-- Trust strip -->
<section class="border-y border-slate-200 bg-slate-50">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6 grid grid-cols-2 sm:grid-cols-4 gap-6 text-center">
    <div class="flex flex-col items-center gap-1">
      <svg class="w-6 h-6 text-brand-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
      <p class="text-sm font-medium text-slate-700">Enkripsi End-to-End</p>
    </div>
    <div class="flex flex-col items-center gap-1">
      <svg class="w-6 h-6 text-brand-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6l9 4 9-4m-9 4v10"/></svg>
      <p class="text-sm font-medium text-slate-700">Audit Transparan</p>
    </div>
    <div class="flex flex-col items-center gap-1">
      <svg class="w-6 h-6 text-brand-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
      <p class="text-sm font-medium text-slate-700">Hasil Real-Time</p>
    </div>
    <div class="flex flex-col items-center gap-1">
      <svg class="w-6 h-6 text-brand-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"/></svg>
      <p class="text-sm font-medium text-slate-700">Verifikasi Identitas</p>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════
     KANDIDAT
════════════════════════════════════════════════════ -->
<section id="kandidat" class="py-16 sm:py-24 bg-gradient-to-b from-white to-slate-50">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">

    <div class="text-center max-w-2xl mx-auto mb-14">
      <span class="inline-flex items-center gap-1.5 bg-brand-100 text-brand-700 text-xs font-semibold px-3 py-1.5 rounded-full mb-4">
        <span class="w-1.5 h-1.5 bg-brand-600 rounded-full"></span> Periode Aktif
      </span>
      <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900">Kenali Kandidat</h2>
      <p class="mt-4 text-lg text-slate-500">Pelajari visi, misi, dan profil sebelum memberikan suara Anda.</p>
    </div>

    @if($candidates->isEmpty())
    <div class="text-center py-16 bg-white rounded-3xl border border-slate-200 shadow-sm">
      <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
      </div>
      <p class="text-slate-600 font-semibold">Kandidat belum diumumkan</p>
      <p class="text-slate-400 text-sm mt-1">Pantau terus halaman ini untuk informasi terbaru.</p>
    </div>

    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-{{ min($candidates->count(), 4) }} gap-8">
      @foreach($candidates as $c)

      <div class="group bg-white rounded-2xl shadow-sm border border-slate-200 hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 overflow-hidden flex flex-col">

        {{-- Banner --}}
        <div class="relative h-44 bg-gradient-to-br from-brand-600 to-indigo-800 flex items-end justify-center">
          {{-- Dekorasi --}}
          <div class="absolute inset-0 overflow-hidden opacity-10">
            <div class="absolute -top-4 -right-4 w-32 h-32 rounded-full border-8 border-white"></div>
            <div class="absolute bottom-2 left-6 w-16 h-16 rounded-full border-4 border-white"></div>
          </div>

          {{-- Nomor urut --}}
          <div class="absolute top-4 left-4 bg-white/20 backdrop-blur-sm text-white text-xs font-bold px-3 py-1.5 rounded-full border border-white/30 tracking-wide">
            No. {{ str_pad($c->number, 2, '0', STR_PAD_LEFT) }}
          </div>

          {{-- Foto persegi besar menggantung ke bawah --}}
          <div class="relative z-10 translate-y-1/2">
            <div class="w-32 h-32 rounded-2xl ring-4 ring-white shadow-xl overflow-hidden bg-white">
              @if($c->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($c->photo))
                <img src="{{ $c->photo_url }}" alt="{{ $c->name }}"
                     class="w-full h-full object-cover object-top">
              @else
                @php
                  $parts    = explode(' ', $c->name);
                  $initials = strtoupper(substr($parts[0],0,1)) . strtoupper(substr($parts[1] ?? '',0,1));
                @endphp
                <div class="w-full h-full bg-gradient-to-br from-brand-500 to-indigo-700 flex items-center justify-center">
                  <span class="text-white font-extrabold text-3xl tracking-tight">{{ $initials }}</span>
                </div>
              @endif
            </div>
          </div>
        </div>

        {{-- Konten --}}
        <div class="flex flex-col flex-1 text-center px-6 pb-6" style="padding-top:4.5rem">

          <h3 class="text-lg font-bold text-slate-900 leading-tight">{{ $c->name }}</h3>

          @if($c->faculty || $c->department)
          <p class="text-xs text-slate-400 mt-1">
            {{ implode(' · ', array_filter([$c->faculty, $c->department])) }}
          </p>
          @endif

          <div class="my-4 h-px bg-slate-100"></div>

          @if($c->vision)
          <div class="flex-1 text-left">
            <p class="text-[10px] font-bold text-brand-500 uppercase tracking-widest mb-1.5">Visi</p>
            <p class="text-sm text-slate-600 leading-relaxed line-clamp-3">{{ $c->vision }}</p>
          </div>
          @else
          <div class="flex-1 flex items-center justify-center">
            <p class="text-xs text-slate-300 italic">Visi belum diisi</p>
          </div>
          @endif

          <a href="{{ route('candidate.profile', $c) }}"
             class="mt-5 w-full bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm py-3 rounded-xl
                    transition-all duration-200 flex items-center justify-center gap-2 shadow-sm group-hover:shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            Lihat Profil
          </a>
        </div>

      </div>
      @endforeach
    </div>
    @endif

  </div>
</section>

<!-- ═══════════════════════════════════════════════════
     FITUR (dipindah ke bawah)
════════════════════════════════════════════════════ -->
<section id="fitur" class="bg-slate-50 border-y border-slate-200">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 py-16 sm:py-24">
    <div class="text-center max-w-2xl mx-auto">
      <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900">Kenapa memilih E-Voting?</h2>
      <p class="mt-4 text-lg text-slate-600">Dirancang untuk keamanan, kemudahan, dan kepercayaan di setiap suara.</p>
    </div>

    <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <div class="bg-white rounded-xl border border-slate-200 p-6 hover:border-brand-600 hover:shadow-lg transition">
        <div class="w-12 h-12 rounded-lg bg-brand-50 flex items-center justify-center text-brand-600">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        </div>
        <h3 class="mt-4 font-semibold text-lg text-slate-900">Aman & Terenkripsi</h3>
        <p class="mt-2 text-slate-600 leading-relaxed">Setiap suara dilindungi enkripsi tingkat militer. Tidak ada pihak yang bisa melihat pilihan Anda.</p>
      </div>
      <div class="bg-white rounded-xl border border-slate-200 p-6 hover:border-brand-600 hover:shadow-lg transition">
        <div class="w-12 h-12 rounded-lg bg-brand-50 flex items-center justify-center text-brand-600">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <h3 class="mt-4 font-semibold text-lg text-slate-900">Transparan</h3>
        <p class="mt-2 text-slate-600 leading-relaxed">Hasil dapat diaudit publik tanpa mengungkap identitas pemilih.</p>
      </div>
      <div class="bg-white rounded-xl border border-slate-200 p-6 hover:border-brand-600 hover:shadow-lg transition">
        <div class="w-12 h-12 rounded-lg bg-brand-50 flex items-center justify-center text-brand-600">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        </div>
        <h3 class="mt-4 font-semibold text-lg text-slate-900">Cepat & Mudah</h3>
        <p class="mt-2 text-slate-600 leading-relaxed">Memilih hanya butuh beberapa menit dari ponsel atau komputer Anda, tanpa antre.</p>
      </div>
      <div class="bg-white rounded-xl border border-slate-200 p-6 hover:border-brand-600 hover:shadow-lg transition">
        <div class="w-12 h-12 rounded-lg bg-brand-50 flex items-center justify-center text-brand-600">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
        </div>
        <h3 class="mt-4 font-semibold text-lg text-slate-900">Hasil Real-Time</h3>
        <p class="mt-2 text-slate-600 leading-relaxed">Pantau perolehan suara langsung saat pemilihan berlangsung, diperbarui setiap detik.</p>
      </div>
      <div class="bg-white rounded-xl border border-slate-200 p-6 hover:border-brand-600 hover:shadow-lg transition">
        <div class="w-12 h-12 rounded-lg bg-brand-50 flex items-center justify-center text-brand-600">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        </div>
        <h3 class="mt-4 font-semibold text-lg text-slate-900">Verifikasi Identitas</h3>
        <p class="mt-2 text-slate-600 leading-relaxed">Hanya pemilih terdaftar yang dapat memberikan suara, satu orang satu suara.</p>
      </div>
      <div class="bg-white rounded-xl border border-slate-200 p-6 hover:border-brand-600 hover:shadow-lg transition">
        <div class="w-12 h-12 rounded-lg bg-brand-50 flex items-center justify-center text-brand-600">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h3 class="mt-4 font-semibold text-lg text-slate-900">Tanpa Biaya</h3>
        <p class="mt-2 text-slate-600 leading-relaxed">Hemat biaya cetak surat suara dan logistik. Ramah lingkungan dan efisien.</p>
      </div>
    </div>
  </div>
</section>

<!-- How it works -->
<section id="alur" class="max-w-6xl mx-auto px-4 sm:px-6 py-16 sm:py-24">
  <div class="text-center max-w-2xl mx-auto">
    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900">Empat langkah memilih</h2>
    <p class="mt-4 text-lg text-slate-600">Prosesnya sederhana dan hanya butuh beberapa menit.</p>
  </div>
  <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="bg-white rounded-xl border border-slate-200 p-6">
      <span class="text-4xl font-extrabold text-brand-100">01</span>
      <h3 class="mt-2 font-semibold text-slate-900">Daftar / Masuk</h3>
      <p class="mt-2 text-sm text-slate-600">Masuk menggunakan akun alumni yang sudah terdaftar di sistem.</p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-6">
      <span class="text-4xl font-extrabold text-brand-100">02</span>
      <h3 class="mt-2 font-semibold text-slate-900">Verifikasi</h3>
      <p class="mt-2 text-sm text-slate-600">Sistem memverifikasi identitas Anda sebagai pemilih yang sah.</p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-6">
      <span class="text-4xl font-extrabold text-brand-100">03</span>
      <h3 class="mt-2 font-semibold text-slate-900">Pilih Kandidat</h3>
      <p class="mt-2 text-sm text-slate-600">Tinjau kandidat lalu berikan suara Anda dengan sekali ketuk.</p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-6">
      <span class="text-4xl font-extrabold text-brand-100">04</span>
      <h3 class="mt-2 font-semibold text-slate-900">Selesai</h3>
      <p class="mt-2 text-sm text-slate-600">Dapatkan bukti digital bahwa suara Anda telah tercatat dengan aman.</p>
    </div>
  </div>
</section>

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
        <div class="w-9 h-9 rounded-lg bg-brand-600 text-white flex items-center justify-center">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <span class="font-bold text-lg text-white">E-Voting</span>
      </div>
      <p class="text-sm leading-relaxed">Sistem pemilihan digital yang aman, transparan, dan dapat dipercaya.</p>
    </div>
    <div>
      <h4 class="font-semibold text-white mb-3">Navigasi</h4>
      <ul class="space-y-2 text-sm">
        <li><a href="#kandidat" class="hover:text-white transition">Kandidat</a></li>
        <li><a href="#fitur"    class="hover:text-white transition">Fitur</a></li>
        <li><a href="#alur"    class="hover:text-white transition">Cara Kerja</a></li>
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
