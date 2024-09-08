<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use App\Filament\Fields\PhoneInput;
use App\Filament\Tables\Actions\SoftDeleteAction;
use App\Filament\Tables\Actions\SoftDeleteBulkAction;
use App\Models\Contact;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ContactsRelationManager extends RelationManager
{
    protected static string $relationship = 'contacts';

    public function form(Form $form): Form
    {
        return $form
            ->schema(static::getFormFieldsSchema());
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make(Contact::NAME),
                TextColumn::make(Contact::PHONE),
                TextColumn::make(Contact::EMAIL),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->bulkActions([
                SoftDeleteBulkAction::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                SoftDeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ]);
    }

    public static function getFormFieldsSchema(): array
    {
        return [
            Group::make()
                ->schema([
                    TextInput::make(Contact::NAME)
                        ->required(),
                    TextInput::make(Contact::EMAIL)
                        ->email(),
                    PhoneInput::make(Contact::PHONE),
                    TextInput::make(Contact::EXTENSION),
                    PhoneInput::make(Contact::CELL_PHONE),
                    PhoneInput::make(Contact::FAX),
                    TextInput::make(Contact::ROLE),
                    Select::make(Contact::PREFER_SEND_MODE)
                        ->options([
                            'email' => 'Email',
                            'fax' => 'Fax',
                            'sms' => 'SMS',
                            'mail' => 'Mail',
                        ]),
                    Textarea::make(Contact::NOTES)
                        ->rows(5)
                        ->columnSpanFull()
                        ->columnSpanFull(),
                ])
                ->columnSpanFull()
                ->columns(),
        ];
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('filament.models.contact').'s';
    }

    public static function getModelLabel(): string
    {
        return __('filament.models.contact');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.models.contact').'s';
    }
}
