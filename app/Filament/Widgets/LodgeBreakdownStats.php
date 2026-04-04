<?php

namespace App\Filament\Widgets;

use App\Models\Participant;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LodgeBreakdownStats extends TableWidget
{
    protected static ?string $heading = 'Loji reprezentate';

    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Participant::query()
                    ->fromSub(
                        Participant::query()
                            ->selectRaw('lodge_number as id, lodge_number, MAX(lodge_name) as lodge_name, COUNT(*) as participants_count')
                            ->groupBy('lodge_number'),
                        'participants'
                    )
            )
            ->defaultSort('participants_count', 'desc')
            ->columns([
                TextColumn::make('lodge_number')
                    ->label('Nr. Lojă')
                    ->sortable(),
                TextColumn::make('lodge_name')
                    ->label('Nume Lojă')
                    ->searchable(),
                TextColumn::make('participants_count')
                    ->label('Participanți')
                    ->sortable()
                    ->alignEnd(),
            ])
            ->paginated(false);
    }
}
