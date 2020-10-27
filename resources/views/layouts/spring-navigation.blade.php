
<nav class="navbar">
    <div class="navbar-nav">
        <a class="nav-item nav-link" href="{{ route('springs.show',$spring->id) }}">{{ __('springs.view') }}</a>
        <a class="nav-item nav-link" href="{{ route('springs.observations.index', $spring->id) }}">{{ __('springs.observations') }}</a>
        <a class="nav-item nav-link" href="{{ route('springs.measurements.index', $spring->id) }}">{{ __('springs.measurements') }}</a>
    </div>
</nav>
