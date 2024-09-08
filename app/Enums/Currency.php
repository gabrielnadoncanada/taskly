<?php

namespace App\Enums;

use App\Enums\Traits\HasRandomEnum;
use App\Enums\Traits\HasTranslatableLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum Currency: string implements HasColor, HasIcon, HasLabel
{
    use HasRandomEnum, HasTranslatableLabel;

    case USD = 'usd';
    case CAD = 'cad';

    public function getLabel(): string
    {
        return $this->getTranslatableLabel();
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::USD => 'success',
            self::CAD => 'success',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::USD => 'heroicon-o-currency-dollar',
            self::CAD => 'heroicon-o-currency-dollar',
        };
    }
}
