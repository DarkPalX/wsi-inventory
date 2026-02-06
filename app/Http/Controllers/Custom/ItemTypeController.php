<?php

namespace App\Http\Controllers\Custom;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Facades\App\Helpers\ListingHelper;
use App\Helpers\ModelHelper;

use App\Models\{Page};
use App\Models\Custom\{ItemType};

class ItemTypeController extends Controller
{
    private $searchFields = ['name'];

    public function index()
    {
        $page = new Page();
        $page->name = "Item Types";
        
        $types = ListingHelper::simple_search(ItemType::class, $this->searchFields);

        $filter = ListingHelper::get_filter($this->searchFields);

        $searchType = 'simple_search';

       return view('theme.pages.custom.items.types.index', compact('page', 'types', 'filter', 'searchType'));
    }

    public function create()
    {
        $page = new Page();
        $page->name = "Item Type";

       return view('theme.pages.custom.items.types.create', compact('page'));
    }

    public function store(Request $request)
    {
        $name_exists = ItemType::where('name', $request->name)->first();
        
        if($name_exists == null){
            $new_data = ItemType::create([
                'name' => $request->name,
                'slug' => ModelHelper::convert_to_slug(ItemType::class, $request->name),
                'description' => $request->description
            ]);
    
           return redirect()->route('items.types.index')->with('alert', 'success:Well done! You successfully added a type');
        }
        else{
            return redirect()->back()->with('alert', 'danger:Failed! Name already exists');
        }
    }

    public function edit(ItemType $type)
    {
        $page = new Page();
        $page->name = "Item Type";

       return view('theme.pages.custom.items.types.edit', compact('page', 'type'));
    }

    public function update(Request $request, ItemType $type)
    {
        $name_exists = ItemType::where('id', '<>', $type->id)->where('name', $request->name)->first();
        
        if($name_exists == null){
            $type->update([
                'name' => $request->name,
                'slug' => ModelHelper::convert_to_slug(ItemType::class, $request->name),
                'description' => $request->description
            ]);

            return redirect()->back()->with('alert', 'success:Well done! You successfully updated a type');
        }
        else{
            return redirect()->back()->with('alert', 'danger:Failed! Name already exists');
        }
    }

    public function single_delete(Request $request)
    {
        $type = ItemType::findOrFail($request->types);
        $type->delete();

        return redirect()->back()->with('alert', 'success:Well done! You successfully deleted an type');
    }

    public function multiple_delete(Request $request)
    {
        $types = explode("|",$request->types);

        foreach($types as $type){
            ItemType::whereId((int) $type)->delete();
        }

        return redirect()->back()->with('alert', 'success:Well done! You successfully deleted multiple types');
    }

    public function single_restore(Request $request)
    {
        $type = ItemType::withTrashed()->findOrFail($request->types);
        $type->restore();

        return redirect()->back()->with('alert', 'success:Well done! You successfully restored an type');
    }

    public function multiple_restore(Request $request)
    {
        $types = explode("|",$request->types);

        foreach($types as $type){
            ItemType::withTrashed()->whereId((int) $type)->restore();
        }

        return redirect()->back()->with('alert', 'success:Well done! You successfully restored multiple types');
    }
    
}
