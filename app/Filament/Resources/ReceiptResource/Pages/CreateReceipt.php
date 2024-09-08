<?php

namespace App\Filament\Resources\ReceiptResource\Pages;

use App\Filament\Resources\ReceiptResource;
use App\Models\Receipt;
use Filament\Resources\Pages\CreateRecord;

class CreateReceipt extends CreateRecord
{
    protected static string $resource = ReceiptResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data[Receipt::DATE] = $data[Receipt::EXPECTED_DATE];

        return $data;
    }
}
