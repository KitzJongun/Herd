@extends('layouts.admin')

@section('page_title', 'Daftar Tiket')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Filter & Ekspor</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.tickets.index') }}" method="GET" id="filterForm">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Pencarian</label>
                        <input type="text" name="search" class="form-control" placeholder="No Tiket, Nama, Email, Judul..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="">Semua Status</option>
                            <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Open</option>
                            <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Prioritas</label>
                        <select name="priority" class="form-control">
                            <option value="">Semua Prioritas</option>
                            <option value="Rendah" {{ request('priority') == 'Rendah' ? 'selected' : '' }}>Rendah</option>
                            <option value="Sedang" {{ request('priority') == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                            <option value="Tinggi" {{ request('priority') == 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="category_id" class="form-control">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Dari Tanggal</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Sampai Tanggal</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                </div>
                <div class="col-md-10 d-flex align-items-end mb-3">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('admin.tickets.index') }}" class="btn btn-secondary mr-2">
                        <i class="fas fa-undo"></i> Reset
                    </a>
                    <button type="button" onclick="exportData()" class="btn btn-success">
                        <i class="fas fa-file-export"></i> Export CSV
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Daftar Tiket</h3>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>No Tiket</th>
                    <th>Judul</th>
                    <th>Klien</th>
                    <th>Kategori</th>
                    <th>Status</th>
                    <th>Prioritas</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->ticket_number }}</td>
                    <td>{{ Str::limit($ticket->title, 30) }}</td>
                    <td>{{ $ticket->client_name }}</td>
                    <td>{{ $ticket->category ? $ticket->category->name : '-' }}</td>
                    <td>
                        @if($ticket->status == 'Open')
                            <span class="badge badge-secondary">{{ $ticket->status }}</span>
                        @elseif($ticket->status == 'In Progress')
                            <span class="badge badge-warning">{{ $ticket->status }}</span>
                        @else
                            <span class="badge badge-success">{{ $ticket->status }}</span>
                        @endif
                    </td>
                    <td>
                        @if($ticket->priority)
                            @if($ticket->priority == 'Tinggi')
                                <span class="badge badge-danger">Tinggi</span>
                            @elseif($ticket->priority == 'Sedang')
                                <span class="badge badge-warning">Sedang</span>
                            @else
                                <span class="badge badge-info">Rendah</span>
                            @endif
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $ticket->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.tickets.show', $ticket) }}" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i> Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Belum ada tiket yang sesuai filter</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer clearfix">
        {{ $tickets->links() }}
    </div>
</div>

@section('scripts')
<script>
    function exportData() {
        const form = document.getElementById('filterForm');
        const params = new URLSearchParams(new FormData(form)).toString();
        window.location.href = "{{ route('admin.tickets.export') }}?" + params;
    }
</script>
@endsection
@endsection
