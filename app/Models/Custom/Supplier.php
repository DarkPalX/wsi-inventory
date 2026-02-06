<?php

namespace App\Models\Custom;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'suppliers';
    protected $fillable = ['name', 'address', 'person_in_charge', 'cellphone_no', 'telephone_no', 'check_no', 'tin_no', 'email', 'bank_name', 'bank_account_no', 'is_vatable'];
    
    
    public function purchase_order_headers()
    {
        return $this->hasMany(PurchaseOrderHeader::class);
    }

    public function receiving_headers()
    {
        return $this->hasMany(ReceivingHeader::class);
    }
    
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords(strtolower($value));
        $this->attributes['address'] = ucwords(strtolower($value));
    }
}
