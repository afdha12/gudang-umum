<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barang_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stationery_id')->constrained()->onDelete('cascade'); // Barang terkait
            $table->enum('jenis', ['masuk', 'keluar']); // Menyimpan jenis transaksi
            $table->integer('jumlah'); // Jumlah barang masuk atau keluar
            $table->date('tanggal'); // Tanggal transaksi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_history');
    }
};
