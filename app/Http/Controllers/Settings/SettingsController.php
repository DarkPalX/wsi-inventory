<?php

namespace App\Http\Controllers\Settings;

use App\Helpers\PanelHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Facades\App\Helpers\ListingHelper;
use App\Models\Setting;
use App\Models\Page;

use Auth;

class SettingsController extends Controller
{
    private $searchFields = ['module'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index($param = null)
    {
        $page = new Page();
        $page->name = "Other Settings";
        
        $setting = Setting::find(1);

        return view('theme.pages.custom.accounts.settings.index', compact('page', 'setting'));
    }

    public function update_settings(Request $request){
        Setting::where('id', 1)
        ->update([
            'company_name' => $request->company_name,
            'company_address' => $request->company_address,
            'mobile_no' => $request->mobile_no,
            'tel_no' => $request->tel_no,
            'tin_no' => $request->tin_no,

            'purchase_order_requested_by' => $request->purchase_order_requested_by,
            'purchase_order_verifier1' => $request->purchase_order_verifier1,
            'purchase_order_prepared_by' => $request->purchase_order_prepared_by,
            'purchase_order_checker' => $request->purchase_order_checker,
            'purchase_order_verifier2' => $request->purchase_order_verifier2,
            'purchase_order_approved_by' => $request->purchase_order_approved_by
        ]);
        return redirect()->back()->with('alert', 'success:Settings successfully updated');
    }
}