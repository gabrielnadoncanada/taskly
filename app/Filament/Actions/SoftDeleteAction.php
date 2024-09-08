<?php

namespace App\Filament\Actions;

use Filament\Actions\DeleteAction;

class SoftDeleteAction extends DeleteAction
{
    private string $verb = 'Désactiver';

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(fn (): string => $this->verb);

        $this->modalHeading(fn (): string => $this->verb.' '.$this->getRecordTitle());

        $this->modalSubmitActionLabel(fn (): string => $this->verb);

        $this->successNotificationTitle('Fait!');

    }

    public function verb(?string $verb): static
    {
        $this->verb = $verb;

        return $this;
    }
}
