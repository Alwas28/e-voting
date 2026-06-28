<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Masuk — E-Voting</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-950 via-indigo-900 to-indigo-800 flex items-center justify-center p-4">

  {{-- Dekorasi --}}
  <div class="absolute inset-0 overflow-hidden pointer-events-none">
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>
  </div>

  <div class="relative w-full max-w-md">

    {{-- Logo --}}
    <div class="flex items-center justify-center gap-3 mb-8">
      <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center">
        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>
      <div>
        <p class="text-white font-bold text-xl leading-tight">E-Voting</p>
        <p class="text-indigo-300 text-xs">Sistem Pemilihan Digital</p>
      </div>
    </div>

    {{-- Card --}}
    <div class="bg-white rounded-3xl shadow-2xl px-8 py-8">

      <h2 class="text-2xl font-bold text-slate-800 mb-1">Masuk</h2>
      <p class="text-sm text-slate-500 mb-7">Gunakan akun yang telah terdaftar untuk masuk.</p>

      {{-- Session status --}}
      @if (session('status'))
      <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-xl mb-5">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('status') }}
      </div>
      @endif

      {{-- Error --}}
      @if ($errors->any())
      <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl mb-5">
        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p>{{ $errors->first() }}</p>
      </div>
      @endif

      <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Email --}}
        <div>
          <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">
            Email
          </label>
          <input type="email" id="email" name="email"
                 value="{{ old('email') }}"
                 required autofocus autocomplete="email"
                 placeholder="email@contoh.com"
                 class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm
                        focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition
                        {{ $errors->has('email') ? 'border-red-400 bg-red-50' : '' }}">
        </div>

        {{-- Password --}}
        <div>
          <div class="flex items-center justify-between mb-1.5">
            <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
            @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}"
               class="text-xs text-indigo-600 hover:text-indigo-700 font-medium hover:underline">
              Lupa password?
            </a>
            @endif
          </div>
          <div class="relative">
            <input type="password" id="password" name="password"
                   required autocomplete="current-password"
                   placeholder="Masukkan password"
                   class="w-full border border-slate-300 rounded-xl px-4 py-3 pr-11 text-sm
                          focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition
                          {{ $errors->has('password') ? 'border-red-400 bg-red-50' : '' }}">
            <button type="button" onclick="togglePwd()"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1">
              <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
            </button>
          </div>
        </div>

        {{-- Remember me --}}
        <label class="flex items-center gap-2.5 cursor-pointer select-none">
          <input type="checkbox" name="remember" id="remember_me"
                 class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
          <span class="text-sm text-slate-600">Ingat saya</span>
        </label>

        <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800
                       text-white font-semibold py-3 rounded-xl transition flex items-center justify-center gap-2">
          Masuk
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
          </svg>
        </button>
      </form>

      {{-- Divider --}}
      <div class="flex items-center gap-3 my-6">
        <div class="flex-1 h-px bg-slate-200"></div>
        <span class="text-xs text-slate-400">atau</span>
        <div class="flex-1 h-px bg-slate-200"></div>
      </div>

      {{-- Daftar alumni --}}
      <a href="{{ route('alumni.register.step1') }}"
         class="w-full flex items-center justify-center gap-2 border border-indigo-200 text-indigo-600
                hover:bg-indigo-50 font-semibold py-3 rounded-xl transition text-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
        </svg>
        Daftar sebagai Alumni
      </a>

    </div>

    <p class="text-center text-indigo-400 text-xs mt-6">&copy; {{ date('Y') }} E-Voting System</p>
  </div>

<script>
function togglePwd() {
  const inp = document.getElementById('password');
  const isHidden = inp.type === 'password';
  inp.type = isHidden ? 'text' : 'password';
  document.getElementById('eyeIcon').innerHTML = isHidden
    ? '<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>'
    : '<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
}
</script>
</body>
</html>
