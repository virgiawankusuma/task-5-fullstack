<?php

namespace App\Http\Controllers\api\v1;

use App\Models\Category;
use App\Http\Resources\api\v1\CategoryResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    function index()
    {
        $categories = Category::all();

        return new CategoryResource($categories);
    }

    function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required'
        ]);

        $category = Category::create($validated);

        return new CategoryResource($category);
    }

    function show(Category $category)
    {
        return new CategoryResource($category);
    }

    function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required'
        ]);

        $category->update($validated);

        return response([
            'message' => 'Category updated successfully',
            'category' => new CategoryResource($category)
        ]);
    }

    function destroy(Category $category)
    {
        $category->delete();

        return response([
            'message' => 'Category deleted successfully'
        ]);
    }
}
