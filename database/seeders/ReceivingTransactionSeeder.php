<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Custom\{PurchaseOrderHeader, PurchaseOrderDetail, ReceivingHeader, ReceivingDetail, Item};
use Carbon\Carbon;

class ReceivingTransactionSeeder extends Seeder
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
            
            // Randomly select po_number
            $po_header = PurchaseOrderHeader::where('status', 'POSTED')->inRandomOrder()->first();

            // Randomly select supplier IDs (1 or 2 suppliers)
            $random_suppliers = array_rand(array_flip($supplier_ids), rand(1, 2));
            $supplier_array = is_array($random_suppliers) ? $random_suppliers : [$random_suppliers];

            // Create a ReceivingHeader
            $header = ReceivingHeader::create([
                'po_number' => $po_header->ref_no,
                'supplier_id' => json_encode($supplier_array), // Store as JSON
                'date_received' => Carbon::now(), // Current date and time
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
                'ref_no' => ReceivingHeader::generateReferenceNo($header->id)
            ]);

            //for po details
            $po_details = PurchaseOrderDetail::where('po_number', $po_header->ref_no)->get();

            // Seed 2-3 random details for each header
            foreach ($po_details as $po_detail) {

                // Insert ReceivingDetail data
                ReceivingDetail::create([
                    'receiving_header_id' => $header->id, // Link to the header
                    'po_number' => $po_header->ref_no,
                    'item_id' => $po_detail->item_id,
                    'sku' => $po_detail->sku,
                    'price' => $po_detail->price,
                    'order' => $po_detail->quantity,
                    'quantity' => $po_detail->quantity,
                ]);

            }
        }
    }
}
