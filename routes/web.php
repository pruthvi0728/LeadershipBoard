<?php

use App\Http\Controllers\UserActivitiesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/user-activities', [UserActivitiesController::class, 'index'])->name('user-activities.index');
Route::post('/user-activities/recalculate', [UserActivitiesController::class, 'recalculate'])->name('user-activities.recalculate');
