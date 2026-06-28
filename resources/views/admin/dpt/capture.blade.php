@extends('layouts.admin')

@section('title', 'Rekam Wajah — Pendaftaran DPT')
@section('page-title', 'Pendaftaran DPT')

@section('content')
<div class="max-w-2xl mx-auto space-y-5">

  {{-- Header alumni --}}
  <div class="bg-white rounded-2xl border border-slate-200 p-5 flex items-center gap-4">
    <div class="w-14 h-14 rounded-2xl bg-brand-600 text-white text-xl font-bold flex items-center justify-center shrink-0">
      {{ $alumni->initials }}
    </div>
    <div class="flex-1 min-w-0">
      <p class="text-lg font-bold text-slate-800 truncate">{{ $alumni->name }}</p>
      <p class="text-sm text-slate-500">{{ $alumni->nim }} &middot; {{ $alumni->faculty }}</p>
    </div>
    <div class="shrink-0">
      @if($voter && $voter->hasFace())
        <span class="inline-flex items-center gap-1.5 bg-green-100 text-green-700 text-sm font-medium px-3 py-1.5 rounded-full">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          Terdaftar DPT
        </span>
        <p class="text-xs text-slate-400 text-right mt-1">{{ $voter->voter_code }}</p>
      @else
        <span class="inline-flex items-center gap-1.5 bg-amber-100 text-amber-700 text-sm font-medium px-3 py-1.5 rounded-full">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          Belum Terdaftar
        </span>
      @endif
    </div>
  </div>

  {{-- Foto hasil + tombol utama --}}
  <div class="bg-white rounded-2xl border border-slate-200 p-6 flex flex-col items-center gap-5">

    {{-- Preview foto --}}
    <div id="photoArea" class="flex flex-col items-center gap-3">
      <div class="w-40 h-52 rounded-2xl overflow-hidden border-2 border-dashed border-slate-200 bg-slate-50 flex items-center justify-center">
        @if($voter && $voter->face_photo)
          <img id="photoPreview" src="{{ $voter->face_photo }}" class="w-full h-full object-cover" />
        @else
          <div id="photoPlaceholder" class="text-center p-4">
            <svg class="w-14 h-14 mx-auto text-slate-200 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
            <p class="text-xs text-slate-400">Belum ada foto</p>
          </div>
          <img id="photoPreview" src="" class="hidden w-full h-full object-cover" />
        @endif
      </div>
      <p id="captureStatus" class="text-sm text-slate-500">
        {{ ($voter && $voter->hasFace()) ? 'Foto wajah tersimpan' : 'Belum ada foto wajah' }}
      </p>
    </div>

    {{-- Tombol --}}
    <div class="flex flex-col items-center gap-3 w-full max-w-xs">
      <button id="btnStart"
              onclick="openCamera()"
              class="w-full flex items-center justify-center gap-2 py-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/></svg>
        {{ ($voter && $voter->hasFace()) ? 'Rekam Ulang Wajah' : 'Mulai Perekaman' }}
      </button>

      {{-- Muncul setelah foto diambil --}}
      <div id="saveArea" class="w-full flex flex-col gap-2" style="display:none">
        <button id="btnRetake" onclick="openCamera()"
                class="w-full py-2.5 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-50 text-sm font-medium transition">
          Ambil Ulang
        </button>
        <button id="btnSave"
                class="w-full py-2.5 rounded-xl bg-green-600 hover:bg-green-700 text-white text-sm font-semibold transition flex items-center justify-center gap-2">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
          Simpan & Daftarkan ke DPT
        </button>
      </div>

      {{-- Reset (jika sudah punya face) --}}
      @if($voter && $voter->hasFace())
      <form method="POST" action="{{ route('admin.dpt.reset', $voter) }}"
            onsubmit="return confirm('Reset data wajah? Perlu rekam ulang untuk vote.')">
        @csrf @method('DELETE')
        <button type="submit" class="text-xs text-red-500 hover:underline">Reset data wajah</button>
      </form>
      @endif
    </div>

    {{-- Hasil sukses/gagal --}}
    <div id="resultMsg" class="hidden w-full rounded-xl border p-4"></div>
  </div>

