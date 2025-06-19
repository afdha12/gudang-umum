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
        Schema::create('item_demands', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('stationery_id');
            $table->string('amount');
            $table->date('dos');
            $table->text('notes')->nullable();
            $table->boolean('manager_approval')->default(null);
            $table->boolean('coo_approval')->default(null);
            $table->boolean('status')->default(null);
            $table->string('rejected_by')->nullable();
            // $table->string('satuan')->nullable();
            // $table->integer('masuk')->nullable();
            // $table->integer('keluar')->nullable();
            // $table->integer('stok')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_demands');
    }
};
