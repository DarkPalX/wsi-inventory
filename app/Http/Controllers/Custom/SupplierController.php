<?php

namespace App\Http\Controllers\Custom;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Facades\App\Helpers\ListingHelper;

use App\Models\{Page};
use App\Models\Custom\{Supplier};

class SupplierController extends Controller
{
    private $searchFields = ['name'];

    public function index()
    {
        $page = new Page();
        $page->name = "Suppliers";
    
        $suppliers = ListingHelper::simple_search(Supplier::class, $this->searchFields);

        $filter = ListingHelper::get_filter($this->searchFields);

        $searchType = 'simple_search';

       return view('theme.pages.custom.receiving.suppliers.index', compact('page', 'suppliers', 'filter', 'searchType'));
    }

    public function create()
    {
        $page = new Page();
        $page->name = "Suppliers";

       return view('theme.pages.custom.receiving.suppliers.create', compact('page'));
    }

    public function store(Request $request)
    {
        $name_exists = Supplier::where('name', $request->name)->first();
        
        if($name_exists == null){
            Supplier::create([
                'name' => $request->name,
                'address' => $request->address,
                'person_in_charge' => $request->person_in_charge,
                'cellphone_no' => $request->cellphone_no,
                'telephone_no' => $request->telephone_no,
                'check_no' => $request->check_no,
                'tin_no' => $request->tin_no,
                'email' => $request->email,
                'bank_name' => $request->bank_name,
                'bank_account_no' => $request->bank_account_no,
                'is_vatable' => $request->is_vatable == 'on' ? 1 : 0
            ]);

            return redirect()->route('receiving.suppliers.index')->with('alert', 'success:Well done! You successfully added a supplier');
        }
        else{
            return redirect()->back()->with('alert', 'danger:Failed! Supplier already exists');
        }
    }

    public function edit(Supplier $supplier)
    {
        $page = new Page();
        $page->name = "Suppliers";

       return view('theme.pages.custom.receiving.suppliers.edit', compact('page', 'supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $name_exists = Supplier::where('id', '<>', $supplier->id)->where('name', $request->name)->first();
        
        if($name_exists == null){
            $supplier->update([
                'name' => $request->name,
                'address' => $request->address,
                'person_in_charge' => $request->person_in_charge,
                'cellphone_no' => $request->cellphone_no,
                'telephone_no' => $request->telephone_no,
                'check_no' => $request->check_no,
                'tin_no' => $request->tin_no,
                'email' => $request->email,
                'bank_name' => $request->bank_name,
                'bank_account_no' => $request->bank_account_no,
                'is_vatable' => $request->is_vatable == 'on' ? 1 : 0
            ]);

            return redirect()->back()->with('alert', 'success:Well done! You successfully updated a supplier');
        }
        else{
            return redirect()->back()->with('alert', 'danger:Failed! Supplier already exists');
        }
    }

    public function single_delete(Request $request)
    {
        $supplier = Supplier::findOrFail($request->suppliers);
        $supplier->delete();

        return redirect()->back()->with('alert', 'success:Well done! You successfully deleted a supplier');
    }

    public function multiple_delete(Request $request)
    {
        $suppliers = explode("|",$request->suppliers);

        foreach($suppliers as $supplier){
            Supplier::whereId((int) $supplier)->delete();
        }

        return redirect()->back()->with('alert', 'success:Well done! You successfully deleted multiple suppliers');
    }

    public function single_restore(Request $request)
    {
        $supplier = Supplier::withTrashed()->findOrFail($request->suppliers);
        $supplier->restore();

        return redirect()->back()->with('alert', 'success:Well done! You successfully restored a supplier');
    }

    public function multiple_restore(Request $request)
    {
        $suppliers = explode("|",$request->suppliers);

        foreach($suppliers as $supplier){
            Supplier::withTrashed()->whereId((int) $supplier)->restore();
        }

        return redirect()->back()->with('alert', 'success:Well done! You successfully restored multiple suppliers');
    }
    
}