</div>

{{-- ══════════════════════════════════════════════════════════
     FULLSCREEN CAMERA OVERLAY
═══════════════════════════════════════════════════════════ --}}
<div id="cameraModal"
     class="fixed inset-0 z-50 bg-black hidden flex-col items-center justify-between"
     style="display:none">

  {{-- Top bar --}}
  <div class="w-full flex items-center justify-between px-5 pt-safe pt-5 pb-3">
    <button onclick="closeCamera()"
            class="flex items-center gap-2 text-white/80 hover:text-white text-sm transition">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      Batal
    </button>
    <div id="camStatus" class="flex items-center gap-2 text-white/80 text-sm">
      <span id="camDot" class="w-2 h-2 rounded-full bg-white/50 animate-pulse inline-block"></span>
      <span id="camStatusText">Memuat...</span>
    </div>
    <div class="w-16"></div>{{-- spacer --}}
  </div>

  {{-- Camera portrait view --}}
  <div class="flex-1 flex items-center justify-center w-full px-4">
    <div class="relative"
         style="width: min(320px, calc(100vw - 2rem)); aspect-ratio: 3/4; max-height: calc(100vh - 200px);">

      {{-- Video feed --}}
      <video id="camVideo" autoplay muted playsinline
             class="absolute inset-0 w-full h-full object-cover rounded-2xl"
             style="transform:scaleX(-1)"></video>

      {{-- Canvas deteksi (tidak terlihat, di belakang SVG guide) --}}
      <canvas id="faceCanvas" class="absolute inset-0 w-full h-full rounded-2xl opacity-0"
              style="transform:scaleX(-1)"></canvas>

      {{-- SVG oval guide --}}
      <svg class="absolute inset-0 w-full h-full rounded-2xl pointer-events-none"
           viewBox="0 0 300 400" preserveAspectRatio="xMidYMid slice">
        <defs>
          <mask id="ovalCut">
            <rect width="300" height="400" fill="white"/>
            <ellipse id="ovalShape" cx="150" cy="185" rx="110" ry="140" fill="black"/>
          </mask>
        </defs>
        {{-- dark area outside oval --}}
        <rect width="300" height="400" fill="rgba(0,0,0,0.55)" mask="url(#ovalCut)"/>
        {{-- oval border - berubah warna saat wajah terdeteksi --}}
        <ellipse id="ovalBorder" cx="150" cy="185" rx="110" ry="140"
                 fill="none" stroke="white" stroke-width="2.5" stroke-dasharray="8 4" opacity="0.7"/>
        {{-- corner markers --}}
        <g stroke="white" stroke-width="3" stroke-linecap="round" opacity="0.9">
          {{-- top-left --}} <path d="M40,80 L40,60 L60,60"/>
          {{-- top-right --}} <path d="M260,80 L260,60 L240,60"/>
          {{-- bottom-left --}} <path d="M40,320 L40,340 L60,340"/>
          {{-- bottom-right --}} <path d="M260,320 L260,340 L240,340"/>
        </g>
        {{-- teks panduan di bawah oval --}}
        <text id="ovalText" x="150" y="358" text-anchor="middle" fill="rgba(255,255,255,0.85)"
              font-size="13" font-family="sans-serif">Posisikan wajah di dalam oval</text>

      </svg>

      {{-- Countdown ring --}}
      <div id="countdownRing" class="absolute inset-0 flex items-center justify-center pointer-events-none" style="display:none">
        <div class="w-20 h-20 rounded-full bg-black/50 flex items-center justify-center">
          <span id="countdownNum" class="text-white text-4xl font-bold">3</span>
        </div>
      </div>

      {{-- Loading overlay di dalam kamera --}}
      <div id="camLoading"
           class="absolute inset-0 flex flex-col items-center justify-center bg-black/80 rounded-2xl">
        <div class="w-8 h-8 border-2 border-white/30 border-t-white rounded-full animate-spin mb-3"></div>
        <p id="camLoadingText" class="text-white text-sm text-center px-4">Membuka kamera...</p>
        {{-- progress bar model --}}
        <div class="mt-3 w-40 h-1.5 bg-white/20 rounded-full overflow-hidden">
          <div id="modelProgress" class="h-full bg-brand-500 rounded-full transition-all duration-300" style="width:0%"></div>
        </div>
      </div>
    </div>
  </div>

  {{-- Bottom bar --}}
  <div class="w-full px-6 pb-safe pb-8 pt-4 flex flex-col items-center gap-3">
    {{-- Quality indicator --}}
    <div id="qualityRow" class="w-full max-w-xs flex items-center gap-3" style="display:none">
      <div class="flex-1 h-1.5 bg-white/20 rounded-full overflow-hidden">
        <div id="qualityBar" class="h-full rounded-full transition-all duration-200 bg-green-400" style="width:0%"></div>
      </div>
      <span id="qualityPct" class="text-white/80 text-xs w-8 text-right">0%</span>
    </div>

    {{-- Capture button --}}
    <button id="btnCapture" disabled
            class="w-16 h-16 rounded-full border-4 border-white flex items-center justify-center transition
                   disabled:opacity-30 disabled:cursor-not-allowed
                   enabled:hover:bg-white/20 enabled:active:scale-95">
      <div class="w-12 h-12 rounded-full bg-white"></div>
    </button>
    <p class="text-white/50 text-xs">Tekan untuk merekam</p>
  </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('face-api/face-api.js') }}"></script>
