<?php

use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AssessmentItemController;
use App\Http\Controllers\CompetencyController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\LessonResourceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SchoolYearController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SyncController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('school-years', SchoolYearController::class)->except(['show']);
    Route::resource('grades', GradeController::class)->except(['show']);
    Route::resource('sections', SectionController::class)->except(['show']);
    Route::resource('students', StudentController::class)->except(['show']);
    Route::resource('enrollments', EnrollmentController::class)->except(['show']);
    Route::resource('subjects', SubjectController::class)->except(['show']);
    Route::resource('competencies', CompetencyController::class)->except(['show']);
    Route::resource('lessons', LessonController::class)->except(['show']);
    Route::resource('lesson-resources', LessonResourceController::class)->except(['show']);
    Route::resource('assessments', AssessmentController::class)->except(['show']);
    Route::resource('assessment-items', AssessmentItemController::class)->except(['show']);
    Route::resource('scores', ScoreController::class)->except(['show']);
    Route::resource('reports', ReportController::class)->only(['index', 'create', 'store', 'show', 'destroy']);
    Route::post('sync/queue', [SyncController::class, 'store'])->name('sync.queue');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
});

require __DIR__.'/auth.php';
