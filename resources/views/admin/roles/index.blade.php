@extends('layouts.admin')

@section('title', 'Role & Akses')
@section('page-title', 'Role & Akses')

@section('content')

  {{-- Flash messages --}}
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

  {{-- Header --}}
  <div class="flex items-center justify-between">
    <div>
      <p class="text-sm text-slate-500 mt-0.5">Kelola role dan hak akses pengguna sistem.</p>
    </div>
    <button onclick="openModal('modalCreate')"
            class="flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
      Tambah Role
    </button>
  </div>

  {{-- Role cards --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
    @foreach ($roles as $role)
      <div class="bg-white rounded-xl border border-slate-200 p-5 flex flex-col gap-4">
        <div class="flex items-start justify-between">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-brand-50 flex items-center justify-center shrink-0">
              <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <div>
              <p class="font-semibold text-slate-800 leading-tight">{{ $role->label }}</p>
              <p class="text-xs text-slate-400 font-mono">{{ $role->name }}</p>
            </div>
          </div>
          @if ($role->is_system)
            <span class="text-xs bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full shrink-0">Sistem</span>
          @endif
        </div>

        <p class="text-xs text-slate-500 leading-relaxed">{{ $role->description ?? '—' }}</p>

        <div class="flex items-center gap-4 text-xs text-slate-500">
          <span class="flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            {{ $role->permissions_count }} izin
          </span>
          <span class="flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4z"/></svg>
            {{ $role->users_count }} pengguna
          </span>
        </div>

        {{-- 3 tombol berjejer --}}
        <div class="flex items-center gap-2 pt-1 border-t border-slate-100">
          <button onclick="openEdit({{ $role->id }}, {{ json_encode($role->label) }}, {{ json_encode($role->description) }}, {{ json_encode($role->permissions->pluck('id')) }})"
                  class="flex-1 flex items-center justify-center gap-1.5 bg-brand-600 hover:bg-brand-700 text-white text-xs font-medium py-2 rounded-lg transition">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
            Atur Izin
          </button>

          <button onclick="openEditInfo({{ $role->id }}, {{ json_encode($role->label) }}, {{ json_encode($role->description) }}, {{ json_encode($role->permissions->pluck('id')) }})"
                  class="flex-1 flex items-center justify-center gap-1.5 border border-slate-300 text-slate-600 hover:bg-slate-50 text-xs font-medium py-2 rounded-lg transition">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit
          </button>

          @if (!$role->is_system)
            <button type="button"
                    onclick="confirmDelete('{{ route('admin.roles.destroy', $role) }}', 'Role: {{ addslashes($role->label) }}')"
                    class="flex-1 flex items-center justify-center gap-1.5 border border-red-200 text-red-500 hover:bg-red-50 text-xs font-medium py-2 rounded-lg transition">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
              Hapus
            </button>
          @else
            <span class="flex-1 flex items-center justify-center gap-1.5 border border-slate-100 text-slate-300 text-xs py-2 rounded-lg select-none">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
              Hapus
            </span>
          @endif
        </div>
      </div>
    @endforeach
  </div>

  {{-- Permission matrix --}}
  <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100">
      <h2 class="font-semibold text-slate-800">Matriks Izin</h2>
      <p class="text-xs text-slate-400 mt-0.5">Centang menunjukkan role memiliki izin tersebut.</p>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-500 text-left">
          <tr>
            <th class="px-5 py-3 font-medium w-48">Izin</th>
            @foreach ($roles as $role)
              <th class="px-4 py-3 font-medium text-center">{{ $role->label }}</th>
            @endforeach
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @foreach ($permissions as $group => $groupPermissions)
            <tr class="bg-slate-50">
              <td colspan="{{ $roles->count() + 1 }}" class="px-5 py-2 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                {{ $group }}
              </td>
            </tr>
            @foreach ($groupPermissions as $permission)
              <tr class="hover:bg-slate-50">
                <td class="px-5 py-3 text-slate-700">{{ $permission->label }}</td>
                @foreach ($roles as $role)
                  <td class="px-4 py-3 text-center">
                    @if ($role->permissions->contains('id', $permission->id))
                      <svg class="w-5 h-5 text-green-500 mx-auto" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    @else
                      <span class="block w-4 h-0.5 bg-slate-200 mx-auto rounded"></span>
                    @endif
                  </td>
                @endforeach
              </tr>
            @endforeach
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

@endsection

{{-- ── Modal: Tambah Role ───────────────────────────────────────────── --}}
@push('modals')

<div id="modalCreate" class="modal-backdrop hidden fixed inset-0 bg-black/40 z-50 items-center justify-center p-4">
  <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
      <h3 class="font-semibold text-slate-800">Tambah Role Baru</h3>
      <button onclick="closeModal('modalCreate')" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>
    <form method="POST" action="{{ route('admin.roles.store') }}" class="flex flex-col flex-1 overflow-hidden">
      @csrf
      <div class="px-6 py-5 space-y-5 overflow-y-auto flex-1">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Role <span class="text-red-500">*</span></label>
            <input type="text" name="name" placeholder="cth: kepala-panitia"
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent"
                   pattern="[a-z0-9\-]+" title="Huruf kecil, angka, dan tanda hubung saja" required />
            <p class="text-xs text-slate-400 mt-1">Huruf kecil, angka, tanda hubung. Tidak bisa diubah.</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Label <span class="text-red-500">*</span></label>
            <input type="text" name="label" placeholder="cth: Kepala Panitia"
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" required />
          </div>
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">Deskripsi</label>
          <input type="text" name="description" placeholder="Jelaskan fungsi role ini..."
                 class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
        </div>
        <div>
          <div class="flex items-center justify-between mb-3">
            <label class="text-sm font-medium text-slate-700">Izin Akses</label>
            <div class="flex gap-3 text-xs">
              <button type="button" onclick="checkAll('modalCreate', true)" class="text-brand-600 hover:underline">Pilih Semua</button>
              <button type="button" onclick="checkAll('modalCreate', false)" class="text-slate-400 hover:underline">Hapus Semua</button>
            </div>
          </div>
          @include('admin.roles._permission_checkboxes', ['permissions' => $permissions, 'selected' => []])
        </div>
      </div>
      <div class="px-6 py-4 border-t border-slate-100 flex justify-end gap-3">
        <button type="button" onclick="closeModal('modalCreate')"
                class="px-4 py-2 text-sm text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-50 transition">Batal</button>
        <button type="submit"
                class="px-5 py-2 text-sm font-medium text-white bg-brand-600 hover:bg-brand-700 rounded-lg transition">Simpan Role</button>
      </div>
    </form>
  </div>
</div>

{{-- ── Modal: Atur Izin ─────────────────────────────────────────────── --}}
<div id="modalPermissions" class="modal-backdrop hidden fixed inset-0 bg-black/40 z-50 items-center justify-center p-4">
  <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
      <div>
        <h3 class="font-semibold text-slate-800">Atur Izin Akses</h3>
        <p id="permModalSubtitle" class="text-xs text-slate-400 mt-0.5"></p>
      </div>
      <button onclick="closeModal('modalPermissions')" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>
    <form id="formPermissions" method="POST" action="" class="flex flex-col flex-1 overflow-hidden">
      @csrf @method('PUT')
      {{-- Label & deskripsi tersembunyi agar tidak hilang saat update --}}
      <input type="hidden" name="label" id="permHiddenLabel" />
      <input type="hidden" name="description" id="permHiddenDesc" />

      <div class="px-6 py-4 flex items-center justify-between border-b border-slate-50">
        <p class="text-sm text-slate-500">Centang izin yang ingin diberikan ke role ini.</p>
        <div class="flex gap-3 text-xs">
          <button type="button" onclick="checkAll('modalPermissions', true)" class="text-brand-600 hover:underline font-medium">Pilih Semua</button>
          <button type="button" onclick="checkAll('modalPermissions', false)" class="text-slate-400 hover:underline">Hapus Semua</button>
        </div>
      </div>

      <div class="px-6 py-5 overflow-y-auto flex-1">
        @include('admin.roles._permission_checkboxes', ['permissions' => $permissions, 'selected' => [], 'prefix' => 'perm'])
      </div>

      <div class="px-6 py-4 border-t border-slate-100 flex justify-end gap-3">
        <button type="button" onclick="closeModal('modalPermissions')"
                class="px-4 py-2 text-sm text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-50 transition">Batal</button>
        <button type="submit"
                class="px-5 py-2 text-sm font-medium text-white bg-brand-600 hover:bg-brand-700 rounded-lg transition flex items-center gap-2">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
          Simpan Izin
        </button>
      </div>
    </form>
  </div>
</div>

{{-- ── Modal: Edit Info Role ────────────────────────────────────────── --}}
<div id="modalEditInfo" class="modal-backdrop hidden fixed inset-0 bg-black/40 z-50 items-center justify-center p-4">
  <div class="bg-white rounded-2xl shadow-xl w-full max-w-md flex flex-col">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
      <h3 class="font-semibold text-slate-800">Edit Info Role</h3>
      <button onclick="closeModal('modalEditInfo')" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>
    <form id="formEditInfo" method="POST" action="" class="flex flex-col">
      @csrf @method('PUT')
      {{-- Kirim ulang permissions yang sudah ada agar tidak terhapus --}}
      <div id="hiddenPermissions"></div>
      <div class="px-6 py-5 space-y-4">
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">Label <span class="text-red-500">*</span></label>
          <input type="text" name="label" id="infoLabel"
                 class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" required />
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">Deskripsi</label>
          <input type="text" name="description" id="infoDesc"
                 class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
        </div>
      </div>
      <div class="px-6 py-4 border-t border-slate-100 flex justify-end gap-3">
        <button type="button" onclick="closeModal('modalEditInfo')"
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

  // Buka modal Atur Izin
  function openEdit(id, label, description, permissionIds) {
    document.getElementById('formPermissions').action = '/admin/roles/' + id;
    document.getElementById('permModalSubtitle').textContent = 'Role: ' + label;
    document.getElementById('permHiddenLabel').value = label;
    document.getElementById('permHiddenDesc').value  = description ?? '';

    document.querySelectorAll('#modalPermissions input[type="checkbox"]').forEach(cb => {
      cb.checked = permissionIds.includes(parseInt(cb.value));
      // sync indeterminate state on group masters
      cb.dispatchEvent(new Event('change'));
    });

    openModal('modalPermissions');
  }

  // Buka modal Edit Info (tanpa mengubah permissions)
  function openEditInfo(id, label, description, permissionIds) {
    document.getElementById('formEditInfo').action = '/admin/roles/' + id;
    document.getElementById('infoLabel').value = label;
    document.getElementById('infoDesc').value  = description ?? '';

    // Sertakan permissions yang sudah ada sebagai hidden inputs
    const container = document.getElementById('hiddenPermissions');
    container.innerHTML = '';
    (permissionIds || []).forEach(pid => {
      const inp = document.createElement('input');
      inp.type  = 'hidden';
      inp.name  = 'permissions[]';
      inp.value = pid;
      container.appendChild(inp);
    });

    openModal('modalEditInfo');
  }

  function checkAll(modalId, checked) {
    document.querySelectorAll('#' + modalId + ' input[type="checkbox"]').forEach(cb => {
      cb.checked = checked;
    });
  }

  const flash = document.getElementById('flashMsg');
  if (flash) setTimeout(() => flash.remove(), 4000);
</script>
@endpush
