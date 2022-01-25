<template>

    <l-map ref="leafletMap" style="width:100%;height:100%;z-index:0;"
           :maxZoom="17"
           :minZoom="10"
           :tms="tms"
           :crs="crs"
           :continuousWorld="true"
           :bounds="bounds"
           @update:zoom="$parent.maaametZoomUpdate"
           @update:center="$parent.mapCenterUpdate"
           :options="mapOptions"
           @ready="onReady"
           @locationfound="onLocationFound"
           @click="$parent.updateLocation"
           @fullscreenchange="maaametFullscreenChanged"
    >
        <!-- @fullscreenchange="maaametFullscreenChanged" -->
        <l-control>
            <svg v-if="!$parent.liveLocation" @click="showLocation" class="h-8 w-8 p-1 bg-white border-2 rounded cursor-pointer hover:bg-gray-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd">
                    <title>{{ $t('springs.pan_to_current_location') }}</title>
                </path>
            </svg>
            <svg v-if="$parent.liveLocation" @click="stopLocation" class="h-8 w-8 p-1 bg-white border-2 rounded cursor-pointer hover:bg-gray-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd">
                </path>
                <line x1='1' y1='20' x2='20' y2='1' stroke-width='1' stroke='#0B2437'/>
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

        <!--<l-control-layers></l-control-layers>-->

        <l-wms-tile-layer
            v-for="layer in wmslayers"
            :key="layer.name"
            :base-url="baseUrl"
            :layers="layer.layers"
            :name="layer.name"
            :tms="tms"
            :zIndex="layer.zindex"
            :transparent="layer.transparent"
            :attribution="layer.attribution"
        />

        <l-marker v-if="$parent.liveLocation"
            :lat-lng="$parent.currentPosition"
            :icon="currentPositionIcon"
        ></l-marker>

        <l-marker v-if="this.$parent.springLocation"
                  :lat-lng="$parent.springLocation"
                  :icon="springLocationIcon"
        ></l-marker>

        <l-marker v-for="(marker, index) in $parent.mapMarkers"
                  :key="index"
                  :lat-lng="marker.position"
                  :icon="marker.icon"
        >
            <l-popup>
                <div class="pb-2"><a class="underline text-blue-700" :href="'/springs/'+marker.id+'/'">{{marker.name || 'Unnamed'}}</a></div>
                <div>{{ $t('springs.spring_code') }}: {{marker.id}} <br />{{ $t('springs.status') }}: {{ $t('springs.status_options.'+marker.status) }}</div>
                <div class="underline"><a target="_blank" :href="'https://www.google.com/maps/search/?api=1&query='+marker.position.lat+'%2C'+marker.position.lng">
                    {{ $t('springs.navigate') }}</a></div>
            </l-popup>
        </l-marker>

    </l-map>

