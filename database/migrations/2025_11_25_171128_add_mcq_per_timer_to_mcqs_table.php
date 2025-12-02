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
        Schema::table('mcqs', function (Blueprint $table) {
            $table->integer('mcq_per_timer')
                ->default(30)
                ->comment('Default timer is 30 seconds per question')
                ->after('marks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mcqs', function (Blueprint $table) {
              $table->dropColumn('mcq_per_timer');
        });
    }
};
