<?php

namespace App\Filament\Resources\ShipmentResource\Pages;

use App\Filament\Resources\ShipmentResource;
use App\Models\Shipment;
use Filament\Resources\Pages\CreateRecord;

class CreateShipment extends CreateRecord
{
    protected static string $resource = ShipmentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data[Shipment::DATE] = $data[Shipment::EXPECTED_DATE];

        return $data;
    }
}
