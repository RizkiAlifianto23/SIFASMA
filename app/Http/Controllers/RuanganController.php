<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ruangan; // Import model Ruangan
use App\Models\Lantai;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Log; // Import model Log

class RuanganController extends Controller
{
    public function index()
    {
        // Ambil data ruangan dari model Ruangan
        $ruangan = Ruangan::with(['lantai'])->get();
        return view('ruangan', compact('ruangan'));
    }
    public function store(Request $request)
    {
        // Validasi manual agar bisa pakai error bag "create"
        $validator = Validator::make($request->all(), [
            'kode_ruangan' => [
                'required',
                'string',
                'max:255',
                'unique:ruangan,kode_ruangan',
                'regex:/^[A-Z0-9]+$/', // hanya huruf kapital dan angka
            ],
            'id_lantai' => 'required|exists:lantai,id',
            'nama_ruangan' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->route('ruangan')
                ->withErrors($validator, 'create')
                ->withInput();
        }

        Ruangan::create([
            'kode_ruangan' => strtoupper($request->kode_ruangan),
            'nama_ruangan' => $request->nama_ruangan,
            'id_lantai' => $request->id_lantai, // ID gedung yang terkait
            'status' => 'Active', // default aktif
            'created_by' => auth()->id(), // ID user yang membuat
            'updated_by' => auth()->id(), // ID user yang mengubah
        ]);
          // Tambahkan log setelah berhasil update user
        Log::create([
            'action' => 'create ruangan',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('ruangan')->with('success', 'Ruangan berhasil ditambahkan.');
    }

    /**
     * Update ruangan berdasarkan ID
     */
    public function update(Request $request, $id)
    {
        // Validasi manual agar bisa pakai error bag "edit"
        $validator = Validator::make($request->all(), [
            'nama_ruangan' => [
                'required',
                'string',
                'max:255',
            ],
            'id_lantai' => 'required|exists:lantai,id',
            'status' => ['required', Rule::in(['Inactive', 'Active'])], // tambahkan validasi status
        ]);

        if ($validator->fails()) {
            return redirect()->route('ruangan')
                ->withErrors($validator, 'edit')
                ->withInput()
                ->with('edit_id', $id);
        }

        $ruangan = Ruangan::findOrFail($id);

        $ruangan->update([
            'nama_ruangan' => $request->nama_ruangan,
            'status' => $request->status, // simpan status
            'id_lantai' => $request->id_lantai, // ID gedung yang terkait
            'updated_by' => auth()->id(), // ID user yang mengubah
        ]);
          // Tambahkan log setelah berhasil update user
        Log::create([
            'action' => 'update ruangan',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('ruangan')->with('success', 'Ruangan berhasil diperbarui.');
    }
}
