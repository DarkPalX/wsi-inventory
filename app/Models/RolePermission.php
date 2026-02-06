<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rolepermission extends Model
{
    public $table = 'role_permission';

    protected $fillable = [ 'module_id',  'role_id', 'permission_id', 'user_id' ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    public function permission()
    {
        return $this->belongsTo('App\Permission');
    }

    public static function has_permission($module_id, $role_id, $permission_id){
        $exists = Rolepermission::where('module_id', $module_id)->where('role_id', $role_id)->where('permission_id', $permission_id)->first();
        if($exists){
            return true;
        }
        else{
            return false;
        }
    }
}
