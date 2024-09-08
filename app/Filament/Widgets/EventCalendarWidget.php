<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ReceiptResource;
use App\Filament\Resources\ShipmentResource;
use App\Models\Receipt;
use App\Models\Shipment;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class EventCalendarWidget extends FullCalendarWidget
{
    public function config(): array
    {
        return [
            'firstDay' => 1,
            'dayMaxEventRows' => true,
        ];
    }

    public function fetchEvents(array $info = []): array
    {
        $events = [];

        $receipts = Receipt::all();
        $shipments = Shipment::all();

        foreach ($receipts as $receipt) {
            $events[] = [
                'title' => $receipt->display_receipt_number.' - '.$receipt->customer->name,
                'start' => $receipt->{Receipt::EXPECTED_DATE},
                'url' => ReceiptResource::getUrl('edit', ['record' => $receipt]),
                'color' => 'rgb(var(--primary-700)',
            ];
        }

        foreach ($shipments as $shipment) {
            $events[] = [
                'title' => $shipment->display_shipment_number.' - '.$shipment->customer->name,
                'start' => $shipment->{Shipment::EXPECTED_DATE},
                'url' => ShipmentResource::getUrl('edit', ['record' => $shipment]),
                'color' => 'rgb(var(--success-700)',
            ];
        }

        return $events;
    }

    public function eventDidMount(): string
    {
        return <<<'JS'
        function({ event, timeText, isStart, isEnd, isMirror, isPast, isFuture, isToday, el, view }){
            el.setAttribute("x-tooltip", "tooltip");
            el.setAttribute("x-data", "{ tooltip: '"+event.title+"' }");
        }
    JS;
    }
}
