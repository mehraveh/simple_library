<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBorrowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('borrows', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('borrower_id');
            $table->foreign('borrower_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade'); 
            $table->unsignedInteger('book_id');
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade')->onUpdate('cascade'); 
            $table->enum('mode', ['accepted', 'pending', 'reject']);
            $table->dateTime('borrowed_at'); 
            $table->dateTime('take_back_at'); 
            $table->boolean('taken_back')->default(0);
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
        Schema::dropIfExists('borrows');
    }
}
