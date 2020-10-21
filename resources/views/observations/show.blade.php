
<div class="card">

@isset($observation->odor)
    <label><strong>{{ __('springs.odor') }}</strong></label>
    <div>{{$observation->odor}}</div>
@endisset

@isset($observation->taste)
    <label><strong>{{ __('springs.taste') }}</strong></label>
        <div>@lang('springs.taste_options.'.$observation->taste)</div>
    @endisset

@isset($observation->color)
    <label><strong>{{ __('springs.color_and_turbidity') }}</strong></label>
    <div>{{$observation->color}}</div>
@endisset

@isset($observation->water_temperature)
    <label><strong>{{ __('springs.water_temperature') }}</strong></label>
    <div>{{$observation->water_temperature}}</div>
@endisset

@isset($observation->air_temperature)
    <label><strong>{{ __('springs.air_temperature') }}</strong></label>
    <div>{{$observation->air_temperature}}</div>
@endisset

@isset($observation->ph)
    <label><strong>{{ __('springs.pH') }}</strong></label>
    <div>{{$observation->ph}}</div>
@endisset

@isset($observation->specific_conductance)
    <label><strong>{{ __('springs.specific_conductance') }}</strong></label>
    <div>{{$observation->specific_conductance}}</div>
@endisset

@isset($observation->electrical_conductivity)
    <label><strong>{{ __('springs.electrical_conductivity') }}</strong></label>
    <div>{{$observation->electrical_conductivity}}</div>
@endisset

@isset($observation->total_dissolved_solids)
    <label><strong>{{ __('springs.total_dissolved_solids') }}</strong></label>
    <div>{{$observation->total_dissolved_solids}}</div>
@endisset

@isset($observation->nitrate)
    <label><strong>{{ __('springs.nitrate') }}</strong></label>
    <div>{{$observation->nitrate}}</div>
@endisset

@isset($observation->bicarbonate)
    <label><strong>{{ __('springs.bicarbonate') }}</strong></label>
    <div>{{$observation->bicarbonate}}</div>
@endisset

@isset($observation->redox_potential)
    <label><strong>{{ __('springs.redox_potential') }}</strong></label>
    <div>{{$observation->redox_potential}}</div>
@endisset

@isset($observation->dissolved_oxygen_percentage)
    <label><strong>{{ __('springs.dissolved_oxygen_percentage') }}</strong></label>
    <div>{{$observation->dissolved_oxygen_percentage}}</div>
@endisset

@isset($observation->dissolved_oxygen_ppm)
    <label><strong>{{ __('springs.dissolved_oxygen_ppm') }}</strong></label>
    <div>{{$observation->dissolved_oxygen_ppm}}</div>
@endisset

@isset($observation->discharge)
    <label><strong>{{ __('springs.discharge') }}</strong></label>
    <div>{{$observation->discharge}}</div>
@endisset

@isset($observation->description)
    <label><strong>{{ __('springs.description') }}</strong></label>
    <div>{{$observation->description}}</div>
@endisset

</div>
