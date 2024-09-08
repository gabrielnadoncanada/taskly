<?php

namespace App\Filament\Resources;

use App\Enums\ShipmentStatus;
use App\Filament\AbstractResource;
use App\Filament\Resources\CustomerResource\RelationManagers\AddressesRelationManager;
use App\Filament\Resources\ShipmentResource\Pages;
use App\Filament\Resources\ShipmentResource\Pages\PlanShipmentItems;
use App\Filament\Resources\ShipmentResource\RelationManagers\ItemsRelationManager;
use App\Filament\Resources\ShipmentResource\RelationManagers\PlanItemsRelationManager;
use App\Filament\Tables\Actions\PlanAction as TablesPlanAction;
use App\Filament\Tables\Actions\SoftDeleteAction;
use App\Filament\Tables\Actions\SoftDeleteBulkAction;
use App\Models\Address;
use App\Models\Carrier;
use App\Models\Customer;
use App\Models\Shipment;
use App\Filament\Components\TimeStampSection;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShipmentResource extends AbstractResource
{
    protected static ?string $model = Shipment::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-americas';

    protected static ?string $recordTitleAttribute = 'shipments';

    protected static ?string $navigationGroup = 'Opération';

    protected static string $customRecordTitleAttribute = 'display_shipment_number';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns()
                    ->schema(static::getFormSchema())
                    ->columnSpan(['lg' => fn ($record) => $record === null ? 3 : 2]),
                TimeStampSection::make()
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make(Shipment::DISPLAY_SHIPMENT_NUMBER)
                    ->searchable(),
                TextColumn::make('customer.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make(Shipment::EXPECTED_DATE),
                TextColumn::make(Shipment::STATUS)
                    ->badge(),
            ])
            ->filters([
                SelectFilter::make(Shipment::STATUS)
                    ->multiple()
                    ->options(ShipmentStatus::class),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                TablesPlanAction::make(name: 'préparer'),
                Tables\Actions\EditAction::make(),
                SoftDeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    SoftDeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ItemsRelationManager::class,
            PlanItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShipments::route('/'),
            'create' => Pages\CreateShipment::route('/create'),
            'edit' => Pages\EditShipment::route('/{record}/edit'),
            'items' => PlanShipmentItems::route('/{record}/items'),
        ];
    }

    public static function getFormSchema(): array
    {
        return [
            TextInput::make(Shipment::DISPLAY_SHIPMENT_NUMBER)
                ->disabled()
                ->hiddenOn(['create'])
                ->formatStateUsing(fn ($record) => $record?->{Shipment::DISPLAY_SHIPMENT_NUMBER}),
            TextInput::make(Shipment::PURCHASE_ORDER_IDENTIFIER)->required(),
            Select::make(Shipment::CUSTOMER_ID)
                ->relationship('customer', 'id')
                ->getOptionLabelFromRecordUsing(fn (Customer $record) => $record->{Customer::DISPLAY_NAME})
                ->searchable()
                ->live()
                ->preload()
                ->required(),

            Select::make(Shipment::CARRIER_ID)
                ->required()
                ->editOptionForm(CarrierResource::getFormFieldsSchema())
                ->createOptionForm(CarrierResource::getFormFieldsSchema())
                ->required()
                ->relationship('carrier', Carrier::NAME),
            DatePicker::make(Shipment::EXPECTED_DATE)
                ->default(now()),
            Group::make([
                Select::make(Shipment::ADDRESS_ID)
                    ->relationship('address', 'id')
                    ->required()
                    ->options(function ($record, $get) {
                        $customerAddresses = Customer::find($get(Shipment::CUSTOMER_ID))
                            ?->address()
                            ->get()
                            ->mapWithKeys(fn ($address) => [
                                $address->id => $address->{Address::FULL_ADDRESS},
                            ])->toArray();

                        return $customerAddresses;
                    })
                    ->editOptionForm(AddressesRelationManager::getFormFieldsSchema())
                    ->createOptionForm(AddressesRelationManager::getFormFieldsSchema())
                    ->createOptionUsing(function (array $data, $get) {
                        $address = Address::create([
                            ...$data,
                            Address::ADDRESSABLE_ID => $get(Shipment::CUSTOMER_ID),
                            Address::ADDRESSABLE_TYPE => 'customer',
                        ]);

                        return $address->id;
                    }),
            ])->hidden(fn (Get $get) => $get(Shipment::CUSTOMER_ID) === null),
        ];
    }
}
