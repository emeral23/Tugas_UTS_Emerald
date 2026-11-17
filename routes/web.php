<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnotasiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HabitatController;
use App\Http\Controllers\RekamanController;
use App\Http\Controllers\TitikPerekamanController;

Route::get('/', [DashboardController::class, 'index'])->name('home');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('rekaman', RekamanController::class);
Route::resource('habitats', HabitatController::class)->except('show');
Route::resource('titik-perekaman', TitikPerekamanController::class)
    ->parameters(['titik-perekaman' => 'titikPerekaman'])
    ->except('show');
Route::resource('anotasi', AnotasiController::class)->except('show');
