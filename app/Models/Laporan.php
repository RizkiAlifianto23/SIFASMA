<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporan';

    protected $fillable = [
        'id_fasilitas',
        'deskripsi_kerusakan',
        'deskripsi_perbaikan',
        'foto_kerusakan',
        'foto_hasil',
        'status',
        'rejected_reason',
        'pelapor_id',
        'teknisi_penanggungjawab_id',
        'updated_by',
        'created_by',
        'approved_at',
        'approved_by',
        'rejected_at',
        'rejected_by',
        'processed_at',
        'processed_by',
        'finished_at',
        'finished_by',
        'cancelled_at',
        'cancelled_by',
        'submission_vendor_at',
        'submission_vendor_by',
        'description_process'
    ];

    protected $dates = [
        'approved_at',
        'rejected_at',
        'processed_at',
        'finished_at',
        'created_at',
        'updated_at',
        'cancelled_at',
        'submission_vendor_at'
        
    ];

    // Relasi ke fasilitas
    public function fasilitas()
    {
        return $this->belongsTo(Fasilitas::class, 'id_fasilitas');
    }

    // Relasi ke user sebagai pelapor
    public function pelapor()
    {
        return $this->belongsTo(User::class, 'pelapor_id');
    }

    // Relasi ke teknisi
    public function teknisi()
    {
        return $this->belongsTo(User::class, 'teknisi_penanggungjawab_id');
    }

    // Relasi ke user yang membuat
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi ke user yang mengupdate
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Relasi ke user yang menyetujui
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Relasi ke user yang menolak
    public function rejector()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    // Relasi ke user yang memproses
    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // Relasi ke user yang menyelesaikan
    public function finisher()
    {
        return $this->belongsTo(User::class, 'finished_by');
    }
    public function canceller()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }
    // Relasi ke user yang membatalkan
    public function roleNotifications()
    {
        return $this->hasMany(RoleNotification::class);
    }
    // Relasi ke user yang mengirim vendor
    public function submissionVendor()
    {
        return $this->belongsTo(User::class, 'submission_vendor_by');
    }

}
