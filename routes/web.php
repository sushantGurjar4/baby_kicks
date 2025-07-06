<?php

use Illuminate\Support\Facades\Route;

// Authentication routes (login, register, etc)
Auth::routes();

Route::group(['middleware' => 'auth'], function() {
    // Show today's kicks (main listing)
    Route::get('/kicks', 'KickController@index')->name('kicks.index');
    
    // Show all-time kicks
    Route::get('/kicks/all', 'KickController@all')->name('kicks.all');

    // Statistics page
    Route::get('/kicks/stats', 'KickController@stats')->name('kicks.stats');
    
    // Store a new kick
    Route::post('/kicks', 'KickController@store')->name('kicks.store');
    
    // Soft-delete (mark inactive)
    Route::delete('/kicks/{kick}', 'KickController@destroy')->name('kicks.destroy');

    // Record baby birth date
    Route::post('/kicks/birth', 'KickController@recordBirth')->name('kicks.recordBirth');

    // Record last period date (LMP) for pregnancy calculations
    Route::post('/kicks/lmp', 'KickController@recordLmp')->name('kicks.recordLmp');
});

// Redirect root URL to today's kicks
Route::redirect('/', '/kicks');
