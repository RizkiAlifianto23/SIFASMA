<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - SIFASMA</title>

    <link rel="stylesheet" href="/assets/css/bootstrap.css">
    <link rel="stylesheet" href="/assets/vendors/chartjs/Chart.min.css">
    <link rel="stylesheet" href="/assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="/assets/css/app.css">
    <link rel="shortcut icon" href="/assets/images/gundarlogo.png" type="image/x-icon">
    <style>
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2eafc 100%);
            min-height: 100vh;
        }

        .welcome-card {
            margin: 60px auto;
            max-width: 600px;
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.07);
            background: #fff;
            padding: 40px 32px;
            text-align: center;
        }

        .welcome-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: #3b5998;
            margin-bottom: 18px;
        }

        .welcome-desc {
            color: #555;
            font-size: 1.1rem;
        }

        @media (max-width: 600px) {
            .welcome-card {
                padding: 24px 10px;
            }

            .welcome-title {
                font-size: 1.4rem;
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
                <div class="welcome-card">
                    <img src="/assets/images/gundarlogo.png" alt="Logo" width="80" class="mb-3">
                    <div class="welcome-title">Selamat Datang di<br>Sistem Informasi Fasilitas Manajemen</div>
                    <div class="welcome-desc">
                        Kelola aset dan fasilitas kampus dengan mudah, cepat, dan efisien.<br>
                        Silakan gunakan menu di samping untuk mulai mengelola data.
                    </div>
                </div>
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

</body>

</html>
