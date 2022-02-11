<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CourseController;

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

/*
 * Register and login Routes dont need any Authentication
 */
Route::post('register',[UserController::class,'register']);
Route::post('login',[UserController::class,'login']);

/*
 * Below Routes are need to Authentication ( JWT Token ) and we take them in a group
 */
Route::group(['middleware'=>['auth:api']],function(){

    /*
     * User API Routes
     */
    Route::get('profile',[UserController::class,'profile']);
    Route::get('logout',[UserController::class,'logout']);

    /*
     * Course API Routes
     */
    Route::post('course-enroll',[CourseController::class,'courseEnrollment']);
    Route::get('total-courses',[CourseController::class,'totalCourses']);
    Route::get('delete-course/{id}',[CourseController::class,'deleteCourse']);

});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
