<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create new spring
            </h2>
        </template>


        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <jet-form-section>

                    <template #form>

                        <div class="flex -mx-2 py-2">
                            <div class="w-full px-2">
                                <jet-label class="font-bold" for="name" value="Name" />
                                <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" />
                                <small id="name_help_block" class="form-text text-muted">
                                    'springs.name_help_text'
                                </small>
                            </div>
                        </div>

                        <div class="flex -mx-2 py-2">
                        <div class="w-1/2 px-2">
                            <jet-label class="font-bold" for="kkr_code" value="KKR code" />
                            <jet-input id="kkr_code" type="text" class="mt-1 block w-full" v-model="form.kkr_code" />
                        </div>
                        </div>

                        <div class="flex -mx-2 py-2">
                            <div class="w-full px-2">
                                <jet-label class="font-bold" value="Location" />
                                <div class="z-depth-1-half map-container" style="height:400px;">
                                    <GmapMap
                                        :center="{lat:58.279, lng:26.054}"
                                        :zoom="7"
                                        map-type-id="terrain"
                                        style="width: 100%; height: 100%"
                                        @click="updateLocation"
                                    >
                                        <GmapMarker
                                            :key="index"
                                            v-for="(location, index) in markers"
                                            :position="location.position"
                                            :draggable="true"
                                            @dragend="updateLocation"
                                        />
                                    </GmapMap>
                                </div>
                            </div>
                        </div>

                        <div class="flex -mx-2 py-2">
                        <div class="w-1/2 px-2">
                            <jet-label class="font-bold" for="latitude" value="$trans('latitude')" />
                            <jet-input id="latitude" type="text" class="mt-1 block w-full" v-model="form.latitude" />
                            <small id="latitude_help_block" class="form-text text-muted">
                                'springs.latitude_help_text'
                            </small>
                            <jet-input-error :message="form.error('latitude')" class="mt-2" />
                        </div>

                        <div class="w-1/2 px-2">
                            <jet-label class="font-bold" for="longitude" value="springs.longitude" />
                            <jet-input id="longitude" type="text" class="mt-1 block w-full" v-model="form.longitude" />
                            <small id="longitude_help_block" class="form-text text-muted">
                                'springs.longitude_help_text'
                            </small>
                            <jet-input-error :message="form.error('longitude')" class="mt-2" />
                        </div>
                        </div>


                        <div class="col-span-6 sm:col-span-4">
                            <jet-label class="font-bold" for="country" value="Country" />
                            <jet-input id="country" type="text" class="mt-1 block w-full" v-model="form.country" />
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <jet-label class="font-bold" for="county" value="County" />
                            <jet-input id="county" type="text" class="mt-1 block w-full" v-model="form.county" />
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <jet-label class="font-bold" for="settlement" value="Settlement" />
                            <jet-input id="settlement" type="text" class="mt-1 block w-full" v-model="form.settlement" />
                        </div>

                        <div class="col-span-12 sm:col-span-4">
                            <jet-label class="font-bold" for="references" value="References" />

                            <div id="references">
                                <div v-for="reference in form.references" :key="reference.id">
                                    <jet-input :id="reference.url_id" v-model="reference.url" type="url" placeholder="URL"/>
                                    <jet-input :id="reference.url_title_id" v-model="reference.url_title" placeholder="URL title"/>
                                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3" @click="addReference">+</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-span-12 sm:col-span-4">
                            <jet-label class="font-bold" for="photos" value="Photos" />
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
                            </el-dialog>
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <jet-label class="font-bold" for="description" value="Description" />
                            <textarea id="description" type="textarea" class="px-2 mt-1 block w-full border" rows="5" v-model="form.description" ></textarea>
                            <small id="description_help_block" class="form-text text-muted">
                                'springs.description_help_text'
                            </small>
                            <jet-input-error :message="form.error('description')" class="mt-2" />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <jet-label class="font-bold" for="folklore" value="Folklore" />
                            <textarea id="folklore" type="textarea" class="px-2 mt-1 block w-full border"  rows="5" v-model="form.folklore"></textarea>
                        </div>

                        <div class="col-span-12 sm:col-span-4">
                            <jet-label class="font-bold" for="database_links" value="Link with other databases" />
                            <div id="database_links">
                                <div v-for="link in form.database_links" :key="link.id">
                                    <jet-input :id="link.database_name_id" v-model="link.database_name" placeholder="Database name"/>
                                    <jet-input :id="link.spring_name_id" v-model="link.spring_name" placeholder="Spring name"/>
                                    <jet-input :id="link.code_id" v-model="link.code" placeholder="Code"/>
                                    <jet-input :id="link.url_id" v-model="link.url" placeholder="URL"/>
                                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3" @click="addDatabaseLink">+</button>
                                </div>
                            </div>
                        </div>

                        <div class="flex -mx-2 py-2">
                            <div class="w-1/2 px-2">
                                <jet-label class="font-bold" for="classification" value="Spring classification" />
                                <select id="classification" v-model="form.classification"
                                    class="block w-full bg-white border border-gray-400 hover:border-gray-500 px-2 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                                    <option v-for='data in classifications' :selected="classification === data.id" :value='data.id'>{{ data.name }}</option>
                                </select>
                            </div>

                            <div class="w-1/2 px-2">
                                <jet-label class="font-bold" for="groundwater_body" value="Groundwater body"></jet-label>
                                <jet-input id="groundwater_body" type="text" class="mt-1 block w-full" v-model="form.groundwater_body" />
                            </div>
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <jet-label class="font-bold" for="folklore" value="Geology" />
                            <textarea id="geology" type="textarea" class="px-2 mt-1 block w-full border" rows="5" v-model="form.geology"></textarea>
                        </div>

                        <div class="w-1/2 px-2 py-2">
                            <jet-label class="font-bold" for="ownership" value="Ownership" />
                            <select id="ownership" v-model="form.ownership"
                                    class="block w-full bg-white border border-gray-400 hover:border-gray-500 px-2 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                                <option v-for='data in ownerships' :selected="ownership === data.id" :value='data.id'>{{ data.name }}</option>
                            </select>
                        </div>

                        <!--<div class="w-1/2 px-2 py-2">
                            <jet-label class="font-bold" for="status" value="Status" />
                            <select id="status" v-model="form.status"
                                    class="block w-full bg-white border border-gray-400 hover:border-gray-500 px-2 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                                <option v-for='data in statuses' :selected="status === data.id" :value='data.id'>{{ data.name }}</option>
                            </select>
                        </div>-->
                        <div class="px-2 py-2">
                            <el-checkbox v-model="form.needs_attention" name="needs_attention"><jet-label for="needs_attention" value="Needs special attention" /></el-checkbox>
                        </div>
                        <div class="px-2 py-2">
                            <el-checkbox v-model="form.featured" name="featured"><jet-label for="featured" value="Featured" /></el-checkbox>
                        </div>

                    </template>
                        <template #actions>
                            <jet-secondary-button type="submit" @click.native="saveDraft(form)">Save as draft</jet-secondary-button>
                            <jet-button class="ml-2" type="submit" @click.native="submit(form)">Submit</jet-button>
                        </template>
                        <!--<button type="submit" class="text-white bg-blue-500 border text-xs font-semibold px-4 py-1 leading-normal">Save as draft</button>
                        <button type="submit" class="text-white bg-blue-500 border text-xs font-semibold px-4 py-1 leading-normal">Submit</button>-->

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
import { gmapApi } from 'gmap-vue';

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
        gmapApi,
    },

    props: ['classifications', 'ownerships', 'statuses', 'classification', 'ownership', 'status'],

    data() {
        return {
            dialogVisible: false,
            dialogPhotoUrl: '',
            map: null,
            markers: [],
            references_counter: 1,
            database_links_counter: 1,
            form: this.$inertia.form({
                '_method': 'PUT',
                name: this.name,
                kkr_code: this.kkr_code,
                latitude: this.latitude,
                longitude: this.longitude,
                country: this.country,
                county: this.county,
                settlement: this.settlement,
                description: this.description,
                classification: 'rheocrene',
                groundwater_body: this.groundwater_body,
                ownership: 'private_property',
                status: 'draft',
                needs_attention: 0,
                featured: 0,
                references: [{
                    url_id: 'references[1][url]',
                    url_title_id: 'references[1][url_title]',
                    url: '',
                    url_title: '',
                }],
                database_links: [{
                    database_name_id: 'database_links[1][database_name]',
                    spring_name_id: 'database_links[1][spring_name]',
                    code_id: 'database_links[1][code]',
                    url_id: 'database_links[1][url]',
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
            this.form.references.push({
                url_id: 'references['+ ++this.references_counter +'][url]',
                url_title_id: 'references['+ this.references_counter +'][url_title]',
            });
        },
        addDatabaseLink() {
            this.form.database_links.push({
                database_name_id: 'database_links['+ ++this.database_links_counter +'][database_name]',
                spring_name_id: 'database_links['+ ++this.database_links_counter +'][spring_name]',
                code_id: 'database_links['+ ++this.database_links_counter +'][code]',
                url_id: 'database_links['+ this.database_links_counter +'][url]',
            });
        },
        saveDraft: function (data) {
            data._method = 'POST';

            /*this.$inertia.post('/springs', {
                title: this.form.name,
                description: this.form.description,
                latitude: this.form.latitude,
                longitude: this.form.latitude,
                references: this.form.references,
                photos: this.photos
            })*/

            this.$inertia.post('/springs', data)
        },
        submit: function (data) {
            data.status = 'submitted';
            data._method = 'POST';
            this.$inertia.post('/springs', data)
        },
        updatePhotos(photo) {
            //this.form.photos.push(photo.raw);
            //savePhoto(photo);
            //TODO upload photo
            var data = new FormData();
            data.append('photo', photo.raw || '');
            //let photo_id = this.$inertia.post('/photos', data);
            let photo_id;
            axios.post('/photos', data).then(response => {
                console.log('resp');
                console.log(response);
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
        handlePhotoCardPreview(photo) {
            this.dialogPhotoUrl = photo.url;
            this.dialogVisible = true;
        },
        updateLocation(location) {
            this.markers = [{
                position: location.latLng
            }];
            this.form.latitude= location.latLng.lat();
            this.form.longitude = location.latLng.lng();

            const geocoder = new google.maps.Geocoder()
            geocoder.geocode({ 'latLng': location.latLng }, (result, status) => {
                if (status === google.maps.GeocoderStatus.OK) {
                    let address_components = result[0].address_components;
                    console.log(address_components);
                    let address = getAddressObject(address_components);
                    this.form.country = address.country;
                    this.form.county = address.county;
                    this.form.settlement = address.settlement;
                    //console.log(result[0].formatted_address);
                }
            })
        }
    }
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
};

</script>