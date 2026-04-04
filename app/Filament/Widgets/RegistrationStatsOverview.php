<?php

namespace App\Filament\Widgets;

use App\Enums\PaymentStatus;
use App\Models\Participant;
use App\Models\Registration;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RegistrationStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalRegistrations = Registration::query()->count();
        $totalParticipants = Participant::query()->count();

        $paidTotal = Registration::query()->sum('paid_amount');

        $pendingTotal = Registration::query()
            ->selectRaw('SUM(total_amount - paid_amount) as remaining')
            ->value('remaining') ?? 0;

        $paidCount = Registration::query()
            ->where('payment_status', '!=', PaymentStatus::Pending)
            ->count();

        $pendingCount = Registration::query()
            ->where('payment_status', PaymentStatus::Pending)
            ->count();

        $byMethod = [];
        foreach ([PaymentStatus::Revolut, PaymentStatus::Bcr, PaymentStatus::Cash] as $status) {
            $sum = Registration::query()->where('payment_status', $status)->sum('paid_amount');
            if ($sum > 0) {
                $byMethod[] = $status->label().': '.number_format($sum, 0, ',', '.').' lei';
            }
        }

        return [
            Stat::make('Înregistrări', $totalRegistrations)
                ->description("{$paidCount} plătite, {$pendingCount} în așteptare"),
            Stat::make('Participanți', $totalParticipants),
            Stat::make('Încasat', number_format($paidTotal, 0, ',', '.').' lei')
                ->description($byMethod ? implode(' | ', $byMethod) : 'Nicio plată')
                ->color('success'),
            Stat::make('De încasat', number_format($pendingTotal, 0, ',', '.').' lei')
                ->description($pendingCount.' înregistrări în așteptare')
                ->color('warning'),
        ];
    }
}
