<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;
use PDF; // Assuming you are using a package like barryvdh/laravel-domPDF
use Illuminate\Support\Facades\Auth;

class PDFController extends Controller
{
    public function generatePDF($id)
    {
        $user = auth()->user();

        // Ambil data laporan beserta relasinya
        $laporan = Laporan::with([
            'fasilitas:id,nama_fasilitas',
            'pelapor:id,name',
            'teknisi:id,name',
            'creator:id,name',
            'approver:id,name',
            'rejector:id,name',
            'processor:id,name',
            'finisher:id,name',
        ])->findOrFail($id);

        // Render PDF dari view
        $pdf = Pdf::loadView('pdf.templatePDF', compact('laporan', 'user'));

        // Download PDF
        return $pdf->download('laporan-' . $laporan->id . '.pdf');
    }
}
