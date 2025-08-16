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
        Schema::create('lantai', function (Blueprint $table) {
            $table->id();
            $table->string('kode_lantai', 10)->unique();
            $table->string('nama_lantai', 50);
            $table->string('status', 20)->default('Active'); // Active, Inactive
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
        Schema::dropIfExists('lantai');
    }
};
