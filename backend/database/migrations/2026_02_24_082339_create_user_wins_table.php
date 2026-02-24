<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_wins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('prize_id');

            // Snapshot of user data
            $table->string('user_name')->nullable();
            $table->string('user_email')->nullable();
            $table->string('user_number')->nullable();
            $table->string('user_interested')->nullable();
            $table->string('user_address')->nullable();
            $table->string('user_resume_file_name')->nullable();

            // Snapshot of prize data
            $table->string('prize_label')->nullable();
            $table->float('prize_weight')->nullable();
            $table->string('prize_price')->nullable();
            $table->boolean('prize_active')->nullable();

            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('prize_id')->references('id')->on('prizes')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_wins');
    }
};