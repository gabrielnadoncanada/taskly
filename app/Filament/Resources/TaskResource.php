<?php

namespace App\Filament\Resources;

use App\Enums\TaskStatus;
use App\Filament\AbstractResource;
use App\Filament\Fields\Subtasks;
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
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Support\Enums\MaxWidth;
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

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?int $navigationSort = 6;

    public static function leftColumn(): array
    {
        return [
            Section::make()
                ->columns()
                ->schema([
                    TextInput::make(Task::TITLE)
                        ->columnSpanFull()
                        ->required(),
                    Select::make(Task::PROJECT_ID)
                        ->relationship('project')
                        ->options(Project::all()->pluck(Project::TITLE, 'id'))
                        ->editOptionForm(ProjectResource::getFormSchema())
                        ->createOptionForm(ProjectResource::getFormSchema())
                        ->required(),
                    DatePicker::make(Task::DATE)
                        ->default(now())
                        ->required(),
                    RichEditor::make(Task::DESCRIPTION)
                        ->columnSpanFull(),
//                    Subtasks::make('subtasks')
//                        ->columnSpanFull(),
                ]),

                        static::subTasksSection(),
            //            static::itemsSection(),
        ];
    }

    public static function rightColumn(): array
    {
        return [
            Section::make()
                ->schema([
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

    public static function subTasksSection(): Section
    {
        return Section::make('Sub Tasks')
            ->collapsible()
            ->collapsed(fn ($record) => ! $record)
            ->hidden(fn ($record) => $record?->parent()->exists())
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
            ]);
    }

    public static function itemsSection(): Section
    {
        Section::make('Items')
            ->collapsed(fn ($record) => ! $record)
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

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make(Task::TITLE),
                Tables\Columns\TextColumn::make('project.title'),
                Tables\Columns\TextColumn::make(Task::DATE)
                  ,
                Tables\Columns\TextColumn::make(Task::STATUS)
                    ->badge(),

            ])
            ->filters([
                TrashedFilter::make(),
            ])

            ->actions([
                EditAction::make()->modalWidth(MaxWidth::SevenExtraLarge),
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
