@extends('admin.layouts.admin')

@section('title', 'Orders')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-1">Orders</h1>
        <p class="text-muted mb-0">
            Manage customer orders.
        </p>
    </div>
</div>

<div class="card">


<div class="card-body">

    @if ($orders->count())

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($orders as $order)

                        @php
                            $statusClass = match ($order->status) {
                                'confirmed' => 'bg-primary',
                                'processing' => 'bg-info text-dark',
                                'shipped' => 'bg-warning text-dark',
                                'delivered' => 'bg-success',
                                'cancelled' => 'bg-danger',
                                default => 'bg-secondary',
                            };

                            $statusLocked = in_array(
                                $order->status,
                                ['delivered', 'cancelled']
                            );
                        @endphp

                        <tr>

                            <td>
                                {{ $orders->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <strong>
                                    #{{ $order->id }}
                                </strong>
                            </td>

                            <td>
                                @if ($order->user)

                                    <strong>
                                        {{ $order->user->name }}
                                    </strong>

                                    <br>

                                    <small class="text-muted">
                                        {{ $order->user->email }}
                                    </small>

                                @else

                                    <span class="text-muted">
                                        Guest
                                    </span>

                                @endif
                            </td>

                            <td>
                                <strong>
                                    {{ number_format($order->total_amount, 2) }}
                                </strong>
                            </td>

                            <td>

                                <span class="badge {{ $statusClass }}">
                                    {{ ucfirst($order->status) }}
                                </span>

                            </td>

                            <td>
                                {{ $order->created_at->format('d M Y') }}

                                <br>

                                <small class="text-muted">
                                    {{ $order->created_at->format('h:i A') }}
                                </small>
                            </td>

                            <td class="text-end">

                                @if (!$statusLocked)

                                    <form
                                        method="POST"
                                        action="{{ route('admin.order.status', $order) }}"
                                        class="d-inline-flex gap-2"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        <select
                                            name="status"
                                            class="form-select form-select-sm"
                                            style="width: 140px;"
                                        >

                                            @foreach ([
                                                'pending',
                                                'confirmed',
                                                'processing',
                                                'shipped',
                                                'delivered',
                                                'cancelled'
                                            ] as $status)

                                                <option
                                                    value="{{ $status }}"
                                                    @selected($order->status === $status)
                                                >
                                                    {{ ucfirst($status) }}
                                                </option>

                                            @endforeach

                                        </select>

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-primary"
                                        >
                                            Update
                                        </button>

                                    </form>

                                @else

                                    <span class="text-muted small">
                                        Status locked
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

        <div class="mt-3">
            {{ $orders->links() }}
        </div>

    @else

        <div class="text-center py-5">

            <h5>No orders found</h5>

            <p class="text-muted mb-0">
                There are currently no orders.
            </p>

        </div>

    @endif

</div>
```

</div>

@endsection
