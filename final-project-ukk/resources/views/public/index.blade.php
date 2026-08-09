@extends('layouts.public')

@section('title', 'Buat Tiket Baru')

@section('content')
<div class="glass-card">
    <div class="card-header">
        <h1>Sampaikan Masalah Anda</h1>
        <p>Isi formulir di bawah ini dengan detail. Asisten AI kami akan membantu mempercepat proses penanganan.</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-error">
            <ul style="margin-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="ticketForm" action="{{ route('ticket.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="client_name">Nama Lengkap *</label>
            <input type="text" id="client_name" name="client_name" class="form-control" value="{{ old('client_name') }}" required>
        </div>

        <div class="form-group">
            <label for="client_email">Alamat Email Aktif *</label>
            <input type="email" id="client_email" name="client_email" class="form-control" value="{{ old('client_email') }}" required>
        </div>

        <div class="form-group">
            <label for="title">Judul Laporan *</label>
            <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" required>
        </div>

        <div class="form-group">
            <label for="category_id">Kategori Masalah *</label>
            <select id="category_id" name="category_id" class="form-control" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="description">Deskripsi Rinci Masalah *</label>
            <textarea id="description" name="description" class="form-control" required placeholder="Ceritakan sedetail mungkin...">{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label for="attachment">Lampiran (Opsional)</label>
            <input type="file" id="attachment" name="attachment" class="form-control" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
        </div>

        <div class="form-group">
            <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
        </div>

        <button type="submit" class="btn btn-primary btn-block-fluid">
            Kirim Tiket Sekarang
        </button>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA_SITE_KEY', '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI') }}"></script>
<script>
    document.getElementById('ticketForm').addEventListener('submit', function(e) {
        e.preventDefault();
        grecaptcha.ready(function() {
          grecaptcha.execute('{{ env('RECAPTCHA_SITE_KEY', '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI') }}', {action: 'submit'}).then(function(token) {
              document.getElementById('g-recaptcha-response').value = token;
              document.getElementById('ticketForm').submit();
          });
        });
    });
</script>
@endsection
