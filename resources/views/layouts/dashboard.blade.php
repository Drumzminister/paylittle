<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PayLITTLE') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="{{ asset('js/custom.js') }}" defer></script>



    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,700,800" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sweetalert.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN"
        crossorigin="anonymous">
</head>
<body>

    <div id="app">
        <nav class="navbar navbar-expand-md bg-primary ">
            <div class="container">
                <a class="navbar-brand text-light" href="{{ url('/clientarea') }}">
                Pay Little | Client Area
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto my-2 dashboard">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item mx-3">
                                <a class="nav-link text-light" href="/">Home</a>
                            </li>
                            <li class="nav-item mx-3">
                                <a class="nav-link text-light" href="/about">About</a>
                            </li>
                            <li class="nav-item mx-3">
                                <a class="nav-link text-light" href="/contact">Contact</a>
                            </li>
                            {{-- <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif --}}
                        @else
                            {{-- <li class="nav-item mx-1">
                                <a class="nav-link text-light" href="/"><i class="fa fa-home" aria-hidden="true"></i></a>
                            </li> --}}
                            {{--<li class="nav-item mx-1 note">--}}
                                {{--<a class="nav-link text-light" href="#">--}}
                                    {{--<i class="fa fa-bell-o" aria-hidden="true"> </i> --}}
                                {{--</a>--}}
                                {{--<i class="badge bg-danger text-light">3</i>--}}
                            {{--</li>--}}
                            <li class="nav-item mx-1 note">
                                <a class="nav-link text-light" href="{{route('clientarea')}}">
                                    Dashboard
                                </a>
                            </li>

                            <li class="nav-item mx-1 note">
                                <a class="nav-link text-light" href="{{route('userProjects.all')}}">
                                    Projects
                                </a>
                            </li>

                            <li class="nav-item mx-1 note">
                                <a class="nav-link text-light" href="{{route('user.show', Auth::user()->id)}}">
                                  Account
                                </a>
                            </li>

                            <li class="nav-item mx-1 note">
                                <a class="nav-link text-light" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="">
            <div class="container-fluid my-4">
                {{-- Flash Notifications --}}
                <div class="row">
                    <div class="col-12">
                        <div class="container my-4">
                            <div class="row justify-content-center">
                                <div class="col-md-8 mx-auto text-center">
                                    @if ($user->email_verified_at !== null)
                                        <script>
                                             swal("Your account is not verified!","", "info",{
                                                buttons: ["Verify", "Cancel"],
                                             });
                                        </script>
                                    @endif

                                    @if (\Session::has('success'))
                                        <script>
                                            swal("{{ \Session::get('success') }}","", "success");
                                        </script>
                                    @endif
                                    @if (\Session::has('error'))
                                        <script>
                                            swal("{{ \Session::get('error') }}","", "error");

                                        </script>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- App Layout --}}
                <div class="row">
                    <div class="col-md-3 col-12  mx-auto">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 text-center mb-5">
                                    <img src="{{asset('storage/avatars/users/' . $user->avatar)}}" alt="" class="profile-img p-1 img-fluid ">
                                </div>
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title mb-5 text-center">{{$user->first_name}} {{$user->last_name}}</h5>
                                            <p>
                                                <i class="fa fa-cog" aria-hidden="true"></i>
                                                &nbsp; &nbsp;
                                                Sponsored Projects: {{$user->sponsoredProjects->count() ?? 0}}
                                            </p>
                                            <p>
                                                <i class="fa fa-cogs" aria-hidden="true"></i>
                                                &nbsp; &nbsp;
                                                Your Projects: {{$user->projects->count() ?? 0}}
                                            </p>
                                            <p>
                                                <i class="fa fa-envelope" aria-hidden="true"></i>
                                                &nbsp; &nbsp;
                                                Account not verified
                                            </p>
                                            <p>
                                                <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                                                &nbsp; &nbsp;
                                                Joined {{$user->created_at->diffForHumans()}}
                                            </p>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-9 col-12">
                        @yield('content')
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
