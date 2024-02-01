<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('guesses', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('correct');
            $table->unsignedInteger('attempt_number');
            // FK
            $table->unsignedInteger('word_id');
            $table->foreign('word_id')->references('id')->on('words');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');


            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at', 0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guesses');
    }
};
