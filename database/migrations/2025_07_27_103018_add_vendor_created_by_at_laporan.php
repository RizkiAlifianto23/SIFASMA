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
            $table->timestamp('submission_vendor_at')->nullable();
            $table->unsignedBigInteger('submission_vendor_by')->nullable()->after('submission_vendor_at');

            $table->foreign('submission_vendor_by')->references('id')->on('role')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan', function (Blueprint $table) {
            $table->dropForeign(['submission_vendor_by']);
            $table->dropColumn(['submission_vendor_at', 'submission_vendor_by']);
        });
    }
};
