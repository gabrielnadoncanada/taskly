<?php

namespace App\Filament\Resources;

use App\Enums\TaskStatus;
use App\Filament\AbstractResource;
use App\Filament\Components\TimeStampSection;
use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers\ItemsRelationManager;
use App\Filament\Resources\TaskResource\RelationManagers\TasksRelationManager;
use App\Filament\Tables\Actions\SoftDeleteAction;
use App\Filament\Tables\Actions\SoftDeleteBulkAction;
use App\Models\Item;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
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

class TaskResource extends AbstractResource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $recordTitleAttribute = 'tasks';


    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make()
                            ->columnSpan(1)
                            ->schema([
                                TextInput::make(Task::TITLE)
                                    ->required(),
                                Select::make(Task::PROJECT_ID)
                                    ->relationship('project')
                                    ->options(Project::all()->pluck(Project::TITLE, 'id'))
                                    ->required(),

                                DateTimePicker::make(Task::DATE)
                                    ->default(now())
                                    ->required(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Group::make()
                    ->schema([

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
                                    ->searchable()
                            ])
                            ->columns(1)
                            ->columnSpanFull(),

                    ])->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
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

    public static function getRelations(): array
    {
        return [
            ItemsRelationManager::class,
            TasksRelationManager::class
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
