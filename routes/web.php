<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-panel', function () {
    $user = \App\Models\User::where('email', 'admin@example.com')->first();

    if ($user) {
        $panel = filament('admin');
        $canAccess = $user->canAccessPanel($panel);
        return "User: {$user->name}, Can access: " . ($canAccess ? 'YES' : 'NO');
    }

    return 'User not found';
});