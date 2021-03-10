<?php

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

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::group([
    'middleware' => 'auth'
], function () {
    Route::get('/tasks', [\App\Http\Controllers\TasksController::class, 'index'])
        ->name('tasks');
    Route::get('/tasks/all', [\App\Http\Controllers\TasksController::class, 'getAllTasks'])
        ->name('tasks.all');

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
        ->name('home');


    Route::get('/documents', [\App\Http\Controllers\DocumentsController::class, 'index'])
        ->name('documents');
    Route::post('/documents/upload', [\App\Http\Controllers\DocumentsController::class, 'uploadDocuments'])
        ->name('documents.upload');
    Route::post('/documents/classify/tasks', [\App\Http\Controllers\DocumentsController::class, 'getClassifyTasks'])
        ->name('documents.classify.tasks');
    Route::post('/documents/recognize/task/{id}', [\App\Http\Controllers\DocumentsController::class, 'getRecognizeTask'])
        ->name('documents.recognize.task.id');
    Route::post('/documents/replace', [\App\Http\Controllers\DocumentsController::class, 'replaceDocument'])
        ->name('documents.replace');
    Route::post('/documents/individuals/add', [\App\Http\Controllers\DocumentsController::class, 'addDocumentForIndividual'])
        ->name('documents.individuals.add');

    Route::post('/documents/recognize/taskkey', [\App\Http\Controllers\DocumentsController::class, 'getRecognizedDataByTaskKey'])
        ->name('documents.recognize.taskkey');

    Route::get('/individuals', [\App\Http\Controllers\IndividualsController::class, 'individualsView'])
        ->name('individuals.view');
    Route::get('/individuals/all', [\App\Http\Controllers\IndividualsController::class, 'getIndividuals']);
    Route::post('/individuals/create', [\App\Http\Controllers\IndividualsController::class, 'save'])
        ->name('individuals.create');
    Route::get('/individuals/{id}', [\App\Http\Controllers\IndividualsController::class, 'watchById'])
        ->name('individuals.id.view');
    Route::get('/individuals/get/{id}', [\App\Http\Controllers\IndividualsController::class, 'getIndividualById'])
        ->name('individuals.id');
    Route::post('/individuals/search', [\App\Http\Controllers\IndividualsController::class, 'search'])
        ->name('individuals.search');

    Route::put('/fields/update', [\App\Http\Controllers\DocumentsController::class, 'updateField'])
        ->name('fields.update');

    Route::put('/documents/restore', [\App\Http\Controllers\DocumentsController::class, 'restoreDocument'])
        ->name('documents.restore');

    Route::middleware(['not.worker'])
        ->group(function () {
        Route::get('/users', [\App\Http\Controllers\UsersController::class, 'usersList'])
            ->name('users.view');
        Route::get('/users/all', [\App\Http\Controllers\UsersController::class, 'getAllUsers'])
            ->name('users.all');
        Route::get('editor', [\App\Http\Controllers\EditorController::class, 'index'])
            ->name('editor');
        Route::post('/users', [\App\Http\Controllers\UsersController::class, 'createUser'])
            ->name('users.add');
        Route::delete('/users/{id}', [\App\Http\Controllers\UsersController::class, 'deleteUser'])
            ->name('users.delete');
        Route::put('/users/block/{id}', [\App\Http\Controllers\UsersController::class, 'changeUserBlockStatus'])
            ->name('users.block');
    });

    Route::middleware(['admin.or.developer'])->group(function () {
        Route::delete('/documents/{id}', [\App\Http\Controllers\DocumentsController::class, 'deleteDocument'])
            ->name('documents.delete.id');
    });

    Route::get('/users/{id}', [\App\Http\Controllers\UsersController::class, 'getUserById'])
        ->name('users.id');
});









