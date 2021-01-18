<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $t('springs.edit_spring') }}
            </h2>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <jet-form-section>

                <template #form>

                    <el-dialog :visible.sync="helpDialogVisible">
                        <div class="break-normal">{{ helptext }}</div>
                    </el-dialog>

                    <div class="flex -mx-2">
                        <div class="w-full px-2">
                            <jet-label class="font-bold inline-block" for="name" :value="$t('springs.name')" />
                            <help-button @click.native="showHelpDialog( $t('springs.name_help_text') )"></help-button>
                            <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" />
                        </div>
                    </div>


                    <div class="flex -mx-2 py-2">
                        <div class="w-full px-2">
                            <jet-label class="font-bold" :value="$t('springs.location')" />
                            <div class="z-depth-1-half map-container" style="height:450px;">
                                <GmapMap
                                    :center="gmapCenter"
                                    :zoom="13"
                                    map-type-id="terrain"
                                    style="width: 100%; height: 100%"
                                    @click="updateLocation"
                                    v-show="googleMap"
                                >
                                    <GmapMarker
                                        :key="index"
                                        v-for="(location, index) in gmapMarkers"
                                        :position="location.position"
                                        :draggable="true"
                                        @dragend="updateLocation"
                                    />
                                </GmapMap>
                                <l-map ref="leafMap"
                                       :minZoom="3"
                                       :maxZoom="13"
                                       :center="leafletCenter"
                                       :tms="tms"
                                       :crs="crs"
                                       v-if="leafletMap"
                                       @click="updateLeafletLocation"
                                       :bounds="bounds"
                                       @ready="onReady"
                                       :options="{zoomControl: false}"
                                >
                                    <l-control-zoom position="bottomright"></l-control-zoom>
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
                                    <l-marker v-for="(location, index) in leafletMarkers"
                                              :key="index"
                                              :lat-lng="location"></l-marker>
                                </l-map>
                                <div class="block">
                                    <button class="border float-right" v-if="googleMap" v-on:click="showLeaflet()">Maa-amet Map</button>
                                    <button class="border float-right" v-if="leafletMap" v-on:click="leafletMap=false;googleMap = true;">Google Map</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex ">
                        <div class="w-1/2 px-2">
                            <jet-label class="font-bold inline-block" for="latitude" :value="$t('springs.latitude')" />
                            <help-button @click.native="showHelpDialog( $t('springs.latitude_help_text') )"></help-button>
                            <jet-input id="latitude" type="text" class="mt-1 block w-full" v-model="form.latitude" />
                            <!--<jet-input-error :message="form.error('latitude')" class="mt-2" />-->
                        </div>

                        <div class="w-1/2 px-2">
                            <jet-label class="font-bold inline-block" for="longitude" :value="$t('springs.longitude')" />
                            <help-button @click.native="showHelpDialog( $t('springs.longitude_help_text') )"></help-button>
                            <jet-input id="longitude" type="text" class="mt-1 block w-full" v-model="form.longitude" />
                            <!--<jet-input-error :message="form.error('longitude')" class="mt-2" />-->
                        </div>
                    </div>


                    <div class="flex">
                        <div class="w-1/3 px-2">
                            <jet-label class="font-bold" for="country" :value="$t('springs.country')" />
                            <jet-input id="country" type="text" class="mt-1 block w-full" v-model="form.country" />
                        </div>
                        <div class="w-1/3 px-2">
                            <jet-label class="font-bold" for="county" :value="$t('springs.county')" />
                            <jet-input id="county" type="text" class="mt-1 block w-full" v-model="form.county" />
                        </div>
                        <div class="w-1/3 px-2">
                            <jet-label class="font-bold" for="settlement" :value="$t('springs.settlement')" />
                            <jet-input id="settlement" type="text" class="mt-1 block w-full" v-model="form.settlement" />
                        </div>
                    </div>

                    <div class="px-2 py-2">
                        <jet-label class="font-bold" for="references" :value="$t('springs.references')" />

                        <div id="references">
                            <div v-for="(reference, index) in form.references" class="py-1">
                                <jet-input class="w-2/5" v-model="reference.url_title" :placeholder="$t('springs.url_title')"/>
                                <jet-input class="w-2/5" v-model="reference.url" :placeholder="$t('springs.url')"/>
                                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3" @click="addReference">+</button>
                                <jet-input-error :message="form.error('references.'+index+'.url')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <div class="px-2 py-2">
                        <jet-label class="font-bold inline-block" for="photos" :value="$t('springs.photos')" />
                        <help-button @click.native="showHelpDialog( $t('springs.photos_help_text') )"></help-button>
                        <el-upload
                            :file-list="photos"
                            action="/"
                            list-type="picture-card"
                            accept="image/*"
                            :auto-upload="false"
                            :on-preview="handlePhotoCardPreview"
                            :on-remove="handleRemove"
                            :on-change="updatePhotos">
                            <i class="el-icon-plus"></i>
                        </el-upload>
                        <el-dialog :visible.sync="dialogVisible">
                            <img width="100%" :src="dialogPhotoUrl" alt="" />
                        </el-dialog>
                    </div>

                    <div class="px-2 py-2">
                        <jet-label class="font-bold inline-block" for="description" :value="$t('springs.description')" />
                        <help-button @click.native="showHelpDialog( $t('springs.description_help_text') )"></help-button>
                        <textarea id="description" type="textarea" class="px-2 mt-1 block w-full border" rows="5" v-model="form.description" ></textarea>
                        <!--<small id="description_help_block" class="form-text text-muted">
                            'springs.description_help_text'
                        </small>-->
                        <jet-input-error :message="form.error('description')" class="mt-2" />
                    </div>

                    <div class="px-2 py-2">
                        <jet-label class="font-bold" for="folklore" :value="$t('springs.folklore')" />
                        <textarea id="folklore" type="textarea" class="px-2 mt-1 block w-full border" rows="5" v-model="form.folklore"></textarea>
                    </div>

                    <div v-if="can('edit spring')">

                    <div class="flex">
                        <div class="w-1/2 px-2">
                            <jet-label class="font-bold" for="kkr_code" :value="$t('springs.kkr_code')" />
                            <jet-input id="kkr_code" type="text" class="mt-1 block w-full" v-model="form.kkr_code" />
                        </div>
                    </div>

                    <div class="px-2 py-2">
                        <jet-label class="font-bold" for="database_links" :value="$t('springs.link_with_other_databases')" />
                        <div id="database_links">
                            <div v-for="link in form.database_links" :key="link.id" class="py-1">
                                <jet-input v-model="link.database_name" :placeholder="$t('springs.database_name')"/>
                                <jet-input v-model="link.spring_name" :placeholder="$t('springs.spring_name')"/>
                                <jet-input v-model="link.code" :placeholder="$t('springs.code')"/>
                                <jet-input v-model="link.url" :placeholder="$t('springs.url')"/>
                                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3" @click="addDatabaseLink">+</button>
                            </div>
                        </div>
                    </div>

                    <div class="flex -mx-2 py-2">
                        <div class="w-1/2 px-2">
                            <jet-label class="font-bold" for="classification" :value="$t('springs.spring_classification')" />
                            <select id="classification" v-model="form.classification"
                                    class="block w-full bg-white border border-gray-400 hover:border-gray-500 px-2 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                                <option value=""></option>
                                <option v-for='data in classifications' :selected="form.classification === data.id" :value='data.id'>{{ $t(data.name) }}</option>
                            </select>
                        </div>

                        <div class="w-1/2 px-2">
                            <jet-label class="font-bold" for="groundwater_body" :value="$t('springs.groundwater_body')"></jet-label>
                            <jet-input id="groundwater_body" type="text" class="mt-1 block w-full" v-model="form.groundwater_body" />
                        </div>
                    </div>

                    <div class="px-2 py-2">
                        <jet-label class="font-bold" for="folklore" :value="$t('springs.geology')" />
                        <textarea id="geology" type="textarea" class="px-2 mt-1 block w-full border" rows="5" v-model="form.geology"></textarea>
                    </div>

                    <div class="w-1/2 px-2 py-2">
                        <jet-label class="font-bold" for="ownership" :value="$t('springs.ownership')" />
                        <select id="ownership" v-model="form.ownership"
                                class="block w-full bg-white border border-gray-400 hover:border-gray-500 px-2 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                            <option value=""></option>
                            <option v-for='data in ownerships' :selected="form.ownership === data.id" :value='data.id'>{{ $t(data.name) }}</option>
                        </select>
                    </div>

                    <div class="px-2 py-2">
                        <el-checkbox :true-label="1" false-label="0" v-model="form.needs_attention" name="needs_attention"><jet-label for="needs_attention" :value="$t('springs.needs_attention')" /></el-checkbox>
                    </div>
                    <div class="px-2 py-2">
                        <el-checkbox :true-label="1" false-label="0" v-model="form.featured" name="featured"><jet-label for="featured" :value="$t('springs.featured')" /></el-checkbox>
                    </div>

                    </div>


            </template>

            <template #actions>
                <jet-secondary-button v-if="form.status === 'draft'" type="submit" @click.native="saveDraft(form)">Save as draft</jet-secondary-button>
                <jet-button v-if="form.status === 'draft'" class="ml-2" type="submit" @click.native="submit(form)">Submit</jet-button>
                <jet-button v-if="can('edit spring') && form.status === 'submitted'" class="ml-2" type="submit" @click.native="confirm(form)">{{ $t('springs.confirm') }}</jet-button>
                <jet-button v-if="can('edit spring') && form.status === 'confirmed'" class="ml-2" type="submit" @click.native="save(form)">{{ $t('springs.save') }}</jet-button>

            </template>
        </jet-form-section>
        </div>
    </app-layout>
