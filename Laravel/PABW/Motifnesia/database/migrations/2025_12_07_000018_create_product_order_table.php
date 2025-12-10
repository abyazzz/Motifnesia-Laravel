<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_order', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('alamat');
            $table->unsignedBigInteger('metode_pengiriman_id');
            $table->unsignedBigInteger('metode_pembayaran_id');
            $table->integer('subtotal_produk');
            $table->integer('total_ongkir');
            $table->integer('total_bayar');
            $table->string('payment_number')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('metode_pengiriman_id')->references('id')->on('metode_pengiriman');
            $table->foreign('metode_pembayaran_id')->references('id')->on('metode_pembayaran');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_order');
    }
};
