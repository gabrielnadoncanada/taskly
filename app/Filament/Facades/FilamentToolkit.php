<?php

namespace App\Filament\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \App\Filament\FilamentToolkit
 */
class FilamentToolkit extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \App\Filament\FilamentToolkit::class;
    }
}
