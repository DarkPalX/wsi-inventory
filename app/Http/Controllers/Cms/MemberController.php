<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\MemberRequest;

use Facades\App\Helpers\ListingHelper;
use App\Helpers\ModelHelper;
use App\Helpers\Setting;

use App\Models\FormAttribute;
use App\Models\Member;
use App\Models\User;
use App\Models\Permission;

use App\Mail\UpdateMemberMail;
use Response;

class MemberController extends Controller
{
    private $searchFields = ['name'];
    private $sortFields = ['updated_at', 'name', 'is_featured'];

    public function __construct()
    {
        Permission::module_init($this, 'members');
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
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $members  = ListingHelper::simple_search(Member::class, $this->searchFields);
        $filter = ListingHelper::get_filter($this->searchFields);

        $searchType = 'simple_search';

        return view('admin.members.index', compact('members', 'filter', 'searchType'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        return view('admin.members.create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(MemberRequest $request)
    {
        $newData = $request->validated();
        $newData['user_id'] = auth()->id();
        // $newData['password'] = md5($this->generateRandomString(10));

        $existingRecipient = Member::withTrashed()
            ->where('email', $newData['email'])
            ->first();

        if ($existingRecipient) {
            return redirect()->route('members.index')->with('error', __('standard.members.email_already_exists'));
        }
        else {

            $existingUserEmail = User::withTrashed()
            ->where('email', $newData['email'])
            ->first();

            if($existingUserEmail){
                return redirect()->route('members.index')->with('error', 'Email already in use by an existing user');
            }
            else{

                $getMemberId = Member::create($newData)->id;
    
                $recipient = Member::where('id', $getMemberId)->first();
    
                $mail = new UpdateMemberMail(Setting::info(), $recipient);
                $mail->view('mail.update-member')
                    ->text('mail.update-member_plain');
    
                \Mail::to($recipient->email)->send($mail->subject('Create Your Password | Taikisha'));
    
                return redirect()->route('members.index')->with('success', __('standard.members.create_success'));
            }
        }
    }

    /**
     * @param Request $request
     * @param Member $members
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $member = Member::where('id',$id)->first();

        return view('admin.members.edit', compact('member'));
    }


    /**
     * @param Request $request
     * @param Member $members
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(MemberRequest $request, Member $member)
    {
        $updateData = $request->validated();
        $updateData['user_id'] = auth()->id();

        $member->where('id', $request->id)->update($updateData);


        return back()->with('success', __('standard.members.update_success'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
 
     public function activate(Request $request)
     {
         $member = Member::withTrashed()->find($request->member_id);
         $member->update([
             'is_active' => 1,
             'user_id'   => auth()->id(),
         ]);
         $member->restore();
 
         return back()->with('success', __('Member has been activated'));
     }
 
     public function deactivate(Request $request)
     {
         $member = Member::withTrashed()->find($request->member_id);
         $member->update([
             'is_active' => 0,
             'user_id'   => auth()->id(),
         ]);
         $member->delete();
 
         return back()->with('success', __('Member has been deactivated'));
     }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        $pages = explode("|", $request->pages);

        foreach ($pages as $page) {
            $members = Member::whereId($page);
            $members->update(['status' => 'PRIVATE']);
            $members->delete();
        }

        return back()->with('success', __('standard.news.article.delete_success'));
    }

    public function restore($page)
    {
        Member::whereId($page)->restore();

        return back()->with('success', __('standard.news.article.restore_success'));
    }

    public function get_slug(Request $request)
    {
        return ModelHelper::convert_to_slug(Member::class, $request->url);
    }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
    
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
    
        return $randomString;
    }
    
    // public function reset_password(Request $request, $token, $email)
    // {
    //     return view('admin.members.reset-password', compact('token', 'email'));
    // }
    
    public function create_password(Request $request)
    {
        if($request->password == $request->password_confirmation){
            $member = Member::where('email', $request->email)->first();
            $role = \Role::where('name', 'Member')->first();

            Member::where('email', $request->email)
            ->update([
                'is_active' => 1
            ]);

            $member_exists = \User::where('member_id', $member->id)->first();

            if(!$member_exists){
                \User::create([
                    'member_id' => $member->id,
                    'name' => $member->name,
                    'firstname' => $member->name,
                    'email' => $member->email,
                    'role_id' => $role->id ?? 1, //to be changed
                    'password' => \Hash::make($request->password_confirmation),
                    'is_active' => 1
                ]);
            }
            else{
                \User::where('member_id', $member->id)
                ->update([
                    'password' => \Hash::make($request->password_confirmation)
                ]);
            }

            return redirect(config('app.url').'/congratulations');
        }
        else{
            return back()->with('error', __('Password reset failed: Passwords mismatched!'));
        }
    }
    
    public function send_reset_form(Request $request)
    {

        $existing_user = \User::where('email', $request->reset_password_email)
        ->where('member_id', '<>', null)
        ->where('role_id', Member::get_member_role_id())
        ->first();

        if($existing_user != null){
            $recipient = \User::where('email', $existing_user->email)->first();
            
            $mail = new UpdateMemberMail(Setting::info(), $recipient);
            $mail->view('mail.update-password-member')
                ->text('mail.update-password-member_plain');
    
            \Mail::to($recipient->email)->send($mail->subject('Update Your Password | Taikisha'));

            return back()->with('login_msg', __('Success! Check your email for verification link!'));
        }
        else{
            $user = \User::where('email', $request->reset_password_email)
            ->where('role_id', '<>' , Member::get_member_role_id())
            ->first();

            if($user){
                return back()->with('login_error', __('This feature is for Members only.'));
            }
            else{
                return back()->with('login_error', __('Email not recognized!'));
            }
        }
    }
    
    public function reset_password(Request $request)
    {
        if($request->password == $request->password_confirmation){
            $member = Member::where('email', $request->email)->first();
            $role = \Role::where('name', 'Member')->first();

            Member::where('email', $request->email)
            ->update([
                'is_active' => 1
            ]);

            $member_exists = \User::where('member_id', $member->id ?? 0)->first();

            if($member_exists){
                \User::where('member_id', $member->id)
                ->update([
                    'password' => \Hash::make($request->password_confirmation)
                ]);

                return redirect(config('app.url').'/congratulations');
            }
            else{
                return redirect(config('app.url'))->with('login_error', 'Invalid Request');
            }

        }
        else{
            return back()->with('error', __('Password reset failed: Passwords mismatched!'));
        }
    }

    public function members_login(Request $request)
    {
        $user = \User::where('email', $request->login_email)->first();

        if ($user && \Hash::check($request->login_password, $user->password)) {
            auth()->login($user);
            session(['member_login_session' => 'active']);
            session(['member_info' => $user]);
            return redirect(config('app.url'));
        }
        else {
            return redirect()->back()->with('login_error', 'Access denied');
        }
    }

    public function members_logout(Request $request)
    {
        \Auth::logout();
        session()->forget('member_login_session');

        return redirect(config('app.url'));
    }

    public function download_template()
    {
        $attributes = FormAttribute::orderBy('name', 'asc')->get();
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=members.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $members = Member::all();
        $columns = array('Name', 'Email');

        foreach($attributes as $attr){
            array_push($columns, $attr->name);
        }

        $callback = function() use ($members, $columns)
        {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);
    }



    public function upload_template(Request $request)
    {
        $csv = array();

        if(($handle = fopen($request->csv, 'r')) !== FALSE) {
            // necessary if a large csv file
            set_time_limit(0);

            $row = 0;
            // $header = InventoryReceiverHeader::create([
            //     'user_id' => Auth::id(),
            //     'status' => 'SAVED'
            // ]);

            while(($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                $row++;
                // number of fields in the csv
                $col_count = count($data);

                $excel_columns = array('Name', 'Email');

                $attributes = FormAttribute::orderBy('name', 'asc')->get();
                foreach($attributes as $attr){
                    array_push($excel_columns, $attr->name);
                }


                if($row > 1){

                    $name = mb_convert_encoding($data[0], "UTF-8");
                    $email = mb_convert_encoding($data[1], "UTF-8");


                    $newData = $request->all();
                    $newData = [
                        'name' => $name,
                        'email' => $email
                    ];

                    $newData['user_id'] = auth()->id();

                    $existingRecipient = Member::withTrashed()
                        ->where('email', $newData['email'])
                        ->first();

                    if (!$existingRecipient) {
                        $existingUserEmail = User::withTrashed()
                        ->where('email', $newData['email'])
                        ->first();

                        if(!$existingUserEmail){
                            $getMemberId = Member::create($newData)->id;
                
                            $recipient = Member::where('id', $getMemberId)->first();
                
                            $mail = new UpdateMemberMail(Setting::info(), $recipient);
                            $mail->view('mail.update-member')
                                ->text('mail.update-member_plain');
                
                            \Mail::to($recipient->email)->send($mail->subject('Create Your Password | Taikisha'));
                        }
                    }
                }

            }
            fclose($handle);
        }

        return back()->with('success','Successfully saved new members record');
    }
}
