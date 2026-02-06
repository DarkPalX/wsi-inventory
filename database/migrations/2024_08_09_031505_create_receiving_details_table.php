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
        Schema::create('receiving_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('receiving_header_id')->nullable();
            $table->string('po_number')->nullable();
            $table->unsignedBigInteger('item_id')->nullable();
            $table->string('sku')->nullable();
            $table->decimal('price', 16, 2)->nullable()->default(0);
            $table->decimal('vat', 16, 2)->nullable()->default(0);
            $table->decimal('vat_inclusive_price', 16, 2)->nullable()->default(0);
            $table->decimal('order', 16, 0)->nullable()->default(0);
            $table->decimal('quantity', 16, 0)->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receiving_details');
    }
};
