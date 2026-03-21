<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class RegistrationSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Setări Înregistrare';

    protected static ?string $title = 'Setări Înregistrare';

    protected static ?int $navigationSort = 98;

    protected string $view = 'filament.pages.registration-settings';

    public static function canAccess(): bool
    {
        return auth()->id() === 1;
    }

    public function isRegistrationOpen(): bool
    {
        return Setting::registrationOpen();
    }

    public function toggleRegistration(): void
    {
        $currentlyOpen = Setting::registrationOpen();
        Setting::set('registration_open', $currentlyOpen ? '0' : '1');

        $status = $currentlyOpen ? 'închise' : 'deschise';

        Notification::make()
            ->title("Înregistrările au fost {$status}!")
            ->success()
            ->send();
    }
}
