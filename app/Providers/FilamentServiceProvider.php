<?php

namespace App\Providers;

use App\Filament\Tables\Actions\SoftDeleteAction as TableSoftDeleteAction;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\DeleteAction as TableDeleteAction;
use Filament\Tables\Actions\EditAction as TableEditAction;
use Filament\Tables\Actions\ForceDeleteAction as TableForceDeleteAction;
use Filament\Tables\Actions\ReplicateAction as TableReplicateAction;
use Filament\Tables\Actions\DetachAction as TableDetachAction;
use Filament\Tables\Actions\RestoreAction as TableRestoreAction;
use Filament\Tables\Actions\ViewAction as TableViewAction;
use Filament\Tables\Columns\Column;
use Filament\Tables\Table;
use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Table::$defaultDateDisplayFormat = 'j M o';
        Table::$defaultDateTimeDisplayFormat = 'j M o H:i:s';
        Table::$defaultTimeDisplayFormat = 'H:i:s';
        Table::configureUsing(function (Table $table): void {
            $table
                ->defaultPaginationPageOption(10)
                ->paginationPageOptions([5, 10, 25, 50]);
        });

        TextInput::configureUsing(function (TextInput $input): void {
            $input->maxLength(255);
        });

        Field::configureUsing(function (Field $field): void {
            $field->label(function () use ($field): string {
                $fieldName = $field->getName();

                return __("filament.fields.$fieldName");
            });
        });

        Column::configureUsing(function (Column $column): void {
            $column->label(function () use ($column): string {
                $fieldName = $column->getName();

                return __("filament.fields.$fieldName");
            });
        });

        TableEditAction::configureUsing(function (TableEditAction $action) {
            $action->hiddenLabel();
            $action->tooltip('Modifier');
        }, isImportant: true);

        TableSoftDeleteAction::configureUsing(function (TableSoftDeleteAction $action) {
            $action->hiddenLabel();

            $action->tooltip('Archiver');
        }, isImportant: true);

        TableForceDeleteAction::configureUsing(function (TableForceDeleteAction $action) {
            $action->hiddenLabel();
            $action->tooltip('Supprimer');
        }, isImportant: true);

        TableRestoreAction::configureUsing(function (TableRestoreAction $action) {
            $action->hiddenLabel();
            $action->tooltip('Restaurer');
        }, isImportant: true);

        TableDeleteAction::configureUsing(function (TableDeleteAction $action) {
            $action->hiddenLabel();
            $action->tooltip('Archiver');
        }, isImportant: true);

        TableDetachAction::configureUsing(function (TableDetachAction $action) {
            $action->hiddenLabel();
            $action->tooltip('Détacher');
        }, isImportant: true);

        TableViewAction::configureUsing(function (TableViewAction $action) {
            $action->hiddenLabel();
            $action->tooltip('Voir');
        }, isImportant: true);

        TableReplicateAction::configureUsing(function (TableReplicateAction $action) {
            $action->hiddenLabel();
            $action->tooltip('Dupliquer');
        }, isImportant: true);
    }
}
