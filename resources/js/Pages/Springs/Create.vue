<template>
    <app-layout>
        <template #header>
            <h1>
                {{ $t('springs.create_new_spring') }}
            </h1>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <jet-form-section>

                    <template #form>

                        <el-dialog :visible.sync="helpDialogVisible">
                            <div class="break-normal">{{ helptext }}</div>
                        </el-dialog>

                        <div class="p-5 bg-blue-50">
                            {{ $t('springs.all_fields_not_required') }}
                        </div>

                        <div class="flex -mx-2">
                            <div class="w-full px-2">
                                <jet-label class="inline-block font-bold" for="name" :value="$t('springs.spring_name')" />
                                <help-button @click.native="showHelpDialog( $t('springs.name_help_text') )"></help-button>
                                <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" />
                            </div>
                        </div>

                        <div class="flex -mx-2">
                            <div class="w-full px-2">
                                <jet-label class="font-bold" :value="$t('springs.location')" />

                                <estonian-maps v-if="appCountry === 'ee'" :view="'create'" :spring_location="springLocation" @changeLocation="updateLocationForMap($event)"></estonian-maps>

                                <latvian-map v-if="appCountry === 'lv'" :view="'create'" :spring_location="springLocation" @changeLocation="updateLocationForMap($event)"></latvian-map>

                                <GmapMap :center="{lat:54, lng:54}"></GmapMap>

                            </div>
                        </div>

                        <div class="flex -mx-2">
                        <div class="w-1/2 px-2">
                            <jet-label class="inline-block font-bold" for="latitude" :value="$t('springs.latitude')" />
                            <help-button @click.native="showHelpDialog( $t('springs.latitude_help_text') )"></help-button>
                            <required-field></required-field>
                            <jet-input id="latitude" type="text" class="mt-1 block w-full" v-model="form.latitude" v-on:change.native="updateLocation" />
                            <jet-input-error :message="form.error('latitude')" class="mt-2" />
                        </div>

                        <div class="w-1/2 px-2">
                            <jet-label class="inline-block font-bold" for="longitude" :value="$t('springs.longitude')" />
                            <help-button @click.native="showHelpDialog( $t('springs.longitude_help_text') )"></help-button>
                            <required-field></required-field>
                            <jet-input id="longitude" type="text" class="mt-1 block w-full" v-model="form.longitude" v-on:change.native="updateLocation" />
                            <jet-input-error :message="form.error('longitude')" class="mt-2" />
                        </div>
                        </div>


                        <div class="flex -mx-2">
                            <div class="w-1/3 px-2">
                                <jet-label class=" inline-block font-bold" for="country" :value="$t('springs.country')" />
                                <required-field></required-field>
                                <select v-model="form.country"
                                        class="block w-full bg-white border border-gray-400 hover:border-gray-500 px-2 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                                    <option value=""></option>
                                    <option v-for='data in countries' :value='data.code'>{{ $t('springs.countries.'+data.code) }}</option>
                                </select>
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
                        <jet-input-error :message="form.error('country')" class="mt-2" />

                        <div class="col-span-12 sm:col-span-4">
                            <jet-label class="font-bold" for="references" :value="$t('springs.references')" />
                            <div id="references">
                                <div v-for="(reference, index) in form.references" class="py-1">
                                    <jet-input v-model="reference.url_title" :placeholder="$t('springs.url_title')"/>
                                    <jet-input v-model="reference.url" type="url" :placeholder="$t('springs.url')"/>
                                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3" @click="addReference">+</button>
                                    <jet-input-error :message="form.error('references.'+index+'.url')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="col-span-12 sm:col-span-4">
                            <jet-label class="font-bold inline-block" for="photos" :value="$t('springs.photos')" />
                            <help-button @click.native="showHelpDialog( $t('springs.photos_help_text') )"></help-button>
                            <el-upload
                                action="/"
                                list-type="picture-card"
                                accept="image/*"
                                :auto-upload="false"
                                :on-preview="handlePhotoCardPreview"
                                :on-change="updatePhotos">
                                <i class="el-icon-plus"></i>
                            </el-upload>
                            <el-dialog :visible.sync="dialogVisible">
                                <img width="100%" :src="dialogPhotoUrl" alt="" />
                                <div v-if="is('admin') || is('super-admin')">
                                    <el-checkbox @change="photoFeaturedChange(dialogPhotoId, $event)" v-model="dialogPhotoFeatured" />
                                    {{ $t('springs.featured') }}
                                </div>
                            </el-dialog>
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <jet-label class="inline-block font-bold" for="description" :value="$t('springs.description')" />
                            <help-button @click.native="showHelpDialog( $t('springs.description_help_text') )"></help-button>
                            <required-field></required-field>
                            <textarea id="description" type="textarea" class="px-2 mt-1 block w-full border" rows="5" v-model="form.description" ></textarea>
                            <jet-input-error :message="form.error('description')" class="mt-2" />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <jet-label class="font-bold" for="folklore" :value="$t('springs.folklore')" />
                            <textarea id="folklore" type="textarea" class="px-2 mt-1 block w-full border"  rows="5" v-model="form.folklore"></textarea>
                        </div>

                        <div v-if="can('edit spring')">

                            <div class="flex -mx-2">
                                <div class="w-1/2 px-2">
                                    <jet-label class="font-bold" for="kkr_code" :value="$t('springs.kkr_code')" />
                                    <jet-input id="kkr_code" type="text" class="mt-1 block w-full" v-model="form.kkr_code" />
                                </div>
                            </div>

                        <div class="col-span-12 sm:col-span-4">
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
                                    <option v-for='data in classifications' :value='data.id'>{{ $t(data.name) }}</option>
                                </select>
                            </div>

                            <div class="w-1/2 px-2">
                                <jet-label class="font-bold" for="groundwater_body" :value="$t('springs.groundwater_body')"></jet-label>
                                <jet-input id="groundwater_body" type="text" class="mt-1 block w-full" v-model="form.groundwater_body" />
                            </div>
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <jet-label class="font-bold" for="folklore" :value="$t('springs.geology')" />
                            <textarea id="geology" type="textarea" class="px-2 mt-1 block w-full border" rows="5" v-model="form.geology"></textarea>
                        </div>

                        <div class="w-1/2 px-2 py-2">
                            <jet-label class="font-bold" for="ownership" :value="$t('springs.ownership')" />
                            <select id="ownership" v-model="form.ownership"
                                    class="block w-full bg-white border border-gray-400 hover:border-gray-500 px-2 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                                <option value=""></option>
                                <option v-for='data in ownerships' :value='data.id'>{{ $t(data.name) }}</option>
                            </select>
                        </div>

                            <div class="px-2 py-2">
                                <el-checkbox v-model="form.needs_attention" name="needs_attention">
                                    <jet-label for="needs_attention" :value="$t('springs.needs_attention')" />
                                </el-checkbox>
                            </div>
                            <div class="px-2 py-2">
                                <el-checkbox v-model="form.featured" name="featured">
                                    <jet-label for="featured" :value="$t('springs.featured')" />
                                </el-checkbox>
                            </div>
                            <div class="px-2 py-2">
                                <el-checkbox v-model="form.unlisted" name="unlisted">
                                    <jet-label for="unlisted" :value="$t('springs.unlisted')" />
                                </el-checkbox>
                            </div>

                        </div>

                    </template>
                        <template #actions>
                            <div class="text-gray-500 mr-2" v-if="processingPhotos">{{ $t('springs.messages.uploading_photos') }}</div>
                            <jet-secondary-button :class="{ 'opacity-25': processingPhotos }" :disabled="processingPhotos" type="submit" @click.native="saveDraft(form)">
                                {{ $t('springs.save_as_draft') }}</jet-secondary-button>
                            <jet-button class="ml-2" :class="{ 'opacity-25': processingPhotos }" :disabled="processingPhotos" type="submit" @click.native="submit(form)">
                                {{ $t('springs.submit') }}</jet-button>
                        </template>

            </jet-form-section>
        </div>

    </app-layout>
