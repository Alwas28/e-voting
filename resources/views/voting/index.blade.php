<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<title>Beri Suara — E-Voting</title>
<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    theme: { extend: { colors: { brand: {
      50:'#eef2ff',100:'#e0e7ff',500:'#6366f1',
      600:'#4f46e5',700:'#4338ca',900:'#312e81'
    }}}}
  }
</script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  body { font-family: 'Inter', sans-serif; }
  .cand-card.selected {
    border-color: #4f46e5;
    background-color: #eef2ff;
    box-shadow: 0 0 0 3px #c7d2fe;
  }
</style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">

{{-- ══════ TOP BAR ══════ --}}
<header class="bg-white border-b border-slate-200 sticky top-0 z-40">
  <div class="max-w-5xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
    <a href="{{ url('/') }}" class="flex items-center gap-2.5">
      <div class="w-9 h-9 rounded-lg bg-brand-600 text-white flex items-center justify-center">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>
      <span class="font-bold text-lg text-slate-900 hidden sm:inline">E-Voting</span>
    </a>

    @if($electionSchedule && $electionSchedule->status === 'berlangsung')
    <div class="flex items-center gap-2 bg-amber-50 text-amber-700 px-3 py-1.5 rounded-full text-sm font-medium">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
      Sisa: <span id="timer">--:--:--</span>
    </div>
    @endif

    <div class="flex items-center gap-2">
      <div class="w-8 h-8 rounded-full bg-brand-600 text-white flex items-center justify-center text-xs font-semibold">
        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
      </div>
      <span class="text-sm font-medium text-slate-700 hidden sm:inline">{{ Auth::user()->name }}</span>
    </div>
  </div>
</header>

<main class="flex-1 max-w-5xl w-full mx-auto px-4 sm:px-6 py-8">

{{-- ══════ STATE: SUDAH MEMILIH ══════ --}}
@if($alreadyVoted || session('success_voted'))
  <section class="text-center max-w-md mx-auto py-8">
    <div class="w-20 h-20 mx-auto rounded-full bg-green-100 flex items-center justify-center">
      <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
      </svg>
    </div>
    <h2 class="mt-6 text-2xl font-extrabold text-slate-900">Suara Anda tercatat!</h2>
    <p class="mt-2 text-slate-600">Terima kasih telah berpartisipasi dalam pemilihan ini.</p>

    @if($voter && $voter->voted_at)
    <div class="mt-6 bg-white border border-slate-200 rounded-xl p-5 text-left">
      <div class="flex justify-between py-2 border-b border-slate-100">
        <span class="text-slate-500 text-sm">Kode Pemilih</span>
        <span class="font-mono font-semibold text-slate-900 text-sm">{{ $voter->voter_code }}</span>
      </div>
      <div class="flex justify-between py-2 border-b border-slate-100">
        <span class="text-slate-500 text-sm">Waktu Memilih</span>
        <span class="font-medium text-slate-900 text-sm">{{ \Carbon\Carbon::parse($voter->voted_at)->locale('id')->translatedFormat('d M Y, H:i') }} WIB</span>
      </div>
      <div class="flex justify-between py-2">
        <span class="text-slate-500 text-sm">Status</span>
        <span class="bg-green-50 text-green-700 px-2 py-0.5 rounded-full text-xs font-medium">Terverifikasi</span>
      </div>
    </div>
    @endif

    <a href="{{ route('admin.results') }}" class="mt-6 inline-flex items-center justify-center gap-2 w-full bg-brand-600 hover:bg-brand-700 text-white font-semibold py-3 rounded-lg transition">
      Lihat Hasil Sementara
    </a>
  </section>

{{-- ══════ STATE: BUKAN DPT ══════ --}}
@elseif($notInDpt)
  <section class="text-center max-w-md mx-auto py-8">
    <div class="w-20 h-20 mx-auto rounded-full bg-amber-100 flex items-center justify-center">
      <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
    </div>
    <h2 class="mt-6 text-2xl font-extrabold text-slate-900">Belum Terdaftar DPT</h2>
    <p class="mt-2 text-slate-600">Anda belum terdaftar sebagai pemilih. Daftarkan diri Anda terlebih dahulu.</p>
    <a href="{{ route('admin.dpt.register') }}" class="mt-6 inline-flex items-center justify-center gap-2 w-full bg-brand-600 hover:bg-brand-700 text-white font-semibold py-3 rounded-lg transition">
      Daftar Sebagai Pemilih
    </a>
  </section>

