@extends('admin.layout.layout')

@section('content')
    <div class="space-y-8">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">Dashboard Overview</h2>
                <p class="text-slate-500 mt-2 font-medium italic">Selamat datang kembali, {{ Auth::user()->name }}! Ringkasan data fasilitas pendidikan.</p>
            </div>
            <div class="hidden md:flex items-center gap-3">
                <span class="flex h-3 w-3 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-indigo-500"></span>
                </span>
                <p class="text-sm font-bold text-slate-600 tracking-wide uppercase italic">Sistem Aktif</p>
            </div>
        </div>

        <!-- Dashboard Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
            <!-- Total Card -->
            <div class="relative bg-gradient-to-br from-indigo-600 to-indigo-700 p-6 rounded-3xl shadow-xl shadow-indigo-200 overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative flex flex-col h-full">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-white mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <p class="text-indigo-100 text-xs font-black uppercase tracking-widest">Total Fasilitas</p>
                    <h3 class="text-3xl font-black text-white mt-1 tracking-tight">{{ $stats['total'] }}</h3>
                </div>
            </div>

            <!-- SD Card -->
            <div class="relative bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="flex flex-col h-full">
                    <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-rose-600 group-hover:text-white transition-colors duration-300 border border-rose-100 uppercase font-black text-xs">
                        SD
                    </div>
                    <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest">Sekolah Dasar</p>
                    <h3 class="text-3xl font-black text-slate-800 mt-1 tracking-tight">{{ $stats['sd'] }}</h3>
                </div>
            </div>

            <!-- SMP Card -->
            <div class="relative bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="flex flex-col h-full">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300 border border-indigo-100 uppercase font-black text-xs">
                        SMP
                    </div>
                    <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest">Menengah Pertama</p>
                    <h3 class="text-3xl font-black text-slate-800 mt-1 tracking-tight">{{ $stats['smp'] }}</h3>
                </div>
            </div>

            <!-- SMA Card -->
            <div class="relative bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="flex flex-col h-full">
                    <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-amber-600 group-hover:text-white transition-colors duration-300 border border-amber-100 uppercase font-black text-xs">
                        SMA
                    </div>
                    <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest">Menengah Atas</p>
                    <h3 class="text-3xl font-black text-slate-800 mt-1 tracking-tight">{{ $stats['sma'] }}</h3>
                </div>
            </div>

            <!-- Universitas Card -->
            <div class="relative bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="flex flex-col h-full">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300 border border-emerald-100 uppercase font-black text-xs">
                        UNIV
                    </div>
                    <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest">Perguruan Tinggi</p>
                    <h3 class="text-3xl font-black text-slate-800 mt-1 tracking-tight">{{ $stats['univ'] }}</h3>
                </div>
            </div>
        </div>

        <!-- Distribution Chart & Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Chart Card -->
            <div class="lg:col-span-7 bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h4 class="text-lg font-bold text-slate-800 tracking-tight">Sebaran Jenjang</h4>
                        <p class="text-xs text-slate-500 font-medium">Distribusi fasilitas berdasarkan tingkat pendidikan.</p>
                    </div>
                </div>
                <div id="jenjangChart" class="min-h-[300px]"></div>
            </div>

            <!-- Activity Card -->
            <div class="lg:col-span-5 bg-white p-8 rounded-3xl border border-slate-100 shadow-sm flex flex-col">
                <h4 class="text-lg font-bold text-slate-800 tracking-tight mb-6">Aktivitas Terakhir</h4>
                <div class="space-y-5 flex-1">
                    @forelse($recent as $item)
                        <div class="flex items-center gap-4 group cursor-default">
                            <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center shrink-0 group-hover:bg-indigo-50 group-hover:border-indigo-100 transition-colors">
                                @if($item->image)
                                    <img src="{{ Storage::disk('public')->url($item->image) }}" class="w-full h-full object-cover rounded-2xl">
                                @else
                                    <span class="text-[10px] font-black text-indigo-600 uppercase">{{ $item->klas }}</span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-slate-800 truncate">Menambahkan {{ $item->name }}</p>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="inline-block w-1.5 h-1.5 rounded-full {{ $item->klas == 'sd' ? 'bg-rose-400' : ($item->klas == 'smp' ? 'bg-indigo-400' : ($item->klas == 'sma' ? 'bg-amber-400' : 'bg-emerald-400')) }}"></span>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $item->klas }} • {{ $item->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="h-full flex flex-col items-center justify-center text-center py-10">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-sm text-slate-400 italic">Belum ada aktivitas baru.</p>
                        </div>
                    @endforelse
                </div>
                <a href="{{ route('admin.education-facility') }}" class="mt-6 block text-center py-3 rounded-2xl bg-slate-50 text-xs font-bold text-slate-500 hover:bg-indigo-50 hover:text-indigo-600 transition-all uppercase tracking-widest">Lihat Semua Data</a>
            </div>
        </div>
    </div>

    <!-- ApexCharts JS -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var options = {
                series: [{{ $stats['sd'] }}, {{ $stats['smp'] }}, {{ $stats['sma'] }}, {{ $stats['univ'] }}],
                chart: {
                    type: 'donut',
                    height: 320,
                    fontFamily: 'Inter, sans-serif'
                },
                labels: ['SD', 'SMP', 'SMA', 'UNIV'],
                colors: ['#f43f5e', '#6366f1', '#f59e0b', '#10b981'],
                legend: {
                    position: 'bottom',
                    fontSize: '12px',
                    fontWeight: 700,
                    markers: { radius: 6 }
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '75%',
                            labels: {
                                show: true,
                                name: { fontSize: '12px', fontWeight: 600, color: '#64748b' },
                                value: { fontSize: '24px', fontWeight: 800, color: '#1e293b' },
                                total: {
                                    show: true,
                                    label: 'TOTAL',
                                    color: '#64748b',
                                    formatter: function (w) {
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                                    }
                                }
                            }
                        }
                    }
                },
                stroke: { show: false },
                dataLabels: { enabled: false }
            };

            var chart = new ApexCharts(document.querySelector("#jenjangChart"), options);
            chart.render();
        });
    </script>
@endsection
