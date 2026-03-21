<?php

namespace App\Filament\Resources\Participants\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ParticipantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('registration.uuid')
                    ->label('Referință')
                    ->formatStateUsing(fn (string $state) => strtoupper(substr($state, 0, 8)))
                    ->searchable(),
                TextColumn::make('prefix')
                    ->badge()
                    ->searchable(),
                TextColumn::make('full_name')
                    ->label('Nume')
                    ->searchable(),
                TextColumn::make('degree')
                    ->label('Grad')
                    ->badge(),
                TextColumn::make('lodge_name')
                    ->label('Loja')
                    ->searchable(),
                TextColumn::make('lodge_number')
                    ->label('Nr.')
                    ->sortable(),
                TextColumn::make('orient')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('phone')
                    ->label('Telefon')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('friday_dinner_count')
                    ->label('Cină')
                    ->sortable(),
                TextColumn::make('symposium_lunch_count')
                    ->label('Simpozion')
                    ->sortable(),
                IconColumn::make('ritual_participation')
                    ->label('Ritual')
                    ->boolean(),
                TextColumn::make('ball_count')
                    ->label('Bal')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