{{-- ══════ STATE: BELUM/SUDAH BUKA ══════ --}}
@elseif($electionStatus !== 'berlangsung')
  <section class="text-center max-w-md mx-auto py-8">
    <div class="w-20 h-20 mx-auto rounded-full bg-slate-100 flex items-center justify-center">
      <svg class="w-10 h-10 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
    </div>
    <h2 class="mt-6 text-2xl font-extrabold text-slate-900">
      @if($electionStatus === 'belum_dimulai') Pemilihan Belum Dimulai
      @elseif($electionStatus === 'selesai') Pemilihan Telah Berakhir
      @else Belum Ada Jadwal Pemilihan
      @endif
    </h2>
    @if($electionSchedule && $electionSchedule->start_date)
    <p class="mt-2 text-slate-600">
      Pemilihan berlangsung pada<br>
      <strong>{{ $electionSchedule->start_date->locale('id')->translatedFormat('d F Y, H:i') }}</strong>
      s/d
      <strong>{{ $electionSchedule->end_date->locale('id')->translatedFormat('d F Y, H:i') }} WIB</strong>
    </p>
    @else
    <p class="mt-2 text-slate-600">Jadwal pemilihan belum ditentukan. Pantau terus informasi resmi.</p>
    @endif
    <a href="{{ url('/') }}" class="mt-6 inline-flex items-center justify-center gap-2 w-full border border-slate-300 text-slate-700 font-semibold py-3 rounded-lg hover:bg-slate-50 transition">
      Kembali ke Beranda
    </a>
  </section>

{{-- ══════ STATE: FORM VOTING ══════ --}}
@else

  {{-- ── STEP 1: VERIFIKASI WAJAH ── --}}
  <section id="step-face">
    <div class="text-center max-w-xl mx-auto mb-8">
      <span class="inline-block text-xs font-semibold text-brand-700 bg-brand-50 px-3 py-1 rounded-full">
        {{ $activePeriod->name ?? 'Pemilihan Aktif' }}
      </span>
      <h1 class="mt-3 text-2xl sm:text-3xl font-extrabold text-slate-900">Verifikasi Wajah</h1>
      <p class="mt-2 text-slate-600">Sebelum memilih, pastikan identitas Anda dengan memindai wajah menggunakan kamera.</p>
    </div>

    <div class="max-w-sm mx-auto bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex flex-col items-center gap-5">
      <div id="faceIcon" class="w-24 h-24 rounded-full bg-brand-50 flex items-center justify-center">
        <svg class="w-12 h-12 text-brand-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
        </svg>
      </div>

      <div id="faceStatus" class="text-center">
        <p class="font-semibold text-slate-900">Belum diverifikasi</p>
        <p class="text-sm text-slate-500 mt-1">Klik tombol di bawah untuk membuka kamera</p>
      </div>

      <div id="faceMsg" class="hidden w-full rounded-xl border p-3 text-sm text-center"></div>

      <button id="btnFaceStart" onclick="openCamera()"
              class="w-full flex items-center justify-center gap-2 py-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
        </svg>
        Mulai Verifikasi Wajah
      </button>

      <div class="flex items-start gap-2 text-xs text-slate-500 text-left bg-slate-50 rounded-lg p-3 w-full">
        <svg class="w-4 h-4 text-slate-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
        </svg>
        Wajah Anda dicocokkan dengan data yang terdaftar saat pendaftaran DPT. Data tidak disimpan ulang.
      </div>
    </div>
  </section>

  {{-- ── STEP 2: PILIH KANDIDAT ── --}}
  <section id="step-vote" class="hidden">
    <div class="text-center max-w-xl mx-auto mb-8">
      <span class="inline-block text-xs font-semibold text-brand-700 bg-brand-50 px-3 py-1 rounded-full">
        {{ $activePeriod->name ?? 'Pemilihan Aktif' }}
      </span>
      <h1 class="mt-3 text-2xl sm:text-3xl font-extrabold text-slate-900">Pilih satu kandidat</h1>
      <p class="mt-2 text-slate-600">Ketuk kartu untuk melihat detail kandidat, lalu pilih. Suara bersifat final.</p>
    </div>

    <div class="grid sm:grid-cols-2 gap-4">
      @foreach($candidates as $c)
      <button onclick="openDetail({{ $c->id }})"
              class="cand-card relative text-left bg-white rounded-xl border-2 border-slate-200 p-5 hover:border-brand-600 transition w-full">
        <div class="flex items-center gap-4">
          @if($c->photo)
            <img src="{{ asset('storage/' . $c->photo) }}" alt="{{ $c->name }}"
                 class="w-16 h-16 rounded-lg object-cover shrink-0 ring-1 ring-slate-200" />
          @else
            <div class="w-16 h-16 rounded-lg bg-brand-600 text-white text-xl font-bold flex items-center justify-center shrink-0">
              {{ strtoupper(substr($c->name, 0, 2)) }}
            </div>
          @endif
          <div class="flex-1 min-w-0">
            <span class="text-xs font-semibold text-brand-700 bg-brand-50 px-2 py-0.5 rounded">No. {{ str_pad($c->number, 2, '0', STR_PAD_LEFT) }}</span>
            <h3 class="mt-1 font-semibold text-lg text-slate-900 truncate">{{ $c->name }}</h3>
            @if($c->alumni?->nim)
              <p class="text-xs text-slate-400">{{ $c->alumni->nim }}</p>
            @endif
            <p class="text-sm text-slate-500 truncate">{{ $c->vision ? \Illuminate\Support\Str::limit($c->vision, 40) : ($c->faculty ?? '') }}</p>
          </div>
          <svg class="w-5 h-5 text-slate-300 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
          </svg>
        </div>
      </button>
      @endforeach
    </div>

    <div class="mt-6 flex items-start gap-3 bg-white border border-slate-200 rounded-xl p-4 text-sm text-slate-600">
      <svg class="w-5 h-5 text-brand-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
      </svg>
      Suara Anda terenkripsi dan rahasia. Tidak ada pihak yang dapat mengetahui pilihan Anda.
    </div>
  </section>

