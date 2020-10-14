@extends('springs.layout')

@section('content')

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="row">
        <div class="pull-left col-lg-9 margin-tb">
                @isset($spring->title)
                    <h1>{{$spring->title}}</h1>
                @else
                    <h1>{{ __('springs.unnamed') }}</h1>
                @endisset
        </div>

        <div class="pull-right col-lg-3 margin-tb">
            @auth
                <form action="{{ route('springs.destroy',$spring->id) }}" method="POST">
                    <a class="btn btn-primary" href="{{ route('springs.edit',$spring->id) }}">{{ __('springs.edit') }}</a>
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('springs.delete') }}</button>
                </form>
            @endauth
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div id="map-container" style="width:100%;height:400px;">
                <div style="width: 100%; height: 100%" id="map"></div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="pull-left col-xs-9 col-sm-9 col-md-9">
                @isset($spring->description)
                    <div class="row margin-tb">
                        <div class="form-group">
                            <h4>{{ __('Description of natural conditions') }}</h4>
                            <div>{{$spring->description}}</div>
                        </div>
                    </div>
                @endisset

                @isset($spring->folklore)
                    <div class="row margin-tb">
                        <div class="form-group">
                            <h4>{{ __('Folklore') }}</h4>
                            <div class="margin-tb">{{$spring->folklore}}</div>
                        </div>
                    </div>
                @endisset

                @isset($spring->geology)
                    <div class="row margin-tb">
                        <div class="form-group">
                            <h4>{{ __('springs.geology') }}</h4>
                            <div class="margin-tb">{{$spring->geology}}</div>
                        </div>
                    </div>
                @endisset
            </div>

            <div class="pull-right col-xs-3 col-sm-3 col-md-3">

                <div class="row">
                    <strong>{{ __('springs.location') }}</strong>
                    <div>{{$spring->county}}</div>

                    @isset($spring->settlement)
                        <div>{{$spring->settlement}}</div>
                    @endisset
                </div>

                <div class="row">
                    <div>{{$spring->latitude}} {{$spring->longitude}}</div>
                </div>

                @isset($spring->kkr_code)
                    <div class="row">
                        <div class="group">
                            <strong>{{ __('KKR Code') }}</strong>
                            <div>{{$spring->kkr_code}}</div>
                        </div>
                    </div>
                @endisset

                @isset($spring->classification)
                    <div class="row">
                        <strong>{{ __('springs.spring_classification') }}</strong>
                        <div>{{$spring->classification}}</div>
                    </div>
                @endisset

                @isset($spring->groundwater_body)
                    <div class="row">
                        <strong>{{ __('springs.groundwater_body') }}</strong>
                        <div>{{$spring->groundwater_body}}</div>
                    </div>
                @endisset

                @isset($spring->ownership)
                    <div class="row">
                        <strong>{{ __('springs.ownership') }}</strong>
                        <div>{{$spring->ownership}}</div>
                    </div>
                @endisset

                @isset($spring->status)
                    <div class="row">
                        <strong>{{ __('springs.status') }}</strong>
                        <div>{{$spring->status}}</div>
                    </div>
                @endisset
            </div>
        </div>
    </div>

    <div>
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ route('springs.index') }}"> Back</a>
                </div>
            </div>
        </div>
    </div>





@endsection

@section('scripts')
    @parent
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initMap" async defer></script>
    <script>
        function initMap() {
            var latitude = {{$spring->latitude}};
            var longitude = {{$spring->longitude}};
            map = new google.maps.Map(document.getElementById("map"), {
                center: {lat: latitude, lng: longitude},
                zoom: 12,
                mapTypeId: 'terrain'
            });
            if (latitude && longitude) {
                const latlng = {
                    lat: latitude,
                    lng: longitude,
                };
                marker = new google.maps.Marker({
                    position: latlng,
                    map: map,
                });
            }
        }
    </script>
@endsection

