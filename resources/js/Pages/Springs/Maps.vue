<style>
@import "leaflet.markercluster/dist/MarkerCluster.css";
@import "leaflet.markercluster/dist/MarkerCluster.Default.css";
</style>

<template>

    <div>

        <div class="z-depth-1-half map-container block h-full">

            <GmapMap ref="map" v-if="googlemap"
                     :center="{lat:58.379, lng:24.554}"
                     :zoom="googleZoom"
                     map-type-id="terrain"
                     style="width: 100%; height: 100%"
            >
                <GmapCluster>
                    <GmapMarker
                        :key="index"
                        v-for="(location, index) in googlemarkers"
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

            <!--<l-map ref="openstreetMap" style="z-index:1;width:100%;height:100%"
                   :zoom="4"
                   :center="leafletCenter"
                   :tms="tms"
                   :continuousWorld="true"
                   v-if="leafletmap"
                   :bounds="bounds"
                   :options="{zoomControl: false}"
            >
                <l-tile-layer
                              :url="openstreetmapUrl"
                              :attribution="openstreetmapAttribution"
                />
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
                <l-control-zoom position="bottomright"  ></l-control-zoom>
            </l-map>-->

            <l-map ref="leafletMap" style="z-index:1;width:100%;height:100%"
                   :minZoom="3"
                   :maxZoom="14"
                   :zoom="leafletZoom"
                   :center="leafletCenter"
                   :tms="tms"
                   :crs="crs"
                   :continuousWorld="true"
                   v-if="leafletmap"
                   :bounds="bounds"
                   :options="{zoomControl: false}"
                   @update:zoom="zoomUpdate"
            >
                <l-tile-layer
                    v-for="layer in maaametLayers"
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
                            <div>{{ $t('springs.spring_code') }}: {{marker.id}} <br />{{ $t('springs.status') }}: {{ $t('springs.status_options.'+marker.status) }}</div>
                        </l-popup>
                    </l-marker>
                </l-marker-cluster>
                <l-control-zoom position="bottomright"  ></l-control-zoom>

            </l-map>
            <div class="block">
                <button class="border float-right" v-if="googlemap" v-on:click="googlemap=false;leafletmap = true;">Maa-amet Map</button>
                <button class="border float-right" v-if="leafletmap" v-on:click="leafletmap=false;googlemap = true;">Google Map</button>
            </div>
            <!--<div class="block">
                <button class="border float-right" v-on:click="crs=projection;maaametmaps=true;openstreetmap=false;">Maa-amet crs</button>
                <button class="border float-right" v-on:click="crs=crs_3857;openstreetmap=true;maaametmaps=false;">openstreet</button>
            </div>-->


        </div>

    </div>


</template>
<script>
import JetLabel from "../../Jetstream/Label";
import { gmapApi } from 'gmap-vue';
import GmapCluster from 'gmap-vue/dist/components/cluster'
import { CRS, latLngBounds, latLng } from "leaflet";
import L from 'leaflet';
import { LMap, LTileLayer, LMarker, LIcon, LControlZoom, LPopup, LWMSTileLayer, LControlLayers } from 'vue2-leaflet';
import "proj4leaflet";
import Vue2LeafletMarkerCluster from 'vue2-leaflet-markercluster'
import { Icon } from 'leaflet';

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
        JetLabel,
        gmapApi,
        GmapCluster,
        "l-wms-tile-layer": LWMSTileLayer,
        LControlLayers,
        LMap,
        LTileLayer,
        LMarker,
        LIcon,
        LControlZoom,
        LPopup,
        'l-marker-cluster': Vue2LeafletMarkerCluster,
    },
    props: ['springs'],
    data() {
        const mapIcons = {
            'confirmed': 'https://maps.google.com/mapfiles/ms/micons/blue-dot.png',
            'submitted': 'https://maps.google.com/mapfiles/ms/micons/orange-dot.png',
        };
        let googlemarkers = [];
        let leafletmarkers = [];
        _.forEach(this.springs, function(spring) {
            googlemarkers.push({
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
            projection: projection,
            crs_3857: L.CRS.EPSG3857,

            maaametmaps: false,
            openstreetmap: true,
            crs: projection,

            googlemap: false,
            leafletmap: true,

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

            googlemarkers: googlemarkers,
            leafletmarkers: leafletmarkers,
            latitude: 58.379,
            longitude: 24.554,

            tms: true,
            leafletCenter: latLng(this.latitude, this.longitude),
            leafletZoom: 13,
            openstreetZoom: 20,
            googleZoom: 7,
            //url: 'http://{s}.google.com/vt/lyrs=p&x={x}&y={y}&z={z}',
            center: [58.779, 25.054],
            openstreetmapUrl: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            openstreetmapAttribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
            attribution: "<a href='http://www.maaamet.ee'>Maa-amet</a>",

            transparency: 'true',
            bounds: latLngBounds([
                [60.4349, 29.4338],
                [56.7458, 20.373]
            ]),


            maaametLayers: [
                {
                    name: 'reljeef',
                    url: 'https://tiles.maaamet.ee/tm/tms/1.0.0/vreljeef/{z}/{x}/{y}.png&ASUTUS=ALLIKAD&KESKKOND=LIVE',
                    zindex: 1,
                    maxzoom: 11,
                },
                {
                    name: 'hybrid',
                    url: 'https://tiles.maaamet.ee/tm/tms/1.0.0/hybriid/{z}/{x}/{y}.png&ASUTUS=ALLIKAD&KESKKOND=LIVE',
                    zindex: 2,
                    maxzoom: 11,
                },
                {
                    name: 'pohi',
                    url: 'https://tiles.maaamet.ee/tm/tms/1.0.0/epk_vv/{z}/{x}/{y}.png&ASUTUS=ALLIKAD&KESKKOND=LIVE',
                    zindex: 3,
                    maxzoom: 11,
                },
            ],
            layerIndex: 0,
            layers: [
                {
                    url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                    attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
                },
                {
                    url: 'https://vec01.maps.yandex.net/tiles?l=map&x={x}&y={y}&z={z}',
                    subdomains: '1,2,3,4',
                    attribution: '&copy; <a href="http://yandex.ru/copyright">Yandex</a>',
                    crs: CRS.EPSG3395
                }
            ]
        }
    },
    methods: {
        zoomUpdate(zoom) {
            //console.log('updating zoom');
            //console.log(zoom);
            this.leafletZoom = zoom;
            this.googleZoom++;
        },
        updateLocation(location) {
            /*this.markers = [{
                position: location.latLng
            }];*/
            this.markers = [ location.latlng ];
            console.log(location.latlng);
            console.log(location.latlng.lat);
            this.form.latitude= location.latlng.lat;
            this.form.longitude = location.latlng.lng;
        },
        toggleInfoWindow: function (marker, idx) {
            this.infoWindowPos = ({
                    lat: marker.position.lat,
                    lng: marker.position.lng,
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
    },
    computed: {
        layer () {
            //console.log('layerindex');
            //console.log(this.layerIndex);
            return this.layers[this.layerIndex]
        }
    },
}
</script>