@endif
</main>

{{-- ══════ FULLSCREEN CAMERA OVERLAY ══════ --}}
<div id="cameraModal" class="fixed inset-0 z-50 bg-black flex-col items-center justify-between" style="display:none">

  <div class="w-full flex items-center justify-between px-5 pt-5 pb-3">
    <button onclick="closeCamera()" class="flex items-center gap-2 text-white/80 hover:text-white text-sm transition">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
      </svg>
      Batal
    </button>
    <div class="flex items-center gap-2 text-white/80 text-sm">
      <span id="camDot" class="w-2 h-2 rounded-full bg-white/50 animate-pulse inline-block"></span>
      <span id="camStatusText">Memuat...</span>
    </div>
    <div class="w-16"></div>
  </div>

  <div class="flex-1 flex items-center justify-center w-full px-4">
    <div class="relative" style="width: min(320px, calc(100vw - 2rem)); aspect-ratio: 3/4; max-height: calc(100vh - 200px);">
      <video id="camVideo" autoplay muted playsinline
             class="absolute inset-0 w-full h-full object-cover rounded-2xl"
             style="transform:scaleX(-1)"></video>
      <canvas id="faceCanvas" class="absolute inset-0 w-full h-full rounded-2xl opacity-0"
              style="transform:scaleX(-1)"></canvas>

      <svg class="absolute inset-0 w-full h-full rounded-2xl pointer-events-none"
           viewBox="0 0 300 400" preserveAspectRatio="xMidYMid slice">
        <defs>
          <mask id="ovalCut">
            <rect width="300" height="400" fill="white"/>
            <ellipse cx="150" cy="185" rx="110" ry="140" fill="black"/>
          </mask>
        </defs>
        <rect width="300" height="400" fill="rgba(0,0,0,0.55)" mask="url(#ovalCut)"/>
        <ellipse id="ovalBorder" cx="150" cy="185" rx="110" ry="140"
                 fill="none" stroke="white" stroke-width="2.5" stroke-dasharray="8 4" opacity="0.7"/>
        <g stroke="white" stroke-width="3" stroke-linecap="round" opacity="0.9">
          <path d="M40,80 L40,60 L60,60"/>
          <path d="M260,80 L260,60 L240,60"/>
          <path d="M40,320 L40,340 L60,340"/>
          <path d="M260,320 L260,340 L240,340"/>
        </g>
        <text id="ovalText" x="150" y="358" text-anchor="middle" fill="rgba(255,255,255,0.85)"
              font-size="13" font-family="sans-serif">Posisikan wajah di dalam oval</text>
      </svg>

      <div id="countdownRing" class="absolute inset-0 flex items-center justify-center pointer-events-none" style="display:none">
        <div class="w-20 h-20 rounded-full bg-black/50 flex items-center justify-center">
          <span id="countdownNum" class="text-white text-4xl font-bold">3</span>
        </div>
      </div>

      <div id="camLoading" class="absolute inset-0 flex flex-col items-center justify-center bg-black/80 rounded-2xl">
        <div class="w-8 h-8 border-2 border-white/30 border-t-white rounded-full animate-spin mb-3"></div>
        <p id="camLoadingText" class="text-white text-sm text-center px-4">Membuka kamera...</p>
        <div class="mt-3 w-40 h-1.5 bg-white/20 rounded-full overflow-hidden">
          <div id="modelProgress" class="h-full bg-indigo-500 rounded-full transition-all duration-300" style="width:0%"></div>
        </div>
      </div>
    </div>
  </div>

  <div class="w-full px-6 pb-8 pt-4 flex flex-col items-center gap-3">
    <div id="qualityRow" class="w-full max-w-xs flex items-center gap-3" style="display:none">
      <div class="flex-1 h-1.5 bg-white/20 rounded-full overflow-hidden">
        <div id="qualityBar" class="h-full rounded-full transition-all duration-200 bg-green-400" style="width:0%"></div>
      </div>
      <span id="qualityPct" class="text-white/80 text-xs w-8 text-right">0%</span>
    </div>
    <button id="btnCapture" disabled
            class="w-16 h-16 rounded-full border-4 border-white flex items-center justify-center transition disabled:opacity-30 disabled:cursor-not-allowed enabled:hover:bg-white/20 enabled:active:scale-95">
      <div class="w-12 h-12 rounded-full bg-white"></div>
    </button>
    <p class="text-white/50 text-xs">Tekan untuk memindai</p>
  </div>
