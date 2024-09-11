<?php

namespace App\Filament\Resources;

use App\Filament\AbstractResource;
use App\Filament\Components\TimeStampSection;
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
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Support\Colors\Color;
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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make()
                            ->columnSpan(1)
                            ->columns()
                            ->schema([
                                TextInput::make(Item::TITLE)->required()->columnSpanFull(),
                                Textarea::make(Item::DESCRIPTION)->columnSpanFull(),
                                DecimalInput::make(Item::DEFAULT_PRICE),
                                TextInput::make(Item::SKU)
                                    ->unique(Item::class, Item::SKU, ignoreRecord: true),
                                TextInput::make(Item::WEIGHT)
                                    ->numeric()
                                    ->columnSpanFull()
                                    ->suffix(fn () => Filament::getTenant() ? Filament::getTenant()->getMeasurementSystemSuffix() : 'kg'),
                            ]),

                        Section::make('Images')
                            ->schema([
                                FileUpload::make('media')
                                    ->image()
                                    ->multiple()
                                    ->maxFiles(5)
                                    ->hiddenLabel(),
                            ])
                            ->collapsible(),

                    ])
                    ->columnSpan(['lg' => fn ($record) => $record === null ? 3 : 2]),

                Group::make()
                    ->schema([
                        TimeStampSection::make(),
                        Section::make('Associations')
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
            ->columns(static::getTableColumns())
            ->filters([

                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
                SoftDeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
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
            ImageColumn::make(Item::MEDIA),

            TextColumn::make(Item::TITLE)
                ->tooltip(fn ($record): string => $record->description ?? '')
                ->searchable(),
            EllipsisTextColumn::make(Item::SKU)
                ->searchable(),
            TextColumn::make(Item::DEFAULT_PRICE)
                ->searchable()
                ->sortable()
                ->formatStateUsing(fn (Item $record) => $record->{Item::DEFAULT_PRICE}.' '.($record->organization ? $record->organization->getCurrencySymbol() : '$')),

            TextColumn::make('category.title')
                ->searchable()
                ->sortable()
                ->badge()
                ->color(fn (Item $record) => Color::hex($record->category->color)),
            TextColumn::make('suppliers.title')
                ->badge()
                ->searchable(),

        ];
    }

    public static function getFormSchema(): array
    {
        return [

        ];
    }

    public static function getFormExtraSchema(): array
    {
        return [

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
