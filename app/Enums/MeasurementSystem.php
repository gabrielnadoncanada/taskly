<?php

namespace App\Enums;

use App\Enums\Traits\HasRandomEnum;
use App\Enums\Traits\HasTranslatableLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum MeasurementSystem: string implements HasColor, HasIcon, HasLabel
{
    use HasRandomEnum, HasTranslatableLabel;

    case IMPERIAL = 'Imperial';
    case METRIC = 'Metric';

    public function getLabel(): string
    {
        return $this->getTranslatableLabel();
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::IMPERIAL => 'warning',
            self::METRIC => 'info',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::IMPERIAL => 'heroicon-o-clipboard-document',
            self::METRIC => 'heroicon-o-calculator',
        };
    }
}
