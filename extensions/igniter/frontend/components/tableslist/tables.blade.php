<div class="container mt-5 mb-5">
    <div class="row">
        @foreach ($tables as $table)
            <div class="col-md-4 mb-4">
                <div class="table-card h-100">
                    <div class="card-body">
{{--                        <h5 class="card-title">Table #{{ $table->table_id }}</h5>--}}
                        <h5 class="card-title">{{ $table->table_name }}</h5>
                        <p class="card-text"><strong>Min Capacity:</strong> {{ $table->min_capacity }}</p>
                        <p class="card-text"><strong>Max Capacity:</strong> {{ $table->max_capacity }}</p>
                        <p class="card-text"><strong>Joinable:</strong> {{ $table->is_joinable ? 'Yes' : 'No' }}</p>
                    </div>
                    <div class="status {{ $table->table_status ? 'available' : 'unavailable' }}">
                        {{ $table->table_status ? '✔' : '✘' }}
                    </div>
                    <div class="card-footer">
                        @if ($table->table_status)
                            <a class="btn btn-primary" href="{{ page_url('local/menus') }}?table_id={{ optional($table)->table_id }}">
                                Order Now
                            </a>
                        @endif

                        <!-- Display orders for this table -->
                        @foreach ($orders as $order)
                            @if ($order->table_id == $table->table_id && $order->processed == 0)
                                <a class="btn btn-info" href="{{ page_url('/checkout/') }}?order_id={{ $order->order_id }}">
                                    Checkout #{{ $order->order_id }}
                                </a>
                                <a class="btn btn-secondary disabled" href="{{ page_url('local/menus') }}?table_id={{ $table->table_id }}&order_id={{ $order->order_id }}">
                                    <i class="fas fa-edit"></i> Edit Order #{{ $order->order_id }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
