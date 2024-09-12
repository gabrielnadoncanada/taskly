<?php

namespace App\Filament\Resources\TaskResource\RelationManagers;

use App\Models\Item;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Form $form): Form
    {
        return $form
            ->schema(self::getFormFieldsSchema());
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make(Item::TITLE),
                Tables\Columns\TextColumn::make('quantity'),

            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        TextInput::make('quantity')->integer()->required(),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->form(fn (EditAction $action): array => [
                    TextInput::make('quantity')->integer()->required(),
                ]),
                Tables\Actions\DetachAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),

            ]);
    }

    public static function getFormFieldsSchema(): array
    {
        return [
            Group::make([

            ])
                ->columnSpanFull()
                ->columns(3),
        ];
    }
}
