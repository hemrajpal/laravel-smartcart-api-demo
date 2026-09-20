<?php

namespace App\Repositories;

use Illuminate\Http\Request;

use App\Models\Product;

class ProductRepository
{
    public function getProducts(Request $request)
    {
        $query = Product::query();

        // Search by name
        if ($request->filled('search')) {
            $query->where(
                'name',
                'like',
                '%' . $request->search . '%'
            );
        }

        // Minimum price
        if ($request->filled('min_price')) {
            $query->where(
                'price',
                '>=',
                $request->min_price
            );
        }

        // Maximum price
        if ($request->filled('max_price')) {
            $query->where(
                'price',
                '<=',
                $request->max_price
            );
        }

        // Sort by price
        if ($request->sort_by === 'price') {
            $direction = $request->order === 'desc'
                ? 'desc'
                : 'asc';

            $query->orderBy('price', $direction);
        } else {
            $query->latest();
        }

        return $query->paginate(10);
    }

    public function find(int $id): ?Product
    {
        return Product::find($id);
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data): bool
    {
        return $product->update($data);
    }

    public function delete(Product $product): bool
    {
        return $product->delete();
    }
}