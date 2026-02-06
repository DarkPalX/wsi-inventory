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
        Schema::create('issuance_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('issuance_header_id')->nullable();
            $table->unsignedBigInteger('book_id')->nullable();
            $table->string('sku')->nullable();
            $table->decimal('quantity', 16, 0)->nullable()->default(0);
            $table->decimal('cost', 16, 2)->nullable()->default(0.00);
            $table->decimal('price', 16, 2)->nullable()->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issuance_details');
    }
};
