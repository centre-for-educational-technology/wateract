<template>

    <div>

        <div class="z-depth-1-half map-container block" style="height:500px;" v-if="openStreetMap">
            <l-map ref="openstreetmap" style="width:100%;height:100%;z-index:1;"
                   :center="openStreetCenter"
                   @update:zoom="openStreetZoomUpdate"
                   :zoom="openStreetMapZoom"
                   :tms="tms"
                   :continuousWorld="true"
                   @update:center="openStreetCenterUpdate"
                   :options="mapOptions"
                   @ready="openStreetOnReady"
                   @locationfound="openStreetOnLocationFound"
                >

                <l-control>
                    <svg @click="openStreetShowLocation" class="h-8 w-8 p-1 bg-white border-2 rounded cursor-pointer hover:bg-gray-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd">
                            <title>{{ $t('springs.pan_to_current_location') }}</title>
                        </path>
                    </svg>
                </l-control>

                <l-control position="bottomright">
                    <div class="bg-white p-1 border-2 rounded cursor-pointer hover:bg-gray-100" @click="showMaaametMap">{{ $t('springs.estonia_map') }}</div>
                </l-control>

                <l-tile-layer
                    :url="layer.url"
                    :attribution="layer.attribution"
                />

                <l-marker
                    :lat-lng="currentPosition"
                    :icon="currentPositionIcon"
                ></l-marker>

                <l-marker-cluster>
                    <l-marker v-for="(marker, index) in leafletmarkers"
                              :key="index"
                              :lat-lng="marker.position">
                        <l-popup>
                            <div class="pb-2"><a class="underline text-blue-700" :href="'springs/'+marker.id+'/'">{{marker.name || 'Unnamed'}}</a></div>
                            <div>{{ $t('springs.spring_code') }}: {{marker.id}} <br />{{ $t('springs.status') }}: {{ $t('springs.status_options.'+marker.status) }}</div>
                        </l-popup>
                    </l-marker>
                </l-marker-cluster>

            </l-map>
        </div>

        <div class="z-depth-1-half map-container block" style="height:500px;" v-show="maaametMap">
            <l-map ref="leafletMap" style="width:100%;height:100%;z-index:1;"
                   :maxZoom="14"
                   :minZoom="3"
                   :zoom="maaametMapZoom"
                   :center="maaametCenter"
                   :tms="tms"
                   :crs="crs"
                   :continuousWorld="true"
                   :bounds="bounds"
                   @update:zoom="maaametZoomUpdate"
                   @update:center="maaametCenterUpdate"
                   :options="mapOptions"
                   @ready="onReady"
                   @locationfound="onLocationFound"

            >

                <l-control>
                    <svg @click="showLocation" class="h-8 w-8 p-1 bg-white border-2 rounded cursor-pointer hover:bg-gray-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd">
                            <title>{{ $t('springs.pan_to_current_location') }}</title>
                        </path>
                    </svg>
                </l-control>

                <l-control position="bottomright">
                    <div class="bg-white p-1 border-2 rounded cursor-pointer hover:bg-gray-100" @click="showWorldMap">{{ $t('springs.world_map') }}</div>
                </l-control>
                <l-control  position="bottomright">
                    <div class="bg-white p-1 border-2 rounded cursor-pointer hover:bg-gray-100" @click="showOrthoPhoto">{{ $t('springs.orthophoto') }}</div>
                </l-control>
                <l-control position="bottomright">
                    <div class="bg-white p-1 border-2 rounded cursor-pointer hover:bg-gray-100" @click="showReliefMap">{{ $t('springs.relief_map') }}</div>
                </l-control>

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

                <l-marker
                    :lat-lng="currentPosition"
                    :icon="currentPositionIcon"
                ></l-marker>

                <l-marker-cluster>
                    <l-marker v-for="(marker, index) in leafletmarkers"
                              :key="index"
                              :lat-lng="marker.position">
                        <l-popup>
                            <div class="pb-2"><a class="underline text-blue-700" :href="'springs/'+marker.id+'/'">{{marker.name || 'Unnamed'}}</a></div>
                            <div>{{ $t('springs.spring_code') }}: {{marker.id}} <br />{{ $t('springs.status') }}: {{ $t('springs.status_options.'+marker.status) }}</div>
                        </l-popup>
                    </l-marker>
                </l-marker-cluster>

            </l-map>

        </div>

    </div>


