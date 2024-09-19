<?php

namespace App\Filament\Resources;

use App\Filament\AbstractResource;
use App\Filament\Resources\ClientResource\RelationManagers\AddressesRelationManager;
use App\Filament\Resources\SupplierResource\Pages;
use App\Filament\Resources\SupplierResource\RelationManagers\ItemsRelationManager;
use App\Filament\Tables\Actions\SoftDeleteAction;
use App\Filament\Tables\Actions\SoftDeleteBulkAction;
use App\Models\Supplier;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Squire\Models\Country;

class SupplierResource extends AbstractResource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $recordTitleAttribute = 'suppliers';

    protected static ?int $navigationSort = 10;

    protected static function leftColumn(): array
    {
        return [
            Section::make([
                TextInput::make(Supplier::TITLE)
                    ->required(),
            ]),

        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make(Supplier::TITLE),
                Tables\Columns\TextColumn::make(Supplier::EMAIL),
                Tables\Columns\TextColumn::make('address')
                    ->getStateUsing(fn ($record): ?string => Country::find($record->addresses->first()?->country)?->name ?? null),
                Tables\Columns\TextColumn::make(Supplier::PHONE),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                SoftDeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\ForceDeleteBulkAction::make(),
                SoftDeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),

            ]);
    }

    public static function getRelations(): array
    {
        return [
            AddressesRelationManager::class,
            ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}
