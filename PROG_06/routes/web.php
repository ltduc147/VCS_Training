<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\SubmissionController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::redirect('/', '/login');

// Route for authentication
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'do_login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('is_login');

// Route for user action
Route::get('/user/students', [UserController::class, 'student_management'])->name('students')->middleware('is_login', 'role:teacher');
Route::get('/users', [UserController::class, 'user_list'])->name('users')->middleware('is_login');
Route::get('/user/form', [UserController::class, 'user_form'])->middleware('is_login', 'role:teacher');
Route::get('/user/{id}', [UserController::class, 'profile'])->where('id','[0-9]+')->name('profile')->middleware('is_login');
Route::get('/user/avt_form', [UserController::class, 'avt_form'])->middleware('is_login');
Route::post('/user/update_profile', [UserController::class, 'update_profile'])->middleware('is_login');
Route::post('/user/change_pass', [UserController::class, 'change_pass'])->middleware('is_login');
Route::post('/user/create', [UserController::class, 'create'])->middleware('is_login', 'role:teacher');
Route::post('/user/update/{id}', [UserController::class, 'update'])->where('id','[0-9]+')->middleware('is_login', 'role:teacher');
Route::get('/user/delete/{id}', [UserController::class, 'delete'])->where('id','[0-9]+')->middleware('is_login', 'role:teacher');

// Route for assignment action
Route::get('/assignments', [AssignmentController::class, 'assignment_list'])->name('assignments')->middleware('is_login');
Route::get('/assignment/form', [AssignmentController::class, 'assignment_form'])->middleware('is_login', 'role:teacher');
Route::get('/assignment/{id}', [AssignmentController::class, 'assignment_detail'])->where('id','[0-9]+')->middleware('is_login');
Route::post('/assignment/create', [AssignmentController::class, 'create'])->middleware('is_login', 'role:teacher');
Route::post('/assignment/update/{id}', [AssignmentController::class, 'update'])->where('id','[0-9]+')->middleware('is_login', 'role:teacher');
Route::get('/assignment/delete/{id}', [AssignmentController::class, 'delete'])->where('id','[0-9]+')->middleware('is_login', 'role:teacher');

// Route for challenge action
Route::get('/challenges', [ChallengeController::class, 'challenge_list'])->name('challenges')->middleware('is_login');
Route::get('/challenge/form', [ChallengeController::class, 'challenge_form'])->middleware('is_login', 'role:teacher');
Route::get('/challenge/{id}', [ChallengeController::class, 'challenge_detail'])->where('id','[0-9]+')->middleware('is_login');
Route::post('/challenge/create', [ChallengeController::class, 'create'])->middleware('is_login', 'role:teacher');
Route::post('/challenge/update/{id}', [ChallengeController::class, 'update'])->where('id','[0-9]+')->middleware('is_login', 'role:teacher');
Route::get('/challenge/delete/{id}', [ChallengeController::class, 'delete'])->where('id','[0-9]+')->middleware('is_login', 'role:teacher');
Route::post('/challenge/answer/{id}', [ChallengeController::class, 'answer'])->where('id','[0-9]+')->middleware('is_login');

// Route for message action
Route::get('/message/form', [MessageController::class, 'message_form'])->middleware('is_login');
Route::post('/message/create', [MessageController::class, 'create'])->middleware('is_login');
Route::post('/message/update/{id}', [MessageController::class, 'update'])->where('id','[0-9]+')->middleware('is_login');
Route::get('/message/delete/{id}', [MessageController::class, 'delete'])->where('id','[0-9]+')->middleware('is_login');

// Route for submission action
Route::post('/submission/create_update', [SubmissionController::class, 'create_update'])->middleware('is_login');
Route::get('/submission/delete/{id}', [SubmissionController::class, 'delete'])->where('id','[0-9]+')->middleware('is_login');
