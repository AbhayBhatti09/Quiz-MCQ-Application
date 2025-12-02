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
        Schema::table('quizzes', function (Blueprint $table) {
             $table->integer('marks_per_question')->default(1)->after('total_marks');
            $table->integer('time_per_question')->default(30)->after('marks_per_question');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
           $table->dropColumn(['marks_per_question', 'time_per_question']);
        });
    }
};
