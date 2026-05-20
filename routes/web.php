<?php

use App\Http\Middleware\SetLocale;
use App\Livewire\RegistrationForm;
use App\Models\Accommodation;
use App\Models\Plansa;
use App\Models\Registration;
use App\Support\QrCodeGenerator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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

Route::get('/plansa/{plansa:uuid}', function (Plansa $plansa) {
    abort_unless(Storage::disk('public')->exists($plansa->file_path), 404);

    return Storage::disk('public')->response(
        $plansa->file_path,
        $plansa->title.'.pdf',
        ['Content-Type' => 'application/pdf'],
        'inline'
    );
})->name('plansa.show');

Route::get('/plansa/{plansa:uuid}/qr', function (Plansa $plansa, QrCodeGenerator $generator) {
    $png = $generator->png(route('plansa.show', $plansa->uuid));

    return response($png, 200, [
        'Content-Type' => 'image/png',
        'Content-Disposition' => 'inline; filename="plansa-'.$plansa->uuid.'.png"',
    ]);
})->name('plansa.qr');
