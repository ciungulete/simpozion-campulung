<?php

namespace App\Filament\Widgets;

use App\Enums\Prefix;
use App\Models\Participant;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PrefixBreakdownStats extends StatsOverviewWidget
{
    protected ?string $heading = 'Distribuție pe Prefix';

    protected static ?int $sort = 3;

    protected function getStats(): array
    {
        $stats = [];

        foreach (Prefix::cases() as $prefix) {
            $count = Participant::query()
                ->where('prefix', $prefix)
                ->count();

            $stats[] = Stat::make($prefix->value, $count);
        }

        return $stats;
    }
}
