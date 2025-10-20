<?php

use Illuminate\Support\Facades\Route;

Route::get('/whales', function () {
    return view('whales');
});
