<?php

namespace App\Filament\Widgets;

use App\Models\Participant;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LodgeBreakdownStats extends StatsOverviewWidget
{
    protected ?string $heading = 'Loji reprezentate';

    protected static ?int $sort = 5;

    protected function getStats(): array
    {
        $distinctLodges = Participant::query()
            ->distinct()
            ->count('lodge_number');

        $topLodges = Participant::query()
            ->selectRaw('lodge_name, lodge_number, COUNT(*) as count')
            ->groupBy('lodge_name', 'lodge_number')
            ->orderByDesc('count')
            ->limit(3)
            ->get();

        $stats = [
            Stat::make('Total Loji', $distinctLodges)
                ->description('Loji distincte reprezentate'),
        ];

        foreach ($topLodges as $lodge) {
            $stats[] = Stat::make(
                $lodge->lodge_name.' nr. '.$lodge->lodge_number,
                $lodge->count.' participanți',
            );
        }

        return $stats;
    }
}
