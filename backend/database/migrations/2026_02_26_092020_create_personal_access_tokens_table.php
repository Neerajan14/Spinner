<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_wins', function (Blueprint $table) {
            if (!Schema::hasColumn('user_wins', 'user_name')) {
                $table->string('user_name');
            }

            if (!Schema::hasColumn('user_wins', 'user_email')) {
                $table->string('user_email');
            }

            // Add other denormalized columns here with same checks
        });
    }

    public function down(): void
    {
        Schema::table('user_wins', function (Blueprint $table) {
            if (Schema::hasColumn('user_wins', 'user_name')) {
                $table->dropColumn('user_name');
            }

            if (Schema::hasColumn('user_wins', 'user_email')) {
                $table->dropColumn('user_email');
            }

            // Drop other columns similarly
        });
    }
};