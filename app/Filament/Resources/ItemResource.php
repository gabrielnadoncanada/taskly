<?php

namespace App\Filament\Resources;

use App\Enums\DimensionUnits;
use App\Enums\ItemStatus;
use App\Enums\WeightUnits;
use App\Filament\AbstractResource;
use App\Filament\Fields\DecimalInput;
use App\Filament\Resources\ItemResource\Pages\CreateItem;
use App\Filament\Resources\ItemResource\Pages\EditItem;
use App\Filament\Resources\ItemResource\Pages\ListItems;
use App\Filament\Resources\ReceiptResource\RelationManagers\PlanItemsRelationManager as PlanReceiptItemsRelationManager;
use App\Filament\Resources\ShipmentResource\RelationManagers\PlanItemsRelationManager as PlanShipmentItemsRelationManager;
use App\Filament\Tables\Actions\SoftDeleteAction;
use App\Filament\Tables\Actions\SoftDeleteBulkAction;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Localization;
use App\Models\Receipt;
use App\Models\Shipment;
use App\Filament\Components\TimeStampSection;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemResource extends AbstractResource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $recordTitleAttribute = 'items';

    protected static ?string $navigationGroup = 'Opération';

    protected static ?int $navigationSort = 6;

    protected static string $customRecordTitleAttribute = 'display_item_number';

    protected static bool $shouldRegisterNavigation = true;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make()
                            ->columnSpan(1)
                            ->columns()
                            ->schema(static::getFormSchema()),
                        Section::make(__('filament.titles.additional_information'))
                            ->columns()
                            ->schema(static::getFormExtraSchema())
                            ->columnSpan(1),
                    ])
                    ->columnSpan(['lg' => fn ($record) => $record === null ? 3 : 2]),

                TimeStampSection::make()
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(static::getTableColumns())
            ->filters([
                SelectFilter::make(Item::STATUS)
                    ->default([ItemStatus::STORED->value])
                    ->multiple()
                    ->options(ItemStatus::class),

                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
                SoftDeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    SoftDeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getTableColumns(): array
    {
        return [
            TextColumn::make(Item::DISPLAY_ITEM_NUMBER)
                ->searchable(),

            TextColumn::make(Item::DESCRIPTION)
                ->searchable(),
            TextColumn::make(Item::STATUS)
                ->badge(),
            TextColumn::make('localization.'.Localization::DISPLAY_LOCALIZATION_NUMBER),
        ];
    }

    public static function getFormSchema(): array
    {
        return [
            TextInput::make(Item::DISPLAY_ITEM_NUMBER)
                ->disabled()
                ->columnSpanFull()
                ->hiddenOn(['create'])
                ->formatStateUsing(fn ($record) => $record?->{Item::DISPLAY_ITEM_NUMBER}),
            Textarea::make(Item::DESCRIPTION)->columnSpanFull(),

            Fieldset::make(__('filament.fields.weight'))
                ->schema([
                    ToggleButtons::make(Item::WEIGHT_UNIT)
                        ->required()
                        ->inline()
                        ->options(WeightUnits::class)
                        ->default(WeightUnits::LB),
                    DecimalInput::make(Item::WEIGHT),
                ]),
            Fieldset::make(__('filament.fields.dimension'))
                ->columns(4)
                ->schema([
                    ToggleButtons::make(Item::DIMENSION_UNIT)
                        ->required()
                        ->inline()
                        ->options(DimensionUnits::class)
                        ->default(DimensionUnits::CM),
                    DecimalInput::make(Item::WIDTH),
                    DecimalInput::make(Item::LENGTH),
                    DecimalInput::make(Item::HEIGHT),
                ]),
        ];
    }

    public static function getFormExtraSchema(): array
    {
        return [
            Select::make(Item::RECEIPT_ID)
                ->relationship('receipt', 'id')
                ->disabled()
                ->hiddenOn([PlanReceiptItemsRelationManager::class, PlanShipmentItemsRelationManager::class])
                ->getOptionLabelFromRecordUsing(fn (Model $record) => $record->{Receipt::DISPLAY_RECEIPT_NUMBER})
                ->nullable(),

            Select::make(Item::SHIPMENT_ID)
                ->relationship('shipment', 'id')
                ->disabled()
                ->hiddenOn([PlanReceiptItemsRelationManager::class, PlanShipmentItemsRelationManager::class])
                ->getOptionLabelFromRecordUsing(fn (Model $record) => $record->{Shipment::DISPLAY_SHIPMENT_NUMBER})
                ->nullable(),

            Select::make(Item::CUSTOMER_ID)
                ->relationship('customer', 'id')
                ->disabledOn(['edit'])
                ->hiddenOn([PlanReceiptItemsRelationManager::class, PlanShipmentItemsRelationManager::class])
                ->getOptionLabelFromRecordUsing(fn (Model $record) => $record->{Customer::NAME})
                ->required(),
            Select::make(Item::LOCALIZATION_ID)
                ->relationship(
                    name: 'localization',
                    titleAttribute: 'id',
                    modifyQueryUsing: fn (Builder $query) => $query->whereHas('warehouse', function ($query) {
                        $query->where('organization_id', filament()->getTenant()->id);
                    })
                )
                ->getOptionLabelFromRecordUsing(fn (Model $record) => $record->{Localization::DISPLAY_LOCALIZATION_NUMBER})
                ->required(),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListItems::route('/'),
            'create' => CreateItem::route('/create'),
            'edit' => EditItem::route('/{record}/edit'),
        ];
    }
}
