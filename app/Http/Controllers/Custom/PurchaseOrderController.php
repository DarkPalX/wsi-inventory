<?php

namespace App\Http\Controllers\Custom;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\PurchaseOrderRequest;

use Facades\App\Helpers\{ListingHelper, FileHelper};


use App\Models\{Page, RolePermission, Setting};
use App\Models\Custom\{Item, ItemType, PurchaseOrderHeader, PurchaseOrderDetail, Supplier, RequisitionHeader, RquisitionDetail};
use Auth;
use DB;


class PurchaseOrderController extends Controller

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
        $page->name = "Purchase Orders";
   
        if(isset($_GET['is_search']) && $_GET['is_search']==1){


            $qry = "select distinct h.* from purchase_order_details d 
                    left join purchase_order_headers h on h.id=d.purchase_order_header_id 
                    left join items i on i.id=d.item_id
                    where d.id>0";

            if(isset($_GET['search']) && strlen($_GET['search']) > 0){
                $qry.=" and (d.sku like '%".$_GET['search']."%' or 
                i.sku like '%".$_GET['search']."%' or 
                i.name like '%".$_GET['search']."%' or 
                h.ref_no like '%".$_GET['search']."%' or 
                h.remarks like '%".$_GET['search']."%' 
                )";
            }

            if(isset($_GET['supplier']) && strlen($_GET['supplier']) > 0){
                $qry.=" and (h.supplier_id like '%[".$_GET['supplier'].",%' or 
                h.supplier_id like '%,".$_GET['supplier']."]' or 
                h.supplier_id like '[".$_GET['supplier']."]' or 
                h.supplier_id like '%,".$_GET['supplier'].",%'               
                )";
            }
            if(isset($_GET['status']) && strlen($_GET['status']) > 0){
                $qry.=" and h.status = '".$_GET['status']."'";
            }
            if(isset($_GET['start_date']) && strlen($_GET['start_date']) > 0){
                $qry.=" and h.date_ordered >= '".$_GET['start_date']."'";
            }
            if(isset($_GET['end_date']) && strlen($_GET['end_date']) > 0){
                $qry.=" and h.date_ordered <= '".$_GET['end_date']."'";
            }

        }
        else{
            $qry = "select * from purchase_order_headers order by updated_at desc";
        }
        

        $basicQuery = DB::select($qry);
        $purchase_orders = $this->paginate($basicQuery);
        return view('theme.pages.custom.receiving.purchase-orders.index', compact('page', 'purchase_orders'));
    }

    public function create()
    {
        if(!RolePermission::has_permission(2,auth()->user()->role_id,1)){
            abort(403, 'Unauthorized action.');
        }

        $page = new Page();
        $page->name = "Purchase Orders";

        $suppliers = Supplier::all();

       return view('theme.pages.custom.receiving.purchase-orders.create', compact('page', 'suppliers'));
    }

    public function store(PurchaseOrderRequest $request)
    {
        $ref_no_exists = PurchaseOrderHeader::where('ref_no', $request->ref_no)->exists();
        if ($ref_no_exists) {
            throw ValidationException::withMessages([
                'ref_no' => 'This reference no. has already been taken.',
            ]);
        }

        $requestData = $request->validated();

        // PURCHASE ORDER HEADER CREATION
        $requestData['created_by'] = Auth::user()->id;
        $purchase_order_header = PurchaseOrderHeader::create($requestData);

        // $purchase_order_header->update([
        //     'ref_no' => PurchaseOrderHeader::generateReferenceNo($purchase_order_header->id)
        // ]);

        //FOR SUPPLER ID AUTOMATION
        // $supplier_ids = [];
        // foreach ($request->item_id as $iid) {
        //     if($iid > 0){
        //         $sids = Item::where('id', $iid)->first()->supplier_id;
        
        //         if ($sids) {
        //             $sids = str_replace(['[', ']'], '', $sids); // Remove square brackets
        //             $sids_array = explode(',', $sids); // Split by comma
            
        //             $supplier_ids = array_merge($supplier_ids, $sids_array);
        //         }
        //     }
        // }

        // $supplier_ids = array_unique($supplier_ids);
        // $request->supplier_id = $supplier_ids;
        //END FOR SUPPLER ID AUTOMATION

        // FOR SUPPLIER
        $supplier_ids = [];
        if (!empty($request->supplier_id)) {
            foreach ($request->supplier_id as $supplier) {
                if (filter_var($supplier, FILTER_VALIDATE_INT) === false) {
                    $new_supplier = Supplier::create([
                        'name' => $supplier
                    ]);
                    $supplier_ids[] = $new_supplier->id;
                } else {
                    $supplier_ids[] = (int)$supplier;
                }
            }

            $purchase_order_header->update([
                'supplier_id' => json_encode($supplier_ids, JSON_UNESCAPED_SLASHES)
            ]);
        }


        // FOR ATTACHMENTS UPLOAD
        if($request->hasFile('attachments')){
            $attachments_url = [];
            foreach ($request->file('attachments') as $attachment) {
                if ($attachment) {
                    $attachment_url = FileHelper::move_to_files_folder($attachment, 'attachments/purchase-orders/attachments/' . $purchase_order_header->id)['url'];
                    $attachments_url[] = $attachment_url;
                }
            }

            $purchase_order_header->update([
                'attachments' => json_encode($attachments_url, JSON_UNESCAPED_SLASHES)
            ]);
        }

        
        // PURCHASE ORDER DETAILS CREATION
        $item_count = 0;
        $total_order = 0;
        $total_quantity = 0;

        foreach($request->item_id as $item){
            if($item > 0){
                $item_info = Item::where('id', $item)->first();

                $requestData['purchase_order_header_id'] = $purchase_order_header->id;
                $requestData['po_number'] = $purchase_order_header->ref_no;
                $requestData['ris_no'] = $request->ris_no[$item_count];
                $requestData['item_id'] = $item;
                $requestData['sku'] = $request->sku[$item_count];
                $requestData['quantity'] = $request->quantity[$item_count];
                $requestData['remaining'] = $request->quantity[$item_count];
                $requestData['price'] = $item_info->price;
                $requestData['vat'] = $request->vat_rate[$item_count];
                $requestData['vat_inclusive_price'] = $request->vat_inclusive_price[$item_count];
                $requestData['purpose'] = $request->po_item_purpose[$item_count] . '|#|' . 
                    (
                        !empty($request->item_purpose[$item_count])
                        ? json_encode(
                            array_map('trim',
                                is_array($request->item_purpose[$item_count])
                                    ? $request->item_purpose[$item_count]
                                    : (is_string($request->item_purpose[$item_count]) && ($decoded = json_decode($request->item_purpose[$item_count], true)) 
                                        ? $decoded 
                                        : explode(',', $request->item_purpose[$item_count]))
                            )
                        )
                        : '' 
                    )
                ;
                $requestData['remarks'] = $request->po_item_remarks[$item_count] . '|#|' . $request->item_remarks[$item_count];

                $total_order += $request->quantity[$item_count];

                PurchaseOrderDetail::create($requestData);

                $item_count++;
            }
        }

        $purchase_order_header->update([
            'total_order' => $total_order,
            'total_remaining' => $total_order
        ]);

       return redirect()->route('receiving.purchase-orders.index')->with('alert', 'success:Well done! You successfully added a purchase_order');
    }

    public function show(Request $request)
    {
        $page = new Page();
        $page->name = "Purchase Orders";

        $purchase_order = PurchaseOrderHeader::withTrashed()->findOrFail($request->query('id'));

        $suppliers = Supplier::all();
        $purchase_order_details = PurchaseOrderDetail::where('purchase_order_header_id', $request->query('id'))->get();

        return view('theme.pages.custom.receiving.purchase-orders.show', compact('purchase_order', 'page', 'suppliers', 'purchase_order_details'));
    }

    public function print(Request $request)
    {
        $page = new Page();
        $page->name = "Purchase Orders";

        $purchase_order = PurchaseOrderHeader::withTrashed()->findOrFail($request->query('id'));

        $suppliers = Supplier::all();
        $purchase_order_details = PurchaseOrderDetail::where('purchase_order_header_id', $request->query('id'))->get();

        $setting = Setting::find(1);

        return view('theme.pages.custom.receiving.purchase-orders.print', compact('purchase_order', 'page', 'suppliers', 'purchase_order_details', 'setting'));
    }

    // public function show(PurchaseOrderHeader $purchase_order)
    // {
    //     $page = new Page();
    //     $page->name = "Purchase Orders";

    //     $suppliers = Supplier::all();
    //     $purchase_order_details = PurchaseOrderDetail::where('purchase_order_header_id', $purchase_order->id)->get();

    //     return view('theme.pages.custom.receiving.purchase-orders.show', compact('purchase_order', 'page', 'suppliers', 'purchase_order_details'));
    // }

    public function edit(PurchaseOrderHeader $purchase_order)
    {
        if(!RolePermission::has_permission(2,auth()->user()->role_id,1)){
            abort(403, 'Unauthorized action.');
        }

        $page = new Page();
        $page->name = "Purchase Orders";

        $suppliers = Supplier::all();
        $purchase_order_details = PurchaseOrderDetail::where('purchase_order_header_id', $purchase_order->id)->get();

        return view('theme.pages.custom.receiving.purchase-orders.edit', compact('purchase_order', 'page', 'suppliers', 'purchase_order_details'));
    }

    public function update(PurchaseOrderRequest $request, PurchaseOrderHeader $purchase_order)
    {
        $ref_no_exists = PurchaseOrderHeader::where('id', '<>', $purchase_order->id)->where('ref_no', $request->ref_no)->exists();
        if ($ref_no_exists) {
            throw ValidationException::withMessages([
                'ref_no' => 'This reference no. has already been taken.',
            ]);
        }

        $requestData = $request->validated();

        // PURCHASE ORDER HEADER CREATION
        $requestData['updated_by'] = Auth::user()->id;
        $purchase_order_header = $purchase_order->update($requestData);

        //FOR SUPPLER ID AUTOMATION
        // $supplier_ids = [];
        // foreach ($request->item_id as $iid) {
        //     if($iid > 0){
        //         $sids = Item::where('id', $iid)->first()->supplier_id;
        
        //         if ($sids) {
        //             $sids = str_replace(['[', ']'], '', $sids); // Remove square brackets
        //             $sids_array = explode(',', $sids); // Split by comma
            
        //             $supplier_ids = array_merge($supplier_ids, $sids_array);
        //         }
        //     }
        // }

        // $supplier_ids = array_unique($supplier_ids);
        // $request->supplier_id = $supplier_ids;
        //END FOR SUPPLER ID AUTOMATION

        // FOR SUPPLIER
        $supplier_ids = [];
        if (!empty($request->supplier_id)) {
            foreach ($request->supplier_id as $supplier) {
                if (filter_var($supplier, FILTER_VALIDATE_INT) === false) {
                    $new_supplier = Supplier::create([
                        'name' => $supplier
                    ]);
                    $supplier_ids[] = $new_supplier->id;
                } else {
                    $supplier_ids[] = (int)$supplier;
                }
            }

            $purchase_order->update([
                'supplier_id' => json_encode($supplier_ids, JSON_UNESCAPED_SLASHES)
            ]);
        }

        // FOR ATTACHMENTS UPLOAD
        if($request->hasFile('attachments')){
            $attachments_url = [];
            foreach ($request->file('attachments') as $attachment) {
                if ($attachment) {
                    $attachment_url = FileHelper::move_to_files_folder($attachment, 'attachments/purchase-orders/attachments/' . $purchase_order->id)['url'];
                    $attachments_url[] = $attachment_url;
                }
            }

            $purchase_order->update([
                'attachments' => json_encode($attachments_url, JSON_UNESCAPED_SLASHES)
            ]);
        }

        // PURCHASE ORDER DETAILS CREATION
        PurchaseOrderDetail::where('purchase_order_header_id', $purchase_order->id)->delete();
        $item_count = 0;
        foreach($request->item_id as $item){
            if($item > 0){
                $item_info = Item::where('id', $item)->first();

                $requestData['purchase_order_header_id'] = $purchase_order->id;
                $requestData['po_number'] = $purchase_order->ref_no;
                $requestData['ris_no'] = $request->ris_no[$item_count];
                $requestData['item_id'] = $item;
                $requestData['sku'] = $request->sku[$item_count];
                $requestData['quantity'] = $request->quantity[$item_count];
                $requestData['remaining'] = $request->quantity[$item_count];
                $requestData['price'] = $item_info->price;
                $requestData['vat'] = $request->vat_rate[$item_count];
                $requestData['vat_inclusive_price'] = $request->vat_inclusive_price[$item_count];
                $requestData['purpose'] = $request->po_item_purpose[$item_count] . '|#|' . 
                    (
                        !empty($request->item_purpose[$item_count])
                        ? json_encode(
                            array_map('trim',
                                is_array($request->item_purpose[$item_count])
                                    ? $request->item_purpose[$item_count]
                                    : (is_string($request->item_purpose[$item_count]) && ($decoded = json_decode($request->item_purpose[$item_count], true)) 
                                        ? $decoded 
                                        : explode(',', $request->item_purpose[$item_count]))
                            )
                        )
                        : '' 
                    )
                ;
                $requestData['remarks'] = $request->po_item_remarks[$item_count] . '|#|' . $request->item_remarks[$item_count];
                // $requestData['purpose'] = !empty($request->item_purpose[$item_count])
                // ? json_encode(
                //     array_map('trim',
                //         is_array($request->item_purpose[$item_count])
                //             ? $request->item_purpose[$item_count]
                //             : (is_string($request->item_purpose[$item_count]) && ($decoded = json_decode($request->item_purpose[$item_count], true)) 
                //                 ? $decoded 
                //                 : explode(',', $request->item_purpose[$item_count]))
                //     )
                // )
                // : '';

                // $requestData['remarks'] = $request->item_remarks[$item_count];

                PurchaseOrderDetail::create($requestData);

                $item_count++;
            }
        }

       return redirect()->route('receiving.purchase-orders.index')->with('alert', 'success:Well done! You successfully updated a purchase_order');
    }

    public function single_delete(Request $request)
    {
        $purchase_order = PurchaseOrderHeader::findOrFail($request->purchase_orders);

        $purchase_order->update([
            'status' => 'CANCELLED',
            'cancelled_by' => Auth::user()->id,
            'cancelled_at' => now()
        ]);

        $purchase_order->delete();

        return redirect()->back()->with('alert', 'success:Well done! You successfully cancelled a purchase_order');
    }

    public function multiple_delete(Request $request)
    {
        $purchase_orders = explode("|",$request->purchase_orders);

        foreach($purchase_orders as $purchase_order){

            PurchaseOrderHeader::where('id', $purchase_order)
            ->update([
                'status' => 'CANCELLED',
                'cancelled_by' => Auth::user()->id,
                'cancelled_at' => now()
            ]);

            PurchaseOrderHeader::whereId((int) $purchase_order)->delete();
        }

        return redirect()->back()->with('alert', 'success:Well done! You successfully cancelled multiple purchase_orders');
    }

    public function single_restore(Request $request)
    {
        $purchase_order = PurchaseOrderHeader::withTrashed()->findOrFail($request->purchase_orders);
        $purchase_order->restore();

        return redirect()->back()->with('alert', 'success:Well done! You successfully restored a purchase_order');
    }

    public function multiple_restore(Request $request)
    {
        $purchase_orders = explode("|",$request->purchase_orders);

        foreach($purchase_orders as $purchase_order){
            PurchaseOrderHeader::withTrashed()->whereId((int) $purchase_order)->restore();
        }

        return redirect()->back()->with('alert', 'success:Well done! You successfully restored multiple purchase_orders');
    }

    public function single_post(Request $request)
    {
        $purchase_order = PurchaseOrderHeader::findOrFail($request->purchase_orders);
        $purchase_order->update([
            'status' => 'POSTED',
            'posted_by' => Auth::user()->id,
            'posted_at' => now()
        ]);

        return redirect()->back()->with('alert', 'success:Well done! You successfully posted a purchase_order');
    }

    public function search_item(Request $request)
    {
        $query = trim($request->input('query'));

        // --- FIRST: Check if the query matches a RIS No ---
        $risExists = RequisitionHeader::where('ref_no', "$query")->exists();
        // $risExists = RequisitionHeader::where('ref_no', 'like', "%$query%")->exists();

        if ($risExists) {
            // Return ALL items under this RIS
            $results = Item::select(
                            'items.id',
                            'items.sku',
                            'items.name',
                            'item_types.name as unit',
                            // 'items.price',
                            'requisition_details.ref_no as ris_no',
                            'requisition_details.quantity',
                            'requisition_details.purpose',
                            'requisition_details.remarks'
                        )
                        ->join('requisition_details', 'requisition_details.item_id', '=', 'items.id')
                        ->join('requisition_headers', 'requisition_headers.id', '=', 'requisition_details.requisition_header_id')
                        ->leftJoin('item_types', 'items.type_id', '=', 'item_types.id')
                        ->where('requisition_headers.ref_no', 'like', "%$query%")
                        ->where('requisition_headers.status', 'POSTED')
                        ->distinct()
                        ->get()
                        ->map(function ($item) {
                            $item->price = $item->mac; // USE MAC AS PRICE
                            $item->purpose = json_decode($item->purpose) ?? []; // decode JSON before returning
                            return $item;
                        });

            return response()->json(['results' => $results]);
        }

        // --- ELSE: Search items
        $results = Item::where('items.id', 'like', '%' . $query . '%')
                        ->orWhere('items.sku', 'like', '%' . $query . '%')
                        ->orWhere('items.name', 'like', '%' . $query . '%')
                        ->leftJoin('item_types', 'items.type_id', '=', 'item_types.id')
                        // ->get(['items.id', 'items.sku', 'items.name', 'item_types.name as unit', 'items.price']); 
                        ->get(['items.id', 'items.sku', 'items.name', 'item_types.name as unit', 'items.is_inventory'])
                        ->map(function ($item) {
                            $item->price = $item->mac; // USE MAC AS PRICE
                            return $item;
                        });
                        
        // $results = Item::select(
        //                     'items.id',
        //                     'items.sku',
        //                     'items.name',
        //                     'item_types.name as unit',
        //                     'items.price',
        //                     'requisition_details.ref_no as ris_no',
        //                     'requisition_details.quantity'
        //                 )
        //                 ->join('requisition_details', 'requisition_details.item_id', '=', 'items.id')
        //                 ->join('requisition_headers', 'requisition_headers.id', '=', 'requisition_details.requisition_header_id')
        //                 ->leftJoin('item_types', 'items.type_id', '=', 'item_types.id')
        //                 ->where(function ($q) use ($query) {
        //                     $q->where('items.id', 'like', "%$query%")
        //                     ->orWhere('items.sku', 'like', "%$query%")
        //                     ->orWhere('items.name', 'like', "%$query%");
        //                 })
        //                 ->where('requisition_headers.status', 'POSTED')
        //                 ->distinct()
        //                 ->get();

        return response()->json(['results' => $results]);
    }


    // public function search_item(Request $request)
    // {
    //     // Perform the search query, using 'like' for partial matches
    //     $query = $request->input('query');
    //     $results = Item::where('items.id', 'like', '%' . $query . '%')
    //                     ->orWhere('items.sku', 'like', '%' . $query . '%')
    //                     ->orWhere('items.name', 'like', '%' . $query . '%')
    //                     ->leftJoin('item_types', 'items.type_id', '=', 'item_types.id')
    //                     ->get(['items.id', 'items.sku', 'items.name', 'item_types.name as unit', 'items.price']); // Select only the necessary fields

    //     return response()->json(['results' => $results]);
    // }
    
}
