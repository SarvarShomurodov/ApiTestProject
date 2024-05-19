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
    /**
        * @OA\Get(
        *     path="/api/categories",
        *     summary="Get all categories",
        *     tags={"Categories"},
        *     @OA\Response(
        *         response=200,
        *         description="List of categories",
        *         @OA\JsonContent(
        *             type="array",
        *             @OA\Items(ref="#/components/schemas/Category")
        *         ),
        *     ),
        * )
    */

    public function index()
    {
        return Category::all();
    }

    /**
        * @OA\Post(
        *     path="/api/categories",
        *     summary="Create a new category",
        *     security={{ "bearerAuth":{} }},
        *     tags={"Categories"},
        *     @OA\RequestBody(
        *         required=true,
        *         @OA\MediaType(
        *             mediaType="multipart/form-data",
        *             @OA\Schema(
        *                 required={"title", "short_description", "long_description", "image"},
        *                 @OA\Property(
        *                     property="title",
        *                     example="Phone",
        *                     type="string",
        *                 ),
        *                 @OA\Property(
        *                     property="short_description",
        *                     type="string",
        *                     example="Very smart phone",
        *                 ),
        *                 @OA\Property(
        *                     property="long_description",
        *                     type="string",
        *                     example="Very smart phone and beautiful",
        *                 ),
        *                 @OA\Property(
        *                     property="image",
        *                     description="Image file to upload",
        *                     type="string",
        *                     format="binary",
        *                 ),
        *             ),
        *         ),
        *     ),
        *     @OA\Response(
        *         response=201,
        *         description="Category created successfully",
        *         @OA\JsonContent(
        *             type="object",
        *             @OA\Property(property="message", type="string", example="Category created successfully"),
        *             @OA\Property(property="category", ref="#/components/schemas/Category"),
        *         ),
        *     ),
        *     @OA\Response(
        *         response=422,
        *         description="Validation error",
        *         @OA\JsonContent(
        *             type="object",
        *             @OA\Property(property="error", type="string", example="Validation error"),
        *             @OA\Property(property="message", type="object"),
        *         ),
        *     ),
        * )
    */

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

    /**
        * @OA\Get(
        *     path="/api/categories/{id}",
        *     summary="Get a category by ID",
        *     tags={"Categories"},
        *     @OA\Parameter(
        *         name="id",
        *         in="path",
        *         description="ID of the category",
        *         required=true,
        *         @OA\Schema(type="integer")
        *     ),
        *     @OA\Response(
        *         response=200,
        *         description="Category details",
        *         @OA\JsonContent(ref="#/components/schemas/Category"),
        *     ),
        *     @OA\Response(
        *         response=404,
        *         description="Category not found",
        *         @OA\JsonContent(
        *             type="object",
        *             @OA\Property(property="error", type="string", example="Not Found"),
        *             @OA\Property(property="message", type="string", example="Category not found"),
        *         ),
        *     ),
        * )
    */

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

    /**
        * @OA\Post(
        *     path="/api/categories/{id}",
        *     summary="Update a category",
        *     security={{ "bearerAuth":{} }},
        *     tags={"Categories"},
        *     @OA\Parameter(
        *         name="id",
        *         in="path",
        *         description="ID of the category to update",
        *         required=true,
        *         @OA\Schema(type="integer")
        *     ),
        *     @OA\RequestBody(
        *         required=true,
        *         @OA\MediaType(
        *             mediaType="multipart/form-data",
        *             @OA\Schema(
        *                 required={"title", "short_description", "long_description", "image"},
        *                 @OA\Property(
        *                     property="title",
        *                     type="string",
        *                 ),
        *                 @OA\Property(
        *                     property="short_description",
        *                     type="string",
        *                 ),
        *                 @OA\Property(
        *                     property="long_description",
        *                     type="string",
        *                 ),
        *                 @OA\Property(
        *                     property="image",
        *                     description="Image file to upload",
        *                     type="string",
        *                     format="binary",
        *                 ),
        *             ),
        *         ),
        *     ),
        *     @OA\Response(
        *         response=200,
        *         description="Category updated successfully",
        *         @OA\JsonContent(
        *             type="object",
        *             @OA\Property(property="message", type="string", example="Category updated successfully"),
        *             @OA\Property(property="category", ref="#/components/schemas/Category"),
        *         ),
        *     ),
        *     @OA\Response(
        *         response=422,
        *         description="Validation error",
        *         @OA\JsonContent(
        *             type="object",
        *             @OA\Property(property="error", type="string", example="Validation error"),
        *             @OA\Property(property="message", type="object"),
        *         ),
        *     ),
        * )
    */

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

    /**
        * @OA\Delete(
        *     path="/api/categories/{id}",
        *     summary="Delete a category",
        *     security={{ "bearerAuth":{} }},
        *     tags={"Categories"},
        *     @OA\Parameter(
        *         name="id",
        *         in="path",
        *         description="ID of the category to delete",
        *         required=true,
        *         @OA\Schema(type="integer")
        *     ),
        *     @OA\Response(
        *         response=204,
        *         description="Category deleted successfully",
        *         @OA\JsonContent(
        *             type="object",
        *             @OA\Property(property="message", type="string", example="Category deleted successfully"),
        *         ),
        *     ),
        *     @OA\Response(
        *         response=404,
        *         description="Category not found",
        *         @OA\JsonContent(
        *             type="object",
        *             @OA\Property(property="error", type="string", example="Not Found"),
        *             @OA\Property(property="message", type="string", example="Category not found"),
        *         ),
        *     ),
        * )
    */

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
