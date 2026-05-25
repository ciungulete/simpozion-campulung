<?php

namespace App\Filament\Resources\Participants\Tables;

use App\Enums\PaymentStatus;
use App\Filament\Resources\Participants\Actions\ExportParticipantsAction;
use App\Filament\Resources\Registrations\RegistrationResource;
use App\Models\Participant;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ParticipantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with('registration'))
            ->headerActions([
//                ExportParticipantsAction::make(),
            ])
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('registration.uuid')
                    ->label('Referință')
                    ->formatStateUsing(fn (string $state) => strtoupper(substr($state, 0, 8)))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('prefix')
                    ->badge()
                    ->searchable(),
                TextColumn::make('full_name')
                    ->label('Nume')
                    ->searchable()
                    ->url(fn (Participant $record): ?string => $record->registration
                        ? RegistrationResource::getUrl('edit', ['record' => $record->registration])
                        : null),
                TextColumn::make('degree')
                    ->label('Grad')
                    ->badge(),
                TextColumn::make('lodge_name')
                    ->label('Loja')
                    ->searchable(),
                TextColumn::make('lodge_number')
                    ->label('Nr.')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('orient')
                    ->searchable(),
                TextColumn::make('email')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('phone')
                    ->label('Telefon')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('friday_dinner_count')
                    ->label('V')
                    ->sortable(),
                TextColumn::make('symposium_lunch_count')
                    ->label('Si')
                    ->sortable(),
                TextColumn::make('companion_lunch_count')
                    ->label('P')
                    ->sortable(),
                IconColumn::make('ritual_participation')
                    ->label('Ritual')
                    ->boolean(),
                TextColumn::make('ball_count')
                    ->label('Bal')
                    ->sortable(),
                TextColumn::make('remaining_due')
                    ->label('Rest')
                    ->badge()
                    ->getStateUsing(fn (Participant $record): int => $record->registration?->remainingAmount() ?? 0)
                    ->formatStateUsing(fn (int $state) => number_format($state, 0, ',', '.').' lei')
                    ->color(fn (int $state): string => $state === 0 ? 'success' : 'danger'),
                TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('payment_status')
                    ->label('Status plată')
                    ->multiple()
                    ->options(collect(PaymentStatus::cases())
                        ->mapWithKeys(fn (PaymentStatus $status) => [$status->value => $status->label()])
                        ->all())
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when($data['values'] ?? [], fn (Builder $query, array $values) => $query
                            ->whereHas('registration', fn (Builder $q) => $q->whereIn('payment_status', $values)))),
                SelectFilter::make('lodge_number')
                    ->label('Nr. Loja')
                    ->multiple()
                    ->searchable()
                    ->options(fn (): array => Participant::query()
                        ->whereNotNull('lodge_number')
                        ->distinct()
                        ->orderBy('lodge_number')
                        ->pluck('lodge_number', 'lodge_number')
                        ->all()),
                SelectFilter::make('orient')
                    ->label('Orient')
                    ->multiple()
                    ->searchable()
                    ->options(fn (): array => Participant::query()
                        ->whereNotNull('orient')
                        ->where('orient', '!=', '')
                        ->distinct()
                        ->orderBy('orient')
                        ->pluck('orient', 'orient')
                        ->all()),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(2)
            ->paginated([25, 50, 100, 200])
            ->defaultPaginationPageOption(50)
            ->striped()
            ->recordActions([]);
    }
}
