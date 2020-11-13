<template>
    <app-layout>
        <template #header>
            <div class="flex w-full">
                <h2 class="w-3/4 font-semibold text-xl text-gray-800 leading-tight" v-if="spring.title">
                    {{ spring.title }}
                </h2>
                <h2 class="w-3/4 font-semibold text-xl text-gray-800 leading-tight" v-if="!spring.title">
                    Unnamed
                </h2>
                <div class="float-right w-1/4" v-if="$page.user">
                    <button class=" border text-xs font-semibold px-3 py-2 leading-normal">
                        <a href="edit">Edit spring</a>
                    </button>
                </div>
            </div>
        </template>

        <div class="py-6">

            view observations measurements

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                        <div class="z-depth-1-half map-container w-full" style="height:400px;">
                            <GmapMap ref="map"
                                 :center="{lat:latitude, lng:longitude}"
                                 :zoom="9"
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

                            <div class="">
                                <h4>springs.description</h4>
                                <div>{{ spring.description }}</div>
                            </div>

                            <div class="" v-if="spring.folklore">
                                <h4>springs.folklore</h4>
                                <div>{{ spring.folklore }}</div>
                            </div>

                            <div class="" v-if="spring.geology">
                                <h4>springs.geology</h4>
                                <div>{{ spring.geology }}</div>
                            </div>

                            <div v-if="spring.references">
                                <h4>springs.references</h4>
                                <div>
                                    <a target="_blank" v-for='data in spring.references'>{{ data.url}}</a>
                                </div>
                            </div>

                            <div v-if="spring.database_links">
                                <h4>springs.database_links</h4>
                                <div>
                                    <a target="_blank" v-for='data in spring.database_links'>{{ data.url}}</a>
                                </div>
                            </div>

                        </div>

                        <div class="w-1/4 px-2">

                            <div class="row">
                                <strong>Location</strong>
                                <div>{{spring.county}}</div>

                                <div v-if="spring.settlement">{{spring.settlement}}</div>
                            </div>

                            <div class="row">
                                <div>{{spring.latitude}} {{spring.longitude}}</div>
                            </div>

                            <div class="row" v-if="spring.kkr_code">
                                <div class="group">
                                    <strong>KKR Code</strong>
                                    <div>{{spring.kkr_code}}</div>
                                </div>
                            </div>

                            <div class="row" v-if="spring.classification">
                                <strong>spring_classification</strong>
                                <div>{{spring.classification}}</div>
                            </div>

                            <div class="row" v-if="spring.groundwater_body">
                                <strong>groundwater_body</strong>
                                <div>{{spring.groundwater_body}}</div>
                            </div>

                            <div class="row" v-if="spring.ownership">
                                <strong>ownership</strong>
                                <div>{{spring.ownership}}</div>
                            </div>

                            <div class="row" v-if="spring.status">
                                <strong>status</strong>
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
import AppLayout from './../../Layouts/AppLayout'
import { gmapApi } from 'gmap-vue';

export default {
    components: {
        AppLayout,
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
                name: this.spring.title,
                description: this.spring.description,
                position: {lat: this.spring.latitude, lng: this.spring.longitude}
            }],
        }
    },
}
</script>
