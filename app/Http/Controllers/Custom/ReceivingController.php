<?php

namespace App\Http\Controllers\Custom;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use App\Http\Requests\ReceivingRequest;

use Facades\App\Helpers\{ListingHelper, FileHelper};


use App\Models\{Page, RolePermission};
use App\Models\Custom\{Item, PurchaseOrderHeader, PurchaseOrderDetail, ReceivingHeader, ReceivingDetail, Supplier};
use Auth;
use DB;


class ReceivingController extends Controller

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
        $page->name = "Receiving Transactions";
   
        if(isset($_GET['is_search']) && $_GET['is_search']==1){


            $qry = "select distinct h.* from receiving_details d 
                    left join receiving_headers h on h.id=d.receiving_header_id 
                    left join items b on b.id=d.item_id
                    where d.id>0";

            if(isset($_GET['search']) && strlen($_GET['search']) > 0){
                $qry.=" and (d.sku like '%".$_GET['search']."%' or 
                b.sku like '%".$_GET['search']."%' or 
                b.name like '%".$_GET['search']."%' or 
                h.ref_no like '%".$_GET['search']."' or 
                h.po_number like '%".$_GET['search']."' or 
                h.si_number like '%".$_GET['search']."' or 
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
                $qry.=" and h.date_received >= '".$_GET['start_date']."'";
            }
            if(isset($_GET['end_date']) && strlen($_GET['end_date']) > 0){
                $qry.=" and h.date_received <= '".$_GET['end_date']."'";
            }

           // $transactions = DB::select($qry)->paginate(30);
            
        }
        else{
            $qry = "select * from receiving_headers order by id desc";
        }
        /*
        $take = 1; 
        $perPage = $_GET['per_page'] ?? 1;
        $page = $_GET['page'] ?? 1; 
        $skip = $page * $perPage;
        if($take < 1) { $take = 1; }
        if($skip < 0) { $skip = 0; }

        $basicQuery = DB::select($qry);
        $basicQuery = collect($basicQuery);
        //dd($basicQuery);
        $totalCount = $basicQuery->count();
        //dd($totalCount);
        $results = $basicQuery
            ->take($perPage)
            ->skip($skip)
            ->get();

        $transactions = new \Illuminate\Pagination\LengthAwarePaginator($results, $totalCount, $take, $page);
        */

        $basicQuery = DB::select($qry);
        //$transactions = Paginator::make($basicQuery, count($basicQuery), 10);
        $transactions = $this->paginate($basicQuery);
        return view('theme.pages.custom.receiving.transactions.index', compact('page', 'transactions'));
    }

    public function create()
    {
        if(!RolePermission::has_permission(2,auth()->user()->role_id,1)){
            abort(403, 'Unauthorized action.');
        }

        $page = new Page();
        $page->name = "Receiving Transactions";

        $suppliers = Supplier::all();

       return view('theme.pages.custom.receiving.transactions.create', compact('page', 'suppliers'));
    }

    public function store(ReceivingRequest $request)
    {
        $requestData = $request->validated();

        // RECEIVING HEADER CREATION
        $requestData['created_by'] = Auth::user()->id;
        $receiving_header = ReceivingHeader::create($requestData);

        $receiving_header->update([
            'ref_no' => ReceivingHeader::generateReferenceNo($receiving_header->id)
        ]);

        // //FOR SUPPLER ID AUTOMATION
        // $supplier_ids = [];
        // foreach ($request->item_id as $bid) {
        //     $sids = Item::where('id', $bid)->first()->supplier_id;
        
        //     if ($sids) {
        //         $sids = str_replace(['[', ']'], '', $sids); // Remove square brackets
        //         $sids_array = explode(',', $sids); // Split by comma
        
        //         $supplier_ids = array_merge($supplier_ids, $sids_array);
        //     }
        // }

        // $supplier_ids = array_unique($supplier_ids);
        // $request->supplier_id = $supplier_ids;
        // //END FOR SUPPLER ID AUTOMATION

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

            $receiving_header->update([
                'supplier_id' => json_encode($supplier_ids, JSON_UNESCAPED_SLASHES)
            ]);
        }

        // FOR ATTACHMENTS UPLOAD
        if($request->hasFile('attachments')){
            $attachments_url = [];
            foreach ($request->file('attachments') as $attachment) {
                if ($attachment) {
                    $attachment_url = FileHelper::move_to_files_folder($attachment, 'attachments/receiving-transactions/attachments/' . $receiving_header->id)['url'];
                    $attachments_url[] = $attachment_url;
                }
            }

            $receiving_header->update([
                'attachments' => json_encode($attachments_url, JSON_UNESCAPED_SLASHES)
            ]);
        }

        
        // RECEIVING DETAILS CREATION
        $item_count = 0;

        foreach($request->item_id as $item){
            $requestData['receiving_header_id'] = $receiving_header->id;
            $requestData['po_number'] = $request->po_number;
            $requestData['item_id'] = $item;
            $requestData['sku'] = $request->sku[$item_count];
            $requestData['price'] = $request->price[$item_count];
            $requestData['vat'] = $request->vat[$item_count];
            $requestData['vat_inclusive_price'] = $request->vat_inclusive_price[$item_count];
            $requestData['order'] = $request->order[$item_count];
            $requestData['quantity'] = $request->quantity[$item_count];

            ReceivingDetail::create($requestData);

            //UPDATE PURCHAE DETAILS REMAINING ORDER
            PurchaseOrderDetail::where('po_number', $request->po_number)->where('item_id', $item)
            ->update([
                'remaining' => \DB::raw("remaining - {$request->quantity[$item_count]}")
            ]);

            $item_count++;
        }

        //UPDATE PURCHASE ORDER REMAINING
        $total_remaining = PurchaseOrderDetail::where('po_number', $request->po_number)->sum('remaining');
        PurchaseOrderHeader::where('ref_no', $request->po_number)
        ->update([
            'total_remaining' => $total_remaining
        ]);

        
        //UPDATE PURCHAE ORDER TO POSTED
        // PurchaseOrderHeader::where('ref_no', $request->po_number)
        // ->update([
        //     'status' => 'POSTED',
        //     'updated_by' => Auth::user()->id,
        //     'updated_at' => now()
        // ]);
        // PurchaseOrderHeader::where('ref_no', $request->po_number)->delete();

       return redirect()->route('receiving.transactions.index')->with('alert', 'success:Well done! You successfully added a transaction');
    }

    public function show(Request $request)
    {
        $page = new Page();
        $page->name = "Receiving Transactions";

        $transaction = ReceivingHeader::withTrashed()->findOrFail($request->query('id'));

        $suppliers = Supplier::all();
        $receiving_details = ReceivingDetail::where('receiving_header_id', $request->query('id'))->get();
        
        $is_vatable = ReceivingDetail::where('receiving_header_id', $transaction->id)->where('vat', '>', 0)->exists();

        return view('theme.pages.custom.receiving.transactions.show', compact('transaction', 'page', 'suppliers', 'receiving_details', 'is_vatable'));
    }

    public function edit(ReceivingHeader $transaction)
    {
        if(!RolePermission::has_permission(2,auth()->user()->role_id,1)){
            abort(403, 'Unauthorized action.');
        }

        $page = new Page();
        $page->name = "Receiving Transactions";

        $suppliers = Supplier::all();
        $receiving_details = ReceivingDetail::where('receiving_header_id', $transaction->id)->get();

        $is_vatable = ReceivingDetail::where('receiving_header_id', $transaction->id)->where('vat', '>', 0)->exists();

        return view('theme.pages.custom.receiving.transactions.edit', compact('transaction', 'page', 'suppliers', 'receiving_details', 'is_vatable'));
    }

    public function update(ReceivingRequest $request, ReceivingHeader $transaction)
    {
        $requestData = $request->validated();

        // RECEIVING HEADER CREATION
        $requestData['updated_by'] = Auth::user()->id;
        $receiving_header = $transaction->update($requestData);

        //FOR SUPPLER ID AUTOMATION
        $supplier_ids = [];
        foreach ($request->item_id as $bid) {
            $sids = Item::where('id', $bid)->first()->supplier_id;
        
            if ($sids) {
                $sids = str_replace(['[', ']'], '', $sids); // Remove square brackets
                $sids_array = explode(',', $sids); // Split by comma
        
                $supplier_ids = array_merge($supplier_ids, $sids_array);
            }
        }

        $supplier_ids = array_unique($supplier_ids);
        $request->supplier_id = $supplier_ids;
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

            $transaction->update([
                'supplier_id' => json_encode($supplier_ids, JSON_UNESCAPED_SLASHES)
            ]);
        }

        // FOR ATTACHMENTS UPLOAD
        if($request->hasFile('attachments')){
            $attachments_url = [];
            foreach ($request->file('attachments') as $attachment) {
                if ($attachment) {
                    $attachment_url = FileHelper::move_to_files_folder($attachment, 'attachments/receiving-transactions/attachments/' . $transaction->id)['url'];
                    $attachments_url[] = $attachment_url;
                }
            }

            $transaction->update([
                'attachments' => json_encode($attachments_url, JSON_UNESCAPED_SLASHES)
            ]);
        }

        // RECEIVING DETAILS CREATION
        ReceivingDetail::where('receiving_header_id', $transaction->id)->delete();
        $item_count = 0;

        foreach($request->item_id as $item){
            $requestData['receiving_header_id'] = $transaction->id;
            $requestData['item_id'] = $item;
            $requestData['sku'] = $request->sku[$item_count];
            $requestData['price'] = $request->price[$item_count];
            $requestData['vat'] = $request->vat[$item_count];
            $requestData['vat_inclusive_price'] = $request->vat_inclusive_price[$item_count];
            $requestData['order'] = $request->order[$item_count];
            $requestData['quantity'] = $request->quantity[$item_count];

            ReceivingDetail::create($requestData);

            //UPDATE PURCHAE DETAILS REMAINING ORDER
            $po_detail = PurchaseOrderDetail::where('po_number', $request->po_number)->where('item_id', $item)->first();

            $receiving_detail = ReceivingDetail::where('po_number', $request->po_number);

            if($po_detail){
                $deducted_quantity = $po_detail->quantity - $receiving_detail->where('item_id', $item)->sum('quantity');

                $po_detail->update([
                    'remaining' => $deducted_quantity
                ]);
            }

            $item_count++;
        }

        //UPDATE PURCHASE ORDER REMAINING
        $total_remaining = PurchaseOrderDetail::where('po_number', $request->po_number)->sum('remaining');
        PurchaseOrderHeader::where('ref_no', $request->po_number)
        ->update([
            'total_remaining' => $total_remaining
        ]);

       return redirect()->back()->with('alert', 'success:Well done! You successfully updated a transaction');
    //    return redirect()->route('receiving.transactions.index')->with('alert', 'success:Well done! You successfully updated a transaction');
    }

    public function single_delete(Request $request)
    {
        $transaction = ReceivingHeader::findOrFail($request->transactions);

        $transaction->update([
            'status' => 'CANCELLED',
            'cancelled_by' => Auth::user()->id,
            'cancelled_at' => now()
        ]);

        $transaction->delete();

        //UPDATE PURCHAE ORDER TO POSTED
        PurchaseOrderHeader::where('ref_no', $transaction->po_number)->restore();
        // PurchaseOrderHeader::where('ref_no', $transaction->po_number)
        // ->update([
        //     'status' => 'SAVED',
        //     'updated_by' => Auth::user()->id,
        //     'updated_at' => now()
        // ]);


        return redirect()->back()->with('alert', 'success:Well done! You successfully cancelled a transaction');
    }

    public function multiple_delete(Request $request)
    {
        $transactions = explode("|",$request->transactions);

        foreach($transactions as $transaction){

            ReceivingHeader::where('id', $transaction)
            ->update([
                'status' => 'CANCELLED',
                'cancelled_by' => Auth::user()->id,
                'cancelled_at' => now()
            ]);

            ReceivingHeader::whereId((int) $transaction)->delete();
        }

        return redirect()->back()->with('alert', 'success:Well done! You successfully cancelled multiple transactions');
    }

    public function single_restore(Request $request)
    {
        $transaction = ReceivingHeader::withTrashed()->findOrFail($request->transactions);
        $transaction->restore();

        return redirect()->back()->with('alert', 'success:Well done! You successfully restored a transaction');
    }

    public function multiple_restore(Request $request)
    {
        $transactions = explode("|",$request->transactions);

        foreach($transactions as $transaction){
            ReceivingHeader::withTrashed()->whereId((int) $transaction)->restore();
        }

        return redirect()->back()->with('alert', 'success:Well done! You successfully restored multiple transactions');
    }

    public function single_post(Request $request)
    {
        $transaction = ReceivingHeader::findOrFail($request->transactions);
        $transaction->update([
            'status' => 'POSTED',
            'posted_by' => Auth::user()->id,
            'posted_at' => now()
        ]);

        //UPDATE PURCHAE ORDER TO POSTED
        // PurchaseOrderHeader::where('ref_no', $transaction->po_number)->withTrashed()
        // ->update([
        //     'status' => 'POSTED',
        //     'posted_by' => Auth::user()->id,
        //     'posted_at' => now()
        // ]);

        return redirect()->back()->with('alert', 'success:Well done! You successfully posted a transaction');
    }

    public function search_item(Request $request)
    {
        // Perform the search query, using 'like' for partial matches
        $query = $request->input('query');
        $results = Item::where('id', 'like', '%' . $query . '%')
                        ->orWhere('sku', 'like', '%' . $query . '%')
                        ->orWhere('name', 'like', '%' . $query . '%')
                        ->get(['id', 'sku', 'name', 'copies', 'total_cost']); // Select only the necessary fields

        return response()->json(['results' => $results]);
    }

    public function search_po_number(Request $request)
    {
        // Perform the search query, using 'like' for partial matches
        $query = $request->input('q');

        $results = PurchaseOrderHeader::where('ref_no', 'like', '%' . $query . '%')->where('status', 'POSTED')->where('total_remaining', '>', 0)->get(['ref_no', 'supplier_id']);

        return response()->json(['results' => $results]);
    }

    public function search_purchased_item(Request $request)
    {
        // Perform the search query, using 'like' for partial matches
        $query = $request->input('q');

        $results = PurchaseOrderHeader::where('ref_no', $query)->where('status', 'POSTED')->where('total_remaining', '>', 0)
            ->rightJoin('purchase_order_details', 'purchase_order_headers.id', '=', 'purchase_order_details.purchase_order_header_id')
            ->leftJoin('items', 'purchase_order_details.item_id', '=', 'items.id')
            ->leftJoin('item_types', 'items.type_id', '=', 'item_types.id')
            ->get([
                'items.id as item_id',
                'items.sku as sku',
                'items.name as item_name',
                'item_types.name as unit',
                'purchase_order_details.price',
                'purchase_order_details.quantity',
                'purchase_order_details.remaining',
                'purchase_order_details.vat',
                'purchase_order_details.vat_inclusive_price',
            ]);


        return response()->json(['results' => $results]);
    }
    
}
