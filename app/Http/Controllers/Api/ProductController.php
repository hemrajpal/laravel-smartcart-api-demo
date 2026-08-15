<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Http\Requests\ProductRequest;

use App\Helpers\ApiResponse;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // Search by name
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by price range
        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->sort_by == 'price') {
            $query->orderBy('price', $request->order ?? 'asc');
        }

        // Pagination
        $products = $query->latest()->paginate(10);

        return ApiResponse::success(ProductResource::collection($products), 'Product list');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        /* $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'image' => 'nullable|image',
            'category_ids' => ['required', 'array', 'min:1'],
            'category_ids.*' => ['integer', 'exists:categories,id'],
        ]);

        if ($validator->fails()) {
            return \App\Helpers\ApiResponse::error('Validation failed', $validator->errors(), 422);
        } */

        $data = $request->validated();

        // Upload to Cloudinary
        if ($request->hasFile('image')) {
            /* $result = Cloudinary::uploadApi()->upload(
                $request->file('image')->getRealPath(),
                [
                    'folder' => 'smartcart-api'
                ]
            );

            $url = $result['secure_url']; */

            $path = Storage::disk('cloudinary')->put('laravel-smartcart-api-products', $request->file('image'));

            $url = Storage::disk('cloudinary')->url($path);

            $data['image'] = $url;
        }

        $product = \App\Models\Product::create($data);

        $product->categories()->sync($data['category_ids']);

        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $product->update($request->all());

        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return ApiResponse::error('Product not found', [], 404);
        }
        
        $product->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
