<?php

use App\Http\Middleware\SetLocale;
use App\Livewire\RegistrationForm;
use App\Models\Registration;
use Illuminate\Support\Facades\Route;

Route::middleware(SetLocale::class)->group(function () {
    Route::livewire('/', RegistrationForm::class)
        ->name('register')
        ->middleware('throttle:5,60');

    Route::get('/payment/{registration:uuid}', function (Registration $registration) {
        $registration->load('participants');
        $locale = session('locale', 'ro');

        return view('pages.payment', compact('registration', 'locale'));
    })->name('payment');
});
