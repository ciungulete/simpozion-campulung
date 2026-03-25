<?php

namespace App\Filament\Resources\Accommodations\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AccommodationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detalii cazare')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nume')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        FileUpload::make('image')
                            ->label('Imagine')
                            ->image()
                            ->disk('public')
                            ->directory('accommodations')
                            ->visibility('public')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->imageResizeTargetWidth('800')
                            ->imageResizeTargetHeight('450')
                            ->columnSpanFull(),
                        TextInput::make('website')
                            ->label('Website')
                            ->url()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-globe-alt'),
                        TextInput::make('phone')
                            ->label('Telefon')
                            ->tel()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-phone'),
                        Textarea::make('pricing')
                            ->label('Prețuri camere')
                            ->helperText('Descrieți prețurile camerelor (ex: Single - 200 lei/noapte, Double - 350 lei/noapte)')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                Section::make('Setări')
                    ->columns(2)
                    ->schema([
                        TextInput::make('sort_order')
                            ->label('Ordine afișare')
                            ->numeric()
                            ->default(0)
                            ->helperText('Număr mai mic = afișat mai sus'),
                        Toggle::make('is_active')
                            ->label('Activ')
                            ->default(true)
                            ->helperText('Dezactivați pentru a ascunde cazarea de pe pagina de plată'),
                    ]),
            ]);
    }
}
