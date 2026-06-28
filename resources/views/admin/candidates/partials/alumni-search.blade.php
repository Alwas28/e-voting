{{-- Alumni Search Component --}}
{{-- Props: $selectedAlumni (Alumni|null), $exceptAlumniId (int|null) --}}
@php
  $sel    = $selectedAlumni ?? null;
  $except = $exceptAlumniId ?? ($sel?->id ?? 0);
@endphp

<div x-data="alumniSearch({{ $sel ? $sel->id : 'null' }}, {{ $sel ? json_encode(['id'=>$sel->id,'nim'=>$sel->nim,'name'=>$sel->name,'faculty'=>$sel->faculty,'department'=>$sel->department]) : 'null' }}, {{ $except }})" class="space-y-3">

  <input type="hidden" name="alumni_id" :value="selected ? selected.id : ''">

  {{-- Search box (muncul jika belum ada yang dipilih) --}}
  <div x-show="!selected">
    <div class="relative">
      <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
        </svg>
      </div>
      <input type="text" x-model="query" @input.debounce.300ms="search()"
             @focus="if(query.length >= 1) open = true"
             @keydown.escape="open = false"
             placeholder="Ketik nama atau NIM alumni..."
             class="w-full rounded-lg border border-slate-200 bg-slate-50 pl-9 pr-4 py-2.5 text-sm text-slate-800
                    focus:outline-none focus:ring-2 focus:ring-brand-200 focus:border-transparent transition">

      {{-- Dropdown hasil --}}
      <div x-show="open && results.length > 0" x-cloak
           @click.outside="open = false"
           class="absolute z-30 left-0 right-0 mt-1 bg-white rounded-xl border border-slate-200 shadow-lg overflow-hidden">
        <template x-for="a in results" :key="a.id">
          <button type="button" @click="pick(a)"
                  class="w-full flex items-center gap-3 px-4 py-3 hover:bg-brand-50 transition text-left">
            <div class="w-8 h-8 rounded-full bg-brand-100 text-brand-600 flex items-center justify-center text-xs font-bold shrink-0"
                 x-text="a.name.slice(0,2).toUpperCase()"></div>
            <div class="min-w-0">
              <p class="text-sm font-semibold text-slate-800 truncate" x-text="a.name"></p>
              <p class="text-xs text-slate-400 truncate">
                <span x-text="a.nim"></span>
                <template x-if="a.faculty"> — <span x-text="a.faculty"></span></template>
              </p>
            </div>
          </button>
        </template>
      </div>

      {{-- No result --}}
      <div x-show="open && results.length === 0 && query.length >= 2" x-cloak
           class="absolute z-30 left-0 right-0 mt-1 bg-white rounded-xl border border-slate-200 shadow-lg px-4 py-3 text-sm text-slate-400">
        Tidak ada alumni ditemukan untuk "<span x-text="query"></span>"
      </div>
    </div>
  </div>

  {{-- Kartu alumni terpilih --}}
  <div x-show="selected" x-cloak
       class="flex items-center gap-4 bg-brand-50 border border-brand-200 rounded-xl px-4 py-3">
    <div class="w-10 h-10 rounded-full bg-brand-600 text-white flex items-center justify-center font-bold text-sm shrink-0"
         x-text="selected ? selected.name.slice(0,2).toUpperCase() : ''"></div>
    <div class="flex-1 min-w-0">
      <p class="font-semibold text-slate-800 text-sm" x-text="selected?.name"></p>
      <p class="text-xs text-slate-500">
        NIM: <span x-text="selected?.nim"></span>
        <template x-if="selected?.faculty">
          <span> &bull; <span x-text="selected?.faculty"></span></span>
        </template>
        <template x-if="selected?.department">
          <span> — <span x-text="selected?.department"></span></span>
        </template>
      </p>
    </div>
    <button type="button" @click="clear()"
            class="shrink-0 text-slate-400 hover:text-red-500 transition p-1">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
      </svg>
    </button>
  </div>

</div>

@push('scripts')
<script>
function alumniSearch(initId, initData, exceptId) {
  return {
    query:   '',
    results: [],
    open:    false,
    selected: initData,
    exceptId: exceptId || 0,

    async search() {
      if (this.query.length < 1) { this.results = []; this.open = false; return; }
      const url = '{{ route('admin.candidates.search-alumni') }}?q=' + encodeURIComponent(this.query)
                + (this.exceptId ? '&except=' + this.exceptId : '');
      const res = await fetch(url);
      this.results = await res.json();
      this.open = this.results.length > 0;
    },

    pick(alumni) {
      this.selected = alumni;
      this.query    = '';
      this.results  = [];
      this.open     = false;
    },

    clear() {
      this.selected = null;
      this.query    = '';
    },
  };
}
</script>
@endpush
