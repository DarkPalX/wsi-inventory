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
        Schema::create('receiving_headers', function (Blueprint $table) {
            $table->id();
            $table->string('ref_no')->nullable();
            $table->string('supplier_id')->nullable();
            $table->date('date_received');
            $table->longText('book_cover')->nullable();
            $table->longText('attachments')->nullable();
            $table->longText('remarks')->nullable();
            $table->string('status')->default('SAVED');
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
        Schema::dropIfExists('receiving_headers');
    }
};
