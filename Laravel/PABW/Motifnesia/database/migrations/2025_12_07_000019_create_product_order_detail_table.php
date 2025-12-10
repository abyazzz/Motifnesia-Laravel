<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_order_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedInteger('product_id');
            $table->string('ukuran');
            $table->integer('qty');
            $table->integer('harga');
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('product_order')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('produk');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_order_detail');
    }
};
