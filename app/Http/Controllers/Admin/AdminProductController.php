<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Http\Requests\AdminProductRequest;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('categories')->latest()->paginate(10);        

        return view('admin.product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::get();
        return view('admin.product.create', ['categories' => $categories]);
    }

    public function store(AdminProductRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $path = Storage::disk('cloudinary')->put(
                'laravel-smartcart-api-products',
                $request->file('image')
            );

            $data['image'] = Storage::disk('cloudinary')->url($path);
        }

        $product = Product::create([
            'name' => $data['name'],
            'price' => $data['price'],
            'description' => $data['description'] ?? null,
            'image' => $data['image'] ?? null,
        ]);

        $product->categories()->sync($data['category_ids']);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::with('categories')->where('id', $id)->first();
        $categories = Category::get();

        return view('admin.product.edit', compact('product', 'categories'));
    }

    public function update(AdminProductRequest $request, Product $product)
    {
        $data = $request->validated();

        // Upload new image to Cloudinary
        if ($request->hasFile('image')) {

            // Delete old Cloudinary image
            if ($product->image) {
                $this->deleteCloudinaryImage($product->image);
            }

            $path = Storage::disk('cloudinary')->put(
                'laravel-smartcart-api-products',
                $request->file('image')
            );

            $data['image'] = Storage::disk('cloudinary')->url($path);

            // Optional:
            // Delete old Cloudinary image here if your disk supports deletion.
        }

        // Update product
        $product->update([
            'name' => $data['name'],
            'price' => $data['price'],
            'description' => $data['description'] ?? null,
            'image' => $data['image'] ?? $product->image,
        ]);

        // Update categories
        $product->categories()->sync($data['category_ids']);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        // Delete image from Cloudinary
        if ($product->image) {
            $this->deleteCloudinaryImage($product->image);
        }

        // Remove product-category relationships
        $product->categories()->detach();

        // Delete product
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    private function deleteCloudinaryImage(?string $url): void
    {
        if (!$url) {
            return;
        }

        $path = parse_url($url, PHP_URL_PATH);

        if (!$path) {
            return;
        }

        // Example:
        // /image/upload/v1234567890/laravel-smartcart-api-products/abc123.jpg

        $path = ltrim($path, '/');

        $parts = explode('/', $path);

        $uploadIndex = array_search('upload', $parts);

        if ($uploadIndex === false) {
            return;
        }

        // Everything after "upload"
        $publicPath = array_slice($parts, $uploadIndex + 1);

        // Remove version: v1234567890
        if (isset($publicPath[0]) && str_starts_with($publicPath[0], 'v')) {
            array_shift($publicPath);
        }

        // Remove extension
        $publicId = implode('/', $publicPath);
        $publicId = pathinfo($publicId, PATHINFO_DIRNAME) !== '.'
            ? pathinfo($publicId, PATHINFO_DIRNAME) . '/' . pathinfo($publicId, PATHINFO_FILENAME)
            : pathinfo($publicId, PATHINFO_FILENAME);

        try {
            Storage::disk('cloudinary')->delete($publicId);
        } catch (\Throwable $e) {
            \Log::warning('Failed to delete Cloudinary image', [
                'url' => $url,
                'public_id' => $publicId,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
