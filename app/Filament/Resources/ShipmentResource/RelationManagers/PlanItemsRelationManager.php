<?php

namespace App\Filament\Resources\ShipmentResource\RelationManagers;

use App\Enums\ItemStatus;
use App\Filament\Resources\ShipmentResource\Pages\PlanShipmentItems;
use App\Filament\Tables\Actions\AssociateShipmentItemsAction;
use App\Filament\Tables\Actions\DissociateBulkShipmentItemsAction;
use App\Filament\Tables\Actions\DissociateShipmentItemsAction;
use App\Models\Item;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class PlanItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $inverseRelationship = 'shipment';

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                AssociateShipmentItemsAction::make(),
            ])
            ->actions([
                DissociateShipmentItemsAction::make(),
                Action::make('received_article')
                    ->hiddenLabel()
                    ->tooltip('Articles expédiés')
                    ->icon('heroicon-o-archive-box-arrow-down')
                    ->color('success')
                    ->action(function (Model $record) {
                        $record->update([Item::STATUS => ItemStatus::SHIPPED]);
                    }),
                Action::make('pending_article')
                    ->tooltip('Articles en attente d\'expédition')
                    ->hiddenLabel()
                    ->icon('heroicon-o-archive-box-x-mark')
                    ->color('warning')
                    ->action(function (Model $record) {
                        $record->update([Item::STATUS => ItemStatus::AWAITING_SHIPMENT]);
                    }),
            ])
            ->columns([
                TextColumn::make(Item::DISPLAY_RECEIPT_ITEM_NUMBER)
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

            ->bulkActions([
                DissociateBulkShipmentItemsAction::make(),
                BulkAction::make('received_article')
                    ->label('Articles expédiés')
                    ->action(function (Collection $records) {
                        $records->each(fn (Model $record) => $record->update([Item::STATUS => ItemStatus::SHIPPED]));
                    })
                    ->icon('heroicon-o-archive-box-arrow-down')
                    ->color('success')
                    ->requiresConfirmation(),
                BulkAction::make('pending_article')
                    ->label('Articles en attente d\'expédition')
                    ->action(function (Collection $records) {
                        $records->each(fn (Model $record) => $record->update([Item::STATUS => ItemStatus::AWAITING_SHIPMENT]));
                    })
                    ->icon('heroicon-o-archive-box-x-mark')
                    ->color('warning')
                    ->requiresConfirmation(),
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
        return $pageClass === PlanShipmentItems::class;
    }
}
