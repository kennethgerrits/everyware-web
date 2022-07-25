<?php

use App\Http\Controllers\API\UserApiController;
use App\Http\Controllers\API\WorksheetApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\StudentApiController;
use App\Http\Controllers\API\ClassroomApiController;
use App\Http\Controllers\API\CategoryApiController;
use App\Http\Controllers\API\TemplateApiController;
use App\Http\Controllers\API\WordlistApiController;

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

Route::middleware('client_credentials')->group(function () {
    Route::get('/categories', [CategoryApiController::class, "getAllCategories"]);
    Route::get('/categories/student/{id}', [CategoryApiController::class, "getStudentCategories"]);
    Route::get('/wordlists', [WordlistApiController::class, "getWordlists"]);
    Route::get('/wordlist/{id}', [WordlistApiController::class, "getWordlistById"]);
    Route::get('/wordlists/{ids}', [WordlistApiController::class, "getWordlistsByIds"]);

    Route::get('/templates', [TemplateApiController::class, "getAllTemplates"]);
    Route::get('/templates/{name}', [TemplateApiController::class, "getTemplatesByName"]);
    Route::get('/templates/multiple/{ids}', [TemplateApiController::class, "getTemplatesByIds"]);
    Route::get('/templates/student/{id}', [TemplateApiController::class, "getTemplatesByStudentId"]);
    Route::get('/templates/{category}', [TemplateApiController::class, "getTemplatesByCategory"]);

    Route::post('/worksheets', [WorksheetApiController::class, "postWorksheet"]);
});

Route::post('/login', [UserApiController::class, "login"]);


