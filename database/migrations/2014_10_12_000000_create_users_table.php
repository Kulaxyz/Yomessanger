<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('surname')->nullable();
            $table->string('username')->nullable();
            $table->string('phone');
            $table->timestamp('last_online_at')->nullable();
            $table->string('verification_id')->nullable();
            $table->string('avatar')->nullable();
            $table->boolean('is_blocked')->nullable();
            $table->string('status_text')->nullable();
            $table->double('balance')->nullable()->default(0.0);
            $table->integer('referred_by')->nullable();
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
        Schema::dropIfExists('users');
    }
}
