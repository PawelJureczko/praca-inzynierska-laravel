<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('test', function (Blueprint $table) {
            // Zmiana nazwy kolumny 'test-1' na 'name'
            $table->renameColumn('test-1', 'name');

            // Zmiana nazwy kolumny 'test-2' na 'surname'
            $table->renameColumn('test-2', 'surname');
        });
    }

    public function down(): void
    {
        // Jeśli konieczne, możesz tutaj przywrócić oryginalne nazwy kolumn w przypadku cofnięcia migracji
        Schema::table('test', function (Blueprint $table) {
            $table->renameColumn('name', 'test-1');
            $table->renameColumn('surname', 'test-2');
        });
    }
};
