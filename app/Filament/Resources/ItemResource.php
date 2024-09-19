<?php

namespace App\Filament\Resources;

use App\Filament\AbstractResource;
use App\Filament\Fields\DecimalInput;
use App\Filament\Resources\ItemResource\Pages\CreateItem;
use App\Filament\Resources\ItemResource\Pages\EditItem;
use App\Filament\Resources\ItemResource\Pages\ListItems;
use App\Filament\Tables\Actions\SoftDeleteAction;
use App\Filament\Tables\Actions\SoftDeleteBulkAction;
use App\Filament\Tables\Columns\EllipsisTextColumn;
use App\Models\Category;
use App\Models\Item;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Support\Colors\Color;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemResource extends AbstractResource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $recordTitleAttribute = 'items';

    protected static ?int $navigationSort = 6;

    protected static bool $shouldRegisterNavigation = true;

    protected static function leftColumn(): array
    {
        return [
            Section::make()
                ->columnSpan(1)
                ->columns()
                ->schema([
                    TextInput::make(Item::TITLE)->required()->columnSpanFull(),
                    RichEditor::make(Item::DESCRIPTION)->columnSpanFull(),
                    TextInput::make(Item::SKU)
                        ->columnSpanFull()
                        ->unique(Item::class, Item::SKU, ignoreRecord: true),
                    DecimalInput::make(Item::DEFAULT_PRICE),

                    TextInput::make(Item::WEIGHT)
                        ->numeric()

                        ->suffix(fn () => Filament::getTenant() ? Filament::getTenant()->getMeasurementSystemSuffix() : 'kg'),
                ]),

        ];
    }

    protected static function rightColumn(): array
    {
        return [
            Section::make()
                ->schema([
                    Select::make(Item::CATEGORY_ID)
                        ->columnSpanFull()
                        ->searchable()
                        ->live()
                        ->preload()
                        ->editOptionForm(CategoryResource::getFormFieldsSchema())
                        ->createOptionForm(CategoryResource::getFormFieldsSchema())
                        ->getOptionLabelFromRecordUsing(fn (Category $record) => $record->{Category::TITLE})
                        ->relationship(name: 'category', titleAttribute: Category::TITLE),
                    FileUpload::make('media')
                        ->image()
                        ->multiple()
                        ->maxFiles(5),
                ]),

        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(static::getTableColumns())
            ->filters([

                TrashedFilter::make(),
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make(),
                    SoftDeleteAction::make(),
                    ForceDeleteAction::make(),
                    RestoreAction::make(),
                ]),

            ])
            ->bulkActions([
                BulkActionGroup::make([
                    SoftDeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getTableColumns(): array
    {
        return [
            ImageColumn::make(Item::MEDIA)
                ->circular(),

            TextColumn::make(Item::TITLE)
                ->tooltip(fn ($record): string => $record->description ?? ''),
            EllipsisTextColumn::make(Item::SKU),
            TextColumn::make('category.title')
                ->badge()
                ->color(fn (Item $record) => Color::hex($record->category->color)),

        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListItems::route('/'),
            'create' => CreateItem::route('/create'),
            'edit' => EditItem::route('/{record}/edit'),
        ];
    }
}
