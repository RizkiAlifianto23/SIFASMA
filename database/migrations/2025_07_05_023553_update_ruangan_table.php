<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan perubahan.
     */
    public function up(): void
    {
        // Hapus kolom id_gedung dari tabel ruangan
        Schema::table('ruangan', function (Blueprint $table) {
            if (Schema::hasColumn('ruangan', 'id_gedung')) {
                // Hapus foreign key terlebih dahulu
                try {
                    $table->dropForeign(['id_gedung']);
                } catch (\Throwable $e) {
                    // Abaikan error jika constraint tidak ditemukan
                }

                $table->dropColumn('id_gedung');
            }
        });

        // Tambahkan kolom id_lantai ke tabel ruangan
        Schema::table('ruangan', function (Blueprint $table) {
            if (!Schema::hasColumn('ruangan', 'id_lantai')) {
                $table->unsignedBigInteger('id_lantai')->nullable()->after('id');

                $table->foreign('id_lantai')
                    ->references('id')
                    ->on('lantai')
                    ->onDelete('set null');
            }
        });
    }

    /**
     * Rollback perubahan.
     */
    public function down(): void
    {
        // Hapus kolom id_lantai dari tabel ruangan
        Schema::table('ruangan', function (Blueprint $table) {
            if (Schema::hasColumn('ruangan', 'id_lantai')) {
                $table->dropForeign(['id_lantai']);
                $table->dropColumn('id_lantai');
            }
        });

        // Tambahkan kembali kolom id_gedung ke tabel ruangan
        Schema::table('ruangan', function (Blueprint $table) {
            if (!Schema::hasColumn('ruangan', 'id_gedung')) {
                $table->unsignedBigInteger('id_gedung')->nullable()->after('id');

                $table->foreign('id_gedung')
                    ->references('id')
                    ->on('gedung')
                    ->onDelete('set null');
            }
        });
    }
};
