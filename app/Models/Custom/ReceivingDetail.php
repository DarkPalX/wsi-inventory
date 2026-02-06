<?php

namespace App\Models\Custom;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceivingDetail extends Model
{
    use HasFactory;
    
    protected $table = 'receiving_details';
    protected $fillable = ['receiving_header_id', 'po_number', 'item_id', 'sku', 'price', 'vat', 'vat_inclusive_price', 'order', 'quantity'];


    public function item()
    {
        return $this->belongsTo(Item::class)->withTrashed()->withDefault();
    }

}
