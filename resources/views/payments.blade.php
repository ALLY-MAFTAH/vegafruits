<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.9">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ 'Place Your Order | ' . config('app.name', 'VegaFruits') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/smoothness/jquery-ui.css">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css"> --}}
    <style>
        .ui-dialog .ui-dialog-title {
            text-align: center;
            color: rgb(62, 2, 93);
        }

        hr {
            margin-top: 5px;
            margin-bottom: 5px;
        }

        .dialog-open {

            opacity: 0.1;

        }
    </style>
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

        </main>
        <footer>
            <div class="row pt-2 text-center">
                <div class="col text-center">
                    <button id="contact-open-dialog" class="btn btn-light">Contacts</button>
                </div>
                <div class="col text-center">
                    <button data-bs-toggle="modal" id="cart-modal" data-bs-target="#cartModal"
                        style="position: relative; width: 150px;" class="btn btn-warning">
                        <div>
                            <i class="fa fa-shopping-cart fa-lg"></i>
                            <span class="cart-quantity"
                                style="position: absolute; top: -10px; left: -10px; background-color: red; color: white; border-radius: 50%; padding: 2px 10px;">0</span>
                            <b class="cart-price text-white">0</b> Tsh
                        </div>
                    </button>
                </div>
                <div class="col text-center">
                    <button id="about-open-dialog" class="btn btn-light">About Us</button>
                </div>
            </div>
        </footer>
    </div>


    {{-- SCRIPTS --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
    <script src="https://use.fontawesome.com/3076bdb328.js"></script>

    <script>
        $(document).ready(function() {

            @if (session('verifyOTPDialog'))
                var otpDialogContainer = $("#otp-dialog-container");
                var otpDialog = $("#otp-dialog").dialog({
                    autoOpen: false,
                    modal: true,
                    width: "auto",
                    show: {
                        effect: "fade",
                        duration: 400
                    },
                    hide: {
                        effect: "fade",
                        duration: 400
                    },
                    open: function(event, ui) {
                        $(this).parent().find(".ui-dialog-titlebar-close").hide();
                        $("main").addClass("dialog-open");
                    },
                    buttons: [{
                        text: "Close",
                        class: "btn btn-sm btn-outline-warning m-0",
                        click: function() {
                            closeDialog(otpDialogContainer, otpDialog);
                            $("main").removeClass("dialog-open");
                        }
                    }],
                    position: {
                        my: "center top",
                        at: "center top+50",
                        of: window
                    }
                });
                $("#otp-dialog").dialog('open');
            @endif
        });
    </script>

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
                $(".total-cart-amount").text(data.cartAmount);

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

            var img = $(this).closest('.card-body').find('img').clone();
            img.css({
                'position': 'absolute',
                'z-index': '1000',
                'top': $(this).closest('.card-body').offset().top,
                'left': $(this).closest('.card-body').offset().left,
                'width': $(this).closest('.card-body').outerWidth(),
                'height': $(this).closest('.card-body').outerHeight()
            });
            $('body').append(img);
            img.animate({
                top: $(".cart-price").offset().top,
                left: $(".cart-price").offset().left,
                width: 20,
                height: 20
            }, 1000, function() {
                img.remove();
            });

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
                        var price = response.totalPrice.toFixed(0).replace(
                            /\B(?=(\d{3})+(?!\d))/g,
                            ",");
                        $(".cart-quantity").text(quantity);
                        $(".cart-price").text(price);
                        $(".total-cart-amount").text(response.totalPrice);

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
                    $(".total-cart-amount").text(response.totalPrice);

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
                    $(".total-cart-amount").text(response.totalPrice);

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
                    var price = response.totalPrice.toFixed(0).replace(
                        /\B(?=(\d{3})+(?!\d))/g, ",");
                    $('.cart-quantity').text(quantity);
                    $('.cart-price').text(price);
                    $(".total-cart-amount").text(response.totalPrice);

                    var cartItems = response.cart;

                    var cartTableHtml = generateCartTable(cartItems);
                    $('#cart-items').html(cartTableHtml);
                },
                error: function(response) {
                    console.log('Error:', response);
                },
            });
        });

        $(document).on('click', '#cart-modal', function(e) {
            e.preventDefault();
            $("#about-dialog").dialog('close');
            $("#otp-dialog").dialog('close');
            $("#contact-dialog").dialog('close');
            $('#checkOutModal').modal('hide');
        });
        $(document).on('click', '.checkout-btn', function(e) {
            e.preventDefault();

            var cartAmount = parseFloat($(".total-cart-amount").text());
            console.log(cartAmount);
            if (cartAmount == 0) {
                alert("There is no product in your cart!");
            } else {
                if (cartAmount < 50000) {
                    alert("Delivery cost for an order with less than 50,000 Tsh will be held by the customer.");
                }

                // Open the modal
                $('#cartModal').modal('hide');
                $("#about-dialog").dialog('close');
                $("#otp-dialog").dialog('close');
                $("#contact-dialog").dialog('close');
                $('#checkOutModal').modal('show');

            }
        });

        function generateCartTable(cartItems) {
            ourCart = cartItems;
            var cartTableHtml = '<table class="table">';
            cartTableHtml +=
                '<thead><tr><th>Name</th><th>Price</th><th>Quantity</th><th class="text-end">Subtotal</th><th></th></tr></thead>';
            cartTableHtml += '<tbody>';

            for (var cartItem in cartItems) {

                var total = (cartItems[cartItem].price * cartItems[cartItem].quantity) / cartItems[cartItem]
                    .volume;
                cartTableHtml += '<tr>';
                cartTableHtml += '<td>' + cartItems[cartItem].name + '</td>';
                cartTableHtml += '<td>' + cartItems[cartItem].price.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g,
                        ",") +
                    '</td>';
                cartTableHtml += '<td><div class="number-input">';
                cartTableHtml +=
                    '<input id="quantity-input" type="number" class="form-control update-cart-quantity" min="' +
                    cartItems[
                        cartItem].volume + '" max="' + cartItems[cartItem].remainedQuantity + '" value="' +
                    cartItems[
                        cartItem].quantity + '" data-product-id="' +
                    cartItem +
                    '">';
                cartTableHtml += '<div class="spinners">';
                cartTableHtml += '<button class="spinner increment">&#9650;</button>';
                cartTableHtml += '<button class="spinner decrement">&#9660;</button>';
                cartTableHtml += '</div></div></td>';
                cartTableHtml += '<td class="text-end">' + total.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g,
                        ",") +
                    '</td>';
                cartTableHtml += '<td><a type="button" class="btn-danger remove-item" data-product-id="' +
                    cartItem +
                    '"><i class="fa fa-trash " style="font-size:19px;color:red"></i></a></td>';
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

    <script>
        $(document).ready(function() {
            var openDialog = function(dialogContainer, dialog) {
                dialogContainer.show("fade", 400);
                dialog.dialog("open");
            };

            var closeDialog = function(dialogContainer, dialog) {
                dialog.dialog("close");
                dialogContainer.hide("fade", 400);
            };

            var aboutDialogContainer = $("#about-dialog-container");
            var aboutDialog = $("#about-dialog").dialog({
                autoOpen: false,
                modal: true,
                width: "auto",
                show: {
                    effect: "fade",
                    duration: 400
                },
                hide: {
                    effect: "fade",
                    duration: 400
                },
                open: function(event, ui) {
                    $(this).parent().find(".ui-dialog-titlebar-close").hide();
                    $("main").addClass("dialog-open");
                },

                buttons: [{
                    text: "Close",
                    class: "btn btn-sm btn-outline-warning m-0",
                    click: function() {
                        closeDialog(aboutDialogContainer, aboutDialog);
                        $("main").removeClass("dialog-open");
                    }
                }],
                position: {
                    my: "center top",
                    at: "center top+50",
                    of: window
                }
            });

            var contactDialogContainer = $("#contact-dialog-container");
            var contactDialog = $("#contact-dialog").dialog({
                autoOpen: false,
                modal: true,
                width: "auto",
                show: {
                    effect: "fade",
                    duration: 400
                },
                hide: {
                    effect: "fade",
                    duration: 400
                },
                open: function(event, ui) {
                    $(this).parent().find(".ui-dialog-titlebar-close").hide();
                    $("main").addClass("dialog-open");
                },

                buttons: [{
                    text: "Close",
                    class: "btn btn-sm btn-outline-warning m-0",
                    click: function() {
                        closeDialog(contactDialogContainer, contactDialog);
                        $("main").removeClass("dialog-open");
                    }
                }],
                position: {
                    my: "center top",
                    at: "center top+50",
                    of: window
                }
            });
            var otpDialogContainer = $("#otp-dialog-container");
            var otpDialog = $("#otp-dialog").dialog({
                autoOpen: false,
                modal: true,
                width: "auto",
                show: {
                    effect: "fade",
                    duration: 400
                },
                hide: {
                    effect: "fade",
                    duration: 400
                },
                open: function(event, ui) {
                    $(this).parent().find(".ui-dialog-titlebar-close").hide();
                    $("main").addClass("dialog-open");
                },

                buttons: [{
                    text: "Close",
                    class: "btn btn-sm btn-outline-warning m-0",
                    click: function() {
                        closeDialog(otpDialogContainer, otpDialog);
                        $("main").removeClass("dialog-open");
                    }
                }],
                position: {
                    my: "center top",
                    at: "center top+50",
                    of: window
                }
            });



            var sendMessageResponse = $('#otp-dialog-container').data('send-message-response');
            if (sendMessageResponse === "Sent") {
                closeDialog(aboutDialogContainer, aboutDialog);
                closeDialog(contactDialogContainer, contactDialog);
                openDialog(otpDialogContainer, otpDialog);
            }

            $("#about-open-dialog").on("click", function() {
                closeDialog(contactDialogContainer, contactDialog);
                closeDialog(otpDialogContainer, otpDialog);
                openDialog(aboutDialogContainer, aboutDialog);
            });

            $("#contact-open-dialog").on("click", function() {
                closeDialog(aboutDialogContainer, aboutDialog);
                closeDialog(otpDialogContainer, otpDialog);
                openDialog(contactDialogContainer, contactDialog);
            });

        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function(event) {
            function OTPInput() {
                const inputs = document.querySelectorAll('#otp > *[id]');
                for (let i = 0; i < inputs.length; i++) {
                    inputs[i].addEventListener('input', function(event) {
                        if (event.inputType === "deleteContentBackward") {
                            inputs[i].value = '';
                            if (i !== 0) inputs[i - 1].focus();
                        } else {
                            if (i === inputs.length - 1 && inputs[i].value !== '') {
                                return true;
                            } else if (/^[0-9A-Za-z]$/.test(inputs[i].value)) {
                                if (i !== inputs.length - 1) inputs[i + 1].focus();
                            } else {
                                inputs[i].value = '';
                            }
                        }
                    });
                }
            }
            OTPInput();
        });
    </script>


</body>

</html>
