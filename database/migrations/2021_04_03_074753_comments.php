<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Comments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // create comments table
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('on_post');
            $table->unsignedBigInteger('from_user');
            $table->foreign('on_post')
            ->references('id')->on('posts')
            ->onDelete('cascade');
            $table->foreign('from_user')
            ->references('id')->on('users')
            ->onDelete('cascade');
            $table->text('body');
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
        // drop comments table
        Schema::drop('comments');
    }
}
