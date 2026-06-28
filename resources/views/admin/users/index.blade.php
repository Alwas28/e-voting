@extends('layouts.admin')

@section('title', 'Pengguna')
@section('page-title', 'Pengguna')

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

  {{-- Stat cards --}}
  <div class="grid grid-cols-3 gap-4">
    <div class="bg-white rounded-xl border border-slate-200 p-5">
      <p class="text-sm text-slate-500">Total Pengguna</p>
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
  </div>

  {{-- Tabel --}}
  <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">

    {{-- Toolbar --}}
    <div class="px-5 py-4 border-b border-slate-100 flex flex-col gap-3">
      <div class="flex items-center justify-between">
        <h2 class="font-semibold text-slate-800">Daftar Pengguna</h2>
        <button onclick="openModal('modalCreate')"
                class="flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
          Tambah Pengguna
        </button>
      </div>

      <form method="GET" action="{{ route('admin.users.index') }}"
            class="flex flex-wrap items-center gap-2">
        <div class="flex items-center bg-slate-100 rounded-lg px-3 py-2 flex-1 min-w-48">
          <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          <input type="text" name="search" value="{{ request('search') }}"
                 placeholder="Cari nama atau email..."
                 class="bg-transparent outline-none text-sm ml-2 w-full" />
        </div>

        <select name="role"
                class="border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-brand-500">
          <option value="">Semua Role</option>
          @foreach ($roles as $role)
            <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>
              {{ $role->label }}
            </option>
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
        @if (request()->hasAny(['search','role','status']))
          <a href="{{ route('admin.users.index') }}"
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
            <th class="px-5 py-3 font-medium">Pengguna</th>
            <th class="px-5 py-3 font-medium">Email</th>
            <th class="px-5 py-3 font-medium">Role</th>
            <th class="px-5 py-3 font-medium">Bergabung</th>
            <th class="px-5 py-3 font-medium">Status</th>
            <th class="px-5 py-3 font-medium text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse ($users as $user)
            <tr class="hover:bg-slate-50 {{ !$user->is_active ? 'opacity-60' : '' }}">

              {{-- Avatar + nama --}}
              <td class="px-5 py-3">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 rounded-full flex items-center justify-center font-semibold text-sm shrink-0
                              {{ $user->id === auth()->id() ? 'bg-brand-600 text-white' : 'bg-slate-100 text-slate-600' }}">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                  </div>
                  <div>
                    <p class="font-medium text-slate-800 flex items-center gap-1.5">
                      {{ $user->name }}
                      @if ($user->id === auth()->id())
                        <span class="text-xs text-brand-600 font-normal">(Anda)</span>
                      @endif
                    </p>
                  </div>
                </div>
              </td>

              <td class="px-5 py-3 text-slate-500">{{ $user->email }}</td>

              {{-- Badges role --}}
              <td class="px-5 py-3">
                <div class="flex flex-wrap gap-1">
                  @forelse ($user->roles as $role)
                    <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-brand-50 text-brand-700">
                      {{ $role->label }}
                    </span>
                  @empty
                    <span class="text-slate-300 text-xs">—</span>
                  @endforelse
                </div>
              </td>

              <td class="px-5 py-3 text-slate-500">
                {{ $user->created_at->format('d M Y') }}
              </td>

              {{-- Toggle status --}}
              <td class="px-5 py-3">
                @if ($user->id !== auth()->id())
                  <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                    @csrf @method('PATCH')
                    <button type="submit"
                            class="px-2.5 py-0.5 rounded-full text-xs font-medium transition
                                   {{ $user->is_active
                                      ? 'bg-green-50 text-green-700 hover:bg-green-100'
                                      : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                      {{ $user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                    </button>
                  </form>
                @else
                  <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700">Aktif</span>
                @endif
              </td>

              {{-- Aksi --}}
              <td class="px-5 py-3">
                <div class="flex items-center justify-center gap-2">
                  <button onclick="openEditUser({{ $user->toJson() }}, {{ $user->roles->pluck('id')->toJson() }})"
                          class="p-1.5 rounded-lg text-slate-400 hover:text-brand-600 hover:bg-brand-50 transition" title="Edit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                  </button>
                  @if ($user->id !== auth()->id())
                    <button type="button"
                            onclick="confirmDelete('{{ route('admin.users.destroy', $user) }}', '{{ addslashes($user->name) }} <{{ addslashes($user->email) }}>')"
                            class="p-1.5 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 transition" title="Hapus">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                  @else
                    <span class="p-1.5 text-slate-200" title="Tidak dapat menghapus akun sendiri">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </span>
                  @endif
                </div>
              </td>

            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-5 py-14 text-center">
                <div class="flex flex-col items-center gap-2 text-slate-400">
                  <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                  <p class="text-sm">Belum ada data pengguna.</p>
                  @if (request()->hasAny(['search','role','status']))
                    <a href="{{ route('admin.users.index') }}" class="text-brand-600 text-xs hover:underline">Hapus filter</a>
                  @endif
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    @if ($users->hasPages())
      <div class="px-5 py-4 border-t border-slate-100 flex items-center justify-between text-sm text-slate-500">
        <p>Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }} pengguna</p>
        <div class="flex items-center gap-1">
          @if ($users->onFirstPage())
            <span class="px-3 py-1.5 rounded-lg text-slate-300 border border-slate-200">‹</span>
          @else
            <a href="{{ $users->previousPageUrl() }}"
               class="px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 transition">‹</a>
          @endif

          @foreach ($users->getUrlRange(max(1,$users->currentPage()-2), min($users->lastPage(),$users->currentPage()+2)) as $page => $url)
            <a href="{{ $url }}"
               class="px-3 py-1.5 rounded-lg border transition
                      {{ $page === $users->currentPage()
                         ? 'bg-brand-600 text-white border-brand-600'
                         : 'border-slate-200 hover:bg-slate-50' }}">
              {{ $page }}
            </a>
          @endforeach

          @if ($users->hasMorePages())
            <a href="{{ $users->nextPageUrl() }}"
               class="px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 transition">›</a>
          @else
            <span class="px-3 py-1.5 rounded-lg text-slate-300 border border-slate-200">›</span>
          @endif
        </div>
      </div>
    @else
      <div class="px-5 py-3 border-t border-slate-100 text-xs text-slate-400">
        Total {{ $users->total() }} pengguna
      </div>
    @endif

  </div>

@endsection

{{-- ── Modal: Tambah Pengguna ─────────────────────────────────────── --}}
@push('modals')

<div id="modalCreate" class="modal-backdrop hidden fixed inset-0 bg-black/40 z-50 items-center justify-center p-4">
  <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] flex flex-col">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
      <h3 class="font-semibold text-slate-800">Tambah Pengguna</h3>
      <button onclick="closeModal('modalCreate')" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-400">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>
    <form method="POST" action="{{ route('admin.users.store') }}" class="flex flex-col flex-1 overflow-hidden">
      @csrf
      <div class="px-6 py-5 space-y-4 overflow-y-auto flex-1">

        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
          <input type="text" name="name" required
                 class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">Email <span class="text-red-500">*</span></label>
          <input type="email" name="email" required
                 class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">Password <span class="text-red-500">*</span></label>
          <div class="relative">
            <input type="password" name="password" id="createPassword" required minlength="8"
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
            <button type="button" onclick="togglePassword('createPassword', this)"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
              <svg class="w-4 h-4 eye-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </button>
          </div>
          <p class="text-xs text-slate-400 mt-1">Minimal 8 karakter.</p>
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-700 mb-2">Role</label>
          <div class="space-y-2 border border-slate-200 rounded-xl p-3 max-h-40 overflow-y-auto">
            @foreach ($roles as $role)
              <label class="flex items-center gap-3 cursor-pointer hover:bg-slate-50 px-2 py-1.5 rounded-lg">
                <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                       class="w-4 h-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500" />
                <div>
                  <p class="text-sm font-medium text-slate-700">{{ $role->label }}</p>
                  @if ($role->description)
                    <p class="text-xs text-slate-400">{{ $role->description }}</p>
                  @endif
                </div>
              </label>
            @endforeach
          </div>
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

{{-- Modal: Edit Pengguna --}}
<div id="modalEdit" class="modal-backdrop hidden fixed inset-0 bg-black/40 z-50 items-center justify-center p-4">
  <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] flex flex-col">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
      <h3 class="font-semibold text-slate-800">Edit Pengguna</h3>
      <button onclick="closeModal('modalEdit')" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-400">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>
    <form id="formEdit" method="POST" action="" class="flex flex-col flex-1 overflow-hidden">
      @csrf @method('PUT')
      <div class="px-6 py-5 space-y-4 overflow-y-auto flex-1">

        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
          <input type="text" name="name" id="editName" required
                 class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">Email <span class="text-red-500">*</span></label>
          <input type="email" name="email" id="editEmail" required
                 class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">Password Baru</label>
          <div class="relative">
            <input type="password" name="password" id="editPassword" minlength="8"
                   placeholder="Kosongkan jika tidak diubah"
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
            <button type="button" onclick="togglePassword('editPassword', this)"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
              <svg class="w-4 h-4 eye-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </button>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-700 mb-2">Role</label>
          <div class="space-y-2 border border-slate-200 rounded-xl p-3 max-h-40 overflow-y-auto">
            @foreach ($roles as $role)
              <label class="flex items-center gap-3 cursor-pointer hover:bg-slate-50 px-2 py-1.5 rounded-lg">
                <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                       data-edit-role
                       class="w-4 h-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500" />
                <div>
                  <p class="text-sm font-medium text-slate-700">{{ $role->label }}</p>
                  @if ($role->description)
                    <p class="text-xs text-slate-400">{{ $role->description }}</p>
                  @endif
                </div>
              </label>
            @endforeach
          </div>
        </div>

        <div class="flex items-center gap-3 pt-1">
          <input type="hidden" name="is_active" value="0" />
          <input type="checkbox" name="is_active" id="editIsActive" value="1"
                 class="w-4 h-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500" />
          <label for="editIsActive" class="text-sm text-slate-700">Pengguna aktif</label>
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

  function openEditUser(user, roleIds) {
    document.getElementById('formEdit').action = '/admin/users/' + user.id;
    document.getElementById('editName').value     = user.name;
    document.getElementById('editEmail').value    = user.email;
    document.getElementById('editPassword').value = '';
    document.getElementById('editIsActive').checked = user.is_active == true;

    document.querySelectorAll('[data-edit-role]').forEach(cb => {
      cb.checked = roleIds.includes(parseInt(cb.value));
    });

    openModal('modalEdit');
  }

  function togglePassword(inputId, btn) {
    const inp = document.getElementById(inputId);
    const isHidden = inp.type === 'password';
    inp.type = isHidden ? 'text' : 'password';
    btn.querySelector('.eye-icon').innerHTML = isHidden
      ? '<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>'
      : '<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
  }

  const flash = document.getElementById('flashMsg');
  if (flash) setTimeout(() => flash.remove(), 4000);
</script>
@endpush
