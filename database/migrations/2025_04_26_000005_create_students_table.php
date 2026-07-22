<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('faculties_id')->constrained('faculties')->onDelete('restrict');
            $table->foreignId('programs_id')->constrained('programs')->onDelete('restrict');
            $table->foreignId('branches_id')->constrained('branches')->onDelete('restrict');
            $table->foreignId('majors_id')->constrained('majors')->onDelete('restrict')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