</template>
<script>
import { latLngBounds, latLng, icon } from "leaflet";
import L from 'leaflet';
import { LMap, LTileLayer, LMarker, LIcon, LControlZoom, LControl, LPopup, LControlLayers } from 'vue2-leaflet';
import "proj4leaflet";
import Vue2LeafletMarkerCluster from 'vue2-leaflet-markercluster'
import { Icon } from 'leaflet';
import { GestureHandling } from "leaflet-gesture-handling";
import "leaflet-gesture-handling/dist/leaflet-gesture-handling.css";
import "leaflet.markercluster/dist/MarkerCluster.css";
import "leaflet.markercluster/dist/MarkerCluster.Default.css";

delete Icon.Default.prototype._getIconUrl;
Icon.Default.mergeOptions({
    iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
    iconUrl: require('leaflet/dist/images/marker-icon.png'),
    shadowUrl: require('leaflet/dist/images/marker-shadow.png'),
});

let projection = new L.Proj.CRS('EPSG:3301', '+proj=lcc +lat_1=59.33333333333334 +lat_2=58 +lat_0=57.51755393055556 +lon_0=24 +x_0=500000 +y_0=6375000 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs', {
    resolutions: [4000, 2000, 1000, 500, 250, 125, 62.5, 31.25, 15.625, 7.8125, 3.90625, 1.953125, 0.9765625, 0.48828125, 0.244140625, 0.122070313, 0.061035156, 0.030517578, 0.015258789],
    origin: [40500, 5993000],
    bounds: L.bounds([40500, 5993000], [1064500, 7017000])
});

let redDotSvgString = '<svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="40" stroke="black" stroke-width="10" fill="red"/></svg>';
let redDotIconUrl = encodeURI("data:image/svg+xml," + redDotSvgString).replace('#','%23');

let relief_layers = [
    {
        name: 'reljeef',
        url: 'https://tiles.maaamet.ee/tm/tms/1.0.0/vreljeef/{z}/{x}/{y}.png&ASUTUS=ALLIKAD',
        zindex: 1,
        maxzoom: 11,
    },
    {
        name: 'hybrid',
        url: 'https://tiles.maaamet.ee/tm/tms/1.0.0/hybriid/{z}/{x}/{y}.png&ASUTUS=ALLIKAD',
        zindex: 3,
        maxzoom: 11,
    },
    {
        name: 'pohi',
        url: 'https://tiles.maaamet.ee/tm/tms/1.0.0/epk_vv/{z}/{x}/{y}.png&ASUTUS=ALLIKAD',
        zindex: 2,
        maxzoom: 11,
    },
];

let orthophoto_layers = [
    {
        name: 'hybrid',
        url: 'https://tiles.maaamet.ee/tm/tms/1.0.0/hybriid/{z}/{x}/{y}.png&ASUTUS=ALLIKAD',
        zindex: 2,
        maxzoom: 11,
    },
    {
        name: 'foto',
        url: 'https://tiles.maaamet.ee/tm/tms/1.0.0/foto/{z}/{x}/{y}.png&ASUTUS=ALLIKAD',
        zindex: 1,
        maxzoom: 11,
    },
];

let openstreet_layers = [
    {
        url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
    }
];

let crs2 = L.CRS.EPSG3857;
let world_bounds = latLngBounds([
    [-90.0,-180.0],
    [90.0,180.0]
]);

