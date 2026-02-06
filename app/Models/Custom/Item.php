<?php

namespace App\Models\Custom;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\ItemFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ActivityLog;


class Item extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'items';
    protected $fillable = [ 'sku', 
                            'name', 
                            'slug', 
                            'supplier_id', 
                            'location', 
                            'category_id',
                            'type_id',
                            'image_cover',
                            'price',
                            'minimum_stock',
                            'is_inventory'];


    protected static function newFactory()
    {
        return ItemFactory::new();
    }

    public function category()
    {
        return $this->belongsTo(ItemCategory::class)->withTrashed()->withDefault();
    }

    public function type()
    {
        return $this->belongsTo(ItemType::class)->withTrashed()->withDefault();
    }

    public function receiving_details()
    {
        return $this->hasMany(ReceivingDetail::class);
    }

    public function requisition_details()
    {
        return $this->hasMany(RequisitionDetail::class);
    }

    public function setNameAttribute($value)
    {
        // Define a pattern to match uppercase words (possible acronyms)
        $pattern = '/\b[A-Z]{2,}\b/';
        
        // Replace each acronym with its original form
        $acronyms = [];
        preg_match_all($pattern, $value, $matches);
        foreach ($matches[0] as $acronym) {
            $acronyms[$acronym] = $acronym;
        }
        
        // Convert the entire string to lowercase and then capitalize each word
        $value = ucwords(strtolower($value));
        
        // Restore the acronyms to their uppercase form
        foreach ($acronyms as $acronym) {
            $value = str_replace(ucwords(strtolower($acronym)), $acronym, $value);
        }
        
        // Set the formatted name
        $this->attributes['name'] = $value;
    }

    protected function getInventoryAttribute()
    {
        $total_received = ReceivingDetail::where('item_id', $this->id)
            ->where('receiving_headers.status', 'POSTED')
            ->join('receiving_headers', 'receiving_headers.id', '=', 'receiving_details.receiving_header_id')
            ->groupBy('item_id')
            ->sum('receiving_details.quantity');
        
        $total_issued = IssuanceDetail::where('item_id', $this->id)
            ->where('issuance_headers.status', 'POSTED')
            ->join('issuance_headers', 'issuance_headers.id', '=', 'issuance_details.issuance_header_id')
            ->groupBy('item_id')
            ->sum('issuance_details.quantity');

        $inventory = $total_received - $total_issued;

        return $inventory > 0 ? $inventory : 0;
    }

    public function getReceivingQtyAttribute() {

        $value = ReceivingDetail::where('item_id', $this->id)
                ->where('receiving_headers.status', 'POSTED')
                ->join('receiving_headers', 'receiving_headers.id', '=', 'receiving_details.receiving_header_id')
                ->sum('receiving_details.quantity');

        return $value;
    }

    public function getIssuedFreeAttribute() {

        $free = IssuanceDetail::where('item_id', $this->id)
                ->where('issuance_headers.status', 'POSTED')
                ->join('issuance_headers', 'issuance_headers.id', '=', 'issuance_details.issuance_header_id')
                ->where('issuance_headers.is_for_sale', 0)
                ->sum('issuance_details.quantity');

        return $free;
    }

    public function getIssuedSaleAttribute() {

        $sale = IssuanceDetail::where('item_id', $this->id)
                ->where('issuance_headers.status', 'POSTED')
                ->join('issuance_headers', 'issuance_headers.id', '=', 'issuance_details.issuance_header_id')
                ->where('issuance_headers.is_for_sale', 1)
                ->sum('issuance_details.quantity');

        return $sale;
    }

    public function getMACAttribute($date = null) {

        if(!$date)
            $date = date('Y-m-d');
        
        $qry = \DB::Select("select x.* from
            (SELECT ihh.date_released as dyt, ihh.ref_no as ref, 
            idd.item_id, idd.quantity as qty, NULL as cost, 'out' as tayp
            FROM issuance_details idd left join issuance_headers ihh on 
            ihh.id=idd.issuance_header_id where ihh.date_released<='".$date."'
            and idd.item_id=".$this->id." 

            UNION ALL

            SELECT rh.date_received as dyt, rh.ref_no as ref, 
            rd.item_id, rd.quantity as qty, rd.price as cost,  'in' as tayp
            FROM receiving_details rd left join receiving_headers rh on 
            rh.id=rd.receiving_header_id where rh.date_received<='".$date."'
            and rd.item_id=".$this->id." 

            ) x

            ORDER by x.dyt, x.tayp");

        $inventoryQty = 0;
        $movingAvgCost = 0.0;

        foreach ($qry as $tx) {
            $type = $tx->tayp;
            $qty = (int) $tx->qty;
            // $cost = isset($tx->cost) ? (float) $tx->cost : $movingAvgCost;
            $cost = $tx->cost !== null ? (float) $tx->cost : $movingAvgCost;

            if ($type === 'in') {
                $totalCost = ($inventoryQty * $movingAvgCost) + ($qty * $cost);
                $inventoryQty += $qty;
                $movingAvgCost = $inventoryQty > 0 ? $totalCost / $inventoryQty : 0.0;
            } elseif ($type === 'out') {
                $inventoryQty -= $qty;
            
            }
        }
      
        $originalPrice = (float) \DB::table('items')
            ->where('id', $this->id)
            ->value('price');

        return round($movingAvgCost > 0 ? $movingAvgCost : $originalPrice , 2);
    }

    // public function getPriceAttribute($value)
    // {
    //     return $this->mac;
    // }







    // ******** AUDIT LOG ******** //
    // Need to change every model
    static $oldModel;
    static $tableTitle = 'item';
    static $name = 'name';
    static $unrelatedFields = ['id', 'created_at', 'updated_at', 'deleted_at'];
    static $logName = [
        'sku' => 'sku', 
        'name' => 'name',
        'slug' => 'slug', 
        'supplier_id' => 'supplier_id', 
        'location' => 'location', 
        'category_id' => 'category_id',
        'type_id' => 'type_id',
        'image_cover' => 'image_cover',
        'price' => 'price', 
        'minimum_stock' => 'minimum_stock', 
        'is_inventory' => 'is_inventory', 
    ];
    
    // END Need to change every model

    public static function boot()
    {
        parent::boot();

        self::created(function($model) {
            $name = $model[self::$name];

            ActivityLog::create([
                'log_by' => auth()->id(),
                'activity_type' => 'insert',
                'dashboard_activity' => 'created a new '. self::$tableTitle,
                'activity_desc' => 'created the '. self::$tableTitle .' '. $name,
                'activity_date' => date("Y-m-d H:i:s"),
                'db_table' => $model->getTable(),
                'old_value' => '',
                'new_value' => $name,
                'reference' => $model->id 
            ]);
        });

        self::updating(function($model) {
            self::$oldModel = $model->fresh();
        });

        self::updated(function($model) {
            $name = $model[self::$name];
            $oldModel = self::$oldModel->toArray();
            foreach ($oldModel as $fieldName => $value) {
                if (in_array($fieldName, self::$unrelatedFields)) {
                    continue;
                }

                $oldValue = $model[$fieldName];
                if ($oldValue != $value) {
                    ActivityLog::create([
                        'log_by' => auth()->id(),
                        'activity_type' => 'update',
                        'dashboard_activity' => 'updated the '. self::$tableTitle .' '. self::$logName[$fieldName],
                        'activity_desc' => 'updated the '. self::$tableTitle .' '. self::$logName[$fieldName] .' of '. $name .' from '. $oldValue .' to '. $value,
                        'activity_date' => date("Y-m-d H:i:s"),
                        'db_table' => $model->getTable(),
                        'old_value' => $oldValue,
                        'new_value' => $value,
                        'reference' => $model->id
                    ]);
                }
            }
        });

        self::deleted(function($model){
            $name = $model[self::$name];
            ActivityLog::create([
                'log_by' => auth()->id(),
                'activity_type' => 'delete',
                'dashboard_activity' => 'deleted a '. self::$tableTitle,
                'activity_desc' => 'deleted the '. self::$tableTitle .' '. $name,
                'activity_date' => date("Y-m-d H:i:s"),
                'db_table' => $model->getTable(),
                'old_value' => '',
                'new_value' => '',
                'reference' => $model->id
            ]);
        });
    }
}
