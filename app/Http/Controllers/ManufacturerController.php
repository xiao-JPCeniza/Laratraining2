<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreManufacturerRequest;
use App\Http\Requests\UpdateManufacturerRequest;
use App\Http\Resources\ManufacturerResource;
use App\Mail\DataCreatedNotification;
use App\Models\Manufacturer;
use Illuminate\Http\Request;

class ManufacturerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('Category/Manufacturer');
    }

    public function list(Request $request)
     {
        \Log::info($request->all());
         $query = Manufacturer::query();
 
         if ($request->has('searchtext') && !empty($request->input('searchtext'))) {
             $search = $request->input('searchtext');
             $query
                 ->whereLike('name', '%'.$search.'%')
                 ->orWhereLike('url', '%'.$search.'%')
                 ->orWhereLike('support_email', '%'.$search.'%');
         }
 
         if ($request->has('sort_field') && $request->has('sort_direction')) {
             $query->orderBy($request->input('sort_field'), $request->input('sort_direction'));
         } else {
             $query->orderBy('name', 'asc'); // Default sorting
         }
 
         $manufacturer = ManufacturerResource::collection(
             $query->orderBy('name', 'asc')->paginate($request->input('per_page', 5))
         );
 
         
         return $manufacturer;
     }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreManufacturerRequest $request)
    {
        $validatedData = $request->validated();
        $manufacturer = Manufacturer::create($validatedData);
    \Mail::to('cenizajp12@gmail.com')->send(new DataCreatedNotification($manufacturer->id,$manufacturer, url('/manufacturers/'.$manufacturer->id)));


        return response()->json([
            'message' => 'Manufacturer created successfully',
            'manufacturer' => $manufacturer,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Manufacturer $manufacturer)
    {

        $manufacturer = Manufacturer::findOrFail($manufacturer->id);

        if(!$manufacturer) {
            return redirect ()->back()->with('error','Manufacturer not found');
        }

        return response()->json($manufacturer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateManufacturerRequest $request, Manufacturer $manufacturer)
    {
        $validatedData = $request->validated();
        $manufacturer->update($validatedData);

        return response()->json([
            'message' => 'Manufacturer updated successfully',
            'manufacturer' => $manufacturer->fresh()
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        try {
            $manufacturer = Manufacturer::findOrFail($id);
            $manufacturer->delete();

            return response()->json([
                'message' => 'Manufacturer deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete Manufacturer'
            ], );
        } 
        
    }
}
