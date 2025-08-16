<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoleNotification extends Model
{
    protected $fillable = [
        'id_role', // Foreign key to Role
        'id_laporan', // Foreign key to Laporan, nullable
        'title',
        'message',
        'is_read',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
    public function laporan(): BelongsTo
    {
        return $this->belongsTo(Laporan::class, 'id_laporan');
    }
}
