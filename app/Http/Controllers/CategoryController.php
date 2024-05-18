<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProductStoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required',
            'long_description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        $imagePath = $request->file('image') ? $request->file('image')->store('categories', 'public') : null;

        $category = Category::create([
            'title' => $request->title,
            'short_description' => $request->short_description,
            'long_description' => $request->long_description,
            'image' => $imagePath,
        ]);

        return response()->json(['message' => 'Category create successfully', 'category' => $category], 201);
    }

    public function show($id)
    {
        try
        {
            $category = Category::findOrFail($id);
            return response()->json($category);
        }catch (ModelNotFoundException $e)
        {
            return response()->json([
                'error' => "Not Found",
                'message' => 'Category not found'
            ], 404);

        }
    }

    public function edit(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string',
            'long_description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

            $category = Category::find($id);
            $category->title = $request->input('title');
            $category->short_description = $request->input('short_description');
            $category->long_description = $request->input('long_description');
            if ($request->hasFile('image')) {
                if ($category->image) {
                    Storage::disk('public')->delete($category->image);
                }
                $imagePath = $request->file('image')->store('categories', 'public');
                $category->image = $imagePath;
            }
            $category->save();
            return response()->json(['message' => 'Category updated successfully', 'category' => $category], 200);
    }
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();
        return response()->json([
            'message' => 'Category is deleted successfully.',
        ], 204);
    }
}
