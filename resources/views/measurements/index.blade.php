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
            <a class="btn btn-primary" href="{{ route('springs.measurements.create',$spring->id) }}">{{ __('springs.add_measurement') }}</a>
        </div>
        @endauth

    </div>

    @include('layouts.spring-navigation')


    @if(count($spring->measurements)>0)
        @foreach ($spring->measurements as $measurement)
            <div>
                @auth
                <div><a class="" href="{{ route('springs.measurements.edit',[$spring->id, $measurement->id]) }}">
                        {{ $measurement->analysis_time }}</a></div>
                @else
                    <div><label><strong>{{ $measurement->analysis_time }}</strong></label></div>
                @endauth

                @include('measurements.show')
            </div>
        @endforeach
    @endif

@endsection
