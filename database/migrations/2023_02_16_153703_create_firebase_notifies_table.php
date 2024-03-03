<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFirebaseNotifiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('firebase_notifies', function (Blueprint $table) {
            $table->id();
            $table->string('server_key')->nullable();
            $table->string('vapid_key')->nullable();
            $table->string('api_key')->nullable();
            $table->string('auth_domain')->nullable();
            $table->string('project_id')->nullable();
            $table->string('storage_bucket')->nullable();
            $table->string('messaging_sender_id')->nullable();
            $table->string('app_id')->nullable();
            $table->string('measurement_id')->nullable();
            $table->boolean('user_foreground')->default(1)->comment("0=> off, 1=> on");
            $table->boolean('user_background')->default(1)->comment("0=> off, 1=> on");
            $table->boolean('admin_foreground')->default(1)->comment("0=> off, 1=> on");
            $table->boolean('admin_background')->default(1)->comment("0=> off, 1=> on");
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
        Schema::dropIfExists('firebase_notifies');
    }
}
