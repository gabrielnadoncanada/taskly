<?php

namespace App\Filament\Resources\ShipmentResource\Pages;

use App\Enums\ShipmentStatus;
use App\Filament\Resources\ShipmentResource;
use App\Models\Shipment;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;

class PlanShipmentItems extends EditRecord
{
    protected static string $resource = ShipmentResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns()
                    ->schema([
                        TextInput::make(Shipment::DISPLAY_SHIPMENT_NUMBER)
                            ->formatStateUsing(fn ($record) => $record?->{Shipment::DISPLAY_SHIPMENT_NUMBER})
                            ->disabled(),
                        DatePicker::make(Shipment::DATE)
                            ->label(__('filament.fields.shipment_date'))
                            ->default(now())
                            ->required(),
                        ToggleButtons::make(Shipment::STATUS)
                            ->columnSpanFull()
                            ->inline()
                            ->options(ShipmentStatus::class)
                            ->required(),
                    ]),
            ])
            ->columns(1);
    }

    public function getTitle(): string|Htmlable
    {
        return __('filament.titles.prepare').' '.$this->getRecordTitle();
    }
}
