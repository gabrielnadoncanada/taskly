<?php

namespace App\Enums;

use App\Enums\Traits\HasRandomEnum;
use App\Enums\Traits\HasTranslatableLabel;
use Filament\Support\Contracts\HasLabel;

enum Language: string implements HasLabel
{
    use HasRandomEnum, HasTranslatableLabel;

    case FR = 'fr';

    public function getLabel(): string
    {
        return $this->getTranslatableLabel();
    }
}
