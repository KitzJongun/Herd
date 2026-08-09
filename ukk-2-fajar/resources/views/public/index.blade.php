@extends('layouts.public')

@section('content')
<div class="max-w-5xl mx-auto px-6" x-data="{ tab: 'submit' }">
    
    <!-- Hero Section -->
    <div class="text-center pt-8 md:pt-16 pb-20">
        
        <!-- Badge -->
        <div class="inline-flex items-center space-x-2 bg-slate-800/50 border border-slate-700/50 rounded-full px-4 py-1.5 mb-8 backdrop-blur-md">
            <i class='bx bxs-sparkles text-sky-300 text-sm'></i>
            <span class="text-xs font-semibold text-slate-300 tracking-wide uppercase">#1 AI-Powered Helpdesk</span>
        </div>

        <!-- Headline -->
        <h1 class="text-5xl md:text-[5.5rem] leading-[1.1] font-bold text-gradient mb-6 tracking-tight">
            The #1 AI agent<br>for customer support
        </h1>

        <!-- Subhead -->
        <p class="text-slate-400 text-sm md:text-lg max-w-xl mx-auto leading-relaxed mb-10 font-medium">
            Streamline your IT support with our intuitive, scalable AI resolution platform. Identify solutions before tickets are even read.
        </p>

        <!-- CTA Buttons -->
        <div class="flex items-center justify-center space-x-4 mb-20 relative z-20">
            <button @click="tab = 'submit'; document.getElementById('interactive-section').scrollIntoView({behavior: 'smooth'})" class="btn-light-blue px-8 py-3.5 rounded-full text-sm font-bold flex items-center">
                <i class='bx bxs-sparkles mr-2 text-base'></i> Buat Laporan
            </button>
            <button @click="tab = 'track'; document.getElementById('interactive-section').scrollIntoView({behavior: 'smooth'})" class="btn-dark px-8 py-3.5 rounded-full text-sm font-semibold flex items-center backdrop-blur-md">
                Lacak Tiket
            </button>
        </div>


    </div>

    <!-- Forms Section (Interactive) -->
    <div id="interactive-section" class="max-w-3xl mx-auto mt-10">
        
        <!-- Tab selector for graceful switching inside the form area if needed -->
        <div class="flex justify-center mb-8 space-x-2 p-1 bg-slate-900/50 rounded-full w-max mx-auto border border-white/5 backdrop-blur-md">
            <button @click="tab = 'submit'" :class="tab === 'submit' ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white'" class="px-6 py-2 rounded-full text-xs font-semibold transition-all">Form Laporan</button>
            <button @click="tab = 'track'" :class="tab === 'track' ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white'" class="px-6 py-2 rounded-full text-xs font-semibold transition-all">Lacak Tiket</button>
        </div>

        <!-- Submit Form -->
        <div x-show="tab === 'submit'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="form-glass rounded-3xl p-8 md:p-12" id="form-laporan">
            
            @if(session('success'))
            <div class="bg-sky-500/10 border border-sky-500/30 rounded-2xl p-6 mb-8 flex flex-col md:flex-row items-center md:items-start text-center md:text-left">
                <i class='bx bxs-check-shield text-sky-400 text-4xl mb-3 md:mb-0 md:mr-4'></i>
                <div>
                    <h4 class="text-sky-100 font-bold mb-1">Berhasil Log Laporan!</h4>
                    <p class="text-sky-200/60 text-xs mb-3">Simpan ID referensi unik ini untuk melacak status resolusi:</p>
                    <div class="inline-block bg-sky-950/50 border border-sky-400/20 text-sky-300 font-mono font-bold px-5 py-2 rounded-xl text-lg tracking-wider">
                        {{ session('success') }}
                    </div>
                </div>
            </div>
            @endif

            <form id="ticket-form" action="{{ route('public.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-2" for="name">Nama Identitas</label>
                        <input type="text" id="name" name="name" required class="w-full input-glass text-sm rounded-xl p-4 transition-all placeholder-slate-500" placeholder="Ketik secara lengkap">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-2" for="email">Saluran Komunikasi (Email)</label>
                        <input type="email" id="email" name="email" required class="w-full input-glass text-sm rounded-xl p-4 transition-all placeholder-slate-500" placeholder="user@domain.com">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2" for="category_id">Klasifikasi Masalah</label>
                    <div class="relative">
                        <select id="category_id" name="category_id" required class="w-full input-glass text-sm rounded-xl p-4 appearance-none cursor-pointer text-slate-200">
                            <option value="" disabled selected class="text-slate-500">Pilih rentang kasus yang relevan</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" class="bg-slate-900">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <i class='bx bx-chevron-down absolute right-4 top-4 text-slate-400 text-xl'></i>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2" for="title">Subjek Kendala</label>
                    <input type="text" id="title" name="title" required class="w-full input-glass text-sm rounded-xl p-4 transition-all placeholder-slate-500" placeholder="Subjek kendala singkat">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2" for="description">Uraian Masalah Rinci</label>
                    <textarea id="description" name="description" rows="4" required class="w-full input-glass text-sm rounded-xl p-4 resize-none transition-all placeholder-slate-500" placeholder="Jelaskan detail yang memicu masalah..."></textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2" for="file_upload">Dokumentasi Visual / Lampiran</label>
                    <label for="file_upload" class="flex flex-col items-center justify-center w-full h-32 border border-slate-700 border-dashed rounded-xl cursor-pointer hover:bg-slate-800/30 transition-colors input-glass">
                        <i class='bx bx-cloud-upload text-slate-400 text-3xl mb-2'></i>
                        <p class="text-sm font-semibold text-slate-300">Tap to upload file</p>
                        <p class="text-xs text-slate-500">PNG, JPG, PDF (Max. 2MB)</p>
                        <input id="file_upload" type="file" name="attachment" class="hidden" accept=".jpg,.jpeg,.png,.pdf" />
                    </label>
                </div>

                <!-- reCAPTCHA v3 UI -->
                <div class="bg-slate-900/50 border border-slate-800 p-4 rounded-xl flex items-center justify-between mt-6">
                    <div class="flex items-center space-x-3">
                        <i class='bx bx-check-shield text-sky-400 text-xl'></i>
                        <div>
                            <span class="text-[11px] font-bold text-slate-200 uppercase tracking-widest block">Proteksi Aktif</span>
                            <span class="text-[10px] text-slate-400">Dilindungi oleh Google reCAPTCHA v3</span>
                        </div>
                    </div>
                    <i class='bx bxl-google text-2xl text-slate-500'></i>
                </div>

                @if($errors->has('g-recaptcha-response'))
                    <div class="text-rose-400 text-xs font-semibold pt-2 text-center">
                        {{ $errors->first('g-recaptcha-response') }}
                    </div>
                @endif

                <button type="button" onclick="handleSubmit(event)" class="w-full btn-light-blue rounded-xl px-6 py-4 mt-4 transition-all text-sm font-bold flex justify-center items-center shadow-lg">
                     <i class='bx bx-send mr-2'></i> Transmisikan Laporan
                </button>
            </form>
        </div>

        <!-- Track Ticket Form -->
        <div x-show="tab === 'track'" style="display: none;" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="form-glass rounded-3xl p-8 md:p-14 text-center mt-8" id="lacak-tiket">
            
            <div class="w-20 h-20 bg-sky-500/10 border border-sky-400/20 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-[0_0_30px_rgba(56,189,248,0.1)]">
                <i class='bx bx-radar text-4xl text-sky-300'></i>
            </div>
            
            <h2 class="text-2xl font-bold text-slate-100 mb-2">Lacak Resolusi Tiket</h2>
            <p class="text-sm font-medium text-slate-400 mb-10 max-w-sm mx-auto">Masukkan ID Referensi TIX-XXXX untuk memantau status secara live.</p>

            <form action="{{ route('public.track') }}" method="GET" class="space-y-6 max-w-xs mx-auto">
                <div class="relative">
                    <i class='bx bx-hash absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-xl'></i>
                    <input type="text" id="ticket_number" name="ticket_number" required class="w-full input-glass text-center text-xl font-mono font-bold tracking-widest uppercase rounded-xl py-4 pl-12 pr-4 transition-all placeholder-slate-600 focus:placeholder-transparent" placeholder="TIX-XXXX" value="{{ request('ticket_number') ?? old('ticket_number') }}">
                </div>
                
                @if($errors->has('ticket_number'))
                    <div class="text-rose-400 text-xs font-semibold">
                         {{ $errors->first('ticket_number') }}
                    </div>
                @endif

                <button type="submit" class="w-full btn-light-blue rounded-xl px-6 py-4 transition-all text-sm font-bold flex justify-center items-center">
                   Mulai Pelacakan
                </button>
            </form>
        </div>

    </div>
</div>

<script>
    function handleSubmit(e) {
        e.preventDefault();
        grecaptcha.ready(function() {
            grecaptcha.execute("{{ env('RECAPTCHA_SITE_KEY') }}", {action: 'submit_ticket'}).then(function(token) {
                document.getElementById('g-recaptcha-response').value = token;
                document.getElementById('ticket-form').submit();
            });
        });
    }
</script>
@endsection
