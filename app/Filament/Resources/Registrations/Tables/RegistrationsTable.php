<?php

namespace App\Filament\Resources\Registrations\Tables;

use App\Enums\PaymentStatus;
use App\Models\Registration;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
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
                TextColumn::make('paid_amount')
                    ->label('Plătit')
                    ->formatStateUsing(fn (int $state) => number_format($state, 0, ',', '.').' lei')
                    ->sortable(),
                TextColumn::make('remaining')
                    ->label('Rest de plată')
                    ->getStateUsing(fn (Registration $record) => $record->remainingAmount())
                    ->formatStateUsing(fn (int $state) => number_format($state, 0, ',', '.').' lei')
                    ->color(fn (int $state) => $state > 0 ? 'danger' : 'success')
                    ->weight(fn (int $state) => $state > 0 ? 'bold' : null),
                TextColumn::make('payment_status')
                    ->label('Status plată')
                    ->badge()
                    ->color(fn (PaymentStatus $state) => $state->color()),
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
