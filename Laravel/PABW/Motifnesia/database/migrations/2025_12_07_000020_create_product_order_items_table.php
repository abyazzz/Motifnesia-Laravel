<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_order_id');
            $table->unsignedInteger('produk_id');
            $table->string('nama');
            $table->integer('harga');
            $table->integer('qty');
            $table->integer('subtotal');
            $table->string('ukuran')->nullable();
            $table->timestamps();

            $table->foreign('product_order_id')->references('id')->on('product_order')->onDelete('cascade');
            $table->foreign('produk_id')->references('id')->on('produk');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_order_items');
    }
};
