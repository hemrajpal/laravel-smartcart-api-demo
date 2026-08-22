@extends('admin.layouts.admin')

@section('title', 'Create Product')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-1">Create Product</h1>
        <p class="text-muted mb-0">
            Add a new product.
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

        {{-- Validation errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Please fix the following errors:</strong>

                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('admin.products.store') }}"
            enctype="multipart/form-data"
        >

            @csrf

            {{-- Product Name --}}
            <div class="mb-3">

                <label for="name" class="form-label">
                    Product Name
                </label>

                <input
                    type="text"
                    name="name"
                    id="name"
                    value="{{ old('name') }}"
                    class="form-control @error('name') is-invalid @enderror"
                    placeholder="Enter product name"
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
                    value="{{ old('price') }}"
                    class="form-control @error('price') is-invalid @enderror"
                    placeholder="Enter price"
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
                            @selected(
                                in_array(
                                    $category->id,
                                    old('category_ids', [])
                                )
                            )
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
                    placeholder="Enter product description"
                >{{ old('description') }}</textarea>

                @error('description')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            {{-- Image --}}
            <div class="mb-4">

                <label for="image" class="form-label">
                    Product Image
                </label>

                <input
                    type="file"
                    name="image"
                    id="image"
                    class="form-control @error('image') is-invalid @enderror"
                    accept="image/*"
                >

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
                    Create Product
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