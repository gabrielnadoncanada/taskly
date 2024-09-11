<?php

namespace App\Filament\Resources\TaskResource\RelationManagers;

use App\Enums\TaskStatus;
use App\Filament\Tables\Actions\SoftDeleteAction;
use App\Filament\Tables\Actions\SoftDeleteBulkAction;
use App\Models\Item;
use App\Models\Task;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class TasksRelationManager extends RelationManager
{
    protected static string $relationship = 'children';

    public function form(Form $form): Form
    {
        return $form
            ->schema(self::getFormFieldsSchema());
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make(Task::TITLE),
                Tables\Columns\TextColumn::make(Task::STATUS)
                    ->badge(),
                Tables\Columns\TextColumn::make(Task::DATE)
                    ->date('Y-m-d'),
                Tables\Columns\TextColumn::make('users.first_name'),

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
                TextInput::make(Task::TITLE)
                    ->required(),
                //                TextInput::make(Task::DESCRIPTION),
                DateTimePicker::make(Task::DATE),
                //                TextInput::make(Task::ESTIMATED_HOURS),
                //                TextInput::make(Task::ACTUAL_HOURS),
                Select::make(Task::STATUS)
                    ->options(TaskStatus::class),
                Select::make('user_id')
                    ->multiple()
                    ->relationship('users') // Defines the relationship with users
                    ->label('Assigned User')
                    ->options(User::all()->pluck('first_name', 'id')) // Adjust this to match your User model
                    ->searchable()
                    ->required(),

                Repeater::make('items')
                    ->relationship('items') // Defines the relationship with users
                    ->schema([
                        Select::make('item_id')
                            ->label('Item')
                            ->options(Item::all()->pluck('title', 'id')) // Adjust this to match your User model
                            ->searchable()
                            ->required(),
                        TextInput::make('quantity')
                            ->label('Quantity')
                            ->numeric()
                            ->required(),
                    ])
                    ->columns(2),
                //                Repeater::make('items')
                //                    ->schema([
                //                        Select::make('item_id')
                //                            ->searchable()
                //                            ->preload()
                //                            ->relationship('items', 'name'),
                //                        TextInput::make('quantity')
                //                            ->default(1)
                //                            ->type('number'),
                //                    ])
            ])
                ->columnSpanFull(),
        ];
    }
}
