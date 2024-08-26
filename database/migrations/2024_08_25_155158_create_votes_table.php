<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVotesTable extends Migration
{
    public function up()
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('votable_id'); 
            $table->string('votable_type'); // (Question or Reply)
            $table->boolean('vote_type'); // 1 for upvote, 0 for downvote
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['user_id', 'votable_id', 'votable_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('votes');
    }
}
