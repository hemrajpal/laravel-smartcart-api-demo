@extends('admin.layouts.admin')

@section('title', 'Edit Category')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Edit Category</h1>

    <a
        href="{{ route('admin.categories.index') }}"
        class="btn btn-secondary"
    >
        Back
    </a>
</div>

<div class="card">
    <div class="card-body">

        <form
            method="POST"
            action="{{ route('admin.categories.update', $category) }}"
        >
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="title" class="form-label">
                    Category Title
                </label>

                <input
                    type="text"
                    id="title"
                    name="title"
                    value="{{ old('title', $category->title) }}"
                    class="form-control @error('title') is-invalid @enderror"
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
                    value="{{ old('slug', $category->slug) }}"
                    class="form-control @error('slug') is-invalid @enderror"
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
                >{{ old('description', $category->description) }}</textarea>

                @error('description')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                Update Category
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