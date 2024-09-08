<?php

namespace App\Filament\Actions;

use App\Enums\OrderStatus;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class PlanAction extends Action
{
    protected ?string $name = 'plan';

    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'plan')
            ->icon('heroicon-o-inbox-stack')
            ->color('info')
            ->url(function ($livewire, Model $record) {

                if ($record->status === OrderStatus::NEW->value) {
                    $record->update([
                        'status' => OrderStatus::PROCESSING,
                    ]);
                }

                $resource = $livewire->getResource();

                return static::redirectToPlanPage($resource, $record);
            });

    }

    public static function redirectToPlanPage($resource, Model $record)
    {
        return $resource::getUrl('items', ['record' => $record]);
    }
}
