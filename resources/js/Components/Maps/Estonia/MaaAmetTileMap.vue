<template>

    <l-map ref="leafletMap" style="width:100%;height:100%;z-index:0;"
           :maxZoom="14"
           :minZoom="3"
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
           @click="updateLocation"
    >
        <!-- @fullscreenchange="maaametFullscreenUpdate" -->
        <l-control>
            <svg @click="showLocation" class="h-8 w-8 p-1 bg-white border-2 rounded cursor-pointer hover:bg-gray-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd">
                    <title>{{ $t('springs.pan_to_current_location') }}</title>
                </path>
            </svg>
        </l-control>

        <l-control position="bottomright" v-if="!fullscreen">
            <div class="bg-white p-1 border-2 rounded cursor-pointer hover:bg-gray-100" @click="showWorldMap">{{ $t('springs.world_map') }}</div>
        </l-control>
        <l-control  position="bottomright">
            <div class="bg-white p-1 border-2 rounded cursor-pointer hover:bg-gray-100" @click="showOrthoPhoto">{{ $t('springs.orthophoto') }}</div>
        </l-control>
        <l-control position="bottomright">
            <div class="bg-white p-1 border-2 rounded cursor-pointer hover:bg-gray-100" @click="showReliefShadedMap">{{ $t('springs.relief_shaded_map') }}</div>
        </l-control>
        <l-control position="bottomright">
            <div class="bg-white p-1 border-2 rounded cursor-pointer hover:bg-gray-100" @click="showReliefMap">{{ $t('springs.relief_map') }}</div>
        </l-control>

        <!--<l-control-fullscreen />-->

        <l-tile-layer
            v-for="layer in tilelayers"
            :key="layer.name"
            :url="layer.url"
            :zIndex="layer.zindex"
            :attribution="attribution"
            :tms="tms"
            :worldCopyJump="true"
            :options="{ maxNativeZoom: layer.maxzoom, maxZoom: layer.maxzoom }"
        />

        <l-marker
            :lat-lng="currentPosition"
            :icon="currentPositionIcon"
        ></l-marker>

        <l-marker v-if="this.springLocation"
                  :lat-lng="springLocation"
                  :icon="springLocationIcon"
        ></l-marker>


        <l-marker-cluster :options="maaametClusterOptions">
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
import L from 'leaflet';
import { LMap, LTileLayer, LMarker, LIcon, LControlZoom, LControl, LPopup, LControlLayers } from 'vue2-leaflet';
import "proj4leaflet";
import Vue2LeafletMarkerCluster from 'vue2-leaflet-markercluster'
import { Icon } from 'leaflet';
import { GestureHandling } from "leaflet-gesture-handling";
import "leaflet-gesture-handling/dist/leaflet-gesture-handling.css";
import "leaflet.markercluster/dist/MarkerCluster.css";
import "leaflet.markercluster/dist/MarkerCluster.Default.css";
import { relief_shaded_layers, relief_layers, orthophoto_layers, springLocationIcon } from '../../../constants.js';
import LControlFullscreen from 'vue2-leaflet-fullscreen';
import omnivore from '@mapbox/leaflet-omnivore';

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
        omnivore,
    },
    props: ['springs', 'spring', 'view', 'zoom'],
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
            mapOptions: {
                zoomSnap: 1,
                gestureHandling:true
            },
            maaametClusterOptions: {
                disableClusteringAtZoom: 11,
                maxClusterRadius: 70,
            },
            openStreetClusterOptions: {
                disableClusteringAtZoom: 15,
                maxClusterRadius: 70,
            },

            cacheOpenStreetMapZoom: 7,
            cacheOpenStreetCenter: latLng(58.379, 24.554),
            cacheMaaametMapZoom: this.zoom,
            cacheMaaametCenter: latLng(58.379, 24.554),

            openStreetMap: map !== 'maaamet',
            openStreetMapZoom: 7,
            openStreetCenter: latLng(58.379, 24.554),

            maaametMap: map !== 'openstreet',
            maaametMapZoom: this.$parent.maaametMapZoom,
            maaametCenter: latLng(58.379, 24.554),

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
            crs: projection,
            tms: true,
            attribution: "<a href='http://www.maaamet.ee'>Maa-amet</a>",
            bounds: latLngBounds([
                [60.4349, 29.4338],
                [56.7458, 20.373]
            ]),
            tilelayers: relief_layers,
            fullscreen: false,

        }
    },
    methods: {
        showReliefMap() {
            this.$parent.maaAmetMapTypeUpdate('relief');
            this.tilelayers = relief_layers;
        },
        showReliefShadedMap() {
            this.$parent.maaAmetMapTypeUpdate('relief_shaded');
            this.tilelayers = relief_shaded_layers;
        },
        showOrthoPhoto() {
            this.$parent.maaAmetMapTypeUpdate('orthophoto');
            this.tilelayers = orthophoto_layers;
        },
        showWorldMap() {
            this.$parent.showWorldMap();
        },
        showMaaametMap() {
            this.$parent.showMaaametMap();
        },
        onReady(mapObject) {
            if (this.$parent.maaAmetMapType === 'relief_shaded') {
                this.tilelayers = relief_shaded_layers;
            } else if (this.$parent.maaAmetMapType === 'orthophoto') {
                this.tilelayers = orthophoto_layers;
            } else {
                this.tilelayers = relief_layers;
            }
            this.leafletMapObject = mapObject;
            this.createLayers();
            this.leafletMapObject.setView(this.$parent.mapCenter, this.$parent.maaAmetMapZoom);
            if (this.view === 'show' || this.view === 'edit') {
                let leafletCenter = latLng(this.spring.latitude, this.spring.longitude);
                this.leafletMapObject.setView(leafletCenter, 11);
            }
        },
        showLocation() {
            this.leafletMapObject.locate();
        },
        onLocationFound(location) {
            this.$parent.mapCenterUpdate(location.latlng);
            this.currentPosition= location.latlng;
            this.leafletMapObject.setView(location.latlng, 9);
        },
        maaametZoomUpdate(zoom) {
            this.$parent.maaametZoomUpdate(zoom);
        },
        maaametCenterUpdate(center) {
            this.$parent.mapCenterUpdate(center);
        },
        maaametFullscreenUpdate() {
            if (this.leafletMapObject.isFullscreen()) {
                this.$parent.fullscreen = true;
                //this.$parent.maaametFullscreenChanged(true);
            } else {
                this.$parent.fullscreen = false;
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
        createLayers() {
            var overlays = {
                "Muinsuskaitsealused allikad": this.kmlSpringsLayer('Kult_allikad'),
                "Loodusdirektiivi allikaelupaigad": this.kmlSpringsLayer('LD_allikad'),
                "Pärandkultuuriallikad": this.kmlSpringsLayer('Par_allikad'),
                "Allikate seirejaamad": this.kmlSpringsLayer('Seire_allikad'),
                "Allikalised vääriselupaigad": this.kmlSpringsLayer('VEP_allikad'),
                "Looduskaitsealused allikad": this.kmlSpringsLayer('UOB_allikad'),
                "Ürglooduse raamatu allikad": this.kmlSpringsLayer('Urg_allikad'),
            };
            L.control.layers(null,overlays,{collapsed:true}).addTo(this.leafletMapObject);
        },
        kmlSpringsLayer(springsType) {
            let springIcon = new L.Icon({
                iconUrl: '/kml/'+ springsType +'/symbol.png',
            });
            let layer = L.geoJson(null, {
                pointToLayer: function (feature, latlng) {
                    return L.marker(latlng, {icon: springIcon});
                },
                onEachFeature: function(feature, featureLayer) {
                    featureLayer.bindPopup(feature.properties.description);
                },
                style: {
                    color: "blue",
                }
            });
            let kmlLayer = omnivore.kml('/kml/'+ springsType +'/doc.kml', null, layer);
            //kmlLayer.addTo(this.leafletMapObject); // should layer be displayed by default or not?
            return kmlLayer;
        },
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
