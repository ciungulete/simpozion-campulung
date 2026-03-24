<?php

namespace App\Filament\Resources\Registrations\Schemas;

use App\Enums\Degree;
use App\Enums\PaymentStatus;
use App\Enums\Prefix;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RegistrationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detalii înregistrare')
                    ->schema([
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
                    ]),

                Section::make('Participanți')
                    ->schema([
                        Repeater::make('participants')
                            ->relationship()
                            ->label('')
                            ->schema([
                                Select::make('prefix')
                                    ->label('Prefix')
                                    ->options(Prefix::class)
                                    ->disabled(),
                                TextInput::make('full_name')
                                    ->label('Nume și Prenume')
                                    ->disabled(),
                                Select::make('degree')
                                    ->label('Grad')
                                    ->options(Degree::class)
                                    ->disabled(),
                                TextInput::make('dignity')
                                    ->label('Demnitate')
                                    ->disabled(),
                                TextInput::make('lodge_name')
                                    ->label('Loja')
                                    ->disabled(),
                                TextInput::make('lodge_number')
                                    ->label('Nr.')
                                    ->disabled(),
                                TextInput::make('orient')
                                    ->label('Orient')
                                    ->disabled(),
                                TextInput::make('email')
                                    ->label('Email')
                                    ->disabled(),
                                TextInput::make('phone')
                                    ->label('Telefon')
                                    ->disabled(),
                                TextInput::make('friday_dinner_count')
                                    ->label('Cină vineri')
                                    ->disabled()
                                    ->suffix('pers'),
                                TextInput::make('symposium_lunch_count')
                                    ->label('Simpozion + Prânz')
                                    ->disabled()
                                    ->suffix('pers'),
                                TextInput::make('companion_lunch_count')
                                    ->label('Prânz (însoțitoare)')
                                    ->disabled()
                                    ->suffix('pers'),
                                Toggle::make('ritual_participation')
                                    ->label('Ținută rituală')
                                    ->disabled(),
                                TextInput::make('ball_count')
                                    ->label('Bal')
                                    ->disabled()
                                    ->suffix('pers'),
                                Textarea::make('observations')
                                    ->label('Observații')
                                    ->disabled()
                                    ->columnSpanFull(),
                            ])
                            ->columns(3)
                            ->deletable(false)
                            ->addable(false)
                            ->reorderable(false),
                    ]),
            ]);
    }
}
