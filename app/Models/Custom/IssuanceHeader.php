<?php

namespace App\Models\Custom;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ActivityLog;
use Carbon\Carbon;

class IssuanceHeader extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'issuance_headers';
    protected $fillable = ['ref_no', 'ris_no', 'section_id', 'technical_report_no', 'receiver_id', 'actual_receiver', 'vehicle_id', 'date_released', 'attachments', 'remarks', 'status', 'is_for_sale', 'net_total', 'created_by', 'updated_by', 'posted_at', 'posted_by', 'cancelled_at', 'cancelled_by'];


    public function receiver()
    {
        return $this->belongsTo(Receiver::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class)->withTrashed()->withDefault();
    }

    public static function generateReferenceNo($id)
    {
        $transaction_count = IssuanceHeader::withTrashed()->whereYear('date_released', date('Y'))->count();
        $transaction = IssuanceHeader::find($id);

        $refcode = 'I' . Carbon::parse($transaction->date_released)->format('Ymd') . '-' . $transaction_count;

        return $refcode;
    }

    public static function receivers_name($id)
    {
        $r = IssuanceHeader::withTrashed()->find($id);
        
        $receivers = '';
        $array = explode(",", rtrim(ltrim($r->receiver_id,"["),"]"));
        foreach($array as $arr){
            $receiver = \App\Models\Custom\Receiver::whereId($arr)->withTrashed()->first();
            $receivers.="<a href='".route('issuance.transactions.index')."?is_search=1&receiver=".$receiver->id."'>".$receiver->name."</a>, ";
        }
        $receivers = rtrim($receivers,", ");
        return $receivers;
    }
    
    public static function issuance_ref_no($id)
    {
        $data = IssuanceHeader::find($id);
        
        return $data->ref_no;
    }

    public static function hasIssuance($ris_no)
    {
        return IssuanceHeader::where('ris_no', $ris_no)->where('issuance_headers.status', 'POSTED')->exists();
    }

    public static function hasEquipment($ris_no)
    {
        return IssuanceDetail::whereHas('issuance_header', function ($query) use ($ris_no) {
                $query->where('ris_no', $ris_no);
            })
            ->where('item_type_id', 2)
            ->exists();
    }

    public static function getIssuanceStatus($ris_no)
    {
        $sum_of_request_qty = RequisitionDetail::where('ref_no', $ris_no)->sum('quantity');
        $sum_of_issuance_qty = IssuanceDetail::leftJoin('issuance_headers', 'issuance_headers.id', '=', 'issuance_details.issuance_header_id')
        ->where('issuance_headers.ris_no', $ris_no)
        ->where('issuance_headers.status', 'POSTED')
        ->sum('issuance_details.quantity');

        return $sum_of_request_qty - $sum_of_issuance_qty;
    }
    
    public static function hasCompleteIssuance($ris_no)
    {
        return IssuanceHeader::select(
                'issuance_headers.id',
                'issuance_details.item_id',
                DB::raw('SUM(issuance_details.quantity) as total_issued'),
                'requisition_details.quantity as requested_qty'
            )
            ->leftJoin('issuance_details', 'issuance_details.issuance_header_id', '=', 'issuance_headers.id')
            ->leftJoin('requisition_headers', 'requisition_headers.ref_no', '=', 'issuance_headers.ris_no')
            ->leftJoin('requisition_details', function($join) {
                $join->on('requisition_details.requisition_header_id', '=', 'requisition_headers.id')
                    ->on('requisition_details.item_id', '=', 'issuance_details.item_id'); // ensure same item
            })
            ->where('issuance_headers.ris_no', $ris_no)
            ->groupBy('issuance_headers.id', 'issuance_details.item_id', 'requisition_details.quantity')
            ->havingRaw('SUM(issuance_details.quantity) = SUM(requisition_details.quantity)')
            ->exists();
    }



    // ******** AUDIT LOG ******** //
    // Need to change every model
    static $oldModel;
    static $tableTitle = 'issuance';
    static $name = '0000' . 'id';
    static $unrelatedFields = ['created_at', 'updated_at', 'deleted_at'];
    static $logName = [
        'id' => 'id',
        'ref_no' => 'ref_no',
        'ris_no' => 'ris_no',
        'section_id' => 'section_id',
        'technical_report_no' => 'technical_report_no',
        'receiver_id' => 'receiver_id', 
        'actual_receiver' => 'actual_receiver', 
        'vehicle_id' => 'vehicle_id', 
        'date_released' => 'date_released', 
        'attachments' => 'attachments', 
        'remarks' => 'remarks', 
        'status' => 'status', 
        'is_for_sale' => 'is_for_sale', 
        'net_total' => 'net_total', 
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