</template>


<script>
import AppLayout from './../../Layouts/AppLayout'
import JetFormSection from "./../../Jetstream/FormSection";
import JetInput from "../../Jetstream/Input";
import JetInputError from "../../Jetstream/InputError";
import JetLabel from "../../Jetstream/Label";
import JetButton from "../../Jetstream/Button";
import JetSecondaryButton from "../../Jetstream/SecondaryButton";
import HelpButton from '../../Components/HelpButton';
import { gmapApi } from 'gmap-vue';
import { latLngBounds, latLng } from "leaflet";
import L from 'leaflet';
import { LMap, LTileLayer, LMarker, LIcon, LControlZoom, LControlLayers } from 'vue2-leaflet';
import "proj4leaflet";

let projection3301 = new L.Proj.CRS('EPSG:3301', '+proj=lcc +lat_1=59.33333333333334 +lat_2=58 +lat_0=57.51755393055556 +lon_0=24 +x_0=500000 +y_0=6375000 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs', {
    resolutions: [4000, 2000, 1000, 500, 250, 125, 62.5, 31.25, 15.625, 7.8125, 3.90625, 1.953125, 0.9765625, 0.48828125, 0.244140625, 0.122070313, 0.061035156, 0.030517578, 0.015258789],
    origin: [40500, 5993000],
    bounds: L.bounds([40500, 5993000], [1064500, 7017000])
});

