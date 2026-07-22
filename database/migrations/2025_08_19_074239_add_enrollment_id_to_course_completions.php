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
        Schema::table('course_completions', function (Blueprint $table) {
            $table->foreignId('enrollment_id')->constrained('enrollments')->onDelete('cascade')->after('course_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_completions', function (Blueprint $table) {
            $table->dropForeign(['enrollment_id']);
            $table->dropColumn('enrollment_id');
        });
    }
};
