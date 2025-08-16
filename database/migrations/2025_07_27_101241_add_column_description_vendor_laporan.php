<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('laporan', function (Blueprint $table) {
            $table->text('description_vendor')->nullable(); // Ganti 'kolom_terakhir' dengan nama kolom terakhir di tabel
            $table->boolean('is_vendor')->default(false)->after('description_vendor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan', function (Blueprint $table) {
            $table->dropColumn(['description_vendor', 'is_vendor']);
        });
    }
};
