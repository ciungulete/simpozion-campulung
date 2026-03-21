<?php

namespace App\Filament\Resources\Registrations\Schemas;

use App\Enums\PaymentStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RegistrationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('uuid')
                    ->label('Referință')
                    ->disabled()
                    ->formatStateUsing(fn (?string $state) => $state ? strtoupper(substr($state, 0, 8)) : null),
                Select::make('payment_status')
                    ->label('Status plată')
                    ->options(PaymentStatus::class)
                    ->required(),
                TextInput::make('total_amount')
                    ->label('Total')
                    ->numeric()
                    ->disabled()
                    ->suffix('lei'),
                TextInput::make('paid_amount')
                    ->label('Sumă plătită')
                    ->numeric()
                    ->default(0)
                    ->suffix('lei')
                    ->helperText('Introduceți suma plătită efectiv. Diferența va apărea ca "Rest de plată" în tabel.'),
                Textarea::make('admin_notes')
                    ->label('Note admin')
                    ->rows(3)
                    ->placeholder('Note interne vizibile doar pentru admin...'),
            ]);
    }
}
