<?php

namespace App\Filament\Resources;

use App\Filament\AbstractResource;
use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers\AddressesRelationManager;
use App\Filament\Resources\CustomerResource\RelationManagers\ContactsRelationManager;
use App\Filament\Tables\Actions\SoftDeleteAction;
use App\Filament\Tables\Actions\SoftDeleteBulkAction;
use App\Models\Customer;
use App\Filament\Components\TimeStampSection;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CustomerResource extends AbstractResource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $recordTitleAttribute = 'customers';

    protected static ?string $navigationGroup = 'Logistique';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make()
                            ->columnSpan(1)
                            ->columns()
                            ->schema(static::getCustomerDetailsSchema()),
                        Section::make(__('filament.fields.primary_address'))
                            ->collapsible()
                            ->columnSpan(1)
                            ->relationship('address')
                            ->schema(AddressesRelationManager::getFormFieldsSchema()),
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
            ->columns([
                Tables\Columns\TextColumn::make(Customer::DISPLAY_CUSTOMER_NUMBER),
                Tables\Columns\TextColumn::make(Customer::NAME),
                Tables\Columns\TextColumn::make('address.fullAddress'),

            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                SoftDeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                ForceDeleteBulkAction::make(),
                SoftDeleteBulkAction::make(),
                RestoreBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ContactsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }

    public static function getCustomerDetailsSchema(): array
    {
        return [
            TextInput::make(Customer::DISPLAY_CUSTOMER_NUMBER)
                ->formatStateUsing(fn ($record) => $record?->{Customer::DISPLAY_CUSTOMER_NUMBER})
                ->disabled()
                ->hiddenOn(['create']),
            TextInput::make(Customer::NAME)
                ->columnSpan(fn ($operation) => $operation === 'create' ? 2 : 1)
                ->required(),
        ];
    }
}
