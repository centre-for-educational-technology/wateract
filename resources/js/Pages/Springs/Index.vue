<style>
    @import "leaflet.markercluster/dist/MarkerCluster.css";
    @import "leaflet.markercluster/dist/MarkerCluster.Default.css";
</style>

<template>
    <app-layout>
        <template #header>
            <div class="flex w-full">
                <h2 class="w-3/4 font-semibold text-xl text-gray-800 leading-tight">
                    {{ $t('springs.observations_and_database') }}
                </h2>
                <div class="w-1/4" v-if="$page.user">
                    <a href="springs/create" class="float-right border text-xs font-semibold px-4 py-1 leading-normal">
                        {{ $t('springs.create_new_spring') }}</a>
                </div>
            </div>
        </template>

        <div class="py-6">

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                    <div class="p-4">
                       <h3 class="text-xl">{{ $t('springs.browse_springs') }}</h3>
                        <div class="flex">
                            <jet-input class="w-1/4 mr-3" type="text" v-model="search_name" name="searchbox" :placeholder="$t('springs.search_spring_name')" />
                            <select id="classification" v-model="search_classification"
                                    class="w-1/4 block bg-white border border-gray-300 hover:border-gray-500 mr-3 px-2 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">{{ $t('springs.classification') }}</option>
                                <option v-for='data in classifications' :value='data.id'> {{ $t( data.name ) }}</option>
                            </select>
                            <select id="country" v-model="search_country" class="w-1/4 block bg-white border border-gray-300 hover:border-gray-500 mr-3 px-2 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">{{ $t('springs.country') }}</option>
                                <option value="EE">{{ $t('springs.countries.ee') }}</option>
                                <option value="LV">{{ $t('springs.countries.lv') }}</option>
                            </select>
                            <button class="w-1/4 items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150"
                                v-on:click="updateMarkers">{{ $t('springs.search') }}</button>
                        </div>
                        <small v-on:click="initializeSearch" class="cursor-pointer underline">{{ $t('springs.see_all_springs') }}</small>
                    </div>

                    <div class="z-depth-1-half map-container" style="height:500px;">
                        <GmapMap ref="map" v-if="googlemap"
                            :center="{lat:58.279, lng:26.054}"
                            :zoom="7"
                            map-type-id="terrain"
                            style="width: 100%; height: 100%"
                        >
                            <GmapCluster>
                            <GmapMarker
                                :key="index"
                                v-for="(location, index) in markers"
                                :position="location.position"
                                :clickable="true"
                                :icon="location.icon"
                                @click="toggleInfoWindow(location, location.id)"
                            />
                            <GmapInfoWindow
                                :options="infoOptions"
                                :position="infoWindowPos"
                                :opened="infoWinOpen"
                                @closeclick="infoWinOpen=false"
                            >
                                <div v-html="infoContent"></div>
                            </GmapInfoWindow>
                            </GmapCluster>
                        </GmapMap>
                        <l-map ref="leafletMap" style="width:100%;height:100%"
                               :minZoom="3"
                               :maxZoom="14"
                               :zoom="13"
                               :center="leafletCenter"
                               :tms="tms"
                               :crs="crs"
                               :continuousWorld="true"
                               v-if="leafletmap"
                               :bounds="bounds"
                               :options="{zoomControl: false}"
                        >
                            <l-tile-layer
                                v-for="layer in tilelayers"
                                :key="layer.name"
                                :url="layer.url"
                                :zIndex="layer.zindex"
                                :attribution="attribution"
                                :tms="tms"
                                :maxZoom="layer.maxzoom"
                                :worldCopyJump="true"
                            />
                            <l-marker-cluster>
                            <l-marker v-for="(marker, index) in leafletmarkers"
                                      :key="index"
                                      :lat-lng="marker.position">
                                <l-popup>
                                    <div class="pb-2"><a class="underline text-blue-700" :href="'springs/'+marker.id+'/'">{{marker.name || 'Unnamed'}}</a></div>
                                    <div>{{ $t('springs.wateract_code') }}: {{marker.id}} <br />{{ $t('springs.status') }}: {{marker.status}}</div>
                                </l-popup>
                            </l-marker>
                            </l-marker-cluster>
                            <l-control-zoom position="bottomright"  ></l-control-zoom>

                        </l-map>
                        <div class="block">
                            <button class="border float-right" v-if="googlemap" v-on:click="googlemap=false;leafletmap = true;">Maa-amet Map</button>
                            <button class="border float-right" v-if="leafletmap" v-on:click="leafletmap=false;googlemap = true;">Google Map</button>
                        </div>
                    </div>

                    <div v-show="featured" v-if="featured_springs.length > 0">
                        <ul class="list-reset flex border-b">
                            <li class="-mb-px mr-1 ml-1">
                                <jet-label class="cursor-pointer bg-white inline-block border-l border-t border-r rounded-t py-2 px-4 font-semibold">
                                    {{  $t('springs.featured_springs') }}</jet-label>
                            </li>
                            <li class="mr-1" v-on:click="featured=false;newest = true;" v-if="newest_springs.length>0">
                                <jet-label class="cursor-pointer bg-white inline-block py-2 px-4 hover:text-gray-800" >
                                    {{ $t('springs.newest_springs') }}</jet-label>
                            </li>
                        </ul>
                        <div class="grid grid-cols-4 gap-4 px-4 py-2 my-2" v-show="featured">
                            <div class="border mx-1" v-for="featured_spring in featured_springs">
                                <spring-view :spring="featured_spring"></spring-view>
                            </div>
                        </div>
                    </div>

                    <div v-show="newest" v-if="newest_springs.length > 0">
                        <ul class="list-reset flex border-b">
                            <li class="mr-1 ml-1" v-on:click="featured=true;newest = false;" v-if="featured_springs.length>0">
                                <jet-label class="cursor-pointer bg-white inline-block py-2 px-4 hover:text-gray-800">
                                    {{  $t('springs.featured_springs') }}</jet-label>
                            </li>
                            <li class="-mb-px mr-1">
                                <jet-label class="cursor-pointer bg-white inline-block py-2 px-4 rounded-t border-l border-t border-r font-semibold" >
                                    {{ $t('springs.newest_springs') }}</jet-label>
                            </li>
                        </ul>
                        <div>
                            <div class="grid grid-cols-4 gap-4 px-4 py-2 my-2" v-show="newest">
                                <div class="border mx-1" v-for="new_spring in newest_springs">
                                    <spring-view :spring="new_spring"></spring-view>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </app-layout>
