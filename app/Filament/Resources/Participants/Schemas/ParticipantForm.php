<?php

namespace App\Filament\Resources\Participants\Schemas;

use App\Enums\Degree;
use App\Enums\Prefix;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ParticipantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('registration_id')
                    ->relationship('registration', 'id')
                    ->required(),
                Select::make('prefix')
                    ->options(Prefix::class)
                    ->required(),
                TextInput::make('full_name')
                    ->required(),
                Select::make('degree')
                    ->options(Degree::class)
                    ->required(),
                TextInput::make('dignity')
                    ->required(),
                TextInput::make('lodge_name')
                    ->required(),
                TextInput::make('lodge_number')
                    ->required()
                    ->numeric(),
                TextInput::make('orient')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('phone')
                    ->tel()
                    ->required(),
                TextInput::make('friday_dinner_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('symposium_lunch_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('ritual_participation')
                    ->required(),
                TextInput::make('ball_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                Textarea::make('observations')
                    ->columnSpanFull(),
            ]);
    }
}
