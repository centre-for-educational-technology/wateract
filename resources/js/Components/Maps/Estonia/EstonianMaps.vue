<template>

    <div class="h-500 lg:h-600 2xl:h-700">

        <div class="z-depth-1-half map-container block h-full" >

            <OpenStreetMap v-if="openStreetMap" :springs="springs" :spring="spring"></OpenStreetMap>

            <MaaAmetTileMap v-if="maaAmetTileMap" :springs="springs" :spring="spring"></MaaAmetTileMap>

            <MaaAmetWmsMap v-if="maaAmetWmsMap" :springs="springs" :spring="spring"></MaaAmetWmsMap>

        </div>

    </div>

</template>

<script>
import OpenStreetMap from "./OpenStreetMap";
import MaaAmetTileMap from "./MaaAmetTileMap";
import MaaAmetWmsMap from "./MaaAmetWmsMap";
import { latLngBounds, latLng, icon } from "leaflet";
import { LMap, LTileLayer, LMarker, LIcon, LControlZoom, LControl, LPopup, LControlLayers } from 'vue2-leaflet';
import "proj4leaflet";
import Vue2LeafletMarkerCluster from 'vue2-leaflet-markercluster'
import { GestureHandling } from "leaflet-gesture-handling";
import "leaflet-gesture-handling/dist/leaflet-gesture-handling.css";
import "leaflet.markercluster/dist/MarkerCluster.css";
import "leaflet.markercluster/dist/MarkerCluster.Default.css";
import { relief_shaded_layers, relief_layers, orthophoto_layers, springLocationIcon } from '../../../constants.js';
import LControlFullscreen from 'vue2-leaflet-fullscreen';

export default {
    components: {
        OpenStreetMap,
        MaaAmetTileMap,
        MaaAmetWmsMap,
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

        let map = 'maaamet';
        let ee_spring = true;
        if (this.spring && this.spring.country !== 'EE') {
            map = 'openstreet';
            ee_spring = false;
        }

        return {

            cacheOpenStreetMapZoom: 7,
            cacheOpenStreetMapCenter: latLng(58.379, 24.554),
            cacheMaaAmetMapZoom: 3,
            cacheMaaAmetCenter: latLng(58.379, 24.554),

            openStreetMap: map !== 'maaamet',
            openStreetMapZoom: 7,
            openStreetMapCenter: latLng(58.379, 24.554),

            maaAmetMap: map !== 'openstreet',
            maaAmetMapZoom: 3,
            maaAmetMapCenter: latLng(58.379, 24.554),
            mapCenter: latLng(58.379, 24.554),

            maaAmetTileMap: true,
            maaAmetWmsMap: false,
            maaAmetMapType: 'relief',       // relief, relief_shaded, orthophoto

            ee_spring: ee_spring,

            currentPosition: {lat: null, lng: null},

            springLocation: springLocation,
            springLocationIcon: springLocationIcon,

            layerIndex: 0,
            leafletmarkers: leafletmarkers,
            tms: true,
            attribution: "<a href='http://www.maaamet.ee'>Maa-amet</a>",
            bounds: latLngBounds([
                [60.4349, 29.4338],
                [56.7458, 20.373]
            ]),
            fullscreen: false,

        }
    },
    methods: {
        showWorldMap() {
            this.maaAmetTileMap = false;
            this.maaAmetWmsMap = false;
            this.openStreetMap = true;
            this.openStreetMapZoom = this.cacheOpenStreetMapZoom;
            this.openStreetMapCenter = this.cacheOpenStreetMapCenter;
        },
        showMaaAmetMap() {
            this.openStreetMap = false;
            if (this.maaAmetMapZoom > 13) {
                this.maaAmetTileMap = false;
                this.maaAmetWmsMap = true;
            } else {
                this.maaAmetTileMap = true;
                this.maaAmetWmsMap = false;
            }
            this.maaAmetMapZoom = this.cacheMaaAmetMapZoom;
            this.maaAmetMapCenter = this.cacheMaaAmetMapCenter;
        },
        openStreetShowLocation() {
            this.openStreetMapObject.locate();
        },
        openStreetOnLocationFound(location) {
            this.currentPosition= location.latlng;
            this.openStreetMapObject.setView(location.latlng, 9);
            this.leafletMapObject.setView(location.latlng, 9);
        },
        showLocation() {
            this.leafletMapObject.locate();
        },
        onLocationFound(location) {
            this.currentPosition= location.latlng;
            this.leafletMapObject.setView(location.latlng, 9);
        },
        maaAmetMapTypeUpdate(type) {
            this.maaAmetMapType = type;
        },
        maaametZoomUpdate(zoom) {
            console.log('update maaamet zoom: '+zoom);
            this.maaAmetMapZoom = zoom;
            if (zoom > 13) {
                this.maaAmetTileMap = false;
                this.maaAmetWmsMap = true;
            } else {
                this.maaAmetTileMap = true;
                this.maaAmetWmsMap = false;
            }
            let new_openstreet_zoom = zoom + 4;
            this.cacheMaaAmetMapZoom = zoom;
            if ( this.cacheOpenStreetMapZoom !== new_openstreet_zoom ) {
                this.cacheOpenStreetMapZoom = new_openstreet_zoom;
            }
        },
        openStreetMapZoomUpdate(zoom) {
            let new_maaamet_zoom = zoom - 4;
            this.cacheOpenStreetMapZoom = zoom;
            if (this.cacheMaaAmetMapZoom !== new_maaamet_zoom) {
                this.cacheMaaAmetMapZoom = new_maaamet_zoom;
            }
        },
        mapCenterUpdate(center) {
            this.mapCenter = center;
        },
        maaametFullscreenChanged(fullscreen) {
            console.log(fullscreen);
            console.log("muudatus");
            /*if (this.leafletMapObject.isFullscreen()) {
                this.fullscreen = true;
            } else {
                this.fullscreen = false;
            }*/
        },
        fullscreenUpdate(map) {
            console.log(map.isFullscreen());
            console.log("muudatus");
            /*if (this.openStreetMapObject.isFullscreen()) {
                this.fullscreen = true;
            } else {
                this.fullscreen = false;
            }*/
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
