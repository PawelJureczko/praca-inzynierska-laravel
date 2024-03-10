<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\NotificationsController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Dashboard', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', function () {
    $userId = 1; // Przykładowe ID użytkownika, zastąp to odpowiednim ID

    $user = \App\Models\User::find($userId);

    return Inertia::render('Dashboard', [
        'user' => $user,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('/test', \App\Http\Controllers\TestController::class)->middleware(['auth', 'verified']);



Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/notifications', [NotificationsController::class, 'index'])->name('notifications.index');
    Route::put('/notifications', [NotificationsController::class, 'acceptInvitation'])->name('notification.accept');
});



Route::middleware(['auth', 'verified', 'checkAdmin'])->group(function () {
    //moi uczniowie
    Route::get('/students', [StudentsController::class, 'getMyStudents'])->name('students.index');
    //zaproszeni uczniowie
    Route::get('/students/invited', [StudentsController::class, 'getInvitedStudents'])->name('students.invited');
    //pozostali uczniowie
    Route::get('/students/other', [StudentsController::class, 'getOtherStudents'])->name('students.other');
    Route::post('/students', [StudentsController::class, 'inviteStudent'])->name('students.invite');
});

//
//Route::resource('/students', \App\Http\Controllers\StudentsController::class)->middleware(['auth', 'verified', 'checkAdmin']);

Route::resource('/schedule', \App\Http\Controllers\ScheduleController::class)->middleware(['auth', 'verified']);

Route::resource('/messages', \App\Http\Controllers\MessagesController::class)->middleware(['auth', 'verified']);

Route::resource('/reports', \App\Http\Controllers\ReportsController::class)->middleware(['auth', 'verified']);


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
