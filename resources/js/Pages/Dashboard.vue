<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <template #title>
            My Springs
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                    <div class="z-depth-1-half map-container w-full" style="height:400px;">
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

                    <table class="table-auto w-full">
                        <thead>
                        <tr>
                            <th>Spring name</th>
                            <th>Location</th>
                            <th>Classification</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tr v-for="spring in springs">
                            <td class="text-center">
                                <a :href="'springs/'+spring.id">{{ spring.title }}</a>
                            </td>
                            <td class="text-center">{{ spring.country }}</td>
                            <td class="text-center">{{ spring.classification}}</td>
                            <td class="text-center">
                                {{ spring.status }}
                            </td>
                        </tr>
                    </table>

                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from './../Layouts/AppLayout'
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
