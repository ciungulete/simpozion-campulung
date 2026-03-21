<?php

namespace App\Filament\Resources\Registrations\Pages;

use App\Enums\PaymentStatus;
use App\Filament\Resources\Registrations\RegistrationResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListRegistrations extends ListRecords
{
    protected static string $resource = RegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Toate'),
            'pending' => Tab::make('În așteptare')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('payment_status', PaymentStatus::Pending))
                ->icon('heroicon-o-clock'),
            'revolut' => Tab::make('Revolut')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('payment_status', PaymentStatus::Revolut))
                ->icon('heroicon-o-credit-card'),
            'bcr' => Tab::make('BCR')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('payment_status', PaymentStatus::Bcr))
                ->icon('heroicon-o-building-library'),
            'cash' => Tab::make('Cash')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('payment_status', PaymentStatus::Cash))
                ->icon('heroicon-o-banknotes'),
        ];
    }
}
