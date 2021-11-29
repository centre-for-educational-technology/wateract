<template>

    <l-map ref="openstreetmap" style="width:100%;height:100%;z-index:0;"
           :center="$parent.mapCenter"
           @update:zoom="openStreetZoomUpdate"
           :zoom="$parent.openStreetMapZoom"
           :tms="true"
           :continuousWorld="true"
           @update:center="$parent.mapCenterUpdate"
           :options="mapOptions"
           @ready="openStreetOnReady"
           @locationfound="openStreetOnLocationFound"
           @click="updateLocation"
    >

        <l-control>
            <svg @click="openStreetShowLocation" class="h-8 w-8 p-1 bg-white border-2 rounded cursor-pointer hover:bg-gray-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd">
                    <title>{{ $t('springs.pan_to_current_location') }}</title>
                </path>
            </svg>
        </l-control>

        <!--<l-control-fullscreen />-->

        <l-control position="bottomright" v-if="ee_spring && !fullscreen">
            <div class="bg-white p-1 border-2 rounded cursor-pointer hover:bg-gray-100" @click="showMaaAmetMap">{{ $t('springs.estonia_map') }}</div>
        </l-control>

        <l-tile-layer
            :url="layer.url"
            :attribution="layer.attribution"
        />

        <l-marker
            :lat-lng="currentPosition"
            :icon="currentPositionIcon"
        ></l-marker>

        <l-marker v-if="this.springLocation"
                  :lat-lng="springLocation"
                  :icon="springLocationIcon"
        ></l-marker>

        <l-marker-cluster :options="openStreetClusterOptions">
            <l-marker v-for="(marker, index) in leafletmarkers"
                      :key="index"
                      :lat-lng="marker.position">
                <l-popup>
                    <div class="pb-2"><a class="underline text-blue-700" :href="'/springs/'+marker.id+'/'">{{marker.name || 'Unnamed'}}</a></div>
                    <div>{{ $t('springs.spring_code') }}: {{marker.id}} <br />{{ $t('springs.status') }}: {{ $t('springs.status_options.'+marker.status) }}</div>
                </l-popup>
            </l-marker>
        </l-marker-cluster>

    </l-map>


</template>
<script>
import { latLngBounds, latLng, icon } from "leaflet";
import { LMap, LTileLayer, LMarker, LIcon, LControlZoom, LControl, LPopup, LControlLayers } from 'vue2-leaflet';
import "proj4leaflet";
import Vue2LeafletMarkerCluster from 'vue2-leaflet-markercluster'
import { Icon } from 'leaflet';
import { GestureHandling } from "leaflet-gesture-handling";
import "leaflet-gesture-handling/dist/leaflet-gesture-handling.css";
import "leaflet.markercluster/dist/MarkerCluster.css";
import "leaflet.markercluster/dist/MarkerCluster.Default.css";
import { springLocationIcon } from '../../../constants.js';
import LControlFullscreen from 'vue2-leaflet-fullscreen';

delete Icon.Default.prototype._getIconUrl;
Icon.Default.mergeOptions({
    iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
    iconUrl: require('leaflet/dist/images/marker-icon.png'),
    shadowUrl: require('leaflet/dist/images/marker-shadow.png'),
});

let redDotSvgString = '<svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="40" stroke="black" stroke-width="10" fill="red"/></svg>';
let redDotIconUrl = encodeURI("data:image/svg+xml," + redDotSvgString).replace('#','%23');

let openstreet_layers = [
    {
        url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
    }
];

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
        LControlFullscreen,
    },
    props: ['springs', 'spring', 'view'],
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
        let springLocation = {lat: null, lng: null};
        if (this.spring) {
            springLocation = {lat: this.spring.latitude, lng: this.spring.longitude}
        }
        let ee_spring = true;
        if (this.spring && this.spring.country !== 'EE') {
            ee_spring = false;
        }
        return {
            mapOptions: {
                zoomSnap: 1,
                gestureHandling:true
            },
            openStreetClusterOptions: {
                disableClusteringAtZoom: 15,
                maxClusterRadius: 70,
            },

            openStreetMapZoom: 7,
            openStreetMapCenter: latLng(58.379, 24.554),
            ee_spring: ee_spring,

            currentPosition: {lat: null, lng: null},
            currentPositionIcon: icon({
                iconUrl: redDotIconUrl,
                iconSize: [16, 16],
                iconAnchor: [8, 16]
            }),

            springLocation: springLocation,
            springLocationIcon: springLocationIcon,

            layerIndex: 0,
            leafletmarkers: leafletmarkers,
            layers: openstreet_layers,
            fullscreen: false,

        }
    },
    methods: {
        showWorldMap() {
            this.$parent.showWorldMap();
        },
        showMaaAmetMap() {
            this.$parent.showMaaAmetMap();
        },
        openStreetOnReady(mapObject) {
            this.openStreetMapObject = mapObject;
            this.openStreetMapObject.setView(this.$parent.mapCenter, this.$parent.openStreetMapZoom);
            if (this.view === 'show') {
                let openStreetMapCenter = latLng(this.spring.latitude, this.spring.longitude);
                this.openStreetMapObject.setView(openStreetMapCenter, 15);
            }
        },
        openStreetShowLocation() {
            this.openStreetMapObject.locate();
        },
        openStreetOnLocationFound(location) {
            this.$parent.mapCenterUpdate(location.latlng);
            this.currentPosition= location.latlng;
            //this.openStreetMapObject.setView(location.latlng, 9);
            //this.leafletMapObject.setView(location.latlng, 9);
        },
        openStreetZoomUpdate(zoom) {
            this.$parent.openStreetMapZoomUpdate(zoom);
        },
        openStreetCenterUpdate(center) {
            //this.$parent.openStreetMapCenterUpdate(center);
        },
        openStreetFullscreenChanged(fullscreen) {
            if (this.openStreetMapObject.isFullscreen()) {
                this.fullscreen = true;
            } else {
                this.fullscreen = false;
            }
        },
        updateLocation(location) {
            if (this.view === 'create' || this.view === 'edit') {
                let latitude = Number(location.latlng.lat);
                let longitude = Number(location.latlng.lng);
                if (latitude && longitude) {
                    this.springLocation = {lat: latitude, lng: longitude};
                    this.$emit('changeLocation', location);
                }
            }
        },
        getExistingSprings() {
            let params = {};
            if (this.spring) {
                params = {
                    'spring_id': this.spring.id
                }
            }
            axios.get('/getSprings', { params }).then(response => {
                let springs = response.data;
                let markers = [];
                _.forEach(springs, function(spring) {
                    markers.push({
                        id: spring.code,
                        name: spring.name,
                        status: spring.status,
                        position: latLng(spring.latitude, spring.longitude),
                    });
                });
                this.leafletmarkers = markers;
            })
        }
    },
    computed: {
        layer () {
            return this.layers[this.layerIndex]
        }
    },
    created: function(){
        if (this.spring || this.view === 'create') {
            this.getExistingSprings();
        }
    }
}
</script>
