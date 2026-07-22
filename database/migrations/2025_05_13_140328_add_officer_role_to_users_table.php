<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop the existing enum column and recreate it with the new value
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['student', 'instructor', 'general', 'insider', 'officer'])->nullable()->change();
        });
    }

    public function down(): void
    {
        // Revert the enum to its original values
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['student', 'instructor', 'general', 'insider'])->nullable()->change();
        });
    }
};
