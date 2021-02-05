<template>
    <app-layout>
        <template #header>
            <div class="flex w-full">
            <h1 class="inline w-4/5" v-if="spring.name">
                {{ spring.name }}
            </h1>
            <h1 class="inline w-4/5" v-if="!spring.name">
                {{ $t('springs.unnamed') }}
            </h1>
            <div class="w-1/5" v-if="$page.user">
                <div class="float-right">
                    <button v-if="(can('edit spring') || spring.status === 'draft')" class="bg-gray-100 hover:bg-blue-100 border text-xs font-semibold px-3 py-2 leading-normal">
                        <inertia-link :href="'/springs/'+spring.code+'/edit'">{{ $t('springs.edit') }}</inertia-link>
                    </button>
                    <spring-feedback class="mt-10 sm:mt-0" :spring="spring" />
                </div>
            </div>
            </div>
        </template>

        <div class="py-6">

            <spring-navigation :spring="spring" :active_tab="'view'"></spring-navigation>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                        <div class="z-depth-1-half map-container w-full" style="height:410px;">
                            <GmapMap ref="map"
                                 :center="{lat:latitude, lng:longitude}"
                                 :zoom="19"
                                 map-type-id="terrain"
                                 style="width: 100%; height: 100%"
                                     v-if="googlemap"
                            >
                            <GmapMarker
                                :key="index"
                                v-for="(location, index) in markers"
                                :position="location.position"
                            />
                            </GmapMap>
                        <div ref="leafmap"> </div>
                        <l-map ref="myMap" style="z-index:0;width:100%;height:100%"
                               :minZoom="3"
                               :maxZoom="14"
                               :zoom="13"
                               :center="leafletCenter"
                               :tms="tms"
                               :crs="crs"
                               :continuousWorld="true"
                               v-if="leafletmap"
                               @ready="onReady"
                               :bounds="bounds"
                               :options="{zoomControl: false}"
                        >
                            <!--<l-control-layers />-->
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
                            <l-marker :lat-lng="location">
                            </l-marker>
                            <l-control-zoom position="bottomright"  ></l-control-zoom>

                        </l-map>
                            <div class="block">
                                <button class="border float-right" v-if="googlemap" v-on:click="googlemap=false;leafletmap = true;">Maa-amet Map</button>
                                <button class="border float-right" v-if="leafletmap" v-on:click="leafletmap=false;googlemap = true;">Google Map</button>
                            </div>
                        </div>

                    <div class="flex -mx-2 w-full px-2 py-2">
                        <div class="w-3/4 px-2">

                            <div class="py-2">
                                <strong>{{ $t('springs.description') }}</strong>
                                <div>{{ spring.description }}</div>
                            </div>

                            <div class="py-2" v-if="spring.folklore">
                                <strong>{{ $t('springs.folklore') }}</strong>
                                <div>{{ spring.folklore }}</div>
                            </div>

                            <div class="py-2" v-if="spring.geology">
                                <strong>{{ $t('springs.geology') }}</strong>
                                <div>{{ spring.geology }}</div>
                            </div>

                            <div class="py-2" v-if="spring.references.length > 0">
                                <strong>{{ $t('springs.references') }}</strong>
                                <div v-for="reference in spring.references">
                                    <a target="_blank" v-bind:href="reference.url" >{{ reference.url_title}}</a>
                                </div>
                            </div>

                            <div class="py-2" v-if="spring.database_links.length > 0">
                                <strong>{{ $t('springs.link_with_other_databases') }}</strong>
                                <table class="table-auto text-sm border w-full">
                                    <thead>
                                    <tr class="bg-gray-300 uppercase text-xs">
                                        <th scope="col">{{ $t('springs.database_name') }}</th>
                                        <th scope="col">{{ $t('springs.code') }}</th>
                                        <th scope="col">{{ $t('springs.spring_name') }}</th>
                                        <th scope="col">{{ $t('springs.url') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(database_link, index) in spring.database_links"
                                            :class="{'bg-gray-100': index % 2, 'bg-white': !(index % 2)}">
                                            <td>{{ database_link.database_name }}</td>
                                            <td>{{ database_link.code }}</td>
                                            <td>{{ database_link.spring_name }}</td>
                                            <td><a :href="database_link.url" target="_blank" class="hover:underline">{{ stripUrl(database_link.url) }}</a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>

                        <div class="w-1/4 px-2">

                            <div class="py-2" v-if="(spring.user && $page.user)">
                                <div class="group">
                                    <strong>{{ $t('springs.added_by') }}</strong>
                                    <div>{{ spring.user.name }}</div>
                                </div>
                            </div>

                            <div class="py-2">
                                <strong>{{ $t('springs.location') }}</strong>

                                <div>{{spring.country}} {{spring.county}}</div>

                                <div v-if="spring.settlement">{{spring.settlement}}</div>
                            </div>

                            <div class="py-2">
                                <div>{{spring.latitude}} {{spring.longitude}}</div>
                            </div>

                            <div class="py-2" v-if="spring.kkr_code">
                                <div class="group">
                                    <strong>{{ $t('springs.kkr_code') }}</strong>
                                    <div>{{spring.kkr_code}}</div>
                                </div>
                            </div>

                            <div class="py-2">
                                <div class="group">
                                    <strong>{{  $t('springs.wateract_code') }}</strong>
                                    <div>{{spring.code}}</div>
                                </div>
                            </div>

                            <div class="py-2" v-if="spring.classification">
                                <strong>{{ $t('springs.spring_classification') }}</strong>
                                <div>{{ $t('springs.classification_options.'+spring.classification) }}</div>
                            </div>

                            <div class="py-2" v-if="spring.groundwater_body">
                                <strong>{{ $t('springs.groundwater_body') }}</strong>
                                <div>{{spring.groundwater_body}}</div>
                            </div>

                            <div class="py-2" v-if="spring.ownership">
                                <strong>{{ $t('springs.ownership') }}</strong>
                                <div>{{ $t('springs.ownership_options.'+spring.ownership) }}</div>
                            </div>

                            <div class="py-2" v-if="spring.status">
                                <strong>{{ $t('springs.status') }}</strong>
                                <div>{{ $t('springs.status_options.'+spring.status) }}</div>
                            </div>

                            <div class="py-2" v-if="spring.photos.length > 0">
                                <strong>Gallery</strong>
                                <div class="grid grid-cols-2 gap-1">
                                    <div @click="handlePhotoPreview(photo)" class="border-1 border-white" v-for="photo in spring.photos">
                                        <img :src="'/'+photo.thumbnail" />
                                    </div>
                                </div>
                            </div>
                            <el-dialog :visible.sync="dialogVisible">
                                <img width="100%" :src="dialogPhotoUrl" alt="" />
                            </el-dialog>
                        </div>


                    </div>
                </div>

            </div>

        </div>
    </app-layout>
</template>

<script>
import AppLayout from './../../Layouts/AppLayout';
import SpringNavigation from './SpringNavigation';
import JetLabel from "../../Jetstream/Label";
import { gmapApi } from 'gmap-vue';
import SpringFeedback from './../SpringFeedback/Create'

import { CRS, latLngBounds, latLng } from "leaflet";
import L from 'leaflet';
import { LMap, LTileLayer, LMarker, LIcon, LControlZoom, LTooltip, LWMSTileLayer, LControlLayers } from 'vue2-leaflet';
import "proj4leaflet";
import proj4 from "proj4";


var projection = new L.Proj.CRS('EPSG:3301', '+proj=lcc +lat_1=59.33333333333334 +lat_2=58 +lat_0=57.51755393055556 +lon_0=24 +x_0=500000 +y_0=6375000 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs', {
    resolutions: [4000, 2000, 1000, 500, 250, 125, 62.5, 31.25, 15.625, 7.8125, 3.90625, 1.953125, 0.9765625, 0.48828125, 0.244140625, 0.122070313, 0.061035156, 0.030517578, 0.015258789],
    origin: [40500, 5993000],
    bounds: L.bounds([40500, 5993000], [1064500, 7017000])
});

export default {
    components: {
        AppLayout,
        SpringNavigation,
        JetLabel,
        SpringFeedback,
        gmapApi,
        "l-wms-tile-layer": LWMSTileLayer,
        LControlLayers,
        LMap,
        LTileLayer,
        LMarker,
        LIcon,
        LControlZoom,
    },
    props: ['spring'],
    data() {
        return {
            leafletmap: this.spring.country == 'EE' ? true : false,
            googlemap: this.spring.country == 'EE' ? false : true,
            crs: projection,
            //crs:null,
            crs2: L.CRS.EPSG4326,
            //url: 'https://tiles.maaamet.ee/tm/tms/1.0.0/kaart/{z}/{x}/{y}.jpg&ASUTUS=MAAAMET&KESKKOND=EXAMPLES',
            tms: true,
            url: 'http://{s}.google.com/vt/lyrs=p&x={x}&y={y}&z={z}',
            leafletCenter: latLng(this.spring.latitude, this.spring.longitude),
            //maaamet_url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            //url: 'http://kaart.maaamet.ee/wms/alus?layers=MA-ALUS',
            attribution: "<a href='http://www.maaamet.ee'>Maa-amet</a>",
            location: latLng(this.spring.latitude, this.spring.longitude),
            maaamet_url: 'https://tiles.maaamet.ee/tm/tms/1.0.0/vreljeef/{z}/{x}/{y}.png&ASUTUS=MAAAMET&KESKKOND=LIVE&IS=TMSNAIDE',
            zoom: 5,
            baseUrl: 'http://kaart.maaamet.ee/wms/alus',
            bounds: latLngBounds([
                [60.4349, 29.4338],
                [56.7458, 20.373]
            ]),

            tilelayers: [
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
                }

            ],

            map: null,
            latitude: this.spring.latitude,
            longitude: this.spring.longitude,
            markers: [{
                id: this.spring.id,
                name: this.spring.name,
                description: this.spring.description,
                position: {lat: this.spring.latitude, lng: this.spring.longitude}
            }],
            dialogVisible: false,
            dialogPhotoUrl: '',
        }
    },
    methods: {
        stripUrl(url) {
            if (url) {
                let stripped = url.slice(0, 25);
                return stripped;
            }
            return "";
        },
        handlePhotoPreview(photo) {
            this.dialogPhotoUrl = '/' + photo.path;
            this.dialogVisible = true;
        },
        onReady() {
            this.$refs.myMap.mapObject.setView(this.leafletCenter, 11);
        }
    },
    mounted: function() {
        console.log(this.bounds);
        //this.$refs.myMap.fitBounds(this.bounds);
        //});
        /*this.$nextTick(() => {
            this.map = this.$refs.myMap.mapObject;
        });
        console.log(this.$refs.leafmap);
        console.log(this.bounds);

        this.map.fitBounds(this.bounds);*/

        //this.$refs.leafmap.mapObject.fitBounds(this.bounds);
        /*map.fitBounds([
            [60.28165, 30.624],
            [57.17855, 19.46739]
        ]);*/

        /*var projection2 = new L.Proj.CRS('EPSG:3301', '+proj=lcc +lat_1=59.33333333333334 +lat_2=58 +lat_0=57.51755393055556 +lon_0=24 +x_0=500000 +y_0=6375000 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs', {
            resolutions: [4000, 2000, 1000, 500, 250, 125, 62.5, 31.25, 15.625, 7.8125, 3.90625, 1.953125, 0.9765625, 0.48828125, 0.244140625, 0.122070313, 0.061035156, 0.030517578, 0.015258789],
            origin: [40500, 5993000],
            bounds: L.bounds([40500, 5993000], [1064500, 7017000])
        });
        var map = L.map('leafmap', {
            crs: projection2
        });
        var tms = new L.TileLayer('https://tiles.maaamet.ee/tm/tms/1.0.0/foto/{z}/{x}/{y}.jpg&ASUTUS=MAAAMET&KESKKOND=LIVE&IS=TMSNAIDE', {
            continuousWorld: true,
            tms: true,
            attribution: "<a  href='http://www.maaamet.ee/'>Maa-amet</a>",
        });
        map.setView([58.66, 25.05], 3);
        map.addLayer(tms);*/
    }
}



</script>

