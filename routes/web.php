<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KickController;

// Authentication routes are automatically registered by make:auth
Auth::routes();

// Only authenticated users can access these routes:
Route::group(['middleware' => 'auth'], function() {
    // Show all kicks
    Route::get('/kicks', 'KickController@index')->name('kicks.index');

    // Store a new kick
    Route::post('/kicks', 'KickController@store')->name('kicks.store');

    Route::delete('/kicks/{kick}', 'KickController@destroy')->name('kicks.destroy');
});

// (Optional) You can also set '/' to redirect to '/kicks', if you want:
Route::get('/', function () {
    return redirect()->route('kicks.index');
});
    
Auth::routes();