<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Notifications\NewUserPasswordResetNotification;
use Exception;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (! isset($this->data['password'])) {
            $data['password'] = Hash::make(\Str::random(12));
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $status = Password::broker(Filament::getAuthPasswordBroker())->sendResetLink(
            ['email' => $this->record->email],
            function ($user, $token) {
                if (! method_exists($user, 'notify')) {
                    throw new Exception('Model ['.get_class($user).'] does not have a [notify()] method.');
                }

                $notification = new NewUserPasswordResetNotification($token);
                $notification->url = Filament::getResetPasswordUrl($token, $user);

                $user->notify($notification);
            }
        );

        if ($status !== Password::RESET_LINK_SENT) {
            Notification::make()
                ->title(__($status))
                ->danger()
                ->send();

            return;
        }

        Notification::make()
            ->title(__($status))
            ->success()
            ->send();
    }
}
