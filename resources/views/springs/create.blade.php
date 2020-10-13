@extends('springs.layout')

@auth
@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div>
                <h2>{{ __('springs.add_new_spring') }}</h2>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('springs.store') }}" method="POST">
        @csrf

        <div class="form-row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ __('springs.title') }}</strong>
                    <input type="text" name="title" class="form-control" placeholder="">
                    <small id="title_help_block" class="form-text text-muted">
                        {{ __('springs.title_help_text') }}
                    </small>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <strong>{{ __('springs.kkr_code') }}</strong>
                    <input type="text" name="kkr_code" class="form-control" placeholder="VEE2096530">
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <strong>{{  __('springs.location') }}</strong>
                <div id="address-map-container" class="col-xs-12 col-sm-12 col-md-12" style="width:100%;height:400px; ">
                    <div style="width: 100%; height: 100%" id="address-map"></div>
                </div>
            </div>
        </div>

        <div class="form-row col-xs-12 col-sm-12 col-md-12">
            <div class="pull-left col-xs-6 col-sm-6 col-md-6">
                <strong>{{ __('springs.latitude') }}</strong>
                <input type="text" name="latitude" class="form-control" placeholder="Latitude" id="latitude">
                <small id="latitude_help_block" class="form-text text-muted">
                    {{ __('springs.latitude_help_text') }}
                </small>
            </div>
            <div class="pull-right col-xs-6 col-sm-6 col-md-6">
                <strong>{{ __('springs.longitude') }}</strong>
                <input type="text" name="longitude" class="form-control" placeholder="Longitude" id="longitude">
                <small id="longitude_help_block" class="form-text text-muted">
                    {{ __('springs.longitude_help_text') }}
                </small>
            </div>
        </div>

        <div class="form-row col-xs-12 col-sm-12 col-md-12">
            <div class="pull-left col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <strong>{{ __('springs.county') }}</strong>
                    <input type="text" name="county" class="form-control" placeholder="County" id="county">
                </div>
            </div>
            <div class="pull-right col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <strong>{{ __('springs.settlement') }}</strong>
                    <input type="text" name="settlement" class="form-control" placeholder="Settlement" id="settlement">
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{__('springs.photos')}}</strong><br />
                <strong>{{__('springs.references')}}</strong>
            </div>
        </div>
        <!--<div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{__('springs.photos')}}</strong>


                <div class="input-group control-group increment" >
                    <input type="file" name="filename[]" class="form-control">
                    <div class="input-group-btn">
                        <button class="btn btn-success" type="button"><i class="glyphicon glyphicon-plus"></i>Add</button>
                    </div>
                </div>
                <div class="clone">
                    <div class="control-group input-group" style="margin-top:10px">
                        <input type="file" name="filename[]" class="form-control">
                        <div class="input-group-btn">
                            <button class="btn btn-danger" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{__('springs.references')}}</strong>
                <div class="form-group">
                    <input type="text" placeholder="URL" name="spring_references[1][url]"/>
                    <input type="text" placeholder="URL description" name="spring_references[1][url_description]"/>
                </div>
                <div class="form-group">
                    <input type="text" placeholder="URL" name="spring_references[2][url]"/>
                    <input type="text" placeholder="URL description" name="spring_references[2][url_description]"/>
                </div>
            </div>
        </div>-->

        <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ __('springs.description') }}</strong>
                    <textarea class="form-control" style="height:150px" name="description" placeholder="Description"></textarea>
                    <small id="description_help_block" class="form-text text-muted">
                        {{ __('springs.description_help_text') }}
                    </small>
                </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ __('springs.folklore') }}</strong>
                    <textarea class="form-control" style="height:150px" name="folklore" placeholder="Folklore"></textarea>
                </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Link with other databases</strong>
            </div>
        </div>

        <div class="form-row col-xs-12 col-sm-12 col-md-12">
            <div class="pull-left col-xs-6 col-sm-6 col-md-6">
                <strong>{{ __('springs.spring_classification') }}</strong>
                <select name="classification" class="form-control">
                    <option value="rheocrene"> {{ __('springs.rheocrene') }}</option>
                    <option value="hillslope_spring">{{ __('springs.hillslope_spring') }}</option>
                    <option value="limnocrene">{{ __('springs.limnocrene') }}</option>
                    <option value="helocrene">{{ __('springs.helocrene') }}</option>
                    <option value="cave_spring">{{ __('springs.cave_spring') }}</option>
                    <option value="hypocrene">{{ __('springs.hypocrene') }}</option>
                    <option value="captured_spring">{{ __('springs.captured_spring') }}</option>
                    <option value="karst_spring">{{ __('springs.karst_spring') }}</option>
                </select>
            </div>
            <div class="pull-right col-xs-6 col-sm-6 col-md-6">
                <strong>{{ __('springs.groundwater_body') }}</strong>
                <input type="text" name="groundwater_body" class="form-control" placeholder="">
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('springs.geology') }}</strong>
                <textarea class="form-control" style="height:150px" name="geology" placeholder=""></textarea>
            </div>
        </div>

        <div class="form-row">
            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <strong>{{ __('springs.ownership') }}</strong>
                    <select name="ownership" class="form-control">
                        <option value="private_property"> {{ __('springs.private_property') }}</option>
                        <option value="state_property">{{ __('springs.state_property') }}</option>
                        <option value="municipal_property">{{ __('springs.municipal_property') }}</option>
                    </select>
                </div>
            </div>
        </div>

        <div>
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <div class="pull-left">
                        <button type="submit" class="btn btn-primary">{{ __('springs.add') }}</button>
                    </div>
                    <div class="pull-right">
                        <a class="btn btn-primary" href="{{ route('springs.index') }}">{{ __('springs.back') }}</a>
                    </div>
                </div>
            </div>
        </div>

    </form>

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script type="text/javascript">

        $(document).ready(function() {

            $(".clone").hide();

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
    <script src="/js/mapInput.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initMap" async defer></script>
@endsection

@endauth

