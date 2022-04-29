<template>

    <div class="h-500 lg:h-600 2xl:h-700">

        <div class="z-depth-1-half map-container block h-full">
            <l-map ref="map" style="width:100%;height:100%;z-index:0;"
                   :center="this.mapCenter"
                   :zoom="this.mapZoom"
                   :tms="this.tms"
                   :continuousWorld="true"
                   :options="this.mapOptions"
                   @ready="this.mapOnReady"
                   @locationfound="this.mapOnLocationFound"
                   @click="updateLocation"
            >

                <l-control>
                    <svg v-if="!this.liveLocation" @click="this.showLocation" class="h-8 w-8 p-1 bg-white border-2 rounded cursor-pointer hover:bg-gray-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd">
                            <title>{{ $t('springs.pan_to_current_location') }}</title>
                        </path>
                    </svg>
                    <svg v-if="this.liveLocation" @click="this.stopLocation" class="h-8 w-8 p-1 bg-white border-2 rounded cursor-pointer hover:bg-gray-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd">
                        </path>
                        <line x1='1' y1='20' x2='20' y2='1' stroke-width='1' stroke='#0B2437'/>
                    </svg>
                </l-control>

                <!--<l-control-fullscreen />-->

                <l-wms-tile-layer
                    :key="this.wmsLayer.name"
                    :base-url="this.lvMap"
                    :visible="this.wmsLayer.visible"
                    :name="this.wmsLayer.name"
                    :attribution="this.wmsLayer.attribution"
                    :transparent="false"
                    format="image/png"
                    layer-type="base">
                </l-wms-tile-layer>

                <l-marker
                    :lat-lng="this.currentPosition"
                    :icon="this.currentPositionIcon"
                ></l-marker>

                <l-marker v-if="this.springLocation"
                          :lat-lng="this.springLocation"
                          :icon="this.springLocationIcon"
                ></l-marker>

                <l-marker-cluster :options="this.mapClusterOptions">
                    <l-marker v-for="(marker, index) in this.mapMarkers"
                              :key="index"
                              :lat-lng="marker.position"
                              :icon="marker.icon">
                        <l-popup>
                            <div class="pb-2"><a class="underline text-blue-700" :href="'/springs/'+marker.id+'/'">{{marker.name || 'Unnamed'}}</a></div>
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
import { LMap, LTileLayer, LWMSTileLayer, LMarker, LIcon, LControlZoom, LControl, LPopup, LControlLayers } from 'vue2-leaflet';
import "proj4leaflet";
import Vue2LeafletMarkerCluster from 'vue2-leaflet-markercluster'
import { Icon } from 'leaflet';
import { GestureHandling } from "leaflet-gesture-handling";
import "leaflet-gesture-handling/dist/leaflet-gesture-handling.css";
import "leaflet.markercluster/dist/MarkerCluster.css";
import "leaflet.markercluster/dist/MarkerCluster.Default.css";
import { springLocationIcon, confirmedSpringIcon, submittedSpringIcon, notASpringIcon, needsAttentionSpringIcon } from '../../constants.js';
import LControlFullscreen from 'vue2-leaflet-fullscreen';

delete Icon.Default.prototype._getIconUrl;
Icon.Default.mergeOptions({
    iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
    iconUrl: require('leaflet/dist/images/marker-icon.png'),
    shadowUrl: require('leaflet/dist/images/marker-shadow.png'),
});

let redDotSvgString = '<svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="40" stroke="black" stroke-width="10" fill="red"/></svg>';
let redDotIconUrl = encodeURI("data:image/svg+xml," + redDotSvgString).replace('#','%23');

export default {
    components: {
        latLngBounds,
        LControlLayers,
        LMap,
        LTileLayer,
        "l-wms-tile-layer": LWMSTileLayer,
        LMarker,
        LIcon,
        LControlZoom,
        LControl,
        LPopup,
        'l-marker-cluster': Vue2LeafletMarkerCluster,
        GestureHandling,
        LControlFullscreen,
    },
    props: ['springs', 'spring', 'view', 'spring_location'],
    data() {
        let mapMarkers = [];
        _.forEach(this.springs, function(spring) {
            mapMarkers.push({
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
        if (this.spring_location) {
            springLocation = this.spring_location;
        }

        let lv_map_api_key = process.env.MIX_KARTES_LV_API_KEY;

        return {
            mapOptions: {
                tap: this.mapTap(),
                zoomSnap: 1,
            },

            lvMap: 'https://wms2.kartes.lv/'+lv_map_api_key+'/wgs/15/?SERVICE=WMS&VERSION=1.1.1&REQUEST=GetMap&STYLES=&FORMAT=image%2Fpng&SRS=EPSG%3A3857&',

            wmsLayer: {
                name: 'Latvian Map',
                visible: true,
                format: 'image/png',
                transparent: false,
                attribution: '<a href="https://www.kartes.lv/">Karšu izdevniecība Jāņa sēta</a> &copy; 2017 — 2021',
            },

            mapClusterOptions: {
                disableClusteringAtZoom: 10,
                maxClusterRadius: 70,
            },

            cacheOpenStreetMapZoom: 7,
            cacheOpenStreetCenter: latLng(54.379, 34.554),
            cacheMaaametMapZoom: 3,
            cacheMaaametCenter: latLng(54.379, 34.554),

            openStreetMap: true,
            mapZoom: 7,
            mapCenter: latLng(57.179, 24.554),

            ee_spring: false,

            liveLocation: false,
            currentPosition: {lat: null, lng: null},
            currentPositionIcon: icon({
                iconUrl: redDotIconUrl,
                iconSize: [16, 16],
                iconAnchor: [8, 16]
            }),

            springLocation: springLocation,
            springLocationIcon: springLocationIcon,

            mapMarkers: mapMarkers,
            tms: true,
            bounds: latLngBounds([
                [60.4349, 29.4338],
                [56.7458, 20.373]
            ]),
            fullscreen: false,

        }
    },
    methods: {
        mapTap() {
            if ( L.Browser.safari && !L.Browser.mobile) {
                return false;
            }
            return true;
        },
        mapOnReady(mapObject) {
            this.openStreetMapObject = mapObject;
            if (this.spring) {
                let mapCenter = latLng(this.spring.latitude, this.spring.longitude);
                this.openStreetMapObject.setView(mapCenter, 15);
            }
        },
        showLocation() {
            this.openStreetMapObject.locate({setView: true, watch: true, enableHighAccuracy:true, maxZoom: 13});
            this.liveLocation = true;
        },
        stopLocation() {
            this.openStreetMapObject.stopLocate();
            this.liveLocation = false;
        },
        mapOnLocationFound(location) {
            this.currentPosition= location.latlng;
            this.openStreetMapObject.setView(location.latlng, 9);
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
                    let springIcon = confirmedSpringIcon;
                    let springStatus = spring.status;
                    if (spring.not_a_spring) {
                        springIcon = notASpringIcon;
                        springStatus = "not_a_spring";
                    } else if (spring.needs_attention) {
                        springIcon = needsAttentionSpringIcon;
                        springStatus = "needs_attention";
                    } else if (spring.status === 'submitted') {
                        springIcon = submittedSpringIcon;
                    }
                    markers.push({
                        id: spring.code,
                        name: spring.name,
                        status: springStatus,
                        position: latLng(spring.latitude, spring.longitude),
                        icon: springIcon,
                    });
                });
                this.mapMarkers = markers;
            })
        },
    },
    created: function() {
        if (this.spring || this.view === 'create' || this.view === 'landing_page') {
            this.getExistingSprings();
        }
    }
}
</script>
