<?php

namespace App\Filament\Resources\WarehouseResource\RelationManagers;

use App\Filament\Tables\Actions\SoftDeleteAction;
use App\Filament\Tables\Actions\SoftDeleteBulkAction;
use App\Models\Localization;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LocalizationsRelationManager extends RelationManager
{
    protected static string $relationship = 'localizations';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make(Localization::LOCATION_IDENTIFIER)
                    ->columnSpanFull()
                    ->required(),

            ]);
    }

    public function table(Table $table): Table
    {

        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with('warehouse'))
            ->recordTitleAttribute(Localization::LOCALIZATION_NUMBER)
            ->columns([
                Tables\Columns\TextColumn::make(Localization::DISPLAY_LOCALIZATION_NUMBER),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('filament.models.localization').'s';
    }

    public static function getModelLabel(): string
    {
        return __('filament.models.localization');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.models.localization').'s';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()

            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
