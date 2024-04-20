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
    return Inertia::render('Schedule/Schedule', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('/test', \App\Http\Controllers\TestController::class)->middleware(['auth', 'verified']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/notifications/invitations', [NotificationsController::class, 'invitations'])->name('notifications.invitations')->middleware(['checkStudent']);
    Route::get('/notifications/homeworks', [NotificationsController::class, 'homeworks'])->name('notifications.homeworks');
    Route::put('/notifications/invitations', [NotificationsController::class, 'acceptInvitation'])->name('notification.accept');
});

Route::middleware(['auth', 'verified', 'checkAdmin'])->group(function () {
    //moi uczniowie
    Route::get('/students', [StudentsController::class, 'getMyStudents'])->name('students.index');
    //zaproszeni uczniowie
    Route::get('/students/invited', [StudentsController::class, 'getInvitedStudents'])->name('students.invited');
    //pozostali uczniowie
    Route::get('/students/other', [StudentsController::class, 'getOtherStudents'])->name('students.other');
    Route::post('/students', [StudentsController::class, 'inviteStudent'])->name('students.invite');
    Route::get('/students/details/{id}', [StudentsController::class, 'showStudentDetails'])->name('student.details')->middleware(['areConnected']);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/messages', [\App\Http\Controllers\MessagesController::class, 'messagesList'])->name('messages.index');
    Route::get('/messages/t/{id}', [\App\Http\Controllers\MessagesController::class, 'messagesSingle'])->name('messages.single')->middleware(['areConnected']);
    Route::get('/messages/reload', [\App\Http\Controllers\MessagesController::class, 'reloadMessages'])->name('messages.reload');
    Route::get('/messages/new', [\App\Http\Controllers\MessagesController::class, 'messageNew'])->name('messages.new');

    Route::post('/messages', [\App\Http\Controllers\MessagesController::class, 'sendMessage'])->name('messages.send');
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/schedule', [\App\Http\Controllers\ScheduleController::class, 'index'])->name('schedule.index');
    Route::get('/schedule/new', [\App\Http\Controllers\ScheduleController::class, 'create'])->name('schedule.create')->middleware(['checkAdmin']);
    Route::post('/schedule/save', [\App\Http\Controllers\ScheduleController::class, 'save'])->name('schedule.save')->middleware(['checkAdmin']);
    Route::get('/schedule/getData', [\App\Http\Controllers\ScheduleController::class, 'getScheduleForDateRange'])->name('schedule.getData');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/lesson/{id}', [\App\Http\Controllers\LessonController::class, 'createLesson'])->name('lesson.create')->middleware(['lessonParticipant']);
    Route::post('/lesson/save', [\App\Http\Controllers\LessonController::class, 'saveLesson'])->name('lesson.save');
    Route::get('/lesson/edit/{id}', [\App\Http\Controllers\LessonController::class, 'editLesson'])->name('lesson.edit')->middleware(['lessonParticipant']);;
    Route::put('/lesson/edit/update', [\App\Http\Controllers\LessonController::class, 'updateLesson'])->name('lesson.update');
    Route::post('lessons/edit/save/addAbsence', [\App\Http\Controllers\LessonController::class, 'addAbsence'])->name('absence.create');
    Route::put('lessons/edit/save/homeworkDone', [\App\Http\Controllers\LessonController::class, 'markHomeworkAsDone'])->name('homework.completed');
});

Route::resource('/reports', \App\Http\Controllers\ReportsController::class)->middleware(['auth', 'verified']);


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
