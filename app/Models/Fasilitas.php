<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    use HasFactory;

    protected $table = 'fasilitas';

    protected $fillable = [
        'id_ruangan',
        'kode_fasilitas',
        'nama_fasilitas',
        'status',
        'keterangan',
        'created_by',
        'updated_by',
    ];

    // Relasi ke ruangan
    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan');
    }

    // Relasi ke user pembuat
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi ke user pengubah terakhir
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function laporan()
    {
        return $this->hasMany(Laporan::class, 'id_fasilitas');
    }

}
