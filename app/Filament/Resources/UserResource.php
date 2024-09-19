<?php

namespace App\Filament\Resources;

use App\Filament\AbstractResource;
use App\Filament\Fields\PhoneInput;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Tables\Actions\SoftDeleteAction;
use App\Filament\Tables\Actions\SoftDeleteBulkAction;
use App\Models\User;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Get;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserResource extends AbstractResource implements HasShieldPermissions
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $recordTitleAttribute = 'users';

    protected static ?int $navigationSort = 6;

    protected static ?string $tenantRelationshipName = 'users';

    protected static ?string $tenantOwnershipRelationshipName = 'tenants';

    public static function leftColumn(): array
    {
        return [
            Section::make()
                ->schema(static::getFormFieldsSchema())
                ->columns()
                ->columnSpan(1),
            Section::make(__('filament.sections.password'))
                ->collapsible()
                ->collapsed(fn($record) => $record)
                ->schema(static::getPasswordFormComponent()),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make(User::FIRST_NAME),
                Tables\Columns\TextColumn::make(User::LAST_NAME),
                Tables\Columns\TextColumn::make(User::EMAIL),

                Tables\Columns\TextColumn::make(User::PHONE),
                Tables\Columns\TextColumn::make(User::EMAIL_VERIFIED_AT)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make(User::CREATED_AT)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make(User::UPDATED_AT)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                ViewAction::make(),
                Tables\Actions\EditAction::make(),
                SoftDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    SoftDeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getFormFieldsSchema(): array
    {
        return [
            Forms\Components\TextInput::make(User::FIRST_NAME)
                ->required(),
            Forms\Components\TextInput::make(User::LAST_NAME)
                ->required(),
            Forms\Components\TextInput::make(User::EMAIL)
                ->email()
                ->unique(ignoreRecord: true)
                ->required(),
            PhoneInput::make(User::PHONE)
                ->default(null),

            Forms\Components\Textarea::make(User::NOTE)
                ->rows(4)
                ->columnSpanFull(),
        ];
    }

    protected static function getPasswordFormComponent(): array
    {
        return [
            Forms\Components\TextInput::make(User::PASSWORD)
                ->password()
                ->required(fn(?User $record) => $record === null)
                ->rule(Password::default())
                ->autocomplete('new-password')
                ->dehydrated(fn($state): bool => filled($state))
                ->dehydrateStateUsing(fn($state): string => Hash::make($state))
                ->live(debounce: 500)
                ->same('passwordConfirmation'),
            Forms\Components\TextInput::make('passwordConfirmation')
                ->label('Confirmation du mot de passe')
                ->password()
                ->required()
                ->visible(fn(Get $get): bool => filled($get('password')))
                ->dehydrated(false),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation.group.administration');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'view_own',
            'create',
            'update',
            'update_own',
            'delete',
            'delete_any',
            'restore',
            'restore_any',
            'replicate',
            'reorder',
            'force_delete',
            'force_delete_any',
        ];
    }
}
