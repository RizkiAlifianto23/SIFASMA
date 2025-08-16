<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gedung', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_lantai')->constrained('lantai')->onDelete('cascade');            $table->string('kode_gedung', 10)->unique();
            $table->string('nama_gedung', 50);
            $table->string('status', 20)->default('Active'); // Tersedia, Tidak Tersedia
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');          
            $table->foreignId('updated_by')->constrained('users')->onDelete('cascade');            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gedung');
    }
};