</template>

<script>
import AppLayout from './../../Layouts/AppLayout'
import JetActionMessage from "../../Jetstream/ActionMessage";
import JetButton from "../../Jetstream/Button";
import JetFormSection from "../../Jetstream/FormSection";
import JetInput from "../../Jetstream/Input";
import JetInputError from "../../Jetstream/InputError";
import JetLabel from "../../Jetstream/Label";
import JetSecondaryButton from "../../Jetstream/SecondaryButton";
import HelpButton from '../../Components/HelpButton';
import RequiredField from '../../Components/RequiredField';
import { gmapApi } from 'gmap-vue';
import EstonianMaps from "../../Components/Maps/Estonia/EstonianMaps";
import LatvianMap from "../../Components/Maps/LatvianMap";
import { latLng } from "leaflet";

export default {
    components: {
        AppLayout,
        JetActionMessage,
        JetButton,
        JetFormSection,
        JetInput,
        JetInputError,
        JetLabel,
        JetSecondaryButton,
        HelpButton,
        RequiredField,
        gmapApi,
        EstonianMaps,
        LatvianMap,
    },

    props: ['countries', 'classifications', 'ownerships'],

    data() {
        let country = process.env.MIX_APP_COUNTRY;
        return {
            appCountry: country,
            helpDialogVisible: false,
            helptext: '',
            dialogVisible: false,
            dialogPhoto: null,
            dialogPhotoUrl: '',
            dialogPhotoId: null,
            dialogPhotoFeatured: false,
            map: null,
            processingPhotos: false,
            springLocation: null,
            form: this.$inertia.form({
                '_method': 'POST',
                name: this.name,
                kkr_code: this.kkr_code,
                latitude: this.latitude,
                longitude: this.longitude,
                country: this.country,
                county: this.county,
                settlement: this.settlement,
                description: this.description,
                photo_ids: [],
                classification: '',
                groundwater_body: this.groundwater_body,
                ownership: '',
                status: 'draft',
                needs_attention: 0,
                featured: 0,
                unlisted: 0,
                references: [{
                    url: '',
                    url_title: '',
                }],
                database_links: [{
                    database_name: '',
                    spring_name: '',
                    code: '',
                    url: '',
                }],
                photos: [],
            }, {
                bag: 'addSpring',
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
        saveDraft: function (data) {
            data._method = 'POST';
            this.clearLocalStorage();
            this.$inertia.post('/springs', data)
        },
        submit: function (data) {
            data.status = 'submitted';
            data._method = 'POST';
            this.clearLocalStorage();
            this.$inertia.post('/springs', data)
        },
        updatePhotos(photo) {
            this.processingPhotos = true;
            let file = photo;
            const isIMAGE = (file.raw.type === 'image/jpeg' || file.raw.type === 'image/png');
            if (!isIMAGE) {
                this.$message.error('Only upload jpg/png picture!');
                return false;
            }

            // upload photo
            var data = new FormData();
            data.append('photo', photo.raw || '');
            //let photo_id = this.$inertia.post('/photos', data);
            let photo_id;
            axios.post('/photos', data).then(response => {
                photo_id = response.data.photo_id;
                this.form.photo_ids.push(photo_id);
                photo.id = photo_id;
                this.processingPhotos = false;
            })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .then(function () {
                    // always executed
                    console.log('upload complete');
                });
            ;
        },
        showHelpDialog(helptext) {
            this.helptext = helptext;
            this.helpDialogVisible = true;
        },
        handlePhotoCardPreview(photo) {
            this.dialogPhoto = photo;
            this.dialogPhotoUrl = photo.url;
            this.dialogPhotoId = photo.id;
            this.dialogPhotoFeatured = false;
            if (photo.featured) {
                this.dialogPhotoFeatured = true;
            }
            this.dialogVisible = true;
        },
        photoFeaturedChange(photo_id, featured) {
            let data = {
                'featured': featured
            }
            axios.put('/photos/'+photo_id, data).then(response => {
                this.dialogPhoto.featured = featured;
            })
            .catch(function (error) {
                // handle error
                console.log(error);
            })
            .then(function () {
                // always executed
            });
        },
        updateLocation() {
            this.springLocation = {lat: this.form.latitude, lng: this.form.longitude};
        },
        updateLocationForMap(location) {
            this.springLocation = location.latlng;
            this.form.latitude= parseFloat(location.latlng.lat).toFixed(6);
            this.form.longitude = parseFloat(location.latlng.lng).toFixed(6);
            this.updateAddressFields( location.latlng )
        },
        updateAddressFields(location) {
            const geocoder = new google.maps.Geocoder()
            geocoder.geocode({ 'latLng': location }, (result, status) => {
                if (status === google.maps.GeocoderStatus.OK) {
                    let address_components = result[0].address_components;
                    let address = getAddressObject(address_components);
                    this.form.country = address.country;
                    this.form.county = address.county;
                    this.form.settlement = address.settlement;
                }
            })
        },
        clearLocalStorage() {
            let keysToRemove = [
                "create_spring_form_name",
                "create_spring_form_latitude",
                "create_spring_form_longitude",
                "create_spring_form_country",
                "create_spring_form_county",
                "create_spring_form_settlement",
                "create_spring_form_references",
                "create_spring_form_description",
                "create_spring_form_folklore",
                "create_spring_form_kkr_code",
                "create_spring_form_database_links",
                "create_spring_form_classification",
                "create_spring_form_groundwater_body",
                "create_spring_form_geology",
                "create_spring_form_ownership"
            ];
            _.forEach(keysToRemove, function(key) {
                localStorage.removeItem(key);
            });
        },
    },
    created() {
        if (localStorage.create_spring_form_name) {
            this.form.name = localStorage.create_spring_form_name;
        }
        if (localStorage.create_spring_form_latitude && localStorage.create_spring_form_longitude) {
            this.form.latitude = localStorage.create_spring_form_latitude;
            this.form.longitude = localStorage.create_spring_form_longitude;
            this.springLocation = latLng(this.form.latitude, this.form.longitude);
        }
        if (localStorage.create_spring_form_country) {
            this.form.country = localStorage.create_spring_form_country;
        }
        if (localStorage.create_spring_form_county) {
            this.form.county = localStorage.create_spring_form_county;
        }
        if (localStorage.create_spring_form_settlement) {
            this.form.settlement = localStorage.create_spring_form_settlement;
        }
        if (localStorage.create_spring_form_description) {
            this.form.description = localStorage.create_spring_form_description;
        }
        if (localStorage.create_spring_form_folklore) {
            this.form.folklore = localStorage.create_spring_form_folklore;
        }
        if (localStorage.create_spring_form_kkr_code) {
            this.form.kkr_code = localStorage.create_spring_form_kkr_code;
        }
        if (localStorage.create_spring_form_references) {
            this.form.references = JSON.parse(localStorage.create_spring_form_references);
        }
        if (localStorage.create_spring_form_database_links) {
            this.form.database_links = JSON.parse(localStorage.create_spring_form_database_links);
        }
        if (localStorage.create_spring_form_classification) {
            this.form.classification = localStorage.create_spring_form_classification;
        }
        if (localStorage.create_spring_form_groundwater_body) {
            this.form.groundwater_body = localStorage.create_spring_form_groundwater_body;
        }
        if (localStorage.create_spring_form_geology) {
            this.form.geology = localStorage.create_spring_form_geology;
        }
        if (localStorage.create_spring_form_ownership) {
            this.form.ownership = localStorage.create_spring_form_ownership;
        }
    },
    watch: {
        'form.name': function (newValue) {
            localStorage.setItem('create_spring_form_name', newValue);
        },
        'form.latitude': function (newValue) {
            localStorage.setItem('create_spring_form_latitude', newValue);
        },
        'form.longitude': function (newValue) {
            localStorage.setItem('create_spring_form_longitude', newValue);
        },
        'form.country': function (newValue) {
            localStorage.setItem('create_spring_form_country', newValue);
        },
        'form.county': function (newValue) {
            localStorage.setItem('create_spring_form_county', newValue);
        },
        'form.settlement': function (newValue) {
            localStorage.setItem('create_spring_form_settlement', newValue);
        },
        'form.references': {
            handler:function(newValue) {
                localStorage.setItem('create_spring_form_references', JSON.stringify(newValue));
            },
            deep:true
        },
        'form.description': function(newValue) {
            localStorage.setItem('create_spring_form_description', newValue);
        },
        'form.folklore': function(newValue) {
            localStorage.setItem('create_spring_form_folklore', newValue);
        },
        'form.kkr_code': function(newValue) {
            localStorage.setItem('create_spring_form_kkr_code', newValue);
        },
        'form.database_links': {
            handler:function(newValue) {
                localStorage.setItem('create_spring_form_database_links', JSON.stringify(newValue));
            },
            deep:true
        },
        'form.classification': function(newValue) {
            localStorage.setItem('create_spring_form_classification', newValue);
        },
        'form.groundwater_body': function(newValue) {
            localStorage.setItem('create_spring_form_groundwater_body', newValue);
        },
        'form.geology': function(newValue) {
            localStorage.setItem('create_spring_form_geology', newValue);
        },
        'form.ownership': function(newValue) {
            localStorage.setItem('create_spring_form_ownership', newValue);
        },
    },
}

async function savePhoto(photo) {

var data = new FormData();
data.append('photo', photo.raw || '');
//let photo_id = this.$inertia.post('/photos', data);
let photo_id;
let res = await axios.post('/photos', data);

console.log(`Status code: ${res.status}`);
console.log(`Status text: ${res.statusText}`);
console.log(`Request method: ${res.request.method}`);
console.log(`Path: ${res.request.path}`);
console.log(`Date: ${res.headers.date}`);
console.log(`Data: ${res.data}`);
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
}

</script>
