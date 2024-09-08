<?php

use App\Enums\ReceiptStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_order_identifier');
            $table->integer('receipt_number');
            $table->date('expected_date')->default(now());
            $table->date('date');
            $table->string('status')->default(ReceiptStatus::NEW);
            $table->foreignId('carrier_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('organization_id')->nullable()->constrained()->onDelete('cascade');
            $table->unique(['receipt_number', 'organization_id']);
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