</template>
<script>
import { latLngBounds, latLng, icon } from "leaflet";
import L from 'leaflet';
import { LMap, LTileLayer, LWMSTileLayer,LMarker, LIcon, LControlZoom, LControl, LPopup, LControlLayers } from 'vue2-leaflet';
import "proj4leaflet";
import { Icon } from 'leaflet';
import { GestureHandling } from "leaflet-gesture-handling";
import "leaflet-gesture-handling/dist/leaflet-gesture-handling.css";
import "leaflet.markercluster/dist/MarkerCluster.css";
import "leaflet.markercluster/dist/MarkerCluster.Default.css";
import { relief_shaded_wms_layers, relief_wms_layers, orthophoto_wms_layers, springLocationIcon, currentPositionIcon } from '../../../constants.js';
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
        GestureHandling,
        LControlFullscreen,
        omnivore,
    },
    props: ['springs', 'spring', 'view', 'zoom'],
    data() {

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

            baseUrl: 'https://kaart.maaamet.ee/wms/fotokaart', //https://kaart.maaamet.ee/wms/fotokaart

            wmslayers: [
                {
                    name: 'Reljeef',
                    zindex: 1,
                    visible: true,
                    format: 'image/png',
                    layers: 'vreljeef,HYBRID', //pohi_vv, of10000
                    attribution: "Maa-amet"
                },
                /*{
                    name: 'Reljeefvarjutusega',
                    visible: false,
                    format: 'image/png',
                    layers: 'pohi_vr2', //pohi_vv, of10000
                    transparent: true,
                    attribution: "Weather data © 2012 IEM Nexrad"
                },
                {
                    name: 'Ortophoto',
                    visible: false,
                    format: 'image/png',
                    layers: 'of10000', //pohi_vv, of10000
                    transparent: true,
                    attribution: "Weather data © 2012 IEM Nexrad"
                },*/

            ],



            mapOptions: {
                zoomSnap: 1,
                gestureHandling:true
            },

            cacheOpenStreetMapZoom: 7,
            cacheOpenStreetCenter: latLng(58.379, 24.554),
            cacheMaaametMapZoom: 11,
            cacheMaaametCenter: latLng(58.379, 24.554),

            openStreetMap: map !== 'maaamet',
            openStreetMapZoom: 7,
            openStreetCenter: latLng(58.379, 24.554),

            maaametMap: map !== 'openstreet',
            maaametMapZoom: 11,
            maaametCenter: latLng(58.379, 24.554),

            ee_spring: ee_spring,

            currentPosition: {lat: null, lng: null},
            currentPositionIcon: currentPositionIcon,

            springLocation: springLocation,
            springLocationIcon: springLocationIcon,

            layerIndex: 0,
            crs: projection,
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
        showReliefMap() {
            this.$parent.maaAmetMapTypeUpdate('relief');
            this.baseUrl = 'https://kaart.maaamet.ee/wms/fotokaart';
            this.wmslayers = relief_wms_layers;
        },
        showReliefShadedMap() {
            this.$parent.maaAmetMapTypeUpdate('relief_shaded');
            this.baseUrl = 'https://kaart.maaamet.ee/wms/alus';
            this.wmslayers = relief_shaded_wms_layers;
        },
        showOrthoPhoto() {
            this.$parent.maaAmetMapTypeUpdate('orthophoto');
            this.baseUrl = 'https://kaart.maaamet.ee/wms/fotokaart';
            this.wmslayers = orthophoto_wms_layers;
        },
        showWorldMap() {
            this.$parent.showWorldMap();
        },
        onReady(mapObject) {
            if (this.$parent.maaAmetMapType === 'relief_shaded') {
                this.baseUrl = 'https://kaart.maaamet.ee/wms/alus';
                this.wmslayers = relief_shaded_wms_layers;
            } else if (this.$parent.maaAmetMapType === 'orthophoto') {
                this.baseUrl = 'https://kaart.maaamet.ee/wms/fotokaart';
                this.wmslayers = orthophoto_wms_layers;
            } else {
                this.baseUrl = 'https://kaart.maaamet.ee/wms/fotokaart';
                this.wmslayers = relief_wms_layers;
            }

            this.leafletMapObject = mapObject;
            this.createLayers();
            this.leafletMapObject.setView(this.$parent.mapCenter, this.$parent.maaAmetMapZoom);
            if (this.$parent.liveLocation) {
                this.showLocation();
            }
            /*if (this.leafletMapObject.isFullscreen() && this.$parent.fullscreen === false) {
                this.leafletMapObject.toggleFullscreen();
            } else if (!(this.leafletMapObject.isFullscreen()) && this.$parent.fullscreen === true) {
                console.log("wms map fullscreen was: " + this.leafletMapObject.isFullscreen());
                console.log("toggle to fullscreen");
                this.leafletMapObject.toggleFullscreen();
                //this.leafletMapObject.fullscreen = true;

            } else {
                console.log("fullscreen ok");
            }
            console.log("wms map fullscreen: " + this.leafletMapObject.isFullscreen());*/

            this.leafletMapObject.on('overlayadd', this.onOverlayAdd);
            this.leafletMapObject.on('overlayremove', this.onOverlayRemove);
        },
        showLocation() {
            this.leafletMapObject.locate({setView: false, watch: true, enableHighAccuracy: true, maxZoom: 17});
        },
        stopLocation() {
            this.leafletMapObject.stopLocate();
            this.$parent.liveLocation = false;
        },
        onLocationFound(location) {
            this.$parent.mapCenterUpdate(location.latlng);
            this.$parent.setCurrentPosition(location);
        },
        maaametFullscreenChanged(fullscreen) {
            console.log("wms fullscreen changed: " + this.leafletMapObject.isFullscreen());
            /*if (this.leafletMapObject.isFullscreen()) {
                this.fullscreen = true;
            } else {
                this.fullscreen = false;
            }*/
        },
    createLayers() {
        var overlays = {
            "Muinsuskaitsealused allikad": this.kmlSpringsLayer('Kult_allikad', "#faac04"),
            "Loodusdirektiivi allikaelupaigad": this.kmlSpringsLayer('LD_allikad', "#ff007326"),
            "Pärandkultuuriallikad": this.kmlSpringsLayer('Par_allikad', "#c404f8"),
            "Allikate seirejaamad": this.kmlSpringsLayer('Seire_allikad', '#fcfc05'),
            "Allikalised vääriselupaigad": this.kmlSpringsLayer('VEP_allikad', '#ff00ff55'),
            "Looduskaitsealused allikad": this.kmlSpringsLayer('UOB_allikad', '#04fcfa'),
            "Ürglooduse raamatu allikad": this.kmlSpringsLayer('Urg_allikad', '#0454e8'),
            "Looduslike pühapaikade allikad": this.kmlSpringsLayer('Lood_puha_allikad', '#ec0404'),
            "Kohapärimuse allikad": this.kmlSpringsLayer('Koha_allikad', '#04fa05'),
        };
        L.control.layers(null,overlays,{collapsed:true}).addTo(this.leafletMapObject);
    },
    kmlSpringsLayer(springsType, color) {
        let springIcon = new L.Icon({
            iconUrl: '/kml/'+ springsType +'/symbol.png',
            iconSize: [20, 20],
            iconAnchor: [10, 16]
        });
        let layer = L.geoJson(null, {
            pointToLayer: function (feature, latlng) {
                return L.marker(latlng, {icon: springIcon});
            },
            onEachFeature: function(feature, featureLayer) {
                featureLayer.bindPopup(feature.properties.description);
            },
            style: {
                color: color,
            }
        });
        let kmlLayer = omnivore.kml('/kml/'+ springsType +'/doc.kml', null, layer);
        if (this.$parent.mapLayers.includes(springsType)) { // should layer be displayed by default or not?
            kmlLayer.addTo(this.leafletMapObject);
        }
        return kmlLayer;
    },
        onOverlayAdd(e) {
            this.$parent.mapLayersAdd(e.name);
        },
        onOverlayRemove(e) {
            this.$parent.mapLayersRemove(e.name);
        },
    },
}
</script>
