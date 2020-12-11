<template>
    <app-layout>
        <template #header>
            <div class="flex w-full">
                <h2 class="w-3/4 font-semibold text-xl text-gray-800 leading-tight">
                    Springs observations and database
                </h2>
                <div class="w-1/4" v-if="$page.user">
                    <a href="springs/create" class="float-right border text-xs font-semibold px-4 py-1 leading-normal">
                        Create new spring</a>
                </div>
            </div>
        </template>

        <div class="py-6">

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="z-depth-1-half map-container" style="height:500px;">
                        <GmapMap ref="map"
                            :center="{lat:58.279, lng:26.054}"
                            :zoom="7"
                            map-type-id="terrain"
                            style="width: 100%; height: 100%"
                        >
                            <GmapCluster>
                            <GmapMarker
                                :key="index"
                                v-for="(location, index) in markers"
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
                    </div>


                    <!--<div>
                        <div v-if="featured_springs.length > 0" v-on:click="featured=true;newest = false;">
                            <jet-label class="semi-bold text-base ml-3">Featured springs</jet-label>
                        </div>
                        <div v-if="newest_springs.length > 0" v-on:click="featured=false;newest = true;">
                            <jet-label class="semi-bold text-base ml-3">Newest springs</jet-label>
                        </div>
                    </div>-->

                    <!--<ul class="list-reset flex border-b">
                        <li class="-mb-px mr-1 ml-1" v-on:click="featured=true;newest = false;">
                            <jet-label class="cursor-pointer bg-white inline-block border-l border-t border-r rounded-t py-2 px-4 hover:border text-blue-800 font-semibold">Featured springs</jet-label>
                        </li>
                        <li class="mr-1" v-on:click="featured=false;newest = true;">
                            <jet-label class="cursor-pointer bg-white inline-block py-2 px-4 text-blue-400 hover:text-blue-600 hover:border font-semibold" >Newest springs</jet-label>
                        </li>
                    </ul>-->

                        <!--<div class="container my-12 mx-auto px-4 md:px-12">
                            <div class="flex flex-wrap -mx-1 lg:-mx-4">

                                <div class="my-1 px-1 w-full md:w-1/2 lg:my-4 lg:px-4 lg:w-1/3">

                                </div>
                            </div>
                        </div>-->
                    <div v-show="featured" v-if="featured_springs.length > 0">
                        <ul class="list-reset flex border-b">
                            <li class="-mb-px mr-1 ml-1">
                                <jet-label class="cursor-pointer bg-white inline-block border-l border-t border-r rounded-t py-2 px-4 font-semibold">Featured springs</jet-label>
                            </li>
                            <li class="mr-1" v-on:click="featured=false;newest = true;" v-if="newest_springs.length>0">
                                <jet-label class="cursor-pointer bg-white inline-block py-2 px-4 hover:text-gray-800" >Newest springs</jet-label>
                            </li>
                        </ul>
                        <div class="grid grid-cols-4 gap-4 px-4 py-2 my-2" v-show="featured">
                            <div class="border mx-1" v-for="featured_spring in featured_springs">
                                <spring-view :spring="featured_spring"></spring-view>
                            </div>
                        </div>
                    </div>

                    <div v-show="newest" v-if="newest_springs.length > 0">
                        <ul class="list-reset flex border-b">
                            <li class="mr-1 ml-1" v-on:click="featured=true;newest = false;" v-if="featured_springs.length>0">
                                <jet-label class="cursor-pointer bg-white inline-block py-2 px-4 hover:text-gray-800">Featured springs</jet-label>
                            </li>
                            <li class="-mb-px mr-1">
                                <jet-label class="cursor-pointer bg-white inline-block py-2 px-4 rounded-t border-l border-t border-r font-semibold" >Newest springs</jet-label>
                            </li>
                        </ul>
                        <div>
                            <div class="grid grid-cols-4 gap-4 px-4 py-2 my-2" v-show="newest">
                                <div class="border mx-1" v-for="new_spring in newest_springs">
                                    <spring-view :spring="new_spring"></spring-view>
                                </div>
                            </div>
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
import GmapCluster from 'gmap-vue/dist/components/cluster'
import SpringView from './SpringView'
import JetLabel from "../../Jetstream/Label";

export default {
    components: {
        AppLayout,
        gmapApi,
        GmapCluster,
        SpringView,
        JetLabel,
    },
    props: ['springs', 'featured_springs', 'newest_springs'],
    data() {
        const mapIcons = {
            'confirmed': 'https://maps.google.com/mapfiles/ms/micons/blue-dot.png',
            'submitted': 'https://maps.google.com/mapfiles/ms/micons/orange-dot.png',
        };
        let markers = [];
        _.forEach(this.springs, function(spring) {
            markers.push({
                id: spring.code,
                name: spring.name,
                description: spring.description,
                status: spring.status,
                date_build: "",
                position: {lat: spring.latitude, lng: spring.longitude},
                icon: mapIcons[spring.status],
            });
        });

        return {
            featured: this.featured_springs.length>0 ? true: false,
            newest: this.featured_springs.length>0 ? false: true,
            map: null,
            markers: markers,
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
        }
    },
    mounted() {
            //set bounds of the map
            this.$refs.map.$mapPromise.then((map) => {
                map.panTo({lat:58.379, lng:24.554});

            });


        /*this.map = new window.google.maps.Map(this.refs["map"], {
            center: {lat: 58, lng: 60},
            zoom: 4,
        })*/
        /*this.mapRef.$mapPromise.then((map) => {
            map.panTo({lat: 1.38, lng: 103.80})
        })*/
    },
    methods: {
        toggleInfoWindow: function (marker, idx) {
            this.infoWindowPos = ({
                    lat : marker.position.lat,
                    lng : marker.position.lng,
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

    }
}
</script>
