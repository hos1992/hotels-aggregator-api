<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('get-best-hotels', 'HotelsController@getBestHotels');
Route::get('get-top-hotels', 'HotelsController@getTopHotels');
Route::get('hotels', 'HotelsController@hotels');
