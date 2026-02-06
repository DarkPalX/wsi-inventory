<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchase_order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_order_header_id')->nullable();
            $table->string('po_number')->nullable(false);
            $table->string('ris_no')->nullable();
            $table->string('section_id')->nullable();
            $table->unsignedBigInteger('item_id')->nullable();
            $table->string('sku')->nullable();
            $table->decimal('quantity', 16, 0)->nullable()->default(0);
            $table->decimal('remaining', 16, 0)->nullable()->default(0);
            $table->decimal('price', 16, 2)->nullable()->default(0.00);
            $table->decimal('vat', 16, 2)->nullable()->default(0.00);
            $table->decimal('vat_inclusive_price', 16, 2)->nullable()->default(0.00);
            $table->string('purpose')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_details');
    }
};
