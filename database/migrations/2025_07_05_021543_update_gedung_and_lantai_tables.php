<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateGedungAndLantaiTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Hapus foreign key & kolom id_lantai dari tabel gedung
        Schema::table('gedung', function (Blueprint $table) {
            if (Schema::hasColumn('gedung', 'id_lantai')) {
                // Hapus foreign key constraint (jika ada)
                try {
                    $table->dropForeign(['id_lantai']);
                } catch (\Exception $e) {
                    // Jika constraint tidak ada, lanjut
                }

                // Hapus kolom id_lantai
                $table->dropColumn('id_lantai');
            }
        });

        // Tambah kolom id_gedung ke tabel lantai
        Schema::table('lantai', function (Blueprint $table) {
            if (!Schema::hasColumn('lantai', 'id_gedung')) {
                $table->unsignedBigInteger('id_gedung')->nullable()->after('id');

                // Tambah foreign key constraint
                $table->foreign('id_gedung')
                    ->references('id')
                    ->on('gedung')
                    ->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tambahkan kembali kolom id_lantai ke gedung
        Schema::table('gedung', function (Blueprint $table) {
            if (!Schema::hasColumn('gedung', 'id_lantai')) {
                $table->unsignedBigInteger('id_lantai')->nullable()->after('id');

                // (Opsional) Tambahkan foreign key lagi jika dibutuhkan
                // $table->foreign('id_lantai')->references('id')->on('lantai')->onDelete('set null');
            }
        });

        // Hapus foreign key & kolom id_gedung dari lantai
        Schema::table('lantai', function (Blueprint $table) {
            if (Schema::hasColumn('lantai', 'id_gedung')) {
                $table->dropForeign(['id_gedung']);
                $table->dropColumn('id_gedung');
            }
        });
    }
}
