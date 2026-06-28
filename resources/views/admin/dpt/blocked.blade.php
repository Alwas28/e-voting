@extends('layouts.admin')

@section('title', 'Pendaftaran DPT')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center p-6">
  <div class="text-center max-w-sm">
    <div class="w-16 h-16 rounded-2xl bg-amber-100 flex items-center justify-center mx-auto mb-4">
      <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
    </div>
    <h2 class="text-xl font-bold text-slate-800 mb-2">Pendaftaran Belum Tersedia</h2>
    <p class="text-slate-500 text-sm leading-relaxed">{{ $message }}</p>
    <a href="{{ route('admin.dashboard') }}"
       class="mt-6 inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
      </svg>
      Kembali ke Dashboard
    </a>
  </div>
</div>
@endsection
