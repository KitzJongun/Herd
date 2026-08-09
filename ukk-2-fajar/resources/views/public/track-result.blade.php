@extends('layouts.public')

@section('title', 'Hasil Lacak Pengajuan - ' . $mockTicket->ticket_number)

@section('content')
<div class="px-5 py-6 md:p-0 max-w-4xl mx-auto relative cursor-default">
    
    <!-- Breadcrumb & Title -->
    <div class="mb-10 flex items-center space-x-4">
        <a href="{{ route('public.index') }}" class="w-12 h-12 glass-panel hover:bg-[#FF0022] hover:border-[#FF0022] hover:shadow-[0_0_15px_rgba(255,0,34,0.4)] rounded-2xl flex items-center justify-center text-gray-400 hover:text-white transition-all group border border-[#222]">
            <i class='bx bx-left-arrow-alt text-2xl group-hover:-translate-x-1 transition-transform'></i>
        </a>
        <div>
            <h1 class="text-2xl md:text-3xl font-display font-bold text-white tracking-tight uppercase">Detail Rekaman</h1>
            <p class="text-[10px] uppercase font-bold text-gray-500 tracking-widest mt-1">Status dan Tanggapan Sistem</p>
        </div>
    </div>

    <!-- Ticket Summary Card -->
    <div class="glass-panel border-t border-l border-[#222] border-b-0 border-r-0 md:border md:border-[#222] rounded-3xl md:rounded-3xl p-6 md:p-10 mb-8 relative group/card shadow-[0_0_50px_rgba(255,0,34,0.02)] overflow-hidden">
        
        <!-- Subtle Glow top -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-[#FF0022]/10 rounded-full blur-[50px] pointer-events-none"></div>

        <div class="flex flex-col md:flex-row md:items-start justify-between mb-8 pb-8 border-b border-[#222] relative z-10">
            <div>
                <div class="flex flex-wrap items-center gap-3 mb-4">
                     <span class="bg-[#111] text-[#FF0022] border border-[#FF0022]/30 font-mono font-bold text-lg px-4 py-1 rounded-xl shadow-[0_0_10px_rgba(255,0,34,0.1)] tracking-widest">{{ $mockTicket->ticket_number }}</span>
                    <!-- Status Badge -->
                    <span class="inline-flex items-center space-x-2 {{ $mockTicket->status === 'In Progress' ? 'bg-orange-500/10 text-orange-500 border-orange-500/20 shadow-[0_0_10px_rgba(249,115,22,0.1)]' : ($mockTicket->status === 'Closed' ? 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20 shadow-[0_0_10px_rgba(16,185,129,0.1)]' : 'bg-blue-500/10 text-blue-500 border-blue-500/20 shadow-[0_0_10px_rgba(59,130,246,0.1)]') }} text-[10px] font-bold uppercase tracking-widest px-4 py-1.5 rounded-xl border">
                        @if($mockTicket->status === 'In Progress')
                             <i class='bx bx-loader-alt animate-spin text-sm'></i>
                        @elseif($mockTicket->status === 'Closed')
                             <i class='bx bx-check-shield text-sm'></i>
                        @else
                             <i class='bx bx-radio-circle-marked text-sm'></i>
                        @endif
                        <span>Status: {{ $mockTicket->status }}</span>
                    </span>
                </div>
                <h2 class="text-xl md:text-2xl font-display font-bold text-white tracking-tight uppercase">{{ $mockTicket->title }}</h2>
                <div class="mt-4 flex flex-col space-y-2">
                     <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest flex items-center">
                         <i class='bx bx-time-five mr-2 text-gray-400'></i> Dibuka pada: <span class="text-gray-300 ml-1">{{ $mockTicket->created_at->format('d M Y H:i:s') }}</span>
                     </p>
                     @if($mockTicket->ai_processed)
                         <p class="text-[10px] font-bold text-[#FF0022] uppercase tracking-widest flex items-center">
                             <i class='bx bx-radar mr-2'></i> Diinspeksi AI pada: <span class="text-gray-300 ml-1">{{ $mockTicket->updated_at->format('H:i:s') }}</span>
                         </p>
                     @endif
                </div>
            </div>
            
            <div class="mt-8 md:mt-0 pt-6 md:pt-0 border-t border-[#222] md:border-none md:text-right md:pl-8 relative z-10 flex-shrink-0">
                 <p class="text-[10px] font-bold text-gray-600 uppercase tracking-widest mb-2">Tingkat Urgensi</p>
                 <span class="inline-flex items-center space-x-2 {{ $mockTicket->priority === 'Tinggi' ? 'bg-[#FF0022]/10 text-[#FF0022] border-[#FF0022]/30 shadow-[0_0_10px_rgba(255,0,34,0.1)]' : ($mockTicket->priority === 'Rendah' ? 'bg-gray-500/10 text-gray-400 border-gray-500/30' : 'bg-orange-500/10 text-orange-500 border-orange-500/30') }} font-bold text-[10px] uppercase tracking-widest px-4 py-1.5 rounded-xl border">
                     <span>Priority: {{ $mockTicket->priority ?? 'Dalam Antrian' }}</span>
                 </span>
                 
                 <div class="mt-6">
                     <p class="text-[10px] font-bold text-gray-600 uppercase tracking-widest mb-2">Klasifikasi Model</p>
                     <p class="text-xs font-bold text-white bg-[#111] inline-block px-3 py-1.5 rounded border border-[#222] font-display uppercase tracking-wider">
                         {{ $mockTicket->category->name }}
                     </p>
                 </div>
            </div>
        </div>

        <div class="relative z-10">
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-4 flex items-center">
                 <i class='bx bx-message-square-detail mr-2 text-gray-400'></i> Transkrip Laporan Orisinil
            </p>
            <div class="bg-[#111] rounded-2xl p-6 md:p-8 border border-[#222] shadow-inner relative overflow-hidden group/text">
                <i class='bx bxs-quote-left absolute opacity-5 text-6xl text-white top-2 left-2 pointer-events-none'></i>
                <p class="text-sm md:text-base text-gray-300 leading-relaxed font-medium relative z-10">{{ $mockTicket->description }}</p>
            </div>
        </div>
        
    </div>

    <!-- Replies Section -->
    <div class="glass-panel border-t border-l border-[#222] border-b-0 border-r-0 md:border md:border-[#222] rounded-3xl md:rounded-3xl p-6 md:p-10 mb-12 shadow-[0_0_30px_rgba(0,0,0,0.5)]">
        <h3 class="text-sm font-display font-bold text-white uppercase tracking-widest flex items-center mb-8">
            <i class='bx bx-transfer-alt text-[#FF0022] text-2xl mr-3 drop-shadow-[0_0_5px_rgba(255,0,34,0.5)]'></i> Komunikasi Sistem
        </h3>

        @if(count($mockTicket->replies) > 0)
            <div class="space-y-8 relative before:absolute before:inset-0 before:ml-[1.3rem] md:before:ml-[1.4rem] before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-[#FF0022] before:via-[#333] before:to-transparent">
                @foreach($mockTicket->replies as $reply)
                    <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                        <!-- Timeline Dot -->
                        <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-[#050505] bg-[#FF0022] shadow-[0_0_10px_rgba(255,0,34,0.5)] text-white shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 relative z-10">
                            <i class='bx bxs-user-badge'></i>
                        </div>
                        
                        <!-- Reply Card -->
                        <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] p-5 rounded-2xl bg-[#111] border border-[#222] shadow-sm relative group-hover:border-[#FF0022]/30 transition-colors">
                            <div class="flex items-center justify-between mb-3 border-b border-[#222] pb-3">
                                <div>
                                    <h4 class="font-bold text-xs text-white uppercase tracking-wider">{{ $reply->user->name ?? 'Admin Support' }}</h4>
                                    <span class="inline-block mt-1 bg-[#FF0022]/10 text-[#FF0022] border border-[#FF0022]/20 text-[8px] uppercase font-black tracking-[0.2em] px-2 py-0.5 rounded shadow-sm">Authorized Operator</span>
                                </div>
                                <time class="text-[10px] font-bold text-gray-500 tracking-widest">{{ $reply->created_at->diffForHumans() }}</time>
                            </div>
                            <p class="text-xs md:text-sm text-gray-400 leading-relaxed">{{ $reply->message }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16 bg-[#111] border border-[#222] rounded-2xl relative overflow-hidden">
                 <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:24px_24px] [mask-image:radial-gradient(ellipse_50%_50%_at_50%_50%,#000_70%,transparent_100%)] point-events-none"></div>

                <div class="bg-gray-800/50 w-24 h-24 rounded-3xl flex items-center justify-center mx-auto mb-6 border border-[#222] shadow-inner relative z-10">
                    <i class='bx bx-radar text-5xl text-gray-600 animate-pulse'></i>
                </div>
                <h4 class="text-white font-display font-bold text-lg mb-2 uppercase tracking-widest relative z-10">Menunggu Tindakan</h4>
                <p class="text-[10px] uppercase font-bold text-gray-500 tracking-widest max-w-sm mx-auto leading-relaxed relative z-10">Laporan Anda saat ini sedang dalam antrian atau dalam proses evaluasi oleh agen kami. Anda akan dinotifikasi jika terdapat pembaruan.</p>
            </div>
        @endif
        
    </div>

</div>
@endsection
