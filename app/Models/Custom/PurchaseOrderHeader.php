<?php

namespace App\Models\Custom;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ActivityLog;
use Carbon\Carbon;

class PurchaseOrderHeader extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'purchase_order_headers';
    protected $fillable = ['ref_no', 'supplier_id', 'date_ordered', 'total_order', 'total_remaining', 'net_total', 'vat', 'grand_total', 'attachments', 'remarks','status', 'created_by', 'updated_by', 'posted_at', 'posted_by', 'cancelled_at', 'cancelled_by'];


    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function details()
    {
        return $this->hasMany(ReceivingDetail::class,'receiving_header_id');
    }

    public static function generateReferenceNo($id)
    {
        $transaction_count = PurchaseOrderHeader::withTrashed()->whereYear('date_ordered', date('Y'))->count();
        $transaction = PurchaseOrderHeader::find($id);

        $refcode = 'PO' . Carbon::parse($transaction->date_ordered)->format('Ymd') . '-' . $transaction_count;

        return $refcode;
    }

    // public static function refcode($id)
    // {
    //     $refcode = '';
    //     for($x = strlen($id); $x<=5; $x++){
    //         $refcode.='0';
    //     }
    //     $refcode.= $id;

    //     return $refcode;
    // }

    public static function suppliers_name($id)
    {
        $r = PurchaseOrderHeader::withTrashed()->find($id);
        
        $suppliers = '';
        $array = explode(",", rtrim(ltrim($r->supplier_id,"["),"]"));
        foreach($array as $arr){
            $supplier = \App\Models\Custom\Supplier::whereId($arr)->withTrashed()->first();
            $suppliers.="<a href='".route('receiving.purchase-orders.index')."?is_search=1&supplier=".$supplier->id."'>".$supplier->name."</a>, ";
        }
        $suppliers = rtrim($suppliers,", ");
        return $suppliers;
    }

    public function getSupplierAttribute()
    {
        $ids = json_decode($this->attributes['supplier_id'] ?? '[]', true);

        if (!empty($ids)) {
            return Supplier::find($ids[0]); // return first supplier
        }

        return null;
    }


    public static function getTransactionAging($ref_no)
    {
        $purchase_order = PurchaseOrderHeader::where('ref_no', $ref_no)->where('status', 'POSTED')->first();

        if (!$purchase_order || !$purchase_order->posted_at) {
            return '0 day/s';
        }

        $purchase_order_posted_date = Carbon::parse($purchase_order->posted_at);
        $purchase_order_details = PurchaseOrderDetail::where('purchase_order_header_id', $purchase_order->id)->where('remaining', '<>', 0)->first();
        
        $receiving_header = ReceivingHeader::where('po_number', $ref_no)->where('status', 'POSTED')->first();
      
        if (!$purchase_order_details && $receiving_header) {
            $receiving_completion_date = Carbon::parse($receiving_header->posted_at);
            $days = $receiving_completion_date->diffInDays($purchase_order_posted_date);

            return $days . ' day/s';
        }

        $days = Carbon::now()->diffInDays($purchase_order_posted_date);
        return $days . ' day/s';
    }




    // ******** AUDIT LOG ******** //
    // Need to change every model
    static $oldModel;
    static $tableTitle = 'purchase-order';
    static $name = '0000' . 'id';
    static $unrelatedFields = ['created_at', 'updated_at', 'deleted_at'];
    static $logName = [
        'id' => 'id',
        'ref_no' => 'ref_no',
        'supplier_id' => 'supplier_id', 
        'date_ordered' => 'date_ordered', 
        'total_order' => 'total_order', 
        'total_remaining' => 'total_remaining', 
        'net_total' => 'net_total', 
        'vat' => 'vat', 
        'grand_total' => 'grand_total', 
        'attachments' => 'attachments', 
        'remarks' => 'remarks', 
        'status' => 'status', 
        'created_by' => 'created_by', 
        'updated_by' => 'updated_by', 
        'posted_at' => 'posted_at', 
        'posted_by' => 'posted_by', 
        'cancelled_at' => 'cancelled_at', 
        'cancelled_by' => 'cancelled_by'
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
