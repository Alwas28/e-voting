@extends('layouts.admin')

@section('title', 'Profil Saya')

@section('content')
<div class="p-6 max-w-xl mx-auto mt-10">
  <div class="bg-white rounded-2xl border border-slate-200 shadow-sm px-8 py-12 text-center">

    <div class="w-16 h-16 rounded-2xl bg-amber-50 flex items-center justify-center mx-auto mb-4">
      <svg class="w-8 h-8 text-amber-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
      </svg>
    </div>

    <h2 class="text-lg font-bold text-slate-800 mb-2">Data Kandidat Belum Terdaftar</h2>
    <p class="text-slate-500 text-sm leading-relaxed mb-6">
      Akun Anda belum terhubung ke data kandidat. Hubungi administrator untuk mendaftarkan Anda sebagai kandidat terlebih dahulu.
    </p>

    <div class="bg-slate-50 rounded-xl border border-slate-200 px-4 py-3 text-left text-xs text-slate-500 mb-6 space-y-1">
      <p class="font-semibold text-slate-600 mb-1.5">Langkah yang perlu dilakukan administrator:</p>
      <p>1. Buka menu <span class="font-medium text-slate-700">Kandidat</span> di panel admin</p>
      <p>2. Klik <span class="font-medium text-slate-700">Tambah Kandidat</span></p>
      <p>3. Cari nama Anda melalui kolom pencarian alumni</p>
      <p>4. Simpan data kandidat</p>
    </div>

    <a href="{{ route('admin.dashboard') }}"
       class="inline-flex items-center gap-2 text-sm font-semibold text-brand-600 hover:text-brand-700 transition">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
      </svg>
      Kembali ke Dashboard
    </a>

  </div>
</div>
@endsection
