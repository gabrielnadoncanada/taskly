<?php

namespace App\Filament\Components;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Model;

class TimeStampSection
{
    public static function make(?array $schema = []): Section
    {
        return Section::make()->grow(false)->schema([
            ...$schema,
            Placeholder::make('created_at')
                ->label('Créé le')
                ->content(fn (Model $record): ?string => $record->created_at?->diffForHumans()),
            Placeholder::make('updated_at')
                ->label('Dernière modification le')
                ->content(fn (Model $record): ?string => $record->updated_at?->diffForHumans()),
        ])
            ->visibleOn(['edit', 'view']);
    }
}
