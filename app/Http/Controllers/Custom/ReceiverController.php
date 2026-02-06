<?php

namespace App\Http\Controllers\Custom;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Facades\App\Helpers\ListingHelper;

use App\Models\{Page};
use App\Models\Custom\{Receiver};

class ReceiverController extends Controller
{
    private $searchFields = ['name'];

    public function index()
    {
        $page = new Page();
        $page->name = "Receivers";
        
        $receivers = ListingHelper::simple_search(Receiver::class, $this->searchFields);

        $filter = ListingHelper::get_filter($this->searchFields);

        $searchType = 'simple_search';

       return view('theme.pages.custom.issuance.receivers.index', compact('page', 'receivers', 'filter', 'searchType'));
    }

    public function create()
    {
        $page = new Page();
        $page->name = "Receivers";

       return view('theme.pages.custom.issuance.receivers.create', compact('page'));
    }

    public function store(Request $request)
    {
        $name_exists = Receiver::where('name', $request->name)->first();
        
        if($name_exists == null){
            Receiver::create([
                'name' => $request->name,
                'address' => $request->address,
                'contact' => $request->contact
            ]);

            return redirect()->route('issuance.receivers.index')->with('alert', 'success:Well done! You successfully added a receiver');
        }
        else{
            return redirect()->back()->with('alert', 'danger:Failed! Receiver already exists');
        }
    }

    public function edit(Receiver $receiver)
    {
        $page = new Page();
        $page->name = "Receivers";

       return view('theme.pages.custom.issuance.receivers.edit', compact('page', 'receiver'));
    }

    public function update(Request $request, Receiver $receiver)
    {
        $name_exists = Receiver::where('id', '<>', $receiver->id)->where('name', $request->name)->first();
        
        if($name_exists == null){
            $receiver->update([
                'name' => $request->name,
                'address' => $request->address,
                'contact' => $request->contact
            ]);

            return redirect()->back()->with('alert', 'success:Well done! You successfully updated a receiver');
        }
        else{
            return redirect()->back()->with('alert', 'danger:Failed! Receiver already exists');
        }
    }

    public function single_delete(Request $request)
    {
        $receiver = Receiver::findOrFail($request->receivers);
        $receiver->delete();

        return redirect()->back()->with('alert', 'success:Well done! You successfully deleted an receiver');
    }

    public function multiple_delete(Request $request)
    {
        $receivers = explode("|",$request->receivers);

        foreach($receivers as $receiver){
            Receiver::whereId((int) $receiver)->delete();
        }

        return redirect()->back()->with('alert', 'success:Well done! You successfully deleted multiple receivers');
    }

    public function single_restore(Request $request)
    {
        $receiver = Receiver::withTrashed()->findOrFail($request->receivers);
        $receiver->restore();

        return redirect()->back()->with('alert', 'success:Well done! You successfully restored an receiver');
    }

    public function multiple_restore(Request $request)
    {
        $receivers = explode("|",$request->receivers);

        foreach($receivers as $receiver){
            Receiver::withTrashed()->whereId((int) $receiver)->restore();
        }

        return redirect()->back()->with('alert', 'success:Well done! You successfully restored multiple receivers');
    }
}
