<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('upload_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('original_filename', 255)->nullable();
            $table->string('safe_filename', 255)->nullable();
            $table->integer('file_size')->nullable();
            $table->string('mime_type', 50)->nullable();
            $table->string('upload_path', 255)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('upload_logs');
    }
};
