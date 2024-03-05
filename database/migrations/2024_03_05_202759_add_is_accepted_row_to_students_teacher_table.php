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
        Schema::table('teachers_students_pivot', function (Blueprint $table) {
            $table->boolean('isAccepted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers_students_pivot', function (Blueprint $table) {
            $table->removeColumn('isAccepted');
        });
    }
};
