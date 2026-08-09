@extends('layouts.admin')

@section('page_title', 'Detail Tiket: ' . $ticket->ticket_number)

@section('content')
<div class="row">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Tiket</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 30%">Nomor Tiket</th>
                        <td>{{ $ticket->ticket_number }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($ticket->status == 'Open')
                                <span class="badge badge-secondary">{{ $ticket->status }}</span>
                            @elseif($ticket->status == 'In Progress')
                                <span class="badge badge-warning">{{ $ticket->status }}</span>
                            @else
                                <span class="badge badge-success">{{ $ticket->status }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td>{{ $ticket->category ? $ticket->category->name : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Prioritas</th>
                        <td>{{ $ticket->priority ?? 'Belum Diatur' }}</td>
                    </tr>
                    <tr>
                        <th>Judul</th>
                        <td>{{ $ticket->title }}</td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td style="white-space: pre-line;">{{ $ticket->description }}</td>
                    </tr>
                    @if($ticket->attachment)
                    <tr>
                        <th>Lampiran</th>
                        <td><a href="{{ Storage::url($ticket->attachment) }}" target="_blank" class="btn btn-sm btn-info">Lihat Lampiran</a></td>
                    </tr>
                    @endif
                    <tr>
                        <th>Nama Klien</th>
                        <td>{{ $ticket->client_name }}</td>
                    </tr>
                    <tr>
                        <th>Email Klien</th>
                        <td>{{ $ticket->client_email }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tanggapan & Riwayat</h3>
            </div>
            <div class="card-body">
                @forelse($ticket->replies->where('sender', '!=', 'ai') as $reply)
                    <div class="callout callout-info">
                        <h5>{{ $reply->user ? $reply->user->name : 'Admin' }} <small class="text-muted float-right">{{ $reply->created_at->format('d M Y H:i') }}</small></h5>
                        <p style="white-space: pre-line;">{{ $reply->message }}</p>
                    </div>
                @empty
                    <p class="text-muted"><i>Belum ada tanggapan.</i></p>
                @endforelse

                <hr>
                
                <form action="{{ route('admin.tickets.reply', $ticket) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="mb-0">Beri Tanggapan</label>
                            @php
                                $aiReply = $ticket->replies->firstWhere('sender', 'ai');
                            @endphp
                            @if($aiReply)
                            <button type="button" id="btnGenerateAi" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-robot"></i> Generate AI
                            </button>
                            @endif
                        </div>
                        <textarea id="replyMessage" name="message" class="form-control" rows="5" required placeholder="Tulis balasan untuk klien..."></textarea>
                        @if($aiReply)
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i> Klik "Generate AI" untuk mengisi jawaban otomatis dari AI, lalu bisa Anda edit sebelum dikirim.
                        </small>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Kirim Balasan</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        @if($ticket->ai_summary || $ticket->ai_suggested_category_id || $ticket->ai_suggested_priority)
        <div class="card bg-gradient-light">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-robot text-primary"></i> Asisten AI</h3>
            </div>
            <div class="card-body">
                <p><strong>Ringkasan Masalah:</strong><br>{{ $ticket->ai_summary }}</p>
                <p><strong>Rekomendasi Kategori:</strong><br>{{ $ticket->aiSuggestedCategory ? $ticket->aiSuggestedCategory->name : '-' }}</p>
                <p><strong>Rekomendasi Prioritas:</strong><br>{{ $ticket->ai_suggested_priority }}</p>

                <form action="{{ route('admin.tickets.acceptAiSuggest', $ticket) }}" method="POST" class="mt-3">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-outline-primary btn-block">Terima Sugesti AI</button>
                </form>
            </div>
        </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Update Status & Prioritas</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.tickets.updateStatus', $ticket) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="Open" {{ $ticket->status == 'Open' ? 'selected' : '' }}>Open</option>
                            <option value="In Progress" {{ $ticket->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Closed" {{ $ticket->status == 'Closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Prioritas</label>
                        <select name="priority" class="form-control">
                            <option value="">-- Pilih --</option>
                            <option value="Rendah" {{ $ticket->priority == 'Rendah' ? 'selected' : '' }}>Rendah</option>
                            <option value="Sedang" {{ $ticket->priority == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                            <option value="Tinggi" {{ $ticket->priority == 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success btn-block">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@php
    $aiReply = $ticket->replies->firstWhere('sender', 'ai');
@endphp
@if($aiReply)
<script>
    document.getElementById('btnGenerateAi').addEventListener('click', function () {
        const aiText = @json($aiReply->message);
        const textarea = document.getElementById('replyMessage');
        textarea.value = aiText;
        textarea.focus();
        // Animasi highlight singkat
        textarea.style.transition = 'background 0.3s';
        textarea.style.background = '#fffbe6';
        setTimeout(() => { textarea.style.background = ''; }, 800);
    });
</script>
@endif
@endsection
