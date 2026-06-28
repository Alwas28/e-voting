<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" id="htmlRoot">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Admin') — Sistem E-Voting</title>
<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          brand: {
            50: 'var(--brand-50)', 100: 'var(--brand-100)', 500: 'var(--brand-500)',
            600: 'var(--brand-600)', 700: 'var(--brand-700)', 900: 'var(--brand-900)'
          },
          sidebar: {
            DEFAULT: 'var(--sidebar-bg)', text: 'var(--sidebar-text)'
          }
        }
      }
    }
  }
</script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  body { font-family: 'Inter', sans-serif; }
  [x-cloak] { display: none; }

  :root {
    --brand-50:#eef2ff; --brand-100:#e0e7ff; --brand-500:#6366f1;
    --brand-600:#4f46e5; --brand-700:#4338ca; --brand-900:#312e81;
    --sidebar-bg:var(--brand-900); --sidebar-text:#ffffff;
    --sidebar-border:rgba(255,255,255,0.10);
    --sidebar-hover:rgba(255,255,255,0.05);
    --sidebar-active:rgba(255,255,255,0.10);
    --sidebar-muted:rgba(255,255,255,0.60);
  }
  [data-theme="emerald"] {
    --brand-50:#ecfdf5; --brand-100:#d1fae5; --brand-500:#10b981;
    --brand-600:#059669; --brand-700:#047857; --brand-900:#064e3b;
    --sidebar-bg:var(--brand-900);
  }
  [data-theme="rose"] {
    --brand-50:#fff1f2; --brand-100:#ffe4e6; --brand-500:#f43f5e;
    --brand-600:#e11d48; --brand-700:#be123c; --brand-900:#881337;
    --sidebar-bg:var(--brand-900);
  }
  [data-theme="amber"] {
    --brand-50:#fffbeb; --brand-100:#fef3c7; --brand-500:#f59e0b;
    --brand-600:#d97706; --brand-700:#b45309; --brand-900:#78350f;
    --sidebar-bg:var(--brand-900);
  }
  [data-theme="slate"] {
    --brand-50:#f8fafc; --brand-100:#f1f5f9; --brand-500:#64748b;
    --brand-600:#475569; --brand-700:#334155; --brand-900:#0f172a;
    --sidebar-bg:var(--brand-900);
  }
  [data-theme="white"] {
    --brand-50:#f1f5f9; --brand-100:#e2e8f0; --brand-500:#475569;
    --brand-600:#334155; --brand-700:#1e293b; --brand-900:#0f172a;
    --sidebar-bg:#ffffff; --sidebar-text:#1e293b;
    --sidebar-border:rgba(15,23,42,0.08);
    --sidebar-hover:rgba(15,23,42,0.04);
    --sidebar-active:rgba(15,23,42,0.06);
    --sidebar-muted:rgba(15,23,42,0.55);
  }

  .sidebar-surface { background: var(--sidebar-bg); color: var(--sidebar-text); }
  .sidebar-muted   { color: var(--sidebar-muted); }
  .sidebar-divider { border-bottom: 1px solid var(--sidebar-border); }
  .sidebar-divider-top { border-top: 1px solid var(--sidebar-border); }
  .sidebar-logo    { background: var(--sidebar-active); }
  .nav-item { color: var(--sidebar-muted); transition: background .15s, color .15s; }
  .nav-item:hover  { background: var(--sidebar-hover); color: var(--sidebar-text); }
  .nav-item.is-active { background: var(--sidebar-active); color: var(--sidebar-text); }

  .swatch.active { box-shadow: 0 0 0 2px #fff, 0 0 0 4px var(--brand-600); }
</style>
@stack('styles')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 text-slate-800">

<div id="app" class="flex min-h-screen">

  <!-- Sidebar overlay (mobile) -->
  <div id="overlay" class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden" onclick="toggleSidebar()"></div>

  <!-- Sidebar -->
  <aside id="sidebar" class="sidebar-surface fixed lg:static inset-y-0 left-0 z-40 w-64 transform -translate-x-full lg:translate-x-0 transition-transform duration-200 flex flex-col">
    <div class="sidebar-divider h-16 flex items-center gap-3 px-6">
      <div class="sidebar-logo w-9 h-9 rounded-lg flex items-center justify-center">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
      <div>
        <p class="font-semibold leading-tight">E-Voting</p>
        <p class="sidebar-muted text-xs">Panel Admin</p>
      </div>
    </div>

    @php $u = auth()->user(); @endphp
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">

      {{-- Dashboard: selalu terlihat --}}
      <a href="{{ route('admin.dashboard') }}"
         class="nav-item {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        Dashboard
      </a>

      {{-- Data Alumni --}}
      @if($u->hasPermission('users.view'))
      <a href="{{ route('admin.alumni.index') }}"
         class="nav-item {{ request()->routeIs('admin.alumni*') ? 'is-active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
        Data Alumni
      </a>
      @endif

      {{-- Pendaftaran DPT — alumni daftar sendiri, atau admin bisa akses --}}
      @if($u->hasPermission('voters.create') || $u->hasRole('alumni'))
      <a href="{{ route('admin.dpt.register') }}"
         class="nav-item {{ request()->routeIs('admin.dpt*') ? 'is-active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/></svg>
        Pendaftaran DPT
      </a>
      @endif

      {{-- Data Pemilih --}}
      @if($u->hasPermission('voters.view'))
      <a href="{{ route('admin.voters') }}"
         class="nav-item {{ request()->routeIs('admin.voters*') ? 'is-active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4z"/></svg>
        Data Pemilih
      </a>
      @endif

      {{-- Kandidat (admin) --}}
      @if($u->hasPermission('candidates.view'))
      <a href="{{ route('admin.candidates') }}"
         class="nav-item {{ request()->routeIs('admin.candidates*') ? 'is-active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        Kandidat
      </a>
      @endif

      {{-- Profil Saya (kandidat) --}}
      @if($u->hasRole('kandidat'))
      <a href="{{ route('kandidat.profil.edit') }}"
         class="nav-item {{ request()->routeIs('kandidat.profil*') ? 'is-active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Profil Saya
      </a>
      @endif

      {{-- Voting (semua alumni yang bisa memilih) --}}
      @if($u->alumni_id)
      <a href="{{ route('voting') }}"
         class="nav-item {{ request()->routeIs('voting*') ? 'is-active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Pilih Kandidat
      </a>
      @endif

      {{-- Hasil Voting: alumni juga bisa lihat --}}
      @if($u->hasPermission('election.results'))
      <a href="{{ route('admin.results') }}"
         class="nav-item {{ request()->routeIs('admin.results*') ? 'is-active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        Hasil Voting
      </a>
      @endif

      {{-- Jadwal Pemilihan --}}
      @if($u->hasPermission('election.manage'))
      <a href="{{ route('admin.schedule') }}"
         class="nav-item {{ request()->routeIs('admin.schedule*') ? 'is-active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        Jadwal Pemilihan
      </a>
      @endif

      {{-- Pengguna --}}
      @if($u->hasPermission('users.view'))
      <a href="{{ route('admin.users.index') }}"
         class="nav-item {{ request()->routeIs('admin.users*') ? 'is-active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        Pengguna
      </a>
      @endif

      {{-- Role & Akses --}}
      @if($u->hasPermission('roles.view'))
      <a href="{{ route('admin.roles.index') }}"
         class="nav-item {{ request()->routeIs('admin.roles*') ? 'is-active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
        Role & Akses
      </a>
      @endif

      {{-- Pengaturan --}}
      @if($u->hasPermission('settings.view'))
      <a href="{{ route('admin.settings') }}"
         class="nav-item {{ request()->routeIs('admin.settings*') ? 'is-active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        Pengaturan
      </a>
      @endif

    </nav>

    <div class="sidebar-divider-top sidebar-muted p-4 text-xs">
      &copy; {{ date('Y') }} E-Voting System
    </div>
  </aside>

  <!-- Main -->
  <div class="flex-1 flex flex-col min-w-0">

    <!-- Topbar -->
    <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 sticky top-0 z-20">
      <div class="flex items-center gap-3">
        <button onclick="toggleSidebar()" class="lg:hidden p-2 -ml-2 rounded-lg hover:bg-slate-100">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <h1 class="text-lg font-semibold text-slate-800">@yield('page-title', 'Dashboard')</h1>
      </div>

      <div class="flex items-center gap-2 sm:gap-3">
        <!-- Search -->
        <div class="hidden md:flex items-center bg-slate-100 rounded-lg px-3 py-2">
          <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          <input type="text" placeholder="Cari..." class="bg-transparent outline-none text-sm ml-2 w-32 lg:w-48" />
        </div>

        <!-- Notifications -->
        <button class="relative p-2 rounded-lg hover:bg-slate-100">
          <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
          <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
        </button>

        <!-- Profile dropdown -->
        <div class="relative">
          <button onclick="toggleProfile()" class="flex items-center gap-2 p-1 pr-2 rounded-lg hover:bg-slate-100">
            <div class="w-9 h-9 rounded-full bg-brand-600 text-white flex items-center justify-center font-semibold text-sm">
              {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
            <div class="hidden sm:block text-left">
              <p class="text-sm font-medium text-slate-800 leading-tight">{{ Auth::user()->name }}</p>
              <p class="text-xs text-slate-500">Administrator</p>
            </div>
            <svg class="w-4 h-4 text-slate-400 hidden sm:block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
          </button>

          <!-- Dropdown menu -->
          <div id="profileMenu" class="hidden absolute right-0 mt-2 w-60 bg-white rounded-xl shadow-lg border border-slate-200 py-2 z-50">
            <div class="px-4 py-3 border-b border-slate-100">
              <p class="text-sm font-semibold text-slate-800">{{ Auth::user()->name }}</p>
              <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</p>
            </div>

            <a href="{{ route('profile.edit') }}" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50">
              <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
              Profile
            </a>

            <a href="{{ route('profile.edit') }}#update-password" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50">
              <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
              Ubah Password
            </a>

            <div class="border-t border-slate-100 my-1"></div>

            <!-- Theme picker -->
            <div class="px-4 py-2.5">
              <p class="text-xs font-medium text-slate-500 mb-2">Warna Tema</p>
              <div class="flex items-center gap-2 flex-wrap">
                <button onclick="setTheme('indigo', this)" title="Indigo" class="swatch w-7 h-7 rounded-full" style="background:#4f46e5"></button>
                <button onclick="setTheme('emerald', this)" title="Emerald" class="swatch w-7 h-7 rounded-full" style="background:#059669"></button>
                <button onclick="setTheme('rose', this)" title="Rose" class="swatch w-7 h-7 rounded-full" style="background:#e11d48"></button>
                <button onclick="setTheme('amber', this)" title="Amber" class="swatch w-7 h-7 rounded-full" style="background:#d97706"></button>
                <button onclick="setTheme('slate', this)" title="Slate" class="swatch w-7 h-7 rounded-full" style="background:#475569"></button>
                <button onclick="setTheme('white', this)" title="Putih" class="swatch w-7 h-7 rounded-full bg-white border border-slate-300"></button>
              </div>
            </div>

            <div class="border-t border-slate-100 my-1"></div>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Logout
              </button>
            </form>
          </div>
        </div>
      </div>
    </header>

    <!-- Page Content -->
    <main class="flex-1 overflow-y-auto">
      {{-- Global flash messages --}}
      @if(session('error'))
      <div class="mx-4 sm:mx-6 mt-4 rounded-xl bg-red-50 border border-red-200 text-red-800 px-4 py-3 flex items-center gap-3 text-sm">
        <svg class="w-5 h-5 text-red-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        {{ session('error') }}
      </div>
      @endif
      <div class="p-4 sm:p-6 space-y-6">
        @yield('content')
      </div>
    </main>
  </div>
</div>

<!-- Page modals injected by child views -->
@stack('modals')

<!-- Modal Konfirmasi Hapus (global) -->
<div id="modalDelete" class="hidden fixed inset-0 bg-black/40 z-[60] items-center justify-center p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden
              transform transition-all duration-200 scale-95 opacity-0" id="modalDeleteBox">

    <!-- Strip merah atas -->
    <div class="h-1.5 w-full bg-gradient-to-r from-red-400 to-rose-500"></div>

    <div class="p-6">
      <!-- Ikon -->
      <div class="flex items-center justify-center mb-4">
        <div class="w-16 h-16 rounded-full bg-red-50 flex items-center justify-center">
          <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
          </svg>
        </div>
      </div>

      <!-- Teks -->
      <h3 class="text-center text-lg font-semibold text-slate-800 mb-1">Hapus Data?</h3>
      <p class="text-center text-sm text-slate-500 mb-1">
        Anda akan menghapus
      </p>
      <p id="deleteTargetName"
         class="text-center text-sm font-semibold text-slate-800 bg-slate-50 rounded-lg px-3 py-2 mb-4 truncate">
      </p>
      <p class="text-center text-xs text-slate-400 mb-6">
        Tindakan ini tidak dapat dibatalkan.
      </p>

      <!-- Tombol -->
      <div class="flex gap-3">
        <button onclick="closeDeleteModal()"
                class="flex-1 px-4 py-2.5 text-sm font-medium text-slate-700 bg-slate-100
                       hover:bg-slate-200 rounded-xl transition">
          Batal
        </button>
        <form id="deleteForm" method="POST" action="" class="flex-1">
          @csrf @method('DELETE')
          <button type="submit"
                  class="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium
                         text-white bg-red-500 hover:bg-red-600 active:bg-red-700 rounded-xl transition
                         shadow-sm shadow-red-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            Ya, Hapus
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Toast -->
<div id="toast" class="hidden fixed bottom-6 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-sm px-4 py-2.5 rounded-lg shadow-lg z-50"></div>

<script>
  function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('-translate-x-full');
    document.getElementById('overlay').classList.toggle('hidden');
  }

  function toggleProfile() {
    document.getElementById('profileMenu').classList.toggle('hidden');
  }

  const THEME_NAMES = {
    indigo:'Indigo', emerald:'Emerald', rose:'Rose',
    amber:'Amber', slate:'Slate', white:'Putih'
  };

  function setTheme(name, el) {
    const root = document.getElementById('htmlRoot');
    if (name === 'indigo') {
      root.removeAttribute('data-theme');
    } else {
      root.setAttribute('data-theme', name);
    }
    document.querySelectorAll('.swatch').forEach(s => s.classList.remove('active'));
    if (el) el.classList.add('active');
    try { localStorage.setItem('evoting-theme', name); } catch (_) {}
    showToast('Tema: ' + (THEME_NAMES[name] || name));
  }

  (function () {
    let saved = 'indigo';
    try { saved = localStorage.getItem('evoting-theme') || 'indigo'; } catch (_) {}
    const root = document.getElementById('htmlRoot');
    if (saved !== 'indigo') root.setAttribute('data-theme', saved);
    const map = { indigo:0, emerald:1, rose:2, amber:3, slate:4, white:5 };
    const swatches = document.querySelectorAll('.swatch');
    const idx = map[saved] ?? 0;
    if (swatches[idx]) swatches[idx].classList.add('active');
  })();

  function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.classList.remove('hidden');
    setTimeout(() => t.classList.add('hidden'), 2200);
  }

  document.addEventListener('click', (e) => {
    const menu = document.getElementById('profileMenu');
    if (!menu) return;
    const btn = e.target.closest('[onclick="toggleProfile()"]');
    if (!btn && !menu.contains(e.target)) menu.classList.add('hidden');
  });

  // ── Modal hapus global ──────────────────────────────────────────────
  function confirmDelete(action, label) {
    document.getElementById('deleteForm').action   = action;
    document.getElementById('deleteTargetName').textContent = label;

    const overlay = document.getElementById('modalDelete');
    const box     = document.getElementById('modalDeleteBox');

    overlay.classList.remove('hidden');
    overlay.classList.add('flex');
    document.body.style.overflow = 'hidden';

    // Animasi masuk
    requestAnimationFrame(() => {
      box.classList.remove('scale-95', 'opacity-0');
      box.classList.add('scale-100', 'opacity-100');
    });
  }

  function closeDeleteModal() {
    const overlay = document.getElementById('modalDelete');
    const box     = document.getElementById('modalDeleteBox');

    box.classList.remove('scale-100', 'opacity-100');
    box.classList.add('scale-95', 'opacity-0');

    setTimeout(() => {
      overlay.classList.add('hidden');
      overlay.classList.remove('flex');
      document.body.style.overflow = '';
    }, 150);
  }

  document.getElementById('modalDelete').addEventListener('click', function (e) {
    if (e.target === this) closeDeleteModal();
  });

  // ── Modal generik ───────────────────────────────────────────────────
  function openModal(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.classList.remove('hidden');
    el.classList.add('flex');
    document.body.style.overflow = 'hidden';
    el.addEventListener('click', function backdropClose(e) {
      if (e.target === el) { closeModal(id); el.removeEventListener('click', backdropClose); }
    });
  }

  function closeModal(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.classList.add('hidden');
    el.classList.remove('flex');
    document.body.style.overflow = '';
  }
</script>
@stack('scripts')
</body>
</html>