export default {
    components: {
        AppLayout,
        JetFormSection,
        JetInput,
        JetInputError,
        JetLabel,
        JetButton,
        JetSecondaryButton,
        HelpButton,
        gmapApi,
        LControlLayers,
        LMap,
        LTileLayer,
        LMarker,
        LIcon,
        LControlZoom,
    },
    props: ['spring', 'classifications', 'ownerships'],
    data() {
        let photos = [];
        _.forEach(this.spring.photos, function(photo) {
            photos.push({
                id: photo.id,
                name: '',
                url: '/' + photo.path,
            });
            //photos.push(['/' + photo.path]);
        });

        return {
            leafletMap: this.spring.country == 'EE' ? true : false,
            googleMap: this.spring.country == 'EE' ? false : true,
            leafletMarkers: [latLng(this.spring.latitude, this.spring.longitude)],
            crs: projection3301,
            tms: true,
            leafletCenter: latLng(this.spring.latitude, this.spring.longitude),
            attribution: "<a href='http://www.maaamet.ee'>Maa-amet</a>",
            baseUrl: 'http://kaart.maaamet.ee/wms/fotokaart?version=1.3.0',
            transparency: false,
            maaamet_map_url: "https://tiles.maaamet.ee/tm/tms/1.0.0/hybriid/{z}/{x}/{y}.png&ASUTUS=MAAAMET&KESKKOND=LIVE&IS=TMSNAIDE",

            bounds: latLngBounds([
                [60.4349, 29.4338],
                [56.7458, 20.373]
            ]),

            tilelayers: [
                {
                    name: 'reljeef',
                    url: 'https://tiles.maaamet.ee/tm/tms/1.0.0/vreljeef/{z}/{x}/{y}.png&ASUTUS=MAAAMET&KESKKOND=ALLIKAD',
                    zindex: 1,
                    maxzoom: 11,
                },
                {
                    name: 'hybrid',
                    url: 'https://tiles.maaamet.ee/tm/tms/1.0.0/hybriid/{z}/{x}/{y}.png&ASUTUS=MAAAMET&KESKKOND=ALLIKAD',
                    zindex: 2,
                    maxzoom: 11,
                },
                {
                    name: 'pohi',
                    url: 'https://tiles.maaamet.ee/tm/tms/1.0.0/epk_vv/{z}/{x}/{y}.png&ASUTUS=MAAAMET&KESKKOND=LIVE&IS=TMSNAIDE',
                    zindex: 3,
                    maxzoom: 11,
                }

            ],
            helpDialogVisible: false,
            helptext: '',
            dialogVisible: false,
            dialogPhotoUrl: '',
            map: null,
            gmapCenter: {lat:this.spring.latitude, lng:this.spring.longitude},
            gmapMarkers: [{
                id: this.spring.id,
                name: this.spring.name,
                description: this.spring.description,
                position: {lat: this.spring.latitude, lng: this.spring.longitude}
            }],
            photos: photos,
            form: this.$inertia.form({
                '_method': 'PUT',
                id: this.spring.id,
                code: this.spring.code,
                name: this.spring.name,
                kkr_code: this.spring.kkr_code,
                latitude: this.spring.latitude,
                longitude: this.spring.longitude,
                country: this.spring.country,
                county: this.spring.county,
                settlement: this.spring.settlement,
                photos_to_add: [],
                photos_to_delete: [],
                description: this.spring.description,
                folklore: this.spring.folklore,
                classification: this.spring.classification,
                groundwater_body: this.spring.groundwater_body,
                geology: this.spring.geology,
                ownership: this.spring.ownership,
                needs_attention: this.spring.needs_attention,
                featured: this.spring.featured,
                references: getReferences(this.spring),
                database_links: getDatabaseLinks(this.spring),
                photos: [],
                status: this.spring.status,
            }, {
                bag: 'editSpring',
                resetOnSuccess: false,
            }),
        }
    },
    methods: {
        addReference() {
            this.form.references.push({});
        },
        addDatabaseLink() {
            this.form.database_links.push({});
        },
        updatePhotos(photo) {
            console.log('update photos');
            //this.form.photos.push(photo.raw);
            //savePhoto(photo);

            var data = new FormData();
            data.append('photo', photo.raw || '');
            let photo_id;
            axios.post('/photos', data).then(response => {
                console.log('resp');
                console.log(response);
                console.log('photo id: ');
                console.log(response.data.photo_id);
                photo_id = response.data.photo_id;
                this.form.photos_to_add.push(photo_id);
                //this.onSuccess(response && response.data);
                //photo_id = resolve(response && response.data);
            })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .then(function () {
                    // always executed
                    console.log('midagi');
                });
            ;
            console.log(photo_id);
        },
        showHelpDialog(helptext) {
            this.helptext = helptext;
            this.helpDialogVisible = true;
        },
        updatePhotosList(photos) {
            console.log(photos);
        },
        handleRemove(photo) {
            //console.log('handle delete');
            this.form.photos_to_delete.push(photo.id);
            /*console.log('handle remove');
            console.log(data.id);
            // delete photo
            data._method = 'DELETE';
            this.$inertia.post('/photos/' + data.id, data)*/
        },
        handlePhotoCardPreview(photo) {
            this.dialogPhotoUrl = photo.url;
            this.dialogVisible = true;
        },
        saveDraft: function (data) {
            data._method = 'PUT';
            this.$inertia.post('/springs/' + data.code, data)
        },
        submit: function (data) {
            data.status = 'submitted';
            data._method = 'PUT';
            this.$inertia.post('/springs/' + data.code , data)
        },
        save: function (data) {
            data._method = 'PUT';
            this.$inertia.post('/springs/' + data.code, data)
        },
        confirm: function (data) {
            data.status = 'confirmed';
            data._method = 'PUT';
            this.$inertia.post('/springs/' + data.code, data)
        },
        showLeaflet() {
            this.googleMap=false;
            this.leafletMap = true;
            this.leafletCenter = latLng(this.form.latitude, this.form.longitude);
        },
        updateLeafletLocation(location) {
            this.leafletMarkers = [ location.latlng ];
            let latitude = location.latlng.lat;
            let longitude = location.latlng.lng;
            this.form.latitude= parseFloat(location.latlng.lat).toFixed(6);
            this.form.longitude = parseFloat(location.latlng.lng).toFixed(6);
            this.gmapCenter = {lat:latitude, lng:longitude};
            this.gmapMarkers = [{
                position: {lat:latitude, lng:longitude}
            }];

            const geocoder = new google.maps.Geocoder()
            geocoder.geocode({ 'latLng': location.latlng }, (result, status) => {
                if (status === google.maps.GeocoderStatus.OK) {
                    let address_components = result[0].address_components;
                    let address = getAddressObject(address_components);
                    this.form.country = address.country;
                    this.form.county = address.county;
                    this.form.settlement = address.settlement;
                    //console.log(result[0].formatted_address);
                }
            })

        },
        updateLocation(location) {
            this.gmapMarkers = [{
                position: location.latLng
            }];
            this.form.latitude= location.latLng.lat();
            this.form.longitude = location.latLng.lng();
            // update leaflet map too
            this.leafletCenter = latLng(this.form.latitude, this.form.longitude);
            this.leafletMarkers = [latLng(this.form.latitude, this.form.longitude)];

            const geocoder = new google.maps.Geocoder()
            geocoder.geocode({ 'latLng': location.latLng }, (result, status) => {
                if (status === google.maps.GeocoderStatus.OK) {
                    let address_components = result[0].address_components;
                    let address = getAddressObject(address_components);
                    this.form.country = address.country;
                    this.form.county = address.county;
                    this.form.settlement = address.settlement;
                }
            })
        },
        onReady() {
            this.$refs.leafMap.mapObject.setView(this.leafletCenter, 11);
            //this.$refs.myMap.mapObject.locate({setView: true, maxZoom: 16});
        }
    },
}

