@extends('springs.layout')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>WaterAct</h2>
            </div>
            <div class="pull-right">
                @auth
                    <a class="btn btn-success" href="{{ route('springs.create') }}">{{__('springs.add_new_spring')}}</a>
                @endauth
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

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

    <table class="table table-bordered">
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($springs as $spring)
            <tr>
                <td>{{ $spring->title }}</td>
                <td>{{ $spring->description }}</td>
                <td>
                    @auth
                        <form action="{{ route('springs.destroy',$spring->id) }}" method="POST">
                            <a class="btn btn-info" href="{{ route('springs.show',$spring->id) }}">Show</a>
                            <a class="btn btn-primary" href="{{ route('springs.edit',$spring->id) }}">Edit</a>
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    @else
                        <a class="btn btn-info" href="{{ route('springs.show',$spring->id) }}">Show</a>
                    @endauth
                </td>
            </tr>
        @endforeach
    </table>

@endsection
