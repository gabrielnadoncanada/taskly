<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class OrdersChart extends ChartWidget
{
    protected static ?string $heading = 'Nombre de réception';

    protected static ?int $sort = 4;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Réception',
                    'data' => [0, 5, 10, 20, 25, 30, 32, 45, 50, 65, 70, 85],
                    'fill' => 'start',
                ],
            ],
            'labels' => ['Janv', 'Fév', 'Mars', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc'],
        ];
    }
}
