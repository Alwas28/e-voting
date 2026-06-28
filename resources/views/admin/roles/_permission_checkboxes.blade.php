@php $prefix = $prefix ?? ''; @endphp

<div class="space-y-5">
  @foreach ($permissions as $group => $groupPermissions)
    <div class="border border-slate-200 rounded-xl overflow-hidden">
      {{-- Group header with select-all for this group --}}
      <label class="flex items-center gap-3 px-4 py-3 bg-slate-50 border-b border-slate-200 cursor-pointer select-none">
        <input type="checkbox"
               class="group-master w-4 h-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500"
               data-group="{{ $prefix . $group }}"
               onchange="toggleGroup(this)" />
        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $group }}</span>
      </label>

      {{-- Individual permissions --}}
      <div class="grid grid-cols-1 sm:grid-cols-2 divide-y sm:divide-y-0 sm:divide-x divide-slate-100">
        @foreach ($groupPermissions as $permission)
          <label class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-50 cursor-pointer">
            <input type="checkbox"
                   name="permissions[]"
                   value="{{ $permission->id }}"
                   data-group-member="{{ $prefix . $group }}"
                   class="perm-cb w-4 h-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500"
                   {{ in_array($permission->id, $selected) ? 'checked' : '' }} />
            <span class="text-sm text-slate-700">{{ $permission->label }}</span>
          </label>
        @endforeach
      </div>
    </div>
  @endforeach
</div>

<script>
  function toggleGroup(master) {
    const group = master.dataset.group;
    document.querySelectorAll('[data-group-member="' + group + '"]').forEach(cb => {
      cb.checked = master.checked;
    });
  }

  // Sync master checkbox state when individual items change
  document.querySelectorAll('.perm-cb').forEach(cb => {
    cb.addEventListener('change', () => {
      const group = cb.dataset.groupMember;
      const all   = [...document.querySelectorAll('[data-group-member="' + group + '"]')];
      const master = document.querySelector('[data-group="' + group + '"]');
      if (!master) return;
      const checkedCount = all.filter(c => c.checked).length;
      master.checked       = checkedCount === all.length;
      master.indeterminate = checkedCount > 0 && checkedCount < all.length;
    });
  });
</script>
