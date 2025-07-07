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
        Schema::table('item_demands', function (Blueprint $table) {
            $table->timestamp('manager_approved_at')->nullable();
            $table->timestamp('coo_approved_at')->nullable();
            $table->timestamp('admin_approved_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_demands', function (Blueprint $table) {
            $table->dropColumn('manager_approved_at');
            $table->dropColumn('coo_approved_at');
            $table->dropColumn('admin_approved_at');
        });
    }
};
