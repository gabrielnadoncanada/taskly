<?php

namespace App\Filament\Resources;

use App\Filament\AbstractResource;
use App\Filament\Resources\CustomerResource\RelationManagers\AddressesRelationManager;
use App\Filament\Resources\WarehouseResource\Pages;
use App\Filament\Resources\WarehouseResource\RelationManagers\LocalizationsRelationManager;
use App\Filament\Tables\Actions\SoftDeleteAction;
use App\Filament\Tables\Actions\SoftDeleteBulkAction;
use App\Models\Warehouse;
use App\Filament\Components\TimeStampSection;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WarehouseResource extends AbstractResource
{
    protected static ?string $model = Warehouse::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $recordTitleAttribute = 'warehouses';

    protected static ?string $navigationGroup = 'Logistique';

    protected static ?int $navigationSort = 6;

    protected static string $customRecordTitleAttribute = 'display_warehouse_number';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make()
                            ->columnSpan(1)
                            ->columns()
                            ->schema([
                                TextInput::make(Warehouse::DISPLAY_WAREHOUSE_NUMBER)
                                    ->disabled()
                                    ->hiddenOn(['create'])
                                    ->formatStateUsing(fn ($record) => $record?->{Warehouse::DISPLAY_WAREHOUSE_NUMBER}),
                                TextInput::make(Warehouse::NAME)
                                    ->columnSpan(fn ($operation) => $operation === 'create' ? 2 : 1)
                                    ->required(),
                            ]),
                        Section::make(__('filament.fields.primary_address'))
                            ->collapsible()
                            ->columns()
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
                TextColumn::make(Warehouse::DISPLAY_WAREHOUSE_NUMBER)
                    ->searchable(),
                Tables\Columns\TextColumn::make(Warehouse::NAME)
                    ->searchable(),
                Tables\Columns\TextColumn::make(Warehouse::CREATED_AT)
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make(Warehouse::UPDATED_AT)
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
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
            ->with(['localizations'])
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getRelations(): array
    {
        return [
            LocalizationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWarehouses::route('/'),
            'create' => Pages\CreateWarehouse::route('/create'),
            'edit' => Pages\EditWarehouse::route('/{record}/edit'),
        ];
    }
}
