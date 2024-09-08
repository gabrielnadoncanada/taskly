<?php

namespace App\Filament\Resources;

use App\Enums\ReceiptStatus;
use App\Filament\AbstractResource;
use App\Filament\Resources\ReceiptResource\Pages\CreateReceipt;
use App\Filament\Resources\ReceiptResource\Pages\EditReceipt;
use App\Filament\Resources\ReceiptResource\Pages\ListReceipts;
use App\Filament\Resources\ReceiptResource\Pages\PlanReceiptItems;
use App\Filament\Resources\ReceiptResource\RelationManagers\ItemsRelationManager;
use App\Filament\Resources\ReceiptResource\RelationManagers\PlanItemsRelationManager;
use App\Filament\Tables\Actions\PlanAction;
use App\Filament\Tables\Actions\SoftDeleteAction;
use App\Filament\Tables\Actions\SoftDeleteBulkAction;
use App\Models\Carrier;
use App\Models\Customer;
use App\Models\Receipt;
use App\Filament\Components\TimeStampSection;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReceiptResource extends AbstractResource
{
    protected static ?string $model = Receipt::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box-arrow-down';

    protected static ?string $recordTitleAttribute = 'receipts';

    protected static ?string $navigationGroup = 'Opération';

    protected static ?int $navigationSort = 6;

    protected static string $customRecordTitleAttribute = 'display_receipt_number';

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
                TextColumn::make(Receipt::DISPLAY_RECEIPT_NUMBER)
                    ->searchable(),
                TextColumn::make('customer.'.Customer::NAME)
                    ->numeric()
                    ->sortable(),
                TextColumn::make(Receipt::EXPECTED_DATE),
                TextColumn::make(Receipt::STATUS)
                    ->badge(),

            ])
            ->filters([
                SelectFilter::make(Receipt::STATUS)
                    ->multiple()
                    ->options(ReceiptStatus::class),
                TrashedFilter::make(),
            ])
            ->actions([
                PlanAction::make(name: 'recevoir'),
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

    public static function getFormSchema(): array
    {
        return [
            TextInput::make(Receipt::DISPLAY_RECEIPT_NUMBER)
                ->disabled()
                ->hiddenOn(['create'])
                ->formatStateUsing(fn ($record) => $record?->{Receipt::DISPLAY_RECEIPT_NUMBER}),
            TextInput::make(Receipt::PURCHASE_ORDER_IDENTIFIER)->required(),
            Select::make(Receipt::CUSTOMER_ID)
                ->relationship('customer', 'id')
                ->getOptionLabelFromRecordUsing(fn (Customer $record) => $record->{Customer::DISPLAY_NAME})
                ->searchable()
                ->preload()
                ->required(),
            Select::make(Receipt::CARRIER_ID)
                ->editOptionForm(CarrierResource::getFormFieldsSchema())
                ->createOptionForm(CarrierResource::getFormFieldsSchema())
                ->createOptionUsing(fn (array $data) => Carrier::create($data))
                ->required()
                ->relationship('carrier', Carrier::NAME),
            DateTimePicker::make(Receipt::EXPECTED_DATE)
                ->default(now()),
        ];
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
            'index' => ListReceipts::route('/'),
            'create' => CreateReceipt::route('/create'),
            'edit' => EditReceipt::route('/{record}/edit'),
            'items' => PlanReceiptItems::route('/{record}/items'),
        ];
    }
}
