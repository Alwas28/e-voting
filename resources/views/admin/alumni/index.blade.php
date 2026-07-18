@extends('layouts.admin')

@section('title', 'Data Alumni')
@section('page-title', 'Data Alumni')

@section('content')

  {{-- Flash --}}
  @if (session('success'))
    <div id="flashMsg" class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-xl">
      <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      {{ session('success') }}
    </div>
  @endif
  @if (session('error'))
    <div id="flashMsg" class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-3 rounded-xl">
      <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      {{ session('error') }}
    </div>
  @endif
  @if (session('import_errors'))
    <div class="bg-amber-50 border border-amber-200 text-amber-800 text-sm px-4 py-3 rounded-xl space-y-1">
      <p class="font-medium">Detail baris yang dilewati:</p>
      <ul class="list-disc list-inside space-y-0.5">
        @foreach (session('import_errors') as $err)
          <li class="text-xs">{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- Stat cards --}}
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="bg-white rounded-xl border border-slate-200 p-5">
      <p class="text-sm text-slate-500">Total Alumni</p>
      <p class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($stats['total']) }}</p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5">
      <p class="text-sm text-slate-500">Aktif</p>
      <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format($stats['active']) }}</p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5">
      <p class="text-sm text-slate-500">Tidak Aktif</p>
      <p class="text-2xl font-bold text-slate-400 mt-1">{{ number_format($stats['inactive']) }}</p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5">
      <p class="text-sm text-slate-500">Jumlah Fakultas</p>
      <p class="text-2xl font-bold text-brand-600 mt-1">{{ $stats['faculties'] }}</p>
    </div>
  </div>

  {{-- Filter & tabel --}}
  <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">

    {{-- Toolbar --}}
    <div class="px-5 py-4 border-b border-slate-100 flex flex-col gap-3">
      <div class="flex items-center justify-between flex-wrap gap-2">
        <h2 class="font-semibold text-slate-800">Daftar Alumni</h2>
        <div class="flex items-center gap-2">
          <a href="{{ route('admin.alumni.template') }}"
             class="flex items-center gap-1.5 border border-slate-300 text-slate-600 hover:bg-slate-50 text-sm font-medium px-3 py-2 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Template
          </a>
          <button onclick="openModal('modalImport')"
                  class="flex items-center gap-1.5 border border-green-600 text-green-700 hover:bg-green-50 text-sm font-medium px-3 py-2 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
            Import Excel
          </button>
          <button onclick="openModal('modalCreate')"
                  class="flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Tambah Alumni
          </button>
        </div>
      </div>

      {{-- Filter form --}}
      <form method="GET" action="{{ route('admin.alumni.index') }}"
            class="flex flex-wrap items-center gap-2">
        <div class="flex items-center bg-slate-100 rounded-lg px-3 py-2 flex-1 min-w-48">
          <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          <input type="text" name="search" value="{{ request('search') }}"
                 placeholder="Cari nama, NIM, email..."
                 class="bg-transparent outline-none text-sm ml-2 w-full" />
        </div>

        <select name="faculty"
                class="border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-brand-500">
          <option value="">Semua Fakultas</option>
          @foreach ($faculties as $f)
            <option value="{{ $f }}" {{ request('faculty') === $f ? 'selected' : '' }}>{{ $f }}</option>
          @endforeach
        </select>

        <select name="department"
                class="border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-brand-500">
          <option value="">Semua Program Studi</option>
          @foreach ($departments as $d)
            <option value="{{ $d }}" {{ request('department') === $d ? 'selected' : '' }}>{{ $d }}</option>
          @endforeach
        </select>

        <select name="year"
                class="border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-brand-500">
          <option value="">Semua Tahun</option>
          @foreach ($years as $y)
            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
          @endforeach
        </select>

        <select name="status"
                class="border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-brand-500">
          <option value="">Semua Status</option>
          <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Aktif</option>
          <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
        </select>

        <button type="submit"
                class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium rounded-lg transition">
          Filter
        </button>

        @if (request()->hasAny(['search','faculty','department','year','status']))
          <a href="{{ route('admin.alumni.index') }}"
             class="px-4 py-2 border border-slate-300 text-slate-600 text-sm rounded-lg hover:bg-slate-50 transition">
            Reset
          </a>
        @endif
      </form>
    </div>

    {{-- Tabel --}}
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-500 text-left">
          <tr>
            <th class="px-5 py-3 font-medium">Alumni</th>
            <th class="px-5 py-3 font-medium">NIM</th>
            <th class="px-5 py-3 font-medium">Fakultas / Jurusan</th>
            <th class="px-5 py-3 font-medium">Tahun Lulus</th>
            <th class="px-5 py-3 font-medium">Kontak</th>
            <th class="px-5 py-3 font-medium">Status</th>
            <th class="px-5 py-3 font-medium text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse ($alumni as $a)
            <tr class="hover:bg-slate-50">

              {{-- Avatar + nama --}}
              <td class="px-5 py-3">
                <div class="flex items-center gap-3">
                  @if ($a->photo)
                    <img src="{{ Storage::url($a->photo) }}" alt="{{ $a->name }}"
                         class="w-9 h-9 rounded-full object-cover shrink-0" />
                  @else
                    <div class="w-9 h-9 rounded-full bg-brand-100 text-brand-700 font-semibold text-sm flex items-center justify-center shrink-0">
                      {{ $a->initials }}
                    </div>
                  @endif
                  <span class="font-medium text-slate-800">{{ $a->name }}</span>
                </div>
              </td>

              <td class="px-5 py-3 font-mono text-slate-600">{{ $a->nim }}</td>

              <td class="px-5 py-3">
                <p class="text-slate-700">{{ $a->faculty }}</p>
                <p class="text-xs text-slate-400">{{ $a->department }}</p>
              </td>

              <td class="px-5 py-3 text-slate-700">{{ $a->graduation_year }}</td>

              <td class="px-5 py-3">
                @if ($a->email)
                  <p class="text-slate-700">{{ $a->email }}</p>
                @endif
                @if ($a->phone)
                  <p class="text-xs text-slate-400">{{ $a->phone }}</p>
                @endif
                @if (!$a->email && !$a->phone)
                  <span class="text-slate-300">—</span>
                @endif
              </td>

              <td class="px-5 py-3">
                <form method="POST" action="{{ route('admin.alumni.toggle', $a) }}">
                  @csrf @method('PATCH')
                  <button type="submit"
                          class="px-2.5 py-0.5 rounded-full text-xs font-medium transition
                                 {{ $a->is_active
                                    ? 'bg-green-50 text-green-700 hover:bg-green-100'
                                    : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                    {{ $a->is_active ? 'Aktif' : 'Tidak Aktif' }}
                  </button>
                </form>
              </td>

              <td class="px-5 py-3">
                <div class="flex items-center justify-center gap-2">
                  <button onclick="openEditAlumni({{ $a->toJson() }})"
                          class="p-1.5 rounded-lg text-slate-400 hover:text-brand-600 hover:bg-brand-50 transition" title="Edit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                  </button>
                  <button type="button"
                          onclick="confirmDelete('{{ route('admin.alumni.destroy', $a) }}', '{{ addslashes($a->name) }} ({{ $a->nim }})')"
                          class="p-1.5 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 transition" title="Hapus">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  </button>
                </div>
              </td>

            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-5 py-14 text-center">
                <div class="flex flex-col items-center gap-2 text-slate-400">
                  <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0v6m0-6l-3.5 2m3.5-2l3.5 2"/></svg>
                  <p class="text-sm">Belum ada data alumni.</p>
                  @if (request()->hasAny(['search','faculty','department','year','status']))
                    <a href="{{ route('admin.alumni.index') }}" class="text-brand-600 text-xs hover:underline">Hapus filter</a>
                  @endif
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    @if ($alumni->hasPages())
      <div class="px-5 py-4 border-t border-slate-100 flex items-center justify-between text-sm text-slate-500">
        <p>Menampilkan {{ $alumni->firstItem() }}–{{ $alumni->lastItem() }} dari {{ $alumni->total() }} alumni</p>
        <div class="flex items-center gap-1">
          @if ($alumni->onFirstPage())
            <span class="px-3 py-1.5 rounded-lg text-slate-300 border border-slate-200">‹</span>
          @else
            <a href="{{ $alumni->previousPageUrl() }}"
               class="px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 transition">‹</a>
          @endif

          @foreach ($alumni->getUrlRange(max(1,$alumni->currentPage()-2), min($alumni->lastPage(),$alumni->currentPage()+2)) as $page => $url)
            <a href="{{ $url }}"
               class="px-3 py-1.5 rounded-lg border transition
                      {{ $page === $alumni->currentPage()
                         ? 'bg-brand-600 text-white border-brand-600'
                         : 'border-slate-200 hover:bg-slate-50' }}">
              {{ $page }}
            </a>
          @endforeach

          @if ($alumni->hasMorePages())
            <a href="{{ $alumni->nextPageUrl() }}"
               class="px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 transition">›</a>
          @else
            <span class="px-3 py-1.5 rounded-lg text-slate-300 border border-slate-200">›</span>
          @endif
        </div>
      </div>
    @else
      <div class="px-5 py-3 border-t border-slate-100 text-xs text-slate-400">
        Total {{ $alumni->total() }} alumni
      </div>
    @endif

  </div>

@endsection

{{-- ── Modal: Tambah Alumni ────────────────────────────────────────── --}}
@push('modals')

<div id="modalCreate" class="modal-backdrop hidden fixed inset-0 bg-black/40 z-50 items-center justify-center p-4">
  <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] flex flex-col">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
      <h3 class="font-semibold text-slate-800">Tambah Data Alumni</h3>
      <button onclick="closeModal('modalCreate')" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-400">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>
    <form method="POST" action="{{ route('admin.alumni.store') }}" class="flex flex-col flex-1 overflow-hidden">
      @csrf
      <div class="px-6 py-5 space-y-4 overflow-y-auto flex-1">

        {{-- Error validasi --}}
        @if ($errors->any())
          <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl">
            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <ul class="list-disc list-inside space-y-0.5">
              @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">NIM <span class="text-red-500">*</span></label>
            <input type="text" name="nim" value="{{ old('nim') }}" required
                   class="w-full border {{ $errors->has('nim') ? 'border-red-400 bg-red-50' : 'border-slate-300' }} rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Tahun Lulus <span class="text-red-500">*</span></label>
            <input type="number" name="graduation_year" min="1990" max="{{ date('Y') + 1 }}"
                   value="{{ old('graduation_year', date('Y')) }}" required
                   class="w-full border {{ $errors->has('graduation_year') ? 'border-red-400 bg-red-50' : 'border-slate-300' }} rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
          <input type="text" name="name" value="{{ old('name') }}" required
                 class="w-full border {{ $errors->has('name') ? 'border-red-400 bg-red-50' : 'border-slate-300' }} rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Fakultas <span class="text-red-500">*</span></label>
            <input type="text" name="faculty" value="{{ old('faculty') }}" required list="facultyList"
                   class="w-full border {{ $errors->has('faculty') ? 'border-red-400 bg-red-50' : 'border-slate-300' }} rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
            <datalist id="facultyList">
              @foreach ($faculties as $f)
                <option value="{{ $f }}">
              @endforeach
            </datalist>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Jurusan / Prodi <span class="text-red-500">*</span></label>
            <input type="text" name="department" value="{{ old('department') }}" required
                   class="w-full border {{ $errors->has('department') ? 'border-red-400 bg-red-50' : 'border-slate-300' }} rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
          </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Tempat Lahir</label>
            <input type="text" name="place_of_birth" value="{{ old('place_of_birth') }}" placeholder="Kota kelahiran"
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Tanggal Lahir</label>
            <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" max="{{ date('Y-m-d') }}"
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">IPK</label>
          <input type="number" name="ipk" value="{{ old('ipk') }}"
                 min="0" max="4" step="0.01" placeholder="Contoh: 3.75"
                 class="w-full border {{ $errors->has('ipk') ? 'border-red-400 bg-red-50' : 'border-slate-300' }} rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
          <p class="text-xs text-slate-400 mt-1">Gunakan titik (.) sebagai pemisah desimal. Contoh: <span class="font-mono">3.75</span></p>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
            <input type="email" name="email" value="{{ old('email') }}"
                   class="w-full border {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-slate-300' }} rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">No. HP</label>
            <input type="text" name="phone" value="{{ old('phone') }}"
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">Alamat</label>
          <input type="text" name="address" value="{{ old('address') }}"
                 class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
        </div>

      </div>
      <div class="px-6 py-4 border-t border-slate-100 flex justify-end gap-3">
        <button type="button" onclick="closeModal('modalCreate')"
                class="px-4 py-2 text-sm text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-50 transition">Batal</button>
        <button type="submit"
                class="px-5 py-2 text-sm font-medium text-white bg-brand-600 hover:bg-brand-700 rounded-lg transition">Simpan</button>
      </div>
    </form>
  </div>
</div>

{{-- Modal: Edit Alumni --}}
<div id="modalEdit" class="modal-backdrop hidden fixed inset-0 bg-black/40 z-50 items-center justify-center p-4">
  <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] flex flex-col">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
      <h3 class="font-semibold text-slate-800">Edit Data Alumni</h3>
      <button onclick="closeModal('modalEdit')" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-400">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>
    <form id="formEdit" method="POST" action="" class="flex flex-col flex-1 overflow-hidden">
      @csrf @method('PUT')
      <div class="px-6 py-5 space-y-4 overflow-y-auto flex-1">

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">NIM</label>
            <input type="text" id="editNim" disabled
                   class="w-full border border-slate-200 bg-slate-50 rounded-lg px-3 py-2 text-sm text-slate-400 cursor-not-allowed font-mono" />
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Tahun Lulus <span class="text-red-500">*</span></label>
            <input type="number" name="graduation_year" id="editYear" min="1990" max="{{ date('Y') + 1 }}" required
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
          <input type="text" name="name" id="editName" required
                 class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Fakultas <span class="text-red-500">*</span></label>
            <input type="text" name="faculty" id="editFaculty" required list="facultyList"
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Jurusan / Prodi <span class="text-red-500">*</span></label>
            <input type="text" name="department" id="editDepartment" required
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
          </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Tempat Lahir</label>
            <input type="text" name="place_of_birth" id="editPlaceOfBirth" placeholder="Kota kelahiran"
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Tanggal Lahir</label>
            <input type="date" name="date_of_birth" id="editDateOfBirth" max="{{ date('Y-m-d') }}"
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">IPK</label>
          <input type="number" name="ipk" id="editIpk"
                 min="0" max="4" step="0.01" placeholder="Contoh: 3.75"
                 class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
          <p class="text-xs text-slate-400 mt-1">Gunakan titik (.) sebagai pemisah desimal. Contoh: <span class="font-mono">3.75</span></p>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
            <input type="email" name="email" id="editEmail"
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">No. HP</label>
            <input type="text" name="phone" id="editPhone"
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">Alamat</label>
          <input type="text" name="address" id="editAddress"
                 class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
        </div>

        <div class="flex items-center gap-3 pt-1">
          <input type="hidden" name="is_active" value="0" />
          <input type="checkbox" name="is_active" id="editIsActive" value="1"
                 class="w-4 h-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500" />
          <label for="editIsActive" class="text-sm text-slate-700">Aktif sebagai pemilih</label>
        </div>

      </div>
      <div class="px-6 py-4 border-t border-slate-100 flex justify-end gap-3">
        <button type="button" onclick="closeModal('modalEdit')"
                class="px-4 py-2 text-sm text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-50 transition">Batal</button>
        <button type="submit"
                class="px-5 py-2 text-sm font-medium text-white bg-brand-600 hover:bg-brand-700 rounded-lg transition">Perbarui</button>
      </div>
    </form>
  </div>
