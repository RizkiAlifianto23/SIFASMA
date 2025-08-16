<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gedung; // Import model Gedung
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Log; // Import model Log

class GedungController extends Controller
{
    public function index()
    {
        // Ambil semua data gedung
        $gedungs = Gedung::all();
        return view('gedung', compact('gedungs'));
    }
    public function store(Request $request)
    {
        // Validasi manual agar bisa pakai error bag "create"
        $validator = Validator::make($request->all(), [
            'kode_gedung' => [
                'required',
                'string',
                'max:255',
                'unique:gedung,kode_gedung',
                'regex:/^[A-Z0-9]+$/', // hanya huruf kapital dan angka
            ],
            'nama_gedung' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->route('gedung')
                ->withErrors($validator, 'create')
                ->withInput();
        }

        Gedung::create([
            'kode_gedung' => strtoupper($request->kode_gedung),
            'nama_gedung' => $request->nama_gedung,
            'status' => 'Active', // default aktif
            'created_by' => auth()->id(), // ID user yang membuat
            'updated_by' => auth()->id(), // ID user yang mengubah
        ]);
        Log::create([
            'action' => 'create gedung',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('gedung')->with('success', 'gedung berhasil ditambahkan.');
    }

    /**
     * Update gedung berdasarkan ID
     */
    public function update(Request $request, $id)
    {
        // Validasi manual agar bisa pakai error bag "edit"
        $validator = Validator::make($request->all(), [
            'nama_gedung' => [
                'required',
                'string',
                'max:255',
            ],
            'status' => ['required', Rule::in(['Inactive', 'Active'])], // tambahkan validasi status
        ]);

        if ($validator->fails()) {
            return redirect()->route('gedung')
                ->withErrors($validator, 'edit')
                ->withInput()
                ->with('edit_id', $id);
        }

        $gedung = Gedung::findOrFail($id);

        $gedung->update([
            'nama_gedung' => $request->nama_gedung,
            'status' => $request->status, // simpan status
            'updated_by' => auth()->id(), // ID user yang mengubah
        ]);
          // Tambahkan log setelah berhasil update user
        Log::create([
            'action' => 'update gedung',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('gedung')->with('success', 'Gedung berhasil diperbarui.');
    }
}
