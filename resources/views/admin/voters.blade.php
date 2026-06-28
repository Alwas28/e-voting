@extends('layouts.admin')

@section('title', 'Data Pemilih (DPT)')
@section('page-title', 'Data Pemilih (DPT)')

@section('content')

  {{-- Flash --}}
  @if (session('success'))
    <div id="flashMsg" class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-xl mb-1">
      <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      {{ session('success') }}
    </div>
  @endif
  @if (session('error'))
    <div id="flashMsg" class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-3 rounded-xl mb-1">
      <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      {{ session('error') }}
    </div>
  @endif

  {{-- Stat cards --}}
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="bg-white rounded-xl border border-slate-200 p-5">
      <p class="text-sm text-slate-500">Total DPT</p>
      <p class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($stats['total']) }}</p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5">
      <div class="flex items-center justify-between mb-1">
        <p class="text-sm text-slate-500">Wajah Terekam</p>
        <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/></svg>
      </div>
      <p class="text-2xl font-bold text-brand-600">{{ number_format($stats['face_ok']) }}</p>
      @if($stats['total'] > 0)
        <p class="text-xs text-slate-400 mt-0.5">{{ round($stats['face_ok'] / $stats['total'] * 100) }}% terdaftar</p>
      @endif
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5">
      <p class="text-sm text-slate-500">Sudah Memilih</p>
      <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format($stats['voted']) }}</p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5">
      <p class="text-sm text-slate-500">Belum Memilih</p>
      <p class="text-2xl font-bold text-amber-500 mt-1">{{ number_format($stats['not_voted']) }}</p>
    </div>
  </div>

  {{-- Filter & Tabel --}}
  <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">

    <div class="px-5 py-4 border-b border-slate-100 flex flex-col gap-3">
      <div class="flex items-center justify-between">
        <h2 class="font-semibold text-slate-800">Daftar Pemilih Tetap</h2>
        @if(auth()->user()->hasPermission('voters.create'))
        <button onclick="openModal('modalCreate')"
                class="flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
          Tambah Manual
        </button>
        @endif
      </div>

      <form method="GET" action="{{ route('admin.voters') }}" class="flex flex-wrap gap-2 items-center">
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
        <select name="face"
                class="border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-brand-500">
          <option value="">Semua Status Wajah</option>
          <option value="registered" {{ request('face') === 'registered' ? 'selected' : '' }}>Wajah Terekam</option>
          <option value="none" {{ request('face') === 'none' ? 'selected' : '' }}>Belum Rekam Wajah</option>
        </select>
        <select name="voted"
                class="border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-brand-500">
          <option value="">Semua Status Vote</option>
          <option value="yes" {{ request('voted') === 'yes' ? 'selected' : '' }}>Sudah Memilih</option>
          <option value="no" {{ request('voted') === 'no' ? 'selected' : '' }}>Belum Memilih</option>
        </select>
        <button type="submit"
                class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium rounded-lg transition">
          Filter
        </button>
        @if(request()->hasAny(['search','faculty','face','voted']))
          <a href="{{ route('admin.voters') }}"
             class="px-4 py-2 border border-slate-300 text-slate-600 hover:bg-slate-50 text-sm rounded-lg transition">
            Reset
          </a>
        @endif
      </form>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-500 text-left">
          <tr>
            <th class="px-5 py-3 font-medium w-8">#</th>
            <th class="px-5 py-3 font-medium">Pemilih</th>
            <th class="px-5 py-3 font-medium">NIM / Kode DPT</th>
            <th class="px-5 py-3 font-medium">Fakultas / Prodi</th>
            <th class="px-5 py-3 font-medium text-center">Wajah</th>
            <th class="px-5 py-3 font-medium text-center">Status Vote</th>
            <th class="px-5 py-3 font-medium text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse ($voters as $v)
            <tr class="hover:bg-slate-50">
              <td class="px-5 py-3 text-slate-400 text-xs">{{ $voters->firstItem() + $loop->index }}</td>

              <td class="px-5 py-3">
                <div class="flex items-center gap-3">
                  {{-- Foto wajah atau inisial --}}
                  <div class="w-9 h-9 rounded-full overflow-hidden shrink-0 bg-brand-100 flex items-center justify-center">
                    @if($v->face_photo)
                      <img src="{{ $v->face_photo }}" class="w-full h-full object-cover" alt="" />
                    @else
                      <span class="text-brand-700 text-xs font-bold">{{ $v->initials }}</span>
                    @endif
                  </div>
                  <div>
                    <p class="font-medium text-slate-800">{{ $v->name }}</p>
                    <p class="text-xs text-slate-400">{{ $v->email ?? $v->phone ?? '—' }}</p>
                  </div>
                </div>
              </td>

              <td class="px-5 py-3">
                <p class="font-mono text-slate-700 text-xs">{{ $v->nim }}</p>
                <p class="text-xs text-slate-400 mt-0.5">{{ $v->voter_code }}</p>
              </td>

              <td class="px-5 py-3">
                <p class="text-slate-700">{{ $v->faculty ?? '—' }}</p>
                <p class="text-xs text-slate-400">{{ $v->department }}</p>
              </td>

              <td class="px-5 py-3 text-center">
                @if($v->hasFace())
                  <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 text-xs font-medium px-2 py-0.5 rounded-full">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Terekam
                  </span>
                @else
                  @if($v->alumni_id)
                    <a href="{{ route('admin.dpt.register.alumni', $v->alumni_id) }}"
                       class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 text-xs font-medium px-2 py-0.5 rounded-full hover:bg-amber-100 transition">
                      <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/></svg>
                      Rekam
                    </a>
                  @else
                    <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-500 text-xs font-medium px-2 py-0.5 rounded-full">
                      Belum
                    </span>
                  @endif
                @endif
              </td>

              <td class="px-5 py-3 text-center">
                @if($v->has_voted)
                  <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 text-xs font-medium px-2.5 py-0.5 rounded-full">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Sudah
                  </span>
                  <p class="text-xs text-slate-400 mt-0.5">{{ $v->voted_at?->format('d/m H:i') }}</p>
                @else
                  <span class="inline-flex items-center bg-slate-100 text-slate-500 text-xs font-medium px-2.5 py-0.5 rounded-full">
                    Belum
                  </span>
                @endif
              </td>

              <td class="px-5 py-3">
                <div class="flex items-center justify-center gap-1">
                  <button onclick="openEditVoter({{ $v->toJson() }})"
                          class="p-1.5 rounded-lg text-slate-400 hover:text-brand-600 hover:bg-brand-50 transition" title="Edit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                  </button>
                  <button type="button"
                          onclick="confirmDelete('{{ route('admin.voters.destroy', $v) }}', '{{ addslashes($v->name) }} ({{ $v->nim }})')"
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
                  <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4z"/></svg>
                  <p class="text-sm">Belum ada data pemilih terdaftar.</p>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    @if ($voters->hasPages())
      <div class="px-5 py-4 border-t border-slate-100 flex items-center justify-between text-sm text-slate-500">
        <p>Menampilkan {{ $voters->firstItem() }}–{{ $voters->lastItem() }} dari {{ $voters->total() }} pemilih</p>
        <div class="flex items-center gap-1">
          @if ($voters->onFirstPage())
            <span class="px-3 py-1.5 rounded-lg text-slate-300 border border-slate-200">‹</span>
          @else
            <a href="{{ $voters->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 transition">‹</a>
          @endif
          @foreach ($voters->getUrlRange(max(1,$voters->currentPage()-2), min($voters->lastPage(),$voters->currentPage()+2)) as $page => $url)
            <a href="{{ $url }}"
               class="px-3 py-1.5 rounded-lg border transition {{ $page === $voters->currentPage() ? 'bg-brand-600 text-white border-brand-600' : 'border-slate-200 hover:bg-slate-50' }}">
              {{ $page }}
            </a>
          @endforeach
          @if ($voters->hasMorePages())
            <a href="{{ $voters->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 transition">›</a>
          @else
            <span class="px-3 py-1.5 rounded-lg text-slate-300 border border-slate-200">›</span>
          @endif
        </div>
      </div>
    @else
      <div class="px-5 py-3 border-t border-slate-100 text-xs text-slate-400">
        Total {{ $voters->total() }} pemilih terdaftar
      </div>
    @endif

  </div>