</div>

{{-- Modal: Import Excel --}}
<div id="modalImport" class="modal-backdrop hidden fixed inset-0 bg-black/40 z-50 items-center justify-center p-4">
  <div class="bg-white rounded-2xl shadow-xl w-full max-w-md">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
      <h3 class="font-semibold text-slate-800">Import Data Alumni</h3>
      <button onclick="closeModal('modalImport')" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-400">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>
    <form method="POST" action="{{ route('admin.alumni.import') }}" enctype="multipart/form-data">
      @csrf
      <div class="px-6 py-5 space-y-4">

        <p class="text-sm text-slate-500">
          Upload file Excel (.xlsx / .xls / .csv) sesuai format template. Data yang sudah ada (NIM sama) akan dilewati.
        </p>

        {{-- Drop zone --}}
        <label for="importFile"
               class="flex flex-col items-center gap-3 border-2 border-dashed border-slate-300 rounded-xl p-6 cursor-pointer hover:border-brand-400 hover:bg-brand-50/30 transition group">
          <svg class="w-8 h-8 text-slate-400 group-hover:text-brand-500 transition" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
          <div class="text-center">
            <span id="importFileName" class="text-sm font-medium text-slate-600 group-hover:text-brand-600">Klik untuk pilih file</span>
            <p class="text-xs text-slate-400 mt-0.5">.xlsx, .xls, atau .csv — maks. 5 MB</p>
          </div>
          <input id="importFile" type="file" name="file" accept=".xlsx,.xls,.csv"
                 class="hidden" onchange="updateFileName(this)" />
        </label>

        <div class="flex items-center gap-2 text-xs text-slate-500 bg-slate-50 rounded-lg px-3 py-2.5">
          <svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          Belum punya template?
          <a href="{{ route('admin.alumni.template') }}" class="text-brand-600 font-medium hover:underline">Download di sini</a>
        </div>

      </div>
      <div class="px-6 py-4 border-t border-slate-100 flex justify-end gap-3">
        <button type="button" onclick="closeModal('modalImport')"
                class="px-4 py-2 text-sm text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-50 transition">Batal</button>
        <button type="submit"
                class="px-5 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition">
          Import
        </button>
      </div>
    </form>
  </div>
