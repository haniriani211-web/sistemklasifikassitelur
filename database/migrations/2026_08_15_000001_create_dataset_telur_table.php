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
        Schema::create('dataset_telur', function (Blueprint $table) {
            $table->id();
            $table->string('kode_telur')->unique();
            $table->float('berat', 8, 2); // Gram
            $table->float('diameter', 8, 2); // Cm
            $table->enum('kondisi_cangkang', ['Normal', 'Retak']);
            $table->enum('warna_cangkang', ['Cokelat Tua', 'Cokelat Muda']);
            $table->enum('kualitas', ['Layak Jual', 'Tidak Layak Jual']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dataset_telur');
    }
};
