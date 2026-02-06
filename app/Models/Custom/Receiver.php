<?php

namespace App\Models\Custom;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receiver extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'receivers';
    protected $fillable = ['name', 'address', 'contact'];

    
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords(strtolower($value));
        $this->attributes['address'] = ucwords(strtolower($value));
    }
}
