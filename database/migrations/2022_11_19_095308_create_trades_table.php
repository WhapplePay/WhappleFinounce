<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trades', function (Blueprint $table) {
            $table->id();
            $table->integer('advertise_id')->nullable()->index();
            $table->integer('sender_id')->nullable()->index();
            $table->integer('owner_id')->nullable()->index();
            $table->enum('type',['buy', 'sell']);
            $table->integer('currency')->nullable()->index();
            $table->integer('payment_method')->nullable()->index();
            $table->decimal('rate',8,8)->default(0.00000000);
            $table->decimal('amount',8,8)->default(0.00000000);
            $table->tinyInteger('status')->default(0)->comment('0=>default');
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
        Schema::dropIfExists('trades');
    }
}
