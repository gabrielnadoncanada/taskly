<?php

namespace App\Filament;

use Filament\Forms\Components\Group;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractResource extends Resource
{
    protected static bool $hasTitleCaseModelLabel = false;

    protected static bool $shouldCheckPolicyExistence = false;

    protected static string $customRecordTitleAttribute = '';

    public static function form(Form $form): Form
    {
        return $form->schema(static::getFormSchema())->columns(1);
    }

    public static function leftColumnSpan(?Model $record): int
    {
        return (static::hasRightColumn()) ? 2 : 3;
    }

    private static function hasRightColumn(): bool
    {
        return ! empty(static::rightColumn());
    }

    public static function getFormSchema(): array
    {
        return [
            Group::make([
                Group::make()
                    ->schema(static::leftColumn())
                    ->columnSpan(['lg' => fn ($record) => static::leftColumnSpan($record)]),
                Group::make()
                    ->schema(static::rightColumn())
                    ->columnSpan(['lg' => 1]),
            ])->columns(3),
        ];
    }

    abstract protected static function leftColumn(): array;

    protected static function rightColumn(): array
    {
        return [

        ];
    }

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