export default {
    components: {
        latLngBounds,
        LControlLayers,
        LMap,
        LTileLayer,
        LMarker,
        LIcon,
        LControlZoom,
        LControl,
        LPopup,
        'l-marker-cluster': Vue2LeafletMarkerCluster,
        GestureHandling,
    },
    props: ['springs'],
    data() {
        let leafletmarkers = [];
        _.forEach(this.springs, function(spring) {
            leafletmarkers.push({
                id: spring.code,
                name: spring.name,
                status: spring.status,
                position: latLng(spring.latitude, spring.longitude),
            });
        });
        return {
            mapOptions: {
                zoomSnap: 0.5,
                gestureHandling:true
            },

            openStreetMap: false,
            openStreetMapZoom: 7,
            openStreetCenter: latLng(58.379, 24.554),

            maaametMap: true,
            maaametMapZoom: 13,
            maaametCenter: latLng(58.379, 24.554),

            currentPosition: {lat: null, lng: null},
            currentPositionIcon: icon({
                iconUrl: redDotIconUrl,
                iconSize: [16, 16],
                iconAnchor: [8, 16]
            }),

            layerIndex: 0,
            markers: [],
            leafletmarkers: leafletmarkers,
            latitude: 58.279,
            longitude: 26.054,
            crs: projection,
            crs2: L.CRS.EPSG4326,
            tms: true,
            //url: 'http://{s}.google.com/vt/lyrs=p&x={x}&y={y}&z={z}',
            center: [58.779, 25.054],
            attribution: "<a href='http://www.maaamet.ee'>Maa-amet</a>",
            bounds: latLngBounds([
                [60.4349, 29.4338],
                [56.7458, 20.373]
            ]),
            layers: openstreet_layers,
            tilelayers: relief_layers,

        }
    },
    methods: {
        showReliefMap() {
            this.tilelayers = relief_layers;
        },
        showOrthoPhoto() {
            this.tilelayers = orthophoto_layers;
        },
        showWorldMap() {
            this.maaametMap = false;
            this.openStreetMap = true;
        },
        showMaaametMap() {
            this.openStreetMap = false;
            this.maaametMap = true;
        },
        openStreetOnReady(mapObject) {
            this.openStreetMapObject = mapObject;
        },
        openStreetShowLocation() {
            this.openStreetMapObject.locate();
        },
        openStreetOnLocationFound(location) {
            this.currentPosition= location.latlng;
            this.openStreetMapObject.setView(location.latlng, 9);
            this.leafletMapObject.setView(location.latlng, 9);
        },
        onReady(mapObject) {
            this.leafletMapObject = mapObject;
        },
        showLocation() {
            this.leafletMapObject.locate();
        },
        onLocationFound(location) {
            this.currentPosition= location.latlng;
            this.leafletMapObject.setView(location.latlng, 9);
        },
        maaametZoomUpdate(zoom) {
            this.openStreetMapZoom = zoom + 4;
        },
        maaametCenterUpdate(center) {
            let new_center_latitude= center.lat;
            let new_center_longitude = center.lng;
            let center_latitude= this.openStreetCenter.lat;
            let center_longitude = this.openStreetCenter.lng;
            if (new_center_latitude !== center_latitude || new_center_longitude !== center_longitude) {
                this.openStreetCenter = center;
            }
        },
        openStreetZoomUpdate(zoom) {
            this.maaametMapZoom = zoom - 4;
        },
        openStreetCenterUpdate(center) {
            let new_center_latitude= center.lat;
            let new_center_longitude = center.lng;
            let center_latitude= this.maaametCenter.lat;
            let center_longitude = this.maaametCenter.lng;
            if (new_center_latitude !== center_latitude || new_center_longitude !== center_longitude) {
                this.maaametCenter = center;
            }
        },
    },
    computed: {
        layer () {
            return this.layers[this.layerIndex]
        }
    },
}
</script>
