<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Facades\App\Helpers\ListingHelper;

use App\Models\{Role, Permission, Page};

use Auth;

class RoleController extends Controller
{
    private $searchFields = ['name'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        Permission::module_init($this, 'role');
    }
    
    public function index()
    {
        $page = new Page();
        $page->name = "Roles";
        
        $roles = ListingHelper::simple_search(Role::class, $this->searchFields);

        $filter = ListingHelper::get_filter($this->searchFields);

        $searchType = 'simple_search';

       return view('theme.pages.custom.accounts.roles.index', compact('page', 'roles', 'filter', 'searchType'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page = new Page();
        $page->name = "Roles";

       return view('theme.pages.custom.accounts.roles.create', compact('page'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name_exists = Role::where('name', $request->name)->first();
        
        if($name_exists == null){
            Role::create([
                'name' 		  => $request->name,
                'description' => $request->description,
                'created_by'  => Auth::user()->id
            ]);
            
            return redirect()->route('accounts.roles.index')->with('alert', 'success:Well done! You successfully added a role');
        }
        else{
            return redirect()->back()->with('alert', 'danger:Failed! Role already exists');
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $page = new Page();
        $page->name = "Roles";

       return view('theme.pages.custom.accounts.roles.edit', compact('page', 'role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $name_exists = Role::where('id', '<>', $id)->where('name', $request->name)->first();
        
        if($name_exists == null){
            Role::find($id)->update([
                'name'        => $request->name,
                'description' => $request->description,
                'created_by'  => Auth::user()->id
            ]);

            return redirect()->back()->with('alert', 'success:Well done! You successfully updated a role');
        }
        else{
            return redirect()->back()->with('alert', 'danger:Failed! Role already exists');
        }
    }

    public function single_delete(Request $request)
    {
        $role = Role::findOrFail($request->roles);
        $role->delete();

        return redirect()->back()->with('alert', 'success:Well done! You successfully deleted a role');
    }

    public function multiple_delete(Request $request)
    {
        $roles = explode("|",$request->roles);

        foreach($roles as $role){
            Role::whereId((int) $role)->delete();
        }

        return redirect()->back()->with('alert', 'success:Well done! You successfully deleted multiple roles');
    }

    public function single_restore(Request $request)
    {
        $role = Role::withTrashed()->findOrFail($request->roles);
        $role->restore();

        return redirect()->back()->with('alert', 'success:Well done! You successfully restored a role');
    }

    public function multiple_restore(Request $request)
    {
        $roles = explode("|",$request->roles);

        foreach($roles as $role){
            Role::withTrashed()->whereId((int) $role)->restore();
        }

        return redirect()->back()->with('alert', 'success:Well done! You successfully restored multiple roles');
    }





















    // OLD FUNCTIONS


    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ($request->role_id != 1) {
            Role::find($request->role_id)->delete();
        }

        return back()->with('success',  __('standard.account_management.roles.delete_success'));
    }

    public function restore($id)
    {
        Role::withTrashed()->findOrFail($id)->restore();

        return back()->with('success', __('standard.account_management.roles.restore_success'));
    }
}
