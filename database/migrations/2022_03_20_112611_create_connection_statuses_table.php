<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConnectionStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('connection_statuses', function (Blueprint $table) {
            $table->unsignedBigInteger('connection_id');
            $table->foreign('connection_id')->references('id')->on('connections')->onDelete('cascade');
            $table->string('morphClass');
            $table->unsignedBigInteger('morphedModel');
        });
    }
}
