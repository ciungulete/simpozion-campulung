<?php

use App\Http\Middleware\SetLocale;
use App\Livewire\RegistrationForm;
use App\Models\Accommodation;
use App\Models\Registration;
use Illuminate\Support\Facades\Route;

Route::middleware(SetLocale::class)->group(function () {
    Route::livewire('/', RegistrationForm::class)
        ->name('register');

    Route::get('/payment/{registration:uuid}', function (Registration $registration) {
        $registration->load('participants');
        $locale = session('locale', 'ro');
        $accommodations = Accommodation::query()->active()->orderBy('sort_order')->get();

        return view('pages.payment', compact('registration', 'locale', 'accommodations'));
    })->name('payment');
});
