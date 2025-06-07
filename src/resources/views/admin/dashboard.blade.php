@extends('admin.layouts.main')

@section('content')
    <div class="container">
        <div class="row mt-3">
            <h2 class="text-center admin-title">Hệ Thống Quản Lý Quy Y</h2>
        </div>
        <div class="row">
            <div class="col-md-12 justify-content-center d-flex admin-box">
                <div class="item-box bg-u-accept m-3">
                    <div class="icon">
                        <i class="ti-user fs-2"></i>
                    </div>
                    <div class="count-number">
                        <span class="counter fs-5 fw-bold">{{ $data['total_users'] ?? 0 }}</span> Phật tử.
                    </div>
                </div>
                <div class="item-box bg-u-wait m-3">
                    <div class="icon">
                        <i class="ti-time fs-2"></i>
                    </div>
                    <div class="count-number">
                        <a href="{{route('admin.quyy.list')}}">
                            <span class="counter fs-5 fw-bold">{{ $data['total_users_pending'] ?? 0 }}</span> chờ duyệt.
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <h4 class="title-section">Thống kê quy y</h4>
            <div class="col-md-12">
                <canvas id="ambarchart3"></canvas>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>

        const chartData = @json($data['per_year']);

        const labels = chartData.map(item => item.year);
        const values = chartData.map(item => item.total);

        if ($('#ambarchart3').length) {
            const ctx = document.getElementById('ambarchart3');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Số lượng Phật tử quy y (Bar)',
                            data: values,
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1,
                            yAxisID: 'y'
                        },
                        {
                            type: 'line',
                            label: 'Số lượng Phật tử quy y (Line)',
                            data: values,
                            borderColor: 'rgba(255, 99, 132, 1)',
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            fill: false,
                            tension: 0.3,
                            yAxisID: 'y'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Số người'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Năm'
                            }
                        }
                    }
                }
            });
        }
    </script>
@endpush
