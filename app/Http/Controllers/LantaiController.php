<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lantai; // Assuming you have a Lantai model
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Log; // Import model Log

class LantaiController extends Controller
{
    public function index()
    {
        // Fetch all lantai records
        $lantai = Lantai::with('gedung')->get(); // Eager load 'gedung' relationship if exist
        return view('lantai', compact('lantai'));
    }
     public function store(Request $request)
    {
        // Validasi manual agar bisa pakai error bag "create"
        $validator = Validator::make($request->all(), [
            'kode_lantai' => [
                'required',
                'string',
                'max:255',
                'unique:lantai,kode_lantai',
                'regex:/^[A-Z0-9]+$/', // hanya huruf kapital dan angka
            ],
            'id_gedung' => 'required|exists:gedung,id',
            'nama_lantai' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->route('gedung')
                ->withErrors($validator, 'create')
                ->withInput();
        }

        Lantai::create([
            'kode_lantai' => strtoupper($request->kode_lantai),
            'nama_lantai' => $request->nama_lantai,
            'id_gedung' => $request->id_gedung, // ID gedung yang terkait
            'status' => 'Active', // default aktif
            'created_by' => auth()->id(), // ID user yang membuat
            'updated_by' => auth()->id(), // ID user yang mengubah
        ]);
          // Tambahkan log setelah berhasil update user
        Log::create([
            'action' => 'create lantai',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('lantai')->with('success', 'Lantai berhasil ditambahkan.');
    }

    /**
     * Update lantai berdasarkan ID
     */
    public function update(Request $request, $id)
    {
        // Validasi manual agar bisa pakai error bag "edit"
        $validator = Validator::make($request->all(), [
            'nama_lantai' => [
                'required',
                'string',
                'max:255',
            ],
            'id_gedung' => 'required|exists:gedung,id',
            'status' => ['required', Rule::in(['Inactive', 'Active'])], // tambahkan validasi status
        ]);

        if ($validator->fails()) {
            return redirect()->route('lantai')
                ->withErrors($validator, 'edit')
                ->withInput()
                ->with('edit_id', $id);
        }

        $lantai = Lantai::findOrFail($id);

        $lantai->update([
            'nama_lantai' => $request->nama_lantai,
            'status' => $request->status, // simpan status
            'id_gedung' => $request->id_gedung, // ID gedung yang terkait
            'updated_by' => auth()->id(), // ID user yang mengubah
        ]);
          // Tambahkan log setelah berhasil update user
        Log::create([
            'action' => 'update lantai',
            'created_by' => auth()->id(),
        ]);
        return redirect()->route('lantai')->with('success', 'Lantai berhasil diperbarui.');
    }
}
