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
        Schema::create('tunjangan_transport_pegawai', function (Blueprint $table) {
            $table->id();
            $table->string('pegawai_nip');
            $table->integer('max_jarak');

            $table->foreignId('periode_id')
                ->constrained('periode')
                ->onDelete('cascade');

            $table->decimal('jarak_km', 10, 2);
            $table->integer('jumlah_hari_masuk');
            $table->decimal('total_tunjangan', 15, 2)->default(0);

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
        Schema::dropIfExists('tunjangan_transport_pegawai');
    }
};