</div>

@endpush

@push('scripts')
<script>
  function openModal(id) {
    const el = document.getElementById(id);
    el.classList.remove('hidden');
    el.classList.add('flex');
    document.body.style.overflow = 'hidden';
  }
  function closeModal(id) {
    const el = document.getElementById(id);
    el.classList.add('hidden');
    el.classList.remove('flex');
    document.body.style.overflow = '';
  }
  document.querySelectorAll('.modal-backdrop').forEach(el => {
    el.addEventListener('click', e => { if (e.target === el) closeModal(el.id); });
  });

  function openEditAlumni(a) {
    document.getElementById('formEdit').action = '/admin/alumni/' + a.id;
    document.getElementById('editNim').value          = a.nim;
    document.getElementById('editName').value         = a.name;
    document.getElementById('editFaculty').value      = a.faculty;
    document.getElementById('editDepartment').value   = a.department;
    document.getElementById('editYear').value         = a.graduation_year;
    document.getElementById('editPlaceOfBirth').value = a.place_of_birth ?? '';
    document.getElementById('editDateOfBirth').value  = a.date_of_birth  ?? '';
    document.getElementById('editIpk').value          = a.ipk !== null ? a.ipk : '';
    document.getElementById('editEmail').value        = a.email   ?? '';
    document.getElementById('editPhone').value        = a.phone   ?? '';
    document.getElementById('editAddress').value      = a.address ?? '';
    document.getElementById('editIsActive').checked   = a.is_active == true;
    openModal('modalEdit');
  }

  const flash = document.getElementById('flashMsg');
  if (flash) setTimeout(() => flash.remove(), 4000);

  function updateFileName(input) {
    const label = document.getElementById('importFileName');
    label.textContent = input.files.length ? input.files[0].name : 'Klik untuk pilih file';
  }

  function confirmDelete(url, label) {
    if (!confirm('Hapus alumni "' + label + '"? Tindakan ini tidak dapat dibatalkan.')) return;
    const f = document.createElement('form');
    f.method = 'POST'; f.action = url;
    f.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">'
                + '<input type="hidden" name="_method" value="DELETE">';
    document.body.appendChild(f); f.submit();
  }

  // Buka ulang modal tambah jika ada error validasi
  @if ($errors->any() && old('nim') !== null)
    document.addEventListener('DOMContentLoaded', () => openModal('modalCreate'));
  @endif
</script>
@endpush
