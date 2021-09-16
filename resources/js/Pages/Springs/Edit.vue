<template>
    <app-layout>
        <template #header>
            <h1>
                {{ $t('springs.edit_spring') }}
            </h1>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <jet-form-section>

                <template #form>

                    <el-dialog :visible.sync="helpDialogVisible">
                        <div class="break-normal">{{ helptext }}</div>
                    </el-dialog>

                    <div class="py-2" v-if="(spring.user ? (spring.user.id !== $page.user.id) : false)">
                        <jet-label class="font-bold" :value="$t('springs.added_by')" />
                        <div>{{ spring.user.name }}</div>
                    </div>

                    <div class="flex -mx-2">
                        <div class="w-full px-2">
                            <jet-label class="font-bold inline-block" for="name" :value="$t('springs.spring_name')" />
                            <help-button @click.native="showHelpDialog( $t('springs.name_help_text') )"></help-button>
                            <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" />
                        </div>
                    </div>


                    <div class="flex -mx-2 py-2">
                        <div class="w-full px-2">
                            <jet-label class="font-bold" :value="$t('springs.location')" />

                            <estonian-map v-if="appCountry === 'ee'" :view="'edit'" :spring="spring" @changeLocation="updateLocationForMap($event)"></estonian-map>

                            <latvian-map v-if="appCountry === 'lv'" :view="'edit'" :spring="spring" @changeLocation="updateLocationForMap($event)"></latvian-map>

                            <GmapMap :center="{lat:54, lng:54}" ></GmapMap>

                        </div>
                    </div>

                    <div class="flex ">
                        <div class="w-1/2 px-2">
                            <jet-label class="font-bold inline-block" for="latitude" :value="$t('springs.latitude')" />
                            <help-button @click.native="showHelpDialog( $t('springs.latitude_help_text') )"></help-button>
                            <jet-input id="latitude" type="text" class="mt-1 block w-full" v-model="form.latitude" v-on:change.native="updateLocation" />
                            <jet-input-error :message="form.error('latitude')" class="mt-2" />
                        </div>

                        <div class="w-1/2 px-2">
                            <jet-label class="font-bold inline-block" for="longitude" :value="$t('springs.longitude')" />
                            <help-button @click.native="showHelpDialog( $t('springs.longitude_help_text') )"></help-button>
                            <jet-input id="longitude" type="text" class="mt-1 block w-full" v-model="form.longitude" v-on:change.native="updateLocation" />
                            <jet-input-error :message="form.error('longitude')" class="mt-2" />
                        </div>
                    </div>


                    <div class="flex">
                        <div class="w-1/3 px-2">
                            <jet-label class="font-bold" for="country" :value="$t('springs.country')" />
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
                    <jet-input-error :message="form.error('country')" class="mt-2 px-2" />

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
                            <el-checkbox :true-label="1" false-label="0" v-model="form.needs_attention" name="needs_attention">
                                <jet-label for="needs_attention" :value="$t('springs.needs_attention')" />
                            </el-checkbox>
                        </div>
                        <div class="px-2 py-2">
                            <el-checkbox :true-label="1" false-label="0" v-model="form.featured" name="featured">
                                <jet-label for="featured" :value="$t('springs.featured')" />
                            </el-checkbox>
                        </div>
                        <div class="px-2 py-2">
                            <el-checkbox :true-label="1" false-label="0" v-model="form.unlisted" name="unlisted">
                                <jet-label for="unlisted" :value="$t('springs.unlisted')" />
                            </el-checkbox>
                        </div>
                        <div class="px-2 py-2">
                            <el-checkbox :true-label="1" false-label="0" v-model="form.not_a_spring" name="not_a_spring">
                                <jet-label for="not_a_spring" :value="$t('springs.not_a_spring')" />
                            </el-checkbox>
                        </div>

                    </div>

            </template>

            <template #actions>
                <div class="text-gray-500 mr-2" v-if="processingPhotos">{{ $t('springs.messages.uploading_photos') }}</div>

                <jet-secondary-button :class="{ 'opacity-25': processingPhotos }" :disabled="processingPhotos" v-if="form.status === 'draft'" type="submit" @click.native="saveDraft(form)">
                    {{ $t('springs.save_as_draft') }}</jet-secondary-button>
                <jet-button :class="{ 'opacity-25': processingPhotos }" :disabled="processingPhotos" v-if="form.status === 'draft'" class="ml-2" type="submit" @click.native="submit(form)">
                    {{ $t('springs.submit') }}</jet-button>

                <jet-secondary-button :class="{ 'opacity-25': processingPhotos }" :disabled="processingPhotos" v-if="can('edit spring') && form.status === 'submitted'" class="ml-2" type="submit" @click.native="save(form)">
                    {{ $t('springs.save') }}</jet-secondary-button>
                <jet-button :class="{ 'opacity-25': processingPhotos }" :disabled="processingPhotos" v-if="can('edit spring') && form.status === 'submitted'" class="ml-2" type="submit" @click.native="confirm(form)">
                    {{ $t('springs.confirm') }}</jet-button>

                <jet-button :class="{ 'opacity-25': processingPhotos }" :disabled="processingPhotos" v-if="can('edit spring') && form.status === 'confirmed'" class="ml-2" type="submit" @click.native="save(form)">
                    {{ $t('springs.save') }}</jet-button>
            </template>

        </jet-form-section>

            <div v-if="can('delete spring')">
                <jet-section-border />
                <delete-spring class="mt-10 sm:mt-0" :spring_id="form.id" />
            </div>

        </div>
    </app-layout>
