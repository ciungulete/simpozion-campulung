<?php

namespace App\Filament\Widgets;

use App\Enums\Degree;
use App\Models\Participant;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DegreeBreakdownStats extends StatsOverviewWidget
{
    protected ?string $heading = 'Distribuție pe Grad';

    protected static ?int $sort = 4;

    protected function getStats(): array
    {
        $stats = [];

        foreach (Degree::cases() as $degree) {
            $count = Participant::query()
                ->where('degree', $degree)
                ->count();

            $stats[] = Stat::make($degree->label(), $count);
        }

        return $stats;
    }
}
