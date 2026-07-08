@extends('layouts.admin')

@section('title', 'Dokumen Publik')

@section('content')
<div class="space-y-6">

  {{-- Header --}}
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold text-slate-900">Dokumen Publik</h1>
      <p class="text-sm text-slate-500 mt-0.5">Kelola dokumen yang dapat diunduh oleh publik di halaman utama.</p>
    </div>
    <button onclick="document.getElementById('modal-upload').classList.remove('hidden')"
            class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-semibold px-4 py-2.5 rounded-xl transition text-sm">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
      Upload Dokumen
    </button>
  </div>

  {{-- Flash --}}
  @if(session('success'))
  <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-xl">
    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ session('success') }}
  </div>
  @endif

  {{-- Tabel Dokumen --}}
  <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
    @if($documents->isEmpty())
      <div class="text-center py-16">
        <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
          <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <p class="font-semibold text-slate-600">Belum ada dokumen</p>
        <p class="text-sm text-slate-400 mt-1">Klik tombol Upload Dokumen untuk menambahkan.</p>
      </div>
    @else
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-slate-100 bg-slate-50 text-xs font-semibold text-slate-500 uppercase tracking-wide">
            <th class="px-6 py-3 text-left">Dokumen</th>
            <th class="px-6 py-3 text-left">Deskripsi</th>
            <th class="px-4 py-3 text-center">Urutan</th>
            <th class="px-4 py-3 text-center">Status</th>
            <th class="px-4 py-3 text-center">Ukuran</th>
            <th class="px-4 py-3 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @foreach($documents as $doc)
          <tr class="hover:bg-slate-50 transition">
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                {{-- Ikon tipe file --}}
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0
                  @if($doc->icon === 'pdf') bg-red-50 text-red-500
                  @elseif($doc->icon === 'word') bg-blue-50 text-blue-500
                  @elseif($doc->icon === 'excel') bg-green-50 text-green-500
                  @elseif($doc->icon === 'ppt') bg-orange-50 text-orange-500
                  @else bg-slate-100 text-slate-400
                  @endif">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                  </svg>
                </div>
                <div class="min-w-0">
                  <p class="font-semibold text-slate-800 truncate max-w-xs">{{ $doc->title }}</p>
                  <p class="text-xs text-slate-400 truncate max-w-xs">{{ $doc->file_name }}</p>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 text-slate-500 max-w-xs">
              <p class="truncate">{{ $doc->description ?? '—' }}</p>
            </td>
            <td class="px-4 py-4 text-center text-slate-600 font-medium">{{ $doc->sort_order }}</td>
            <td class="px-4 py-4 text-center">
              <form action="{{ route('admin.documents.toggle', $doc) }}" method="POST">
                @csrf @method('PATCH')
                <button type="submit"
                  class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full transition
                    {{ $doc->is_published ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                  <span class="w-1.5 h-1.5 rounded-full {{ $doc->is_published ? 'bg-green-500' : 'bg-slate-400' }}"></span>
                  {{ $doc->is_published ? 'Publik' : 'Tersembunyi' }}
                </button>
              </form>
            </td>
            <td class="px-4 py-4 text-center text-xs text-slate-500">{{ $doc->file_size ?? '—' }}</td>
            <td class="px-4 py-4 text-center">
              <div class="flex items-center justify-center gap-2">
                <a href="{{ $doc->download_url }}" target="_blank"
                   class="p-1.5 rounded-lg text-brand-600 hover:bg-brand-50 transition" title="Unduh">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                </a>
                <form action="{{ route('admin.documents.destroy', $doc) }}" method="POST"
                      onsubmit="return confirm('Hapus dokumen ini?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="p-1.5 rounded-lg text-red-500 hover:bg-red-50 transition" title="Hapus">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    @endif
  </div>

</div>

{{-- Modal Upload --}}
<div id="modal-upload" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
      <h2 class="font-bold text-slate-900">Upload Dokumen</h2>
      <button onclick="document.getElementById('modal-upload').classList.add('hidden')"
              class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>

    <form action="{{ route('admin.documents.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
      @csrf

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Judul Dokumen <span class="text-red-500">*</span></label>
        <input type="text" name="title" required placeholder="Contoh: Petunjuk Teknis Pemilihan"
               class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Deskripsi</label>
        <textarea name="description" rows="2" placeholder="Deskripsi singkat dokumen (opsional)"
                  class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 resize-none"></textarea>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">File <span class="text-red-500">*</span></label>
        <input type="file" name="file" required accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip"
               class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700">
        <p class="text-xs text-slate-400 mt-1">PDF, Word, Excel, PPT, ZIP. Maks 20 MB.</p>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">Urutan Tampil</label>
          <input type="number" name="sort_order" value="0" min="0"
                 class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
        </div>
        <div class="flex items-end pb-1">
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="is_published" value="1" checked
                   class="w-4 h-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500">
            <span class="text-sm text-slate-700">Tampilkan ke publik</span>
          </label>
        </div>
      </div>

      <div class="flex items-center justify-end gap-3 pt-2">
        <button type="button" onclick="document.getElementById('modal-upload').classList.add('hidden')"
                class="px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100 rounded-xl transition">
          Batal
        </button>
        <button type="submit"
                class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-xl transition">
          Upload
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
