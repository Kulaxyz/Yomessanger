<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('chat_id');
            $table->bigInteger('sender_id');
            $table->text('message')->nullable();
            $table->enum('type', ['voice', 'video', 'media', 'text', 'missed'])->default('text');
            $table->string('attachment_url')->nullable();
            $table->bigInteger('reply_to')->nullable();
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
        Schema::dropIfExists('messages');
    }
}
