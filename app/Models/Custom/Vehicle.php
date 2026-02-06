<?php

namespace App\Models\Custom;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'vehicles';
    protected $fillable = ['name', 'slug', 'plate_no', 'type', 'driver', 'description'];

    
    public function issuance_headers()
    {
        return $this->hasMany(IssuanceHeader::class);
    }
    
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords(strtolower($value));
    }
}
