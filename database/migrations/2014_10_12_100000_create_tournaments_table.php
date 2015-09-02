<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTournamentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournaments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key');
            $table->string('name');
            $table->integer('pistelasku');
            $table->integer('ratkaisuaika');
            $table->timestamp('starts_at');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->integer('loaded_to_server');
            $table->integer('collection_id')->unsigned();

            $table->foreign('collection_id')->references('id')->on('collections');
            $table->unique('key');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tournaments');
    }
}


