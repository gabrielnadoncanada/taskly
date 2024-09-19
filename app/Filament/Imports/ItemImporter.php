<?php

namespace App\Filament\Imports;

use App\Models\Item;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class ItemImporter extends Importer
{
    protected static ?string $model = Item::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make(Item::TITLE)
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make(Item::DESCRIPTION)
                ->rules(['max:255']),
            ImportColumn::make(Item::WEIGHT)
                ->ignoreBlankState()
                ->numeric(),
            ImportColumn::make(Item::MEDIA)
                ->ignoreBlankState()
                ->rules(['max:255']),
            ImportColumn::make(Item::DEFAULT_PRICE)
                ->numeric()
                ->ignoreBlankState(),
            ImportColumn::make('category')
                ->relationship(),
            ImportColumn::make(Item::SKU)
                ->ignoreBlankState()
                ->requiredMapping()
                ->label('SKU')
                ->rules(['max:255']),
            ImportColumn::make(Item::TENANT_ID)
                ->ignoreBlankState()
                ->requiredMapping()
                ->relationship(),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your item import has completed and '.number_format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
