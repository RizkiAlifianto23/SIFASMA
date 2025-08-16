<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCancelledFieldsToLaporanTable extends Migration
{
    public function up()
    {
        Schema::table('laporan', function (Blueprint $table) {
            $table->timestamp('cancelled_at')->nullable()->after('approved_at');
            $table->unsignedBigInteger('cancelled_by')->nullable()->after('cancelled_at');

            // Jika kamu ingin relasi ke tabel users (opsional):
            // $table->foreign('cancelled_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('laporan', function (Blueprint $table) {
            $table->dropColumn(['cancelled_at', 'cancelled_by']);
        });
    }
}
