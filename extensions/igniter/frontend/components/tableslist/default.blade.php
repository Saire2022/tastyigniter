
<div class="container mt-5 ">
    <h2 class="mb-3"> Select Table for {{ $locationName }}</h2>
</div>

<div class="container mt-5 mb-5">
    <div class="list-table table-responsive">
        <table class="table table-hover mb-0 border-bottom">
            <thead>
            <tr>
                <th scope="col">Table ID</th>
                <th scope="col">Name</th>
                <th scope="col">Minimum Capacity</th>
                <th scope="col">Maximum Capacity</th>
                <th scope="col">Is Joinable</th>
                <th scope="col">Status</th>
                <th scope="col">Make order</th>
                <th scope="col">Orders</th>
            </tr>
            </thead>
            <tbody>
                @foreach ($tables as $table)
                    <tr>
                        <td class="text-center">{{ $table->table_id }}</td>
                        <td class="text-center">{{ $table->table_name }}</td>
                        <td class="text-center">{{ $table->min_capacity }}</td>
                        <td class="text-center">{{ $table->max_capacity }}</td>
                        <td class="text-center">{{ $table->is_joinable ? 'Yes' : 'No' }}</td>
                        <td class="text-center">{{ $table->table_status ? 'Available' : 'No available' }}</td>
                        <td>
                            @if ($table->table_status)
                                <a class="btn btn-primary" href="{{ page_url('local/menus') }}?table_id={{ optional($table)->table_id }}">
                                    Order Now
                                </a>
                            @else
                                <span class="text-muted">Order Now</span>
                            @endif
                        </td>
                        <td>
                            <!-- Display orders for this table -->
                            @foreach ($orders as $order)
                                @if ($order->table_id == $table->table_id && $order->processed == 0)
                                    <a class="btn btn-primary disabled" href="{{ page_url('local/menus') }}?table_id={{ $table->table_id }}&order_id={{ $order->order_id }}">
                                        Edit Order #{{ $order->order_id }}
                                    </a>
                                    <a class="btn btn-secondary ml-3" href="{{ page_url('/checkout/') }}?order_id={{ $order->order_id }}">
                                        checkout #{{ $order->order_id }}
                                    </a>
                                @endif
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@partial('@tables')