@endsection

{{-- ── Modal Tambah ─────────────────────────────────────────────────── --}}
@push('modals')

<div id="modalCreate" class="modal-backdrop hidden fixed inset-0 bg-black/40 z-50 items-center justify-center p-4">
  <div class="bg-white rounded-2xl shadow-xl w-full max-w-md flex flex-col">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
      <h3 class="font-semibold text-slate-800">Tambah Pemilih Manual</h3>
      <button onclick="closeModal('modalCreate')" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-400">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>
    <form method="POST" action="{{ route('admin.voters.store') }}">
      @csrf
      <div class="px-6 py-5 space-y-4">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">NIM <span class="text-red-500">*</span></label>
            <input type="text" name="nim" required
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500" />
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
            <input type="text" name="name" required
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500" />
          </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Fakultas</label>
            <input type="text" name="faculty"
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500" />
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Jurusan / Prodi</label>
            <input type="text" name="department"
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500" />
          </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
            <input type="email" name="email"
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500" />
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">No. HP</label>
            <input type="text" name="phone"
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500" />
          </div>
        </div>
        <p class="text-xs text-slate-400 bg-amber-50 border border-amber-100 rounded-lg px-3 py-2">
          Rekam wajah dapat dilakukan setelah data tersimpan melalui menu <strong>Pendaftaran DPT</strong>.
        </p>
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

