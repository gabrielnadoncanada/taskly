<?php

namespace App\Filament\Actions;

use App\Enums\ProjectStatus;
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

                if ($record->status === ProjectStatus::NEW->value) {
                    $record->update([
                        'status' => ProjectStatus::PROCESSING,
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
