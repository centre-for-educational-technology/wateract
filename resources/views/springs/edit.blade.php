@extends('springs.layout')

@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div>
                <h2>{{ __('Edit Spring') }}</h2>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('springs.update', $spring->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ __('Title') }}</strong>
                    <input type="text" name="title" class="form-control" placeholder="" value="{{$spring->title}}">
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <strong>{{ __('KKR Code') }}</strong>
                    <input type="text" name="kkr_code" class="form-control" placeholder="VEE2096530" value="{{$spring->kkr_code}}">
                </div>
            </div>
        </div>

        <div class="form-row">
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
                <input type="text" name="latitude" class="form-control" placeholder="Latitude" id="latitude" value="{{$spring->latitude}}">
            </div>
            <div class="pull-right col-xs-6 col-sm-6 col-md-6">
                <strong>{{ __('Longitude') }}</strong>
                <input type="text" name="longitude" class="form-control" placeholder="Longitude" id="longitude" value="{{$spring->longitude}}">
            </div>
        </div>

        <div class="form-row col-xs-12 col-sm-12 col-md-12">
            <div class="pull-left col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <strong>{{ __('County') }}</strong>
                    <input type="text" name="county" class="form-control" placeholder="County" id="county" value="{{$spring->county}}">
                </div>
            </div>
            <div class="pull-right col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <strong>{{ __('Municipality') }}</strong>
                    <input type="text" name="municipality" class="form-control" placeholder="Municipality" id="municipality" value="{{$spring->municipality}}">
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col-xs-12 col-sm-12 col-md-12">PHOTOS</div>
            <div class="col-xs-12 col-sm-12 col-md-12">REFERENCES</div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('Description of natural conditions') }}</strong>
                <textarea class="form-control" style="height:150px" name="description" placeholder="Description">{{$spring->description}}</textarea>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('Folklore') }}</strong>
                <textarea class="form-control" style="height:150px" name="folklore" placeholder="Folklore">{{$spring->folklore}}</textarea>
            </div>
        </div>

        <!-- image upload

        <div class="input-group control-group increment" >
            <input type="file" name="filename[]" class="form-control">
            <div class="input-group-btn">
                <button class="btn btn-success" type="button"><i class="glyphicon glyphicon-plus"></i>Add</button>
            </div>
        </div>
        <div class="clone hide">
            <div class="control-group input-group" style="margin-top:10px">
                <input type="file" name="filename[]" class="form-control">
                <div class="input-group-btn">
                    <button class="btn btn-danger" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button>
                </div>
            </div>
        </div>
         image upload ------------- -->







    <!--<div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">{{ __('Add') }}</button>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('springs.index') }}"> Back</a>
            </div>-->
        <div>
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <div class="pull-left">
                        <button type="submit" class="btn btn-primary">{{ __('Edit') }}</button>
                    </div>
                    <div class="pull-right">
                        <a class="btn btn-primary" href="{{ route('springs.index') }}"> Back</a>
                    </div>
                </div>
            </div>
        </div>

    </form>

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script type="text/javascript">

        $(document).ready(function() {

            $(".btn-success").click(function(){
                var html = $(".clone").html();
                $(".increment").after(html);
            });

            $("body").on("click",".btn-danger",function(){
                $(this).parents(".control-group").remove();
            });

        });

    </script>

@endsection

@section('scripts')
    @parent
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initMap" async defer></script>
    <script src="/js/mapInput.js"></script>
@endsection

