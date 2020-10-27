@extends('springs.layout')

@auth
@section('content')

    @include('layouts.spring-navigation', ['spring' => $measurement->spring])

    <div class="form-row">
        <h3>{{ __('springs.edit_measurement') }}</h3>
    </div>

    @include('layouts.messages')

    <form action="{{ route('measurements.update', $measurement->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-row">
            <div class="col-xs-3 col-sm-3 col-md-3">
                <div class="form-group">
                    <strong>{{ __('springs.analysis_time') }}</strong>
                    <input type="datetime-local" name="analysis_time" class="form-control" value="{{old('analysis_time')?? date('Y-m-d\TH:i', strtotime($measurement->analysis_time)) }}" >
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
                                <input type="{{$field->type}}" class="form-control" id="{{$field->name}}" name="measurement_values[{{$field->id}}]" value="{{$field->field_value($measurement->id)}}"/>
                            </div>
                            @endforeach
                    </div>

        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <button type="submit" class="btn btn-primary">{{ __('springs.edit') }}</button>
                </div>
                <div class="pull-right">
                <!--<a class="btn btn-primary" href="{{ route('springs.index') }}">{{ __('springs.Cancel') }}</a>-->
                </div>
            </div>
        </div>

    </form>

@endsection
@endauth
