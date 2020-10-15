@extends('springs.layout')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-right">
                @auth
                    <a class="btn btn-success" href="{{ route('springs.create') }}">{{__('springs.add_new_spring')}}</a>
                @endauth
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="z-depth-1-half map-container" style="height:600px;">
        <div style="width: 100%; height: 100%" id="map"></div>
    </div>

@endsection

@section('scripts')
    @parent
    <script src="/js/map.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initMap" async defer></script>
@endsection
