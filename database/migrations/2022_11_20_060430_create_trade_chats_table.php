<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradeChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trade_chats', function (Blueprint $table) {
            $table->id();
            $table->integer('trades_id')->index()->nullable();
            $table->morphs('chatable');
            $table->longText('description')->nullable();
            $table->boolean('is_read')->default(0);
            $table->boolean('is_read_admin')->default(0);
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
        Schema::dropIfExists('trade_chats');
    }
}
