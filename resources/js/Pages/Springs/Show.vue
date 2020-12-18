<template>
    <app-layout>
        <template #header>
            <div class="flex w-full">
                <h2 class="w-3/4 font-semibold text-xl text-gray-800 leading-tight" v-if="spring.name">
                    {{ spring.name }}
                </h2>
                <h2 class="w-3/4 font-semibold text-xl text-gray-800 leading-tight" v-if="!spring.name">
                    Unnamed
                </h2>
                <div class="w-1/4" v-if="$page.user">
                    <button v-if="(can('edit spring') || spring.status === 'draft')" class="float-right border text-xs font-semibold px-3 py-2 leading-normal">
                        <inertia-link :href="'/springs/'+spring.code+'/edit'">Edit spring</inertia-link>
                    </button>
                </div>
            </div>
        </template>

        <div class="py-6">

            <spring-navigation :spring="spring" :active_tab="'view'"></spring-navigation>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                        <div class="z-depth-1-half map-container w-full" style="height:400px;">
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

                        <l-map ref="myMap"
                               :zoom="12"
                               :center="center"
                               :tms="tms"
                               :crs="crs"
                               :continuousWorld="true"
                               v-if="leafletmap"
                        >
                            <l-control-layers />
                            <!--<l-tile-layer
                                :url="url"
                                :attribution="attribution"
                            />-->
                            <l-wms-tile-layer
                                v-for="layer in layers"
                                :key="layer.name"
                                :base-url="baseUrl"
                                :layers="layer.layers"
                                :visible="layer.visible"
                                :name="layer.name"
                                layer-type="base"
                                :attribution="attribution"
                            />
                            <l-marker :lat-lng="location">
                                <l-icon
                                    icon-url="https://maps.google.com/mapfiles/ms/micons/blue-dot.png"
                                />
                            </l-marker>

                        </l-map>
                            <div class="block">
                                <button class="border float-right" v-if="googlemap" v-on:click="googlemap=false;leafletmap = true;">Maa-amet Map</button>
                                <button class="border float-right" v-if="leafletmap" v-on:click="leafletmap=false;googlemap = true;">Google Map</button>
                            </div>
                        </div>

                    <div class="flex -mx-2 w-full px-2 py-2">
                        <div class="w-3/4 px-2">

                            <div class="py-2">
                                <strong>Description</strong>
                                <div>{{ spring.description }}</div>
                            </div>

                            <div class="py-2" v-if="spring.folklore">
                                <strong>Folklore</strong>
                                <div>{{ spring.folklore }}</div>
                            </div>

                            <div class="py-2" v-if="spring.geology">
                                <strong>Geology</strong>
                                <div>{{ spring.geology }}</div>
                            </div>

                            <div class="py-2" v-if="spring.references.length > 0">
                                <strong>References</strong>
                                <div v-for="reference in spring.references">
                                    <a target="_blank" v-bind:href="reference.url" >{{ reference.url_title}}</a>
                                </div>
                            </div>

                            <div class="py-2" v-if="spring.database_links.length > 0">
                                <strong>Database links</strong>
                                <table class="table-auto text-sm border">
                                    <thead>
                                    <tr class="bg-gray-300">
                                        <th scope="col">Database name</th>
                                        <th scope="col">Code</th>
                                        <th scope="col">Spring name</th>
                                        <th scope="col">URL</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(database_link, index) in spring.database_links"
                                            :class="{'bg-gray-100': index % 2, 'bg-white': !(index % 2)}">
                                            <td>{{ database_link.database_name }}</td>
                                            <td>{{ database_link.code }}</td>
                                            <td>{{ database_link.spring_name }}</td>
                                            <td><a :href="database_link.url" target="_blank">{{ database_link.url }}</a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>

                        <div class="w-1/4 px-2">

                            <div class="py-2">
                                <strong>Location</strong>

                                <div>{{spring.country}} {{spring.county}}</div>

                                <div v-if="spring.settlement">{{spring.settlement}}</div>
                            </div>

                            <div class="py-2">
                                <div>{{spring.latitude}} {{spring.longitude}}</div>
                            </div>

                            <div class="py-2" v-if="spring.kkr_code">
                                <div class="group">
                                    <strong>KKR code</strong>
                                    <div>{{spring.kkr_code}}</div>
                                </div>
                            </div>

                            <div class="py-2">
                                <div class="group">
                                    <strong>Allikad.info code</strong>
                                    <div>{{spring.code}}</div>
                                </div>
                            </div>

                            <div class="py-2" v-if="spring.classification">
                                <strong>Spring classification</strong>
                                <div>{{spring.classification}}</div>
                            </div>

                            <div class="py-2" v-if="spring.groundwater_body">
                                <strong>Groundwater body</strong>
                                <div>{{spring.groundwater_body}}</div>
                            </div>

                            <div class="py-2" v-if="spring.ownership">
                                <strong>Ownership</strong>
                                <div>{{spring.ownership}}</div>
                            </div>

                            <div class="py-2" v-if="spring.status">
                                <strong>Status</strong>
                                <div>{{spring.status}}</div>
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