</div>

{{-- ══════ CANDIDATE DETAIL MODAL ══════ --}}
<div id="detailModal" class="hidden fixed inset-0 z-50">
  <div class="absolute inset-0 bg-black/50" onclick="closeDetail()"></div>
  <div class="absolute inset-x-0 bottom-0 sm:inset-0 sm:flex sm:items-center sm:justify-center p-0 sm:p-4">
    <div class="bg-white w-full sm:max-w-lg mx-auto rounded-t-2xl sm:rounded-2xl overflow-hidden max-h-[92vh] flex flex-col">
      <div class="relative h-0">
        <button onclick="closeDetail()" class="absolute top-3 right-3 z-10 w-9 h-9 rounded-full bg-black/30 hover:bg-black/50 text-white flex items-center justify-center transition">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
      <div class="overflow-y-auto">
        <div class="px-6 pt-6">
          <img id="detailPhoto" src="" alt="" class="w-full max-w-xs mx-auto aspect-square rounded-2xl object-cover bg-slate-100 border border-slate-200" />
        </div>
        <div class="px-6 pt-5 text-center">
          <span id="detailNo" class="text-xs font-semibold text-brand-700 bg-brand-50 px-2 py-0.5 rounded">No. —</span>
          <h3 id="detailName" class="mt-2 text-2xl font-extrabold text-slate-900">—</h3>
          <p id="detailFaculty" class="text-slate-500 text-sm mt-1">—</p>
        </div>
        <div class="px-6 pb-2">
          <div class="mt-5 text-left">
            <h4 class="text-sm font-semibold text-slate-900">Visi</h4>
            <p id="detailVisi" class="mt-1 text-sm text-slate-600 leading-relaxed">—</p>
          </div>
          <div class="mt-4 text-left">
            <h4 class="text-sm font-semibold text-slate-900">Misi</h4>
            <p id="detailMisi" class="mt-1 text-sm text-slate-600 leading-relaxed">—</p>
          </div>
          <div class="mt-5 flex items-start gap-2.5 bg-amber-50 border border-amber-100 rounded-lg p-3 text-xs text-amber-800 text-left">
            <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.74-3L13.74 4a2 2 0 00-3.48 0L3.34 16a2 2 0 001.73 3z"/>
            </svg>
            Setelah memilih, suara tidak dapat diubah. Pastikan pilihan Anda sudah benar.
          </div>
        </div>
      </div>
      <div class="px-6 py-4 border-t border-slate-100 flex flex-col-reverse sm:flex-row gap-3">
        <button onclick="closeDetail()" class="w-full border border-slate-300 text-slate-700 font-semibold py-2.5 rounded-lg hover:bg-slate-50 transition">Kembali</button>
        <button onclick="confirmVote()" class="w-full bg-brand-600 text-white font-semibold py-2.5 rounded-lg hover:bg-brand-700 transition">Pilih Kandidat Ini</button>
      </div>
    </div>
  </div>
