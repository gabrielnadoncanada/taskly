<?php

namespace App\Filament\Resources;

use App\Filament\AbstractResource;
use App\Filament\Components\TimeStampSection;
use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers\TasksRelationManager;
use App\Filament\Tables\Actions\SoftDeleteAction;
use App\Filament\Tables\Actions\SoftDeleteBulkAction;
use App\Models\Project;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProjectResource extends AbstractResource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $recordTitleAttribute = 'projects';

    protected static ?int $navigationSort = 10;

    protected static function leftColumn(): array
    {
        return [
            Section::make([
                TextInput::make(Project::TITLE)
                    ->required(),
                RichEditor::make(Project::DESCRIPTION),

            ]),

        ];
    }

    protected static function rightColumn(): array
    {
        return [
            TimeStampSection::make(),
            Section::make([
                Select::make(Project::CLIENT_ID)
                    ->relationship('client', 'name')
                    ->required(),
                DatePicker::make(Project::DATE)
                    ->default(now())
                    ->required(),
            ]),
        ];
    }

    public static function getFormFieldsSchema(): array
    {
        return [

        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make(Project::TITLE)
                    ->searchable(),
                Tables\Columns\TextColumn::make('client.name')
                    ->sortable(),

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
            TasksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
