<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {

    if (auth()->check()) {
        return redirect('/home');
    }else{
        return view('welcome');
    }
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/tasks', [App\Http\Controllers\TaskController::class, 'index'])->name('tasks.index');
Route::get('/projects', [App\Http\Controllers\ProjectController::class, 'index'])->name('projects.index');

Route::get('/tasks/{projectId}', [App\Http\Controllers\TaskController::class, 'show'])->name('tasks.show');
Route::post('/tasks/new', [App\Http\Controllers\TaskController::class, 'store'])->name('tasks.store');
Route::patch('/tasks/{task}/toggle', [App\Http\Controllers\TaskController::class, 'toggle'])->name('tasks.toggle');
Route::get('/tasks/{task}/edit', [App\Http\Controllers\TaskController::class, 'edit'])->name('tasks.edit');
Route::put('/tasks/{task}/update', [App\Http\Controllers\TaskController::class, 'update'])->name('tasks.update');
Route::delete('/tasks/{task}/delete', [App\Http\Controllers\TaskController::class, 'destroy'])->name('tasks.destroy');
Route::get('/tasks/{task}/view', [App\Http\Controllers\TaskController::class, 'view'])->name('tasks.view');



Route::post('/projects/new', [App\Http\Controllers\ProjectController::class, 'store'])->name('projects.store');
Route::get('/projects/show', [App\Http\Controllers\ProjectController::class, 'show'])->name('projects.show');
Route::get('/projects/{project}/edit', [App\Http\Controllers\ProjectController::class, 'edit'])->name('projects.edit');
Route::put('/projects/{project}/update', [App\Http\Controllers\ProjectController::class, 'update'])->name('projects.update');
Route::delete('/projects/{project}/delete', [App\Http\Controllers\ProjectController::class, 'destroy'])->name('projects.destroy');
Route::get('/projects/{project}/tasks/count', [App\Http\Controllers\ProjectController::class, 'countTasks'])->name('projects.countTasks');
Route::get('/projects/{project}/view', [App\Http\Controllers\ProjectController::class, 'view'])->name('projects.view');



