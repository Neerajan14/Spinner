<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_wins', function (Blueprint $table) {
            $table->string('user_name')->nullable();
            $table->string('user_email')->nullable();
            $table->string('user_number')->nullable();
            $table->string('user_interested')->nullable();
            $table->string('user_address')->nullable();
            $table->string('user_resume_file_name')->nullable();
            $table->string('prize_label')->nullable();
            $table->integer('prize_weight')->nullable();
            $table->string('prize_price')->nullable();
            $table->boolean('prize_active')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('user_wins', function (Blueprint $table) {
            $table->dropColumn([
                'user_name', 'user_email', 'user_number', 'user_interested',
                'user_address', 'user_resume_file_name', 'prize_label',
                'prize_weight', 'prize_price', 'prize_active',
            ]);
        });
    }
};