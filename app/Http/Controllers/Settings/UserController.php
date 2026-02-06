<?php

namespace App\Http\Controllers\Settings;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

use Facades\App\Helpers\{ListingHelper, FileHelper};

use App\Helpers\Setting;

use Illuminate\Support\Facades\Input;
use App\Http\Requests\UserRequest;

use App\Mail\UpdatePasswordMail;
use App\Mail\AddNewUserMail;

use App\Models\{Page, Permission, Role, User, ActivityLog};

use Auth, Str;


class UserController extends Controller
{
    use SendsPasswordResetEmails;

    private $searchFields = ['name'];

    public function __construct()
    {
        Permission::module_init($this, 'user');
    }

    public function index()
    {
        $page = new Page();
        $page->name = "Users";

        $listing = ListingHelper::required_condition('role_id', '<>', 6);
        $users = $listing->simple_search(User::class, $this->searchFields);

        // Simple search init data
        $filter = $listing->get_filter($this->searchFields);

        $searchType = 'simple_search';

        // return view('admin.users.index',compact('users','filter', 'searchType'));
       return view('theme.pages.custom.accounts.users.index', compact('page', 'users', 'filter', 'searchType'));

    }

    public function create()
    {
        $page = new Page();
        $page->name = "Users";

        $roles = Role::orderBy('name','asc')->get();
        return view('theme.pages.custom.accounts.users.create',compact('page', 'roles'));
    }

    public function store(UserRequest $request)
    {
        $email_exists = User::where('email', $request->email)->exists();
        if ($email_exists) {
            throw ValidationException::withMessages([
                'email' => 'This username has already been taken.',
            ]);
        }

        $additionalRules = [
            'email' => 'required|max:191',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
        ];

        $requestData = $request->validate(array_merge($request->rules(), $additionalRules));

        $requestData['name'] = $request->firstname.' '.$request->lastname;
        $requestData['is_active'] = 1;
        $requestData['user_id'] = Auth::id();
        $requestData['remember_token'] = Str::random(10);
        $requestData['password'] = \Hash::make($request->confirm_password, array('rounds'=>12));

        $user = User::create($requestData);

        // $user->send_reset_temporary_password_email();

       return redirect()->route('accounts.users.index')->with('alert', 'success:Well done! You successfully added a user');
    }

    public function edit($id)
    {
        $page = new Page();
        $page->name = "Users";

        $roles = Role::orderBy('name','asc')->get();
        $user = User::where('id',$id)->first();

        return view('theme.pages.custom.accounts.users.edit',compact('page', 'user', 'roles'));
    }

    public function update(UserRequest $request, User $user)
    {
        $email_exists = User::where('id', '<>', $user->id)->where('email', $request->email)->exists();
        if ($email_exists) {
            throw ValidationException::withMessages([
                'email' => 'This username has already been taken.',
            ]);
        }

        $requestData = $request->validated();

        $requestData['name'] = $request->firstname.' '.$request->lastname;
        $requestData['user_id'] = Auth::id();

        $user->update($requestData);

        return redirect()->back()->with('alert', 'success:Well done! You successfully updated a user');
    }

    public function edit_profile()
    {
        $page = new Page();
        $page->name = "Edit Profile";

        $roles = Role::orderBy('name','asc')->get();
        $user = User::where('id',Auth::user()->id)->first();

        return view('theme.pages.custom.accounts.users.edit-profile',compact('page', 'user', 'roles'));
    }

    public function update_profile(UserRequest $request)
    {
        $email_exists = User::where('id', '<>', Auth::id())->where('email', $request->email)->exists();
        if ($email_exists) {
            throw ValidationException::withMessages([
                'email' => 'This username has already been taken.',
            ]);
        }

        $requestData = $request->validated();

        $requestData['name'] = $request->firstname.' '.$request->lastname;
        $requestData['user_id'] = Auth::id();

        User::where('id', Auth::id())->update($requestData);

        return redirect()->back()->with('alert', 'success:Well done! You successfully updated your profile');
    }

    public function update_email(Request $request)
    {
        $email_exists = User::where('id', '<>', Auth::id())->where('email', $request->email)->exists();
        if ($email_exists) {
            throw ValidationException::withMessages([
                'email' => 'This username has already been taken.',
            ]);
        }

        $requestData['user_id'] = Auth::id();
        $requestData['email'] = $request->email;

        User::where('id', Auth::id())->update($requestData);

        return redirect()->back()->with('alert', 'success:Well done! You successfully updated your username');
    }

