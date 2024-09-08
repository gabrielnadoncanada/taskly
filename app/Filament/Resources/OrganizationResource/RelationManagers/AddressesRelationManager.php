<?php

namespace App\Filament\Resources\OrganizationResource\RelationManagers;

use App\Filament\Tables\Actions\SoftDeleteAction;
use App\Filament\Tables\Actions\SoftDeleteBulkAction;
use App\Models\Address;
use App\Filament\Components\TimeStampSection;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
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
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Squire\Models\Country;
use Squire\Models\Region;

class AddressesRelationManager extends RelationManager
{
    protected static string $relationship = 'addresses';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make()
                            ->schema(self::getFormFieldsSchema())
                            ->columns(),
                    ])->columnSpan(['lg' => 2]),

                TimeStampSection::make()->columnSpan(['lg' => 1]),

            ])->columns();
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['region']))
            ->columns([
                Tables\Columns\TextColumn::make(Address::STREET),
                Tables\Columns\TextColumn::make(Address::POSTAL_CODE),
                Tables\Columns\TextColumn::make(Address::CITY),

            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                ViewAction::make(),
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
                TextInput::make(Address::STREET)->columnSpanFull(),
                TextInput::make(Address::CITY),
                TextInput::make(Address::POSTAL_CODE),
                Group::make([
                    TextInput::make(Address::COUNTRY),
                    TextInput::make(Address::STATE),
                    // Select::make(Address::COUNTRY)
                    //     ->searchable()
                    //     ->optionsLimit(250)
                    //     ->live(onBlur: true)
                    //     ->default('ca')
                    //     ->options(Country::pluck('name', 'id')),
                    // Select::make(Address::STATE)
                    //     ->options(fn (Get $get) => Region::where('country_id', $get('country'))->orderBy('name')->pluck('name', 'id'))
                    //     ->default('ca-qc')
                    //     ->key('dynamicStateOptions'),
                ])
                    ->columnSpanFull()
                    ->columns(),
            ])
                ->columnSpanFull()
                ->columns(),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()

            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('filament.models.plural.address');
    }

    public static function getModelLabel(): string
    {
        return __('filament.models.singular.address');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.models.plural.address');
    }

    protected static function getRecordLabel(): ?string
    {
        return __('filament.models.plural.address');
    }
}
