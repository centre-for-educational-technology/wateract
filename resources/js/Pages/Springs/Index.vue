<template>
    <app-layout>
        <template #header>
            <div class="flex w-full">
                <h2 class="w-3/4 font-semibold text-xl text-gray-800 leading-tight">
                    Springs observations and database
                </h2>
                <div class="w-1/4" v-if="$page.user">
                    <a href="springs/create" class="border text-xs font-semibold px-4 py-1 leading-normal">
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
                            <GmapMarker
                                :key="index"
                                v-for="(location, index) in markers"
                                :position="location.position"
                                :clickable="true"
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
                        </GmapMap>
                    </div>

                </div>
            </div>
        </div>

    </app-layout>
</template>
<script src="https://maps.googleapis.com/maps/api/js?key=env.google_maps_api_key" async defer></script>
<script>
import AppLayout from './../../Layouts/AppLayout'
import { gmapApi } from 'gmap-vue';

export default {
    components: {
        AppLayout,
        gmapApi,
    },
    props: ['springs'],
    data() {
        var markers = [];
        _.forEach(this.springs, function(spring) {
            markers.push({
                id: spring.id,
                name: spring.title,
                description: spring.description,
                date_build: "",
                position: {lat: spring.latitude, lng: spring.longitude}
            });
        });

        return {
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
                map.panTo({lat:58.279, lng:26.054})

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
            return('<div class="info_window container"> <a href="springs/'+marker.id+'/">'+markerName+'</a></div>');
        },

    }
}
</script>
