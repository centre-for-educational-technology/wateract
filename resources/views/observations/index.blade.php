@extends('springs.layout')

@section('content')

    @include('layouts.spring-navigation')

    @auth
        <div class="row">
            <div class="pull-right col-lg-3 margin-tb">
                <a class="btn btn-primary" href="{{ route('springs.observations.create',$spring->id) }}">{{ __('springs.add_observation') }}</a>
            </div>
        </div>
    @endauth

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
