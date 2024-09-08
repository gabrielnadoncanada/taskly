<?php

namespace App\Enums;

use App\Enums\Traits\HasRandomEnum;
use App\Enums\Traits\HasTranslatableLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ItemStatus: string implements HasColor, HasIcon, HasLabel
{
    use HasRandomEnum, HasTranslatableLabel;

    case AWAITING_RECEIPT = 'awaiting_receipt';
    case STORED = 'stored';
    case AWAITING_SHIPMENT = 'awaiting_shipment';
    case SHIPPED = 'shipped';

    public function getLabel(): string
    {
        return $this->getTranslatableLabel();
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::AWAITING_RECEIPT, self::AWAITING_SHIPMENT => 'warning',
            self::STORED => 'info',
            self::SHIPPED => 'success',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::AWAITING_RECEIPT, self::AWAITING_SHIPMENT => 'heroicon-m-arrow-path',
            self::STORED => 'heroicon-m-archive-box-arrow-down',
            self::SHIPPED => 'heroicon-m-truck',
        };
    }
}
