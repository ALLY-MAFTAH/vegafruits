<form method="POST" action="{{ route('stocks.edit',$stock) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-sm-4 mb-1">
            <label for="name" class=" col-form-label text-sm-start">{{ __('Name') }}</label>
            <div class="input-group">
                <input id="name" type="text" placeholder=""
                    class="form-control @error('name') is-invalid @enderror" name="name"
                    value="{{ old('name', $stock->name) }}" required autocomplete="name" autofocus style="float: left;">
                <select class="form-control form-select" name="type" required
                    style="float: left;max-width:115px; width: inaitial; background-color:rgb(238, 238, 242)">
                    <option value="Matunda" {{ $stock->type == 'Matunda' ? 'selected' : '' }}>Matunda</option>
                    <option value="Mboga"  {{ $stock->type == 'Mboga' ? 'selected' : '' }}>Mboga</option>
                    <option value="Mizizi" {{ $stock->type == 'Mizizi' ? 'selected' : '' }}>Mizizi</option>
                    <option value="Nafaka" {{ $stock->type == 'Nafaka' ? 'selected' : '' }}>Nafaka</option>
                    <option value="Viungo" {{ $stock->type == 'Viungo' ? 'selected' : '' }}>Viungo</option>
                </select>
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="col-sm-4 mb-1">
            <label for="quantity" class=" col-form-label text-sm-start">{{ __('Stock Quantity') }}</label>
            <div class="input-group">
                <input id="quantity" type="number" step="any" placeholder="00"
                    class="form-control @error('quantity') is-invalid @enderror" name="quantity"
                    value="{{ old('quantity', $stock->quantity) }}" required autocomplete="quantity" autofocus
                    style="float: left;">
                <select class="form-control form-select" name="unit" required
                    style="float: left;max-width:115px; width: inaitial; background-color:rgb(238, 238, 242)">
                    <option value="Kilogram"{{ $stock->unit == 'Kilogram' ? 'selected' : '' }}>Kilogram</option>
                    <option value="Litre"{{ $stock->unit == 'Litre' ? 'selected' : '' }}>Litre</option>
                    <option value="Piece"{{ $stock->unit == 'Piece' ? 'selected' : '' }}>Piece</option>
                    <option value="Bottle"{{ $stock->unit == 'Bottle' ? 'selected' : '' }}>Bottle</option>
                    <option value="Packet"{{ $stock->unit == 'Packet' ? 'selected' : '' }}>Packet</option>
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
                    value="{{ old('photo') }}" autocomplete="photo" autofocus>
                <span>{{ $stock->product->photo }}</span>
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
            <label for="volume" class=" col-form-label text-sm-start">{{ __('Selling Quantity') }}</label>
            <div class="">
                <input id="volume" type="number" step="any" placeholder="00"
                    class="form-control @error('volume') is-invalid @enderror" name="volume"
                    value="{{ old('volume', $stock->product->volume) }}"required autocomplete="volume" autofocus>
                @error('volume')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="col-sm-4 mb-1">
            <label for="buying_price" class=" col-form-label text-sm-start">{{ __('Buying Price') }}</label>
            <div class="">
                <input id="buying_price" type="number" step="any" placeholder="Tsh"
                    class="form-control @error('buying_price') is-invalid @enderror" name="buying_price"
                    value="{{ old('buying_price', $stock->buying_price) }}"required autocomplete="buying_price"
                    autofocus>
                @error('buying_price')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="col-sm-4 mb-1">
            <label for="selling_price" class=" col-form-label text-sm-start">{{ __('Selling Price') }}</label>
            <div class="">
                <input id="selling_price" type="number" step="any" placeholder="Tsh"
                    class="form-control @error('selling_price') is-invalid @enderror" name="selling_price"
                    value="{{ old('selling_price', $stock->product->selling_price) }}"required
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
