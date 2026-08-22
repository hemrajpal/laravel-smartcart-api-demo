@extends('admin.layouts.admin')

@section('title', 'Products')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-1">Products</h1>
        <p class="text-muted mb-0">
            Manage your products.
        </p>
    </div>

    <a
        href="{{ route('admin.products.create') }}"
        class="btn btn-primary"
    >
        Add Product
    </a>
</div>

<div class="card">

    <div class="card-body">

        @if ($products->count())

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Photo</th>
                            <th>Product</th>
                            <th>Categories</th>
                            <th>Price</th>
                            <th>Created</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($products as $product)

                            <tr>

                                <td>
                                    {{ $products->firstItem() + $loop->index }}
                                </td>
                                
                                <td>
                                    @if ($product->image)
                                        <img
                                            src="{{ $product->image }}"
                                            alt="{{ $product->name }}"
                                            width="60"
                                            height="60"
                                            class="rounded"
                                            style="object-fit: cover;"
                                        >
                                    @else
                                        <span class="text-muted">No Photo</span>
                                    @endif
                                </td>

                                <td>
                                    <strong>
                                        {{ $product->name }}
                                    </strong>

                                    @if ($product->description)
                                        <div class="small text-muted">
                                            {{ Str::limit($product->description, 50) }}
                                        </div>
                                    @endif
                                </td>

                                <td>

                                    @forelse ($product->categories as $category)

                                        <span class="badge bg-secondary">
                                            {{ $category->title }}
                                        </span>

                                    @empty

                                        <span class="text-muted">
                                            No category
                                        </span>

                                    @endforelse

                                </td>

                                <td>
                                    ₹{{ number_format($product->price, 2) }}
                                </td>

                                <td>
                                    {{ $product->created_at->format('d M Y') }}
                                </td>

                                <td class="text-end">

                                    <a
                                        href="{{ route('admin.products.edit', $product) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('admin.products.destroy', $product) }}"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this product?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                        >
                                            Delete
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

            <div class="mt-3">
                {{ $products->links() }}
            </div>

        @else

            <div class="text-center py-5">

                <h5>No products found</h5>

                <p class="text-muted">
                    Create your first product.
                </p>

                <a
                    href="{{ route('admin.products.create') }}"
                    class="btn btn-primary"
                >
                    Add Product
                </a>

            </div>

        @endif

    </div>

</div>

@endsection