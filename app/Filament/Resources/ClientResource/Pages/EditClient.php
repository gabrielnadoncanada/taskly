<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Actions\SoftDeleteAction;
use App\Filament\Resources\ClientResource;
use Filament\Resources\Pages\EditRecord;

class EditClient extends EditRecord
{
    protected static string $resource = ClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            SoftDeleteAction::make(),
        ];
    }
}
