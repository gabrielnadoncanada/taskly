<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Actions\SoftDeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTask extends EditRecord
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            SoftDeleteAction::make(),
        ];
    }
}
