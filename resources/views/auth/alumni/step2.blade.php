<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Buat Akun Alumni — E-Voting</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; }
  </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-950 via-indigo-900 to-indigo-800 flex items-center justify-center p-4">

  {{-- Dekorasi background --}}
  <div class="absolute inset-0 overflow-hidden pointer-events-none">
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>
  </div>

  <div class="relative w-full max-w-md">

    {{-- Logo --}}
    <div class="flex items-center justify-center gap-3 mb-8">
      <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center">
        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
      <div>
        <p class="text-white font-bold text-xl leading-tight">E-Voting</p>
        <p class="text-indigo-300 text-xs">Sistem Pemilihan Digital</p>
      </div>
    </div>

    {{-- Card --}}
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">

      {{-- Step indicator --}}
      <div class="px-8 pt-8 pb-6 border-b border-slate-100">
        <div class="flex items-center gap-3">
          {{-- Step 1 selesai --}}
          <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full bg-green-500 text-white text-sm font-bold flex items-center justify-center shrink-0">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </div>
            <span class="text-sm font-medium text-green-600">Verifikasi Data</span>
          </div>
          {{-- Connector --}}
          <div class="flex-1 h-0.5 bg-indigo-600 rounded"></div>
          {{-- Step 2 aktif --}}
          <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full bg-indigo-600 text-white text-sm font-bold flex items-center justify-center shrink-0">
              2
            </div>
            <span class="text-sm font-semibold text-indigo-600">Buat Akun</span>
          </div>
        </div>
      </div>

      {{-- Info alumni yang ditemukan --}}
      <div class="mx-8 mt-7 bg-indigo-50 border border-indigo-100 rounded-2xl px-4 py-4 flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-indigo-600 text-white font-bold text-lg flex items-center justify-center shrink-0">
          {{ strtoupper(substr($alumni->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $alumni->name)[1] ?? '_', 0, 1)) }}
        </div>
        <div class="min-w-0">
          <p class="font-semibold text-slate-800 truncate">{{ $alumni->name }}</p>
          <p class="text-xs text-slate-500">{{ $alumni->nim }} &middot; {{ $alumni->faculty }}</p>
          <p class="text-xs text-slate-500">{{ $alumni->department }}, {{ $alumni->graduation_year }}</p>
        </div>
        <div class="shrink-0">
          <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-medium">Terverifikasi</span>
        </div>
      </div>

      {{-- Form --}}
      <div class="px-8 pt-5 pb-7">
        <h2 class="text-xl font-bold text-slate-800 mb-1">Buat Akun Anda</h2>
        <p class="text-sm text-slate-500 mb-5">Lengkapi informasi berikut untuk membuat akun alumni.</p>

        {{-- Error --}}
        @if ($errors->any())
          <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl mb-5">
            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <ul class="list-disc list-inside space-y-0.5">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('alumni.register.submit') }}">
          @csrf

          <div class="space-y-5">

            {{-- Username / Nama tampil --}}
            <div>
              <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">
                Username <span class="text-red-500">*</span>
              </label>
              <input type="text" id="name" name="name" value="{{ old('name') }}"
                     required autofocus
                     placeholder="Nama yang akan ditampilkan"
                     class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm
                            focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                            {{ $errors->has('name') ? 'border-red-400 bg-red-50' : '' }}" />
              <p class="text-xs text-slate-400 mt-1">Nama yang akan tampil di sistem.</p>
            </div>

            {{-- Email --}}
            <div>
              <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">
                Email <span class="text-red-500">*</span>
              </label>
              <input type="email" id="email" name="email"
                     value="{{ old('email', $alumni->email) }}"
                     required
                     placeholder="email@contoh.com"
                     class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm
                            focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                            {{ $errors->has('email') ? 'border-red-400 bg-red-50' : '' }}" />
            </div>

            {{-- Password --}}
            <div>
              <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">
                Password <span class="text-red-500">*</span>
              </label>
              <div class="relative">
                <input type="password" id="password" name="password"
                       required minlength="8"
                       placeholder="Minimal 8 karakter"
                       class="w-full border border-slate-300 rounded-xl px-4 py-3 pr-11 text-sm
                              focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                              {{ $errors->has('password') ? 'border-red-400 bg-red-50' : '' }}" />
                <button type="button" onclick="togglePwd('password', this)"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1">
                  <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </button>
              </div>
            </div>

            {{-- Konfirmasi Password --}}
            <div>
              <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1.5">
                Konfirmasi Password <span class="text-red-500">*</span>
              </label>
              <div class="relative">
                <input type="password" id="password_confirmation" name="password_confirmation"
                       required minlength="8"
                       placeholder="Ulangi password"
                       class="w-full border border-slate-300 rounded-xl px-4 py-3 pr-11 text-sm
                              focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                <button type="button" onclick="togglePwd('password_confirmation', this)"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1">
                  <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </button>
              </div>

              {{-- Password strength indicator --}}
              <div class="mt-2 flex gap-1" id="strengthBars">
                <div class="h-1 flex-1 rounded-full bg-slate-200" id="bar1"></div>
                <div class="h-1 flex-1 rounded-full bg-slate-200" id="bar2"></div>
                <div class="h-1 flex-1 rounded-full bg-slate-200" id="bar3"></div>
                <div class="h-1 flex-1 rounded-full bg-slate-200" id="bar4"></div>
              </div>
              <p class="text-xs text-slate-400 mt-1" id="strengthLabel">Masukkan password untuk melihat kekuatan</p>
            </div>

            {{-- Role info (read-only) --}}
            <div class="flex items-center gap-3 bg-slate-50 border border-slate-200 rounded-xl px-4 py-3">
              <svg class="w-5 h-5 text-indigo-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
              <div>
                <p class="text-sm font-medium text-slate-700">Role: <span class="text-indigo-600">Alumni</span></p>
                <p class="text-xs text-slate-400">Ditetapkan otomatis oleh sistem.</p>
              </div>
            </div>
          </div>

          <div class="flex gap-3 mt-6">
            <a href="{{ route('alumni.register.step1') }}"
               class="flex-1 flex items-center justify-center gap-2 border border-slate-300 text-slate-600
                      hover:bg-slate-50 font-medium py-3 rounded-xl transition text-sm">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
              Kembali
            </a>
            <button type="button" id="btnSubmit" onclick="showSavePopup()"
                    class="flex-1 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800
                           text-white font-semibold py-3 rounded-xl transition flex items-center justify-center gap-2 text-sm">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
              Buat Akun
            </button>
          </div>
        </form>
      </div>
    </div>

    <p class="text-center text-indigo-400 text-xs mt-6">&copy; {{ date('Y') }} E-Voting System</p>
  </div>

