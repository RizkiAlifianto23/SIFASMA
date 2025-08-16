<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;
use App\Models\RoleNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Gedung;
use App\Models\Lantai;

class TeknisiController extends Controller
{
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

        return view('teknisi.dashboard', compact('laporan', 'gedungs', 'lantais'));
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


        return view('teknisi.show', compact('laporan'));
    }
    public function process(Request $request, $id)
    {
        $processed_at = $request->input('processed_at');
        $description_process = $request->input('description_process');

        if (!$processed_at || !$description_process) {
            return back()->with('error', 'Tanggal proses wajib diisi.');
        }

        $laporan = Laporan::findOrFail($id);
        $laporan->status = 'diproses';
        $laporan->processed_at = $processed_at;
        $laporan->description_process = $description_process;
        $laporan->processed_by = auth()->id();
        $laporan->save();

        foreach ([1, 2] as $roleId) {
            RoleNotification::create([
                'id_role' => $roleId,
                'id_laporan' => $laporan->id,
                'title' => 'Laporan Diproses',
                'message' => 'Teknisi memproses laporan',
                'is_read' => false,
            ]);
        }

        return redirect()->route('teknisi.dashboard')->with('success', 'Laporan diproses.');
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

        // RoleNotification::create([
        //     'id_role' => 2,
        //     'id_laporan' => $laporan->id, // ← tambahkan relasi ke laporan
        //     'title' => 'Laporan ditolak',
        //     'message' => 'Teknisi menolak laporan',
        //     'is_read' => false, // ← opsional: default false jika belum dibaca
        // ]);

        return redirect()->route('teknisi.dashboard')->with('success', 'Laporan ditolak dengan alasan.');
    }

    public function vendor(Request $request, $id)
    {
        $request->validate([
            'deskripsi_vendor' => 'required|string|max:500',
        ]);

        $laporan = Laporan::findOrFail($id);
        $laporan->status = 'menunggu';
        $laporan->is_vendor = true; // Set is_vendor ke true
        $laporan->description_vendor = $request->deskripsi_vendor;
        $laporan->submission_vendor_at = now();
        $laporan->submission_vendor_by = auth()->id();
        $laporan->save();

        RoleNotification::create([
            'id_role' => 1,
            'id_laporan' => $laporan->id, // ← tambahkan relasi ke laporan
            'title' => 'Laporan Butuh Vendor',
            'message' => 'Teknisi mengajukan vendor pada laporan',
            'is_read' => false, // ← opsional: default false jika belum dibaca
        ]);

        return redirect()->route('teknisi.dashboard')->with('success', 'Laporan submission vendor dengan alasan.');
    }
    public function finish(Request $request, $id)
    {
        // Validasi input gambar
        $request->validate([
            'foto_hasil' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'finished_at' => 'required|date',
        ]);

        $laporan = Laporan::findOrFail($id);
        $fotoPath = null;
        if ($request->hasFile('foto_hasil')) {
            $file = $request->file('foto_hasil');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('perbaikan'), $filename);
            $fotoPath = 'perbaikan/' . $filename;

            // Simpan ke kolom 'foto_hasil'
            $laporan->foto_hasil = $fotoPath;
        }

        $laporan->status = 'selesai';
        $laporan->finished_at = $request->finished_at;
        $laporan->finished_by = auth()->id();
        $laporan->save();


        RoleNotification::create([
            'id_role' => 1,
            'id_laporan' => $laporan->id, // ← tambahkan relasi ke laporan
            'title' => 'Laporan Selesai Diproses',
            'message' => 'Teknisi selesai memproses laporan',
            'is_read' => false, // ← opsional: default false jika belum dibaca
        ]);
        RoleNotification::create([
            'id_role' => 2,
            'id_laporan' => $laporan->id, // ← tambahkan relasi ke laporan
            'title' => 'Laporan Selesai Diproses',
            'message' => 'Teknisi selesai memproses laporan',
            'is_read' => false, // ← opsional: default false jika belum dibaca
        ]);

        return redirect()->route('teknisi.dashboard')->with('success', 'Laporan berhasil diselesaikan.');
    }
}
