<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\ActivityLog;

class Permission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [ 'name', 'module', 'description', 'routes', 'methods', 'user_id', 'is_view_page'];

    protected $casts = [
        'routes' => 'array',
        'methods' => 'array',
    ];


    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission')->where('isAllowed', 1);
    }

    public function module_code()
    {
        return implode("_", explode(' ', $this->module));
    }

    public static function module_init($controller, $moduleName)
    {
        $permissions = Permission::where('module', $moduleName)->get();

        foreach ($permissions as $permission) {
            $controller->middleware('checkAccessRights:'.$permission->id, ['only' => $permission->methods]);
        }
    }

    public static function has_access_to_route($routeId)
    {
        if (auth()->check())
        {
            $userPermissions = auth()->user()->assign_role->permissions;
            if ($userPermissions->count())
            {
                $permissionIds = $userPermissions->pluck('id')->toArray();

                return (in_array($routeId, $permissionIds));
            }
        }

        return false;
    }

    public static function modules()
    {
        return [
            'page' => 'Pages',
            'banner' => 'Banners',
            'file_manager' => 'Files',
            'menu' => 'Menu',
            'news' => 'News',
            'news_category' => 'News Category',
            'website_settings' => 'Website Settings',
            'audit_logs' => 'Audit Trail',
            'user' => 'Users',
            'subscriber_group' => 'Subscriber Group',
            'subscriber' => 'Subscriber',
            'campaign' => 'Campaign',
            'sent_item' => 'Sent Campaign',
            'career' => 'Careers',
            'career_category' => 'Career Category',
            'resources' => 'Resources',
            'resource_categories' => 'Resource Category',
            'members' => 'Members'
        ];
    }
}
