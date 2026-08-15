<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'SmartCart Admin')
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body>

    {{-- Navbar --}}
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">

            <a
                href="{{ route('admin.dashboard') }}"
                class="navbar-brand"
            >
                SmartCart Admin
            </a>

            <div class="d-flex align-items-center">

                <span class="text-white me-3">
                    {{ auth()->user()->name }}
                </span>

                <form
                    method="POST"
                    action="{{ route('admin.logout') }}"
                >
                    @csrf

                    <button
                        type="submit"
                        class="btn btn-outline-light btn-sm"
                    >
                        Logout
                    </button>
                </form>

            </div>

        </div>
    </nav>


    <div class="container-fluid">

        <div class="row">

            {{-- Sidebar --}}
            <aside class="col-md-2 bg-light min-vh-100 p-3">

                <h6 class="text-muted">
                    MENU
                </h6>

                <div class="nav flex-column">

                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="nav-link"
                    >
                        Dashboard
                    </a>

                    <a
                        href="{{ route('admin.categories.index') }}"
                        class="nav-link"
                    >
                        Categories
                    </a>

                    <a
                        {{-- href="{{ route('admin.products.index') }}" --}}
                        class="nav-link"
                    >
                        Products
                    </a>

                </div>

            </aside>


            {{-- Main Content --}}
            <main class="col-md-10 p-4">

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')

            </main>

        </div>

    </div>


    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    ></script>

</body>

</html>