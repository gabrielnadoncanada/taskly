<?php

namespace App\Filament\Resources\ReceiptResource\RelationManagers;

use App\Filament\Actions\GenerateItemsAction;
use App\Filament\Resources\ItemResource;
use App\Filament\Resources\ReceiptResource\Pages\EditReceipt;
use App\Filament\Tables\Actions\SoftDeleteAction;
use App\Filament\Tables\Actions\SoftDeleteBulkAction;
use App\Models\Item;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()

                    ->columns()
                    ->schema(ItemResource::getFormSchema()),
                Section::make(__('filament.titles.additional_information'))
                    ->columns()
                    ->schema(ItemResource::getFormExtraSchema()),

            ]);

    }

    public function table(Table $table): Table
    {
        return
            $table->columns([
                TextColumn::make(Item::DISPLAY_ITEM_NUMBER)
                    ->searchable(),
                TextColumn::make(Item::DISPLAY_LOCALIZATION_NUMBER),
                TextColumn::make(Item::DESCRIPTION)
                    ->searchable(),

                TextColumn::make(Item::STATUS)
                    ->badge(),

                TextColumn::make(Item::DISPLAY_WEIGHT)
                    ->numeric()
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->sortable(),
                TextColumn::make(Item::DISPLAY_DIMENSIONS)
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->searchable(),
            ])
                ->headerActions([
                    GenerateItemsAction::make(),
                ])
                ->filters([
                    TrashedFilter::make(),
                ])
                ->actions([
                    EditAction::make(),
                    SoftDeleteAction::make(),
                    ForceDeleteAction::make(),
                    RestoreAction::make(),
                ])
                ->bulkActions([
                    SoftDeleteBulkAction::make(),
                ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    protected static function getRecordLabel(): ?string
    {
        return __('filament.models.item');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('filament.models.item').'s';
    }

    protected static function getModelLabel(): ?string
    {
        return __('filament.models.item');
    }

    protected static function getPluralModelLabel(): ?string
    {
        return __('filament.models.item').'s';
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $pageClass === EditReceipt::class;
    }
}
