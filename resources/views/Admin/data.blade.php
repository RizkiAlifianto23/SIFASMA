<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Dashboard</title>

    <link rel="stylesheet" href="/assets/css/bootstrap.css">
    <link rel="stylesheet" href="/assets/vendors/chartjs/Chart.min.css">
    <link rel="stylesheet" href="/assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="/assets/css/app.css">
    <link rel="shortcut icon" href="/assets/images/gundarlogo.png" type="image/x-icon">
</head>

<body>
    <div id="app">
        @include('layouts.sidebar')
        <div id="main">
            @include('layouts.navbar')

            <div class="main-content container-fluid">
                <div class="page-title">
                    <h3>Dashboard</h3>
                    <p class="text-subtitle text-muted">A good dashboard to display your statistics</p>
                </div>
                <section class="section">
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="card">
                                            <div class="card-body">
                                                <iframe src="{{ $iframeUrl1 }}" frameborder="0" width="100%"
                                                    height="1000" allowtransparency="true">
                                                </iframe>
                                            </div>
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

</body>

</html>
