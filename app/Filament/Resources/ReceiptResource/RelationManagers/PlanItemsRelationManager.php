<?php

namespace App\Filament\Resources\ReceiptResource\RelationManagers;

use App\Enums\ItemStatus;
use App\Filament\Actions\GenerateItemsAction;
use App\Filament\Resources\ItemResource;
use App\Filament\Resources\ReceiptResource\Pages\PlanReceiptItems;
use App\Filament\Tables\Actions\SoftDeleteBulkAction;
use App\Models\Item;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class PlanItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Form $form): Form
    {
        return ItemResource::form($form);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make(Item::DISPLAY_ITEM_NUMBER)
                    ->searchable(),
                TextColumn::make(Item::DESCRIPTION)
                    ->searchable(),
                TextColumn::make(Item::STATUS)
                    ->badge(),
                TextColumn::make(Item::DISPLAY_WEIGHT)
                    ->numeric()
                    ->sortable(),
                TextColumn::make(Item::DISPLAY_DIMENSIONS)
                    ->searchable(),
            ])
            ->headerActions([
                GenerateItemsAction::make(),
            ])
            ->actions([
                Action::make('received_article')
                    ->hiddenLabel()
                    ->tooltip('Articles stockés')
                    ->icon('heroicon-o-archive-box-arrow-down')
                    ->color('info')
                    ->action(function (Model $record) {
                        $record->update([Item::STATUS => ItemStatus::STORED]);
                    }),
                Action::make('pending_article')
                    ->tooltip('Articles en attente de réception')
                    ->hiddenLabel()
                    ->icon('heroicon-o-archive-box-x-mark')
                    ->color('warning')
                    ->action(function (Model $record) {
                        $record->update([Item::STATUS => ItemStatus::AWAITING_RECEIPT]);
                    }),
                EditAction::make(),

                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkAction::make('received_article')
                    ->label('Articles stockés')
                    ->action(function (Collection $records) {
                        $records->each(fn (Model $record) => $record->update([Item::STATUS => ItemStatus::STORED]));
                    })
                    ->icon('heroicon-o-archive-box-arrow-down')
                    ->color('info')
                    ->requiresConfirmation(),
                BulkAction::make('pending_article')
                    ->action(function (Collection $records) {
                        $records->each(fn (Model $record) => $record->update([Item::STATUS => ItemStatus::AWAITING_RECEIPT]));
                    })
                    ->label('Articles en attente de réception')
                    ->icon('heroicon-o-archive-box-x-mark')
                    ->color('warning')
                    ->requiresConfirmation(),
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
        return $pageClass === PlanReceiptItems::class;
    }
}
