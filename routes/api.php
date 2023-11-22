<?php

use App\Classes\Modifiers\Category;
use App\Classes\Modifiers\Modifiers;
use App\Services\CombatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/test', function (Request $request) {
    
    $state =  CombatService::testBattle();

    return ['state' => $state];
})->name('test');
