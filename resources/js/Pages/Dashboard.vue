<style>
    @import "leaflet.markercluster/dist/MarkerCluster.css";
    @import "leaflet.markercluster/dist/MarkerCluster.Default.css";
</style>

<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <template #title>
            My Springs
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                    <div class="z-depth-1-half map-container w-full" style="height:410px;">
                        <GmapMap ref="map" v-if="googlemap"
                                 :center="{lat:58.279, lng:26.054}"
                                 :zoom="7"
                                 map-type-id="terrain"
                                 style="width: 100%; height: 100%"
                        >
                            <GmapMarker
                                :key="index"
                                v-for="(location, index) in markers"
                                :position="location.position"
                                :clickable="true"
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
                        </GmapMap>
                        <l-map ref="leafletMap" style="width:100%;height:100%"
                               :minZoom="3"
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
                                        <div class="pb-2"><a class="underline text-blue-700" :href="'springs/'+marker.id+'/'">{{marker.name}}</a></div>
                                        <div>Allikad.info code: {{marker.id}} <br /> Status: {{marker.status}}</div>
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

                    <div class="m-4">
                        <h3 class="text-xl">My springs</h3>
                        <data-table
                                :columns="columns"
                                :url="'/myspringsview'"
                                framework="tailwind"
                                order-by="created_at"
                                order-dir="desc"
                                :pagination="pagination"
                                :classes="classes">
                            <span slot="filters" slot-scope="{ tableData, perPage }"></span>
                        </data-table>
                    </div>

                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from './../Layouts/AppLayout'
    import { gmapApi } from 'gmap-vue';

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
            gmapApi,
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
        props: ['springs'],
        data() {
            var markers = [];
            let leafletmarkers = [];
            _.forEach(this.springs, function(spring) {
                markers.push({
                    code: spring.code,
                    name: spring.name,
                    description: spring.description,
                    date_build: "",
                    position: {lat: spring.latitude, lng: spring.longitude}
                });
                leafletmarkers.push({
                    id: spring.code,
                    name: spring.name,
                    status: spring.status,
                    position: latLng(spring.latitude, spring.longitude),
                });
            });
            return {
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
                    }

                ],


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
                columns: [
                    {
                        label: 'Spring name',
                        name: 'name',
                        orderable: true,
                    },
                    {
                        label: 'Location',
                        name: 'country',
                        orderable: true,
                        transform: ({data}) => `${data['country']}, ${data['county']}, ${data['settlement']}`,
                    },
                    {
                        label: 'Date & Time',
                        name: 'created_at',
                        orderable: true,
                        transform: ({data, name}) => `${moment(data[name]).format("DD.MM.YYYY H:mm")}`,
                    },
                    {
                        label: 'Allikad.info code',
                        name: 'code',
                        orderable: true,
                    },
                    {
                        label: 'Status',
                        name: 'status',
                        orderable: true,
                    },
                ],
                pagination: {
                    'size': 'small',
                },
                classes: {
                    "td": {
                        'py-4': true,
                        'px-6': true,
                        'border-b': true,
                        'border-grey-light': true,
                        'text-gray-light': true,
                    },
                    "th": {
                        'text-gray-dark': true,
                        'border-gray': true,
                        'border-b-2': true,
                        'border-t-2': true,
                        'border-gray-200': true,
                        'py-3': true,
                        'px-4': true,
                        'bg-gray-100': true,
                        'text-left': true,
                        'text-xs': true,
                        'font-semibold': true,
                        'uppercase': true,
                    },
                }
            }
        },
        methods: {
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
                return('<div class="info_window container"> <a href="springs/'+marker.code+'/">'+markerName+'</a></div>');
            },

        }
    }
</script>
