<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logs', function (Blueprint $table) {
            $table->string('method')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->unsignedBigInteger('proxy_id')->nullable();
            $table->foreign('proxy_id')->references('id')->on('proxies')->onDelete('cascade');
        });
    }

}
