<?php

use App\Livewire\RegistrationForm;
use App\Models\Registration;
use Illuminate\Support\Facades\Route;

Route::livewire('/', RegistrationForm::class)
    ->name('register')
    ->middleware('throttle:5,60');

Route::get('/payment/{registration:uuid}', function (Registration $registration) {
    $registration->load('participants');

    return view('pages.payment', compact('registration'));
})->name('payment');
