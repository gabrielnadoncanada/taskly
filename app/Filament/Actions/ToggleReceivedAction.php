<?php

namespace App\Filament\Actions;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\ToggleButtons;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class ToggleReceivedAction extends Action
{
    protected ?string $name = 'toggleReceived';

    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'toggleReceived')
            ->form([
                Section::make()
                    ->schema([
                        ToggleButtons::make('is_received')
                            ->boolean()
                            ->grouped()
                            ->default(fn (Model $record) => $record->is_received),
                    ])
                    ->columns(1)
                    ->columnSpan(1),
            ])
            ->action(function (array $data, Model $record): void {
                $record->update([
                    'is_received' => $data['is_received'],
                ]);
            });
    }
}
