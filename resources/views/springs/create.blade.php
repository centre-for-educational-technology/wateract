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
                    <label><strong>{{ __('springs.title') }}</strong></label>
                    <input type="text" name="title" class="form-control" placeholder="" value="{{old('title')}}">
                    <small id="title_help_block" class="form-text text-muted">
                        {{ __('springs.title_help_text') }}
                    </small>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <label><strong>{{ __('springs.kkr_code') }}</strong></label>
                    <input type="text" name="kkr_code" class="form-control" placeholder="VEE2096530" value="{{old('kkr_code')}}">
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <label><strong>{{  __('springs.location') }}</strong></label>
                <div id="address-map-container" style="width:100%;height:400px; ">
                    <div style="width: 100%; height: 100%" id="address-map"></div>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="pull-left col-xs-6 col-sm-6 col-md-6">
                <label><strong>{{ __('springs.latitude') }}</strong></label>
                <input type="text" name="latitude" class="form-control" placeholder="" id="latitude" value="{{old('latitude')}}">
                <small id="latitude_help_block" class="form-text text-muted">
                    {{ __('springs.latitude_help_text') }}
                </small>
            </div>
            <div class="pull-right col-xs-6 col-sm-6 col-md-6">
                <label><strong>{{ __('springs.longitude') }}</strong></label>
                <input type="text" name="longitude" class="form-control" placeholder="" id="longitude" value="{{old('longitude')}}">
                <small id="longitude_help_block" class="form-text text-muted">
                    {{ __('springs.longitude_help_text') }}
                </small>
            </div>
        </div>

        <div class="form-row">
            <div class="pull-left col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <label><strong>{{ __('springs.county') }}</strong></label>
                    <input type="text" name="county" class="form-control" placeholder="" id="county" value="{{old('county')}}">
                </div>
            </div>
            <div class="pull-right col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <label><strong>{{ __('springs.settlement') }}</strong></label>
                    <input type="text" name="settlement" class="form-control" placeholder="" id="settlement" value="{{old('settlement')}}">
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{__('springs.photos')}}</strong><br />
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
        -->

        <div class="col-xs-12 col-sm-12 col-md-12">
            <strong>{{__('springs.references')}}</strong>

            <div class="reference-group form-group input-group control-group increment ">
                <input type="text" class="form-control" placeholder="{{__('springs.url')}}" name="spring_references[1][url]"/>
                <input type="text" class="form-control" placeholder="{{__('springs.url_title')}}" name="spring_references[1][url_title]"/>
                <div class="input-group-btn">
                    <button class="btn btn-success" type="button"><i class="glyphicon glyphicon-plus"></i>{{__('springs.add')}}</button>
                </div>
            </div>

                <div class="clone">
                    <div class="reference-group control-group input-group form-group">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="{{__('springs.url')}}" name=""/>
                            <input type="text" class="form-control" placeholder="{{__('springs.url_title')}}" name=""/>
                        </div>
                        <div class="input-group-btn">
                            <button class="btn btn-danger" type="button"><i class="glyphicon glyphicon-remove"></i>{{__('springs.remove')}}</button>
                        </div>
                    </div>
                </div>

        </div>


        <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <label><strong>{{ __('springs.description') }}</strong></label>
                    <textarea class="form-control" style="height:150px" name="description" placeholder="" >{{old('description')}}</textarea>
                    <small id="description_help_block" class="form-text text-muted">
                        {{ __('springs.description_help_text') }}
                    </small>
                </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <label><strong>{{ __('springs.folklore') }}</strong></label>
                    <textarea class="form-control" style="height:150px" name="folklore" placeholder="" >{{old('folklore')}}</textarea>
                </div>
        </div>

            <div class="form-group pull-left">
                <label><strong>{{ __('springs.link_with_other_databases') }}</strong></label>
                <div class="database-group form-group input-group control-group databases-increment ">
                    <div class="pull-left col-xs-6 col-sm-6 col-md-6">
                        <input type="text" class="form-control" placeholder="{{__('springs.database_name')}}" name="spring_databases[1][database_name]"/>
                        <input type="text" class="form-control" placeholder="{{__('springs.spring_name')}}" name="spring_databases[1][spring_name]"/>
                    </div>
                    <div class="pull-right col-xs-6 col-sm-6 col-md-6">
                        <input type="text" class="form-control" placeholder="{{__('springs.code')}}" name="spring_databases[1][code]"/>
                        <input type="text" class="form-control" placeholder="{{__('springs.url')}}" name="spring_databases[1][url]"/>
                    </div>
                    <div class="input-group-btn">
                        <button class="btn database-btn-success" type="button"><i class="glyphicon glyphicon-plus"></i>{{__('springs.add')}}</button>
                    </div>
                </div>
            </div>
        <div class="database-clone clone">
            <div class="database-group control-group input-group form-group">
                <div class="pull-left col-xs-6 col-sm-6 col-md-6">
                    <input type="text" class="form-control" placeholder="{{__('springs.database_name')}}" name=""/>
                    <input type="text" class="form-control" placeholder="{{__('springs.spring_name')}}" name=""/>
                </div>
                <div class="pull-right col-xs-6 col-sm-6 col-md-6">
                    <input type="text" class="form-control" placeholder="{{__('springs.code')}}" name=""/>
                    <input type="text" class="form-control" placeholder="{{__('springs.url')}}" name=""/>
                </div>
                <div class="input-group-btn">
                    <button class="btn btn-danger" type="button"><i class="glyphicon glyphicon-remove"></i>{{__('springs.remove')}}</button>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="pull-left col-xs-6 col-sm-6 col-md-6">
                <label><strong>{{ __('springs.spring_classification') }}</strong></label>
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
                <label><strong>{{ __('springs.groundwater_body') }}</strong></label>
                <input type="text" name="groundwater_body" class="form-control" placeholder="" value="{{old('groundwater_body')}}">
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <label><strong>{{ __('springs.geology') }}</strong></label>
                <textarea class="form-control" style="height:150px" name="geology" placeholder="">{{old('geology')}}</textarea>
            </div>
        </div>

        <div class="form-row">
            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <label><strong>{{ __('springs.ownership') }}</strong></label>
                    <select name="ownership" class="form-control">
                        <option value="private_property"> {{ __('springs.private_property') }}</option>
                        <option value="state_property">{{ __('springs.state_property') }}</option>
                        <option value="municipal_property">{{ __('springs.municipal_property') }}</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-row col-xs-12 col-sm-12 col-md-12">
            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <label><strong>{{ __('springs.status') }}</strong></label>
                    <select name="status" class="form-control">
                        <option value="unconfirmed"> {{ __('springs.unconfirmed') }}</option>
                        <option value="confirmed">{{ __('springs.confirmed') }}</option>
                        <option value="featured">{{ __('springs.featured') }}</option>
                    </select>
                </div>
            </div>
        </div>

        <div>
            <div class="form-row">
                <div class="col-lg-12 margin-tb">
                    <div class="pull-left">
                        <button type="submit" class="btn btn-primary">{{ __('springs.add') }}</button>
                    <!--</div>
                    <div class="pull-right">-->
                        <a class="btn" href="{{ route('springs.index') }}">{{ __('springs.cancel') }}</a>
                    </div>
                </div>
            </div>
        </div>

    </form>


    <script type="text/javascript">

        $(document).ready(function() {

            let references_count = 1;
            let databases_count = 1;

            $(".clone").hide();

            $(".btn-success").click(function(){
                references_count++;
                cloneReferenceBlock(references_count);
            });
            $(".database-btn-success").click(function(){
                databases_count++;
                cloneDatabaseBlock(databases_count);
            });

            $("body").on("click",".btn-danger",function(){
                $(this).parents(".control-group").remove();
            });

        });

        function cloneReferenceBlock(references_count) {

                //alert(references_count);

                var clone = $(".clone").clone();
                clone.find('input[type=text]').each(function(index) {

                    if (index == 0) {
                        $(this).attr('name', "spring_references[" + references_count + "][url]");
                    } else if(index == 1) {
                        $(this).attr('name', "spring_references[" + references_count + "][url_title]");
                    }
                });

                var html = clone.html();
                $(".reference-group:visible").last().after(html);
            //$(".increment").after(html);



        }

        function cloneDatabaseBlock(databases_count) {
            var clone = $(".database-clone").clone();
            clone.find('input[type=text]').each(function(index) {
                if (index == 0) {
                    $(this).attr('name', "spring_databases[" + databases_count + "][database_name]");
                } else if(index == 1) {
                    $(this).attr('name', "spring_databases[" + databases_count + "][spring_name]");
                } else if(index == 2) {
                    $(this).attr('name', "spring_databases[" + databases_count + "][code]");
                } else if(index == 3) {
                    $(this).attr('name', "spring_databases[" + databases_count + "][url]");
                }
            });
            var html = clone.html();
            $(".database-group:visible").last().after(html);
        }

    </script>

@endsection

@section('scripts')
    @parent
    <script src="/js/mapInput.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initMap" async defer></script>
@endsection

@endauth

