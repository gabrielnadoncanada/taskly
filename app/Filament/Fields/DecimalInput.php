<?php

namespace App\Filament\Fields;

use Filament\Forms\Components\TextInput;

class DecimalInput extends TextInput
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->numeric();
        $this->maxLength(null);
        $this->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/']);
    }
}
