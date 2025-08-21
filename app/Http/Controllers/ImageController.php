<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ImageController extends Controller
{
    public function show($filename)
    {
        // Tentukan path relatif berdasarkan nama file
        // Laravel secara otomatis menyimpan file di sub-folder yang kita berikan,
        // jadi kita perlu memeriksa kedua lokasi.
        
        $path1 = 'images/' . $filename;
        $path2 = 'perbaikan/' . $filename;

        // Cek apakah file ada di salah satu dari kedua path
        if (Storage::disk('public')->exists($path1)) {
            $path = $path1;
        } elseif (Storage::disk('public')->exists($path2)) {
            $path = $path2;
        } else {
            // Jika file tidak ditemukan di kedua lokasi, kembalikan 404
            abort(404);
        }

        // Baca file dan tentukan tipe MIME-nya
        $file = Storage::disk('public')->get($path);
        $type = Storage::disk('public')->mimeType($path);

        // Sajikan file sebagai respons HTTP
        return response($file, 200)->header('Content-Type', $type);
    }
}