{{-- Popup: Simpan Kredensial --}}
<div id="savePopup" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none!important">
  <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
  <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden">

    {{-- Header --}}
    <div class="bg-amber-50 border-b border-amber-100 px-6 py-5 flex items-start gap-4">
      <div class="w-11 h-11 rounded-2xl bg-amber-100 flex items-center justify-center shrink-0">
        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        </svg>
      </div>
      <div>
        <p class="font-bold text-slate-800 text-base">Simpan Data Login Anda!</p>
        <p class="text-sm text-amber-700 mt-0.5">Catat atau screenshot email dan password di bawah agar tidak terlupa.</p>
      </div>
    </div>

    {{-- Kredensial --}}
    <div class="px-6 py-5 space-y-3">

      <div class="bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3">
        <p class="text-xs font-medium text-slate-400 mb-1">Email</p>
        <div class="flex items-center justify-between gap-2">
          <p id="popupEmail" class="text-sm font-semibold text-slate-800 break-all"></p>
          <button onclick="copyText('popupEmail', this)" title="Salin"
                  class="shrink-0 p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
          </button>
        </div>
      </div>

      <div class="bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3">
        <p class="text-xs font-medium text-slate-400 mb-1">Password</p>
        <div class="flex items-center justify-between gap-2">
          <p id="popupPassword" class="text-sm font-semibold text-slate-800 font-mono tracking-widest"></p>
          <button onclick="copyText('popupPassword', this)" title="Salin"
                  class="shrink-0 p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
          </button>
        </div>
      </div>

      <div class="flex items-start gap-2 text-xs text-slate-500 bg-blue-50 border border-blue-100 rounded-xl px-3 py-2.5">
        <svg class="w-4 h-4 text-blue-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Password tidak bisa dilihat lagi setelah akun dibuat. Simpan sekarang sebelum melanjutkan.
      </div>
    </div>

    {{-- Actions --}}
    <div class="px-6 pb-6 flex flex-col gap-2">
      <button id="btnConfirm" onclick="confirmAndSubmit()"
              class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-xl transition flex items-center justify-center gap-2 text-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        Sudah Disimpan, Buat Akun
      </button>
      <button onclick="closePopup()"
              class="w-full border border-slate-300 text-slate-600 hover:bg-slate-50 font-medium py-2.5 rounded-xl transition text-sm">
        Kembali &amp; Periksa Lagi
      </button>
    </div>

  </div>
