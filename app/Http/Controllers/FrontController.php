<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;

use Facades\App\Helpers\ListingHelper;

use App\Http\Requests\ContactUsRequest;
use App\Helpers\Setting;

use Illuminate\Support\Facades\Mail;
use App\Mail\InquiryAdminMail;
use App\Mail\InquiryMail;

use App\Models\{Page, User, ActivityLog};
use \App\Models\Custom\{Item, ReceivingHeader, IssuanceDetail, IssuanceHeader};

use Auth, DB, Swift_TransportException, Str;
use Carbon\Carbon;


class FrontController extends Controller
{
    private $searchFields = ['id'];
    private $paginate = 10;
    
    public function home()
    {
        if(Auth::user()){
            $page = new Page();
            $page->name = 'Home';

            $inventory = 0;
            $total_cost = 0;
            $items = Item::all();
            foreach($items as $item){
                $inventory += $item->Inventory;
                $total_cost += $item->inventory * $item->total_cost;
            }

            $total_issuance = IssuanceDetail::all()->sum('quantity');

            $current_receiving_count = ReceivingHeader::whereStatus('SAVED')->count();
            $current_issuance_count = IssuanceHeader::whereStatus('SAVED')->count();

            $receiving_transactions = ReceivingHeader::whereBetween('created_at', [Carbon::now()->subWeek(), Carbon::now()])->orderByDesc('created_at')->get();
            $issuance_transactions = IssuanceHeader::whereBetween('created_at', [Carbon::now()->subWeek(), Carbon::now()])->orderByDesc('created_at')->get();
            // $receiving_transactions = ReceivingHeader::orderByDesc('created_at')->paginate($this->paginate);
            // $issuance_transactions = IssuanceHeader::orderByDesc('created_at')->paginate($this->paginate);

            $activity_logs = ActivityLog::orderByDesc('id')->paginate(10);

            return view('theme.pages.home', compact('page', 'inventory', 'total_cost', 'current_receiving_count', 'current_issuance_count', 'receiving_transactions', 'issuance_transactions', 'activity_logs','total_issuance'));
        }
        else{
            $page = new Page();
            $page->name = 'Login';
            
            return view('auth.login', compact('page'));
        }
    }
    
    public function signup()
    {
        $page = new Page();
        $page->name = 'Sign Up';
        
        return view('auth.signup', compact('page'));
    }

    public function submit_registration(UserRequest $request)
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
        
        Auth::login($user);

        return redirect()->route('home');
    }

    public function seach_result(Request $request)
    {
        $page = new Page();
        $page->name = 'Search Results';

        $breadcrumb = $this->breadcrumb($page);
        $pageLimit = 10;

        $searchtxt = $request->searchtxt; 
        session(['searchtxt' => $searchtxt]);

        $pages = Page::where('status', 'PUBLISHED')
            ->whereNotIn('slug', ['footer', 'home'])
            ->where(function ($query) use ($searchtxt) {
                $query->where('name', 'like', '%' . $searchtxt . '%')
                    ->orWhere('contents', 'like', '%' . $searchtxt . '%');
            })
            ->where('slug', 'create-password')
            ->where('slug', 'reset-password')
            ->where('slug', 'congratulations')
            ->select('name', 'slug')
            ->orderBy('name', 'asc')
            ->get();

        $news = Article::where('status', 'PUBLISHED')
            ->where(function ($query) use ($searchtxt) {
                $query->where('name', 'like', '%' . $searchtxt . '%')
                    ->orWhere('contents', 'like', '%' . $searchtxt . '%');
            })
            ->select('name', 'slug')
            ->orderBy('name', 'asc')
            ->get();

        $totalItems = $pages->count()+$news->count();

        $searchResult = collect($pages)->merge($news)->paginate(10);

        return view('theme.pages.search-result', compact('searchResult', 'totalItems', 'page','breadcrumb'));
    }

    public function privacy_policy(){

        $footer = Page::where('slug', 'footer')->where('name', 'footer')->first();

        $page = new Page();
        $page->name = Setting::info()->data_privacy_title;

        $breadcrumb = $this->breadcrumb($page);

        return view('theme.pages.privacy-policy', compact('page', 'footer','breadcrumb'));

    }

    public function page($slug = "home")
    {
        if (Auth::guest()) {
            $page = Page::where('slug', $slug)->where('status', 'PUBLISHED')->first();
        } else {
            $page = Page::where('slug', $slug)->first();
        }

        if ($page == null) {
            $view404 = 'theme.pages.404';
            if (view()->exists($view404)) {
                $page = new Page();
                $page->name = 'Page not found';
                return view($view404, compact('page'));
            }

            abort(404);
        }

        $breadcrumb = $this->breadcrumb($page);

        $footer = Page::where('slug', 'footer')->where('name', 'footer')->first();

        if (!empty($page->template)) {
            return view('theme.pages.'.$page->template, compact('footer', 'page', 'breadcrumb'));
        }

        $parentPage = null;
        $parentPageName = $page->name;
        $currentPageItems = [];
        $currentPageItems[] = $page->id;
        if ($page->has_parent_page() || $page->has_sub_pages()) {
            if ($page->has_parent_page()) {
                $parentPage = $page->parent_page;
                $parentPageName = $parentPage->name;
                $currentPageItems[] = $parentPage->id;
                while ($parentPage->has_parent_page()) {
                    $parentPage = $parentPage->parent_page;
                    $currentPageItems[] = $parentPage->id;
                }
            } else {
                $parentPage = $page;
                $currentPageItems[] = $parentPage->id;
            }
        }

        return view('theme.page', compact('footer', 'page', 'parentPage', 'breadcrumb', 'currentPageItems', 'parentPageName'));
    }

    // public function contact_us(ContactUsRequest $request)
    public function contact_us(Request $request)
    {
        $admins  = User::where('role_id', 1)->get();
        $client = $request->all();

        \Mail::to($client['email'])->send(new InquiryMail(Setting::info(), $client));

        foreach ($admins as $admin) {
            \Mail::to($admin->email)->send(new InquiryAdminMail(Setting::info(), $client, $admin));
        }

        // if (\Mail::failures()) {
        //     return redirect()->back()->with('error','Failed to send inquiry. Please try again later.');
        // }

        session()->flash('inquiry_msg', 'Email sent!');

        return redirect()->back();
    }

    public function breadcrumb($page)
    {
        return [
            'Home' => url('/'),
            $page->name => url('/').'/'.$page->slug
        ];
    }
    
    public function create_password(Request $request)
    {
        if($request->password == $request->password_confirmation){
            $member = Member::where('email', $request->email)->first();

            Member::where('email', $request->email)
            ->update([
                'is_active' => 1,
                'user_id' => auth()->id()
            ]);

            $member_exists = \User::where('member_id', $member->id)->first();

            if(!$member_exists){
                \User::create([
                    'member_id' => $member->id,
                    'name' => $member->name,
                    'firstname' => $member->name,
                    'email' => $member->email,
                    'role_id' => 1, //to be changed
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
}
