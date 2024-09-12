<?php

namespace App\Filament\Resources;

use App\Enums\TaskStatus;
use App\Filament\AbstractResource;
use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Tables\Actions\SoftDeleteAction;
use App\Filament\Tables\Actions\SoftDeleteBulkAction;
use App\Models\Item;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class TaskResource extends AbstractResource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $recordTitleAttribute = 'tasks';

    protected static ?int $navigationSort = 6;

    public static function leftColumn(): array
    {
        return [
            Section::make()
                ->schema([
                    TextInput::make(Task::TITLE)
                        ->required(),
                    RichEditor::make(Task::DESCRIPTION)
                        ->required(),
                ]),
            Section::make('Sub Tasks')
                ->hidden(fn ($record) => $record->parent()->exists())
                ->schema([
                    TableRepeater::make('children')
                        ->hiddenLabel()
                        ->extraItemActions([
                            Action::make('openTask')
                                ->icon('heroicon-o-pencil-square')
                                ->url(function (array $arguments, Repeater $component, $record): ?string {
                                    $itemData = $component->getRawItemState($arguments['item']);
                                    if (! array_key_exists('id', $itemData)) {
                                        return null;
                                    } else {
                                        $record = Task::find($itemData['id']);
                                    }

                                    return TaskResource::getUrl('edit', ['record' => $record]);
                                }),
                        ])
                        ->relationship('children')
                        ->headers([
                            Header::make('title'),
                            Header::make('status'),
                        ])
                        ->mutateRelationshipDataBeforeCreateUsing(function (array $data, $get): array {
                            $data['project_id'] = $get(Task::PROJECT_ID);

                            return $data;
                        })
                        ->schema([
                            TextInput::make(Task::TITLE)
                                ->required(),
                            Select::make(Task::STATUS)
                                ->default(TaskStatus::NOT_STARTED)
                                ->selectablePlaceholder(false)
                                ->options(TaskStatus::class),
                        ])
                        ->required(),
                ]),
            Section::make('Items')
                ->collapsible()
                ->schema([
                    TableRepeater::make('items')
                        ->hiddenLabel()
                        ->relationship('items')
                        ->headers([
                            Header::make('title'),
                            Header::make('quantity'),
                        ])
                        ->schema([
                            Select::make('item_id')
                                ->searchable()
                                ->preload()
                                ->options(Item::all()->pluck('title', 'id')),
                            TextInput::make('quantity')
                                ->default(1)
                                ->type('number'),

                        ])
                        ->required(),

                ]),
        ];
    }

    public static function rightColumn(): array
    {
        return [
            Section::make()
                ->schema([
                    Select::make(Task::PROJECT_ID)
                        ->relationship('project')
                        ->options(Project::all()->pluck(Project::TITLE, 'id'))
                        ->required(),
                    DateTimePicker::make(Task::DATE)
                        ->default(now())
                        ->required(),
                    Select::make(Task::STATUS)
                        ->default(TaskStatus::NOT_STARTED)
                        ->selectablePlaceholder(false)
                        ->options(TaskStatus::class),
                    Select::make('user_id')
                        ->multiple()
                        ->relationship('users')
                        ->options(User::all()->pluck('name', 'id'))
                        ->searchable(),

                ])
                ->columns(1)
                ->columnSpanFull(),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make(Task::TITLE),
                Tables\Columns\TextColumn::make('parent.title'),
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

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }

    public static function getFormFieldsSchema(): array
    {
        return [
            Group::make([

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
