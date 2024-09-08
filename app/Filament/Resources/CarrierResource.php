<?php

namespace App\Filament\Resources;

use App\Filament\AbstractResource;
use App\Filament\Resources\CarrierResource\Pages;
use App\Filament\Tables\Actions\SoftDeleteAction;
use App\Filament\Tables\Actions\SoftDeleteBulkAction;
use App\Models\Carrier;
use App\Filament\Components\TimeStampSection;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CarrierResource extends AbstractResource
{
    protected static ?string $model = Carrier::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $recordTitleAttribute = 'carriers';

    protected static ?string $navigationGroup = 'Logistique';

    protected static string $customRecordTitleAttribute = 'display_carrier_number';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns()
                    ->schema(static::getFormFieldsSchema())
                    ->columnSpan(['lg' => fn ($record) => $record === null ? 3 : 2]),
                TimeStampSection::make()
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);

    }

    public static function getFormFieldsSchema(): array
    {
        return [
            Group::make([
                TextInput::make(Carrier::DISPLAY_CARRIER_NUMBER)
                    ->disabled()
                    ->hiddenOn(['create', 'createOption'])
                    ->formatStateUsing(fn ($record) => $record?->{Carrier::DISPLAY_CARRIER_NUMBER}),
                TextInput::make(Carrier::NAME)
                    ->columnSpan(fn ($operation) => in_array($operation, ['create', 'createOption']) ? 2 : 1)
                    ->required(),
            ])->columns()->columnsPan(2),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make(Carrier::DISPLAY_CARRIER_NUMBER)
                    ->searchable(),
                Tables\Columns\TextColumn::make(Carrier::NAME)
                    ->searchable(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCarriers::route('/'),
            'create' => Pages\CreateCarrier::route('/create'),
            'edit' => Pages\EditCarrier::route('/{record}/edit'),
        ];
    }
}
