<?php

namespace App\Filament\Tables\Actions;

use App\Enums\ItemStatus;
use App\Models\Item;
use Filament\Tables\Actions\DissociateBulkAction;

class DissociateBulkShipmentItemsAction extends DissociateBulkAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->after(fn ($livewire) => $livewire->getSelectedTableRecords()->each(
            fn ($record) => $record->update([Item::STATUS => ItemStatus::STORED])
        ));
    }
}
