<?php

namespace App\Filament\Widgets;

use App\Enums\ItemStatus;
use App\Models\Client;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverviewWidget extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static bool $isLazy = false;

    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $startDate = $this->filters['startDate'] ?? null ? Carbon::parse($this->filters['startDate']) : null;
        $endDate = $this->filters['endDate'] ?? null ? Carbon::parse($this->filters['endDate']) : now();
        $customerId = $this->getCustomerId();

        if (! $customerId) {
            return [];
        }

        $unloaded = $this->getUnloadedCount($customerId, $startDate, $endDate);
        $loaded = $this->getLoadedCount($customerId, $startDate, $endDate);
        $byMonth = $this->getByMonthCount($customerId);

        return [
            Stat::make('Nombre de déchargement', $unloaded)
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->description('Augmentation de 3%')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            //            Stat::make('Nombre de chargements', $loaded)
            //                ->chart([17, 16, 14, 15, 14, 13, 12])
            //                ->description('Augmentation de 3%')
            //                ->descriptionIcon('heroicon-m-arrow-trending-up')
            //                ->color('danger'),
            //            Stat::make('Nombre d\'entreposage', array_sum($byMonth))
            //                ->chart([15, 4, 10, 2, 12, 4, 12])
            //                ->description('Augmentation de 3%')
            //                ->descriptionIcon('heroicon-m-arrow-trending-up')
            //                ->color('success'),
        ];
    }

    protected function getUnloadedCount($customerId, $startDate, $endDate)
    {
        return Client::find($customerId)
            ->items()
            ->where('items.status', ItemStatus::STORED)
            ->whereHas('receipt', function ($query) use ($startDate, $endDate) {
                if ($startDate) {
                    $query->whereBetween('receipts.date', [$startDate, $endDate]);
                }
            })->count();
    }

    protected function getLoadedCount($customerId, $startDate, $endDate)
    {
        return Client::find($customerId)
            ->items()
            ->where('items.status', ItemStatus::SHIPPED)
            ->whereHas('shipment', function ($query) use ($startDate, $endDate) {
                if ($startDate) {
                    $query->whereBetween('shipments.date', [$startDate, $endDate]);
                }
            })->count();
    }

    protected function getByMonthCount($customerId)
    {
        return DB::table('items')
            ->join('receipts', 'receipts.id', '=', 'items.receipt_id')
            ->where('receipts.customer_id', $customerId)
            ->where(function ($query) {
                $query->where('items.status', ItemStatus::STORED)
                    ->orWhere('items.status', ItemStatus::AWAITING_SHIPMENT);
            })
            ->select(DB::raw('MONTH(receipts.date) as month'), DB::raw('count(*) as count'))
            ->groupBy(DB::raw('MONTH(receipts.date)'))
            ->get()
            ->pluck('count', 'month')
            ->toArray();
    }

    protected function getCustomerId()
    {
        $customerId = $this->filters['customer_id'];

        if ($customerId && Client::find($customerId)) {
            return $customerId;
        }

        return null;
    }
}
