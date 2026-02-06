<?php

namespace App\Http\Controllers\Custom;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Facades\App\Helpers\ListingHelper;
use App\Helpers\ModelHelper;

use App\Models\{Page};
use App\Models\Custom\{Vehicle};

class VehicleController extends Controller
{
    private $searchFields = ['name','plate_no'];

    public function index()
    {
        $page = new Page();
        $page->name = "Vehicles";
        
        $vehicles = ListingHelper::simple_search(Vehicle::class, $this->searchFields);

        $filter = ListingHelper::get_filter($this->searchFields);

        $searchType = 'simple_search';

       return view('theme.pages.custom.issuance.vehicles.index', compact('page', 'vehicles', 'filter', 'searchType'));
    }

    public function create()
    {
        $page = new Page();
        $page->name = "Vehicle";

       return view('theme.pages.custom.issuance.vehicles.create', compact('page'));
    }

    public function store(Request $request)
    {
        $plate_no_exists = Vehicle::where('plate_no', $request->plate_no)->first();
        
        if($plate_no_exists == null){
            $new_data = Vehicle::create([
                // 'name' => $request->name,
                // 'slug' => ModelHelper::convert_to_slug(Vehicle::class, $request->name),
                'plate_no' => $request->plate_no,
                'type' => $request->type,
                'description' => $request->description
            ]);
    
           return redirect()->route('issuance.vehicles.index')->with('alert', 'success:Well done! You successfully added a vehicle');
        }
        else{
            return redirect()->back()->with('alert', 'danger:Failed! Plate # already exists');
        }
    }

    public function edit(Vehicle $vehicle)
    {
        $page = new Page();
        $page->name = "Vehicle";

       return view('theme.pages.custom.issuance.vehicles.edit', compact('page', 'vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $plate_no_exists = Vehicle::where('id', '<>', $vehicle->id)->where('plate_no', $request->plate_no)->first();
        
        if($plate_no_exists == null){
            $vehicle->update([
                // 'name' => $request->name,
                // 'slug' => ModelHelper::convert_to_slug(Vehicle::class, $request->name),
                'plate_no' => $request->plate_no,
                'type' => $request->type,
                'description' => $request->description
            ]);

            return redirect()->back()->with('alert', 'success:Well done! You successfully updated a vehicle');
        }
        else{
            return redirect()->back()->with('alert', 'danger:Failed! Plate # already exists');
        }
    }

    public function single_delete(Request $request)
    {
        $vehicle = Vehicle::findOrFail($request->vehicles);
        $vehicle->delete();

        return redirect()->back()->with('alert', 'success:Well done! You successfully deleted a vehicle');
    }

    public function multiple_delete(Request $request)
    {
        $vehicles = explode("|",$request->vehicles);

        foreach($vehicles as $vehicle){
            Vehicle::whereId((int) $vehicle)->delete();
        }

        return redirect()->back()->with('alert', 'success:Well done! You successfully deleted multiple vehicles');
    }

    public function single_restore(Request $request)
    {
        $vehicle = Vehicle::withTrashed()->findOrFail($request->vehicles);
        $vehicle->restore();

        return redirect()->back()->with('alert', 'success:Well done! You successfully restored a vehicle');
    }

    public function multiple_restore(Request $request)
    {
        $vehicles = explode("|",$request->vehicles);

        foreach($vehicles as $vehicle){
            Vehicle::withTrashed()->whereId((int) $vehicle)->restore();
        }

        return redirect()->back()->with('alert', 'success:Well done! You successfully restored multiple vehicles');
    }
    
    public function search_vehicle(Request $request)
    {
        $types = $request->types ?? [];

        if (empty($types)) {
            return response()->json([]);
        }

        $vehicles = Vehicle::whereIn('type', $types)
                    ->orWhereNull('type')
                    ->get(['id', 'plate_no', 'type']);

        return response()->json($vehicles);
    }
    
}
