<?php

namespace App\Filament\Resources\Accommodations;

use App\Filament\Resources\Accommodations\Pages\CreateAccommodation;
use App\Filament\Resources\Accommodations\Pages\EditAccommodation;
use App\Filament\Resources\Accommodations\Pages\ListAccommodations;
use App\Filament\Resources\Accommodations\Schemas\AccommodationForm;
use App\Filament\Resources\Accommodations\Tables\AccommodationsTable;
use App\Models\Accommodation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AccommodationResource extends Resource
{
    protected static ?string $model = Accommodation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice;

    protected static ?string $navigationLabel = 'Cazare';

    protected static ?string $modelLabel = 'Cazare';

    protected static ?string $pluralModelLabel = 'Cazări';

    public static function canCreate(): bool
    {
        return auth()->id() === 1 || auth()->id() === 2;
    }

    public static function canEdit($record): bool
    {
        return auth()->id() === 1 || auth()->id() === 2;
    }

    public static function canDelete($record): bool
    {
        return auth()->id() === 1 || auth()->id() === 2;
    }

    public static function form(Schema $schema): Schema
    {
        return AccommodationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AccommodationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAccommodations::route('/'),
            'create' => CreateAccommodation::route('/create'),
            'edit' => EditAccommodation::route('/{record}/edit'),
        ];
    }
}
