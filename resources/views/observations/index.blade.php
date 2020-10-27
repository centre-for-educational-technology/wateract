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
        @auth
            <div class="pull-right col-lg-3 margin-tb">
                <a class="btn btn-primary" href="{{ route('springs.observations.create',$spring->id) }}">{{ __('springs.add_observation') }}</a>
            </div>
        @endauth

    </div>

    @include('layouts.spring-navigation')

    @if(count($spring->observations)>0)
            @foreach ($spring->observations as $observation)
                <div>
                    @auth
                    <div><a class="" href="{{ route('springs.observations.edit',[$spring->id, $observation->id]) }}">
                            {{ $observation->measurement_time }}</a></div>
                    @else
                        <div><label><strong>{{ $observation->measurement_time }}</strong></label></div>
                    @endauth
                    @include('observations.show')
                </div>
            @endforeach
    @endif

@endsection
