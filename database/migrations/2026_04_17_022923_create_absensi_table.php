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
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->string('pegawai_nip');

            $table->foreignId('periode_id')
                ->constrained('periode')
                ->onDelete('cascade');

            $table->integer('bulan');
            $table->integer('tgl');
            $table->integer('pagi')->nullable();
            $table->time('jam_masuk_pagi')->nullable();
            $table->time('jam_masuk_sore')->nullable();
            $table->integer('sore')->nullable();
            $table->string('keterangan')->nullable();

            $table->timestamps();

            $table->foreign('pegawai_nip')
                ->references('nip')
                ->on('pegawai')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
