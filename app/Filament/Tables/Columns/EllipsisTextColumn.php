<?php

namespace App\Filament\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

class EllipsisTextColumn extends TextColumn
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->tooltip(fn (TextColumn $column): ?string => $this->formatTooltip($column))
            ->limit(30);
    }

    public function formatTooltip(TextColumn $column): ?string
    {
        $state = $column->getState();
        if (strlen($state) <= $column->getCharacterLimit()) {
            return null;
        }

        return $state;
    }
}
