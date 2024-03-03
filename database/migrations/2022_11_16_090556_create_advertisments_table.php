<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertismentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertisments', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->index();
            $table->enum('type',['buy', 'sell'])->nullable();
            $table->integer('crypto_id')->index()->nullable();
            $table->integer('fiat_id')->index()->nullable();
            $table->integer('gateway_id')->index()->nullable();
            $table->enum('price_type',['margin', 'fixed'])->default('margin');
            $table->double('price')->default(0.00);
            $table->integer('payment_window_id')->nullable();
            $table->double('minimum_limit')->nullable();
            $table->double('maximum_limit')->nullable();
            $table->longText('payment_details')->nullable();
            $table->longText('terms_of_trade')->nullable();
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
        Schema::dropIfExists('advertisments');
    }
}
