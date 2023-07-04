<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('details', function (Blueprint $table) {
            $table->id();
            $table->uuid('transaksi_id');
            $table->integer('product_id');
            $table->integer('size_id');
            $table->integer('alamat_id');
            $table->integer('quantity');
            $table->integer('price');
            $table->timestamp('transaction_date');
            $table->timestamp('end_transaction')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('details');
    }
};