import { CRS, latLng } from "leaflet";
import L from 'leaflet';
import { LMap, LTileLayer, LMarker, LIcon, LTooltip, LWMSTileLayer, LControlLayers } from 'vue2-leaflet';
import "proj4leaflet";
import proj4 from "proj4";

/*const eoxMaps = {
    resolutions: [
        2048 , 1024 , 512 , 256 , 128 ,
        64 , 32 , 16 , 8 , 4 , 2 , 1 , 0.5
    ],
    url: 'http://kaart.maaamet.ee/wms/alus',
    format: 'image/jpeg',
    style: 'default',
    projection: 'EPSG :3301',
};*/


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
        gmapApi,
        "l-wms-tile-layer": LWMSTileLayer,
        LControlLayers,
        LMap,
        LTileLayer,
        LMarker,
        LIcon,
    },
    props: ['spring'],
    data() {
        return {
            leafletmap: this.spring.country == 'EE' ? true : false,
            googlemap: this.spring.country == 'EE' ? false : true,
            crs: projection,
            crs2: L.CRS.EPSG4326,
            //url: 'https://tiles.maaamet.ee/tm/tms/1.0.0/kaart/{z}/{x}/{y}.jpg&ASUTUS=MAAAMET&KESKKOND=EXAMPLES',
            tms: true,
            url: 'http://{s}.google.com/vt/lyrs=p&x={x}&y={y}&z={z}',
            center: latLng(this.spring.latitude, this.spring.longitude),
            //url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            //url: 'http://kaart.maaamet.ee/wms/alus?layers=MA-ALUS',
            attribution: "<a href='http://www.maaamet.ee'>Maa-amet</a>",
            location: latLng(this.spring.latitude, this.spring.longitude),

            baseUrl: 'http://kaart.maaamet.ee/wms/alus',
             layers: [
                /*{
                    crs:projection,
                    name: 'suvaline',
                    visible: true,
                    format: 'image/png',
                    layers: 'MA-ALUS',
                    transparent: false,
                    continuousWorld : true,
                    attribution: "<a  href='http://www.maaamet.ee/'>Maa-amet</a>",
                },*/
                {
                    //crs: projection,
                    name: 'Reljeefvarjutusega põhikaart',
                    visible: true,
                    format: 'image/jpeg',
                    layers: 'pohi_vv',
                    transparent: true,
                    continuousWorld : true
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
        handlePhotoPreview(photo) {
            this.dialogPhotoUrl = '/' + photo.path;
            this.dialogVisible = true;
        },
    },
    created: function(){

        /*var projection2 = new L.Proj.CRS('EPSG:3301', '+proj=lcc +lat_1=59.33333333333334 +lat_2=58 +lat_0=57.51755393055556 +lon_0=24 +x_0=500000 +y_0=6375000 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs', {
            resolutions: [4000, 2000, 1000, 500, 250, 125, 62.5, 31.25, 15.625, 7.8125, 3.90625, 1.953125, 0.9765625, 0.48828125, 0.244140625, 0.122070313, 0.061035156, 0.030517578, 0.015258789],
            origin: [40500, 5993000],
            bounds: L.bounds([40500, 5993000], [1064500, 7017000])
        });
        var map = L.map('leafmap', {
            crs: projection2
        });
        var tms = new L.TileLayer('https://tiles.maaamet.ee/tm/tms/1.0.0/kaart/{z}/{x}/{y}.jpg&ASUTUS=MAAAMET&KESKKOND=EXAMPLES', {
            continuousWorld: true,
            tms: true,
            attribution: "<a  href='http://www.maaamet.ee/'>Maa-amet</a>",
        });
        map.setView([58.66, 25.05], 3);
        tms.addTo(map);*/
    }
}



</script>

