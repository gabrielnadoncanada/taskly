<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use App\Filament\Resources\TaskResource;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class TasksRelationManager extends RelationManager
{
    protected static string $relationship = 'tasks';

    public function form(Form $form): Form
    {
        return TaskResource::form($form);
    }

    public function table(Table $table): Table
    {
        return TaskResource::table($table);
    }
}
