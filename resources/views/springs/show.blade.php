@extends('springs.layout')

@section('content')

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    @include('layouts.spring-navigation')

    @auth
        <div class="row">
            <div class="pull-right col-lg-3 margin-tb">
                <form action="{{ route('springs.destroy',$spring->id) }}" method="POST">
                    <a class="btn btn-primary" href="{{ route('springs.edit',$spring->id) }}">{{ __('springs.edit') }}</a>
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('springs.delete') }}</button>
                </form>
            </div>
        </div>
    @endauth

    <div class="row m-t">
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
                            <h4>{{ __('springs.description') }}</h4>
                            <div>{{$spring->description}}</div>
                        </div>
                    </div>
                @endisset

                @isset($spring->folklore)
                    <div class="row margin-tb">
                        <div class="form-group">
                            <h4>{{ __('springs.folklore') }}</h4>
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

                    @if ( count($spring->references)>0 )
                        <div class="row margin-tb">
                            <div class="form-group">
                                <h4>{{__('springs.references')}}</h4>
                                @foreach ($spring->references as $spring_reference)
                                    <div class="form-group">
                                        <a href="{{ $spring_reference->url }}" target="_blank">{{ $spring_reference->url_title }}</a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ( count($spring->database_links)>0 )
                        <div class="row margin-tb">
                            <div class="form-group">
                                <h4>{{__('springs.link_with_other_databases')}}</h4>
                                <table class="table">
                                <tbody>
                                    <thead>
                                    <tr>
                                        <th scope="col">{{__('springs.database_name')}}</th>
                                        <th scope="col">{{__('springs.code')}}</th>
                                        <th scope="col">{{__('springs.spring_name')}}</th>
                                        <th scope="col">{{__('springs.url')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($spring->database_links as $database_link)
                                        <tr>
                                            <td>{{ $database_link->database_name }}</td>
                                            <td>{{ $database_link->code }}</td>
                                            <td>{{ $database_link->spring_name }}</td>
                                            <td><a href="{{ $database_link->url }}" target="_blank">{{ $database_link->url }}</a></td>
                                    </tr>
                                </tbody>
                                </table>
                                @endforeach
                            </div>
                        </div>
                    @endif

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
                        <div>@lang('springs.classification_options.'.$spring->classification)</div>
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
                        <div>@lang('springs.ownership_options.'.$spring->ownership)</div>
                    </div>
                @endisset

                @isset($spring->status)
                    <div class="row">
                        <strong>{{ __('springs.status') }}</strong>
                        <div>@lang('springs.status_options.'.$spring->status)</div>
                    </div>
                @endisset
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

