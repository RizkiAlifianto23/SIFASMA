<?php

namespace Database\Seeders;

use App\Models\Gedung;
use Illuminate\Database\Seeder;

class GedungSeeder extends Seeder
{
    public function run(): void
    {
        Gedung::create([
            'id_lantai' => 1,
            'kode_gedung' => 'G1',
            'nama_gedung' => 'Gedung A',
            'status' => 'Active',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        Gedung::create([
            'id_lantai' => 2,
            'kode_gedung' => 'G2',
            'nama_gedung' => 'Gedung B',
            'status' => 'Active',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        Gedung::create([
            'id_lantai' => 3,
            'kode_gedung' => 'G3',
            'nama_gedung' => 'Gedung C',
            'status' => 'Inactive',
            'created_by' => 1,
            'updated_by' => 1,
        ]);
    }
}
