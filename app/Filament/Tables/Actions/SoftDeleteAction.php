<?php

namespace App\Filament\Tables\Actions;

use Filament\Tables\Actions\DeleteAction;

class SoftDeleteAction extends DeleteAction
{
    private string $verb = 'Archiver';

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(fn (): string => $this->verb);

        $this->modalHeading(fn (): string => $this->verb.' '.$this->getRecordTitle());

        $this->modalSubmitActionLabel(fn (): string => $this->verb);

        $this->successNotificationTitle('Fait!');

        $this->tooltip(fn (): string => $this->verb.' '.$this->getRecordTitle());

    }

    public function verb(?string $verb): static
    {
        $this->verb = $verb;

        return $this;
    }
}
