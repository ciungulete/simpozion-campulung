<?php

namespace App\Filament\Resources\Plansas\Pages;

use App\Filament\Resources\Plansas\PlansaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPlansas extends ListRecords
{
    protected static string $resource = PlansaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
