<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/check-user', function () {
    $user = \App\Models\User::where('email', 'admin@test.com')->first();
    return $user ? 'User exists: ' . $user->name : 'User not found';
});