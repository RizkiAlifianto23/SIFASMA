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
        Schema::create('laporan', function (Blueprint $table) {
            $table->id();
            $table->foreignId(column: 'id_fasilitas')->constrained('fasilitas')->onDelete('cascade');
            $table->text('deskripsi_kerusakan')->nullable();
            $table->text('deskripsi_perbaikan')->nullable();
            $table->text('foto_kerusakan')->nullable();
            $table->text('foto_hasil')->nullable();
            $table->string('status', 20)->default('Pending'); // Pending / Diterima / Ditolak / Diproses / Selesai
            $table->text('rejected_reason')->nullable();
            $table->foreignId('pelapor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('teknisi_penanggungjawab_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamp('rejected_at')->nullable();
            $table->foreignId('rejected_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamp('processed_at')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamp('finished_at')->nullable();
            $table->foreignId('finished_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps(); // created_at & updated_at
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};
