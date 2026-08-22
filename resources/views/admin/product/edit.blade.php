@extends('admin.layouts.admin')

@section('title', 'Edit Product')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-1">Edit Product</h1>
        <p class="text-muted mb-0">
            Update product information.
        </p>
    </div>

    <a
        href="{{ route('admin.products.index') }}"
        class="btn btn-outline-secondary"
    >
        Back to Products
    </a>
</div>

<div class="card">

    <div class="card-body">

        <form
            method="POST"
            action="{{ route('admin.products.update', $product) }}"
            enctype="multipart/form-data"
        >

            @csrf
            @method('PUT')

            {{-- Name --}}
            <div class="mb-3">

                <label for="name" class="form-label">
                    Product Name
                </label>

                <input
                    type="text"
                    name="name"
                    id="name"
                    value="{{ old('name', $product->name) }}"
                    class="form-control @error('name') is-invalid @enderror"
                    required
                >

                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            {{-- Price --}}
            <div class="mb-3">

                <label for="price" class="form-label">
                    Price
                </label>

                <input
                    type="number"
                    name="price"
                    id="price"
                    value="{{ old('price', $product->price) }}"
                    class="form-control @error('price') is-invalid @enderror"
                    step="0.01"
                    min="0"
                    required
                >

                @error('price')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            {{-- Categories --}}
            <div class="mb-3">

                <label for="category_ids" class="form-label">
                    Categories
                </label>

                @php
                    $selectedCategories = old(
                        'category_ids',
                        $product->categories->pluck('id')->toArray()
                    );
                @endphp

                <select
                    name="category_ids[]"
                    id="category_ids"
                    class="form-select @error('category_ids') is-invalid @enderror"
                    multiple
                    required
                >

                    @foreach ($categories as $category)

                        <option
                            value="{{ $category->id }}"
                            @selected(in_array($category->id, $selectedCategories))
                        >
                            {{ $category->title }}
                        </option>

                    @endforeach

                </select>

                <small class="text-muted">
                    Hold Ctrl/Cmd to select multiple categories.
                </small>

                @error('category_ids')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

                @error('category_ids.*')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            {{-- Description --}}
            <div class="mb-3">

                <label for="description" class="form-label">
                    Description
                </label>

                <textarea
                    name="description"
                    id="description"
                    rows="5"
                    class="form-control @error('description') is-invalid @enderror"
                >{{ old('description', $product->description) }}</textarea>

                @error('description')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            {{-- Current Image --}}
            @if ($product->image)

                <div class="mb-3">

                    <label class="form-label">
                        Current Image
                    </label>

                    <div>
                        <img
                            src="{{ $product->image }}"
                            alt="{{ $product->name }}"
                            width="120"
                            height="120"
                            class="rounded border"
                            style="object-fit: cover;"
                        >
                    </div>

                </div>

            @endif

            {{-- New Image --}}
            <div class="mb-4">

                <label for="image" class="form-label">
                    Change Image
                </label>

                <input
                    type="file"
                    name="image"
                    id="image"
                    class="form-control @error('image') is-invalid @enderror"
                    accept="image/*"
                >

                <small class="text-muted">
                    Leave empty to keep the current image.
                </small>

                @error('image')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            {{-- Buttons --}}
            <div class="d-flex gap-2">

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Update Product
                </button>

                <a
                    href="{{ route('admin.products.index') }}"
                    class="btn btn-secondary"
                >
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

@endsection