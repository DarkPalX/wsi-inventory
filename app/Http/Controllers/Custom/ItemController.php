<?php

namespace App\Http\Controllers\Custom;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\ItemRequest;


use Facades\App\Helpers\{ListingHelper, FileHelper};

use App\Models\{Page, RolePermission};
use App\Helpers\ModelHelper;
use App\Models\Custom\{Item, ItemCategory, ItemType, Supplier, ReceivingHeader, ReceivingDetail, IssuanceHeader, IssuanceDetail};
use DB;

class ItemController extends Controller
{
    private $searchFields = ['name', 'sku'];

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
        $page->name = "Items";

        $qry = "SELECT i.id as iid, i.name as iname, i.price, i.created_at, 
                    c.name as cname
                FROM items i
                LEFT JOIN item_categories c ON c.id = i.category_id
                WHERE i.id > 0 AND i.deleted_at IS NULL";

        if(isset($_GET['search']) && strlen($_GET['search']) > 0) {
            $qry .= " AND (i.sku LIKE '%".$_GET['search']."%' OR 
                        i.name LIKE '%".$_GET['search']."%' OR 
                        i.location LIKE '%".$_GET['search']."%' )";
        }

        if(isset($_GET['category']) && strlen($_GET['category']) > 0) {
            $qry .= " AND c.id = ".$_GET['category'];
        }

        if(isset($_GET['end_date']) && strlen($_GET['end_date']) > 0) {
            $qry .= " AND i.publication_date <= '".$_GET['end_date']."'";
        }

        // Add all non-aggregated columns to the GROUP BY clause
        // $qry .= " GROUP BY i.id, i.name, i.sku, i.price, i.created_at, c.name
        //         ORDER BY i.updated_at DESC";

        $qry .= " ORDER BY i.updated_at DESC";
        
        $basicQuery = DB::select($qry);
        //$transactions = Paginator::make($basicQuery, count($basicQuery), 10);
        $items = $this->paginate($basicQuery);
        
        // $items = Item::all();

       return view('theme.pages.custom.items.index', compact('page', 'items'));
    }

    public function create()
    {    
        if(!RolePermission::has_permission(1,auth()->user()->role_id,1)){
            abort(403, 'Unauthorized action.');
        }
        
        $page = new Page();
        $page->name = "Items";

        $categories = ItemCategory::all();
        $types = ItemType::all();
        $suppliers = Supplier::all();

       return view('theme.pages.custom.items.create', compact('page', 'categories', 'types', 'suppliers'));
    }

    public function store(ItemRequest $request)
    {
        $requestData = $request->validated();
        $requestData['sku'] = 'sku001';
        $requestData['slug'] = ModelHelper::convert_to_slug(Item::class, $request->name);

        $item = Item::create($requestData);

        // FOR FILE UPLOADS
        $image_cover = $request->hasFile('image_cover') ? FileHelper::move_to_files_folder($request->file('image_cover'), 'attachments/items/'. $item->slug)['url'] : null;
        $item->update([
            'image_cover' => $image_cover
        ]);

        // FOR ITEM TYPE
        if (!empty($request->type_id)) {

            $type = $request->type_id;

            if(filter_var($type, FILTER_VALIDATE_INT) == false){
                $new_type = ItemType::create([
                    'name' => $type,
                    'slug' => ModelHelper::convert_to_slug(ItemType::class, $type),
                    'description' => $type,
                ]);
                $type = $new_type->id;
            }

            $item->update([
                'type_id' => $type
            ]);
        }

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

            $item->update([
                'supplier_id' => json_encode($supplier_ids, JSON_UNESCAPED_SLASHES)
            ]);
        }

        // make sku generator algo here..
        $md = strtotime(now());
        $y = date('Y', $md);
        $m = date('m', $md);

        $sku_number = $y . $m . $item->id;
        $item->update([
            'sku' => $sku_number
        ]);

       return redirect()->route('items.index')->with('alert', 'success:Well done! You successfully added a item');
    }

    public function show($id)
    {
        $page = new Page();
        $page->name = "Item Details";

        $item = Item::withTrashed()->find($id);
        $categories = ItemCategory::all();
        $authors = Author::all();
        $publishers = Publisher::all();
        $agencies = Agency::all();


       return view('theme.pages.custom.items.show', compact('page', 'item', 'categories', 'authors', 'publishers', 'agencies'));
    }
    
    public function stock_card($id){
        $page = new Page();
        $page->name = "Stock Card";

        $item = Item::find($id);

        $receiving_transactions = ReceivingHeader::where('receiving_headers.status', 'POSTED')
        ->join('receiving_details', 'receiving_details.receiving_header_id', '=', 'receiving_headers.id')
        ->where('receiving_details.item_id', $id)
        ->select('receiving_headers.posted_at', 'receiving_details.quantity', 'receiving_headers.id', 'receiving_headers.ref_no', DB::raw("'Receiving' as type"))
        ->get();


        $issuance_transactions = IssuanceHeader::where('issuance_headers.status', 'POSTED')
        ->join('issuance_details', 'issuance_details.issuance_header_id', '=', 'issuance_headers.id')
        ->where('issuance_details.item_id', $id)
        ->select('issuance_headers.posted_at', 'issuance_details.quantity', 'issuance_headers.id', 'issuance_headers.ref_no', DB::raw("'Issuance' as type"))
        ->get();

        $transactions = collect();
        $transactions = $transactions->merge($receiving_transactions)->merge($issuance_transactions);
        //dd($transactions);
        $sorted_transactions = $transactions->sortBy('posted_at')->values();

        $running_balance = 0;
        $stock_card = [];

        foreach ($sorted_transactions as $transaction) {
            if ($transaction->type === 'Receiving') {
                $running_balance += $transaction->quantity;
            } else if ($transaction->type === 'Issuance') {
                $running_balance -= $transaction->quantity;
            }

            $stock_card[] = [
                'date' => $transaction->posted_at,
                'type' => $transaction->type,
                'transaction_id' => $transaction->id,
                'ref_no' => $transaction->ref_no,
                'quantity' => $transaction->quantity,
                'running_balance' => $running_balance
            ];
        }

       return view('theme.pages.custom.items.stock-card', compact('page', 'item', 'stock_card'));
    }

    public function edit(Item $item)
    {
        if(!RolePermission::has_permission(1,auth()->user()->role_id,1)){
            abort(403, 'Unauthorized action.');
        }

        $page = new Page();
        $page->name = "Items";

        $categories = ItemCategory::all();
        $types = ItemType::all();
        $suppliers = Supplier::all();

       return view('theme.pages.custom.items.edit', compact('page', 'item', 'categories', 'types', 'suppliers'));
    }

    public function update(ItemRequest $request, Item $item)
    {
        $sku_exists = Item::where('id', '<>', $item->id)->where('sku', $request->sku)->exists();
        if ($sku_exists) {
            throw ValidationException::withMessages([
                'sku' => 'The sku has already been taken.',
            ]);
        }

        $requestData = $request->validated();
        $requestData['slug'] = ModelHelper::convert_to_slug(Item::class, $request->name);
        $requestData['is_inventory'] = $request->is_inventory ?? 1;

        $item->update($requestData);
        
        // FOR FILE UPLOADS
        if($request->hasFile('image_cover')){
            $image_cover = $request->hasFile('image_cover') ? FileHelper::move_to_files_folder($request->file('image_cover'), 'attachments/items/'. $item->slug)['url'] : null;
            $item->update([
                'image_cover' => $image_cover
            ]);
        }

        // FOR ITEM TYPE
        if (!empty($request->type_id)) {

            $type = $request->type_id;

            if(filter_var($type, FILTER_VALIDATE_INT) == false){
                $new_type = ItemType::create([
                    'name' => $type,
                    'slug' => ModelHelper::convert_to_slug(ItemType::class, $type),
                    'description' => $type,
                ]);
                $type = $new_type->id;
            }

            $item->update([
                'type_id' => $type
            ]);
        }

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

            $item->update([
                'supplier_id' => json_encode($supplier_ids, JSON_UNESCAPED_SLASHES)
            ]);
        }


       return redirect()->route('items.index')->with('alert', 'success:Well done! You successfully updated a item');
    }

    public function single_delete(Request $request)
    {
        $item = Item::findOrFail($request->items);
        $item->delete();

        return redirect()->back()->with('alert', 'success:Well done! You successfully deleted a item');
    }

    public function multiple_delete(Request $request)
    {
        $items = explode("|",$request->items);

        foreach($items as $item){
            Item::whereId((int) $item)->delete();
        }

        return redirect()->back()->with('alert', 'success:Well done! You successfully deleted multiple items');
    }

    public function single_restore(Request $request)
    {
        $item = Item::withTrashed()->findOrFail($request->items);
        $item->restore();

        return redirect()->back()->with('alert', 'success:Well done! You successfully restored an item');
    }

    public function multiple_restore(Request $request)
    {
        $items = explode("|",$request->items);

        foreach($items as $item){
            Item::withTrashed()->whereId((int) $item)->restore();
        }

        return redirect()->back()->with('alert', 'success:Well done! You successfully restored multiple items');
    }
    
}
