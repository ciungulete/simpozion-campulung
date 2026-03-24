<?php

namespace App\Filament\Widgets;

use App\Models\Participant;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EventParticipationStats extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        return [
            Stat::make('Cină vineri', Participant::query()->sum('friday_dinner_count').' persoane'),
            Stat::make('Simpozion + Prânz', Participant::query()->sum('symposium_lunch_count').' persoane'),
            Stat::make('Prânz (însoțitoare)', Participant::query()->sum('companion_lunch_count').' persoane'),
            Stat::make('Ținută rituală', Participant::query()->where('ritual_participation', true)->count().' participanți'),
            Stat::make('Bal', Participant::query()->sum('ball_count').' persoane'),
        ];
    }
}
