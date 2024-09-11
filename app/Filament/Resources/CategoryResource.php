<?php

namespace App\Filament\Resources;

use App\Filament\AbstractResource;
use App\Filament\Components\TimeStampSection;
use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Tables\Actions\SoftDeleteAction;
use App\Filament\Tables\Actions\SoftDeleteBulkAction;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends AbstractResource
{
    protected static ?string $model = Category::class;

    protected static bool $shouldCheckPolicyExistence = false;

    protected static bool $hasTitleCaseModelLabel = false;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $recordTitleAttribute = Category::TITLE;

    protected static ?int $navigationSort = 3;

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
                Group::make()
                    ->schema([
                        TimeStampSection::make(),

                    ])->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    public static function getFormFieldsSchema(): array
    {
        return [
            Forms\Components\TextInput::make(Category::TITLE)
                ->columnSpanFull()
                ->required(),

            Forms\Components\ColorPicker::make(Category::COLOR)
                ->columnSpanFull()
                ->required(),

            Forms\Components\Textarea::make(Category::DESCRIPTION)
                ->columnSpanFull()
                ->rows(4),

        ];
    }
    //endregion

    //region TABLE
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make(Category::TITLE)
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn (Category $record) => Color::hex($record->color)),

                Tables\Columns\TextColumn::make(Category::DESCRIPTION)
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                SoftDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
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
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),

            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
    //endregion
}
