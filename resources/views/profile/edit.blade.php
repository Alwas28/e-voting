@extends('layouts.admin')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@section('content')
@php
  $alumni = $user->alumni;
  $voter  = $alumni?->voter;
@endphp

<div class="max-w-3xl mx-auto space-y-6">

  {{-- Header profil --}}
  <div class="bg-white rounded-2xl border border-slate-200 p-6 flex items-center gap-5">
    <div class="w-16 h-16 rounded-2xl bg-brand-600 text-white flex items-center justify-center text-2xl font-extrabold shrink-0">
      {{ strtoupper(substr($user->name, 0, 1)) }}
    </div>
    <div class="min-w-0">
      <h2 class="text-xl font-bold text-slate-900 truncate">{{ $user->name }}</h2>
      <p class="text-sm text-slate-500 mt-0.5">{{ $user->email }}</p>
      <div class="flex flex-wrap gap-2 mt-2">
        @foreach($user->roles as $role)
          <span class="inline-block text-xs font-semibold bg-brand-100 text-brand-700 px-2.5 py-0.5 rounded-full capitalize">
            {{ $role->name }}
          </span>
        @endforeach
        @if($voter)
          <span class="inline-block text-xs font-semibold px-2.5 py-0.5 rounded-full
            {{ $voter->has_voted ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
            {{ $voter->has_voted ? '✓ Sudah Memilih' : '○ Belum Memilih' }}
          </span>
        @endif
      </div>
    </div>
  </div>

  {{-- Data Alumni (read-only) --}}
  @if($alumni)
  <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100">
      <h3 class="font-semibold text-slate-900">Data Alumni</h3>
      <p class="text-xs text-slate-400 mt-0.5">Informasi akademik dari data resmi kampus</p>
    </div>
    <div class="p-6 grid sm:grid-cols-2 gap-x-8 gap-y-5">
      @php
        $fields = [
          ['label' => 'NIM',              'value' => $alumni->nim],
          ['label' => 'Nama Lengkap',     'value' => $alumni->name],
          ['label' => 'Fakultas',         'value' => $alumni->faculty],
          ['label' => 'Program Studi',    'value' => $alumni->department],
          ['label' => 'Tahun Lulus',      'value' => $alumni->graduation_year],
          ['label' => 'Tempat Lahir',     'value' => $alumni->place_of_birth],
          ['label' => 'Tanggal Lahir',    'value' => $alumni->date_of_birth?->translatedFormat('d F Y') ?? $alumni->date_of_birth?->format('d F Y')],
          ['label' => 'Email',            'value' => $alumni->email],
          ['label' => 'No. Telepon',      'value' => $alumni->phone],
          ['label' => 'Alamat',           'value' => $alumni->address, 'full' => true],
        ];
      @endphp
      @foreach($fields as $f)
      <div class="{{ ($f['full'] ?? false) ? 'sm:col-span-2' : '' }}">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">{{ $f['label'] }}</p>
        <p class="text-sm font-medium text-slate-800">{{ $f['value'] ?: '—' }}</p>
      </div>
      @endforeach
    </div>
  </div>
  @endif

  {{-- Status DPT --}}
  @if($voter)
  <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100">
      <h3 class="font-semibold text-slate-900">Status DPT</h3>
    </div>
    <div class="p-6 grid sm:grid-cols-3 gap-4">
      <div class="bg-slate-50 rounded-xl p-4 text-center">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Status</p>
        <span class="inline-block text-sm font-bold {{ $voter->is_active ? 'text-green-600' : 'text-red-500' }}">
          {{ $voter->is_active ? 'Aktif' : 'Tidak Aktif' }}
        </span>
      </div>
      <div class="bg-slate-50 rounded-xl p-4 text-center">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Rekam Wajah</p>
        <span class="inline-block text-sm font-bold {{ $voter->face_descriptor ? 'text-green-600' : 'text-amber-500' }}">
          {{ $voter->face_descriptor ? 'Terdaftar' : 'Belum' }}
        </span>
      </div>
      <div class="bg-slate-50 rounded-xl p-4 text-center">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Voting</p>
        <span class="inline-block text-sm font-bold {{ $voter->has_voted ? 'text-green-600' : 'text-slate-500' }}">
          {{ $voter->has_voted ? 'Sudah Memilih' : 'Belum Memilih' }}
        </span>
      </div>
    </div>
  </div>
  @endif

</div>
@endsection
