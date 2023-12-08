<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthTeacherController;
use App\Http\Controllers\Auth\AuthUserController;
use App\Http\Controllers\Auth\AuthAdminController;
use App\Http\Controllers\AchievedAthkarController;
use App\Http\Controllers\AchievedEthicController;
use App\Http\Controllers\AchievedQuranController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\MembersController;
use App\Http\Controllers\NewsPanelController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\QuizController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * Teacher Routes
 */
Route::post('teacher/login', [AuthTeacherController::class, 'login']);

Route::group(['prefix' => 'teacher','middleware' => ['assign.guard:teacher']],function(){
    Route::get('logout', [AuthTeacherController::class, 'logout']);
    Route::get('profile', [AuthTeacherController::class, 'profile']);
    Route::get('achievedAthkarInMonthForTeacher/{month}/{user_id}', [AchievedAthkarController::class, 'achievedAthkarInMonthForTeacher']);
    Route::get('achievedEthicInMonthForTeacher/{month}/{user_id}', [AchievedEthicController::class, 'achievedEthicInMonthForTeacher']);
    Route::get('achievedQuranInMonthForTeacher/{month}/{user_id}', [AchievedQuranController::class, 'achievedQuranInMonthForTeacher']);

    Route::post('createAchievedQuran/{user_id}',[AchievedQuranController::class, 'create']);

    Route::post('createNews', [NewsPanelController::class, 'create']);
    Route::post('updateNews/{news_id}', [NewsPanelController::class, 'update']);
    Route::delete('deleteNews/{news_id}', [NewsPanelController::class, 'delete']);

    Route::post('createGroup', [GroupController::class, 'create']);
    Route::post('updateGroup/{group_id}', [GroupController::class, 'update']);
    Route::get('resetInviteUrl/{group_id}', [GroupController::class, 'resetInviteUrl']);
    Route::get('myGroupMembers', [GroupController::class, 'usersOfTeacherGroup']);
    Route::get('createdGroups', [GroupController::class, 'createdGroups']);

    Route::post('createQuiz', [QuizController::class, 'store']);
    Route::get('createdQuizzes', [QuizController::class, 'createdQuizzes']);
    Route::post('updateQuiz/{id}', [QuizController::class, 'update']);
    Route::delete('deleteQuiz/{id}', [QuizController::class, 'delete']);
    Route::get('assignQuiz/{quiz_id}', [QuizController::class, 'assignQuiz']);
    //Route::get('test', [AuthTeacherController::class, 'test'])->middleware('assign.guard:teacher');
});

/**
 * User Routes
 */
Route::post('user/register', [AuthUserController::class, 'register']);
Route::post('user/login', [AuthUserController::class, 'login']);

Route::group(['prefix' => 'user','middleware' => ['assign.guard:user']],function(){
    Route::get('logout', [AuthUserController::class, 'logout']);
    Route::get('profile',[AuthUserController::class, 'profile']);

    Route::get('athkary', [AchievedAthkarController::class, 'athkary']);
    Route::get('athkars', [AchievedAthkarController::class, 'athkars']);

    Route::post('createAchievedAthkar/{id}',[AchievedAthkarController::class, 'create']);
    Route::get('achievedAthkarToday', [AchievedAthkarController::class, 'achievedAthkarToday']);
    Route::get('achievedAthkarInMonth/{month}', [AchievedAthkarController::class, 'achievedAthkarInMonth']);

    Route::post('createAchievedEthic/{id}',[AchievedEthicController::class, 'create']);
    Route::get('achievedEthicToday', [AchievedEthicController::class, 'achievedEthicToday']);
    Route::get('achievedEthicInMonth/{month}', [AchievedEthicController::class, 'achievedEthicInMonth']);

    
    Route::get('achievedQuranToday', [AchievedQuranController::class, 'achievedQuranToday']);
    Route::get('achievedQuranInMonth/{month}', [AchievedQuranController::class, 'achievedQuranInMonth']);

    Route::get('availableGroups', [GroupController::class, 'availableGroups']);
    Route::post('joinGroupByInviteUrl', [MembersController::class, 'joinByInviteUrl']);
    Route::get('joinGroupById/{group_id}', [MembersController::class, 'joinById']);
    Route::get('leaveGroup', [MembersController::class, 'leave']);

    Route::get('myGroupNews', [NewsPanelController::class, 'my_group_news']);

    Route::get('achievedQuizzes', [QuizController::class, 'achievedQuizzes']);
    Route::get('listQuizzes', [QuizController::class, 'listQuizzes']);
    Route::get('listQuestions/{quiz_id}', [QuizController::class, 'listQuestions']);
    Route::post('assignResult', [QuizController::class, 'assignResult']);
    Route::get('test', [AuthUserController::class, 'test'])->middleware('assign.guard:user');
});

/**
 * Admin Routes
 */

Route::post('admin/login', [AuthAdminController::class, 'login']);
Route::post('admin/register', [AuthAdminController::class, 'register']);
Route::group(['prefix' => 'admin','middleware' => ['assign.guard:admin']],function(){
    Route::get('logout', [AuthAdminController::class, 'logout']);
    
    Route::post('registerTeacher', [AuthTeacherController::class, 'register']);
    
    Route::post('createVideo', [VideoController::class, 'create']);
    Route::post('updateVideo/{id}', [VideoController::class, 'update']);
    Route::delete('deleteVideo/{id}', [VideoController::class, 'delete']);

    Route::post('createBook', [BookController::class, 'create']);
    Route::post('updateBook/{id}', [BookController::class, 'update']);
    Route::delete('deleteBook/{id}', [BookController::class, 'delete']);

    Route::get('usersOfGroup/{group_id}', [GroupController::class, 'usersOfGroup']);
});

Route::get('show/{id}', [BookController::class, 'show']);
Route::get('show/{id}', [VideoController::class, 'show']);
Route::get('showAll', [VideoController::class, 'showAll']);
Route::get('showAll', [BookController::class, 'showAll']);