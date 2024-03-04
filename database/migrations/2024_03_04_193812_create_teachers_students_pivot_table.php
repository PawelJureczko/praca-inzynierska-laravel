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
        Schema::create('teachers_students_pivot', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('teacher_id')->default(NULL);
            $table->bigInteger('student_id')->default(NULL);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers_students_pivot');
    }
};
