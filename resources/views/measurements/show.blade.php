
<div class="card">

    @foreach ($measurement->getFieldValues() as $measurement_value)
        <label><strong>@lang('springs.measurement_fields.'.$measurement_value->name)</strong></label>
        <div>{{$measurement_value->value}} {{$measurement_value->unit}}</div>
    @endforeach

</div>
