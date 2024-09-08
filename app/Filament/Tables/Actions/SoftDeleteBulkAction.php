<?php

namespace App\Filament\Tables\Actions;

use Filament\Tables\Actions\DeleteBulkAction;
use Str;

class SoftDeleteBulkAction extends DeleteBulkAction
{
    private string $verb = 'Archiver';

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(fn (): string => $this->verb.' les '.Str::of($this->getPluralModelLabel())->lower());

        $this->modalHeading(fn (): string => $this->verb
            .' les '.Str::of($this->getPluralModelLabel())->lower()
            .' sélectionnés'
        );

        $this->modalSubmitActionLabel(fn (): string => $this->verb);

        $this->successNotificationTitle('Fait!');

    }

    public function verb(?string $verb): static
    {
        $this->verb = $verb;

        return $this;
    }
}
