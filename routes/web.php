<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClassGroupController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\LearningMaterialController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\TemplateCollectionController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WordlistController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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


Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::prefix('stats')->group(function () {
        Route::get('/', [StatsController::class, 'index'])->name('stats.index');
        Route::get('/refresh', [StatsController::class, 'refreshStats'])->name('stats.refresh');
        Route::get('/{template_id}/users', [StatsController::class, 'users'])->name('stats.users');
        Route::get('/{template_id}/users/{user_id}/worksheets', [StatsController::class, 'worksheets'])->name('stats.worksheets');
        Route::get('/{template_id}/users/{user_id}/worksheets/{worksheet_id}', [StatsController::class, 'worksheetsDetails'])->name('stats.worksheet.details');
    });

    Route::prefix('material')->group(function () {
        Route::get('/', [LearningMaterialController::class, 'index'])->name('material.index');
        Route::get('/search', [LearningMaterialController::class, 'search'])->name('material.search');
        Route::get('/categories', [LearningMaterialController::class, 'getCategories'])->name('material.categories.get');
        Route::get('/templates', [LearningMaterialController::class, 'getTemplates'])->name('material.templates.get');
        Route::get('/template-collections', [LearningMaterialController::class, 'getTemplateCollections'])->name('material.template-collections.get');
    });

    Route::get('groups', [GroupController::class, 'index'])->name('groups.index');

    Route::get('wordlists/{id}/images', [WordlistController::class, 'getImages'])->name('wordlists.images.get');
    Route::get('templates/answers/all', [TemplateController::class, 'getAnswers'])->name('templates.answers.get');
    Route::get('templates/wordlist/{id}', [TemplateController::class, 'getWordlist'])->name('templates.wordlist.get');
    Route::post('templates/{id}/copy', [TemplateController::class, 'copy'])->name('templates.copy.post');

    Route::resource('templates', TemplateController::class);
    Route::resource('template-collections', TemplateCollectionController::class);
    Route::resource('wordlists', WordlistController::class);
    Route::resource('category', CategoryController::class);
    Route::resource('class-groups', ClassGroupController::class);
    Route::resource('users', UserController::class);
});
