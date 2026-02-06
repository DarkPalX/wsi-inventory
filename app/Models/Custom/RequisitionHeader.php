<?php

namespace App\Models\Custom;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ActivityLog;
use App\Models\Scopes\SectionScope;
use Carbon\Carbon;

class RequisitionHeader extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'requisition_headers';
    protected $fillable = ['ref_no', 'date_requested', 'date_needed', 'requisition_type', 'requisition_parts_needed', 'requisition_assessment', 'vehicle_id', 'purpose', 'remarks','status', 'requested_by', 'requested_at', 'approved_by', 'approved_at', 'posted_by', 'posted_at', 'cancelled_by', 'cancelled_at', 'created_by', 'updated_by'];

    
    public function details()
    {
        return $this->hasMany(RequisitionDetail::class, 'requisition_header_id');
    }

    public static function generateReferenceNo($id)
    {
        $transaction = RequisitionHeader::find($id);
    
        $yearMonth = Carbon::parse($transaction->date_requested)->format('Ym');
    
        // Get count of all transactions (including soft-deleted) in the same month
        $transaction_count = RequisitionHeader::withTrashed()
            ->whereYear('date_requested', Carbon::parse($transaction->date_requested)->year)
            ->whereMonth('date_requested', Carbon::parse($transaction->date_requested)->month)
            ->count();
    
        $sequence = str_pad($transaction_count, 3, '0', STR_PAD_LEFT);
        $refcode = 'RIS' . $yearMonth . $sequence;
    
        return $refcode;
    }

    
    public static function getTransactionId($ris_no)
    {
        return RequisitionHeader::where('ref_no', $ris_no)->first()->id;
    }


    public static function getTransactionAging($ris_no)
    {
        $requisition = RequisitionHeader::where('ref_no', $ris_no)->where('status', 'POSTED')->first();

        if (!$requisition || !$requisition->posted_at) {
            return '0 day/s';
        }

        $requisition_posted_date = Carbon::parse($requisition->posted_at);

        if (IssuanceHeader::hasIssuance($ris_no) && IssuanceHeader::getIssuanceStatus($ris_no) == 0) {

            $issuance = IssuanceHeader::where('ris_no', $ris_no)->orderByDesc('updated_at')->first();

            if ($issuance) {
                $issuance_completion_date = Carbon::parse($issuance->updated_at);
                $days = $issuance_completion_date->diffInDays($requisition_posted_date);

                return $days . ' day/s';
            }
        }

        $days = Carbon::now()->diffInDays($requisition_posted_date);
        return $days . ' day/s';
    }
    



    // ******** AUDIT LOG ******** //
    // Need to change every model
    static $oldModel;
    static $tableTitle = 'requisition';
    static $name = '0000' . 'id';
    static $unrelatedFields = ['created_at', 'updated_at', 'deleted_at'];
    static $logName = [
        'id' => 'id',
        'ref_no' => 'ref_no',
        'date_requested' => 'date_requested', 
        'date_needed' => 'date_needed', 
        'requisition_type' => 'requisition_type', 
        'requisition_parts_needed' => 'requisition_parts_needed', 
        'requisition_assessment' => 'requisition_assessment', 
        'vehicle_id' => 'vehicle_id', 
        'purpose' => 'purpose', 
        'remarks' => 'remarks', 
        'status' => 'status', 
        'requested_by' => 'requested_by', 
        'requested_at' => 'requested_at', 
        'approved_by' => 'approved_by', 
        'approved_at' => 'approved_at', 
        'posted_at' => 'posted_at', 
        'posted_by' => 'posted_by', 
        'cancelled_at' => 'cancelled_at', 
        'cancelled_by' => 'cancelled_by',
        'created_by' => 'created_by', 
        'updated_by' => 'updated_by'
    ];
    // END Need to change every model
    
    public static function boot()
    {
        parent::boot();
        
        // static::addGlobalScope(new SectionScope);

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
