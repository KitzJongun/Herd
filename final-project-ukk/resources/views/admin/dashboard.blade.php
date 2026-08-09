@extends('layouts.admin')

@section('page_title', 'Dashboard')

@section('content')
<div class="row mb-3">
    <div class="col-12 text-right">
        <a href="{{ route('admin.export') }}" class="btn btn-success"><i class="fas fa-file-excel"></i> Export CSV</a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Statistik Tiket Masuk & Closed (Tahun Ini)</h3>
            </div>
            <div class="card-body">
                <canvas id="ticketChart" style="min-height: 250px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">10 Kategori Terbanyak</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th style="width: 40px">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topCategories as $cat)
                        <tr>
                            <td>{{ $cat->category ? $cat->category->name : 'Tanpa Kategori' }}</td>
                            <td><span class="badge bg-primary">{{ $cat->total }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center">Belum ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('ticketChart').getContext('2d');
    var ticketChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: [
                {
                    label: 'Tiket Masuk',
                    backgroundColor: 'rgba(60,141,188,0.9)',
                    borderColor: 'rgba(60,141,188,0.8)',
                    data: {!! json_encode($chartData['tickets']) !!}
                },
                {
                    label: 'Tiket Closed',
                    backgroundColor: 'rgba(210, 214, 222, 1)',
                    borderColor: 'rgba(210, 214, 222, 1)',
                    data: {!! json_encode($chartData['closed']) !!}
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endsection