<script>
const MODEL_URL   = '{{ asset('face-api/model') }}';
const CAPTURE_URL = '{{ route('admin.dpt.capture') }}';
const ALUMNI_ID   = {{ $alumni->id }};
const CSRF        = document.querySelector('meta[name="csrf-token"]').content;

const camVideo    = document.getElementById('camVideo');
const faceCanvas  = document.getElementById('faceCanvas');
const ctx         = faceCanvas.getContext('2d');
const modal       = document.getElementById('cameraModal');

let capturedDescriptor = null;
let capturedPhotoB64   = null;
let detectionInterval  = null;
let faceDetected       = false;
let consecutiveFrames  = 0;
let modelsReady        = false;
let cameraStream       = null;
let countdownTimer     = null;
let countingDown       = false;
let pendingDescriptor  = null;   // descriptor terbaru dari deteksi aktif

const REQUIRED_FRAMES = 5;

// ── Buka fullscreen camera ────────────────────────────────────────────────────
async function openCamera() {
  // Reset state
  capturedDescriptor = null;
  capturedPhotoB64   = null;
  faceDetected       = false;
  consecutiveFrames  = 0;
  countingDown       = false;
  pendingDescriptor  = null;
  clearInterval(detectionInterval);
  clearInterval(countdownTimer);

  // Tampilkan modal
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

  // 1. Buka kamera
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
      NotAllowedError:  'Izin kamera ditolak.',
      NotFoundError:    'Tidak ada kamera terdeteksi.',
      NotReadableError: 'Kamera dipakai aplikasi lain.',
    };
    setCamStatus(msgs[err.name] || err.message, 'error');
    document.getElementById('camLoadingText').textContent = msgs[err.name] || err.message;
    return;
  }

  // 2. Load model jika belum
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
      setCamStatus('Gagal memuat model: ' + err.message, 'error');
      document.getElementById('camLoadingText').textContent = 'Gagal: ' + err.message;
      return;
    }
  }

  // Sembunyikan loading, mulai deteksi
  document.getElementById('camLoading').style.display = 'none';
  setCamStatus('Arahkan wajah ke oval', 'idle');
  startDetection();
}

// ── Tutup kamera ─────────────────────────────────────────────────────────────
function closeCamera() {
  clearInterval(detectionInterval);
  clearInterval(countdownTimer);
  if (cameraStream) {
    cameraStream.getTracks().forEach(t => t.stop());
    cameraStream = null;
  }
  modal.style.display = 'none';
  document.body.style.overflow = '';
}

