<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::enableForeignKeyConstraints();
        Schema::create('books', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('owner_id');
            $table->string('author');
            $table->string('name');
            $table->string('publisher');
            $table->string('code')->unique();
            //$table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('books');
    }
}
