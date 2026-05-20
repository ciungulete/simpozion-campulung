<?php

namespace App\Filament\Resources\Plansas\Pages;

use App\Filament\Resources\Plansas\PlansaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPlansa extends EditRecord
{
    protected static string $resource = PlansaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
