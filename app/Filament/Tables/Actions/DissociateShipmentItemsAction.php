<?php

namespace App\Filament\Tables\Actions;

use App\Enums\ItemStatus;
use App\Models\Item;
use Filament\Tables\Actions\DissociateAction;

class DissociateShipmentItemsAction extends DissociateAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->after(fn ($action) => $action->getRecord()->update([
            Item::STATUS => ItemStatus::STORED,
        ]));
    }
}
