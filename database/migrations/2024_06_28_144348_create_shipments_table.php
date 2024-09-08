<?php

use App\Enums\ShipmentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->integer('shipment_number');
            $table->string('purchase_order_identifier');
            $table->date('expected_date')->default(now());
            $table->date('date');
            $table->string('status')->default(ShipmentStatus::NEW->value);
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('carrier_id')->constrained()->onDelete('cascade');
            $table->foreignId('address_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('organization_id')->nullable()->constrained()->onDelete('cascade');
            $table->unique(['shipment_number', 'organization_id']);
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
