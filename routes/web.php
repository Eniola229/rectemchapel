<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

//Attendance
use App\Http\Controllers\Attendance\AuthController;
use App\Http\Controllers\Attendance\StudentController;
use App\Http\Controllers\Attendance\AdminController;
use App\Http\Controllers\Attendance\AttendanceController;
use App\Http\Controllers\Attendance\AddDateTimeController;

//Library
use App\Http\Controllers\Library\LibraryController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/departments', function () {
    return view('departments');
});

Route::get('/gallery', function () {
    return view('gallery');
});

Route::get('/contact', function () {
    return view('contact');
});

Route::get('/e-library', function () {
    return view('library');
});

//e-library
Route::post('/uploadMaterial', [LibraryController::class, 'uploadMaterial']);
Route::get('/getMaterials', [LibraryController::class, 'getMaterials']);


Route::post('/contact-submit', [ContactController::class, 'submitContactForm'])->name('contact.submit');
Route::post('/newsletter-subscribe', [ContactController::class, 'subscribe'])->name('newsletter.subscribe');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('admin/login', [AuthController::class, 'index'])->name('admin-login');
Route::post('post/login', [AuthController::class, 'postLogin'])->name('admin-login.post'); 


Route::get('/admin/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
Route::post('/admin/attendance/mark', [AttendanceController::class, 'markAttendance'])->name('attendance.mark');

Route::get('admin/logout', [AuthController::class, 'logout'])->name('admin-logout');
Route::middleware('auth:admin')->prefix('admin')->group(function () {
 Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('admin-dashboard'); 
 Route::get('/students', [StudentController::class, 'index'])->name('admin-students'); 
 Route::post('/store', [StudentController::class, 'store'])->name('student.store');
 Route::get('/student-info/{id}', [StudentController::class, 'show'])->name('student.show');
 Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('students.destroy');
 Route::get('/admins', [AdminController::class, 'index'])->name('admin-admin'); 
 Route::post('/admin-store', [AdminController::class, 'store'])->name('admin.store');
 Route::delete('/admin/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');
 Route::get('/attendance', [AttendanceController::class, 'index'])->name('admin-attendance'); 
 Route::get('/time', [AddDateTimeController::class, 'index'])->name('admin-time'); 
 Route::post('/schedule/store', [AddDateTimeController::class, 'store'])->name('schedule.store');
 Route::get('/schedule/all', [AddDateTimeController::class, 'all'])->name('schedule.all');
 Route::get('/schedule/{id}', [AddDateTimeController::class, 'edit']);
 Route::put('/schedule/update/{id}', [AddDateTimeController::class, 'update']);
 Route::delete('/schedule/delete/{id}', [AddDateTimeController::class, 'destroy']);

});

require __DIR__.'/auth.php';