</div>

<script>
  function togglePwd(id, btn) {
    const inp = document.getElementById(id);
    const isHidden = inp.type === 'password';
    inp.type = isHidden ? 'text' : 'password';
    btn.querySelector('.eye-icon').innerHTML = isHidden
      ? '<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>'
      : '<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
  }

  // Password strength
  document.getElementById('password').addEventListener('input', function () {
    const val = this.value;
    const bars   = [1,2,3,4].map(i => document.getElementById('bar' + i));
    const label  = document.getElementById('strengthLabel');
    let score = 0;
    if (val.length >= 8)                     score++;
    if (/[A-Z]/.test(val))                   score++;
    if (/[0-9]/.test(val))                   score++;
    if (/[^A-Za-z0-9]/.test(val))            score++;

    const colors  = ['bg-red-400','bg-orange-400','bg-yellow-400','bg-green-500'];
    const labels  = ['Sangat Lemah','Lemah','Cukup','Kuat'];
    const txtClrs = ['text-red-500','text-orange-500','text-yellow-600','text-green-600'];

    bars.forEach((b, i) => {
      b.className = 'h-1 flex-1 rounded-full ' +
        (i < score && val.length > 0 ? colors[score - 1] : 'bg-slate-200');
    });

    if (val.length === 0) {
      label.textContent = 'Masukkan password untuk melihat kekuatan';
      label.className   = 'text-xs text-slate-400 mt-1';
    } else {
      label.textContent = labels[score - 1] || 'Sangat Lemah';
      label.className   = 'text-xs mt-1 ' + (txtClrs[score - 1] || 'text-red-500');
    }
  });

  function showSavePopup() {
    const form  = document.querySelector('form[action="{{ route("alumni.register.submit") }}"]');
    const email = document.getElementById('email').value.trim();
    const pwd   = document.getElementById('password').value;

    // Trigger HTML5 validation first
    if (!form.checkValidity()) { form.reportValidity(); return; }

    document.getElementById('popupEmail').textContent    = email || '(tidak diisi)';
    document.getElementById('popupPassword').textContent = pwd   || '(tidak diisi)';

    const popup = document.getElementById('savePopup');
    popup.style.cssText = 'display:flex!important';
    document.body.style.overflow = 'hidden';
  }

  function closePopup() {
    document.getElementById('savePopup').style.cssText = 'display:none!important';
    document.body.style.overflow = '';
  }

  function confirmAndSubmit() {
    const btn = document.getElementById('btnConfirm');
    btn.disabled = true;
    btn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg> Memproses...';
    document.querySelector('form[action="{{ route("alumni.register.submit") }}"]').submit();
  }

  function copyText(id, btn) {
    const text = document.getElementById(id).textContent;
    navigator.clipboard.writeText(text).then(() => {
      const orig = btn.innerHTML;
      btn.innerHTML = '<svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>';
      setTimeout(() => { btn.innerHTML = orig; }, 1500);
    });
  }
</script>
</body>
</html>
