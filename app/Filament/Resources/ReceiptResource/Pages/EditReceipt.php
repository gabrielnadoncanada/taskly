<?php

namespace App\Filament\Resources\ReceiptResource\Pages;

use App\Filament\Actions\PlanAction;
use App\Filament\Actions\SoftDeleteAction;
use App\Filament\Resources\ReceiptResource;
use Filament\Resources\Pages\EditRecord;

class EditReceipt extends EditRecord
{
    protected static string $resource = ReceiptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            PlanAction::make(name: 'Recevoir'),
            SoftDeleteAction::make(),
        ];
    }
}
