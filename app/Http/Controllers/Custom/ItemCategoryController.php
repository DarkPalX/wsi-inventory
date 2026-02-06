<?php

namespace App\Http\Controllers\Custom;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Facades\App\Helpers\ListingHelper;
use App\Helpers\ModelHelper;

use App\Models\{Page};
use App\Models\Custom\{ItemCategory};

class ItemCategoryController extends Controller
{
    private $searchFields = ['name'];

    public function index()
    {
        $page = new Page();
        $page->name = "Item Categories";
        
        $categories = ListingHelper::simple_search(ItemCategory::class, $this->searchFields);

        $filter = ListingHelper::get_filter($this->searchFields);

        $searchType = 'simple_search';

       return view('theme.pages.custom.items.categories.index', compact('page', 'categories', 'filter', 'searchType'));
    }

    public function create()
    {
        $page = new Page();
        $page->name = "Item Category";

       return view('theme.pages.custom.items.categories.create', compact('page'));
    }

    public function store(Request $request)
    {
        $name_exists = ItemCategory::where('name', $request->name)->first();
        
        if($name_exists == null){
            $new_data = ItemCategory::create([
                'name' => $request->name,
                'slug' => ModelHelper::convert_to_slug(ItemCategory::class, $request->name),
                'description' => $request->description,
                'order' => ItemCategory::count() + 1
            ]);
    
           return redirect()->route('items.categories.index')->with('alert', 'success:Well done! You successfully added a category');
        }
        else{
            return redirect()->back()->with('alert', 'danger:Failed! Name already exists');
        }
    }

    public function edit(ItemCategory $category)
    {
        $page = new Page();
        $page->name = "Item Category";

       return view('theme.pages.custom.items.categories.edit', compact('page', 'category'));
    }

    public function update(Request $request, ItemCategory $category)
    {
        $name_exists = ItemCategory::where('id', '<>', $category->id)->where('name', $request->name)->first();
        
        if($name_exists == null){
            $category->update([
                'name' => $request->name,
                'slug' => ModelHelper::convert_to_slug(ItemCategory::class, $request->name),
                'description' => $request->description
            ]);

            return redirect()->back()->with('alert', 'success:Well done! You successfully updated a category');
        }
        else{
            return redirect()->back()->with('alert', 'danger:Failed! Name already exists');
        }
    }

    public function single_delete(Request $request)
    {
        $category = ItemCategory::findOrFail($request->categories);
        $category->delete();

        return redirect()->back()->with('alert', 'success:Well done! You successfully deleted an category');
    }

    public function multiple_delete(Request $request)
    {
        $categories = explode("|",$request->categories);

        foreach($categories as $category){
            ItemCategory::whereId((int) $category)->delete();
        }

        return redirect()->back()->with('alert', 'success:Well done! You successfully deleted multiple categories');
    }

    public function single_restore(Request $request)
    {
        $category = ItemCategory::withTrashed()->findOrFail($request->categories);
        $category->restore();

        return redirect()->back()->with('alert', 'success:Well done! You successfully restored an category');
    }

    public function multiple_restore(Request $request)
    {
        $categories = explode("|",$request->categories);

        foreach($categories as $category){
            ItemCategory::withTrashed()->whereId((int) $category)->restore();
        }

        return redirect()->back()->with('alert', 'success:Well done! You successfully restored multiple categories');
    }
    
}
