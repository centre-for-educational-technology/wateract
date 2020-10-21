@extends('springs.layout')

@section('content')

    <div class="row">
        <div class="pull-left col-lg-9 margin-tb">
            @isset($spring->title)
                <h2>{{$spring->title}}</h2>
            @else
                <h2>{{ __('springs.unnamed') }}</h2>
            @endisset
        </div>
        <div class="pull-right col-lg-3 margin-tb">
            <a class="btn btn-primary" href="{{ route('springs.observations.create',$spring->id) }}">{{ __('springs.add_observation') }}</a>
        </div>

    </div>

    <nav class="navbar">
        <div class="navbar-nav">
            <a class="nav-item nav-link active" href="{{ route('springs.show',$spring->id) }}">{{ __('springs.view') }}</a>
            <span class="nav-item nav-link active">{{ __('springs.observations') }}</span>
            <a class="nav-item nav-link" href="{{$spring->id}}/measurements">{{ __('springs.measurements') }}</a>
        </div>
    </nav>


    @if(count($spring->observations)>0)
            @foreach ($spring->observations as $observation)
                <div>
                    <div><a class="" href="{{ route('springs.observations.edit',[$spring->id, $observation->id]) }}">
                            {{ $observation->measurement_time }}</a></div>
                    @include('observations.show')
                </div>
            @endforeach
    @endif

@endsection
