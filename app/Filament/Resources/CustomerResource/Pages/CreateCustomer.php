<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use App\Filament\Resources\CustomerResource\RelationManagers\AddressesRelationManager;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Resources\Pages\Concerns\HasWizard;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    use HasWizard;

    protected static string $resource = CustomerResource::class;

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
                        ->schema(CustomerResource::getCustomerDetailsSchema())
                        ->columns(),
                ]),

            Step::make(__('filament.models.address'))
                ->schema([
                    Section::make()
                        ->mutateRelationshipDataBeforeSaveUsing(function ($data) {

                            return $data;
                        })
                        ->relationship('defaultAddress', 'default_address_id')
                        ->schema(AddressesRelationManager::getFormFieldsSchema()),
                ]),
        ];
    }
}
