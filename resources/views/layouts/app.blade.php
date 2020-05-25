<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css')}}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('DataTables/datatables.min.css')}}" />

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
</head>

<body>
    {{-- <div class="col-md-12 mt-1"></div> --}}
    <nav class="navbar navbar-expand-md fixed-top navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <h3>{{ config('app.name', 'Laravel') }}</h3>

            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->

                @isset($request->alert)
                <span class="alert alert-warning alert-dismissible" role="alert">
                    {{$request->alert}}
                </span>
                @endisset
                @if(Session::has('success'))
                <span class="alert alert-success alert-dismissible" role="alert">
                    {{Session::get('success')}}
                </span>
                @elseif(Session::has('error'))
                <span class="alert alert-warning alert-dismissible" role="alert">
                    {{Session::get('error')}}
                </span>
                @endif
                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    @if (Route::has('register'))
                    <!--<li class="nav-item">-->
                    <!--  <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>-->
                    <!--</li>-->
                    @endif
                    @else
                    @if (Auth::user()->admin)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('list-users') }}">{{ __('Users') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('list-projects') }}">{{ __('Projects') }}</a>
                    </li>
                    @endif
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                               document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4 mt-5">
        @yield('content')
    </main>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="{{ asset('js/app.js')}}"></script>
    <script src="{{ asset('js/jquery.min.js')}}"></script>
    <script src="{{ asset('js/main.js')}}"></script>
    <script type="text/javascript" src="{{asset('DataTables/datatables.min.js')}}"></script>

    <script>
        window.setTimeout(function () {
  $(".alert-dismissible").fadeTo(500, 0).slideUp(500, function () {
    $(this).remove();
  });
}, 5000);

$("#many").DataTable();
    </script>
</body>

</html>