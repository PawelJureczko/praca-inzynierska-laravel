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
        Schema::table('users', function (Blueprint $table) {
            // Zmiana nazwy kolumny 'name' na 'last_name'
            $table->renameColumn('name', 'last_name');

            // Dodanie nowych kolumn
            $table->string('role')->default('admin');
            $table->string('first_name')->default(NULL);
            $table->string('phone_number')->default(NULL);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Jeśli konieczne, możesz tutaj przywrócić oryginalne nazwy kolumn w przypadku cofnięcia migracji
        Schema::table('users', function (Blueprint $table) {
            // Zmiana nazwy kolumny 'test-1' na 'name'
            $table->renameColumn('last_name', 'name');

            // Usunięcie dodanych kolumn
            $table->dropColumn('role');
            $table->dropColumn('first_name');
            $table->dropColumn('phone_number');
        });
    }
};
