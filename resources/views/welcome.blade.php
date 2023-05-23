<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ 'Place Your Order | ' . config('app.name', 'VegaFruits') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/smoothness/jquery-ui.css">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <script src="https://use.fontawesome.com/3076bdb328.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>

    <!-- Scripts -->
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="" href="{{ url('/') }}">
                    <img src="{{ asset('assets/images/logo.png') }}" height="40px" alt="">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">

                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/') }}">{{ __('Bidhaa Zote') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/') }}">{{ __('Matunda') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/') }}">{{ __('Mboga') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/') }}">{{ __('Viungo') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/') }}">{{ __('Nafaka') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/') }}">{{ __('Mizizi') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-2 container">
            <div class="search-bar text-center">
                <form action="#" method="post">
                    @csrf
                    <div class="input-group  control-group" style="margin-bottom:10px">
                        <input id="category_id" class="input-field form-control " name="category_id" required
                            style="float: left; width: inaitial; " placeholder="Search">
                        <span class="input-group-btn">
                            <a href="#" class="btn btn-warning "
                                type="button"style="border-top-left-radius: 0%;border-bottom-left-radius: 0%"><i
                                    class="fa fa-search"></i></a>
                        </span>
                    </div>
                </form>
            </div>

            <div class="products-container">
                @foreach ($stocks as $stock)
                    <div class="card shadow">
                        <div class="card-body text-center">
                            <img src="{{ asset('storage/' . $stock->product->photo) }}" height="150px" width="150px"
                                alt="">
                            <div>{{ $stock->name }}</div>
                            <h5 class="text-info"><b>{{ $stock->product->volume }}</b> {{ $stock->unit }}</h5>
                            <h5 class="text-success">
                                <b>{{ number_format($stock->product->selling_price, 0, '.', ',') }} Tsh</b>
                            </h5>
                            <button class="btn btn-outline-warning add-to-cart"
                                id="add-to-cart-{{ $stock->product->id }}" data-product-id="{{ $stock->product->id }}"
                                data-product-name="{{ $stock->name }}"
                                data-product-price="{{ $stock->product->selling_price }}"
                                data-product-quantity="{{ $stock->product->volume }}"
                                data-product-volume="{{ $stock->product->volume }}"
                                data-product-remainedQuantity="{{ $stock->quantity }}">
                                <i class="fa fa-shopping-cart fa-lg"></i> Add to Cart
                            </button>
                        </div>
                    </div>
                @endforeach

            </div>
        </main>
        <footer>
            <div class="row pt-2 text-center">
                <div class="col text-center">
                    <button class="btn btn-light">Contacts</button>
                </div>
                <div class="col text-center">
                    <button data-bs-toggle="modal" data-bs-target="#cartModal" style="position: relative; width: 150px;"
                        class="btn btn-warning">
                        <div>
                            <i class="fa fa-shopping-cart fa-lg"></i>
                            <span class="cart-quantity"
                                style="position: absolute; top: -10px; left: -10px; background-color: red; color: white; border-radius: 50%; padding: 2px 10px;">0</span>
                            <b class="cart-price text-white">0</b> Tsh
                        </div>
                    </button>

                </div>
                <div class="col text-center">
                    <button class="btn btn-light">About Us</button>
                </div>

            </div>
        </footer>
    </div>
    <div class="modal modal fade" id="cartModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title text-center" id="exampleModalLabel">
                        <div class="text-center">
                            <div style="position: relative; width: 150px;" class="btn btn-warning">
                                <div>
                                    <i class="fa fa-shopping-cart fa-lg"></i>
                                    <span class="cart-quantity"
                                        style="position: absolute; top: -10px; left: -10px; background-color: red; color: white; border-radius: 50%; padding: 2px 10px;">0</span>
                                    <b class="text-dark">Your Cart</b>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="cart-items">

                        {{-- TABLE SCRIPTS LOAD HERE --}}

                    </div>
                    <div class="row mb-1 mt-2">
                        <div class="text-center">
                            <button data-bs-toggle="modal" data-bs-target="#cartModal" style=""
                                class="shadow btn btn-white">
                                <h5 style="font-weight:bold;"class=" text-success">
                                    <span>Grand Total: </span>&nbsp;&nbsp;
                                    <span class="cart-price text-success">0</span>&nbsp; Tsh
                                </h5>
                            </button>
                        </div>
                    </div>
                    <div class="row mb-1 mt-2">
                        <div class="text-center">
                            <a href="#" class="btn btn-danger empty-cart-btn">{{ __('Empty Cart') }}</a>
                            &nbsp;&nbsp;&nbsp;
                            <a href="#" class="btn checkout-btn"
                                style="color:white;background: rgb(3, 174, 3)">{{ __('Checkout') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPTS --}}

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {
            var quantity = 0;
            var price = 0;
            $.get("/cart/get-cart", function(data) {
                console.log("Initial ni:");
                console.log(data);
                var cartItems = data.cart;
                var quantity = data.count;
                var price = data.cartAmount.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                $(".cart-quantity").text(quantity);
                $(".cart-price").text(price);

                var cartItemsHtml = generateCartTable(cartItems)
                $('#cart-items').html(cartItemsHtml);
            });
        });

        $(document).on('click', '.add-to-cart', function() {
            var productId = $(this).data('product-id');
            var productName = $(this).data('product-name');
            var productPrice = parseFloat($(this).data('product-price'));
            var productQuantity = parseFloat($(this).data('product-quantity'));
            var productVolume = parseFloat($(this).data('product-volume'));

            $.ajax({
                type: 'POST',
                url: '/cart/add',
                data: {
                    product_id: productId,
                    product_name: productName,
                    product_price: productPrice,
                    quantity: productQuantity,
                    volume: productVolume,
                },
                success: function(response) {
                    if (response.success) {
                        var quantity = response.totalQuantity;
                        var price = response.totalPrice.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g,
                        ",");
                        $(".cart-quantity").text(quantity);
                        $(".cart-price").text(price);
                        var cartItems = response.cart;
                        var cartItemsHtml = generateCartTable(cartItems);
                        $('#cart-items').html(cartItemsHtml);
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error:', error);
                }
            });

        });

        $(document).on('click', '.remove-item', function() {
            var productId = $(this).data('product-id');

            $.ajax({
                type: 'POST',
                url: '/cart/remove',
                data: {
                    product_id: productId,
                },
                success: function(response) {
                    var quantity = response.totalQuantity;
                    var price = response.totalPrice.toFixed(0).replace(
                        /\B(?=(\d{3})+(?!\d))/g, ",");

                    console.log(price);
                    console.log(quantity);
                    $(".cart-quantity").text(quantity);
                    $(".cart-price").text(price);
                    var cartItems = response.cart;
                    var cartTableHtml = generateCartTable(cartItems);
                    $('#cart-items').html(cartTableHtml);
                }
            });
        });

        $(document).on('click', '.empty-cart-btn', function(e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: '{{ route('cart.empty') }}',
                success: function(response) {
                    var quantity = response.totalQuantity;
                    var price = response.totalPrice;

                    console.log(price);
                    console.log(quantity);
                    $(".cart-quantity").text(quantity);
                    $(".cart-price").text(price);
                    var cartItems = response.cart;
                    var cartTableHtml = generateCartTable(cartItems);
                    $('#cart-items').html(cartTableHtml);

                }
            });
        });

        $(document).on('click', '.increment', function(e) {
            e.preventDefault();
            var input = $(this).closest('.number-input').find('input');
            var value = parseFloat(input.val());
            var max = parseFloat(input.attr('max'));
            console.log(value);
            console.log(max);
            if (isNaN(max) && value >= max) {
                return;
            }
            input.trigger('change');
        });

        $(document).on('click', '.decrement', function(e) {
            e.preventDefault();
            var input = $(this).closest('.number-input').find('input');
            var value = parseFloat(input.val());
            var min = parseFloat(input.attr('min'));
            console.log(value);
            console.log(min);
            if (isNaN(min) && value <= min) {
                return;
            }
            input.trigger('change');
        });


        $(document).on('change', '.update-cart-quantity', function(e) {

            var input = $(this);
            var productId = input.data('product-id');
            var newQuantity = parseFloat(input.val());
            var minQuantity = parseFloat(input.attr('min'));
            var maxQuantity = parseFloat(input.attr('max'));

            if (parseFloat(newQuantity) < parseFloat(minQuantity)) {
                alert('The minimum quantity is ' + minQuantity);
                newQuantity = minQuantity;
                input.val(newQuantity);
            }

            if (parseFloat(newQuantity) > parseFloat(maxQuantity)) {
                alert('Sorry, the remained quantity in stock is ' + maxQuantity);
                newQuantity = maxQuantity;
                input.val(newQuantity);
            }

            var url = '{{ route('cart.update') }}';
            var token = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: token,
                    product_id: productId,
                    quantity: newQuantity,
                },
                success: function(response) {
                    var quantity = response.totalQuantity;
                    var price = response.totalPrice.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    $('.cart-quantity').text(quantity);
                    $('.cart-price').text(price);
                    var cartItems = response.cart;

                    var cartTableHtml = generateCartTable(cartItems);
                    $('#cart-items').html(cartTableHtml);
                },
                error: function(response) {
                    console.log('Error:', response);
                },
            });
        });


        $(document).on('click', '.checkout-btn', function(e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: '{{ route('cart.checkout') }}',
                success: function(response) {
                    // Handle success response here
                }
            });
        });

        function generateCartTable(cartItems) {
            ourCart = cartItems;
            var cartTableHtml = '<table class="table">';
            cartTableHtml +=
                '<thead><tr><th>Name</th><th>Price</th><th>Quantity</th><th class="text-end">Subtotal</th><th></th></tr></thead>';
            cartTableHtml += '<tbody>';

            for (var cartItem in cartItems) {

                var total = (cartItems[cartItem].price * cartItems[cartItem].quantity) / cartItems[cartItem].volume;
                cartTableHtml += '<tr>';
                cartTableHtml += '<td>' + cartItems[cartItem].name + '</td>';
                cartTableHtml += '<td>' + cartItems[cartItem].price.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",") +
                    '</td>';
                cartTableHtml += '<td><div class="number-input">';
                cartTableHtml +=
                    '<input id="quantity-input" type="number" class="form-control update-cart-quantity" min="' +
                    cartItems[
                        cartItem].volume + '" max="' + cartItems[cartItem].remainedQuantity + '" value="' + cartItems[
                        cartItem].quantity + '" data-product-id="' +
                    cartItem +
                    '">';
                cartTableHtml += '<div class="spinners">';
                cartTableHtml += '<button class="spinner increment">&#9650;</button>';
                cartTableHtml += '<button class="spinner decrement">&#9660;</button>';
                cartTableHtml += '</div></div></td>';
                cartTableHtml += '<td class="text-end">' + total.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",") +
                    '</td>';
                cartTableHtml += '<td><a type="button" class="btn-danger remove-item" data-product-id="' + cartItem +
                    '"><i class="fa fa-trash " style="color:red"></i></a></td>';
                cartTableHtml += '</tr>';

            }

            cartTableHtml += '</tbody></table>';

            var cartTable = $(cartTableHtml);

            // Attach event listeners to increment and decrement buttons
            var incrementButtons = cartTable.find('.increment');
            var decrementButtons = cartTable.find('.decrement');

            incrementButtons.on('click', function() {
                var input = $(this).closest('.number-input').find('.update-cart-quantity');
                var currentValue = parseFloat(input.val());
                if (currentValue <= parseFloat(input.attr('max'))) {
                    input.val(currentValue + 1);
                }
            });
            decrementButtons.on('click', function() {
                var input = $(this).closest('.number-input').find('.update-cart-quantity');
                var currentValue = parseFloat(input.val());
                if (currentValue >= parseFloat(input.attr('min'))) {
                    input.val(currentValue - 1);
                }
            });

            return cartTable;
        }
    </script>
</body>

</html>
