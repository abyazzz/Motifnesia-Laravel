<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update semua data terjual yang bukan angka jadi 0
        DB::statement("UPDATE produk SET terjual = '0' WHERE terjual IS NULL OR terjual = '' OR terjual NOT REGEXP '^[0-9]+$'");
        
        Schema::table('produk', function (Blueprint $table) {
            // Ubah kolom terjual dari string ke integer
            $table->integer('terjual')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            // Kembalikan ke string jika rollback
            $table->string('terjual', 255)->nullable()->change();
        });
    }
};
