<?php

namespace App\Filament\Resources\Plansas\Tables;

use App\Models\Plansa;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PlansasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Titlu')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('uuid')
                    ->label('Link public')
                    ->formatStateUsing(fn (Plansa $record): string => $record->publicUrl())
                    ->url(fn (Plansa $record): string => $record->publicUrl(), shouldOpenInNewTab: true)
                    ->limit(50)
                    ->copyable()
                    ->copyableState(fn (Plansa $record): string => $record->publicUrl())
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Creat')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                Action::make('view_pdf')
                    ->label('PDF')
                    ->icon(Heroicon::OutlinedDocument)
                    ->color('gray')
                    ->url(fn (Plansa $record): string => $record->publicUrl(), shouldOpenInNewTab: true),
                Action::make('view_qr')
                    ->label('QR')
                    ->icon(Heroicon::OutlinedQrCode)
                    ->color('gray')
                    ->url(fn (Plansa $record): string => route('plansa.qr', $record->uuid), shouldOpenInNewTab: true),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