{{-- Modal Edit --}}
<div id="modalEdit" class="modal-backdrop hidden fixed inset-0 bg-black/40 z-50 items-center justify-center p-4">
  <div class="bg-white rounded-2xl shadow-xl w-full max-w-md flex flex-col">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
      <h3 class="font-semibold text-slate-800">Edit Data Pemilih</h3>
      <button onclick="closeModal('modalEdit')" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-400">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>
    <form id="formEdit" method="POST" action="">
      @csrf @method('PUT')
      <div class="px-6 py-5 space-y-4">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">NIM</label>
            <input type="text" id="editNim" disabled
                   class="w-full border border-slate-200 bg-slate-50 rounded-lg px-3 py-2 text-sm text-slate-400 cursor-not-allowed font-mono" />
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="editName" required
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500" />
          </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Fakultas</label>
            <input type="text" name="faculty" id="editFaculty"
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500" />
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Jurusan</label>
            <input type="text" name="department" id="editDepartment"
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500" />
          </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
            <input type="email" name="email" id="editEmail"
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500" />
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">No. HP</label>
            <input type="text" name="phone" id="editPhone"
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500" />
          </div>
        </div>
        <div class="flex items-center gap-2">
          <input type="checkbox" name="is_active" id="editIsActive" value="1" class="rounded">
          <label for="editIsActive" class="text-sm text-slate-700">Aktif</label>
        </div>
      </div>
      <div class="px-6 py-4 border-t border-slate-100 flex justify-end gap-3">
        <button type="button" onclick="closeModal('modalEdit')"
                class="px-4 py-2 text-sm text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-50 transition">Batal</button>
        <button type="submit"
                class="px-5 py-2 text-sm font-medium text-white bg-brand-600 hover:bg-brand-700 rounded-lg transition">Simpan</button>
      </div>
    </form>
  </div>
</div>

@endpush

@push('scripts')
<script>
  function openEditVoter(v) {
    document.getElementById('formEdit').action = '/admin/voters/' + v.id;
    document.getElementById('editNim').value        = v.nim;
    document.getElementById('editName').value       = v.name;
    document.getElementById('editFaculty').value    = v.faculty   ?? '';
    document.getElementById('editDepartment').value = v.department ?? '';
    document.getElementById('editEmail').value      = v.email  ?? '';
    document.getElementById('editPhone').value      = v.phone  ?? '';
    document.getElementById('editIsActive').checked = v.is_active == true;
    openModal('modalEdit');
  }

  const flash = document.getElementById('flashMsg');
  if (flash) setTimeout(() => flash.remove(), 4000);

  @if ($errors->any() && old('nim') !== null)
    document.addEventListener('DOMContentLoaded', () => openModal('modalCreate'));
  @endif
</script>
@endpush
