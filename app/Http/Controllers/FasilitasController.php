<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Log; // Import model Log

class FasilitasController extends Controller
{
    public function index()
    {
        // Ambil data ruangan dari model Ruangan
        $fasilitas = Fasilitas::with(['ruangan'])->get();
        return view('fasilitas', compact('fasilitas'));
    }
    public function store(Request $request)
    {
        // Validasi manual agar bisa pakai error bag "create"
        $validator = Validator::make($request->all(), [
            'kode_fasilitas' => [
                'required',
                'string',
                'max:255',
                'unique:fasilitas,kode_fasilitas',
                'regex:/^[A-Z0-9]+$/', // hanya huruf kapital dan angka
            ],
            'id_ruangan' => 'required|exists:ruangan,id',
            'nama_fasilitas' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:500', // Keterangan opsional

        ]);

        if ($validator->fails()) {
            return redirect()->route('fasilitas')
                ->withErrors($validator, 'create')
                ->withInput();
        }

        Fasilitas::create([
            'kode_fasilitas' => strtoupper($request->kode_fasilitas),
            'nama_fasilitas' => $request->nama_fasilitas,
            'keterangan' => $request->keterangan, // Simpan keterangan
            'id_ruangan' => $request->id_ruangan, // ID ruangan yang terkait
            'status' => 'Active', // default aktif
            'created_by' => auth()->id(), // ID user yang membuat
            'updated_by' => auth()->id(), // ID user yang mengubah
        ]);
        Log::create([
            'action' => 'create fasilitas',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('fasilitas')->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    /**
     * Update ruangan berdasarkan ID
     */
    public function update(Request $request, $id)
    {
        // Validasi manual agar bisa pakai error bag "edit"
        $validator = Validator::make($request->all(), [
            'nama_fasilitas' => [
                'required',
                'string',
                'max:255',
            ],
            'id_ruangan' => 'required|exists:ruangan,id',
            'keterangan' => 'nullable|string|max:500', // Keterangan opsional
            'status' => ['required', Rule::in(['Inactive', 'Active'])], // tambahkan validasi status
        ]);

        if ($validator->fails()) {
            return redirect()->route('fasilitas')
                ->withErrors($validator, 'edit')
                ->withInput()
                ->with('edit_id', $id);
        }

        $fasilitas = Fasilitas::findOrFail($id);

        $fasilitas->update([
            'nama_fasilitas' => $request->nama_fasilitas,
            'status' => $request->status, // simpan status
            'id_ruangan' => $request->id_ruangan, // ID ruangan yang terkait
            'keterangan' => $request->keterangan, // Simpan keterangan
            'updated_by' => auth()->id(), // ID user yang mengubah
        ]);

        Log::create([
            'action' => 'update fasilitas',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('fasilitas')->with('success', 'Fasilitas berhasil diperbarui.');
    }
}
