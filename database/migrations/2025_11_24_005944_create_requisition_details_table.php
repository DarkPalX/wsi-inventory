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
        Schema::create('requisition_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requisition_header_id')->nullable();
            $table->string('ref_no')->nullable(false);
            $table->unsignedBigInteger('item_id')->nullable();
            $table->string('sku')->nullable();
            $table->decimal('quantity', 16, 0)->nullable()->default(0);
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
        Schema::dropIfExists('requisition_details');
    }
};
