<?php

namespace App\Filament\Resources;

use App\Filament\AbstractResource;
use App\Filament\Fields\PhoneInput;
use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers\AddressesRelationManager;
use App\Filament\Tables\Actions\SoftDeleteAction;
use App\Filament\Tables\Actions\SoftDeleteBulkAction;
use App\Models\Client;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ClientResource extends AbstractResource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $recordTitleAttribute = 'clientss';

    protected static ?int $navigationSort = 1;

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
                Tables\Columns\TextColumn::make(Client::NAME)

                    ->tooltip(fn ($record): string => $record->{Client::NOTE} ?? ''),
                Tables\Columns\TextColumn::make(Client::EMAIL),
                Tables\Columns\TextColumn::make(Client::PHONE)


            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                SoftDeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                ForceDeleteBulkAction::make(),
                SoftDeleteBulkAction::make(),
                RestoreBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            AddressesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}
