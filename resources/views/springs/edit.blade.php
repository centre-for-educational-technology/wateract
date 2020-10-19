@extends('springs.layout')

@auth
@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div>
                <h2>{{ __('springs.edit_spring') }}</h2>
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

    <form action="{{ route('springs.update', $spring->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ __('springs.title') }}</strong>
                    <input type="text" name="title" class="form-control" placeholder="" value="{{$spring->title}}">
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
                    <input type="text" name="kkr_code" class="form-control" placeholder="VEE2096530" value="{{$spring->kkr_code}}">
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <label><strong>{{  __('springs.location') }}</strong></label>
                <div id="address-map-container" class="col-xs-12 col-sm-12 col-md-12" style="width:100%;height:400px; ">
                    <div style="width: 100%; height: 100%" id="address-map"></div>
                </div>
            </div>
        </div>

        <div class="form-row col-xs-12 col-sm-12 col-md-12">
            <div class="pull-left col-xs-6 col-sm-6 col-md-6">
                <label><strong>{{ __('springs.latitude') }}</strong></label>
                <input type="text" name="latitude" class="form-control" placeholder="" id="latitude" value="{{$spring->latitude}}">
                <small id="latitude_help_block" class="form-text text-muted">
                    {{ __('springs.latitude_help_text') }}
                </small>
            </div>
            <div class="pull-right col-xs-6 col-sm-6 col-md-6">
                <label><strong>{{ __('springs.longitude') }}</strong></label>
                <input type="text" name="longitude" class="form-control" placeholder="" id="longitude" value="{{$spring->longitude}}">
                <small id="longitude_help_block" class="form-text text-muted">
                    {{ __('springs.longitude_help_text') }}
                </small>
            </div>
        </div>

        <div class="form-row col-xs-12 col-sm-12 col-md-12">
            <div class="pull-left col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <label><strong>{{ __('springs.county') }}</strong></label>
                    <input type="text" name="county" class="form-control" placeholder="" id="county" value="{{$spring->county}}">
                </div>
            </div>
            <div class="pull-right col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <label><strong>{{ __('springs.settlement') }}</strong></label>
                    <input type="text" name="settlement" class="form-control" placeholder="" id="settlement" value="{{$spring->settlement}}">
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{__('springs.photos')}}</strong><br />
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <label><strong>{{__('springs.references')}}</strong></label>
                @foreach ($spring->references as $spring_reference)
                    <div class="form-group">
                        <input type="hidden" class="" name="spring_references[{{ $loop->index }}][id]" value="{{ $spring_reference->id }}" />
                        <input type="text" class="form-control" placeholder="URL" name="spring_references[{{ $loop->index }}][url]" value="{{ $spring_reference->url }}" />
                        <input type="text" class="form-control" placeholder="URL title" name="spring_references[{{ $loop->index }}][url_title]" value="{{ $spring_reference->url_title }}" />
                    </div>
                @endforeach
                @if ( count($spring->references) == 0 )
                    <input type="text" class="form-control" placeholder="URL" name="spring_references[1][url]"/>
                    <input type="text" class="form-control" placeholder="URL title" name="spring_references[1][url_title]"/>
                    @endif

            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <label><strong>{{ __('springs.description') }}</strong></label>
                <textarea class="form-control" style="height:150px" name="description" placeholder="">{{$spring->description}}</textarea>
                <small id="description_help_block" class="form-text text-muted">
                    {{ __('springs.description_help_text') }}
                </small>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <label><strong>{{ __('springs.folklore') }}</strong></label>
                <textarea class="form-control" style="height:150px" name="folklore" placeholder="">{{$spring->folklore}}</textarea>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <label><strong>{{ __('springs.link_with_other_databases') }}</strong></label>
                <div class="database-group form-group input-group control-group databases-increment ">
                    @foreach ($spring->database_links as $database_link)
                    <div class="pull-left col-xs-6 col-sm-6 col-md-6">
                        <input type="hidden" class="" name="spring_databases[{{ $loop->index }}][id]" value="{{ $database_link->id }}" />
                        <input type="text" class="form-control" placeholder="{{__('springs.database_name')}}" name="spring_databases[{{ $loop->index }}][database_name]" value="{{$database_link->database_name}}" />
                        <input type="text" class="form-control" placeholder="{{__('springs.spring_name')}}" name="spring_databases[{{ $loop->index }}][spring_name]" value="{{$database_link->spring_name}}" />
                    </div>
                    <div class="pull-right col-xs-6 col-sm-6 col-md-6">
                        <input type="text" class="form-control" placeholder="{{__('springs.code')}}" name="spring_databases[{{ $loop->index }}][code]" value="{{$database_link->code}}" />
                        <input type="text" class="form-control" placeholder="{{__('springs.url')}}" name="spring_databases[{{ $loop->index }}][url]" value="{{$database_link->url}}" />
                    </div>
                    <!--<div class="input-group-btn">
                        <button class="btn database-btn-success" type="button"><i class="glyphicon glyphicon-plus"></i>{{__('springs.add')}}</button>
                    </div>-->
                    @endforeach
                </div>
            </div>
        </div>

        <div class="form-row col-xs-12 col-sm-12 col-md-12">
            <div class="pull-left col-xs-6 col-sm-6 col-md-6">
                <strong>{{ __('springs.spring_classification') }}</strong>
                <select name="classification" class="form-control">
                    <option value="rheocrene"
                            @if($spring->classification == 'rheocrene') selected @endif>
                        {{ __('springs.rheocrene') }}</option>
                    <option value="hillslope_spring"
                            @if($spring->classification == 'hillslope_spring') selected @endif>
                        {{ __('springs.hillslope_spring') }}</option>
                    <option value="limnocrene"
                            @if($spring->classification == 'limnocrene') selected @endif>
                        {{ __('springs.limnocrene') }}</option>
                    <option value="helocrene"
                            @if($spring->classification == 'helocrene') selected @endif>
                        {{ __('springs.helocrene') }}</option>
                    <option value="cave_spring"
                            @if($spring->classification == 'cave_spring') selected @endif>
                        {{ __('springs.cave_spring') }}</option>
                    <option value="hypocrene"
                            @if($spring->classification == 'hypocrene') selected @endif>
                        {{ __('springs.hypocrene') }}</option>
                    <option value="captured_spring"
                            @if($spring->classification == 'captured_spring') selected @endif>
                        {{ __('springs.captured_spring') }}</option>
                    <option value="karst_spring"
                            @if($spring->classification == 'karst_spring') selected @endif>
                        {{ __('springs.karst_spring') }}</option>
                </select>
            </div>
            <div class="pull-right col-xs-6 col-sm-6 col-md-6">
                <label><strong>{{ __('springs.groundwater_body') }}</strong></label>
                <input type="text" name="groundwater_body" class="form-control" placeholder="" value="{{$spring->groundwater_body}}">
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <label><strong>{{ __('springs.geology') }}</strong></label>
                <textarea class="form-control" style="height:150px" name="geology" placeholder="">{{$spring->geology}}</textarea>
            </div>
        </div>

        <div class="form-row">
            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <label><strong>{{ __('springs.ownership') }}</strong></label>
                    <select name="ownership" class="form-control">
                        <option value="private_property"
                                @if($spring->ownership == 'private_property') selected @endif>
                            {{ __('springs.private_property') }}
                        </option>
                        <option value="state_property"
                                @if($spring->ownership == 'state_property') selected @endif>
                            {{ __('springs.state_property') }}
                        </option>
                        <option value="municipal_property"
                                @if($spring->ownership == 'municipal_property') selected @endif>
                            {{ __('springs.municipal_property') }}
                        </option>
                    </select>
                </div>
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


        <div>
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <div class="pull-left">
                        <button type="submit" class="btn btn-primary">{{ __('springs.edit') }}</button>
                        <a class="btn" href="{{ route('springs.index') }}">{{ __('springs.cancel') }}</a>
                    </div>
                </div>
            </div>
        </div>

    </form>

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
    <script src="/js/mapInput.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initMap" async defer></script>
@endsection

@endauth
