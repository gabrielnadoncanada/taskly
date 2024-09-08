<?php

namespace Database\Seeders\Models;

use App\Enums\ItemStatus;
use App\Enums\ReceiptStatus;
use App\Models\Item;
use App\Models\Organization;
use App\Models\Receipt;
use Illuminate\Database\Seeder;

class ReceiptSeeder extends Seeder
{
    public function run()
    {
        Organization::all()->each(function ($organization) {
            foreach ($organization->customers as $customer) {
                $receipts = $this->createReceiptsForCustomer($customer, $organization);

                foreach ($receipts as $receipt) {
                    $this->createItemsForReceipt($receipt, $organization);
                }
            }
        });
    }

    private function createReceiptsForCustomer($customer, $organization)
    {
        return Receipt::factory()
            ->count(1)
            ->create([
                Receipt::CUSTOMER_ID => $customer->id,
                Receipt::CARRIER_ID => $organization->carriers->random()->id,
                Receipt::ORGANIZATION_ID => $organization->id,
            ]);
    }

    private function createItemsForReceipt($receipt, $organization)
    {
        $items = Item::factory()
            ->count(5)
            ->create([
                Item::RECEIPT_ID => $receipt->id,
                Item::ORGANIZATION_ID => $organization->id,
                Item::CUSTOMER_ID => $receipt->customer_id,
                Item::LOCALIZATION_ID => $organization->warehouses->random()->localizations->random()->id,
            ]);

        foreach ($items as $item) {
            $item->update([Item::STATUS => $this->getItemStatusBasedOnReceipt($receipt->status)]);
        }
    }

    private function getItemStatusBasedOnReceipt($receiptStatus)
    {
        return match ($receiptStatus) {
            ReceiptStatus::NEW => ItemStatus::AWAITING_RECEIPT,
            ReceiptStatus::PROCESSING => ItemStatus::randomValue(),
            ReceiptStatus::RECEIVED => ItemStatus::SHIPPED,
            ReceiptStatus::CANCELLED => ItemStatus::AWAITING_RECEIPT,
            default => ItemStatus::AWAITING_RECEIPT,
        };
    }
}
