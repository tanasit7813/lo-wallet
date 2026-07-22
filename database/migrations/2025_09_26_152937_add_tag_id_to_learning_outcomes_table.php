<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('learning_outcomes', function (Blueprint $table) {
            $table->foreignId('tag_id')->nullable()->constrained('tags')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('learning_outcomes', function (Blueprint $table) {
            $table->dropForeign(['tag_id']);
            $table->dropColumn('tag_id');
        });
    }
};
