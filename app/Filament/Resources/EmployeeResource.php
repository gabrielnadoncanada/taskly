<?php

namespace App\Filament\Resources;

use App\Filament\AbstractResource;
use App\Filament\Fields\PhoneInput;
use App\Filament\Resources\EmployeeResource\Pages;
use App\Models\Client;
use App\Models\Employee;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Table;

class EmployeeResource extends AbstractResource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'table_name';

    protected static ?string $navigationGroup = 'nav_group';

    protected static ?int $navigationSort = 6;

    protected static function leftColumn(): array
    {
        return [
            Section::make(self::getDetailsSchema()),
        ];
    }

    public static function getDetailsSchema(): array
    {
        return [

            TextInput::make(Client::NAME)
                ->columnSpanFull()
                ->required(),
            TextInput::make(Client::EMAIL)
                ->email(),
            PhoneInput::make(Client::PHONE),
            Textarea::make(Client::NOTE)
                ->rows(5)
                ->columnSpanFull()
                ->columnSpanFull(),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
