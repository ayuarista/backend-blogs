<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/manual-login', function () {
    $user = \App\Models\User::where('email', 'admin@test.com')->first();

    if ($user) {
        Auth::login($user);
        return redirect('/admin');
    }

    return 'User not found';
});

Route::get('/debug-filament', function () {
    $user = \App\Models\User::where('email', 'admin@test.com')->first();

    $debug = [
        'user_exists' => $user ? true : false,
        'user_name' => $user?->name,
        'auth_guard' => config('filament.auth.guard'),
        'panel_exists' => class_exists('App\Providers\Filament\AdminPanelProvider'),
    ];

    if ($user) {
        try {
            $panel = filament('admin');
            $debug['panel_found'] = true;
            $debug['can_access_panel'] = $user->canAccessPanel($panel);
        } catch (\Exception $e) {
            $debug['panel_error'] = $e->getMessage();
        }
    }

    return response()->json($debug);
});