// ── Deteksi wajah ─────────────────────────────────────────────────────────────
function startDetection() {
  detectionInterval = setInterval(detectFace, 200);
}

async function detectFace() {
  if (!camVideo.videoWidth || !modelsReady) return;

  const opts = new faceapi.TinyFaceDetectorOptions({ inputSize: 320, scoreThreshold: 0.5 });
  const result = await faceapi
    .detectSingleFace(camVideo, opts)
    .withFaceLandmarks(true)
    .withFaceDescriptor();

  if (result) {
    const score = result.detection.score;
    updateQuality(score);
    consecutiveFrames++;

    // Cek wajah di tengah
    const { x, y, width, height } = result.detection.box;
    const faceCX   = (x + width / 2) / camVideo.videoWidth;
    const faceCY   = (y + height / 2) / camVideo.videoHeight;
    const centered = faceCX > 0.2 && faceCX < 0.8 && faceCY > 0.15 && faceCY < 0.8;

    if (!centered) {
      if (faceDetected) resetFaceState();
      setCamStatus('Geser agar wajah di tengah oval', 'idle');
      return;
    }

    // Simpan descriptor terbaru setiap frame
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
  faceDetected      = false;
  countingDown      = false;
  pendingDescriptor = null;
  clearInterval(countdownTimer);
  setOvalColor('white');
  setOvalText('Posisikan wajah di dalam oval');
  document.getElementById('btnCapture').disabled = true;
  document.getElementById('countdownRing').style.display = 'none';
}

// ── Auto-countdown 3 detik setelah wajah terdeteksi ─────────────────────────
function startCountdown() {
  if (countingDown) return;
  countingDown = true;
  let count = 5;
  const ring = document.getElementById('countdownRing');
  const num  = document.getElementById('countdownNum');
  ring.style.display = 'flex';
  num.textContent = count;

  countdownTimer = setInterval(async () => {
    count--;
    num.textContent = count;
    if (count <= 0) {
      clearInterval(countdownTimer);
      ring.style.display = 'none';
      await doCapture();
    }
  }, 1000);
}

// ── Ambil foto & descriptor ───────────────────────────────────────────────────
async function doCapture() {
  clearInterval(detectionInterval);
  setCamStatus('Memproses...', 'loading');

  // Gunakan descriptor yang sudah di-cache saat deteksi aktif — tidak perlu re-deteksi
  if (!pendingDescriptor) {
    setCamStatus('Gagal mendapatkan data wajah. Coba lagi.', 'error');
    resetFaceState();
    startDetection();
    return;
  }

  capturedDescriptor = pendingDescriptor;

  // Ambil foto portrait — ambil strip 3:4 dari tengah video (tidak gepeng)
  const photoCanvas = document.createElement('canvas');
  photoCanvas.width  = 300;
  photoCanvas.height = 400;
  const pc = photoCanvas.getContext('2d');

  // Hitung crop 3:4 portrait dari tengah frame landscape
  const vW = camVideo.videoWidth;
  const vH = camVideo.videoHeight;
  // Gunakan tinggi penuh, lebar = tinggi * 3/4
  const srcH = vH;
  const srcW = Math.round(vH * 3 / 4);   // misal 480 * 0.75 = 360
  const srcX = Math.round((vW - srcW) / 2); // tengahkan horizontal
  const srcY = 0;

  // Mirror horizontal agar tampak seperti cermin
  pc.save();
  pc.translate(300, 0);
  pc.scale(-1, 1);
  pc.drawImage(camVideo, srcX, srcY, srcW, srcH, 0, 0, 300, 400);
  pc.restore();

  capturedPhotoB64 = photoCanvas.toDataURL('image/jpeg', 0.90);

  // Tampilkan di halaman utama
  const preview = document.getElementById('photoPreview');
  preview.src = capturedPhotoB64;
  preview.style.display = 'block';
  const ph = document.getElementById('photoPlaceholder');
  if (ph) ph.style.display = 'none';
  document.getElementById('captureStatus').textContent = 'Foto berhasil diambil — klik Simpan untuk mendaftarkan';
  document.getElementById('saveArea').style.display = 'flex';
  document.getElementById('btnStart').style.display = 'none';

  // Tutup modal
  setTimeout(closeCamera, 300);
}

// ── Tombol capture manual ─────────────────────────────────────────────────────
document.getElementById('btnCapture').addEventListener('click', async () => {
  if (!faceDetected) return;
  clearInterval(countdownTimer);
  document.getElementById('countdownRing').style.display = 'none';
  countingDown = false;
  await doCapture();
});

// ── Simpan ke server ──────────────────────────────────────────────────────────
document.getElementById('btnSave').addEventListener('click', async () => {
  if (!capturedDescriptor) return;
  const btn = document.getElementById('btnSave');
  btn.disabled = true;
  btn.innerHTML = `<svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Menyimpan...`;

  try {
    const res  = await fetch(CAPTURE_URL, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
      body: JSON.stringify({
        face_descriptor: capturedDescriptor,
        face_photo:      capturedPhotoB64,
        alumni_id:       ALUMNI_ID,
      }),
    });

    let data = {};
    try { data = await res.json(); } catch (e) {}

    if (res.ok && data.success) {
      showResult(true, data.message + ' — Kode: ' + data.voter_code);
      document.getElementById('saveArea').style.display = 'none';
      setTimeout(() => location.reload(), 2500);
    } else {
      // Ambil pesan error dari Laravel (validasi, exception, dll.)
      let msg = data.message || (data.errors ? Object.values(data.errors).flat().join(', ') : null)
                || `Server error (HTTP ${res.status})`;
      showResult(false, msg);
      btn.disabled = false;
      btn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Simpan & Daftarkan ke DPT`;
    }
  } catch (err) {
    showResult(false, 'Koneksi error: ' + err.message);
    btn.disabled = false;
  }
});

// ── Helpers UI ────────────────────────────────────────────────────────────────
function setCamStatus(text, type) {
  const dot  = document.getElementById('camDot');
  const span = document.getElementById('camStatusText');
  span.textContent = text;
  const colors = { loading:'bg-blue-400', ok:'bg-green-400', error:'bg-red-400', idle:'bg-white/50' };
  dot.className = `w-2 h-2 rounded-full inline-block ${colors[type] || colors.idle} ${type === 'loading' ? 'animate-pulse' : ''}`;
}

function setOvalColor(color) {
  document.getElementById('ovalBorder').setAttribute('stroke', color);
}

function setOvalText(text) {
  const el = document.getElementById('ovalText');
  if (el) el.textContent = text;
}

function updateQuality(score) {
  const pct = Math.round(score * 100);
  document.getElementById('qualityRow').style.display = 'flex';
  const bar = document.getElementById('qualityBar');
  bar.style.width = pct + '%';
  bar.className = `h-full rounded-full transition-all duration-200 ${pct >= 85 ? 'bg-green-400' : pct >= 65 ? 'bg-amber-400' : 'bg-red-400'}`;
  document.getElementById('qualityPct').textContent = pct + '%';
}

function showResult(success, message) {
  const el = document.getElementById('resultMsg');
  el.className = `w-full rounded-xl border p-4 flex items-start gap-3 ${success ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800'}`;
  el.innerHTML = `
    <svg class="w-5 h-5 shrink-0 mt-0.5 ${success ? 'text-green-600' : 'text-red-500'}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      ${success
        ? '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'
        : '<path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>'}
    </svg>
    <div>
      <p class="font-semibold text-sm">${success ? 'Berhasil!' : 'Gagal'}</p>
      <p class="text-sm mt-0.5">${message}</p>
    </div>`;
  el.style.display = 'flex';
}

// Tutup modal jika tekan Escape
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeCamera(); });
</script>
@endpush
