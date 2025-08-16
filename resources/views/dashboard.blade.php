<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SIFASMA</title>

    <link rel="stylesheet" href="/assets/css/bootstrap.css">
    <link rel="stylesheet" href="/assets/vendors/chartjs/Chart.min.css">
    <link rel="stylesheet" href="/assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="/assets/css/app.css">
    <link rel="shortcut icon" href="/assets/images/gundarlogo.png" type="image/x-icon">

    <style>
        body {
            background: linear-gradient(120deg, #f8fafc 0%, #e2eafc 100%);
        }

        .card-statistic {
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.07);
            transition: transform 0.15s, box-shadow 0.15s;
            background: linear-gradient(120deg, #a18cd1 0%, #fbc2eb 100%);
            color: #3b3b3b;
        }

        .card-statistic:hover {
            transform: translateY(-4px) scale(1.03);
            box-shadow: 0 8px 32px rgba(161, 140, 209, 0.13);
        }

        .card-statistic .card-title {
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: 1px;
            color: #6c3cc4;
        }

        .card-statistic p {
            font-size: 2.1rem;
            font-weight: bold;
            margin-bottom: 0;
            color: #3b5998;
        }

        .card {
            border-radius: 16px !important;
            box-shadow: 0 2px 12px rgba(161, 140, 209, 0.07);
            border: none;
        }

        .card-header {
            background: transparent;
            border-bottom: none;
        }

        .badge {
            font-size: 0.95em;
            padding: 0.5em 1em;
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .table th {
            color: #6c3cc4;
            font-weight: 700;
            background: #f8f6fa;
        }

        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
        }

        .main-content {
            padding-bottom: 40px;
        }

        @media (max-width: 900px) {
            .card-statistic p {
                font-size: 1.3rem;
            }

            .card-statistic .card-title {
                font-size: 1rem;
            }
        }

        @media (max-width: 600px) {
            .main-content {
                padding: 0 2px !important;
            }

            .card,
            .card-statistic {
                border-radius: 10px !important;
            }
        }
    </style>
</head>

<body>
    <div id="app">
        @include('layouts.sidebar')
        <div id="main">
            @include('layouts.navbar')

            <div class="main-content container-fluid">
                <div class="page-title">
                    <h3>Dashboard</h3>
                    <p class="text-subtitle text-muted">Laporan Dashboard Fasilitas</p>
                </div>
                <section class="section">
                    <div class="row mb-2">

                        <div class="col-12 col-md-3">
                            <div class="card card-statistic">
                                <div class="card-body p-0">
                                    <div class="d-flex flex-column">
                                        <div class='px-3 py-3 d-flex justify-content-between'>
                                            <h3 class='card-title'>LAPORAN TERTUNDA</h3>
                                            <div class="card-right d-flex align-items-center">
                                                <p>{{ $pendingLaporan }}</p>
                                            </div>
                                        </div>
                                        <div class="chart-wrapper">
                                            <canvas id="canvas3" style="height:100px !important"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="card card-statistic">
                                <div class="card-body p-0">
                                    <div class="d-flex flex-column">
                                        <div class='px-3 py-3 d-flex justify-content-between'>
                                            <h3 class='card-title'>LAPORAN SELESAI</h3>
                                            <div class="card-right d-flex align-items-center">
                                                <p>{{ $finishedLaporan }}</p>
                                            </div>
                                        </div>
                                        <div class="chart-wrapper">
                                            <canvas id="canvas2" style="height:100px !important"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="card card-statistic">
                                <div class="card-body p-0">
                                    <div class="d-flex flex-column">
                                        <div class='px-3 py-3 d-flex justify-content-between'>
                                            <h3 class='card-title'>LAPORAN DIPROSES</h3>
                                            <div class="card-right d-flex align-items-center">
                                                <p>{{ $processedLaporan }}</p>
                                            </div>
                                        </div>
                                        <div class="chart-wrapper">
                                            <canvas id="canvas4" style="height:100px !important"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <div class="card card-statistic">
                                <div class="card-body p-0">
                                    <div class="d-flex flex-column">
                                        <div class='px-3 py-3 d-flex justify-content-between'>
                                            <h3 class='card-title'>LAPORAN DITOLAK</h3>
                                            <div class="card-right d-flex align-items-center">
                                                <p>{{ $rejectedLaporan }}</p>
                                            </div>
                                        </div>
                                        <div class="chart-wrapper">
                                            <canvas id="canvas1" style="height:100px !important"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4>Total laporan seminggu terakhir</h4>
                                            </div>
                                            <div class="card-body">
                                                <canvas id="weeklyReportChart"
                                                    style="max-height: 300px; width: 100%;"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4 class="card-title">Laporan Terbaru</h4>
                                </div>
                                <div class="card-body px-0 pb-0">
                                    <div class="table-responsive">
                                        <table class='table mb-0' id="table1">
                                            <thead>
                                                <tr>
                                                    <th>Kode Fasilita</th>
                                                    <th>Fasilitas</th>
                                                    <th>Deskripsi Kerusakan</th>
                                                    <th>Created</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($latestLaporan as $item)
                                                    <tr>
                                                        <td>{{ $item->fasilitas->kode_fasilitas }}</td>
                                                        <td>{{ $item->fasilitas->nama_fasilitas ?? '-' }}</td>
                                                        <td>{{ $item->deskripsi_kerusakan }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('F j, Y, g:i A') }}
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="badge bg-{{ $item->status === 'diproses' || $item->status === 'pending'
                                                                    ? 'warning'
                                                                    : ($item->status === 'diterima'
                                                                        ? 'success'
                                                                        : ($item->status === 'selesai'
                                                                            ? 'info'
                                                                            : 'danger')) }}">
                                                                {{ ucfirst($item->status) }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card ">
                                <div class="card-header">
                                    <h4>Total Laporan</h4>
                                </div>
                                <div class="card-body">
                                    <div id="radialBars"></div>
                                    <div class="text-center mb-5">
                                        <h1 class='text-green'>{{ $totalLaporan }}</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="card widget-todo">
                                <div class="card-body px-0 py-1">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Laporan berdasarkan status seminggu terakhir</h4>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="statusReportChart"
                                                style="max-height: 300px; width: 100%;"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            @include('layouts.footer')

        </div>
        <script src="/assets/js/feather-icons/feather.min.js"></script>
        <script src="/assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
        <script src="/assets/js/app.js"></script>

        <script src="/assets/vendors/chartjs/Chart.min.js"></script>
        <script src="/assets/vendors/apexcharts/apexcharts.min.js"></script>
        <script src="/assets/js/pages/dashboard.js"></script>

        <script src="/assets/js/main.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                fetch('/dashboard/chart-data')
                    .then(res => res.json())
                    .then(data => {
                        // Chart Total Laporan per Hari
                        const ctxWeekly = document.getElementById('weeklyReportChart').getContext('2d');
                        new Chart(ctxWeekly, {
                            type: 'bar',
                            data: {
                                labels: data.weekly.map(item => {
                                    const d = new Date(item.date);
                                    return d.toLocaleDateString('id-ID', {
                                        weekday: 'short',
                                        day: 'numeric',
                                        month: 'short'
                                    });
                                }),
                                datasets: [{
                                    label: 'Total Laporan',
                                    data: data.weekly.map(item => item.total),
                                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        precision: 0
                                    }
                                }
                            }
                        });

                        // Chart Status Laporan
                        const ctxStatus = document.getElementById('statusReportChart').getContext('2d');
                        new Chart(ctxStatus, {
                            type: 'doughnut',
                            data: {
                                labels: Object.keys(data.status),
                                datasets: [{
                                    label: 'Jumlah',
                                    data: Object.values(data.status),
                                    backgroundColor: [
                                        '#f59e0b', // tertunda
                                        '#6366f1', // diproses
                                        '#10b981', // diterima
                                        '#3b82f6', // selesai
                                        '#ef4444' // ditolak
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom'
                                    }
                                }
                            }
                        });
                    });
            });
        </script>




</body>

</html>
