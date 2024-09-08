<?php

namespace App\Filament;

use Filament\Resources\Resource;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractResource extends Resource
{
    protected static bool $hasTitleCaseModelLabel = false;

    protected static bool $shouldCheckPolicyExistence = false;

    protected static string $customRecordTitleAttribute = '';

    public static function getModelLabel(): string
    {
        return __('filament.models.'.parent::getModelLabel());
    }

    public static function getRecordTitle(?Model $record): string|Htmlable|null
    {
        if (! static::$customRecordTitleAttribute) {
            return parent::getRecordTitle($record);
        }

        $customRecordTitleAttribute = strtoupper(static::$customRecordTitleAttribute);

        return sprintf(
            '%s (%s)',
            $record?->getAttribute(
                get_class($record)::{$customRecordTitleAttribute}
            ), static::getModelLabel()
        );
    }
}
