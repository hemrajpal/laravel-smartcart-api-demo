@extends('admin.layouts.admin')

@section('title', 'Categories')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-1">Categories</h1>
        <p class="text-muted mb-0">
            Manage product categories.
        </p>
    </div>

    <a
        href="{{ route('admin.categories.create') }}"
        class="btn btn-primary"
    >
        Add Category
    </a>
</div>

<div class="card">

    <div class="card-body">

        @if ($categories->count())

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Created</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($categories as $category)

                            <tr>
                                <td>
                                    {{ $categories->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $category->title }}
                                    </strong>
                                </td>

                                <td>
                                    <code>
                                        {{ $category->slug }}
                                    </code>
                                </td>

                                <td>
                                    {{ $category->created_at->format('d M Y') }}
                                </td>

                                <td class="text-end">

                                    <a
                                        href="{{ route('admin.categories.edit', $category) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('admin.categories.destroy', $category) }}"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this category?')"
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
                {{ $categories->links() }}
            </div>

        @else

            <div class="text-center py-5">

                <h5>No categories found</h5>

                <p class="text-muted">
                    Create your first product category.
                </p>

                <a
                    href="{{ route('admin.categories.create') }}"
                    class="btn btn-primary"
                >
                    Add Category
                </a>

            </div>

        @endif

    </div>

</div>

@endsection