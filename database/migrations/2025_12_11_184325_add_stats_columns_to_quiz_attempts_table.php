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
        Schema::table('quiz_attempts', function (Blueprint $table) {
              $table->integer('total_questions')->default(0);
            $table->integer('total_attended')->default(0);
            $table->integer('not_attended')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
             $table->dropColumn('total_questions');
            $table->dropColumn('total_attended');
            $table->dropColumn('not_attended');
        });
    }
};
