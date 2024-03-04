<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ScheduleController extends Controller
{
    public function index() {
        $users = DB::select('SELECT * FROM users'); // Zastąp 'your_table' nazwą rzeczywistej tabeli w bazie danych

        return Inertia::render('Schedule/Schedule', [
            'users'=>$users
        ]);
    }
}
