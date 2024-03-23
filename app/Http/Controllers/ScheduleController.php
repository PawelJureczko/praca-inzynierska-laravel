<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ScheduleController extends Controller
{
    public function index() {
        return Inertia::render('Schedule/Schedule', [
            'test'=>'test'
        ]);
    }

    public function create() {
        return Inertia::render('Schedule/ScheduleCreate', [
            'test' => 'test'
        ]);
    }
}
