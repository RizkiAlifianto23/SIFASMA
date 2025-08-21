<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Log; // Import model Log
use App\Models\Fasilitas;
use App\Models\Lantai;
use App\Models\Ruangan;
use App\Models\Gedung; // Import model Gedung
use Illuminate\Validation\Rule; // Import Rule untuk validasi khusus
use App\Models\RoleNotification; // Import model RoleNotification


class LaporanController extends Controller
{
    // Tampilkan daftar laporan
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
            ->where('created_by', Auth::id())
            ->when($request->status, function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->gedung_filter, function ($q) use ($request) {
                $q->whereHas('fasilitas.ruangan.lantai.gedung', function ($sub) use ($request) {
                    $sub->where('id', $request->gedung_filter);
                });
            })
            ->when($request->lantai_filter, function ($q) use ($request) {
                $q->whereHas('fasilitas.ruangan.lantai', function ($sub) use ($request) {
                    $sub->where('id', $request->lantai_filter);
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

        // INI SATU-SATUNYA paginate() YANG DIPERLUKAN
        $laporan = $query->paginate(10)->withQueryString();

        return view('laporan.dashboard', compact('laporan', 'gedungs', 'lantais'));
    }
    // Simpan laporan baru
    public function store(Request $request)
    {
        // Validasi awal
        $validator = Validator::make($request->all(), [
            'id_fasilitas' => 'required|exists:fasilitas,id',
            'deskripsi_kerusakan' => 'required|string',
            'foto_kerusakan' => 'nullable|image|mimes:jpg,jpeg,png|max:5048',
        ]);

        if ($validator->fails()) {
            return redirect()->route('laporan')
                ->withErrors($validator, 'create')
                ->withInput()
                ->with('error_modal', 'create');
        }

        // Cek apakah ada laporan dengan id_fasilitas yang sama dan status pending
        $sudahAda = Laporan::where('id_fasilitas', $request->id_fasilitas)
            ->whereIn('status', ['tertunda', 'diterima', 'diproses'])
            ->exists();

        if ($sudahAda) {
            return redirect()->route('laporan')
                ->withErrors(['id_fasilitas' => 'Fasilitas ini sudah memiliki laporan yang masih aktif.'], 'create')
                ->withInput()
                ->with('error_modal', 'create');
        }

        $userId = Auth::id();
        $fotoPath = null;

        if ($request->hasFile('foto_kerusakan')) {
            $file = $request->file('foto_kerusakan');
            if ($file->isValid()) {
                $path = $file->store('image', 'public'); // simpan ke storage/app/public/image
                $fotoPath = 'storage/' . $path;          // simpan path agar bisa diakses publik
            }
        }

        $laporan = Laporan::create([
            'id_fasilitas' => $request->id_fasilitas,
            'deskripsi_kerusakan' => $request->deskripsi_kerusakan,
            'foto_kerusakan' => $fotoPath,
            'status' => 'tertunda',
            'pelapor_id' => $userId,
            'created_by' => $userId,
            'updated_by' => $userId,
        ]);

        Log::create([
            'action' => 'create laporan',
            'created_by' => $userId,
        ]);

        RoleNotification::create([
            'id_role' => 3,
            'id_laporan' => $laporan->id,
            'title' => 'Laporan Baru',
            'message' => 'OB mengirim laporan baru harus segera ditindaklanjuti.',
            'is_read' => false,
        ]);

        return redirect()->route('laporan')->with('success', 'Laporan berhasil dibuat.');
    }
    // Update laporan yang ada
    public function update(Request $request, $id)
    {
        $laporan = Laporan::findOrFail($id);

        // Validasi dan handle error untuk modal edit
        $validator = Validator::make($request->all(), [
            'id_fasilitas' => 'required|exists:fasilitas,id',
            'deskripsi_kerusakan' => 'required|string',
            'foto_kerusakan' => 'nullable|image|mimes:jpg,jpeg,png|max:5048',
        ]);

        if ($validator->fails()) {
            return redirect()->route('laporan.laporan')
                ->withErrors($validator, 'edit') // error bag: "edit"
                ->withInput()
                ->with('error_modal', 'edit')
                ->with('edit_id', $id); // kirim id agar bisa diisi ulang di form edit
        }

        if ($request->id_fasilitas != $laporan->id_fasilitas) {
            // Cek apakah ada laporan lain dengan id_fasilitas yang sama dan status aktif
            $sudahAda = Laporan::where('id_fasilitas', $request->id_fasilitas)
                ->whereIn('status', ['tertunda', 'diterima', 'diproses'])
                ->exists();

            if ($sudahAda) {
                return redirect()->route('laporan')
                    ->withErrors(['id_fasilitas' => 'Fasilitas ini sudah memiliki laporan yang masih aktif.'], 'create')
                    ->withInput()
                    ->with('error_modal', 'create');
            }
        }

        $userId = Auth::id();
        $fotoPath = $laporan->foto_kerusakan;

        // Ganti foto jika file baru diupload
        if ($request->hasFile('foto_kerusakan')) {
            $file = $request->file('foto_kerusakan');
            if ($file->isValid()) {
                $path = $file->store('image', 'public'); // simpan ke storage/app/public/image
                $fotoPath = 'storage/' . $path;          // simpan path agar bisa diakses publik
            }
        }
        // Update data
        $laporan->update([
            'id_fasilitas' => $request->id_fasilitas,
            'deskripsi_kerusakan' => $request->deskripsi_kerusakan,
            'foto_kerusakan' => $fotoPath,
            'updated_by' => $userId,
        ]);
        // Tambahkan log setelah berhasil update laporan
        Log::create([
            'action' => 'update laporan',
            'created_by' => $userId,
        ]);


        return redirect()->route('laporan')->with('success', 'Laporan berhasil diupdate.');
    }
    public function show($id)
    {
        $user = auth()->user();
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
        RoleNotification::where('id_laporan', $id)
            ->where('id_role', $user->id_role)
            ->where('is_read', false)
            ->update(['is_read' => true]);


        return view('laporan.show', compact('laporan'));
    }
    public function getLantai($gedung_id)
    {
        return response()->json(
            Lantai::where('id_gedung', $gedung_id)
                ->where('status', 'Active')
                ->get()
        );
    }
    public function getRuangan($lantai_id)
    {
        return response()->json(
            Ruangan::where('id_lantai', $lantai_id)
                ->where('status', 'Active')
                ->get()
        );
    }
    public function getFasilitas($ruangan_id)
    {
        return response()->json(
            Fasilitas::where('id_ruangan', $ruangan_id)
                ->where('status', 'Active')
                ->get()
        );
    }
    // LaporanController.php
    public function getEditData($id)
    {
        $laporan = Laporan::with('fasilitas.ruangan.lantai.gedung')->findOrFail($id);

        $gedung_id = $laporan->fasilitas->ruangan->lantai->gedung->id;
        $lantai_id = $laporan->fasilitas->ruangan->lantai->id;
        $ruangan_id = $laporan->fasilitas->ruangan->id;
        $fasilitas_id = $laporan->fasilitas->id;

        return response()->json([
            // Dropdown isi (pakai alias agar field-nya semua jadi `nama`)
            'gedungs' => Gedung::where('status', 'Active')->get(['id', 'nama_gedung as nama']),
            'lantais' => Lantai::where('id_gedung', $gedung_id)->where('status', 'Active')->get(['id', 'nama_lantai as nama']),
            'ruangans' => Ruangan::where('id_lantai', $lantai_id)->where('status', 'Active')->get(['id', 'nama_ruangan as nama']),
            'fasilitass' => Fasilitas::where('id_ruangan', $ruangan_id)->where('status', 'Active')->get(['id', 'nama_fasilitas as nama']),

            // Value yang dipilih
            'selected_gedung' => $gedung_id,
            'selected_lantai' => $lantai_id,
            'selected_ruangan' => $ruangan_id,
            'selected_fasilitas' => $fasilitas_id,

            'deskripsi_kerusakan' => $laporan->deskripsi_kerusakan,
        ]);
    }
    public function cancel($id)
    {
        $laporan = Laporan::findOrFail($id);
        $laporan->status = 'dibatalkan';
        $laporan->cancelled_at = now();
        $laporan->cancelled_by = auth()->id();
        $laporan->save();

        return redirect()->route('laporan')->with('success', 'Laporan dibatalkan.');
    }
}
