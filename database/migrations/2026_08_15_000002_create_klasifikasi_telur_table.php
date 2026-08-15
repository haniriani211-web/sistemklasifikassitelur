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
        Schema::create('klasifikasi_telur', function (Blueprint $table) {
            $table->id();
            $table->string('kode_telur');
            $table->date('tanggal_panen');
            $table->float('berat', 8, 2);
            $table->float('diameter', 8, 2);
            $table->enum('kondisi_cangkang', ['Normal', 'Retak']);
            $table->enum('warna_cangkang', ['Cokelat Tua', 'Cokelat Muda']);
            $table->enum('hasil_klasifikasi', ['Layak Jual', 'Tidak Layak Jual']);
            $table->string('rule_applied')->nullable();
            $table->foreignId('pekerja_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('klasifikasi_telur');
    }
};
