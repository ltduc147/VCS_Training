<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AssignmentController;
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

Route::get('/user/students', [UserController::class, 'student_management'])->name('students');
Route::get('/users', [UserController::class, 'user_list'])->name('users');
Route::get('/user/form', [UserController::class, 'user_form']);
Route::get('/user/{id}', [UserController::class, 'profile'])->where('id','[0-9]+');
Route::get('/user/create', [UserController::class, 'create']);
Route::get('/user/update/{id}', [UserController::class, 'update'])->where('id','[0-9]+');
Route::get('/user/delete/{id}', [UserController::class, 'delete'])->where('id','[0-9]+');


Route::get('/assignments', [AssignmentController::class, 'assignment_list'])->name('assignments');
Route::get('/assignment/form', [AssignmentController::class, 'assignment_form']);
Route::get('/assignment/{id}', [AssignmentController::class, 'assignment_detail'])->where('id','[0-9]+');
Route::get('/assignment/create', [AssignmentController::class, 'create']);
Route::get('/assignment/update/{id}', [AssignmentController::class, 'update'])->where('id','[0-9]+');
Route::get('/assignment/delete/{id}', [AssignmentController::class, 'delete'])->where('id','[0-9]+');

Route::get('/challenges', [ChallengeController::class, 'challenge_list'])->name('challenges');
Route::get('/challenge/form', [ChallengeController::class, 'challenge_form']);
Route::get('/challenge/{id}', [ChallengeController::class, 'challenge_detail'])->where('id','[0-9]+');
Route::get('/challenge/create', [ChallengeController::class, 'create']);
Route::get('/challenge/update/{id}', [ChallengeController::class, 'update'])->where('id','[0-9]+');
Route::get('/challenge/delete/{id}', [ChallengeController::class, 'delete'])->where('id','[0-9]+');
Route::get('/challenge/check_answer/{id}', [ChallengeController::class, 'delete'])->where('id','[0-9]+');

Route::get('/message/form', [MessageController::class, 'message_form']);
Route::get('/message/create', [MessageController::class, 'create']);
Route::get('/message/update/{id}', [MessageController::class, 'update'])->where('id','[0-9]+');
Route::get('/message/delete/{id}', [MessageController::class, 'delete'])->where('id','[0-9]+');


Route::get('/submission/form', [SubmissionController::class, 'message_form']);
Route::get('/submission/create', [SubmissionController::class, 'create']);
Route::get('/submission/update/{id}', [SubmissionController::class, 'update'])->where('id','[0-9]+');
Route::get('/submission/delete/{id}', [SubmissionController::class, 'delete'])->where('id','[0-9]+');
