<?php

namespace App\Filament\Resources\ShipmentResource\Pages;

use App\Filament\Actions\PlanAction;
use App\Filament\Actions\SoftDeleteAction;
use App\Filament\Resources\ShipmentResource;
use Filament\Resources\Pages\EditRecord;

class EditShipment extends EditRecord
{
    protected static string $resource = ShipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            PlanAction::make(name: 'Préparer'),
            SoftDeleteAction::make(),
        ];
    }
}
