@extends('backend.layouts.master')
@section('css')
<style>
    .dashboard-card {
        border-radius: 12px;
        padding: 24px 20px;
        color: #fff;
        min-width: 200px;
        min-height: 90px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .card-1 { background: #ff9472; }
    .card-2 { background: #00c9a7; }
    .card-3 { background: #ff6a88; }
    .card-4 { background: #00b8d9; }
    .dashboard-label { font-size: 14px; opacity: 0.85; }
    .dashboard-value { font-size: 22px; font-weight: bold; }
    .dashboard-update { font-size: 12px; opacity: 0.7; margin-top: 8px; }
</style>
@endsection
@section('content')
<div class="grid grid-cols-12 gap-6 mb-8">
    <div class="col-span-12 md:col-span-3">
        <div class="dashboard-card card-1">
            <div class="dashboard-value">{{ $total_song }}</div>
            <div class="dashboard-label">Tổng số bài hát</div>
            {{-- <div class="dashboard-update">&#128337; update: 2:15 am</div> --}}
        </div>
    </div>
    <div class="col-span-12 md:col-span-3">
        <div class="dashboard-card card-2">
            <div class="dashboard-value">{{ $total_user }}</div>
            <div class="dashboard-label">Tổng người dùng</div>
            {{-- <div class="dashboard-update">&#128337; update: 2:15 am</div> --}}
        </div>
    </div>
    <div class="col-span-12 md:col-span-3">
        <div class="dashboard-card card-3">
            <div class="dashboard-value">{{ $total_club }}</div>
            <div class="dashboard-label">Tổng câu lạc bộ</div>
            {{-- <div class="dashboard-update">&#128337; update: 2:15 am</div> --}}
        </div>
    </div>
    <div class="col-span-12 md:col-span-3">
        <div class="dashboard-card card-4">
            <div class="dashboard-value">{{ $total_post }}</div>
            <div class="dashboard-label">Tổng bài viết</div>
            {{-- <div class="dashboard-update">&#128337; update: 2:15 am</div> --}}
        </div>
    </div>
</div>
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-lg font-semibold mb-4 text-center">Biểu đồ đường (Line Chart)</h2>
    <canvas id="myLineChart" height="80"></canvas>
</div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('myLineChart').getContext('2d');
    const myLineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($array_title),
            datasets: [{
                label: 'Doanh thu',
                data: @json($array_view),
                borderColor: '#00b8d9',
                backgroundColor: 'rgba(0,184,217,0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointBackgroundColor: '#00b8d9',
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true },
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection