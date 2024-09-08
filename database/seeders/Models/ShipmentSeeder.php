<?php

namespace Database\Seeders\Models;

use App\Enums\ItemStatus;
use App\Enums\ReceiptStatus;
use App\Enums\ShipmentStatus;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Organization;
use App\Models\Receipt;
use App\Models\Shipment;
use Illuminate\Database\Seeder;

class ShipmentSeeder extends Seeder
{
    public function run()
    {
        Organization::all()->each(function ($organization) {
            $customers = $this->getCustomers($organization->id);

            foreach ($customers as $customer) {
                $receipts = $this->getReceiptsForCustomer($customer->id);

                foreach ($receipts as $receipt) {
                    $this->createShipmentForReceipt($receipt);
                }
            }
        });
    }

    private function getCustomers($organizationId)
    {
        return Customer::where(Customer::ORGANIZATION_ID, '=', $organizationId)->get();
    }

    private function getReceiptsForCustomer($customerId)
    {
        return Receipt::where(Receipt::CUSTOMER_ID, '=', $customerId)->get();
    }

    private function createShipmentForReceipt($receipt)
    {
        $defaultAddress = $receipt->customer->defaultAddress;

        $shipment = Shipment::factory()->create([
            Shipment::CARRIER_ID => $receipt->carrier_id,
            Shipment::ADDRESS_ID => $defaultAddress ? $defaultAddress->id : null,
            Shipment::CUSTOMER_ID => $receipt->customer_id,
            Shipment::ORGANIZATION_ID => $receipt->organization_id,
            Shipment::STATUS => $this->mapReceiptStatusToShipmentStatus($receipt->status),
        ]);

        $items = $this->getItemsForShipment($receipt->id);

        foreach ($items as $item) {
            $item->shipment_id = $shipment->id;
            $item->save();
        }
    }

    private function getItemsForShipment($receiptId)
    {
        return Item::where(Item::RECEIPT_ID, '=', $receiptId)
            ->whereIn(Item::STATUS, [ItemStatus::AWAITING_SHIPMENT, ItemStatus::SHIPPED])
            ->get();
    }

    private function mapReceiptStatusToShipmentStatus($receiptStatus)
    {
        return match ($receiptStatus) {
            ReceiptStatus::NEW => ShipmentStatus::NEW,
            ReceiptStatus::PROCESSING => ShipmentStatus::PROCESSING,
            ReceiptStatus::RECEIVED => ShipmentStatus::DELIVERED,
            ReceiptStatus::CANCELLED => ShipmentStatus::CANCELLED,
            default => ShipmentStatus::NEW,
        };
    }
}
