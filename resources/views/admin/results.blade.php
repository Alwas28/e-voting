@extends('layouts.admin')

@section('title', 'Hasil Voting')
@section('page-title', 'Hasil Voting')

@section('content')
<div class="space-y-5">

  {{-- ══════ HEADER STATS ══════ --}}
  <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
    <div class="bg-white rounded-2xl border border-slate-200 p-4">
      <p class="text-xs text-slate-500 font-medium">Periode</p>
      <p id="hdr-period" class="mt-1 font-bold text-slate-800 text-sm truncate">—</p>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 p-4">
      <p class="text-xs text-slate-500 font-medium">Total Suara Masuk</p>
      <p id="hdr-votes" class="mt-1 font-bold text-2xl text-brand-600">—</p>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 p-4">
      <p class="text-xs text-slate-500 font-medium">Partisipasi DPT</p>
      <p id="hdr-pct" class="mt-1 font-bold text-2xl text-green-600">—%</p>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 p-4 flex items-center justify-between">
      <div>
        <p class="text-xs text-slate-500 font-medium">Diperbarui</p>
        <p id="hdr-updated" class="mt-1 font-semibold text-slate-700 text-sm">—</p>
      </div>
      <span id="liveDot" class="w-3 h-3 rounded-full bg-green-400 animate-pulse shrink-0"></span>
    </div>
  </div>

  {{-- ══════ ROW 1: Grafik Kandidat + Log Vote ══════ --}}
  <div class="grid lg:grid-cols-5 gap-4">

    {{-- Grafik Bar Horizontal --}}
    <div class="lg:col-span-3 bg-white rounded-2xl border border-slate-200 p-5 flex flex-col">
      <div class="flex items-center justify-between mb-4">
        <div>
          <h2 class="font-bold text-slate-800">Perolehan Suara</h2>
          <p class="text-xs text-slate-500 mt-0.5">Per kandidat · diperbarui otomatis</p>
        </div>
        <span class="text-xs text-brand-600 bg-brand-50 px-2 py-1 rounded-full font-medium">Real-time</span>
      </div>
      <div class="flex-1 min-h-0" style="position:relative; height:280px">
        <canvas id="chartBar"></canvas>
      </div>
    </div>

    {{-- Log Vote --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 flex flex-col" style="max-height:400px">
      <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between shrink-0">
        <div>
          <h2 class="font-bold text-slate-800">Log Suara</h2>
          <p class="text-xs text-slate-500 mt-0.5">30 suara terbaru</p>
        </div>
        <span id="logCount" class="text-xs bg-brand-50 text-brand-700 font-semibold px-2 py-0.5 rounded-full">0</span>
      </div>
      <div id="logFeed" class="overflow-y-auto flex-1 divide-y divide-slate-50">
        <div class="flex items-center justify-center h-24 text-slate-400 text-sm">Memuat...</div>
      </div>
    </div>

  </div>

  {{-- ══════ ROW 2: Grafik DPT ══════ --}}
  <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">

    {{-- Donut: Sudah vs Belum --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-5">
      <h2 class="font-bold text-slate-800 mb-1">Partisipasi DPT</h2>
      <p class="text-xs text-slate-500 mb-4">Sudah vs Belum memberikan suara</p>
      <div style="position:relative; height:200px">
        <canvas id="chartDonut"></canvas>
      </div>
      <div class="mt-4 grid grid-cols-2 gap-2 text-center">
        <div class="bg-green-50 rounded-xl p-2">
          <p id="dpt-voted" class="font-bold text-green-700 text-xl">—</p>
          <p class="text-xs text-slate-500 mt-0.5">Sudah Memilih</p>
        </div>
        <div class="bg-slate-50 rounded-xl p-2">
          <p id="dpt-not" class="font-bold text-slate-600 text-xl">—</p>
          <p class="text-xs text-slate-500 mt-0.5">Belum Memilih</p>
        </div>
      </div>
    </div>

    {{-- Bar: Suara per kandidat (mini) --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-5">
      <h2 class="font-bold text-slate-800 mb-1">Distribusi Suara</h2>
      <p class="text-xs text-slate-500 mb-4">Persentase tiap kandidat</p>
      <div style="position:relative; height:200px">
        <canvas id="chartPie"></canvas>
      </div>
    </div>

    {{-- Stat Cards --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-5 flex flex-col gap-3">
      <h2 class="font-bold text-slate-800">Ringkasan DPT</h2>

      <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
        <span class="text-sm text-slate-600">Total Terdaftar DPT</span>
        <span id="stat-total" class="font-bold text-slate-800">—</span>
      </div>
      <div class="flex items-center justify-between p-3 bg-green-50 rounded-xl">
        <span class="text-sm text-slate-600">Sudah Memilih</span>
        <span id="stat-voted" class="font-bold text-green-700">—</span>
      </div>
      <div class="flex items-center justify-between p-3 bg-amber-50 rounded-xl">
        <span class="text-sm text-slate-600">Belum Memilih</span>
        <span id="stat-not" class="font-bold text-amber-700">—</span>
      </div>

      {{-- Progress bar --}}
      <div>
        <div class="flex justify-between text-xs text-slate-500 mb-1">
          <span>Partisipasi</span>
          <span id="stat-pct" class="font-semibold text-slate-700">—%</span>
        </div>
        <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden">
          <div id="stat-bar" class="h-full bg-green-500 rounded-full transition-all duration-700" style="width:0%"></div>
        </div>
      </div>
    </div>

  </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
const DATA_URL  = "{{ route('admin.results.data') }}";
const CSRF      = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

// ── Palet warna kandidat ──────────────────────────────────────────────────────
const PALETTE = [
  '#4f46e5','#0ea5e9','#22c55e','#f59e0b','#ec4899','#8b5cf6','#14b8a6','#f97316'
];

// ── Init charts ───────────────────────────────────────────────────────────────
const chartBar = new Chart(document.getElementById('chartBar'), {
  type: 'bar',
  data: { labels: [], datasets: [{ data: [], backgroundColor: [], borderRadius: 6, barThickness: 32 }] },
  options: {
    indexAxis: 'y',
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: { display: false },
      tooltip: {
        callbacks: {
          label: ctx => ` ${ctx.raw} suara`,
        }
      }
    },
    scales: {
      x: {
        beginAtZero: true,
        ticks: { stepSize: 1, color: '#64748b', font: { size: 11 } },
        grid: { color: '#f1f5f9' },
      },
      y: {
        ticks: { color: '#1e293b', font: { size: 12, weight: '600' } },
        grid: { display: false },
      }
    }
  }
});

const chartDonut = new Chart(document.getElementById('chartDonut'), {
  type: 'doughnut',
  data: {
    labels: ['Sudah Memilih', 'Belum Memilih'],
    datasets: [{ data: [0, 0], backgroundColor: ['#22c55e','#e2e8f0'], borderWidth: 0, hoverOffset: 6 }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    cutout: '70%',
    plugins: { legend: { display: false } }
  }
});

const chartPie = new Chart(document.getElementById('chartPie'), {
  type: 'doughnut',
  data: { labels: [], datasets: [{ data: [], backgroundColor: [], borderWidth: 2, borderColor: '#fff', hoverOffset: 6 }] },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    cutout: '50%',
    plugins: {
      legend: {
        position: 'bottom',
        labels: { font: { size: 11 }, padding: 10, boxWidth: 12, color: '#475569' }
      },
      tooltip: {
        callbacks: { label: ctx => ` ${ctx.label}: ${ctx.raw}%` }
      }
    }
  }
});

// ── Fetch & render ────────────────────────────────────────────────────────────
async function fetchResults() {
  try {
    const res  = await fetch(DATA_URL, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } });
    if (!res.ok) return;
    const data = await res.json();
    renderResults(data);
  } catch (e) {
    console.error('Polling error:', e);
  }
}

function renderResults(data) {
  // Header
  document.getElementById('hdr-period').textContent  = data.period_name ?? '—';
  document.getElementById('hdr-votes').textContent   = data.total_votes ?? 0;
  document.getElementById('hdr-pct').textContent     = (data.dpt?.part_pct ?? 0) + '%';
  document.getElementById('hdr-updated').textContent = data.updated_at ?? '—';

  // Bar chart
  const labels = data.candidates.map(c => `No.${c.no}  ${c.name}`);
  const votes  = data.candidates.map(c => c.votes);
  const colors = data.candidates.map((_, i) => PALETTE[i % PALETTE.length]);

  chartBar.data.labels                       = labels;
  chartBar.data.datasets[0].data             = votes;
  chartBar.data.datasets[0].backgroundColor  = colors;
  chartBar.update('none');

  // Pie chart
  chartPie.data.labels                       = data.candidates.map(c => `No.${c.no} ${c.name}`);
  chartPie.data.datasets[0].data             = data.candidates.map(c => c.pct);
  chartPie.data.datasets[0].backgroundColor  = colors;
  chartPie.update('none');

  // Donut DPT
  const dpt = data.dpt ?? {};
  chartDonut.data.datasets[0].data = [dpt.voted ?? 0, dpt.not_voted ?? 0];
  chartDonut.update('none');

  // Stat cards
  document.getElementById('dpt-voted').textContent  = (dpt.voted ?? 0).toLocaleString('id');
  document.getElementById('dpt-not').textContent    = (dpt.not_voted ?? 0).toLocaleString('id');
  document.getElementById('stat-total').textContent = (dpt.total ?? 0).toLocaleString('id');
  document.getElementById('stat-voted').textContent = (dpt.voted ?? 0).toLocaleString('id');
  document.getElementById('stat-not').textContent   = (dpt.not_voted ?? 0).toLocaleString('id');
  document.getElementById('stat-pct').textContent   = (dpt.part_pct ?? 0) + '%';
  document.getElementById('stat-bar').style.width   = (dpt.part_pct ?? 0) + '%';

  // Log feed
  renderLog(data.recent_votes ?? [], data.candidates ?? []);
}

function renderLog(logs, candidates) {
  document.getElementById('logCount').textContent = logs.length;

  if (!logs.length) {
    document.getElementById('logFeed').innerHTML =
      '<div class="flex items-center justify-center h-24 text-slate-400 text-sm">Belum ada suara masuk</div>';
    return;
  }

  // Build color map candidateName → color
  const colorMap = {};
  candidates.forEach((c, i) => { colorMap[c.name] = PALETTE[i % PALETTE.length]; });

  document.getElementById('logFeed').innerHTML = logs.map((v, idx) => {
    const color   = colorMap[v.candidate] ?? '#4f46e5';
    const initial = v.name ? v.name.split(' ').slice(0, 2).map(w => w[0]).join('').toUpperCase() : '??';
    return `
    <div class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 transition ${idx === 0 ? 'bg-green-50/60' : ''}">
      <div class="w-9 h-9 rounded-full shrink-0 flex items-center justify-center text-xs font-bold text-white"
           style="background:${color}22; color:${color}">
        ${initial}
      </div>
      <div class="flex-1 min-w-0">
        <p class="font-semibold text-slate-800 text-sm truncate">${v.name}</p>
        <p class="text-xs text-slate-400 truncate">${v.nim ? v.nim + ' · ' : ''}${v.faculty || ''}</p>
      </div>
      <div class="text-right shrink-0">
        <span class="text-xs font-semibold px-2 py-0.5 rounded-full" style="background:${color}22;color:${color}">
          No.${v.no}
        </span>
        <p class="text-xs text-slate-400 mt-1">${v.time}</p>
      </div>
    </div>`;
  }).join('');
}

// ── Polling tiap 5 detik ─────────────────────────────────────────────────────
fetchResults();
setInterval(fetchResults, 5000);
</script>
@endpush
