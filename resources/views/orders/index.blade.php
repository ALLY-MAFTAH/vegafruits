@extends('layouts.app')
@section('title')
    Orders
@endsection
@section('style')
@endsection
@section('content')
    @if (session('info'))
        <div class="alert alert-info" role="alert">
            {{ session('info') }}
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif
    <div class="card">
        <div class=" card-header">
            <div class="row">
                <div class="col">
                    <div class=" text-left">
                        <h5 class="my-0">
                            <span class="">
                                <b>{{ __('ORDERS') . ' - ' }}
                                </b>
                                <div class="btn btn-icon round"
                                    style="height: 32px;width:32px;cursor: auto;padding: 0;font-size: 15px;line-height:2rem; border-radius:50%;background-color:rgb(229, 207, 242);color:var(--first-color)">
                                    {{ $orders->count() }}
                                </div>
                            </span>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="collapseTwo" style="width: 100%;border-width:0px" class="accordion-collapse collapse"
                aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="card mb-1 p-2" style="background: var(--form-bg-color)">
                                <form id="add-order-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col mb-1">
                                            <label for="order"
                                                class=" col-form-label text-sm-start"><b>{{ __('Order Products') }}</b></label>
                                            <div
                                                class="input-group input-group control-group after-add-more"style="margin-bottom:10px">
                                                <select id="order_name" class="input-field form-control form-select"
                                                    name="ids[]" required style="float: left;">
                                                    <option value="">{{ 'Please Select Product' }}</option>
                                                    @foreach ($stocks as $stock)
                                                        <option value="{{ $stock->id }}">{{ $stock->name }} -
                                                            {{ $stock->volume }} {{ $stock->measure }}
                                                            ({{ $stock->unit }})
                                                        </option>
                                                    @endforeach
                                                    @error('order_name')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </select>
                                                <input id="order_quantity" type="number" min="1" placeholder="0"
                                                    class="form-control @error('order_quantity') is-invalid @enderror"
                                                    name="quantities[]" value="{{ old('order_quantity') }}" required
                                                    autocomplete="order_quantity" autofocus
                                                    style="float: left;max-width:130px">
                                                @error('order_quantity')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                                <span class="input-group-btn">
                                                    <button class="btn btn-outline-success add-more"
                                                        type="button"style="border-top-left-radius: 0%;border-bottom-left-radius: 0%"><i
                                                            class="bx bx-plus"></i></button>
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Copy Fields -->
                                        <div class="copy hide">
                                            <div class="input-group control-group" style="margin-bottom:10px">
                                                <select id="order_name" class="input-field form-control form-select"
                                                    name="ids[]" required style="float: left; width: inaitial; ">
                                                    <option value="">{{ 'Please Select Products' }}</option>
                                                    @foreach ($stocks as $stock)
                                                        <option value="{{ $stock->id }}">{{ $stock->name }} -
                                                            {{ $stock->volume }} {{ $stock->measure }}
                                                            ({{ $stock->unit }})
                                                        </option>
                                                    @endforeach
                                                    @error('order_name')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </select>
                                                <input id="order_quantity" type="number" step="any" placeholder="0"
                                                    class="form-control @error('order_quantity') is-invalid @enderror"
                                                    name="quantities[]" value="{{ old('order_quantity') }}" required
                                                    autocomplete="order_quantity" autofocus
                                                    style="float: left;max-width:130px">
                                                @error('order_quantity')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror

                                                <span class="input-group-btn">
                                                    <button class="btn btn-outline-danger remove"
                                                        type="button"style="border-top-left-radius: 0%;border-bottom-left-radius: 0%"><i
                                                            class="bx bx-minus"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-1 mt-2">
                                        <div class="text-center">
                                            <button id="" class="btn btn-sm btn-outline-primary" type="submit">
                                                {{ __('Submit') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-7">
                            <div id="orderSummary" style="width: 100%;border-width:0px" class="accordion-collapse collapse"
                                aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div id=" summary-card" class="card">
                                        <div class="card-body text-center">
                                            <h6><b>ORDER SUMMARY</b></h6>
                                            <hr style="margin:0px">
                                            <div class="row">
                                                <div class="col-7">
                                                    <div class="card my-2">
                                                        <table class="table table-stripped" id="order-summary">
                                                            <tbody>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="col-5">
                                                    <div class="card my-2">
                                                        <b>This order will be sent to:</b>
                                                        <br>
                                                        <h6 class="text-start ml-2">
                                                            <b> Admin: </b> &nbsp;&nbsp;{{ setting('Admin Email') }}
                                                        </h6>
                                                        <h6 class="text-start ml-2">
                                                            <b> Afya: </b> &nbsp;&nbsp; {{ setting('Afya Email') }}
                                                        </h6>
                                                        <div class="text-end px-3">
                                                            @if (Auth::user()->role_id == 1)
                                                                <a href="{{ route('settings.index') }}"><i
                                                                        class="fa fa-edit"style="font-size:26px"></i>
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <form id="send-order-form" action="{{ route('orders.send') }}"
                                                method="post">
                                                @csrf
                                                <button id="send-order-btn" class="btn btn-sm btn-outline-primary"
                                                    type="submit" aria-expanded="false" aria-controls="orderSummary">
                                                    {{ __('Send Order') }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <hr>
            </div>
            <table id="data-tebo1"
                class="dt-responsive nowrap table table-bordered shadow rounded-3 table-responsive-sm  table-striped table-hover"
                style="width: 100%">
                <thead class="shadow rounded-3">
                    <th style="max-width: 20px">#</th>
                    <th>Order Number</th>
                    <th>Created Date</th>
                    <th>Order Items</th>
                    <th>Amount</th>
                    <th>Delivery Location</th>
                    <th>Delivery Date</th>
                    <th>Customer</th>
                    <th>Served Date</th>
                    <th></th>
                    <th></th>
                </thead>
                <tbody>
                    @foreach ($orders as $index => $order)
                        <tr>
                            <td>{{ ++$index }}</td>
                            <td>{{ $order->number }}</td>
                            <td>{{ Illuminate\Support\Carbon::parse($order->date)->format('D, d M, Y') }}</td>
                            <td>
                                @foreach ($order->items as $item)
                                    <div>
                                        {{ $item->name }} -
                                        {{ $item->quantity }} {{ $item->unit }},
                                    </div>
                                @endforeach
                            </td>
                            <td>{{ number_format($order->total_amount, 0, '.', ',') }} Tsh </td>
                            <td>{{ $order->delivery_location }}</td>
                            <td>
                                <div>

                                    {{ Illuminate\Support\Carbon::parse($order->delivery_date)->format('D, d M, Y') }}
                                </div>
                                <div>
                                    {{ ' at ' . $order->delivery_time }}
                                </div>
                            </td>
                            <td>
                                <div>
                                    {{ $order->customer->name }}
                                </div>
                                <div>

                                    {{ $order->customer->mobile }}
                                </div>
                            </td>
                            @if ($order->served_date != null)
                                <td>{{ Illuminate\Support\Carbon::parse($order->served_date)->format('D, d M Y') }}</td>
                            @else
                                <td>Not Served</td>
                            @endif

                            @if ($order->served_date == null)
                                <td class="text-center">
                                    <a href="{{ route('sales.sell', $order) }}"
                                        class="btn btn-sm btn-outline-success">SELL</a>
                                </td>
                            @else
                                <td><label style="padding:5px;background:rgb(161, 227, 161)">Served</label></td>
                            @endif

                            <td class="text-center">
                                <a href="#" class="btn btn-sm btn-outline-danger"
                                    onclick="if(confirm('Are you sure want to delete {{ $order->name }}?')) document.getElementById('delete-role-{{ $order->id }}').submit()">
                                    <i class="f"></i>Delete
                                </a>
                                <form id="delete-role-{{ $order->id }}" method="post"
                                    action="{{ route('orders.delete', $order) }}">@csrf @method('delete')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        var selectedStocks;

        $(document).ready(function() {
            $('#add-order-form').submit(function(event) {
                event.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: 'add-order',
                    data: $(this).serialize(),
                    success: function(response) {
                        console.log(response);
                        selectedStocks = response;
                        var tbody = $('#order-summary tbody');
                        tbody.empty();
                        response.forEach(function(selectedStock) {
                            tbody.append(
                                '<tr style="margin:0px;padding:0px"><td class="text-start">' +
                                selectedStock
                                .name + ' ' +
                                selectedStock.volume + ' ' + selectedStock.measure +
                                '</td><td colspan="1"></td><td class="text-start">' +
                                selectedStock.quantity + ' ' + selectedStock.unit +
                                '</td></tr>'
                            );
                        });
                        $("#orderSummary").collapse("show");
                    }
                });
            });

            $('#send-order-form').submit(function() {
                $('<input>').attr({
                    type: 'hidden',
                    name: 'selectedStocks',
                    value: JSON.stringify(selectedStocks)
                }).appendTo('#send-order-form');
            });

        });
    </script>
    <script>
        let selectedProducts = [];
        $(document).on('change', '.input-field', function() {
            let selectedProduct = this.value;
            if (selectedProducts.includes(selectedProduct)) {
                alert('Product has already been selected. Please choose another product.');
                this.value = '';
            } else {
                selectedProducts.push(selectedProduct);
            }
        });
    </script>
@endsection
