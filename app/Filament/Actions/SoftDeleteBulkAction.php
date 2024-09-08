<?php

namespace App\Filament\Actions;

use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Support\Str;

class SoftDeleteBulkAction extends DeleteBulkAction
{
    private string $verb = 'Désactiver';

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(fn (): string => $this->verb.' les '.Str::of($this->getPluralModelLabel())->lower());

        $this->modalHeading(
            fn (): string => $this->verb
            .' les '.Str::of($this->getPluralModelLabel())->lower()
            .' sélectionné(e)s'
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
