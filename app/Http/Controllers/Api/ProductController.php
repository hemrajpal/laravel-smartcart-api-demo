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
use App\Repositories\ProductRepository;

class ProductController extends Controller
{

    public function __construct(
        private ProductRepository $productRepository
    ) {

    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = $this->productRepository->getProducts($request);

        return ApiResponse::success(ProductResource::collection($products), 'Product list');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }
}
