<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'role';

    protected $fillable = [
        'name_role',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Relasi: Satu Role bisa dimiliki banyak User
     */
    public function users()
    {
        return $this->hasMany(User::class, 'id_role');
    }

    /**
     * Relasi ke user yang membuat role ini
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi ke user yang terakhir mengupdate role ini
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function notifications()
    {
        return $this->hasMany(RoleNotification::class);
    }
    

}
