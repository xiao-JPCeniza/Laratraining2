<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssetRequest;
use App\Http\Requests\UpdateAssetRequest;
use App\Http\Resources\AssetResource;
use App\Mail\DataCreatedNotification;
use App\Models\Asset;
use Illuminate\Http\Request;
use App\Models\AssetCategory;

use App\Models\Category;
use App\Models\Location;
use App\Models\Manufacturer;
use App\Models\User;



class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::orderBy('name','asc')->get(['id','name']);
        $locations = Location::orderBy('name','asc')->get(['id','name']);
        $manufacturers = Manufacturer::orderBy('name','asc')->get(['id','name']);
        $users = User::orderBy('name','asc')->get(['id','name']);

        return inertia('Category/Assets', [
            'categories' => $categories,
            'locations' => $locations,
            'manufacturers' => $manufacturers,
            'users' => $users,
        ]);


    }
     public function list(Request $request)
     {
         $query = Asset::query();
 
         if ($request->has('searchtext') && !empty($request->input('searchtext'))) {
             $search = $request->input('searchtext');
             $query
                 ->whereLike('name', '%'.$search.'%')
                 ->orWhereLike('model_name', '%'.$search.'%')
                 ->orWhereLike('status', '%'.$search.'%')
                 ->orWhereLike('notes', '%'.$search.'%');
         }
 
         if ($request->has('sort_field') && $request->has('sort_direction')) {
             $query->orderBy($request->input('sort_field'), $request->input('sort_direction'));
         } else {
             $query->orderBy('name', 'asc'); // Default sorting
         }
 
         $assets = AssetResource::collection(
             $query->orderBy('name', 'asc')->paginate($request->input('per_page', 5))
         );
 
         
         return $assets;
     }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAssetRequest $request )
    {
        $validatedData = $request->validated();
        $asset = Asset::create($validatedData);
        \Mail::to('cenizajp12@gmail.com')->send(new DataCreatedNotification($asset->id,$asset, url('/assets/'.$asset->id)));
        //    \Mail::to('aguilarufino@gmail.com')->send(new DataCreatedNotification($category->id,$category, url('/categories/'.$category->id)));


        return response()->json([
            'message' => 'Asset created successfully',
            'Asset' => $asset,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Asset $asset)
    {

        $asset = Asset::with(['category', 'location','manufacturer','assignedTo'])->findOrFail($asset->id);

        if (! $asset) {
            return redirect()->back()->with('error','Asset not found.');
        }
        return response()->json(new AssetResource($asset));

        // $asset = Asset::findOrFail($asset->id);

        // if(!$asset) {
        //     return redirect ()->back()->with('error','Asset not found');
        // }

        // return response()->json($asset);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAssetRequest $request, Asset $asset)
    {
        $validatedData = $request->validated();
        $asset->update($validatedData);

        return response()->json([
            'message' => 'Asset updated successfully',
            'asset' => $asset->fresh()
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        try {
            $Asset = Asset::findOrFail($id);
            $Asset->delete();

            return response()->json([
                'message' => 'Asset deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete Asset'
            ], 500);
        } 
        
    }
}
