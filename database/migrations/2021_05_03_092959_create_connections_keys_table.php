<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConnectionsKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('connections_keys', function (Blueprint $table) {
            $table->unsignedBigInteger('connection_id')->nullable();
            $table->foreign('connection_id')->references('id')->on('connections')->onDelete('cascade');
            $table->unsignedBigInteger('key_id')->nullable();
            $table->foreign('key_id')->references('id')->on('keys')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('connections_keys');
    }
}
