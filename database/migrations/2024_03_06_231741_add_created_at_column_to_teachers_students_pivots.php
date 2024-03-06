<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('teachers_students_pivot', function (Blueprint $table) {
            // Dodaj nową kolumnę timestamp
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('your_table', function (Blueprint $table) {
            // Usuń istniejącą kolumnę timestamp
            $table->dropColumn('created_at');
        });
    }
};
