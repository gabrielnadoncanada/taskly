<?php

namespace App\Filament\Actions;

use Filament\Tables\Actions\DeleteAction;

class SoftDeleteActionTable extends DeleteAction
{
    private string $verb = 'Désactiver';

    protected function setUp(): void
    {
        parent::setUp();

        $this->configureUsing(
            (function (DeleteAction $action): void {
                $action->tooltip('Désactiver')->label('');
            }),
            isImportant: true
        );

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
