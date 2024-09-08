<?php

namespace App\Filament\Resources\WarehouseResource\Pages;

use App\Filament\Actions\SoftDeleteAction;
use App\Filament\Resources\WarehouseResource;
use Filament\Resources\Pages\EditRecord;

class EditWarehouse extends EditRecord
{
    protected static string $resource = WarehouseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            SoftDeleteAction::make(),
        ];
    }
}
