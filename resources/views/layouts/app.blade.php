<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    {{-- DATA TABLE --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.4/css/buttons.bootstrap5.min.css">


    <!-- Scripts -->
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
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
                    <ul class="navbar-nav me-auto ">
                    </ul>
                    @guest
                        <div></div>
                    @else
                        <ul class="navbar-nav me-auto text-center ">

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('stocks.index') }}">{{ __('Stock') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('orders.index') }}">{{ __('Orders') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('customers.index') }}">{{ __('Customers') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('sales.index') }}">{{ __('Sales') }}</a>
                            </li>
                        </ul>
                    @endguest
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>

        </nav>

        <main class="py-4">
            <div class="container">

                @yield('content')
            </div>
        </main>
    </div>
    @yield('scripts')

    {{-- DATA TABLE --}}
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.0/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.2/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.colVis.min.js"></script>

    @yield('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('submit', 'form', function() {
                $('button').attr('disabled', 'disabled');

            });
        });
    </script>

    <script>
        $(document).ready(function() {
            var table = $('#data-tebo1').DataTable({
                // dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                //     "<'row'<'col-sm-12'tr>>" +
                //     "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                lengthChange: true,
                columnDefs: [{
                    visible: true,
                    targets: '_all'
                }, ],
                // buttons: [
                //     // {
                //     //     extend: 'print',
                //     //     exportOptions: {
                //     //         columns: ':visible'
                //     //     },
                //     //     messageTop: 'DATAAAAAAAAAA'

                //     // },
                //     {
                //         extend: 'pdf',
                //         exportOptions: {
                //             columns: ':visible'
                //         },
                //         margin: [20, 20, 20, 20],
                //         padding: [20, 20, 20, 20],
                //         customize: function(doc) {
                //             doc.content[1].table.widths = Array(doc.content[1].table.body[0]
                //                 .length + 1).join('*').split('');
                //             doc.content[1].table.widths[0] = 'auto';
                //         }
                //     },
                //     {
                //         extend: 'excel',
                //         exportOptions: {
                //             columns: ':visible'
                //         }
                //     },
                //     // {
                //     //     extend: 'csv',
                //     //     exportOptions: {
                //     //         columns: ':visible'
                //     //     }
                //     // },
                //     'colvis'
                // ]
            });
            table.buttons().container()
                .appendTo('#data-tebo1_wrapper .col-md-6:eq(0)');
        });
    </script>
    <script>
        $(document).ready(function() {
            var table = $('#data-tebo2').DataTable({
                // dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                //     "<'row'<'col-sm-12'tr>>" +
                //     "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                lengthChange: true,
                columnDefs: [{
                    visible: true,
                    targets: '_all'
                }, ],
                // buttons: [
                //     // {
                //     //         extend: 'print',
                //     //         exportOptions: {
                //     //             columns: ':visible'
                //     //         },
                //     //         messageTop: 'DATAAAAAAAAAA'

                //     //     },
                //     {
                //         extend: 'pdf',
                //         exportOptions: {
                //             columns: ':visible'
                //         },
                //         margin: [20, 20, 20, 20],
                //         padding: [20, 20, 20, 20],
                //         customize: function(doc) {
                //             doc.content[1].table.widths = Array(doc.content[1].table.body[0]
                //                 .length + 1).join('*').split('');
                //             doc.content[1].table.widths[0] = 'auto';
                //         }
                //     },
                //     {
                //         extend: 'excel',
                //         exportOptions: {
                //             columns: ':visible'
                //         }
                //     },
                //     // {
                //     //     extend: 'csv',
                //     //     exportOptions: {
                //     //         columns: ':visible'
                //     //     }
                //     // },
                //     'colvis'
                // ]
            });
            table.buttons().container()
                .appendTo('#data-tebo2_wrapper .col-md-6:eq(0)');

        });
    </script>
</body>

</html>
