<?php

namespace App\Filament\Fields;

use Filament\Forms\Components\TextInput;

class PhoneInput extends TextInput
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->regex('/^(?:\+?\d{1,3}[-. ]?)?\(?([2-9][0-9]{2})\)?[-. ]?([2-9][0-9]{2})[-. ]?([0-9]{4})$/');
    }
}
