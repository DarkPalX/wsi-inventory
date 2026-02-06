<?php

namespace App\Models\Custom;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssuanceDetail extends Model
{
    use HasFactory;

    protected $table = 'issuance_details';
    protected $fillable = ['issuance_header_id', 'item_id', 'sku', 'quantity', 'cost', 'price'];


    public function issuance_header()
    {
        return $this->belongsTo(IssuanceHeader::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class)->withTrashed()->withDefault();
    }

    public static function getConsumableQuantity($ris_no, $sku)
    {
        // $issuance = IssuanceHeader::where('ris_no', $ris_no)->join('issuance_details', 'issuance_details.issuance_header_id', 'issuance_header.id')->where('issuance_details.sku', $sku)->first();
        $issuance = IssuanceHeader::where('ris_no', $ris_no)
                ->join('issuance_details', 'issuance_details.issuance_header_id', 'issuance_headers.id')
                ->where('issuance_details.sku', $sku)
                ->first();

        return $issuance->quantity ?? 0;
    }

    public static function getIssuedQty($ris_no, $item_id)
    {
        // $issuance = IssuanceHeader::where('ris_no', $ris_no)->join('issuance_details', 'issuance_details.issuance_header_id', 'issuance_header.id')->where('issuance_details.sku', $sku)->first();
        $issuance = IssuanceHeader::where('ris_no', $ris_no)
                ->join('issuance_details', 'issuance_details.issuance_header_id', 'issuance_headers.id')
                ->where('issuance_details.item_id', $item_id)
                ->sum('issuance_details.quantity');

        return $issuance ?? 0;
    }
}
