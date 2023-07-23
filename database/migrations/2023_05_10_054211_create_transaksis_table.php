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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->uuid('id');
            $table->integer('user_id');
            $table->integer('alamat_id');
            $table->string('bukti_pembayaran')->nullable();
            $table->integer('ekspedisi_id');
            $table->integer('status_id');
            $table->integer('final_price');
            $table->boolean('paid');
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
        Schema::dropIfExists('transaksis');
    }
};
