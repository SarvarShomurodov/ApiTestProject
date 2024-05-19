<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
        * @OA\Get(
        *     path="/api/products",
        *     summary="Get all products",
        *     tags={"Products"},
        *     @OA\Response(
        *         response=200,
        *         description="List of products",
        *         @OA\JsonContent(
        *             type="array",
        *             @OA\Items(ref="#/components/schemas/Product")
        *         ),
        *     ),
        * )
    */
    public function index()
    {
        $products = Product::all();
        return response()->json($products, 200);
    }

    /**
        * @OA\Post(
        *     path="/api/products",
        *     summary="Create a new product",
        *     security={{ "bearerAuth":{} }},
        *     tags={"Products"},
        *     @OA\RequestBody(
        *         required=true,
        *         @OA\MediaType(
        *             mediaType="multipart/form-data",
        *             @OA\Schema(
        *                 required={"category_id", "title", "short_description", "long_description", "image"},
        *                 @OA\Property(property="category_id", type="integer", format="int64"),
        *                 @OA\Property(property="title", type="string"),
        *                 @OA\Property(property="short_description", type="string"),
        *                 @OA\Property(property="long_description", type="string"),
        *                 @OA\Property(property="image", description="Image file to upload", type="string", format="binary"),
        *             ),
        *         ),
        *     ),
        *     @OA\Response(
        *         response=201,
        *         description="Product created successfully",
        *         @OA\JsonContent(
        *             type="object",
        *             @OA\Property(property="message", type="string", example="Product created successfully"),
        *             @OA\Property(property="product", ref="#/components/schemas/Product"),
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
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'short_description' => 'required|string',
            'long_description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $imagePath = $request->file('image') ? $request->file('image')->store('products', 'public') : null;
        $product = Product::create([
            'category_id'=>$request->category_id,
            'title' => $request->title,
            'short_description' => $request->short_description,
            'long_description' => $request->long_description,
            'image' => $imagePath,
        ]);

        return response()->json(['message' => 'Product create successfully', 'product' => $product], 201);
    }

    /**
        * @OA\Get(
        *     path="/api/products/{id}",
        *     summary="Get a product by ID",
        *     tags={"Products"},
        *     @OA\Parameter(
        *         name="id",
        *         in="path",
        *         description="ID of the product",
        *         required=true,
        *         @OA\Schema(type="integer")
        *     ),
        *     @OA\Response(
        *         response=200,
        *         description="Product details",
        *         @OA\JsonContent(ref="#/components/schemas/Product"),
        *     ),
        *     @OA\Response(
        *         response=404,
        *         description="Product not found",
        *         @OA\JsonContent(
        *             type="object",
        *             @OA\Property(property="error", type="string", example="Not Found"),
        *             @OA\Property(property="message", type="string", example="Product not found"),
        *         ),
        *     ),
        * )
    */

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product, 200);
    }

    /**
        * @OA\Post(
        *     path="/api/products/{id}",
        *     summary="Update a product",
        *     security={{ "bearerAuth":{} }},
        *     tags={"Products"},
        *     @OA\Parameter(
        *         name="id",
        *         in="path",
        *         description="ID of the product to update",
        *         required=true,
        *         @OA\Schema(type="integer")
        *     ),
        *     @OA\RequestBody(
        *         required=true,
        *         @OA\MediaType(
        *             mediaType="multipart/form-data",
        *             @OA\Schema(
        *                 required={"category_id", "title", "short_description", "long_description", "image"},
        *                 @OA\Property(property="category_id", type="integer", format="int64"),
        *                 @OA\Property(property="title", type="string"),
        *                 @OA\Property(property="short_description", type="string"),
        *                 @OA\Property(property="long_description", type="string"),
        *                 @OA\Property(property="image", description="Image file to upload", type="string", format="binary"),
        *             ),
        *         ),
        *     ),
        *     @OA\Response(
        *         response=200,
        *         description="Product updated successfully",
        *         @OA\JsonContent(
        *             type="object",
        *             @OA\Property(property="message", type="string", example="Product updated successfully"),
        *             @OA\Property(property="product", ref="#/components/schemas/Product"),
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
            'category_id'=>'required|integer',
            'title' => 'required|string|max:255',
            'short_description' => 'required|string',
            'long_description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        $product = Product::find($id);
        $product->category_id = $request->input('category_id');
        $product->title = $request->input('title');
        $product->short_description = $request->input('short_description');
        $product->long_description = $request->input('long_description');
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
            $product->image = $imagePath;
        }
        $product->save();
        return response()->json(['message' => 'Product updated successfully', 'product' => $product], 200);
    }
    /**
        * @OA\Delete(
        *     path="/api/products/{id}",
        *     summary="Delete a product",
        *     security={{ "bearerAuth":{} }},
        *     tags={"Products"},
        *     @OA\Parameter(
        *         name="id",
        *         in="path",
        *         description="ID of the product to delete",
        *         required=true,
        *         @OA\Schema(type="integer")
        *     ),
        *     @OA\Response(
        *         response=204,
        *         description="Product deleted successfully",
        *         @OA\JsonContent(
        *             type="object",
        *             @OA\Property(property="message", type="string", example="Product deleted successfully"),
        *         ),
        *     ),
        *     @OA\Response(
        *         response=404,
        *         description="Product not found",
        *         @OA\JsonContent(
        *             type="object",
        *             @OA\Property(property="error", type="string", example="Not Found"),
        *             @OA\Property(property="message", type="string", example="Product not found"),
        *         ),
        *     ),
        * )
    */

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(204);
    }
}