</div>

{{-- ══════ CONFIRM MODAL ══════ --}}
<div id="confirmModal" class="hidden fixed inset-0 z-[60]">
  <div class="absolute inset-0 bg-black/60"></div>
  <div class="absolute inset-0 flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-sm rounded-2xl p-6 shadow-2xl relative">
      <h3 class="text-lg font-bold text-slate-900 text-center">Konfirmasi Pilihan</h3>
      <p class="mt-2 text-slate-600 text-sm text-center">Anda akan memilih:</p>
      <div class="mt-4 flex items-center gap-3 bg-brand-50 rounded-xl p-4">
        <div id="confirmPhotoWrap" class="w-12 h-12 rounded-lg bg-brand-600 text-white font-bold flex items-center justify-center shrink-0 text-lg overflow-hidden">
          <img id="confirmPhoto" src="" alt="" class="w-full h-full object-cover hidden" />
          <span id="confirmInitials"></span>
        </div>
        <div>
          <p id="confirmNo" class="text-xs font-semibold text-brand-700"></p>
          <p id="confirmName" class="font-semibold text-slate-900"></p>
        </div>
      </div>
      <p class="mt-4 text-xs text-slate-500 text-center">Suara tidak dapat diubah setelah dikonfirmasi.</p>
      <div class="mt-5 flex gap-3">
        <button onclick="closeConfirm()" class="flex-1 border border-slate-300 text-slate-700 font-semibold py-2.5 rounded-lg hover:bg-slate-50 transition">Batal</button>
        <form id="castForm" method="POST" action="{{ route('voting.cast') }}" class="flex-1">
          @csrf
          <input type="hidden" name="candidate_id" id="castCandidateId" value="">
          <button type="submit" class="w-full bg-brand-600 text-white font-semibold py-2.5 rounded-lg hover:bg-brand-700 transition">Ya, Pilih!</button>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- ══════ SCRIPTS ══════ --}}
@if($electionSchedule && $electionSchedule->status === 'berlangsung' && $electionSchedule->end_date)
<script>
const timerTarget = new Date("{{ $electionSchedule->end_date->toIso8601String() }}");
function tickTimer() {
  const diff = timerTarget - Date.now();
  const el = document.getElementById('timer');
  if (!el) return;
  if (diff <= 0) { el.textContent = '00:00:00'; return; }
  const h = String(Math.floor(diff / 3600000)).padStart(2, '0');
  const m = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
  const s = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');
  el.textContent = h + ':' + m + ':' + s;
}
tickTimer();
setInterval(tickTimer, 1000);
</script>
@endif

