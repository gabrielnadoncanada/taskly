<?php

namespace App\Filament\Resources\ItemResource\Pages;

use App\Filament\Exports\ItemExporter;
use App\Filament\Imports\ItemImporter;
use App\Filament\Resources\ItemResource;
use Filament\Actions;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListItems extends ListRecords
{
    protected static string $resource = ItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            Actions\ExportAction::make()
                ->exporter(ItemExporter::class),
            ImportAction::make()
                ->importer(ItemImporter::class),
        ];
    }
}