</template>
<script>
import AppLayout from './../../Layouts/AppLayout'
import JetInput from "../../Jetstream/Input";
import JetSecondaryButton from './../../Jetstream/SecondaryButton'
import { gmapApi } from 'gmap-vue';
import GmapCluster from 'gmap-vue/dist/components/cluster'
import SpringView from './SpringView'
import JetLabel from "../../Jetstream/Label";

import { CRS, latLngBounds, latLng } from "leaflet";
import L from 'leaflet';
import { LMap, LTileLayer, LMarker, LIcon, LControlZoom, LPopup, LWMSTileLayer, LControlLayers } from 'vue2-leaflet';
import "proj4leaflet";
import proj4 from "proj4";
import Vue2LeafletMarkerCluster from 'vue2-leaflet-markercluster'

var projection = new L.Proj.CRS('EPSG:3301', '+proj=lcc +lat_1=59.33333333333334 +lat_2=58 +lat_0=57.51755393055556 +lon_0=24 +x_0=500000 +y_0=6375000 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs', {
    resolutions: [4000, 2000, 1000, 500, 250, 125, 62.5, 31.25, 15.625, 7.8125, 3.90625, 1.953125, 0.9765625, 0.48828125, 0.244140625, 0.122070313, 0.061035156, 0.030517578, 0.015258789],
    origin: [40500, 5993000],
    bounds: L.bounds([40500, 5993000], [1064500, 7017000])
});

