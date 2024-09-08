<?php

namespace App\Enums;

use App\Enums\Traits\HasRandomEnum;
use App\Enums\Traits\HasTranslatableLabel;
use Filament\Support\Contracts\HasLabel;

enum WeightUnits: string implements HasLabel
{
    use HasRandomEnum, HasTranslatableLabel;

    case LB = 'lb';

    case KG = 'kg';

    public function getLabel(): string
    {
        return $this->getTranslatableLabel();
    }
}
