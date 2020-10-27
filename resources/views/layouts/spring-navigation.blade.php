
<div class="row">
    <div class="pull-left col-lg-9 margin-tb">
        @isset($spring->title)
            <h2>{{$spring->title}}</h2>
        @else
            <h2>{{ __('springs.unnamed') }}</h2>
        @endisset
    </div>
</div>

<nav class="navbar">
    <div class="navbar-nav">
        <a class="nav-item nav-link" href="{{ route('springs.show',$spring->id) }}">{{ __('springs.view') }}</a>
        <a class="nav-item nav-link" href="{{ route('springs.observations.index', $spring->id) }}">{{ __('springs.observations') }}</a>
        <a class="nav-item nav-link" href="{{ route('springs.measurements.index', $spring->id) }}">{{ __('springs.measurements') }}</a>
    </div>
</nav>
