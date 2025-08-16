<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleNotificationsTable extends Migration
{
    public function up(): void
    {
        Schema::create('role_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_role')->constrained('role')->onDelete('cascade');
            $table->string('title');
            $table->text('message');
            $table->boolean('is_read')->default(false); // opsional
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_notifications');
    }
}
