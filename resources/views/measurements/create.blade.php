
@extends('springs.layout')

@auth
@section('content')

    @include('layouts.spring-navigation')

    <div class="form-row">
        <h3>{{ __('springs.add_measurement') }}</h3>
    </div>
    @include('layouts.messages')

    <form action="{{ route('springs.measurements.store', $spring->id) }}" method="POST">
        @csrf
        <input type="hidden" name="spring_id" value="{{$spring->id}}" />
        <div class="form-row">
            <div class="col-xs-3 col-sm-3 col-md-3">
                <div class="form-group">
                    <label for="analysis_time"><strong>{{ __('springs.analysis_time') }}</strong></label>
                    <?php date_default_timezone_set('Europe/Tallinn');?>
                    <input type="datetime-local" name="analysis_time" id="analysis_time" class="form-control" value="{{old('analysis_time')?? date('Y-m-d\TH:i') }}">
                </div>
            </div>
        </div>
        <div class="form-row col-xs-12 col-sm-12 col-md-12">
        @foreach (\App\Models\MeasurementField::where('visible', 1)->orderBy('position')->get() as $field)
            @if($loop->iteration % 2 == 0)
                <div class="pull-right col-xs-6 col-sm-6 col-md-6">
            @else
                <div class="pull-left col-xs-6 col-sm-6 col-md-6">
            @endif
                <label for="{{$field->name}}"><strong>@lang('springs.measurement_fields.'.$field->name)</strong></label>
                <input type="{{$field->type}}" class="form-control" id="{{$field->name}}" name="measurement_values[{{$field->id}}]"/>
            </div>
        @endforeach
                </div>

        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <button type="submit" class="btn btn-primary">{{ __('springs.add') }}</button>
                </div>
                <div class="pull-right">
                <!--<a class="btn btn-primary" href="{{ route('springs.index') }}">{{ __('springs.Cancel') }}</a>-->
                </div>
            </div>
        </div>

    </form>
@endsection
@endauth
