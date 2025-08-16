<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gedung extends Model
{
    use HasFactory;

    protected $table = 'gedung';

    protected $fillable = [
        'kode_gedung',
        'nama_gedung',
        'status',
        'created_by',
        'updated_by',
    ];

    // Relasi ke lantai
    public function lantai()
    {
        return $this->hasMany(Lantai::class, 'id_lantai');
    }

    // Relasi ke user (pembuat)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi ke user (pengubah)
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
