<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Whapplefinounce extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('whapplefinounce', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->nullable()->index();
            $table->enum('status', ['false', 'true'])->default('false');
        
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
      Schema::drop('whapplefinounce');
    }
}
