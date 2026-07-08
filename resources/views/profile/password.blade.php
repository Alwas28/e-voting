@extends('layouts.admin')

@section('title', 'Ubah Password')
@section('page-title', 'Ubah Password')

@section('content')
<div class="max-w-md mx-auto">

  <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100">
      <h3 class="font-semibold text-slate-900">Ubah Password</h3>
      <p class="text-xs text-slate-400 mt-0.5">Gunakan password yang kuat dan unik untuk keamanan akun Anda</p>
    </div>
    <div class="p-6">

      @if(session('status') === 'password-updated')
      <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-xl mb-5">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Password berhasil diperbarui.
      </div>
      @endif

      <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('PUT')

        {{-- Password saat ini --}}
        <div>
          <label for="current_password" class="block text-sm font-medium text-slate-700 mb-1.5">
            Password Saat Ini
          </label>
          <div class="relative">
            <input type="password" id="current_password" name="current_password"
                   autocomplete="current-password"
                   class="w-full border rounded-xl px-4 py-2.5 text-sm pr-11 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition
                          {{ $errors->updatePassword->has('current_password') ? 'border-red-400 bg-red-50' : 'border-slate-300' }}">
            <button type="button" onclick="togglePwd('current_password')"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
            </button>
          </div>
          @foreach($errors->updatePassword->get('current_password') as $e)
            <p class="text-xs text-red-500 mt-1">{{ $e }}</p>
          @endforeach
        </div>

        {{-- Password baru --}}
        <div>
          <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">
            Password Baru
          </label>
          <div class="relative">
            <input type="password" id="password" name="password"
                   autocomplete="new-password"
                   class="w-full border rounded-xl px-4 py-2.5 text-sm pr-11 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition
                          {{ $errors->updatePassword->has('password') ? 'border-red-400 bg-red-50' : 'border-slate-300' }}">
            <button type="button" onclick="togglePwd('password')"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
            </button>
          </div>
          @foreach($errors->updatePassword->get('password') as $e)
            <p class="text-xs text-red-500 mt-1">{{ $e }}</p>
          @endforeach
        </div>

        {{-- Konfirmasi password --}}
        <div>
          <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1.5">
            Konfirmasi Password Baru
          </label>
          <div class="relative">
            <input type="password" id="password_confirmation" name="password_confirmation"
                   autocomplete="new-password"
                   class="w-full border rounded-xl px-4 py-2.5 text-sm pr-11 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition
                          {{ $errors->updatePassword->has('password_confirmation') ? 'border-red-400 bg-red-50' : 'border-slate-300' }}">
            <button type="button" onclick="togglePwd('password_confirmation')"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
            </button>
          </div>
          @foreach($errors->updatePassword->get('password_confirmation') as $e)
            <p class="text-xs text-red-500 mt-1">{{ $e }}</p>
          @endforeach
        </div>

        <div class="pt-1">
          <button type="submit"
                  class="w-full bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-semibold py-3 rounded-xl transition">
            Perbarui Password
          </button>
        </div>
      </form>
    </div>
  </div>

</div>

@push('scripts')
<script>
function togglePwd(id) {
  const inp = document.getElementById(id);
  inp.type = inp.type === 'password' ? 'text' : 'password';
}
</script>
@endpush
@endsection
