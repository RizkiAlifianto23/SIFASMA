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
        Schema::create('fasilitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_ruangan')->constrained('ruangan')->onDelete('cascade');
            $table->string('kode_fasilitas', 10)->unique();
            $table->string('nama_fasilitas', 50);
            $table->string('status', 20)->default('Active'); // Active, Inactive, Maintenance
            $table->string('keterangan')->nullable();
            $table->foreignId(column: 'created_by')->constrained('users')->onDelete('cascade');          
            $table->foreignId('updated_by')->constrained('users')->onDelete('cascade');            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fasilitas');
    }
};
