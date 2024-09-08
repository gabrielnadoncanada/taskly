<?php

namespace App\Filament\Resources\ReceiptResource\Pages;

use App\Enums\ReceiptStatus;
use App\Filament\Resources\ReceiptResource;
use App\Models\Receipt;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;

class PlanReceiptItems extends EditRecord
{
    protected static string $resource = ReceiptResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns()
                    ->schema([
                        TextInput::make(Receipt::DISPLAY_RECEIPT_NUMBER)
                            ->formatStateUsing(fn ($record) => $record?->{Receipt::DISPLAY_RECEIPT_NUMBER})
                            ->disabled(),
                        DatePicker::make(Receipt::DATE)
                            ->label(__('filament.fields.receipt_date'))
                            ->default(now())
                            ->required(),
                        ToggleButtons::make(Receipt::STATUS)
                            ->inline()
                            ->columnSpanFull()
                            ->options(ReceiptStatus::class)
                            ->required(),
                    ]),
            ])
            ->columns(1);
    }

    public function getTitle(): string|Htmlable
    {
        return __('filament.titles.receive').' '.$this->getRecordTitle();
    }
}
