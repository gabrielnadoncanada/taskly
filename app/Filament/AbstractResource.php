<?php

namespace App\Filament;

use App\Filament\Components\TimeStampSection;
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
        return $form
            ->schema([
                Group::make()
                    ->schema(static::leftColumn())
                    ->columnSpan(['lg' => fn ($record) => $record === null ? 3 : 2]),
                Group::make()
                    ->schema(static::rightColumn())
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    abstract protected static function leftColumn(): array;

    protected static function rightColumn(): array
    {
        return [
            TimeStampSection::make(),
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