@if($electionStatus === 'berlangsung' && !$alreadyVoted && !$notInDpt)
@php
$candidatesJs = $candidates->mapWithKeys(fn($c) => [
    $c->id => [
        'id'         => $c->id,
        'name'       => $c->name,
        'nim'        => $c->alumni?->nim ?? '',
        'no'         => str_pad($c->number, 2, '0', STR_PAD_LEFT),
        'faculty'    => $c->faculty ?? '',
        'visi'       => $c->vision ?? 'Belum tersedia',
        'misi'       => $c->mission ?? 'Belum tersedia',
        'photo'      => $c->photo ? asset('storage/' . $c->photo) : '',
        'initials'   => strtoupper(substr($c->name, 0, 2)),
        'profileUrl' => url('/kandidat/' . $c->id),
    ]
])->all();
@endphp
<script>
const CANDIDATES = {!! json_encode($candidatesJs, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!};

const CSRF       = document.querySelector('meta[name="csrf-token"]').content;
const VERIFY_URL = "{{ route('voting.verify-face') }}";
const MODEL_URL  = "{{ asset('face-api/model') }}";

let selectedId = null;

// ── Face verification ─────────────────────────────────────────────────────────
const camVideo   = document.getElementById('camVideo');
const faceCanvas = document.getElementById('faceCanvas');
const modal      = document.getElementById('cameraModal');

let modelsReady       = false;
let cameraStream      = null;
let detectionInterval = null;
let countdownTimer    = null;
let faceDetected      = false;
let consecutiveFrames = 0;
let countingDown      = false;
let pendingDescriptor = null;

const REQUIRED_FRAMES = 3;

async function openCamera() {
  faceDetected = false; consecutiveFrames = 0;
  countingDown = false; pendingDescriptor = null;
  clearInterval(detectionInterval); clearInterval(countdownTimer);

  modal.style.display = 'flex';
  document.body.style.overflow = 'hidden';
  document.getElementById('camLoading').style.display = 'flex';
  document.getElementById('camLoadingText').textContent = 'Membuka kamera...';
  document.getElementById('modelProgress').style.width = '0%';
  setOvalColor('white');
  setOvalText('Posisikan wajah di dalam oval');
  document.getElementById('countdownRing').style.display = 'none';
  document.getElementById('btnCapture').disabled = true;
  document.getElementById('qualityRow').style.display = 'none';

  try {
    cameraStream = await navigator.mediaDevices.getUserMedia({
      video: { width: { ideal: 640 }, height: { ideal: 480 }, facingMode: 'user' }
    });
    camVideo.srcObject = cameraStream;
    await new Promise(r => { camVideo.onloadedmetadata = r; });
    faceCanvas.width  = camVideo.videoWidth;
    faceCanvas.height = camVideo.videoHeight;
  } catch (err) {
    const msgs = {
      NotAllowedError: 'Izin kamera ditolak.',
      NotFoundError: 'Tidak ada kamera terdeteksi.',
      NotReadableError: 'Kamera dipakai aplikasi lain.'
    };
    document.getElementById('camLoadingText').textContent = msgs[err.name] || err.message;
    return;
  }

  if (!modelsReady) {
    setCamStatus('Memuat AI...', 'loading');
    try {
      document.getElementById('camLoadingText').textContent = 'Memuat model deteksi...';
      document.getElementById('modelProgress').style.width = '15%';
      await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);

      document.getElementById('camLoadingText').textContent = 'Memuat model landmark...';
      document.getElementById('modelProgress').style.width = '50%';
      await faceapi.nets.faceLandmark68TinyNet.loadFromUri(MODEL_URL);

      document.getElementById('camLoadingText').textContent = 'Memuat model pengenalan...';
      document.getElementById('modelProgress').style.width = '75%';
      await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);

      document.getElementById('modelProgress').style.width = '100%';
      modelsReady = true;
    } catch (err) {
      document.getElementById('camLoadingText').textContent = 'Gagal memuat model: ' + err.message;
      return;
    }
  }

  document.getElementById('camLoading').style.display = 'none';
  setCamStatus('Arahkan wajah ke oval', 'idle');
  startDetection();
}

function closeCamera() {
  clearInterval(detectionInterval); clearInterval(countdownTimer);
  if (cameraStream) { cameraStream.getTracks().forEach(t => t.stop()); cameraStream = null; }
  modal.style.display = 'none';
  document.body.style.overflow = '';
}

function startDetection() {
  detectionInterval = setInterval(detectFace, 120);
}

