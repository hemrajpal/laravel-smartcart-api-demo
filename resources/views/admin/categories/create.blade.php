@extends('admin.layouts.admin')

@section('title', 'Create Category')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-1">Create Category</h1>
        <p class="text-muted mb-0">
            Add a new product category.
        </p>
    </div>

    <a
        href="{{ route('admin.categories.index') }}"
        class="btn btn-secondary"
    >
        Back
    </a>
</div>

<div class="card">
    <div class="card-body">

        {{-- @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif --}}

        <form
            method="POST"
            action="{{ route('admin.categories.store') }}"
        >
            @csrf

            <div class="mb-3">
                <label for="title" class="form-label">
                    Category Title
                </label>

                <input
                    type="text"
                    id="title"
                    name="title"
                    value="{{ old('title') }}"
                    class="form-control @error('title') is-invalid @enderror"
                    placeholder="Enter category name"
                    required
                >

                @error('title')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="slug" class="form-label">
                    Slug
                </label>

                <input
                    type="text"
                    id="slug"
                    name="slug"
                    value="{{ old('slug') }}"
                    class="form-control @error('slug') is-invalid @enderror"
                    placeholder="category-slug"
                >

                @error('slug')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">
                    Description
                </label>

                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    class="form-control @error('description') is-invalid @enderror"
                    placeholder="Enter category description"
                >{{ old('description') }}</textarea>

                @error('description')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <button
                type="submit"
                class="btn btn-primary"
            >
                Create Category
            </button>

            <a
                href="{{ route('admin.categories.index') }}"
                class="btn btn-secondary"
            >
                Cancel
            </a>

        </form>

    </div>
</div>

@endsection