<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdLaporanToRoleNotificationsTable extends Migration
{
    public function up(): void
    {
        Schema::table('role_notifications', function (Blueprint $table) {
            $table->unsignedBigInteger('id_laporan')->nullable()->after('id_role');

            $table->foreign('id_laporan')
                ->references('id')->on('laporan') // atau 'laporans' sesuai nama tabel kamu
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('role_notifications', function (Blueprint $table) {
            $table->dropForeign(['id_laporan']);
            $table->dropColumn('id_laporan');
        });
    }
}
