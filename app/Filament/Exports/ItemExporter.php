<?php

namespace App\Filament\Exports;

use App\Models\Item;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ItemExporter extends Exporter
{
    protected static ?string $model = Item::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make(Item::TITLE),
            ExportColumn::make(Item::DESCRIPTION),
            ExportColumn::make(Item::WEIGHT),
            ExportColumn::make(Item::MEDIA),
            ExportColumn::make(Item::DEFAULT_PRICE),
            ExportColumn::make(Item::CATEGORY_ID),
            ExportColumn::make(Item::SKU),
            ExportColumn::make(Item::TENANT_ID),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your item export has completed and '.number_format($export->successful_rows).' '.str('row')->plural($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
