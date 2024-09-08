<?php

namespace App\Filament\Tables\Actions;

use App\Enums\ItemStatus;
use App\Models\Item;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\AssociateAction;
use Illuminate\Database\Eloquent\Model;

class AssociateShipmentItemsAction extends AssociateAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->after(function () {
            $tableRecords = $this->getTable()->getRecords();
            if ($tableRecords->isNotEmpty()) {
                $tableRecords
                    ->filter(fn (Model $record) => $record->status === ItemStatus::STORED)
                    ->each(fn (Model $record) => $record->update([Item::STATUS => ItemStatus::AWAITING_SHIPMENT]));
            }
        })->recordSelect(
            function (Select $select) {
                $select->options(
                    Item::query()
                        ->where(Item::STATUS, ItemStatus::STORED)
                        ->get()
                        ->mapWithKeys(fn (Item $item) => [$item->getKey() => $item->{Item::DISPLAY_RECEIPT_ITEM_NUMBER}])
                        ->toArray()
                );

                return $select;
            }
        )->multiple()->preloadRecordSelect();
    }
}
