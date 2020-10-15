<!DOCTYPE html>
<html>
    <head>
        <title>{{ __('springs.wateract') }}</title>
        <!--<link href="{{ asset('css/app.css') }}" rel="stylesheet">-->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/css/bootstrap.css" rel="stylesheet">
        @yield('scripts')
    </head>
    <body>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h1><a href="/">{{__('springs.wateract')}}</a></h1>
                </div>
                <div class="pull-right">
        @if (Route::has('login'))
            <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                @auth
                    <a href="{{ url('/logout') }}" class="text-sm text-gray-700 underline">Logout</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Login</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">Register</a>
                    @endif
                @endif
            </div>
        @endif
        </div>
            </div>
        </div>
    </div>
        <div class="container">
            @yield('content')
        </div>
    </body>
</html>
