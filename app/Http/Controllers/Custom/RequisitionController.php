<?php

namespace App\Http\Controllers\Custom;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\RequisitionRequest;

use Facades\App\Helpers\{ListingHelper, FileHelper};


use App\Models\{Page, RolePermission};
use App\Models\Custom\{Item, ItemType, RequisitionHeader, RequisitionDetail, IssuanceHeader, IssuanceDetail, Receiver, Vehicle};
use Auth;
use DB;


class RequisitionController extends Controller

{
    private $searchFields = ['id'];

    public function paginate($items, $perPage = 10, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page,  [
            'path' => Paginator::resolveCurrentPath()
        ]);
    }

    public function index()
    {
        $page = new Page();
        $page->name = "Requisitions";
   
        if(isset($_GET['is_search']) && $_GET['is_search']==1){

            $qry = "select distinct h.* from requisition_details d 
                    left join requisition_headers h on h.id=d.requisition_header_id 
                    left join items i on i.id=d.item_id
                    where d.id>0 ";

            if(isset($_GET['search']) && strlen($_GET['search']) > 0){
                $qry.=" and (d.sku like '%".$_GET['search']."%' or 
                i.sku like '%".$_GET['search']."%' or 
                i.name like '%".$_GET['search']."%' or 
                h.ref_no like '%".$_GET['search']."%' or 
                h.purpose like '%".$_GET['search']."%' or 
                h.remarks like '%".$_GET['search']."%' 
                )";
            }
            if(isset($_GET['requester']) && strlen($_GET['requester']) > 0){
                $qry.=" and h.requested_by = '".$_GET['requester']."'";
            }
            if(isset($_GET['status']) && strlen($_GET['status']) > 0){
                $qry.=" and h.status = '".$_GET['status']."'";
            }
            if(isset($_GET['start_date']) && strlen($_GET['start_date']) > 0){
                $qry.=" and h.date_requested >= '".$_GET['start_date']."'";
            }
            if(isset($_GET['end_date']) && strlen($_GET['end_date']) > 0){
                $qry.=" and h.date_requested <= '".$_GET['end_date']."'";
            }

        }
        else{
            $qry = "select * from requisition_headers h where 1=1 ";
        }

        // if(Auth::user()->role_id == env('APPROVER_ROLE_ID') || Auth::user()->role_id == env('SECRETARY_ROLE_ID')){
        //     $qry .= " and h.section_id=". Auth::user()->section_id . "";
        // }

        // if(Auth::user()->role_id == env('WAREHOUSE_ROLE_ID')){
        //     $qry .= " and h.status='POSTED'";
        // }

        $qry .= " order by updated_at desc";
        

        $basicQuery = DB::select($qry);
        $requisitions = $this->paginate($basicQuery);
        return view('theme.pages.custom.issuance.requisitions.index', compact('page', 'requisitions'));
    }

    public function create()
    {
        if(!RolePermission::has_permission(3,auth()->user()->role_id,1)){
            abort(403, 'Unauthorized action.');
        }

        $page = new Page();
        $page->name = "Requisitions";

        $vehicles = Vehicle::all();

       return view('theme.pages.custom.issuance.requisitions.create', compact('page', 'vehicles'));
    }

    public function store(RequisitionRequest $request)
    {
        
        // $item_count = 0;
        // foreach($request->item_id as $item){
        //     if($item > 0){
        //         $item_info = Item::where('id', $item)->first();

        //         $requestData['item_id'] = $item;
        //         $requestData['sku'] = $request->sku[$item_count];
        //         $requestData['quantity'] = $request->quantity[$item_count];
        //         // $requestData['purpose'] = json_encode((array) ($request->item_purpose[$item_count] ?? ''));
        //         // $purpose = $request->item_purpose[$item_count] ?? [];

        //         $requestData['purpose'] = empty(array_filter((array) ($request->item_purpose[$item_count] ?? []))) ? json_encode([]) : json_encode($purpose);

        //         $requestData['remarks'] = $request->item_remarks[$item_count];


        //         $item_count++;

        //         dd($requestData['purpose']);
        //     }
        // }






        $requestData = $request->validated();

        // REQUISITION HEADER CREATION
        $requestData['created_by'] = Auth::user()->id;
        $requestData['requested_by'] = Auth::user()->id;
        $requestData['requested_at'] = now();

        $requestData['requisition_type'] = json_encode($request->requisition_type) ?? null;
        $requestData['requisition_parts_needed'] = json_encode($request->requisition_parts_needed) ?? null;
        $requestData['requisition_assessment'] = json_encode($request->requisition_assessment) ?? null;

        $requisition_header = RequisitionHeader::create($requestData);

        $requisition_header->update([
            'ref_no' => RequisitionHeader::generateReferenceNo($requisition_header->id)
        ]);


        // FOR VEHICLE
        $vehicle_ids = [];
        if (!empty($request->vehicle_id)) {
            foreach ($request->vehicle_id as $vehicle) {
                if (filter_var($vehicle, FILTER_VALIDATE_INT) === false) {
                    $new_vehicle = Vehicle::create([
                        'plate_no' => $vehicle
                    ]);
                    $vehicle_ids[] = $new_vehicle->id;
                } else {
                    $vehicle_ids[] = (int)$vehicle;
                }
            }

            $requisition_header->update([
                'vehicle_id' => $vehicle_ids
                // 'vehicle_id' => json_encode($vehicle_ids, JSON_UNESCAPED_SLASHES)
            ]);
        }
        
        // REQUISITION DETAILS CREATION
        $item_count = 0;
        foreach($request->item_id as $item){
            if($item > 0){
                $item_info = Item::where('id', $item)->first();

                $requestData['requisition_header_id'] = $requisition_header->id;
                $requestData['ref_no'] = $requisition_header->ref_no;
                $requestData['item_id'] = $item;
                $requestData['sku'] = $request->sku[$item_count];
                $requestData['quantity'] = $request->quantity[$item_count];
                $requestData['purpose'] = empty(array_filter((array) ($request->item_purpose[$item_count] ?? []))) ? json_encode([]) : json_encode($purpose);
                // $requestData['purpose'] = json_encode((array) ($request->item_purpose[$item_count] ?? ''));
                $requestData['remarks'] = $request->item_remarks[$item_count];

                RequisitionDetail::create($requestData);

                $item_count++;
            }
        }

       return redirect()->route('issuance.requisitions.index')->with('alert', 'success:Well done! You successfully added a requisition');
    }

    public function show(Request $request)
    {
        $page = new Page();
        $page->name = "Requisitions";

        $requisition = RequisitionHeader::withTrashed()->findOrFail($request->query('id'));

        $requisition_details = RequisitionDetail::where('requisition_header_id', $request->query('id'))->get();

        $vehicles = Vehicle::all();

        return view('theme.pages.custom.issuance.requisitions.show', compact('requisition', 'page', 'requisition_details', 'vehicles'));
    }

    public function edit(RequisitionHeader $requisition)
    {
        if(!RolePermission::has_permission(3,auth()->user()->role_id,1)){
            abort(403, 'Unauthorized action.');
        }

        $page = new Page();
        $page->name = "Requisitions";

        $requisition_details = RequisitionDetail::where('requisition_header_id', $requisition->id)->get();

        $vehicles = Vehicle::all();

        return view('theme.pages.custom.issuance.requisitions.edit', compact('requisition', 'page', 'requisition_details', 'vehicles'));
    }

    public function update(RequisitionRequest $request, RequisitionHeader $requisition)
    {
        $requestData = $request->validated();

        // REQUISITION HEADER
        $requestData['updated_by'] = Auth::user()->id;

        $requestData['requisition_type'] = json_encode($request->requisition_type) ?? null;
        $requestData['requisition_parts_needed'] = json_encode($request->requisition_parts_needed) ?? null;
        $requestData['requisition_assessment'] = json_encode($request->requisition_assessment) ?? null;

        $requisition_header = $requisition->update($requestData);
        

        // FOR VEHICLE
        $vehicle_ids = [];
        if (!empty($request->vehicle_id)) {
            foreach ($request->vehicle_id as $vehicle) {
                if (filter_var($vehicle, FILTER_VALIDATE_INT) === false) {
                    $new_vehicle = Vehicle::create([
                        'plate_no' => $vehicle
                    ]);
                    $vehicle_ids[] = $new_vehicle->id;

                } else {
                    $vehicle_ids[] = (int) $vehicle;
                }
            }
            
            $requisition->update([
                'vehicle_id' => $vehicle_ids
                // 'vehicle_id' => json_encode($vehicle_ids, JSON_UNESCAPED_SLASHES)
            ]);

        } 
        else {
            $requisition->update([ 'vehicle_id' => null ]);
        }

        // REQUISITION DETAILS CREATION
        RequisitionDetail::where('requisition_header_id', $requisition->id)->delete();
        $item_count = 0;
        foreach($request->item_id as $item){
            if($item > 0){
                $item_info = Item::where('id', $item)->first();

                $requestData['requisition_header_id'] = $requisition->id;
                $requestData['ref_no'] = $requisition->ref_no;
                $requestData['item_id'] = $item;
                $requestData['sku'] = $request->sku[$item_count];
                $requestData['quantity'] = $request->quantity[$item_count];
                $requestData['purpose'] = empty(array_filter((array) ($request->item_purpose[$item_count] ?? []))) ? json_encode([]) : json_encode($purpose);
                // $requestData['purpose'] = json_encode((array) ($request->item_purpose[$item_count] ?? ''));
                $requestData['remarks'] = $request->item_remarks[$item_count];

                RequisitionDetail::create($requestData);

                $item_count++;
            }
        }

       return redirect()->route('issuance.requisitions.index')->with('alert', 'success:Well done! You successfully updated a requisition');
    }
    
    public function create_issuance(Request $request)
    {
        if(!RolePermission::has_permission(3,auth()->user()->role_id,1)){
            abort(403, 'Unauthorized action.');
        }

        $page = new Page();
        $page->name = "Issuance";

        $requisition = RequisitionHeader::find($request->requisition_id);
        $requisition_details = RequisitionDetail::where('requisition_header_id', $requisition->id)->get();

        return view('theme.pages.custom.issuance.transactions.create', compact('requisition', 'requisition_details', 'page'));
    }
    
    public function show_issuance(Request $request, $id)
    {
        if(!RolePermission::has_permission(3,auth()->user()->role_id,1)){
            abort(403, 'Unauthorized action.');
        }

        $page = new Page();
        $page->name = "Issuance";

        $requisition = RequisitionHeader::find($id);
        $requisition_details = RequisitionDetail::where('requisition_header_id', $id)->get();

        $issuance = IssuanceHeader::where('ris_no', $requisition->ref_no)->first();
        $issuance_details = IssuanceDetail::where('issuance_header_id', $issuance->id)->get();


        //added for issuance view
        $transaction = IssuanceHeader::where('ris_no', $requisition->ref_no)->withTrashed()->first();

        $receivers = Receiver::all();
        $issuance_details = IssuanceDetail::where('issuance_header_id', $transaction->id)->get();
        $vehicles = Vehicle::all();

        return view('theme.pages.custom.issuance.transactions.show', compact('requisition', 'requisition_details', 'issuance', 'issuance_details', 'page', 'transaction', 'receivers', 'issuance_details', 'vehicles'));
    }
    
    // public function show_issuance(Request $request, $id)
    // {
    //     if(!RolePermission::has_permission(3,auth()->user()->role_id,1)){
    //         abort(403, 'Unauthorized action.');
    //     }

    //     $page = new Page();
    //     $page->name = "Issuance";

    //     $requisition = RequisitionHeader::find($id);
    //     $requisition_details = RequisitionDetail::where('requisition_header_id', $id)->get();

    //     $issuance = IssuanceHeader::where('ris_no', $requisition->ref_no)->first();
    //     $issuance_details = IssuanceDetail::where('issuance_header_id', $issuance->id)->get();

    //     return view('theme.pages.custom.issuance.transactions.show', compact('requisition', 'requisition_details', 'issuance', 'issuance_details', 'page'));
    // }
    
    public function edit_issuance(Request $request)
    {
        if(!RolePermission::has_permission(3,auth()->user()->role_id,1)){
            abort(403, 'Unauthorized action.');
        }

        $page = new Page();
        $page->name = "Issuance";

        $requisition = RequisitionHeader::find($request->requisition_id);
        $requisition_details = RequisitionDetail::where('requisition_header_id', $requisition->id)->get();

        $issuance = IssuanceHeader::where('ris_no', $requisition->ref_no)->first();
        $issuance_details = IssuanceDetail::where('issuance_header_id', $issuance->id)->get();

        return view('theme.pages.custom.issuance.transactions.edit', compact('requisition', 'requisition_details', 'issuance', 'issuance_details', 'page'));
    }

    public function single_delete(Request $request)
    {
        $requisition = RequisitionHeader::findOrFail($request->requisitions);
        $requisition->update([
            'status' => 'CANCELLED',
            'cancelled_by' => Auth::user()->id,
            'cancelled_at' => now()
        ]);

        $requisition->delete();

        return redirect()->back()->with('alert', 'success:Well done! You successfully cancelled a requisition');
    }

    public function multiple_delete(Request $request)
    {
        $requisitions = explode("|",$request->requisitions);

        foreach($requisitions as $requisition){

            RequisitionHeader::where('id', $requisition)
            ->update([
                'status' => 'CANCELLED',
                'cancelled_by' => Auth::user()->id,
                'cancelled_at' => now()
            ]);

            RequisitionHeader::whereId((int) $requisition)->delete();
        }

        return redirect()->back()->with('alert', 'success:Well done! You successfully cancelled multiple requisitions');
    }

    public function single_restore(Request $request)
    {
        $requisition = RequisitionHeader::withTrashed()->findOrFail($request->requisitions);
        $requisition->restore();

        return redirect()->back()->with('alert', 'success:Well done! You successfully restored a requisition');
    }

    public function multiple_restore(Request $request)
    {
        $requisitions = explode("|",$request->requisitions);

        foreach($requisitions as $requisition){
            RequisitionHeader::withTrashed()->whereId((int) $requisition)->restore();
        }

        return redirect()->back()->with('alert', 'success:Well done! You successfully restored multiple requisitions');
    }

    public function single_post(Request $request)
    {
        $requisition = RequisitionHeader::findOrFail($request->requisitions);
        $requisition->update([
            'status' => 'POSTED',
            'posted_by' => Auth::user()->id,
            'posted_at' => now()
        ]);

        return redirect()->back()->with('alert', 'success:Well done! You successfully posted a requisition');
    }

    public function search_item(Request $request)
    {
        // Perform the search query, using 'like' for partial matches
        $query = $request->input('query');
        $results = Item::where('items.id', 'like', '%' . $query . '%')
                        ->orWhere('items.sku', 'like', '%' . $query . '%')
                        ->orWhere('items.name', 'like', '%' . $query . '%')
                        ->leftJoin('item_types', 'items.type_id', '=', 'item_types.id')
                        ->get(['items.id', 'items.sku', 'items.name', 'item_types.name as unit', 'items.price', 'items.is_inventory']); // Select only the necessary fields

        // Filter out items with inventory equal to 0 and include the inventory in the response
        $filteredResults = $results->filter(function ($item) {
            $item->inventory = $item->inventory;
            // return $item->inventory > 0;
            return ($item->inventory > 0) || ($item->is_inventory == 0);
        });

        // return response()->json(['results' => $results]);
        return response()->json(['results' => $filteredResults->values()]); //if want filter the iitems with stocks only
    }

    // public function search_item(Request $request)
    // {
    //     // Perform the search query, using 'like' for partial matches
    //     $query = $request->input('query');
    //     $results = Item::where('items.id', 'like', '%' . $query . '%')
    //                     ->orWhere('items.sku', 'like', '%' . $query . '%')
    //                     ->orWhere('items.name', 'like', '%' . $query . '%')
    //                     ->leftJoin('item_units', 'items.unit_id', '=', 'item_units.id')
    //                     ->leftJoin('item_types', 'items.type_id', '=', 'item_types.id')
    //                     ->get(['items.id', 'items.sku', 'items.name', 'item_units.name as unit', 'item_types.name as type']); // Select only the necessary fields

    //     $filteredResults = $results->filter(function ($item) {
    //         $item->inventory = $item->inventory; 
    //         return $item->inventory > 0; // Only include items with inventory greater than 0
    //     });

    //     return response()->json(['results' => $filteredResults->values()]);
    // }
    
}
