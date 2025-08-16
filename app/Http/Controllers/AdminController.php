<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;
use Illuminate\Support\Facades\DB;
use Firebase\JWT\JWT;
use App\Models\RoleNotification;
use App\Models\Gedung;
use App\Models\Lantai;

class AdminController extends Controller
{
    // Tampilkan halaman dashboard admin
    public function index(Request $request)
    {
        $gedungs = Gedung::select('id', 'nama_gedung')->get();
        $lantais = Lantai::select('id', 'nama_lantai')->get();
        $query = Laporan::with([
            'fasilitas:id,nama_fasilitas,kode_fasilitas,id_ruangan',
            'fasilitas.ruangan:id,nama_ruangan,id_lantai',
            'fasilitas.ruangan.lantai:id,nama_lantai,id_gedung',
            'fasilitas.ruangan.lantai.gedung:id,nama_gedung',

            'creator:id,name,email',
            'teknisi:id,name,email',
            'approver:id,name,email',
            'rejector:id,name,email',
            'processor:id,name,email',
            'finisher:id,name,email',
            'canceller:id,name,email',
        ])
            ->when($request->status, function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->gedung, function ($q) use ($request) {
                $q->whereHas('fasilitas.ruangan.lantai.gedung', function ($sub) use ($request) {
                    $sub->where('id', $request->gedung);
                });
            })
            ->when($request->lantai, function ($q) use ($request) {
                $q->whereHas('fasilitas.ruangan.lantai', function ($sub) use ($request) {
                    $sub->where('id', $request->lantai);
                });
            })
            ->when($request->search, function ($q) use ($request) {
                $q->whereHas('fasilitas', function ($sub) use ($request) {
                    $sub->where('nama_fasilitas', 'like', '%' . $request->search . '%')
                        ->orWhere('kode_fasilitas', 'like', '%' . $request->search . '%');
                });
            })
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

        $laporan = $query->paginate(10)->withQueryString();

        return view('admin.dashboard', compact('laporan', 'gedungs', 'lantais'));
    }
    // Tampilkan halaman laporan
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


        return view('admin.show', compact('laporan'));
    }
    public function approve($id)
    {
        $laporan = Laporan::findOrFail($id);
        $laporan->status = 'diterima';
        $laporan->approved_at = now();
        $laporan->approved_by = auth()->id();
        $laporan->save();

        RoleNotification::create([
            'id_role' => 3,
            'id_laporan' => $laporan->id, // ← tambahkan relasi ke laporan
            'title' => 'Laporan Baru',
            'message' => 'Admin mererima laporan baru harus segera ditindaklanjuti.',
            'is_read' => false, // ← opsional: default false jika belum dibaca
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Laporan disetujui.');
    }
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejected_reason' => 'required|string|max:500',
        ]);

        $laporan = Laporan::findOrFail($id);
        $laporan->status = 'ditolak';
        $laporan->rejected_reason = $request->rejected_reason;
        $laporan->rejected_at = now();
        $laporan->rejected_by = auth()->id();
        $laporan->save();

        RoleNotification::create([
            'id_role' => 3,
            'id_laporan' => $laporan->id, // ← tambahkan relasi ke laporan
            'title' => 'Laporan ditolak',
            'message' => 'Admin menolak laporan',
            'is_read' => false, // ← opsional: default false jika belum dibaca
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Laporan ditolak dengan alasan.');
    }

    public function metabase()
    {
        $METABASE_SITE_URL = 'http://localhost:3000';
        $METABASE_SECRET_KEY = '2e1d26b2ec691edfdef23d4c09053ab59397e5cc7083c8dbeb3c3d9f0651f691';

        $exp = time() + (10 * 60); // 10 menit

        // Question 3
        $payload1 = [
            'resource' => ['question' => 42],
            'params' => new \stdClass(),
            'exp' => $exp,
        ];
        $token1 = JWT::encode($payload1, $METABASE_SECRET_KEY, 'HS256');
        $iframeUrl1 = $METABASE_SITE_URL . "/embed/question/" . $token1 . "#bordered=true&titled=true";

        return view('admin.data', compact(
            'iframeUrl1'
        ));

    }

}
