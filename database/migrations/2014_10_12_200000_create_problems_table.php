<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProblemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('problems', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('position');
            $table->string('solution');
            $table->integer('difficulty')->unsigned();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');

            $table->integer('collection_id')->unsigned();
            $table->foreign('collection_id')->references('id')->on('collections');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('problems');
    }
}
