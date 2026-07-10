<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kurang_kirim', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('toko_id')->nullable();
            $table->string('kode_toko', 50);
            $table->date('tgl_kirim');
            $table->string('nomor_surat_jalan', 100)->nullable();
            $table->string('lampiran', 255)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('toko_id', 'fk_toko')->references('id')->on('toko')->cascadeOnDelete();
            $table->index('tgl_kirim', 'idx_tgl_kirim');
            $table->index('status', 'idx_status');
            $table->index('nomor_surat_jalan', 'idx_nomor_surat_jalan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kurang_kirim');
    }
};
