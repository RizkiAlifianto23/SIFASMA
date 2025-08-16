<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Log extends Model
{
    // Jika nama tabel tidak jamak ('logs'), sebutkan eksplisit
    protected $table = 'log';

    protected $fillable = [
        'action',
        'created_by',
    ];

    /**
     * Relasi ke user yang membuat log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
