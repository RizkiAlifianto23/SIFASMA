<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditFinishedAndProcessedAtColumnsInLaporanTable extends Migration
{
    public function up(): void
    {
        Schema::table('laporan', function (Blueprint $table) {
            // Ubah kolom jika sebelumnya dibuat otomatis (misalnya pakai useCurrent atau nullableTimestamps)
            // Agar bisa diisi manual, cukup biarkan sebagai nullable timestamp
            $table->timestamp('finished_at')->nullable()->change();
            $table->timestamp('processed_at')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('laporan', function (Blueprint $table) {
            // Optional: kembalikan ke versi sebelumnya jika ingin rollback
            // Contoh: otomatis isi waktu saat ini
            $table->timestamp('finished_at')->nullable()->useCurrent()->change();
            $table->timestamp('processed_at')->nullable()->useCurrent()->change();
        });
    }
}