    public function update_password(Request $request)
    {
        Validator::make($request->all(), [
            'password' => [
                'required',
                'min:8'
                // 'regex:/[a-z]/', // must contain at least one lowercase letter
                // 'regex:/[A-Z]/', // must contain at least one uppercase letter
                // 'regex:/[0-9]/', // must contain at least one digit
                // 'regex:/[@$!%*#?&]/', // must contain a special character
            ],
            'confirm_password' => 'required|same:password',
            'current_password' => ['required', function ($attribute, $value, $fail) {
                if (!\Hash::check($value, Auth::user()->password)) {
                    return $fail(__('The current password is incorrect.'));
                }
            }]
        ])->validate();

        $user = auth()->user();

        $is_updated = $user->update(['password' => \Hash::make($request->confirm_password, array('rounds'=>12))]);

        if ($is_updated) {
            Auth::logout();
            return redirect()->back()->with('alert', 'success:Well done! You successfully updated your username');
        } else {
            return redirect()->back()->with('alert', 'danger:Failed changing password');
        }
    }

    public function update_avatar(Request $request){
        $avatar = $request->hasFile('avatar') ? FileHelper::move_to_files_folder($request->file('avatar'), 'images/profile/'. Auth::user()->id)['url'] : null;
        User::where('id', Auth::user()->id)
        ->update([
            'avatar' => $avatar
        ]);

        return redirect()->back()->with('alert', 'success:Well done! You successfully updated your avatar');
    }

    public function single_delete(Request $request)
    {
        $user = User::findOrFail($request->users);
        $user->delete();

        return redirect()->back()->with('alert', 'success:Well done! You successfully deleted a user');
    }

    public function multiple_delete(Request $request)
    {
        $users = explode("|",$request->users);

        foreach($users as $user){
            User::whereId((int) $user)->delete();
        }

        return redirect()->back()->with('alert', 'success:Well done! You successfully deleted multiple users');
    }

    public function single_restore(Request $request)
    {
        $user = User::withTrashed()->findOrFail($request->users);
        $user->restore();

        return redirect()->back()->with('alert', 'success:Well done! You successfully restored a user');
    }

    public function multiple_restore(Request $request)
    {
        $users = explode("|",$request->users);

        foreach($users as $user){
            User::withTrashed()->whereId((int) $user)->restore();
        }

        return redirect()->back()->with('alert', 'success:Well done! You successfully restored multiple users');
    }










    //OLD FUNCTIONS


    public function deactivate(Request $request)
    {
        $user = User::find($request->user_id);

        $user->update([
            'is_active' => 0,
            'user_id'   => Auth::id(),
        ]);
        $user->delete();

        return back()->with('success', __('standard.users.status_success', ['status' => 'deactivated']));
    }

    public function activate(Request $request)
    {
        $user = User::withTrashed()->find($request->user_id);

        $user->update([
            'is_active' => 1,
            'user_id'   => Auth::id(),
        ]);
        $user->restore();

        return back()->with('success', __('standard.users.status_success', ['status' => 'activated']));
    }


    public function show($id, $filter = null)
    {
        $searchFields = ['db_table'];
        $filterFields = ['activity_date', 'db_table'];

        $user = User::withTrashed()->find($id);


        $listing = ListingHelper::required_condition('log_by', '=', $id)->sort_by('activity_date')->filter_fields($filterFields);
        $logs = $listing->simple_search(ActivityLog::class, $searchFields);

        // Simple search init data
        $filter = $listing->get_filter($searchFields);
        $searchType = 'simple_search';

        return view('admin.users.profile',compact('user','logs', 'filter', 'searchType'));
    }

    public function filter(Request $request)
    {
        $params = $request->all();

        return $this->apply_filter($params);
    }

    public function apply_filter($param = null)
    {
        $user = User::where('id',$param['id'])->first();

        if(isset($param['order'])){
            $logs = ActivityLog::where('log_by',$param['id'])->orderBy($param['sort'],$param['order'])->paginate($param['pageLimit']);
        } else {
            $logs = ActivityLog::where('log_by',$param['id'])->paginate($param['pageLimit']);
        }

        return view('admin.users.profile',compact('user','logs','param'));
    }

}
