@extends('springs.layout')

@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div>
                @isset($spring->title)
                    <h2>{{$spring->title}}</h2>
                @else
                    <h2>{{ __('springs.unnamed') }}</h2>
                @endisset
            </div>
        </div>
    </div>

    @isset($spring->kkr_code)
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="group">
                    <strong>{{ __('KKR Code') }}</strong>
                    <div>{{$spring->kkr_code}}</div>
                </div>
            </div>
        </div>
    @endisset

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <strong>{{  __('Location') }}</strong>
                <div id="address-map-container" class="col-xs-12 col-sm-12 col-md-12" style="width:100%;height:400px; ">
                    <div style="width: 100%; height: 100%" id="address-map"></div>
                </div>
            </div>
        </div>

        <div class="form-row col-xs-12 col-sm-12 col-md-12">
            <div class="pull-left col-xs-6 col-sm-6 col-md-6">
                <strong>{{ __('Latitude') }}</strong>
                <div>{{$spring->latitude}}</div>
            </div>
            <div class="pull-right col-xs-6 col-sm-6 col-md-6">
                <strong>{{ __('Longitude') }}</strong>
                <div>{{$spring->longitude}}</div>
            </div>
        </div>

        <div class="form-row col-xs-12 col-sm-12 col-md-12">
            <div class="pull-left col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <strong>{{ __('County') }}</strong>
                    <div>{{$spring->county}}</div>
                </div>
            </div>
            @isset($spring->settlement)
                <div class="pull-right col-xs-6 col-sm-6 col-md-6">
                    <div class="form-group">
                        <strong>{{ __('Settlement') }}</strong>
                        <div>{{$spring->settlement}}</div>
                    </div>
                </div>
            @endisset
        </div>

        <!--<div class="form-row">
            <div class="col-xs-12 col-sm-12 col-md-12">PHOTOS</div>
            <div class="col-xs-12 col-sm-12 col-md-12">REFERENCES</div>
        </div>-->

    @isset($spring->description)
        <div class="row col-xs-12 col-sm-12 col-md-12">
            <div class="group">
                <strong>{{ __('Description of natural conditions') }}</strong>
                <div>{{$spring->description}}</div>
            </div>
        </div>
    @endisset

    @isset($spring->folklore)
        <div class="row col-xs-12 col-sm-12 col-md-12">
            <div class="group">
                <strong>{{ __('Folklore') }}</strong>
                <div>{{$spring->folklore}}</div>
            </div>
        </div>
    @endisset

        <div>
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <!--<div class="pull-left">
                        <button type="submit" class="btn btn-primary">{{ __('Edit') }}</button>
                    </div>-->
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
            map = new google.maps.Map(document.getElementById("address-map"), {
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

