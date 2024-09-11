<?php

namespace App\Filament\Resources;

use App\Enums\Currency;
use App\Enums\MeasurementSystem;
use App\Filament\AbstractResource;
use App\Filament\Components\TimeStampSection;
use App\Filament\Resources\OrganizationResource\Pages;
use App\Filament\Tables\Actions\SoftDeleteAction;
use App\Filament\Tables\Actions\SoftDeleteBulkAction;
use App\Models\Organization;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class OrganizationResource extends AbstractResource
{
    protected static ?string $model = Organization::class;

    protected static bool $shouldCheckPolicyExistence = false;

    protected static bool $hasTitleCaseModelLabel = false;

    protected static ?int $navigationSort = 999;

    protected static ?string $recordTitleAttribute = Organization::TITLE;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static bool $isScopedToTenant = false;

    //region FORM
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make()
                            ->schema(self::getFormFieldsSchema())
                            ->columns(),

                    ])->columnSpan(['lg' => fn ($record) => $record === null ? 3 : 2]),
                TimeStampSection::make()
                    ->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    protected static function getFormFieldsSchema(): array
    {
        return [
            Forms\Components\TextInput::make(Organization::TITLE)
                ->required(),
            Forms\Components\Select::make(Organization::CURRENCY)
                ->options(Currency::class)
                ->default(Currency::CAD)
                ->selectablePlaceholder(false)
                ->required(),
            Forms\Components\ToggleButtons::make(Organization::MEASUREMENT_SYSTEM)
                ->options(MeasurementSystem::class)
                ->default(MeasurementSystem::METRIC)
                ->inline()
                ->required(),
            Forms\Components\TextInput::make(Organization::EMAIL)
                ->email()
                ->required(),
        ];
    }
    //endregion

    //region TABLE
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make(Organization::TITLE)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make(Organization::CURRENCY)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make(Organization::MEASUREMENT_SYSTEM)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make(Organization::EMAIL)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make(Organization::CREATED_AT)
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make(Organization::UPDATED_AT)
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\RestoreAction::make(),
                SoftDeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),

            ])
            ->bulkActions([
                SoftDeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ]);
    }
    //endregion

    //region FUNCTIONS

    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation.group.administration');
    }

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrganizations::route('/'),
            'create' => Pages\CreateOrganization::route('/create'),
            'edit' => Pages\EditOrganization::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();

        return parent::getEloquentQuery()
            ->when(
                $user->hasRole('Super Administrateur'),
                fn ($query) => $query->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]),
                fn ($query) => $query
                    ->join('organization_user', 'organizations.id', '=', 'organization_user.organization_id')
                    ->where('organization_user.user_id', $user->id)
                    ->select('organizations.*')
            );
    }

    //endregion
}