function getReferences(spring) {
    if (spring.references.length !== 0) {
        return spring.references;
    }
    return [{
        url: '',
        url_title: '',
    }];
}

function getDatabaseLinks(spring) {
    if (spring.database_links.length !== 0) {
        return spring.database_links;
    }
    return [{
        database_name: '',
        spring_name: '',
        code: '',
        url: '',
    }];
}

function getAddressObject(address_components) {
    let ShouldBeComponent = {
        county: [
            "administrative_area_level_1",
            "administrative_area_level_2",
            "administrative_area_level_3",
            "administrative_area_level_4",
            "administrative_area_level_5"
        ],
        settlement: [
            "locality",
            "sublocality",
            "sublocality_level_1",
            "sublocality_level_2",
            "sublocality_level_3",
            "sublocality_level_4"
        ],
        country: ["country"]
    };

    var address = {
        county: "",
        settlement: "",
        country: ""
    };
    address_components.forEach(component => {
        for (var shouldBe in ShouldBeComponent) {
            if (ShouldBeComponent[shouldBe].indexOf(component.types[0]) !== -1) {
                if (shouldBe === "country") {
                    address[shouldBe] = component.short_name;
                } else {
                    address[shouldBe] = component.long_name;
                }
            }
        }
    });
    return address;
};
</script>
