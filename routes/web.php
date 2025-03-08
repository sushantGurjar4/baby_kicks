<?php

use Illuminate\Support\Facades\Route;

// If using Laravel 5.5, you must use string-based references.
// Also ensure you only have Auth::routes() once.
Auth::routes();

Route::group(['middleware' => 'auth'], function() {
    // Show today's kicks (main listing)
    Route::get('/kicks', 'KickController@index')->name('kicks.index');
    
    // Show all-time kicks
    Route::get('/kicks/all', 'KickController@all')->name('kicks.all');

    // Statistics page (new)
    Route::get('/kicks/stats', 'KickController@stats')->name('kicks.stats');
    
    // Store a new kick
    Route::post('/kicks', 'KickController@store')->name('kicks.store');
    
    // Soft-delete (mark inactive)
    Route::delete('/kicks/{kick}', 'KickController@destroy')->name('kicks.destroy');

    //Record birth
    Route::post('/kicks/birth', 'KickController@recordBirth')->name('kicks.recordBirth');
});

// Redirect '/' to '/kicks'
Route::get('/', function () {
    return redirect()->route('kicks.index');
});
