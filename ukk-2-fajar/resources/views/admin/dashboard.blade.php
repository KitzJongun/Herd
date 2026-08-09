@extends('layouts.admin')

@section('title', 'Admin Command Center')

@section('content')
    <!-- Dashboard Improvisations Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
        <div>
            <h2 class="text-3xl md:text-5xl font-display font-bold text-white tracking-tighter uppercase mb-2">
                Ai <span class="neon-red-text">Triage</span> System
            </h2>
            <p class="text-sm text-gray-400 max-w-lg leading-relaxed">Sistem analisis tiket otomatis menggunakan pemrosesan bahasa alami (NLP). Semua data di bawah diproses secara real-time.</p>
        </div>
        <div class="flex space-x-2">
            <button class="bg-[#222] border border-[#333] hover:border-[#38bdf8]/50 text-white px-4 py-2 rounded-lg text-sm font-semibold transition flex items-center">
                <i class='bx bx-export mr-2 text-[#38bdf8]'></i> Export CSV
            </button>
            <button class="bg-neon-red hover:bg-red-700 neon-red-glow text-white px-5 py-2 rounded-lg text-sm font-bold transition flex items-center">
                <i class='bx bx-refresh mr-2'></i> Sync Data
            </button>
        </div>
    </div>

    <!-- Stat Cards (Improvisation) -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-8">
        <div class="glass-panel p-5 rounded-2xl relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-[#38bdf8]/10 rounded-full group-hover:bg-[#38bdf8]/20 transition-all blur-md"></div>
            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Total Tiket</p>
            <h3 class="text-3xl font-display font-bold text-white">{{ $stats['total_tickets'] }}</h3>
            <div class="mt-2 text-xs font-medium text-emerald-500 flex items-center">
                <i class='bx bx-trending-up mr-1'></i> +12% dari minggu lalu
            </div>
        </div>
        <div class="glass-panel p-5 rounded-2xl relative overflow-hidden group border border-[#38bdf8]/20 neon-red-glow-subtle">
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-[#38bdf8]/20 rounded-full group-hover:scale-150 transition-all blur-md"></div>
            <p class="text-xs font-bold text-[#38bdf8] uppercase tracking-widest mb-1 shadow-sm">Tiket Open</p>
            <h3 class="text-3xl font-display font-bold text-white">{{ $stats['open_tickets'] }}</h3>
            <div class="mt-2 text-xs font-medium text-gray-400 flex items-center">
                <i class='bx bx-time-five mr-1 text-[#38bdf8]'></i> Menunggu Respon
            </div>
        </div>
        <div class="glass-panel p-5 rounded-2xl relative overflow-hidden group">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Tiket Closed</p>
            <h3 class="text-3xl font-display font-bold text-white">{{ $stats['resolved_tickets'] }}</h3>
            <div class="mt-2 text-xs font-medium text-gray-400 flex items-center">
                Tingkat Resolusi: 83%
            </div>
        </div>
        <div class="glass-panel p-5 rounded-2xl relative overflow-hidden group bg-gradient-to-br from-[#111] to-[#38bdf8]/10">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1 flex items-center">
                <i class='bx bx-brain text-[#38bdf8] mr-1'></i> AI Processed
            </p>
            <h3 class="text-3xl font-display font-bold text-white">{{ $stats['ai_assisted'] }}</h3>
            <div class="mt-2 text-xs font-medium text-emerald-500 flex items-center">
                Akurasi Prediksi: 94.2%
            </div>
        </div>
    </div>

    <!-- Main Dashboard Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        <!-- Ticket Analytics Chart -->
        <div class="lg:col-span-2 glass-panel rounded-2xl p-6 border border-[#222]">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-sm font-bold text-white uppercase tracking-widest">Volume Tiket Masuk</h3>
                    <p class="text-xs text-gray-500 mt-1">Januari - Desember (Bulanan)</p>
                </div>
                <div class="p-1 px-2 border border-[#333] rounded-md text-xs font-mono text-[#38bdf8]">2026</div>
            </div>
            
            <div class="relative h-64 w-full">
                <canvas id="trafficChart"></canvas>
            </div>
        </div>

        <!-- System & AI Status (Improvisation) -->
        <div class="glass-panel rounded-2xl p-6 border border-[#222] flex flex-col">
            <h3 class="text-sm font-bold text-white uppercase tracking-widest mb-6">Nexus Engine Status</h3>
            
            <div class="space-y-4 mb-auto">
                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-gray-400 font-medium">Model NLP (Gemini)</span>
                        <span class="text-emerald-500 font-bold">Online</span>
                    </div>
                    <div class="w-full bg-[#222] rounded-full h-1.5">
                        <div class="bg-emerald-500 h-1.5 rounded-full" style="width: 100%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-gray-400 font-medium">Beban Server Utama</span>
                        <span class="text-white font-bold">42%</span>
                    </div>
                    <div class="w-full bg-[#222] rounded-full h-1.5">
                        <div class="bg-[#38bdf8] h-1.5 rounded-full" style="width: 42%"></div>
                    </div>
                    <p class="text-[10px] text-gray-500 mt-1">Normal Operating Level</p>
                </div>
                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-gray-400 font-medium">API Rate Limit</span>
                        <span class="text-white font-bold">850/1000</span>
                    </div>
                    <div class="w-full bg-[#222] rounded-full h-1.5">
                        <div class="bg-[#38bdf8] h-1.5 rounded-full" style="width: 85%"></div>
                    </div>
                </div>
            </div>

            <div class="mt-6 p-4 rounded-xl bg-gradient-to-r from-[#38bdf8]/10 to-transparent border border-[#38bdf8]/20">
                <div class="flex items-start">
                    <i class='bx bxs-bolt-circle text-[#38bdf8] text-xl mt-0.5 mr-3 drop-shadow-[0_0_5px_rgba(56,189,248,0.8)]'></i>
                    <div>
                        <h4 class="text-xs font-bold text-white">Auto-Triage Active</h4>
                        <p class="text-[10px] text-gray-400 mt-1 leading-relaxed">AI saat ini secara otomatis memberikan ringkasan dan sugesti pada setiap tiket baru dalam < 2 detik.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- AI Triage Inbox (Improvisation) -->
    <div class="glass-panel border border-[#222] rounded-2xl overflow-hidden relative">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-[#38bdf8] to-transparent opacity-50"></div>
        
        <div class="p-6 border-b border-[#222] flex justify-between items-center">
             <div>
                <h3 class="text-base font-bold text-white uppercase tracking-widest flex items-center">
                    <i class='bx bx-radar text-[#38bdf8] mr-2 text-xl'></i> Live Triage Radar
                </h3>
                <p class="text-xs text-gray-500 mt-1">Tiket terbaru yang sedang atau telah dianalisis AI.</p>
            </div>
            <a href="#" class="text-xs font-bold text-[#38bdf8] hover:text-white transition uppercase tracking-widest hidden md:block">Lihat Semua Tiket →</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#111] border-b border-[#222]">
                        <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-widest">ID Tiket</th>
                        <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Waktu</th>
                        <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-widest min-w-[300px]">Sugesti AI (Ringkasan)</th>
                        <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Prediksi Prioritas</th>
                        <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#222]">
                    @foreach($recentTickets as $ticket)
                    <tr class="hover:bg-white/5 transition group">
                        <td class="p-4">
                            <span class="font-mono text-sm font-bold text-white block">{{ $ticket->ticket_number }}</span>
                            <span class="text-[10px] uppercase font-bold text-emerald-500 mt-1 block">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block mr-1"></span>{{ $ticket->status }}
                            </span>
                        </td>
                        <td class="p-4 text-xs font-medium text-gray-400 whitespace-nowrap">{{ $ticket->created_at->diffForHumans() }}</td>
                        <td class="p-4">
                            <p class="text-sm font-bold text-white mb-1 truncate max-w-sm" title="{{ $ticket->title }}">{{ $ticket->title }}</p>
                            <div class="flex items-start text-xs text-gray-400 bg-[#111] p-2 rounded-lg border border-[#333]">
                                <i class='bx bx-bot text-[#38bdf8] mr-2 mt-0.5'></i>
                                <span class="italic leading-snug">"{{ $ticket->ai_summary ?? 'Belum diproses AI...' }}"</span>
                            </div>
                            <div class="mt-2 text-[10px] font-bold uppercase tracking-widest text-gray-500">
                                Kategori: <span class="text-[#facc15] bg-[#facc15]/10 px-1 border border-[#facc15]/20 rounded">{{ $ticket->suggestedCategory->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="p-4">
                            @if($ticket->priority == 'Tinggi')
                                <span class="inline-flex items-center space-x-1 px-2.5 py-1 rounded bg-[#FF0022]/10 border border-[#FF0022]/30 text-[#FF0022] text-[10px] font-bold uppercase tracking-widest">
                                    <i class='bx bxs-error-circle'></i> <span>Tinggi</span>
                                </span>
                            @elseif($ticket->priority == 'Sedang')
                                <span class="inline-flex items-center space-x-1 px-2.5 py-1 rounded bg-orange-500/10 border border-orange-500/30 text-orange-500 text-[10px] font-bold uppercase tracking-widest">
                                    <i class='bx bx-error'></i> <span>Sedang</span>
                                </span>
                            @else
                                <span class="inline-flex items-center space-x-1 px-2.5 py-1 rounded bg-gray-500/10 border border-gray-500/30 text-gray-400 text-[10px] font-bold uppercase tracking-widest">
                                    <i class='bx bx-info-circle'></i> <span>Rendah</span>
                                </span>
                            @endif
                        </td>
                        <td class="p-4 text-right">
                            <a href="{{ route('admin.tickets.show', $ticket->id ?? 1) }}" class="bg-[#222] hover:bg-[#38bdf8] hover:text-white border border-[#333] hover:border-[#38bdf8] transition px-3 py-1.5 rounded text-xs font-bold text-gray-300">
                                Tinjau
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Neon Blue Theme Chart.js
        const ctx = document.getElementById('trafficChart').getContext('2d');
        
        // Gradient fill for chart
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(56, 189, 248, 0.5)'); // Neon Blue at top
        gradient.addColorStop(1, 'rgba(56, 189, 248, 0.0)'); // Transparent at bottom

        const trafficChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Tiket Masuk',
                    data: [45, 59, 80, 81, 56, 120, 100, 90, 110, 130, 95, 124], // Mock Data
                    borderColor: '#38bdf8',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#050505',
                    pointBorderColor: '#38bdf8',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4 // Sleek curves
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#111',
                        titleColor: '#fff',
                        bodyColor: '#e5e5e5',
                        borderColor: '#38bdf8',
                        borderWidth: 1,
                        padding: 10,
                        displayColors: false,
                        titleFont: { family: "'Space Grotesk', sans-serif", size: 14 },
                        bodyFont: { family: "'Inter', sans-serif", size: 13, weight: 'bold' }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#666',
                            font: { family: "'Space Grotesk', sans-serif", size: 11, weight: 'bold' }
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)',
                            drawBorder: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            color: '#666',
                            font: { family: "'Inter', sans-serif", size: 11 },
                            stepSize: 30
                        },
                        beginAtZero: true
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
            }
        });
    });
</script>
@endpush