async function detectFace() {
  if (!camVideo.videoWidth || !modelsReady) return;
  const opts = new faceapi.TinyFaceDetectorOptions({ inputSize: 160, scoreThreshold: 0.45 });
  const result = await faceapi
    .detectSingleFace(camVideo, opts)
    .withFaceLandmarks(true)
    .withFaceDescriptor();

  if (result) {
    const score = result.detection.score;
    updateQuality(score);
    consecutiveFrames++;
    const { x, y, width, height } = result.detection.box;
    const cx = (x + width / 2) / camVideo.videoWidth;
    const cy = (y + height / 2) / camVideo.videoHeight;

    if (cx < 0.2 || cx > 0.8 || cy < 0.15 || cy > 0.8) {
      if (faceDetected) resetFaceState();
      setCamStatus('Geser agar wajah di tengah', 'idle');
      return;
    }

    pendingDescriptor = Array.from(result.descriptor);

    if (consecutiveFrames >= REQUIRED_FRAMES && score > 0.65 && !faceDetected) {
      faceDetected = true;
      setOvalColor('#22c55e');
      setOvalText('Tahan diam...');
      setCamStatus('Wajah terdeteksi ✓', 'ok');
      document.getElementById('btnCapture').disabled = false;
      startCountdown();
    }
  } else {
    consecutiveFrames = 0;
    if (faceDetected) resetFaceState();
    setCamStatus('Arahkan wajah ke oval', 'idle');
    document.getElementById('qualityRow').style.display = 'none';
  }
}

function resetFaceState() {
  faceDetected = false; countingDown = false; pendingDescriptor = null;
  clearInterval(countdownTimer);
  setOvalColor('white'); setOvalText('Posisikan wajah di dalam oval');
  document.getElementById('btnCapture').disabled = true;
  document.getElementById('countdownRing').style.display = 'none';
}

function startCountdown() {
  if (countingDown) return;
  countingDown = true;
  let count = 5;
  const ring = document.getElementById('countdownRing');
  const num  = document.getElementById('countdownNum');
  ring.style.display = 'flex'; num.textContent = count;
  countdownTimer = setInterval(async () => {
    count--; num.textContent = count;
    if (count <= 0) { clearInterval(countdownTimer); ring.style.display = 'none'; await doCapture(); }
  }, 1000);
}

async function doCapture() {
  clearInterval(detectionInterval);
  setCamStatus('Memproses...', 'loading');
  if (!pendingDescriptor) {
    setCamStatus('Gagal. Coba lagi.', 'error');
    resetFaceState(); startDetection(); return;
  }
  const desc = pendingDescriptor;
  closeCamera();
  setFaceUI('verifying');

  try {
    const res = await fetch(VERIFY_URL, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
      body: JSON.stringify({ descriptor: desc })
    });
    const data = await res.json();
    if (res.ok && data.ok) {
      setFaceUI('verified');
      setTimeout(() => {
        document.getElementById('step-face').classList.add('hidden');
        document.getElementById('step-vote').classList.remove('hidden');
      }, 1200);
    } else {
      setFaceUI('failed', data.message || 'Wajah tidak cocok. Silakan coba lagi.');
    }
  } catch (err) {
    setFaceUI('failed', 'Koneksi error: ' + err.message);
  }
}

document.getElementById('btnCapture').addEventListener('click', async () => {
  if (!faceDetected) return;
  clearInterval(countdownTimer);
  document.getElementById('countdownRing').style.display = 'none';
  countingDown = false;
  await doCapture();
});

function setFaceUI(state, msg) {
  const icon   = document.getElementById('faceIcon');
  const status = document.getElementById('faceStatus');
  const msgEl  = document.getElementById('faceMsg');
  const btn    = document.getElementById('btnFaceStart');

  if (state === 'verifying') {
    icon.className = 'w-24 h-24 rounded-full bg-blue-50 flex items-center justify-center';
    icon.innerHTML = '<div class="w-10 h-10 border-[3px] border-blue-200 border-t-blue-600 rounded-full animate-spin"></div>';
    status.innerHTML = '<p class="font-semibold text-slate-900">Memverifikasi...</p><p class="text-sm text-slate-500 mt-1">Sedang mencocokkan wajah Anda</p>';
    btn.classList.add('hidden');
    msgEl.classList.add('hidden');
  } else if (state === 'verified') {
    icon.className = 'w-24 h-24 rounded-full bg-green-100 flex items-center justify-center';
    icon.innerHTML = '<svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>';
    status.innerHTML = '<p class="font-semibold text-green-700">Verifikasi Berhasil!</p><p class="text-sm text-slate-500 mt-1">Menampilkan daftar kandidat...</p>';
    btn.classList.add('hidden');
    msgEl.classList.add('hidden');
  } else if (state === 'failed') {
    icon.className = 'w-24 h-24 rounded-full bg-red-100 flex items-center justify-center';
    icon.innerHTML = '<svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
    status.innerHTML = '<p class="font-semibold text-red-700">Verifikasi Gagal</p><p class="text-sm text-slate-500 mt-1">Wajah tidak cocok. Silakan coba lagi.</p>';
    btn.textContent = 'Coba Lagi';
    btn.classList.remove('hidden');
    msgEl.className = 'w-full rounded-xl border p-3 text-sm text-center bg-red-50 border-red-200 text-red-700';
    msgEl.textContent = msg || 'Verifikasi gagal.';
    msgEl.classList.remove('hidden');
  }
}

