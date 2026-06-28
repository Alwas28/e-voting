<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Daftar Alumni — E-Voting</title>
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
          {{-- Step 1 --}}
          <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full bg-indigo-600 text-white text-sm font-bold flex items-center justify-center shrink-0">
              1
            </div>
            <span class="text-sm font-semibold text-indigo-600">Verifikasi Data</span>
          </div>
          {{-- Connector --}}
          <div class="flex-1 h-0.5 bg-slate-200 rounded"></div>
          {{-- Step 2 --}}
          <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-400 text-sm font-bold flex items-center justify-center shrink-0">
              2
            </div>
            <span class="text-sm font-medium text-slate-400">Buat Akun</span>
          </div>
        </div>
      </div>

      {{-- Form --}}
      <div class="px-8 py-7">
        <h2 class="text-xl font-bold text-slate-800 mb-1">Verifikasi Data Alumni</h2>
        <p class="text-sm text-slate-500 mb-6">
          Masukkan NIM dan tanggal lahir Anda sesuai data yang terdaftar.
        </p>

        {{-- Error --}}
        @if ($errors->any())
          <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl mb-5">
            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p>{{ $errors->first() }}</p>
          </div>
        @endif

        @if (session('error'))
          <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl mb-5">
            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p>{{ session('error') }}</p>
          </div>
        @endif

        <form method="POST" action="{{ route('alumni.register.verify') }}">
          @csrf

          <div class="space-y-5">
            {{-- NIM --}}
            <div>
              <label for="nim" class="block text-sm font-medium text-slate-700 mb-1.5">
                Nomor Induk Mahasiswa (NIM)
              </label>
              <input type="text" id="nim" name="nim" value="{{ old('nim') }}"
                     required autofocus
                     placeholder="Contoh: 2021010001"
                     class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm
                            focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                            {{ $errors->has('nim') ? 'border-red-400 bg-red-50' : '' }}" />
            </div>

            {{-- Tanggal lahir --}}
            <div>
              <label for="date_of_birth" class="block text-sm font-medium text-slate-700 mb-1.5">
                Tanggal Lahir
              </label>
              <input type="date" id="date_of_birth" name="date_of_birth"
                     value="{{ old('date_of_birth') }}"
                     required
                     max="{{ date('Y-m-d') }}"
                     class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm
                            focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                            {{ $errors->has('date_of_birth') ? 'border-red-400 bg-red-50' : '' }}" />
            </div>
          </div>

          <button type="submit"
                  class="w-full mt-6 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800
                         text-white font-semibold py-3 rounded-xl transition flex items-center justify-center gap-2">
            Verifikasi Data
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
          </button>
        </form>

        <p class="text-center text-sm text-slate-500 mt-6">
          Sudah punya akun?
          <a href="{{ route('login') }}" class="text-indigo-600 font-medium hover:underline">Masuk di sini</a>
        </p>
      </div>
    </div>

    <p class="text-center text-indigo-400 text-xs mt-6">&copy; {{ date('Y') }} E-Voting System</p>
  </div>

</body>
</html>
