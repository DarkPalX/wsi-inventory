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
        Schema::create('issuance_headers', function (Blueprint $table) {
            $table->id();
            $table->string('ref_no')->nullable();
            $table->string('ris_no')->nullable();
            $table->string('section_id')->nullable();
            $table->string('technical_report_no')->nullable();
            $table->string('receiver_id')->nullable();
            $table->string('actual_receiver')->nullable();
            $table->string('vehicle_id');
            $table->date('date_released');
            $table->longText('attachments')->nullable();
            $table->longText('remarks')->nullable();
            $table->string('status')->default('SAVED');
            $table->tinyInteger('is_for_sale')->default(0);
            $table->decimal('net_total', 16, 2)->nullable()->default(0.00);
            $table->timestamp('posted_at')->nullable();
            $table->unsignedBigInteger('posted_by')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->unsignedBigInteger('cancelled_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issuance_headers');
    }
};