// ── Candidate detail modal ────────────────────────────────────────────────────
function openDetail(id) {
  const c = CANDIDATES[id];
  if (!c) return;
  selectedId = id;

  const photo = document.getElementById('detailPhoto');
  if (c.photo) {
    photo.src = c.photo;
    photo.classList.remove('hidden');
  } else {
    photo.classList.add('hidden');
  }
  photo.alt = c.name;

  document.getElementById('detailNo').textContent = 'No. ' + c.no;
  document.getElementById('detailName').textContent = c.name;
  document.getElementById('detailFaculty').textContent = (c.nim ? c.nim + ' · ' : '') + c.faculty;
  document.getElementById('detailVisi').textContent = c.visi;
  document.getElementById('detailMisi').textContent = c.misi;
  document.getElementById('detailModal').classList.remove('hidden');
  document.body.style.overflow = 'hidden';
}

function closeDetail() {
  document.getElementById('detailModal').classList.add('hidden');
  document.body.style.overflow = '';
}

function confirmVote() {
  if (!selectedId) return;
  const c = CANDIDATES[selectedId];
  closeDetail();

  const photoEl    = document.getElementById('confirmPhoto');
  const initialsEl = document.getElementById('confirmInitials');
  if (c.photo) {
    photoEl.src = c.photo;
    photoEl.classList.remove('hidden');
    initialsEl.textContent = '';
  } else {
    photoEl.classList.add('hidden');
    initialsEl.textContent = c.initials;
  }

  document.getElementById('confirmNo').textContent   = 'No. ' + c.no;
  document.getElementById('confirmName').textContent = c.name;
  document.getElementById('castCandidateId').value   = selectedId;
  document.getElementById('confirmModal').classList.remove('hidden');
}

function closeConfirm() {
  document.getElementById('confirmModal').classList.add('hidden');
}

// ── UI helpers ────────────────────────────────────────────────────────────────
function setCamStatus(text, type) {
  const dot  = document.getElementById('camDot');
  const span = document.getElementById('camStatusText');
  span.textContent = text;
  const colors = { loading: 'bg-blue-400', ok: 'bg-green-400', error: 'bg-red-400', idle: 'bg-white/50' };
  dot.className = `w-2 h-2 rounded-full inline-block ${colors[type] || colors.idle} ${type === 'loading' ? 'animate-pulse' : ''}`;
}
function setOvalColor(c) { const el = document.getElementById('ovalBorder'); if (el) el.setAttribute('stroke', c); }
function setOvalText(t)   { const el = document.getElementById('ovalText');   if (el) el.textContent = t; }
function updateQuality(score) {
  const pct = Math.round(score * 100);
  document.getElementById('qualityRow').style.display = 'flex';
  const bar = document.getElementById('qualityBar');
  bar.style.width = pct + '%';
  bar.className = `h-full rounded-full transition-all duration-200 ${pct >= 85 ? 'bg-green-400' : pct >= 65 ? 'bg-amber-400' : 'bg-red-400'}`;
  document.getElementById('qualityPct').textContent = pct + '%';
}

document.addEventListener('keydown', e => {
  if (e.key === 'Escape') { closeCamera(); closeDetail(); closeConfirm(); }
});
</script>
<script src="{{ asset('face-api/face-api.js') }}"></script>
@endif

</body>
</html>
