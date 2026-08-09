@extends('layouts.public')

@section('title', 'Lacak Pengajuan')

@section('content')
<div class="glass-card narrow-card">
    <div class="card-header">
        <h1>Lacak Tiket</h1>
        <p>Masukkan Nomor Tiket Anda untuk melihat status terbaru.</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-error">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('lacak.check') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="ticket_number">Nomor Tiket (Contoh: TIX-A9B3K2) *</label>
            <input type="text" id="ticket_number" name="ticket_number" class="form-control" required style="font-size: 1.2rem; text-align: center; font-weight: bold; text-transform: uppercase;">
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;">Lacak Sekarang</button>
    </form>
</div>
@endsection
