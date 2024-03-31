<?php

use App\Http\Controllers\ContactController;
use App\Livewire\ChatComponent;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')
    ->resource('contacts', ContactController::class)->except('show');

Route::get('/chat', ChatComponent::class)
    ->middleware('auth')
    ->name('chat.index');
