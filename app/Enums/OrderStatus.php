<?php

namespace App\Enums;

use App\Enums\Traits\HasRandomEnum;
use App\Enums\Traits\HasTranslatableLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum OrderStatus: string implements HasColor, HasIcon, HasLabel
{
    use HasRandomEnum, HasTranslatableLabel;

    case NEW = 'new';
    case PROCESSING = 'processing';
    case CANCELLED = 'cancelled';

    public function getLabel(): string
    {
        return $this->getTranslatableLabel();
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::NEW => 'info',
            self::PROCESSING => 'warning',
            self::CANCELLED => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::NEW => 'heroicon-m-sparkles',
            self::PROCESSING => 'heroicon-m-arrow-path',
            self::CANCELLED => 'heroicon-m-x-circle',
        };
    }
}
