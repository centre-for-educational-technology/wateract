<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit spring
            </h2>
        </template>

        <jet-form-section>

            <template #form>
        <div class="py-6">

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">



                        <div class="flex -mx-2">
                            <div class="w-full px-2">
                                <jet-label class="font-bold" for="title" value="Name" />
                                <jet-input id="title" type="text" class="mt-1 block w-full" v-model="form.title" />
                            </div>
                        </div>

                    <div class="flex -mx-2">
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

                    <div class="flex -mx-2">
                        <div class="w-1/2 px-2">
                            <jet-label class="font-bold" for="latitude" value="$trans('latitude')" />
                            <jet-input id="latitude" type="text" class="mt-1 block w-full" v-model="form.latitude" />
                            <!--<jet-input-error :message="form.error('latitude')" class="mt-2" />-->
                        </div>

                        <div class="w-1/2 px-2">
                            <jet-label class="font-bold" for="longitude" value="springs.longitude" />
                            <jet-input id="longitude" type="text" class="mt-1 block w-full" v-model="form.longitude" />
                            <!--<jet-input-error :message="form.error('longitude')" class="mt-2" />-->
                        </div>
                    </div>


                    <div class="px-2 py-2">
                        <jet-label class="font-bold" for="country" value="Country" />
                        <jet-input id="country" type="text" class="mt-1 block w-full" v-model="form.country" />
                    </div>
                    <div class="px-2 py-2">
                        <jet-label class="font-bold" for="county" value="County" />
                        <jet-input id="county" type="text" class="mt-1 block w-full" v-model="form.county" />
                    </div>
                    <div class="px-2 py-2">
                        <jet-label class="font-bold" for="settlement" value="Settlement" />
                        <jet-input id="settlement" type="text" class="mt-1 block w-full" v-model="form.settlement" />
                    </div>

                    <div class="col-span-12 sm:col-span-4">
                        <jet-label class="font-bold" for="references" value="References" />

                        <div id="references">
                            <div v-for="reference in form.references" :key="reference.id">
                                <jet-input :id="reference.url_id" v-model="reference.url" placeholder="URL"/>
                                <jet-input :id="reference.url_title_id" v-model="reference.url_title" placeholder="URL title"/>
                                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3" @click="addReference">+</button>
                            </div>
                        </div>
                    </div>

                    <div class="px-2 py-2">
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

                    <div class="px-2 py-2">
                        <jet-label class="font-bold" for="description" value="Description" />
                        <textarea id="description" type="textarea" class="px-2 mt-1 block w-full border" rows="5" v-model="spring.description" ></textarea>
                        <small id="description_help_block" class="form-text text-muted">
                            'springs.description_help_text'
                        </small>
                        <jet-input-error :message="form.error('description')" class="mt-2" />
                    </div>

                    <div class="px-2 py-2">
                        <jet-label class="font-bold" for="folklore" value="Folklore" />
                        <textarea id="folklore" type="textarea" class="px-2 mt-1 block w-full border"  rows="5" v-model="form.folklore"></textarea>
                    </div>

                    <div class="px-2 py-2">
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

                    <div class="px-2 py-2">
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

                    <div class="px-2 py-2">
                        <el-checkbox v-model="form.needs_attention" name="needs_attention"><jet-label for="needs_attention" value="Needs special attention" /></el-checkbox>
                    </div>
                    <div class="px-2 py-2">
                        <el-checkbox v-model="form.featured" name="featured"><jet-label for="featured" value="Featured" /></el-checkbox>
                    </div>




                </div>
            </div>

        </div>
            </template>

            <template #actions>
                <jet-secondary-button v-if="form.status !== 'submitted'" type="submit" @click.native="saveDraft(form)">Save as draft</jet-secondary-button>
                <jet-button v-if="form.status !== 'submitted'" class="ml-2" type="submit" @click.native="submit(form)">Submit</jet-button>
                <jet-button v-if="form.status === 'submitted'" class="ml-2" type="submit" @click.native="confirm(form)">Confirm</jet-button>
            </template>
        </jet-form-section>
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
import { gmapApi } from 'gmap-vue';

export default {
    components: {
        AppLayout,
        JetFormSection,
        JetInput,
        JetInputError,
        JetLabel,
        JetButton,
        JetSecondaryButton,
        gmapApi,
    },
    props: ['spring', 'classifications', 'ownerships'],
    data() {
        return {
            dialogVisible: false,
            dialogPhotoUrl: '',
            map: null,
            markers: [],
            references_counter: this.spring.references.length,
            database_links_counter: this.spring.database_links.length,
            form: this.$inertia.form({
                '_method': 'PUT',
                title: this.spring.title,
                kkr_code: this.spring.kkr_code,
                latitude: this.spring.latitude,
                longitude: this.spring.longitude,
                country: this.spring.country,
                county: this.spring.county,
                settlement: this.spring.settlement,
                description: this.spring.description,
                folklore: this.spring.folklore,
                classification: this.spring.classification,
                groundwater_body: this.spring.groundwater_body,
                geology: this.spring.geology,
                ownership: this.spring.ownership,
                needs_attention: this.spring.needs_attention,
                featured: this.spring.featured,
                references: this.spring.references,
                database_links: this.spring.database_links,
                photos: [],
                status: this.spring.status,
            }, {
                bag: 'addSpring',
                resetOnSuccess: false,
            }),
        }
    },
    methods: {
        addReference() {
            this.form.references.push({
                url_id: 'references[' + ++this.references_counter + '][url]',
                url_title_id: 'references[' + this.references_counter + '][url_title]',
                value: '',
            });
        },
        addDatabaseLink() {
            this.form.database_links.push({
                database_name_id: 'database_links[' + ++this.database_links_counter + '][database_name]',
                spring_name_id: 'database_links[' + ++this.database_links_counter + '][spring_name]',
                code_id: 'database_links[' + ++this.database_links_counter + '][code]',
                url_id: 'database_links[' + this.database_links_counter + '][url]',
            });
        },
        handlePhotoCardPreview(photo) {
            this.dialogPhotoUrl = photo.url;
            this.dialogVisible = true;
        },
        saveDraft: function (data) {
            data._method = 'POST';
            this.$inertia.post('/springs', data)
        },
        submit: function (data) {
            data.status = 'submitted';
            data._method = 'POST';
            this.$inertia.post('/springs', data)
        },
        confirm: function (data) {
            data.status = 'confirmed';
            data._method = 'POST';
            this.$inertia.post('/springs', data)
        },
    },
}
</script>
<!--<script src="./../../../../public/js/map.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=' + services.google.maps.api-key + '&libraries=places&callback=initMap" async defer></script>
-->
