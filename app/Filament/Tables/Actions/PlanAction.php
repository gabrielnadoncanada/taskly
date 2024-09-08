<?php

namespace App\Filament\Tables\Actions;

use App\Enums\OrderStatus;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class PlanAction extends Action
{
    protected ?string $name = 'plan';

    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'plan')
            ->icon('heroicon-o-inbox-stack')
            ->color('info')
            ->hiddenLabel()
            ->requiresConfirmation(fn (Model $record) => $record->status->value === OrderStatus::NEW->value)
            ->tooltip(ucfirst($name ?? 'Plan'))
            ->action(function (Model $record, $livewire) {
                if ($record->status->value === OrderStatus::NEW->value) {
                    $record->update([
                        'status' => OrderStatus::PROCESSING->value,
                    ]);
                    Notification::make()
                        ->title('Success')
                        ->body('Plannification en cours')
                        ->success()
                        ->send();
                }

                $resource = $livewire->getResource();

                return redirect(static::redirectToPlanPage($resource, $record));
            });
    }

    public static function redirectToPlanPage($resource, Model $record)
    {
        return $resource::getUrl('items', ['record' => $record]);
    }
}
