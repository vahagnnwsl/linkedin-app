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
            $table->id();
            $table->unsignedBigInteger('conversation_id');
            $table->foreign('conversation_id')->references('id')->on('conversations')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('account_id')->nullable();
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->unsignedBigInteger('connection_id')->nullable();
            $table->foreign('connection_id')->references('id')->on('connections')->onDelete('cascade');
            $table->string('entityUrn')->nullable();
            $table->longText('text')->nullable();
            $table->json('media')->nullable();
            $table->json('attachments')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('event')->default(0);
            $table->tinyInteger('is_delete')->default(0);
            $table->dateTime('date');
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
