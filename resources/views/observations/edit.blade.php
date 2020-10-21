
@extends('springs.layout')

@auth
@section('content')

    <div class="row">
        <div class="pull-left col-lg-9 margin-tb">
            <h2><a href="{{ route('springs.show',$observation->spring_id) }}">
                    @isset($spring->title)
                        {{$spring->title}}
                    @else
                        {{ __('springs.unnamed') }}
                    @endisset
                </a></h2>
        </div>
    </div>
    <div class="form-row">
        <h3>{{ __('springs.edit_observation') }}</h3>
    </div>

    @include('layouts.messages')

    <form action="{{ route('observations.update', $observation->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-row">
            <div class="col-xs-3 col-sm-3 col-md-3">
                <div class="form-group">
                    <strong>{{ __('springs.measurement_time') }}</strong>
                    <input type="datetime-local" name="measurement_time" class="form-control" value="{{old('time')?? date('Y-m-d\TH:i', strtotime($observation->measurement_time)) }}" aria-describedby="measurement_time_help_block">
                    <small id="measurement_time_help_block" class="form-text text-muted">
                        {{ __('springs.measurement_time_help_text') }}
                    </small>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <label><strong>{{ __('springs.odor') }}</strong></label>
                    <input type="text" name="odor" class="form-control" placeholder="" value="{{$observation->odor}}" aria-describedby="odor_help_block">
                    <small id="odor_help_block" class="form-text text-muted">
                        {{ __('springs.odor_help_text') }}
                    </small>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <strong>{{ __('springs.taste') }}</strong>

                    <select class="form-control" name="status" aria-describedby="taste_help_block">
                        <?php $lists = ['Tastes fine, no complaints', 'Tastes "flat" - noticeable lack of taste', 'Metallic, like iron or rust', 'Earthy or moldy', 'Smells and tastes like rotten eggs', 'Tastes salty']; ?>
                        @foreach($lists as $list)
                            <option value={{$list}}>{{$list}}</option>
                        @endforeach
                    </select>
                    <small id="taste_help_block" class="form-text text-muted">
                        {{ __('springs.taste_help_text') }}
                    </small>

                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ __('springs.color_and_turbidity') }}</strong>
                    <input type="text" name="color" class="form-control" placeholder="" value="{{$observation->color}}" aria-describedby="color_help_block">
                    <small id="color_help_block" class="form-text text-muted">
                        {{ __('springs.color_and_turbidity_help_text') }}
                    </small>
                </div>
            </div>
        </div>

        <div class="form-row col-xs-12 col-sm-12 col-md-12">
            <div class="pull-left col-xs-6 col-sm-6 col-md-6">
                <strong>{{ __('springs.water_temperature') }}</strong>
                <input type="number" step=".1" name="water_temperature" class="form-control" placeholder="0-30C" value="{{$observation->water_temperature}}" aria-describedby="water_temperature_help_block">
                <small id="water_temperature_help_block" class="form-text text-muted">
                    {{ __('springs.water_temperature_help_text') }}
                </small>
            </div>
            <div class="pull-right col-xs-6 col-sm-6 col-md-6">
                <strong>{{ __('springs.air_temperature') }}</strong>
                <input type="number" step=".1" name="air_temperature" class="form-control" placeholder="-30 - +45C" value="{{$observation->air_temperature}}" aria-describedby="air_temperature_help_block">
                <small id="air_temperature_help_block" class="form-text text-muted">
                    {{ __('springs.air_temperature_help_text') }}
                </small>
            </div>
        </div>

        <div class="form-row col-xs-12 col-sm-12 col-md-12">
            <div class="pull-left col-xs-6 col-sm-6 col-md-6">
                <strong>{{ __('springs.pH') }}</strong>
                <input type="number" name="ph" class="form-control" placeholder="0-5000" value="{{$observation->ph}}" aria-describedby="ph_help_block">
                <small id="ph_help_block" class="form-text text-muted">
                    {{ __('springs.ph_help_text') }}
                </small>
            </div>
            <div class="pull-right col-xs-6 col-sm-6 col-md-6">
                <strong>{{ __('springs.specific_conductance') }}</strong>
                <input type="number" name="specific_conductance" class="form-control" placeholder="" value="{{$observation->specific_conductance}}" aria-describedby="specific_conductance_help_block">
                <small id="specific_conductance_help_block" class="form-text text-muted">
                    {{ __('springs.specific_conductance_help_text') }}
                </small>
            </div>
        </div>

        <div class="form-row col-xs-12 col-sm-12 col-md-12">
            <div class="pull-left col-xs-6 col-sm-6 col-md-6">
                <strong>{{ __('springs.electrical_conductivity') }}</strong>
                <input type="number" name="electrical_conductivity" class="form-control" placeholder="" value="{{$observation->electrical_conductivity}}" aria-describedby="electrical_conductivity_help_block">
                <small id="electrical_conductivity_help_block" class="form-text text-muted">
                    {{ __('springs.electrical_conductivity_help_text') }}
                </small>
            </div>
            <div class="pull-right col-xs-6 col-sm-6 col-md-6">
                <strong>{{ __('springs.total_dissolved_solids') }}</strong>
                <input type="number" name="total_dissolved_solids" class="form-control" placeholder="" value="{{$observation->total_dissolved_solids}}">
            </div>
        </div>

        <div class="form-row col-xs-12 col-sm-12 col-md-12">
            <div class="pull-left col-xs-6 col-sm-6 col-md-6">
                <strong>{{ __('springs.nitrate') }}</strong>
                <input type="number" name="nitrate" class="form-control" placeholder="" value="{{$observation->nitrate}}">
            </div>
            <div class="pull-right col-xs-6 col-sm-6 col-md-6">
                <strong>{{ __('springs.bicarbonate') }}</strong>
                <input type="number" name="bicarbonate" class="form-control" placeholder="" value="{{$observation->bicarbonate}}">
            </div>
        </div>

        <div class="form-row col-xs-12 col-sm-12 col-md-12">
            <div class="pull-left col-xs-6 col-sm-6 col-md-6">
                <strong>{{ __('springs.redox_potential') }}</strong>
                <input type="number" name="redox_poential" class="form-control" placeholder="" value="{{$observation->redox_potential}}">
            </div>
            <div class="pull-right col-xs-6 col-sm-6 col-md-6">
                <strong>{{ __('springs.dissolved_oxygen_percentage') }}</strong>
                <input type="number" name="dissolved_oxygen_percentage" class="form-control" placeholder="" value="{{$observation->dissolved_oxygen_percentage}}" aria-describedby="dissolved_oxygen_help_block">
                <small id="dissolved_oxygen_percentage_help_block" class="form-text text-muted">
                    {{ __('springs.dissolved_oxygen_percentage_help_text') }}
                </small>
            </div>
        </div>

        <div class="form-row col-xs-12 col-sm-12 col-md-12">
            <div class="pull-left col-xs-6 col-sm-6 col-md-6">
                <strong>{{ __('springs.dissolved_oxygen_ppm') }}</strong>
                <input type="number" name="dissolved_oxygen_ppm" class="form-control" placeholder="" value="{{$observation->dissolved_oxygen_ppm}}" aria-describedby="water_temperature_help_block">
                <small id="dissolved_oxygen_ppm_help_block" class="form-text text-muted">
                    {{ __('springs.dissolved_oxygen_ppm_help_text') }}
                </small>
            </div>
            <div class="pull-right col-xs-6 col-sm-6 col-md-6">
                <strong>{{ __('springs.discharge') }}</strong>
                <input type="number" name="discharge" class="form-control" placeholder="" value="{{$observation->discharge}}" aria-describedby="discharge_help_block">
                <small id="discharge_help_block" class="form-text text-muted">
                    {{ __('springs.discharge_help_text') }}
                </small>
            </div>
        </div>



        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('springs.description') }}</strong>
                <textarea class="form-control" style="height:150px" name="description" placeholder="" aria-describedby="description_help_block">{{$observation->description}}</textarea>
                <small id="description_help_block" class="form-text text-muted">
                    {{ __('springs.description_help_text') }}
                </small>
            </div>
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
