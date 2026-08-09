@extends('layouts.admin')

@section('title', 'Triage & Detail Tiket ' . $ticket->ticket_number)

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.dashboard') }}" class="w-10 h-10 bg-[#222] border border-[#333] hover:border-[#FF0022] hover:text-[#FF0022] rounded-full flex items-center justify-center text-gray-400 transition">
                <i class='bx bx-left-arrow-alt text-xl'></i>
            </a>
            <div>
                <h2 class="text-2xl font-display font-bold text-white uppercase tracking-tight">Detail Tiket <span class="text-[#FF0022]">{{ $ticket->ticket_number }}</span></h2>
            </div>
        </div>
        
        <form action="{{ url('admin/tickets/' . $ticket->id) }}" method="POST" class="flex items-center space-x-3">
            @csrf
            @method('PATCH')
            <!-- We will build the apply suggestions functionality here if needed, but for now just show them -->
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: AI Triage & Suggestion (Improvisation AI) -->
        <div class="lg:col-span-1 border border-[#222] glass-panel rounded-2xl overflow-hidden relative group h-fit">
            <div class="absolute top-0 right-0 w-32 h-32 bg-[#FF0022]/10 rounded-full blur-[50px] pointer-events-none"></div>
            
            <div class="bg-gradient-to-r from-[#FF0022]/10 to-transparent p-5 border-b border-[#FF0022]/20 shadow-inner">
                 <h3 class="text-xs font-bold text-white uppercase tracking-widest flex items-center shadow-sm">
                    <i class='bx bx-brain text-[#FF0022] mr-2 text-xl drop-shadow-[0_0_8px_rgba(255,0,34,0.8)]'></i> Nexus AI Analysis
                </h3>
            </div>

            <div class="p-6">
                @if($ticket->ai_processed)
                    <div class="mb-5">
                        <label class="text-[10px] uppercase font-bold text-gray-500 tracking-widest block mb-2">Ringkasan Otomatis</label>
                        <div class="bg-[#111] p-3 rounded-xl border border-[#333] text-sm text-gray-300 leading-relaxed italic border-l-2 border-l-[#FF0022]">
                            "{{ $ticket->ai_summary }}"
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] uppercase font-bold text-gray-500 tracking-widest block mb-2">Sugesti Kategori</label>
                            <span class="inline-block bg-[#222] border border-[#444] text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm font-display">
                                {{ $ticket->suggestedCategory->name ?? 'Tidak dikategorikan (AI Gagal)' }}
                            </span>
                        </div>
                        <div>
                            <label class="text-[10px] uppercase font-bold text-gray-500 tracking-widest block mb-2">Sugesti Prioritas</label>
                             @if($ticket->ai_suggested_priority == 'Tinggi')
                                <span class="inline-block px-3 py-1.5 rounded-lg bg-[#FF0022]/10 border border-[#FF0022]/30 text-[#FF0022] text-xs font-bold uppercase tracking-widest">Tinggi</span>
                             @elseif($ticket->ai_suggested_priority == 'Sedang')
                                <span class="inline-block px-3 py-1.5 rounded-lg bg-orange-500/10 border border-orange-500/30 text-orange-500 text-xs font-bold uppercase tracking-widest">Sedang</span>
                             @else
                                <span class="inline-block px-3 py-1.5 rounded-lg bg-gray-500/10 border border-gray-500/30 text-gray-400 text-xs font-bold uppercase tracking-widest">Rendah</span>
                             @endif
                        </div>
                    </div>
                @else
                     <div class="text-center py-8">
                         <i class='bx bx-loader-alt animate-spin text-3xl text-[#FF0022] mb-3'></i>
                         <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">AI Sedang Menganalisis...</p>
                     </div>
                     <!-- Reload Script automatically if not processed? The controller is sync so it should always be true here if $ticket->ai_processed but if API disabled it might fall here -->
                @endif
                
                <div class="mt-6 pt-5 border-t border-[#222]">
                    <button class="w-full bg-[#222] hover:bg-[#FF0022] hover:border-[#FF0022] text-white py-2.5 rounded-lg border border-[#333] transition-all font-bold text-xs uppercase tracking-widest flex items-center justify-center">
                        <i class='bx bx-check-double mr-2'></i> Terapkan Semua Sugesti
                    </button>
                </div>
            </div>
        </div>

        <!-- Middle & Right Columns: Ticket Original Data & Reply -->
        <div class="lg:col-span-2 space-y-6">
            
            <div class="glass-panel border border-[#222] p-6 rounded-2xl relative">
                <div class="flex flex-col md:flex-row md:items-start justify-between mb-4 pb-4 border-b border-[#222]">
                    <div>
                         <span class="inline-flex items-center space-x-1 {{ $ticket->status === 'In Progress' ? 'bg-orange-500/10 text-orange-500 border-orange-500/20' : ($ticket->status === 'Closed' ? 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20' : 'bg-blue-500/10 text-blue-500 border-blue-500/20') }} text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded border mb-2">
                             <span>{{ $ticket->status }}</span>
                         </span>
                         <h2 class="text-2xl font-bold text-white font-display">{{ $ticket->title }}</h2>
                         <p class="text-xs font-medium text-gray-500 mt-1 flex items-center">
                             <i class='bx bx-user mr-1'></i> {{ $ticket->name }} ({{ $ticket->email }}) &bull; {{ $ticket->created_at->format('d M y H:i') }}
                         </p>
                    </div>
                </div>

                <div class="bg-[#111] p-5 rounded-xl border border-[#222] shadow-inner mb-4">
                    <p class="text-sm text-gray-300 leading-relaxed font-medium">{{ $ticket->description }}</p>
                </div>

                <!-- Admin Action Form -->
                <form action="#" method="POST" class="bg-[#222]/50 p-4 border border-[#333] rounded-xl">
                     @csrf
                     <!-- Need proper update ticket routing for save these fields -->
                     <h4 class="text-xs uppercase font-bold text-white mb-3">Manajemen Manual</h4>
                     <div class="grid grid-cols-3 gap-3">
                         <select class="w-full bg-[#111] border border-[#333] text-gray-300 text-xs rounded focus:border-[#FF0022] p-2 outline-none">
                            <option value="Open">Status: Open</option>
                            <option value="In Progress">Status: In Progress</option>
                            <option value="Closed">Status: Closed</option>
                         </select>
                          <select class="w-full bg-[#111] border border-[#333] text-gray-300 text-xs rounded focus:border-[#FF0022] p-2 outline-none">
                            <option>Prioritas: Rendah</option>
                            <option>Prioritas: Sedang</option>
                            <option>Prioritas: Tinggi</option>
                         </select>
                         <button type="button" class="bg-gray-700 hover:bg-gray-600 text-white font-bold px-3 py-2 rounded text-xs transition">
                             Simpan Perubahan
                         </button>
                     </div>
                </form>
            </div>

            <!-- Balasan/ Tanggapan Area -->
            <div class="glass-panel border border-[#222] rounded-2xl p-6">
                <h3 class="text-sm font-bold text-white uppercase tracking-widest flex items-center mb-6">
                    <i class='bx bx-message-rounded-dots text-gray-400 text-xl mr-2'></i> Diskusi & Balasan
                </h3>

                <form action="#" method="POST" class="mb-6 relative">
                    <!-- Textarea background matching design -->
                    <textarea rows="3" class="w-full bg-[#111] border border-[#333] rounded-xl text-sm p-4 text-white placeholder-gray-600 focus:outline-none focus:border-[#FF0022] transition-colors resize-none" placeholder="Tulis balasan untuk klien di sini..."></textarea>
                    <button type="button" class="absolute bottom-4 right-4 bg-white text-black font-bold text-xs uppercase tracking-widest px-4 py-2 rounded-lg hover:bg-gray-200 transition">Kirim Balasan</button>
                </form>

                <div class="space-y-4">
                     <!-- Replies goes here. Currently handled statically in logic, will display DB replies if they exsit -->
                     @foreach($ticket->replies as $reply)
                      <div class="flex">
                           <div class="shrink-0 mr-3">
                               <div class="w-8 h-8 rounded bg-[#333] flex items-center justify-center text-white text-xs font-bold shadow-md">
                                   AD
                               </div>
                           </div>
                           <div class="flex-1 bg-[#111] border border-[#222] rounded-xl p-3 shadow-sm relative">
                               <div class="flex justify-between items-start mb-1">
                                   <h4 class="font-bold text-xs text-[#FF0022]">{{ $reply->user->name ?? 'Admin' }}</h4>
                                   <span class="text-[10px] font-medium text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                               </div>
                               <p class="text-xs text-gray-400">{{ $reply->message }}</p>
                           </div>
                       </div>
                     @endforeach
                </div>
            </div>

        </div>
    </div>
@endsection
