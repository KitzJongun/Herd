@extends('layouts.public')

@section('title', 'Detail Tiket ' . $ticket->ticket_number)

@section('content')
<div class="glass-card">
    <div class="d-flex-mobile-column mb-4">
        <div>
            <h1 style="color: var(--secondary); font-size: clamp(1.5rem, 5vw, 2.5rem);">{{ $ticket->ticket_number }}</h1>
            <p>{{ $ticket->created_at->format('d M Y H:i') }}</p>
        </div>
        <div class="mt-mobile-2">
            @if($ticket->status == 'Open')
                <span style="background: #e2e8f0; color: #475569; padding: 5px 15px; border-radius: 20px; font-weight: bold;">{{ $ticket->status }}</span>
            @elseif($ticket->status == 'In Progress')
                <span style="background: #fef08a; color: #854d0e; padding: 5px 15px; border-radius: 20px; font-weight: bold;">{{ $ticket->status }}</span>
            @else
                <span style="background: #bbf7d0; color: #166534; padding: 5px 15px; border-radius: 20px; font-weight: bold;">{{ $ticket->status }}</span>
            @endif
        </div>
    </div>

    <div class="table-responsive" style="background: rgba(255,255,255,0.5); padding: 1.5rem; border-radius: 10px; margin-bottom: 2rem;">
        <h3 style="margin-bottom: 10px;">{{ $ticket->title }}</h3>
        <p><strong>Kategori:</strong> {{ $ticket->category ? $ticket->category->name : '-' }}</p>
        <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 15px 0;">
        <p style="white-space: pre-line;">{{ $ticket->description }}</p>
    </div>

    <h3>Riwayat Tanggapan</h3>
    <div style="margin-top: 1rem; border-left: 2px solid var(--primary); padding-left: 20px;">
        @if($ticket->replies->where('sender', '!=', 'ai')->count() > 0)
            @foreach($ticket->replies->where('sender', '!=', 'ai') as $reply)
                <div style="margin-bottom: 1.5rem; position: relative;">
                    <div style="position: absolute; left: -29px; top: 0; width: 16px; height: 16px; border-radius: 50%; background: var(--primary);"></div>
                    
                    <p style="font-weight: bold; color: var(--secondary);">
                        👨‍💻 Admin
                        - {{ $reply->created_at->format('d M Y H:i') }}
                    </p>

                    <div style="
                        padding: 15px; 
                        border-radius: 8px; 
                        margin-top: 5px;
                        background: #ffffff;
                        border: 1px solid #e2e8f0;
                    ">
                        <p style="white-space: pre-line; color: #334155;">
                            {{ $reply->message }}
                        </p>
                    </div>
                </div>
            @endforeach
        @else
            <p style="color: var(--text-light); font-style: italic;">Belum ada tanggapan dari admin.</p>
        @endif
    </div>

    <div style="margin-top: 2rem; text-align: center;">
        <a href="{{ route('lacak') }}" class="btn btn-outline">&larr; Kembali Lacak Lain</a>
    </div>
</div>
@endsection
