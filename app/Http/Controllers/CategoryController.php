<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Mail\DataCreatedNotification;
use App\Mail\NotifyUser;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('Category/Index');
    }

     public function list(Request $request)
     {
         $query = Category::query();
 
         if ($request->has('searchtext') && !empty($request->input('searchtext'))) {
             $search = $request->input('searchtext');
             $query
                 ->whereLike('name', '%'.$search.'%')
                 ->orWhereLike('description', '%'.$search.'%');
         }
 
         if ($request->has('sort_field') && $request->has('sort_direction')) {
             $query->orderBy($request->input('sort_field'), $request->input('sort_direction'));
         } else {
             $query->orderBy('name', 'asc'); // Default sorting
         }
 
         $categories = CategoryResource::collection(
             $query->orderBy('name', 'asc')->paginate($request->input('per_page', 5))
         );
 
         
         return $categories;
     }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $validatedData = $request->validated();
        $category = Category::create($validatedData);

           \Mail::to('cenizajp12@gmail.com')->send(new DataCreatedNotification($category->id,$category, url('/categories/'.$category->id)));
       \Mail::to('cenizajp12@gmail.com')->send(new NotifyUser($category->id,$category, url('/categories/'.$category->id)));

        return response()->json([
            'message' => 'Category created successfully',
            'category' => $category,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        
        $category = Category::findOrFail($category->id);

        if(!$category) {
            return redirect ()->back()->with('error','Category not found');
        }

        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $validatedData = $request->validated();
        $category->update($validatedData);

        return response()->json([
            'message' => 'Category updated successfully',
            'category' => $category->fresh()
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();

            return response()->json([
                'message' => 'Category deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete category'
            ], );
        } 
        
    }
}
