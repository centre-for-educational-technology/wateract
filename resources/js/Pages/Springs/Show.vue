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
                <div class="float-right w-1/4" v-if="$page.user">
                    <button v-if="(can('edit spring') && spring.status === 'submitted') || spring.status === 'draft'" class="border text-xs font-semibold px-3 py-2 leading-normal">
                        <inertia-link :href="'/springs/'+spring.code+'/edit'">Edit spring</inertia-link>
                    </button>
                </div>
            </div>
        </template>

        <div class="py-6">

            <spring-navigation :spring="spring"></spring-navigation>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                        <div class="z-depth-1-half map-container w-full" style="height:400px;">
                            <GmapMap ref="map"
                                 :center="{lat:latitude, lng:longitude}"
                                 :zoom="14"
                                 map-type-id="terrain"
                                 style="width: 100%; height: 100%"
                            >
                            <GmapMarker
                                :key="index"
                                v-for="(location, index) in markers"
                                :position="location.position"
                            />
                            </GmapMap>
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
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">Database name</th>
                                        <th scope="col">Code</th>
                                        <th scope="col">Spring name</th>
                                        <th scope="col">URL</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for='database_link in spring.database_links'>
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
                                <div>{{spring.county}}</div>

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

export default {
    components: {
        AppLayout,
        SpringNavigation,
        JetLabel,
        gmapApi,
    },
    props: ['spring'],
    data() {
        return {
            map: null,
            latitude: this.spring.latitude,
            longitude: this.spring.longitude,
            markers: [{
                id: this.spring.id,
                name: this.spring.name,
                description: this.spring.description,
                position: {lat: this.spring.latitude, lng: this.spring.longitude}
            }],
        }
    },
}
</script>
