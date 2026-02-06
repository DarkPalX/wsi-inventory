<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Custom\{PurchaseOrderHeader, PurchaseOrderDetail, Item};
use Carbon\Carbon;

class PurchaseOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Array of possible supplier IDs
        $supplier_ids = [1, 2, 3]; // Example suppliers
        
        // Loop to create 20 headers
        for ($i = 1; $i <= 20; $i++) {
            // Randomly pick a status: 'SAVED' or 'POSTED'
            $status = $i % 3 === 0 ? 'POSTED' : 'SAVED'; // Every 3rd one is 'POSTED'
            
            // Randomly select supplier IDs (1 or 2 suppliers)
            $random_suppliers = array_rand(array_flip($supplier_ids), rand(1, 2));
            $supplier_array = is_array($random_suppliers) ? $random_suppliers : [$random_suppliers];

            // Create a PurchaseOrderHeader
            $header = PurchaseOrderHeader::create([
                'supplier_id' => json_encode($supplier_array), // Store as JSON
                'date_ordered' => Carbon::now(), // Current date and time
                'total_order' => 100,
                'total_remaining' => 100,
                'attachments' => null, // No attachments
                'remarks' => 'Receiving transaction ' . $i, // Remarks per transaction
                'status' => $status, // Status is 'SAVED' or 'POSTED'
                'created_at' => now(), // Created at
                'created_by' => 1, // Example user ID who created the entry
                'updated_by' => 1, // Example user ID who updated the entry
                'posted_at' => $status === 'POSTED' ? Carbon::now() : null, // Null if 'SAVED'
                'posted_by' => $status === 'POSTED' ? 1 : null, // Null if 'SAVED'
                'cancelled_at' => null, // No cancellation
                'cancelled_by' => null, // No cancellation
            ]);

            $header->update([
                'ref_no' => PurchaseOrderHeader::generateReferenceNo($header->id)
            ]);



            // Seed 2-3 random details for each header
            $detail_count = rand(2, 3);
            for ($j = 1; $j <= $detail_count; $j++) {
                // Randomly pick a book_id between 1 and 10
                $item_id = rand(1, 20);
                $item = Item::find($item_id);

                $net_total = 0;

                // Insert PurchaseOrderDetail data
                $detail = PurchaseOrderDetail::create([
                    'purchase_order_header_id' => $header->id, // Link to the header
                    'po_number' => $header->ref_no, // Link to the header
                    'item_id' => $item->id,
                    'sku' => $item->sku,
                    'quantity' => rand(50, 100), // Random quantity between 1 and 10
                    'remaining' => 0,
                    'price' => $item->price
                ]);

                $net_total += ($detail->price * $detail->quantity);
            }

            $header->update([
                'net_total' => $net_total,
                'vat' => 12,
                'grand_total' => $net_total + ($net_total * .12)
            ]);

        }
    }
}
