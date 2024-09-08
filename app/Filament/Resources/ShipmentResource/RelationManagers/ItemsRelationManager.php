<?php

namespace App\Filament\Resources\ShipmentResource\RelationManagers;

use App\Filament\Resources\ItemResource;
use App\Filament\Resources\ShipmentResource\Pages\EditShipment;
use App\Filament\Tables\Actions\AssociateShipmentItemsAction;
use App\Filament\Tables\Actions\DissociateBulkShipmentItemsAction;
use App\Filament\Tables\Actions\DissociateShipmentItemsAction;
use App\Models\Item;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $inverseRelationship = 'shipment';

    public function form(Form $form): Form
    {
        return ItemResource::form($form);
    }

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                AssociateShipmentItemsAction::make(),
            ])
            ->recordTitleAttribute('item_number')
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
            ])
            ->actions([
                DissociateShipmentItemsAction::make(),
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

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $pageClass === EditShipment::class;
    }
}
