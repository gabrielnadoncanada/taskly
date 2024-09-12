<?php

namespace App\Enums;

use App\Enums\Traits\HasRandomEnum;
use App\Enums\Traits\HasTranslatableLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ProjectStatus: string implements HasColor, HasLabel
{
    use HasRandomEnum, HasTranslatableLabel;

    case NOT_STARTED = 'not_started';
    case IN_PROGRESS = 'in_progress';
    case REVIEW = 'review';
    case ON_HOLD = 'on_hold';
    case CLOSED = 'closed';

    case CANCELLED = 'cancelled';

    public function getLabel(): string
    {
        return $this->getTranslatableLabel();
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::NOT_STARTED => 'info',
            self::IN_PROGRESS => 'success',
            self::REVIEW, self::ON_HOLD => 'warning',
            self::CLOSED, self::CANCELLED => 'gray',
        };
    }
}
