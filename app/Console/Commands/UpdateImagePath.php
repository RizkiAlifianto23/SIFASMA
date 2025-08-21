<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateImagePath extends Command
{
    protected $signature = 'update:image-path';
    protected $description = 'Update old image paths in the database from public/image to storage/images';

    public function handle()
    {
        $this->info('Starting to update image paths...');

        // Ambil semua data laporan yang memiliki foto
        $laporans = DB::table('laporan')->whereNotNull('foto_kerusakan')->get();

        $updatedCount = 0;

        foreach ($laporans as $laporan) {
            // Pastikan path yang lama dimulai dengan 'image/'
            if (str_starts_with($laporan->foto_kerusakan, 'image/')) {
                // Ambil nama file saja
                $filename = basename($laporan->foto_kerusakan);

                // Buat path baru sesuai dengan struktur storage
                $newPath = 'images/' . $filename;

                // Update record di database
                DB::table('laporan')
                    ->where('id', $laporan->id)
                    ->update(['foto_kerusakan' => $newPath]);

                $updatedCount++;
            }
        }

        $this->info("Successfully updated {$updatedCount} image paths.");

        return 0;
    }
}