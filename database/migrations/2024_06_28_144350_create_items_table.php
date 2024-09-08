<?php

use App\Enums\DimensionUnits;
use App\Enums\ItemStatus;
use App\Enums\WeightUnits;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->integer('item_number');
            $table->text('description')->nullable();
            $table->decimal('weight', 10)->nullable();
            $table->string('weight_unit')->default(WeightUnits::KG);
            $table->decimal('width', 10)->nullable();
            $table->decimal('length', 10)->nullable();
            $table->decimal('height', 10)->nullable();
            $table->string('dimension_unit')->default(DimensionUnits::CM);
            $table->string('status')->default(ItemStatus::AWAITING_RECEIPT);
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('receipt_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('shipment_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('localization_id')->constrained()->onDelete('cascade');
            $table->foreignId('organization_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
