<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;
use App\Models\RoleNotification;
use Illuminate\Support\Facades\DB;
class LacakController extends Controller
{
    public function index(Request $request)
    {
        $query = Laporan::with([
            'fasilitas:id,nama_fasilitas,kode_fasilitas',
            'creator:id,name,email',// ambil data user dari relasi 'creator'
            'teknisi:id,name,email',
            'approver:id,name,email',
            'rejector:id,name,email',
            'processor:id,name,email',
            'finisher:id,name,email',
            'canceller:id,name,email',

        ])
            ->select(
                'id',
                'id_fasilitas',
                'created_by',
                'deskripsi_kerusakan',
                'created_at',
                'status',
                'approved_at',
                'processed_at',
                'finished_at',
                'cancelled_at'
            )
            ->orderBy('created_at', 'desc');

        // Jika ada parameter pencarian
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;

            $query->whereHas('fasilitas', function ($q) use ($searchTerm) {
                $q->where(function ($subQuery) use ($searchTerm) {
                    $subQuery->where('nama_fasilitas', 'like', '%' . $searchTerm . '%')
                        ->orWhere('kode_fasilitas', 'like', '%' . $searchTerm . '%');
                });
            });
        }
        // Filter: Status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        $laporan = $query->get();

        return view('lacak.dashboard', compact('laporan'));
    }
    public function show($id)
    {
        $user = auth()->user();
        $laporan = Laporan::with([
            'fasilitas:id,nama_fasilitas,id_ruangan',
            'fasilitas.ruangan:id,nama_ruangan,id_lantai',
            'fasilitas.ruangan.lantai:id,nama_lantai,id_gedung',
            'fasilitas.ruangan.lantai.gedung:id,nama_gedung',
            'pelapor:id,name',
            'teknisi:id,name',
            'creator:id,name',
            'approver:id,name',
            'rejector:id,name',
            'processor:id,name',
            'finisher:id,name',
        ])->findOrFail($id);
        RoleNotification::where('id_laporan', $id)
            ->where('id_role', $user->id_role)
            ->where('is_read', false)
            ->update(['is_read' => true]);


        return view('lacak.show', compact('laporan'));
    }
}
