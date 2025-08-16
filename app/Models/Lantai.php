<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lantai extends Model
{
    use HasFactory;

    protected $table = 'lantai';

    protected $fillable = [
        'id_gedung', // Foreign key to Gedung
        'kode_lantai',
        'nama_lantai',
        'status',
        'created_by',
        'updated_by',
    ];
    // Relasi ke Gedung
    public function gedung()
    {
        return $this->belongsTo(Gedung::class, 'id_gedung');
    }
    public function ruangan()
    {
        return $this->hasMany(Ruangan::class, 'id_ruangan');
    }
    // Relasi ke User (pembuat)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi ke User (pengubah terakhir)
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