export default {
    components: {
        AppLayout,
        JetInput,
        JetSecondaryButton,
        gmapApi,
        GmapCluster,
        SpringView,
        JetLabel,
        "l-wms-tile-layer": LWMSTileLayer,
        LControlLayers,
        LMap,
        LTileLayer,
        LMarker,
        LIcon,
        LControlZoom,
        LPopup,
        'l-marker-cluster': Vue2LeafletMarkerCluster
    },
    props: ['springs', 'featured_springs', 'newest_springs', 'classifications'],
    data() {
        const mapIcons = {
            'confirmed': 'https://maps.google.com/mapfiles/ms/micons/blue-dot.png',
            'submitted': 'https://maps.google.com/mapfiles/ms/micons/orange-dot.png',
        };
        let markers = [];
        let leafletmarkers = [];
        _.forEach(this.springs, function(spring) {
            markers.push({
                id: spring.code,
                name: spring.name,
                description: spring.description,
                status: spring.status,
                date_build: "",
                position: {lat: spring.latitude, lng: spring.longitude},
                icon: mapIcons[spring.status],
            });
            leafletmarkers.push({
                id: spring.code,
                name: spring.name,
                status: spring.status,
                position: latLng(spring.latitude, spring.longitude),
            });
        });

        return {
            search_name: '',
            search_classification: '',
            search_country: '',
            leafletmap: true,
            googlemap: false,
            crs: projection,
            tms: true,
            leafletCenter: latLng(58.379, 24.554),
            attribution: "<a href='http://www.maaamet.ee'>Maa-amet</a>",
            maaamet_url: 'https://tiles.maaamet.ee/tm/tms/1.0.0/vreljeef/{z}/{x}/{y}.png&ASUTUS=MAAAMET&KESKKOND=LIVE&IS=TMSNAIDE',
            zoom: 5,
            baseUrl: 'http://kaart.maaamet.ee/wms/alus',
            bounds: latLngBounds([
                [60.4349, 29.4338],
                [56.7458, 20.373]
            ]),
            leafletmarkers: leafletmarkers,
            tilelayers: [
                {
                    name: 'reljeef',
                    url: 'https://tiles.maaamet.ee/tm/tms/1.0.0/vreljeef/{z}/{x}/{y}.png&ASUTUS=MAAAMET&KESKKOND=LIVE&IS=TMSNAIDE',
                    zindex: 1,
                    maxzoom: 11,
                },
                {
                    name: 'hybrid',
                    url: 'https://tiles.maaamet.ee/tm/tms/1.0.0/hybriid/{z}/{x}/{y}.png&ASUTUS=MAAAMET&KESKKOND=LIVE&IS=TMSNAIDE',
                    zindex: 2,
                    maxzoom: 11,
                },
                {
                    name: 'pohi',
                    url: 'https://tiles.maaamet.ee/tm/tms/1.0.0/epk_vv/{z}/{x}/{y}.png&ASUTUS=MAAAMET&KESKKOND=LIVE&IS=TMSNAIDE',
                    zindex: 3,
                    maxzoom: 11,
                }

            ],

            featured: this.featured_springs.length>0 ? true: false,
            newest: this.featured_springs.length>0 ? false: true,
            map: null,
            markers: markers,
            infoWinOpen: false,
            infoContent: '',
            infoWindowPos: {
                lat: 0,
                lng: 0
            },
            infoOptions: {
                pixelOffset: {
                    width: 0,
                    height: -35
                }
            },
        }
    },
    mounted() {
            //set bounds of the map
            /*this.$refs.map.$mapPromise.then((map) => {
                map.panTo({lat:58.379, lng:24.554});

            });*/
            /*console.log('mount');
            const layers = {
                'Muinsuskaitsealused allikad' : 'http://allikad.info/kml/Kult_allikad_komb.kmz',
                'Loodusdirektiivi allikaelupaigad' : 'http://allikad.info/kml/LD_allikad_pind1.kmz',
                'Parandkultuuriallikad' : 'http://allikad.info/kml/Par_allikad_punkt1.kmz',
                'Allikate seirejaamad' : 'http://allikad.info/kml/Seire_allikad_punkt1.kmz',
                'Allikalised vaariselupaigad' : 'http://allikad.info/kml/VEP_allikad_pind1.kmz',
                'Looduskaitsealused allikad' : 'http://allikad.info/kml/UOB_allikad_komb.kmz',
                'Urglooduse raamatu allikad' : 'http://allikad.info/kml/Urg_allikad_komb.kmz'
            };
            this.$refs.map.$mapPromise.then((map) => {
                _.forEach(layers, function(layer_file, layer_name) {
                    console.log(layer_file);
                    let options = {
                        map: map,
                        url: layer_file,
                    }
                    let kml = new google.maps.KmlLayer(options)
                });
            })*/
    },
    methods: {
        zoomUpdate(zoom) {
            console.log(zoom);
        },
        toggleInfoWindow: function (marker, idx) {
            this.infoWindowPos = ({
                    lat : marker.position.lat,
                    lng : marker.position.lng,
                }
            );
            this.infoContent = this.getInfoWindowContent(marker);

            //check if its the same marker that was selected if yes toggle
            if (this.currentMidx == idx) {
                this.infoWinOpen = !this.infoWinOpen;
            }
            //if different marker set infowindow to open and reset current marker index
            else {
                this.infoWinOpen = true;
                this.currentMidx = idx;
            }
        },
        getInfoWindowContent: function (marker) {
            var markerName = "Unnamed";
            if (marker.name) {
                markerName = marker.name;
            }
            var markerinfo = '<div>Allikad.info code: '+marker.id+'<br />Status: '+marker.status+'</div>';
            return('<div class="info_window container"> <a class="underline text-blue-700" href="springs/'+marker.id+'/">'+markerName+'</a><br /><br />'+markerinfo+'</div>');
        },
        initializeSearch: function() {
            this.search_name = '';
            this.search_classification = '';
            this.search_country = '';
            this.updateMarkers();
        },
        updateMarkers: function () {
            //get markers based on search params
            let springs = [];
            let markers = [];
            let leafletmarkers = [];
            let params = {
                "name": this.search_name,
                "classification": this.search_classification,
                "country": this.search_country,
            }
            axios.get('/getSprings', { params }).then(response => {
                springs = response.data;
                _.forEach(springs, function(spring) {
                    markers.push({
                        id: spring.code,
                        name: spring.name,
                        status: spring.status,
                        position: {lat: spring.latitude, lng: spring.longitude},
                    });
                    leafletmarkers.push({
                        id: spring.code,
                        name: spring.name,
                        status: spring.status,
                        position: latLng(spring.latitude, spring.longitude),
                    });
                });
                this.markers=markers;
                this.leafletmarkers = leafletmarkers;
            })
        }

    }
}
</script>
