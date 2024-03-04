<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ReportsController extends Controller
{
    public function index() {
        $users = DB::select('SELECT * FROM users'); // Zastąp 'your_table' nazwą rzeczywistej tabeli w bazie danych

        return Inertia::render('Reports/Reports', [
            'users'=>$users
        ]);
    }
}
