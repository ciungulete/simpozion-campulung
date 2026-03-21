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

        $paidTotal = Registration::query()
            ->where('payment_status', '!=', PaymentStatus::Pending)
            ->sum('total_amount');

        $pendingTotal = Registration::query()
            ->where('payment_status', PaymentStatus::Pending)
            ->sum('total_amount');

        $paidCount = Registration::query()
            ->where('payment_status', '!=', PaymentStatus::Pending)
            ->count();

        $pendingCount = Registration::query()
            ->where('payment_status', PaymentStatus::Pending)
            ->count();

        $byMethod = [];
        foreach ([PaymentStatus::Revolut, PaymentStatus::Bcr, PaymentStatus::Cash] as $status) {
            $count = Registration::query()->where('payment_status', $status)->count();
            if ($count > 0) {
                $byMethod[] = $status->label().': '.$count;
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
