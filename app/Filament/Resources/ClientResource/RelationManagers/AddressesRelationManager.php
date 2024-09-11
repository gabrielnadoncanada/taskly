<?php

namespace App\Filament\Resources\ClientResource\RelationManagers;

use App\Filament\Tables\Actions\SoftDeleteAction;
use App\Filament\Tables\Actions\SoftDeleteBulkAction;
use App\Models\Address;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Squire\Models\Country;
use Squire\Models\Region;

class AddressesRelationManager extends RelationManager
{
    protected static string $relationship = 'addresses';

    public function form(Form $form): Form
    {
        return $form
            ->schema(self::getFormFieldsSchema());
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['region']))
            ->columns([
                Tables\Columns\TextColumn::make(Address::STREET),
                Tables\Columns\TextColumn::make(Address::CITY),
                Tables\Columns\TextColumn::make(Address::DISPLAY_PROVINCE),
                Tables\Columns\TextColumn::make(Address::DISPLAY_COUNTRY),
                Tables\Columns\TextColumn::make(Address::POSTAL_CODE),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                SoftDeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->bulkActions([
                SoftDeleteBulkAction::make(),
                ForceDeleteBulkAction::make(),
                RestoreBulkAction::make(),
            ]);
    }

    public static function getFormFieldsSchema(): array
    {
        return [
            Group::make([
                TextInput::make(Address::STREET)
                    ->required(),
                TextInput::make(Address::CITY)
                    ->required(),
                TextInput::make(Address::POSTAL_CODE)
                    ->regex('/^([a-zA-Z]\d[a-zA-Z])\ {0,1}(\d[a-zA-Z]\d)$/')
                    ->required(),
                Group::make([
                    Select::make(Address::COUNTRY)
                        ->searchable()
                        ->optionsLimit(250)
                        ->required()
                        ->live(onBlur: true)
                        ->default('ca')
                        ->options(Country::whereIn('id', ['ca'])->pluck('name', 'id')),
                    Select::make(Address::STATE)
                        ->options(fn (Get $get) => Region::where('country_id', $get('country'))->orderBy('name')->pluck('name', 'id'))
                        ->hidden(fn (Select $component) => count($component->getOptions()) === 0)
                        ->required()
                        ->default('ca-qc')
                        ->key('dynamicStateOptions'),
                ])
                    ->columnSpanFull()
                    ->columns(),
            ])
                ->columnSpanFull()
                ->columns(3),
        ];
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('filament.titles.secondary_addresses');
    }

    public static function getModelLabel(): string
    {
        return __('filament.titles.secondary_addresse');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.titles.secondary_addresses');
    }
}
