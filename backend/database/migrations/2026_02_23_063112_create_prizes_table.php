<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    Schema::create('prizes', function (Blueprint $table) {
        $table->id();
        $table->string('label');      // Name of prize
        $table->float('weight')->default(1);
        $table->string('price')->nullable();
        $table->boolean('active')->default(true); // Enable/disable prize
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prizes');
    }
};
