<?php

namespace App\Filament\Resources\WarehouseResource\Pages;

use App\Filament\Resources\CustomerResource;
use App\Filament\Resources\CustomerResource\RelationManagers\AddressesRelationManager;
use App\Filament\Resources\WarehouseResource;
use App\Models\Localization;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Resources\Pages\Concerns\HasWizard;
use Filament\Resources\Pages\CreateRecord;

class CreateWarehouse extends CreateRecord
{
    use HasWizard;

    protected static string $resource = WarehouseResource::class;

    public function form(Form $form): Form
    {
        return parent::form($form)
            ->schema([
                Wizard::make($this->getSteps())
                    ->startOnStep($this->getStartStep())
                    ->cancelAction($this->getCancelFormAction())
                    ->submitAction($this->getSubmitFormAction())
                    ->skippable($this->hasSkippableSteps())
                    ->contained(false),
            ])
            ->columns(null);
    }

    protected function getSteps(): array
    {
        return [
            Step::make(__('filament.titles.detail'))
                ->schema([
                    Section::make()
                        ->schema(CustomerResource::getCustomerDetailsSchema()),
                ]
                ),
            Step::make(__('filament.fields.primary_address'))
                ->schema([
                    Section::make()
                        ->relationship('address')
                        ->schema(AddressesRelationManager::getFormFieldsSchema())
                        ->columns(),
                ]),

            Step::make(__('filament.models.localization'))
                ->schema([
                    Section::make()
                        ->schema(static::getLocalizationsSchema()),
                ]),
        ];
    }

    protected static function getLocalizationsSchema(): array
    {
        return [
            TableRepeater::make('localizations')
                ->relationship('localizations')
                ->hiddenLabel()
                ->addActionLabel(__('filament.actions.add_item'))
                ->headers([
                    Header::make(__('filament.fields.'.Localization::LOCATION_IDENTIFIER)),
                ])
                ->defaultItems(1)
                ->schema([
                    TextInput::make(Localization::LOCATION_IDENTIFIER)->required(),

                ])
                ->columnSpan('full'),
        ];
    }
}
