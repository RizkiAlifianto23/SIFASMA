<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - SIFASMA</title>

    <link rel="stylesheet" href="/assets/css/bootstrap.css">
    <link rel="stylesheet" href="/assets/vendors/simple-datatables/style.css">
    <link rel="stylesheet" href="/assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="/assets/css/app.css">
    <link rel="stylesheet" href="/assets/vendors/simple-datatables/style.css">
    <link rel="shortcut icon" href="/assets/images/gundarlogo.png" type="image/x-icon">
</head>

<body>
    <div id="app">
        @include('layouts.sidebar')
        <div id="main">
            @include('layouts.navbar')

            <div class="main-content container-fluid">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Laporan</h3>
                            <p class="text-subtitle text-muted">Verifikasi Laporan.</p>
                        </div>
                    </div>
                </div>

                <section class="section">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="mt-3 ms-3">Verifikasi Laporan Kerusakan</h4>
                        </div>
                        <div class="card-body">
                            <div class="card-header d-flex flex-wrap gap-2 justify-content-between align-items-center">
                                {{-- Kiri: Export Buttons --}}
                                <div class="d-flex flex-wrap gap-2">
                                    <button class="btn btn-danger" onclick="exportTableToPDF()">PDF</button>
                                    <button class="btn btn-primary" onclick="exportTableToExcel()">Excel</button>
                                </div>

                                {{-- Tengah: Filter Status + Pencarian --}}
                                <form method="GET" action="{{ url('/admin/dashboard') }}" class="w-100">
                                    <div class="row g-2 align-items-end">
                                        {{-- Filter Status --}}
                                        <div class="col-md-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select name="status" id="status" class="form-select"
                                                onchange="this.form.submit()">
                                                <option value="">-- Semua Status --</option>
                                                <option value="tertunda"
                                                    {{ request('status') == 'tertunda' ? 'selected' : '' }}>Tertunda
                                                </option>
                                                <option value="diterima"
                                                    {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima
                                                </option>
                                                <option value="ditolak"
                                                    {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak
                                                </option>
                                                <option value="dibatalkan"
                                                    {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan
                                                </option>
                                                <option value="diproses"
                                                    {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses
                                                </option>
                                                <option value="selesai"
                                                    {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai
                                                </option>
                                            </select>
                                        </div>

                                        {{-- Filter Gedung --}}
                                        <div class="col-md-3">
                                            <label for="gedung" class="form-label">Gedung</label>
                                            <select name="gedung" id="gedung" class="form-select"
                                                onchange="this.form.submit()">
                                                <option value="">-- Semua Gedung --</option>
                                                @foreach ($gedungs as $gedung)
                                                    <option value="{{ $gedung->id }}"
                                                        {{ request('gedung') == $gedung->id ? 'selected' : '' }}>
                                                        {{ $gedung->nama_gedung }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Filter Lantai --}}
                                        <div class="col-md-3">
                                            <label for="lantai" class="form-label">Lantai</label>
                                            <select name="lantai" id="lantai" class="form-select"
                                                onchange="this.form.submit()">
                                                <option value="">-- Semua Lantai --</option>
                                                @foreach ($lantais as $lantai)
                                                    <option value="{{ $lantai->id }}"
                                                        {{ request('lantai') == $lantai->id ? 'selected' : '' }}>
                                                        {{ $lantai->nama_lantai }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Pencarian --}}
                                        <div class="col-md-3">
                                            <label for="search" class="form-label">Cari Fasilitas</label>
                                            <div class="input-group">
                                                <input type="text" name="search" id="search"
                                                    value="{{ request('search') }}" class="form-control"
                                                    placeholder="Nama/Kode fasilitas">
                                                <button type="submit" class="btn btn-primary">Cari</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <table class="table table-striped" id="laporanTable">
                                <thead>
                                    <tr>
                                        <th>Fasilitas</th>
                                        <th>Deskripsi Kerusakan</th>
                                        <th>Gedung</th>
                                        <th>Lantai</th>
                                        <th>Tanggal Dibuat</th>
                                        <th>Pelapor</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($laporan as $item)
                                        <tr>
                                            <td>{{ $item->fasilitas->nama_fasilitas ?? '-' }}</td>
                                            <td>{{ $item->deskripsi_kerusakan }}</td>
                                            <td>{{ $item->fasilitas->ruangan->lantai->gedung->nama_gedung ?? '-' }}
                                            </td>
                                            <td>{{ $item->fasilitas->ruangan->lantai->nama_lantai ?? '-' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('F j, Y, g:i A') }}
                                            </td>
                                            <td>{{ $item->creator->name ?? '-' }}</td>
                                            <td>
                                                @php
                                                    $status = $item->status;
                                                    $badgeClass = match ($status) {
                                                        'tertunda' => 'bg-warning text-dark',
                                                        'diterima' => 'bg-primary',
                                                        'ditolak' => 'bg-danger',
                                                        'dibatalkan' => 'bg-secondary',
                                                        'diproses' => 'bg-info text-dark',
                                                        'selesai' => 'bg-success',
                                                        default => 'bg-light text-dark',
                                                    };
                                                @endphp
                                                <span class="badge {{ $badgeClass }}">
                                                    {{ ucfirst($status) }}
                                                </span>
                                            <td>
                                                <a href="{{ url('/admin/laporan/' . $item->id) }}"
                                                    class="btn btn-sm btn-primary me-1">
                                                    <i data-feather="eye"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-3 d-flex justify-content-between align-items-center flex-wrap">
                                {{-- Info halaman --}}
                                <div>
                                    Menampilkan {{ $laporan->firstItem() }} - {{ $laporan->lastItem() }} dari total
                                    {{ $laporan->total() }} laporan
                                </div>

                                {{-- Tombol pagination --}}
                                <div>
                                    {{ $laporan->withQueryString()->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            @include('layouts.footer')
        </div>
    </div>

    {{-- Scripts --}}
    <script src="{{ asset('assets/js/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>

    <script src="{{ asset('assets/vendors/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/js/vendors.js') }}"></script>

    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <script>
        function openEditLaporanModal(id, idFasilitas, deskripsi) {
            document.getElementById('edit_id_fasilitas').value = idFasilitas;
            document.getElementById('edit_deskripsi_kerusakan').value = deskripsi;
            document.getElementById('editLaporanForm').action = `/laporan/${id}`;
            var modal = new bootstrap.Modal(document.getElementById('editLaporanModal'));
            modal.show();
        }

        feather.replace(); // refresh feather icons
    </script>

    <script>
        function confirmApprove(id) {
            if (confirm("Apakah Anda yakin ingin menyetujui laporan ini?")) {
                window.location.href = `/admin/laporan/${id}/approve`;
            }
        }
    </script>

    {{-- pdf maker --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <script>
        function exportTableToCSV() {
            let csv = [];
            const rows = document.querySelectorAll("table tr");
            for (let row of rows) {
                let cols = row.querySelectorAll("td, th");
                let rowData = Array.from(cols)
                    .slice(0, -1) // abaikan kolom terakhir (Aksi)
                    .map(col => `"${col.innerText.trim()}"`);
                csv.push(rowData.join(","));
            }
            const csvString = csv.join("\n");
            const blob = new Blob([csvString], {
                type: "text/csv;charset=utf-8;"
            });
            saveAs(blob, "laporan.csv");
        }


        function exportTableToExcel() {
            let table = document.getElementById("laporanTable");

            // Clone table & hapus kolom terakhir
            let clone = table.cloneNode(true);
            clone.querySelectorAll("tr").forEach(tr => tr.removeChild(tr.lastElementChild));

            let wb = XLSX.utils.table_to_book(clone, {
                sheet: "Laporan"
            });
            XLSX.writeFile(wb, "laporan.xlsx");
        }

        function exportTableToPDF() {
            let doc = {
                content: []
            };
            const table = document.querySelector("#laporanTable");
            const headers = Array.from(table.querySelectorAll("thead th"))
                .slice(0, -1) // abaikan "Aksi"
                .map(th => th.innerText);

            const body = [headers];

            table.querySelectorAll("tbody tr").forEach(tr => {
                const row = Array.from(tr.querySelectorAll("td"))
                    .slice(0, -1) // abaikan "Aksi"
                    .map(td => td.innerText);
                body.push(row);
            });

            doc.content.push({
                table: {
                    headerRows: 1,
                    widths: Array(headers.length).fill("*"),
                    body: body
                }
            });

            pdfMake.createPdf(doc).download("laporan.pdf");
        }
    </script>



</body>

</html>
