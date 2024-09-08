<?php

namespace App\Filament\Pages;

use App\Models\Customer;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Support\HtmlString;

class Dashboard extends BaseDashboard
{
    //    use BaseDashboard\Concerns\HasFiltersForm;
    //
    //    public function persistsFiltersInSession(): bool
    //    {
    //        return false;
    //    }

    //    public function filtersForm(Form $form): Form
    //    {
    //        return $form
    //            ->schema([
    //                Section::make()
    ////                    ->heading(fn () => new HtmlString(view('components.section-heading')))
    //                    ->schema([
    //                        Select::make('customer_id')
    //                            ->searchable()
    //                            ->options(fn () => Customer::all()->pluck(Customer::NAME, 'id')->toArray())
    //                            ->afterStateHydrated(fn (Get $get) => $get('customer_id') ?: Customer::first()?->id)
    //                            ->preload()
    //                            ->required(),
    //                        DatePicker::make('startDate')
    //                            ->maxDate(fn (Get $get) => $get('endDate') ?: now()),
    //                        DatePicker::make('endDate')
    //                            ->minDate(fn (Get $get) => $get('startDate') ?: now())
    //                            ->maxDate(now()),
    //                    ])
    //                    ->columns(3),
    //            ]);
    //    }
}