</template>


<script>
import AppLayout from './../../Layouts/AppLayout'
import JetFormSection from "./../../Jetstream/FormSection";
import JetSectionBorder from './../../Jetstream/SectionBorder'
import JetInput from "../../Jetstream/Input";
import JetInputError from "../../Jetstream/InputError";
import JetLabel from "../../Jetstream/Label";
import JetButton from "../../Jetstream/Button";
import JetSecondaryButton from "../../Jetstream/SecondaryButton";
import HelpButton from '../../Components/HelpButton';
import DeleteSpring from './DeleteSpring'
import { gmapApi } from 'gmap-vue';
import EstonianMap from "../../Components/Maps/EstonianMap";
import LatvianMap from "../../Components/Maps/LatvianMap";

export default {
    components: {
        AppLayout,
        JetFormSection,
        JetSectionBorder,
        JetInput,
        JetInputError,
        JetLabel,
        JetButton,
        JetSecondaryButton,
        HelpButton,
        DeleteSpring,
        gmapApi,
        EstonianMap,
        LatvianMap,
    },
    props: ['spring', 'countries', 'classifications', 'ownerships'],
    data() {
        let photos = [];
        _.forEach(this.spring.photos, function(photo) {
            photos.push({
                id: photo.id,
                name: '',
                url: '/' + photo.path,
            });
        });

        let ee_spring = true;
        if (this.spring && this.spring.country !== 'EE') {
            ee_spring = false;
        }

        return {
            appCountry: process.env.MIX_APP_COUNTRY,
            ee_spring: ee_spring,
            helpDialogVisible: false,
            helptext: '',
            dialogVisible: false,
            dialogPhotoUrl: '',
            map: null,
            photos: photos,
            processingPhotos: false,
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
                unlisted: this.spring.unlisted,
                not_a_spring: this.spring.not_a_spring,
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
            this.processingPhotos = true;
            var data = new FormData();
            data.append('photo', photo.raw || '');
            let photo_id;
            axios.post('/photos', data).then(response => {
                photo_id = response.data.photo_id;
                this.form.photos_to_add.push(photo_id);
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
        updateLocation() {
            let latitude = Number(this.form.latitude);
            let longitude = Number(this.form.longitude);
            if ( latitude && longitude ) {
                this.springLocation = {lat: latitude, lng: longitude};
                this.updateAddressFields( this.springLocation );
                this.leafletMapObject.setView( this.springLocation );
            }
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
        updateLocationForMap(location) {
            this.springLocation = location.latlng;
            this.form.latitude= parseFloat(location.latlng.lat).toFixed(6);
            this.form.longitude = parseFloat(location.latlng.lng).toFixed(6);
            this.updateAddressFields( location.latlng )
        },
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
