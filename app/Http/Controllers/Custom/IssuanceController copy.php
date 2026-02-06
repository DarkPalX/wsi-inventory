<?php

namespace App\Http\Controllers\Custom;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

use Illuminate\Http\Request;
use App\Http\Requests\IssuanceRequest;

use Facades\App\Helpers\{ListingHelper, FileHelper};


use App\Models\{Page, RolePermission};
use App\Models\Custom\{Item, IssuanceHeader, IssuanceDetail, Receiver};
use Auth;
use DB;

class IssuanceController extends Controller

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
        $page->name = "Issuance Transactions";
   
        if(isset($_GET['is_search']) && $_GET['is_search']==1){


            $qry = "select distinct h.* from issuance_details d 
                    left join issuance_headers h on h.id=d.issuance_header_id 
                    left join items b on b.id=d.item_id
                    where d.id>0";

            if(isset($_GET['search']) && strlen($_GET['search']) > 0){
                $qry.=" and (d.sku like '%".$_GET['search']."%' or 
                b.sku like '%".$_GET['search']."%' or 
                b.name like '%".$_GET['search']."%' or 
                b.subtitle like '%".$_GET['search']."%' or 
                b.isbn like '%".$_GET['search']."%' or 
                h.ref_no like '%".$_GET['search']."' or 
                h.remarks like '%".$_GET['search']."%' 
                )";
            }

            if(isset($_GET['receiver']) && strlen($_GET['receiver']) > 0){
                $qry.=" and (h.receiver_id like '%[".$_GET['receiver'].",%' or 
                h.receiver_id like '%,".$_GET['receiver']."]' or 
                h.receiver_id like '[".$_GET['receiver']."]' or 
                h.receiver_id like '%,".$_GET['receiver'].",%'               
                )";
            }
            if(isset($_GET['status']) && strlen($_GET['status']) > 0){
                $qry.=" and h.status = '".$_GET['status']."'";
            }
            if(isset($_GET['start_date']) && strlen($_GET['start_date']) > 0){
                $qry.=" and h.date_released >= '".$_GET['start_date']."'";
            }
            if(isset($_GET['end_date']) && strlen($_GET['end_date']) > 0){
                $qry.=" and h.date_released <= '".$_GET['end_date']."'";
            }

        }
        else{
            $qry = "select * from issuance_headers order by id desc";
        }
      

        $basicQuery = DB::select($qry);
    
        $transactions = $this->paginate($basicQuery);
        return view('theme.pages.custom.issuance.transactions.index', compact('page', 'transactions'));
    }

    public function index_old()
    {
        $page = new Page();
        $page->name = "Issuance Transactions";
    
        $transactions = ListingHelper::simple_search(IssuanceHeader::class, $this->searchFields);

        $filter = ListingHelper::get_filter($this->searchFields);

        $searchType = 'simple_search';

       return view('theme.pages.custom.issuance.transactions.index', compact('page', 'transactions', 'filter', 'searchType'));
    }

    public function create()
    {
        if(!RolePermission::has_permission(3,auth()->user()->role_id,1)){
            abort(403, 'Unauthorized action.');
        }

        $page = new Page();
        $page->name = "Issuance Transactions";

        $receivers = Receiver::all();

       return view('theme.pages.custom.issuance.transactions.create', compact('page', 'receivers'));
    }

    public function store(IssuanceRequest $request)
    {
        $requestData = $request->validated();

        // ISSUANCE HEADER CREATION
        $requestData['created_by'] = Auth::user()->id;
        $requestData['is_for_sale'] = $request->is_for_sale ? 1 : 0;
        $issuance_header = IssuanceHeader::create($requestData);

        $issuance_header->update([
            'ref_no' => IssuanceHeader::generateReferenceNo($issuance_header->id)
        ]);


        // FOR RECEIVER
        $receiver_ids = [];
        if (!empty($request->receiver_id)) {
            foreach ($request->receiver_id as $receiver) {
                if (filter_var($receiver, FILTER_VALIDATE_INT) === false) {
                    $new_receiver = Receiver::create([
                        'name' => $receiver
                    ]);
                    $receiver_ids[] = $new_receiver->id;
                } else {
                    $receiver_ids[] = (int)$receiver;
                }
            }

            $issuance_header->update([
                'receiver_id' => json_encode($receiver_ids, JSON_UNESCAPED_SLASHES)
            ]);
        }

        // FOR ATTACHMENTS UPLOAD
        if($request->hasFile('attachments')){
            $attachments_url = [];
            foreach ($request->file('attachments') as $attachment) {
                if ($attachment) {
                    $attachment_url = FileHelper::move_to_files_folder($attachment, 'attachments/issuance-transactions/attachments/' . $issuance_header->id)['url'];
                    $attachments_url[] = $attachment_url;
                }
            }

            $issuance_header->update([
                'attachments' => json_encode($attachments_url, JSON_UNESCAPED_SLASHES)
            ]);
        }

        // ISSUANCE DETAILS CREATION
        $item_count = 0;
        foreach($request->item_id as $item){
            $requestData['issuance_header_id'] = $issuance_header->id;
            $requestData['item_id'] = $item;
            $requestData['sku'] = $request->sku[$item_count];
            $requestData['quantity'] = $request->quantity[$item_count];
            $requestData['cost'] = $request->cost[$item_count];
            $requestData['price'] = $request->price[$item_count];

            IssuanceDetail::create($requestData);

            $item_count++;
        }

       return redirect()->route('issuance.transactions.index')->with('alert', 'success:Well done! You successfully added a transaction');
    }
    
    public function show(Request $request)
    {
        $page = new Page();
        $page->name = "Issuance Transactions";

        $transaction = IssuanceHeader::withTrashed()->findOrFail($request->query('id'));

        $receivers = Receiver::all();
        $issuance_details = IssuanceDetail::where('issuance_header_id', $transaction->id)->get();

        return view('theme.pages.custom.issuance.transactions.show', compact('transaction', 'page', 'receivers', 'issuance_details'));
    }
    
    // public function show(IssuanceHeader $transaction)
    // {
    //     $page = new Page();
    //     $page->name = "Issuance Transactions";

    //     $receivers = Receiver::all();
    //     $issuance_details = IssuanceDetail::where('issuance_header_id', $transaction->id)->get();

    //     return view('theme.pages.custom.issuance.transactions.show', compact('transaction', 'page', 'receivers', 'issuance_details'));
    // }

    public function edit(IssuanceHeader $transaction)
    {
        if(!RolePermission::has_permission(3,auth()->user()->role_id,1)){
            abort(403, 'Unauthorized action.');
        }
        
        $page = new Page();
        $page->name = "Issuance Transactions";

        $receivers = Receiver::all();
        $issuance_details = IssuanceDetail::where('issuance_header_id', $transaction->id)->get();

        return view('theme.pages.custom.issuance.transactions.edit', compact('transaction', 'page', 'receivers', 'issuance_details'));
    }

    public function update(IssuanceRequest $request, IssuanceHeader $transaction)
    {
        $requestData = $request->validated();

        // ISSUANCE HEADER CREATION
        $requestData['updated_by'] = Auth::user()->id;
        $requestData['is_for_sale'] = $request->is_for_sale ? 1 : 0;
        $issuance_header = $transaction->update($requestData);


        // FOR RECEIVER
        $receiver_ids = [];
        if (!empty($request->receiver_id)) {
            foreach ($request->receiver_id as $receiver) {
                if (filter_var($receiver, FILTER_VALIDATE_INT) === false) {
                    $new_receiver = Receiver::create([
                        'name' => $receiver
                    ]);
                    $receiver_ids[] = $new_receiver->id;
                } else {
                    $receiver_ids[] = (int)$receiver;
                }
            }

            $transaction->update([
                'receiver_id' => json_encode($receiver_ids, JSON_UNESCAPED_SLASHES)
            ]);
        }

        // FOR ATTACHMENTS UPLOAD
        if($request->hasFile('attachments')){
            $attachments_url = [];
            foreach ($request->file('attachments') as $attachment) {
                if ($attachment) {
                    $attachment_url = FileHelper::move_to_files_folder($attachment, 'attachments/issuance-transactions/attachments/' . $transaction->id)['url'];
                    $attachments_url[] = $attachment_url;
                }
            }

            $transaction->update([
                'attachments' => json_encode($attachments_url, JSON_UNESCAPED_SLASHES)
            ]);
        }

        // ISSUANCE DETAILS CREATION
        IssuanceDetail::where('issuance_header_id', $transaction->id)->delete();
        $item_count = 0;
        foreach($request->item_id as $item){
            $requestData['issuance_header_id'] = $transaction->id;
            $requestData['item_id'] = $item;
            $requestData['sku'] = $request->sku[$item_count];
            $requestData['quantity'] = $request->quantity[$item_count];
            $requestData['cost'] = $request->cost[$item_count];
            $requestData['price'] = $request->price[$item_count];

            IssuanceDetail::create($requestData);

            $item_count++;
        }

       return redirect()->route('issuance.transactions.index')->with('alert', 'success:Well done! You successfully updated a transaction');
    }

    public function single_delete(Request $request)
    {
        $transaction = IssuanceHeader::findOrFail($request->transactions);

        $transaction->update([
            'status' => 'CANCELLED',
            'cancelled_by' => Auth::user()->id,
            'cancelled_at' => now()
        ]);

        $transaction->delete();

        return redirect()->back()->with('alert', 'success:Well done! You successfully deleted a transaction');
    }

    public function multiple_delete(Request $request)
    {
        $transactions = explode("|",$request->transactions);

        foreach($transactions as $transaction){

            IssuanceHeader::where('id', $transaction)
            ->update([
                'status' => 'CANCELLED',
                'cancelled_by' => Auth::user()->id,
                'cancelled_at' => now()
            ]);

            IssuanceHeader::whereId((int) $transaction)->delete();
        }

        return redirect()->back()->with('alert', 'success:Well done! You successfully deleted multiple transactions');
    }

    public function single_restore(Request $request)
    {
        $transaction = IssuanceHeader::withTrashed()->findOrFail($request->transactions);
        $transaction->restore();

        return redirect()->back()->with('alert', 'success:Well done! You successfully restored a transaction');
    }

    public function multiple_restore(Request $request)
    {
        $transactions = explode("|",$request->transactions);

        foreach($transactions as $transaction){
            IssuanceHeader::withTrashed()->whereId((int) $transaction)->restore();
        }

        return redirect()->back()->with('alert', 'success:Well done! You successfully restored multiple transactions');
    }

    public function single_post(Request $request)
    {
        $transaction = IssuanceHeader::findOrFail($request->transactions);
        $transaction->update([
            'status' => 'POSTED',
            'posted_by' => Auth::user()->id,
            'posted_at' => now()
        ]);

        return redirect()->back()->with('alert', 'success:Well done! You successfully posted a transaction');
    }

    public function search_item(Request $request)
    {
        // Perform the search query, using 'like' for partial matches
        $query = $request->input('query');
        $results = Item::where('id', 'like', '%' . $query . '%')
                        ->orWhere('sku', 'like', '%' . $query . '%')
                        ->orWhere('name', 'like', '%' . $query . '%')
                        ->get(['id', 'sku', 'name', 'total_cost', 'total_price']); // Select only the necessary fields

        // Filter out items with inventory equal to 0 and include the inventory in the response
        $filteredResults = $results->filter(function ($item) {
            $item->inventory = $item->inventory; // Access the inventory attribute
            return $item->inventory > 0; // Only include items with inventory greater than 0
        });

        return response()->json(['results' => $filteredResults->values()]);
    }
    
}
