<?php

namespace App\Filament\Actions;

use App\Enums\DimensionUnits;
use App\Enums\WeightUnits;
use App\Filament\Fields\DecimalInput;
use App\Models\Item;
use App\Models\Localization;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Facades\Filament;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Wizard\Step;
use Filament\Tables\Actions\CreateAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class GenerateItemsAction extends CreateAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->using(function (array $data, $model, $livewire): Model {
            $baseData = $this->getBaseData($data, $model, $livewire);
            $allRecords = $this->generateAllRecords($data['items'], $baseData, $model);

            $createdModels = $allRecords->map(function ($record) use ($model) {
                unset($record['items']);

                return $model::withoutEvents(fn () => $model::create($record));
            });

            return $createdModels->first();
        });

        $this->setUpSteps();
    }

    private function getBaseData(array $data, $model, $livewire): array
    {
        $organizationId = Filament::getTenant()->id;
        $receiptId = $livewire->ownerRecord->id;
        $customerId = $livewire->ownerRecord->{$model::CUSTOMER_ID};

        return array_merge($data, [
            $model::TENANT_ID => $organizationId,
            $model::RECEIPT_ID => $receiptId,
            $model::CUSTOMER_ID => $customerId,
            $model::ITEM_NUMBER => $model::generateItemNumber($organizationId) - 1,
        ]);
    }

    private function generateAllRecords(array $items, array $baseData, $model)
    {
        $allRecords = collect();
        $itemCount = 1;

        foreach ($items as $item) {
            $quantity = $item[$model::QUANTITY];
            $localizationId = $item[$model::LOCALIZATION_ID];

            $records = collect(range(1, $quantity))->map(function ($i) use ($baseData, $model, $localizationId, &$itemCount) {
                $recordData = $baseData;
                $recordData[$model::ITEM_NUMBER] += $i + $itemCount;
                $recordData[$model::LOCALIZATION_ID] = $localizationId;

                return $recordData;
            });

            $itemCount++;
            $allRecords = $allRecords->merge($records);
        }

        return $allRecords;
    }

    private function setUpSteps(): void
    {
        $this->steps([
            Step::make(__('filament.fields.quantity_localization'))
                ->schema([
                    $this->createItemsRepeater(),
                ])
                ->columns(),
            Step::make(__('filament.fields.details'))
                ->schema([
                    Textarea::make(Item::DESCRIPTION),
                    $this->createWeightFieldset(),
                    $this->createDimensionFieldset(),
                ]),
        ]);
    }

    private function createItemsRepeater(): TableRepeater
    {
        return TableRepeater::make('items')
            ->hiddenLabel()
            ->addActionLabel(__('filament.actions.add_item'))
            ->headers([
                Header::make(__('filament.fields.'.Item::QUANTITY)),
                Header::make(__('filament.fields.'.Item::LOCALIZATION_ID)),
            ])
            ->defaultItems(1)
            ->schema([
                TextInput::make(Item::QUANTITY)->required()
                    ->integer()->default(1),
                Select::make(Item::LOCALIZATION_ID)
                    ->relationship(
                        name: 'localization',
                        titleAttribute: 'id',
                        modifyQueryUsing: fn (Builder $query) => $query->whereHas('warehouse', function ($query) {
                            $query->where('tenant_id', filament()->getTenant()->id);
                        })
                    )
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => $record->{Localization::DISPLAY_LOCALIZATION_NUMBER})
                    ->required(),
            ])
            ->columnSpan('full');
    }

    private function createWeightFieldset(): Fieldset
    {
        return Fieldset::make(__('filament.fields.weight'))
            ->schema([
                ToggleButtons::make(Item::WEIGHT_UNIT)
                    ->required()
                    ->inline()
                    ->options(WeightUnits::class)
                    ->default(WeightUnits::LB),
                DecimalInput::make(Item::WEIGHT),
            ]);
    }

    private function createDimensionFieldset(): Fieldset
    {
        return Fieldset::make(__('filament.fields.dimension'))
            ->columns(4)
            ->schema([
                ToggleButtons::make(Item::DIMENSION_UNIT)
                    ->required()
                    ->inline()
                    ->options(DimensionUnits::class)
                    ->default(DimensionUnits::CM),
                DecimalInput::make(Item::WIDTH),
                DecimalInput::make(Item::LENGTH),
                DecimalInput::make(Item::HEIGHT),
            ]);
    }
}
