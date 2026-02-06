<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Custom\IssuanceHeader;
use App\Models\Custom\IssuanceDetail;
use App\Models\Custom\Item;
use Carbon\Carbon;

class IssuanceTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Array of possible receiver IDs
        $receiver_ids = [1, 2, 3]; // Example receivers
        
        // Loop to create 20 headers
        for ($i = 1; $i <= 20; $i++) {
            // Randomly pick a status: 'SAVED' or 'POSTED'
            $status = $i % 3 === 0 ? 'POSTED' : 'SAVED'; // Every 3rd one is 'POSTED'
            
            // Randomly select receiver IDs (1 or 2 receivers)
            $random_receivers = array_rand(array_flip($receiver_ids), rand(1, 2));
            $receiver_array = is_array($random_receivers) ? $random_receivers : [$random_receivers];

            // Create an IssuanceHeader
            $header = IssuanceHeader::create([
                'receiver_id' => json_encode($receiver_array), // Store as JSON
                'technical_report_no' => 'TRN' . $i,
                'actual_receiver' => 'Mike Tyson', // Store as JSON
                'vehicle_id' => rand(1, 3),
                'date_released' => Carbon::now(), // Current date and time
                'attachments' => null, // No attachments
                'remarks' => 'Issuance transaction ' . $i, // Remarks per transaction
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
                'ref_no' => IssuanceHeader::generateReferenceNo($header->id)
            ]);

            // Seed 2-3 random details for each header
            $detail_count = rand(2, 3);
            for ($j = 1; $j <= $detail_count; $j++) {
                // Randomly pick a book_id between 1 and 10
                $item_id = rand(1, 20);
                $item = Item::find($item_id);

                if($item->Inventory > 0){
                    // Insert IssuanceDetail data
                    IssuanceDetail::create([
                        'issuance_header_id' => $header->id, // Link to the header
                        'item_id' => $item->id,
                        'sku' => $item->sku,
                        'quantity' => rand(1, 10), // Random quantity between 1 and 10
                        'cost' => $item->price, 
                        'price' => $item->price
                    ]);
                }
            }
        }
    }
}
