<?php

namespace App\Filament\Resources\Plansas;

use App\Filament\Resources\Plansas\Pages\CreatePlansa;
use App\Filament\Resources\Plansas\Pages\EditPlansa;
use App\Filament\Resources\Plansas\Pages\ListPlansas;
use App\Filament\Resources\Plansas\Schemas\PlansaForm;
use App\Filament\Resources\Plansas\Tables\PlansasTable;
use App\Models\Plansa;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PlansaResource extends Resource
{
    protected static ?string $model = Plansa::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $navigationLabel = 'Planșe';

    protected static ?string $modelLabel = 'Planșă';

    protected static ?string $pluralModelLabel = 'Planșe';

    protected static ?string $recordTitleAttribute = 'title';

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
        return PlansaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PlansasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPlansas::route('/'),
            'create' => CreatePlansa::route('/create'),
            'edit' => EditPlansa::route('/{record}/edit'),
        ];
    }
}
