<?php

namespace App\Filament\Resources\Registrations\Tables;

use App\Enums\PaymentStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RegistrationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('uuid')
                    ->label('Referință')
                    ->formatStateUsing(fn (string $state) => strtoupper(substr($state, 0, 8)))
                    ->searchable(),
                TextColumn::make('participants_count')
                    ->counts('participants')
                    ->label('Participanți')
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->label('Total')
                    ->formatStateUsing(fn (int $state) => number_format($state, 0, ',', '.').' lei')
                    ->sortable(),
                TextColumn::make('payment_status')
                    ->label('Status plată')
                    ->badge(),
                TextColumn::make('admin_notes')
                    ->label('Note')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Data înregistrării')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('payment_status')
                    ->options(PaymentStatus::class)
                    ->label('Status plată'),
            ])
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
