<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('spins', function (Blueprint $table) {
            $table->string('user_name')->nullable();
            $table->string('user_email')->nullable();
            $table->string('user_number')->nullable();
            $table->string('user_address')->nullable();
        });
    }

    public function down()
    {
        Schema::table('spins', function (Blueprint $table) {
            $table->dropColumn(['user_name', 'user_email', 'user_number', 'user_address']);
        });
    }
};