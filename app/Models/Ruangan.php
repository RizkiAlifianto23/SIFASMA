<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    use HasFactory;

    protected $table = 'ruangan';

    protected $fillable = [
        'id_ruangan',
        'id_lantai', // Foreign key to Lantai
        'kode_ruangan',
        'nama_ruangan',
        'status',
        'created_by',
        'updated_by',
    ];

    // Relasi ke gedung
    public function gedung()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan');
    }
    // Relasi ke lantai
    public function lantai()
    {
        return $this->belongsTo(Lantai::class, 'id_lantai');
    }

    // Relasi ke user pembuat
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi ke user pengubah
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
