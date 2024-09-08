<?php

namespace App\Enums;

use App\Enums\Traits\HasRandomEnum;
use App\Enums\Traits\HasTranslatableLabel;
use Filament\Support\Contracts\HasLabel;

enum DimensionUnits: string implements HasLabel
{
    use HasRandomEnum, HasTranslatableLabel;

    case CM = 'cm';

    case INCH = 'inch';

    public function getLabel(): string
    {
        return $this->getTranslatableLabel();
    }
}
