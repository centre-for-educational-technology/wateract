<template>
    <app-layout>
        <template #header>
            <h1>{{ $t('springs.observations_and_database') }}</h1>
            <div class="sm:float-right lg:mt-0 mt-4" v-if="$page.user">
                <nav-button :href="'springs/create'">{{ $t('springs.create_new_spring') }}</nav-button>
            </div>
        </template>

        <div class="py-6">

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                    <div class="p-4">
                       <h3 class="text-xl">{{ $t('springs.browse_springs') }}</h3>
                        <div class="flex">
                            <jet-input class="w-1/3 lg:w-1/4 sm:w-1/3 mr-3" type="text" v-model="search_name" name="searchbox" :placeholder="$t('springs.search_spring_name')" />
                            <select id="classification" v-model="search_classification"
                                    class="w-1/3 lg:w-1/4 sm:w-1/3 block bg-white border border-gray-300 hover:border-gray-500 mr-3 px-2 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">{{ $t('springs.classification') }}</option>
                                <option v-for='data in classifications' :value='data.id'> {{ $t( data.name ) }}</option>
                            </select>
                            <button class="w-1/3 lg:w-1/4 sm:w-1/3 items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150"
                                v-on:click="updateMarkers">{{ $t('springs.search') }}</button>
                        </div>
                        <small v-on:click="initializeSearch" class="cursor-pointer underline">{{ $t('springs.see_all_springs') }}</small>
                    </div>

                    <leaflet-maps :key="mapRefresh" :springs="mapSprings" ></leaflet-maps>

                    <tabs :featured_springs="featured_springs" :newest_springs="newest_springs" ></tabs>

                </div>

            </div>
        </div>

    </app-layout>
</template>
<script>
import AppLayout from './../../Layouts/AppLayout'
import JetInput from "../../Jetstream/Input";
import JetSecondaryButton from './../../Jetstream/SecondaryButton'
import SpringView from './SpringView'
import JetLabel from "../../Jetstream/Label";
import NavButton from '../../Components/NavButton';
import LeafletMaps from './LeafletMaps';
import Tabs from './Tabs';

export default {
    components: {
        AppLayout,
        JetInput,
        JetSecondaryButton,
        SpringView,
        JetLabel,
        NavButton,
        LeafletMaps,
        Tabs,
    },
    props: ['springs', 'featured_springs', 'newest_springs', 'classifications'],
    data() {
        return {
            mapSprings: this.springs,
            mapRefresh: 0,
            search_name: '',
            search_classification: '',
        }
    },
    mounted() {
            //set bounds of the map
            /*this.$refs.map.$mapPromise.then((map) => {
                map.panTo({lat:58.379, lng:24.554});

            });*/
            /*console.log('mount');
            const layers = {
                'Muinsuskaitsealused allikad' : 'http://allikad.info/kml/Kult_allikad_komb.kmz',
                'Loodusdirektiivi allikaelupaigad' : 'http://allikad.info/kml/LD_allikad_pind1.kmz',
                'Parandkultuuriallikad' : 'http://allikad.info/kml/Par_allikad_punkt1.kmz',
                'Allikate seirejaamad' : 'http://allikad.info/kml/Seire_allikad_punkt1.kmz',
                'Allikalised vaariselupaigad' : 'http://allikad.info/kml/VEP_allikad_pind1.kmz',
                'Looduskaitsealused allikad' : 'http://allikad.info/kml/UOB_allikad_komb.kmz',
                'Urglooduse raamatu allikad' : 'http://allikad.info/kml/Urg_allikad_komb.kmz'
            };
            this.$refs.map.$mapPromise.then((map) => {
                _.forEach(layers, function(layer_file, layer_name) {
                    console.log(layer_file);
                    let options = {
                        map: map,
                        url: layer_file,
                    }
                    let kml = new google.maps.KmlLayer(options)
                });
            })*/
    },
    methods: {
        initializeSearch: function() {
            this.search_name = '';
            this.search_classification = '';
            this.search_country = '';
            this.updateMarkers();
        },
        updateMarkers: function () {
            //get springs based on search params
            let params = {
                "name": this.search_name,
                "classification": this.search_classification,
            }
            axios.get('/getSprings', { params }).then(response => {
                this.mapSprings = response.data;
                this.mapRefresh++;
            })
        }

    }
}
</script>
