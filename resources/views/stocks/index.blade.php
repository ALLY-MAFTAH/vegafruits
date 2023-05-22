@extends('layouts.app')
@section('title')
    Stocks
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
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <div class=" text-left">
                        <h5 class="my-0">
                            <span class="">
                                <b>{{ __('STOCK') . ' - ' . $stocks->count() }}
                                </b>
                            </span>
                        </h5>
                    </div>
                </div>
                <div class="col text-end">
                    <a href="#" class="btn btn-sm btn-outline-primary collapsed" type="button"
                        data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false"
                        aria-controls="collapseTwo">
                        <i class="feather icon-plus"></i> Add New Product
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="collapseTwo" style="width: 100%;border-width:0px" class="accordion-collapse collapse"
                aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="card mb-1 p-2" style="background: var(--form-bg-color)">
                        <form method="POST" action="{{ route('stocks.add') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-sm-4 mb-1">
                                    <label for="name" class=" col-form-label text-sm-start">{{ __('Name') }}</label>
                                    <div class="input-group">
                                        <input id="name" type="text" placeholder=""
                                            class="form-control @error('name') is-invalid @enderror" name="name"
                                            value="{{ old('name') }}" required autocomplete="name" autofocus
                                            style="float: left;">
                                        <select class="form-control form-select" name="type" required
                                            style="float: left;max-width:115px; width: inaitial; background-color:rgb(238, 238, 242)">
                                            <option value="">Type</option>
                                            <option value="Matunda">Matunda</option>
                                            <option value="Mboga">Mboga</option>
                                            <option value="Mizizi">Mizizi</option>
                                            <option value="Nafaka">Nafaka</option>
                                            <option value="Viungo">Viungo</option>
                                        </select>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-4 mb-1">
                                    <label for="quantity"
                                        class=" col-form-label text-sm-start">{{ __('Stock Quantity') }}</label>
                                    <div class="input-group">
                                        <input id="quantity" type="number" step="any" placeholder="00"
                                            class="form-control @error('quantity') is-invalid @enderror" name="quantity"
                                            value="{{ old('quantity') }}" required autocomplete="quantity" autofocus
                                            style="float: left;">
                                        <select class="form-control form-select" name="unit" required
                                            style="float: left;max-width:115px; width: inaitial; background-color:rgb(238, 238, 242)">
                                            <option value="">Unit</option>
                                            <option value="Kilogram">Kilogram</option>
                                            <option value="Litre">Litre</option>
                                            <option value="Piece">Piece</option>
                                            <option value="Bottle">Bottle</option>
                                            <option value="Packet">Packet</option>
                                        </select>
                                        @error('quantity')
                                            <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-4 mb-1">
                                    <label for="photo" class=" col-form-label text-sm-start">{{ __('Photo') }}</label>
                                    <div class="">
                                        <input id="photo" type="file" accept=".png,.jpg,.jpeg,.gif"
                                            class="form-control @error('photo') is-invalid @enderror" name="photo"
                                            value="{{ old('photo') }}"required autocomplete="photo" autofocus>
                                        @error('photo')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4 mb-1">
                                    <label for="volume"
                                        class=" col-form-label text-sm-start">{{ __('Selling Quantity') }}</label>
                                    <div class="">
                                        <input id="volume" type="number" step="any" placeholder="00"
                                            class="form-control @error('volume') is-invalid @enderror" name="volume"
                                            value="{{ old('volume') }}"required autocomplete="volume" autofocus>
                                        @error('volume')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-4 mb-1">
                                    <label for="buying_price"
                                        class=" col-form-label text-sm-start">{{ __('Buying Price') }}</label>
                                    <div class="">
                                        <input id="buying_price" type="number" step="any" placeholder="Tsh"
                                            class="form-control @error('buying_price') is-invalid @enderror"
                                            name="buying_price" value="{{ old('buying_price') }}"required
                                            autocomplete="buying_price" autofocus>
                                        @error('buying_price')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-4 mb-1">
                                    <label for="selling_price"
                                        class=" col-form-label text-sm-start">{{ __('Selling Price') }}</label>
                                    <div class="">
                                        <input id="selling_price" type="number" step="any" placeholder="Tsh"
                                            class="form-control @error('selling_price') is-invalid @enderror"
                                            name="selling_price" value="{{ old('selling_price') }}"required
                                            autocomplete="selling_price" autofocus>
                                        @error('selling_price')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-1 mt-2">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                        {{ __('Submit') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div><br>
            </div>
            <div class="table-responsive">
                <table id="data-tebo1" class="dt-responsive nowrap table shadow rounded-3  table-striped table-hover"
                    style="width: 100%">
                    <thead class="shadow rounded-3">
                        <tr>
                            <th style="max-width: 20px">#</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Quantity</th>
                            <th class="text-right">Buying Price</th>
                            <th class="text-right">Selling Price</th>
                            <th>Last Updated</th>
                            <th class="text-center">Discount</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalBuyingPrice = 0;
                            $totalSellingPrice = 0;
                        @endphp
                        @foreach ($stocks as $index => $stock)
                            <tr>
                                <td>{{ ++$index }}</td>
                                <td>
                                    <div class="profile-image">
                                        <img height="40px" width="40px"
                                            src="{{ asset('storage/' . $stock->product->photo) }}" alt="Profile image">
                                    </div>
                                </td>
                                <td>{{ $stock->name }}</td>
                                <td>{{ $stock->type }}</td>
                                <td>{{ $stock->quantity . ' ' . $stock->unit }}</td>

                                <td class="text-right">{{ number_format($stock->buying_price, 0, '.', ',') }} Tsh</td>
                                @php
                                    $totalBuyingPrice = $totalBuyingPrice + $stock->buying_price * $stock->quantity;
                                    $totalSellingPrice = $totalSellingPrice + $stock->product->selling_price * $stock->quantity;
                                @endphp
                                <td class="text-right">
                                    {{ number_format($stock->product->selling_price, 0, '.', ',') }}
                                    Tsh
                                </td>
                                <td class="">{{ $stock->updated_at->format('D, d M Y \a\t H:i:s') }} </td>

                                <td class="text-center">
                                    <form id="toggle-status-form-{{ $stock->product->id }}" method="POST"
                                        class="px-2" action="{{ route('products.toggle-discount', $stock->product) }}">
                                        <div class="form-check form-switch ">
                                            <input type="hidden" name="has_discount" value="0">
                                            <input type="checkbox" name="has_discount"
                                                id="status-switch-{{ $stock->product->id }}" class="form-check-input "
                                                @if ($stock->product->has_discount) checked @endif
                                                @if ($stock->trashed()) disabled @endif value="1"
                                                onclick="this.form.submit()" />
                                        </div>
                                        @csrf
                                        @method('PUT')
                                    </form>
                                <td>
                                <td class="text-center">
                                    <form id="toggle-status-form-{{ $stock->id }}" method="POST" class="px-2"
                                        action="{{ route('stocks.toggle-status', $stock) }}">
                                        <div class="form-check form-switch ">
                                            <input type="hidden" name="status" value="0">
                                            <input type="checkbox" name="status" id="status-switch-{{ $stock->id }}"
                                                class="form-check-input " @if ($stock->status) checked @endif
                                                @if ($stock->trashed()) disabled @endif value="1"
                                                onclick="this.form.submit()" />
                                        </div>
                                        @csrf
                                        @method('PUT')
                                    </form>
                                    <a href="{{ route('stocks.show', $stock) }}"
                                        class="btn btn-sm btn-outline-info collapsed mx-2" type="button">
                                        <i class="feather icon-edit"></i> View
                                    </a>
                                    <a href="#" class="btn btn-sm btn-outline-primary collapsed mx-2"
                                        type="button" data-bs-toggle="modal"
                                        data-bs-target="#editModal-{{ $stock->id }}" aria-expanded="false"
                                        aria-controls="collapseTwo">
                                        <i class="feather icon-edit"></i> Edit
                                    </a>
                                    <a href="#" class="btn btn-sm btn-outline-danger mx-2"
                                        onclick="if(confirm('Are you sure want to delete {{ $stock->name }}?')) document.getElementById('delete-stock-{{ $stock->id }}').submit()">
                                        <i class="f"></i>Delete
                                    </a>
                                    <form id="delete-stock-{{ $stock->id }}" method="post"
                                        action="{{ route('stocks.delete', $stock) }}">
                                        @csrf @method('delete')
                                    </form>

                                    <div class="modal modal-sm fade" id="editModal-{{ $stock->id }}" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Edit Product in
                                                        Stock
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="POST" action="{{ route('stocks.edit', $stock) }}">
                                                        @method('PUT')
                                                        @csrf
                                                        <div class="text-start mb-1">
                                                            <label for="name"
                                                                class=" col-form-label text-sm-start">{{ __('Name') }}</label>
                                                            <div class="input-group">
                                                                <input id="name" type="text" placeholder=""
                                                                    class="form-control @error('name') is-invalid @enderror"
                                                                    name="name"
                                                                    value="{{ old('name', $stock->name) }}" required
                                                                    autocomplete="name" autofocus style="float: left;">
                                                                <select class="form-control form-select" name="type"
                                                                    required
                                                                    style="float: left;max-width:115px; width: inaitial; background-color:rgb(238, 238, 242)">
                                                                    <option value="Water"
                                                                        {{ $stock->type == 'Water' ? 'selected' : '' }}>
                                                                        Water</option>
                                                                    <option value="Juice"
                                                                        {{ $stock->type == 'Juice' ? 'selected' : '' }}>
                                                                        Juice</option>
                                                                </select>
                                                                @error('name')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="text-start mb-1">
                                                            <label for="volume"
                                                                class=" col-form-label text-sm-start">{{ __('Volume') }}</label>
                                                            <div class="input-group">
                                                                <input id="volume" type="number" step="any"
                                                                    placeholder="00"
                                                                    class="form-control @error('volume') is-invalid @enderror"
                                                                    name="volume"
                                                                    value="{{ old('volume', $stock->volume) }}" required
                                                                    autocomplete="volume" autofocus style="float: left;">
                                                                <select class="form-control form-select" name="measure"
                                                                    required
                                                                    style="float: left;max-width:115px; width: inaitial; background-color:rgb(238, 238, 242)">
                                                                    <option value="Litres"
                                                                        {{ $stock->measure == 'Litres' ? 'selected' : '' }}>
                                                                        Litres</option>
                                                                    <option value="Millilitres"
                                                                        {{ $stock->measure == 'Millilitres' ? 'selected' : '' }}>
                                                                        Millilitres</option>
                                                                </select>
                                                                @error('volume')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="text-start mb-1">
                                                            <label for="quantity"
                                                                class=" col-form-label text-sm-start">{{ __('Quantity') }}</label>
                                                            <div class="input-group">
                                                                <input id="quantity" type="number" step="any"
                                                                    placeholder="00"
                                                                    class="form-control @error('quantity') is-invalid @enderror"
                                                                    name="quantity"
                                                                    value="{{ old('quantity', $stock->quantity) }}"
                                                                    required autocomplete="quantity" autofocus
                                                                    style="float: left;">
                                                                <select class="form-control form-select" name="unit"
                                                                    required
                                                                    style="float: left;max-width:115px; width: inaitial; background-color:rgb(238, 238, 242)">
                                                                    <option value="Bottles"
                                                                        {{ $stock->unit == 'Bottles' ? 'selected' : '' }}>
                                                                        Bottles</option>
                                                                    <option value="Cartons"
                                                                        {{ $stock->unit == 'Cartons' ? 'selected' : '' }}>
                                                                        Cartons</option>
                                                                </select>
                                                                @error('quantity')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="text-start mb-1">
                                                            <label for="selling_price"
                                                                class="col-form-label text-sm-start">{{ __('Buying Price') }}</label>
                                                            <input id="selling_price" type="number" placeholder="Tsh"
                                                                class="form-control @error('selling_price') is-invalid @enderror"
                                                                name="selling_price"
                                                                value="{{ old('selling_price', $stock->selling_price) }}"
                                                                required autocomplete="selling_price" autofocus>
                                                            @error('selling_price')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                        <div class="text-start mb-1">
                                                            <label for="price"
                                                                class="col-form-label text-sm-start">{{ __('Selling Price') }}</label>
                                                            <input id="price" type="number" placeholder="Tsh"
                                                                class="form-control @error('price') is-invalid @enderror"
                                                                name="price"
                                                                value="{{ old('price', $stock->product->selling_price) }}"
                                                                required autocomplete="price" autofocus>
                                                            @error('price')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                        <div class="row mb-1 mt-2">
                                                            <div class="text-center">
                                                                <button type="submit" class="btn btn-sm btn-primary">
                                                                    {{ __('Submit') }}
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                    <tr>
                        <td colspan="4"></td>
                        <td>
                            <h5>Total</h5>
                        </td>
                        <td class="text-right">
                            <h5>{{ number_format($totalBuyingPrice, 0, '.', ',') }} Tsh</h5>
                        </td>
                        <td class="text-right">
                            <h5>{{ number_format($totalSellingPrice, 0, '.', ',') }} Tsh</h5>
                        </td>
                    </tr>
                </table>

            </div>
        </div>
    @endsection
    @section('scripts')
    @endsection
