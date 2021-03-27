<style>
p.leading-5 {
    display: none;
}
</style>
<template>

    <div>

        <ul class="list-reset flex border-b">
            <li class="mr-1" v-bind:class="{ '-mb-px ml-1': featured }" v-on:click="showFeatured">
                <jet-label class="cursor-pointer bg-white inline-block py-2 px-4 hover:text-gray-800"
                           v-bind:class="{ 'border-l border-t border-r rounded-t font-semibold': featured }">
                    {{  $t('springs.featured_springs') }}</jet-label>
            </li>
            <li class="mr-1" v-bind:class="{ '-mb-px ml-1': newest }" v-on:click="showNewest" v-if="newest_springs.length>0">
                <jet-label class="cursor-pointer bg-white inline-block py-2 px-4 hover:text-gray-800"
                           v-bind:class="{ 'border-l border-t border-r rounded-t font-semibold' : newest }">
                    {{ $t('springs.newest_springs') }}</jet-label>
            </li>
            <li class="mr-1" v-bind:class="{ '-mb-px ml-1': observations }" v-on:click="showObservations">
                <jet-label class="cursor-pointer bg-white inline-block py-2 px-4 hover:text-gray-800"
                           v-bind:class="{ 'border-l border-t border-r rounded-t font-semibold': observations }">
                    {{ $t('springs.newest_observations') }}</jet-label>
            </li>
            <li class="mr-1" v-bind:class="{ '-mb-px ml-1': measurements }" v-on:click="showMeasurements">
                <jet-label class="cursor-pointer bg-white inline-block py-2 px-4 hover:text-gray-800"
                           v-bind:class="{ 'border-l border-t border-r rounded-t font-semibold': measurements }">
                    {{ $t('springs.newest_measurements') }}</jet-label>
            </li>
        </ul>

        <div class="grid lg:grid-cols-4 md:grid-cols-3 grid-cols-2 gap-4 px-4 py-2 my-2" v-show="featured">
            <div class="border mx-1" v-for="featured_spring in featured_springs">
                <spring-view :spring="featured_spring"></spring-view>
            </div>
        </div>

        <div class="grid lg:grid-cols-4 md:grid-cols-3 grid-cols-2 gap-4 px-4 py-2 my-2" v-show="newest">
            <div class="border mx-1" v-for="new_spring in newest_springs">
                <spring-view :spring="new_spring"></spring-view>
            </div>
        </div>

        <div class="px-4 py-2 my-2" v-show="observations">
            <div class="py-1" v-for="new_observation in newest_observations.data" :key="new_observation.id">

                <div class="">
                    <span class="font-semibold hover:underline">
                        <a :href="'/springs/'+ new_observation.spring.code">{{new_observation.spring.name || $t('springs.unnamed')}}</a>
                    </span> -
                    <div class="cursor-pointer p-1 inline hover:bg-gray-100 hover:font-bold"
                         @click="showObservation(new_observation)">
                        {{ $moment(new_observation.measurement_time).format("DD.MM.YYYY H:mm") }}
                        <span class="pl-1" v-if="new_observation.user">{{ new_observation.user.name }}</span>
                    </div>
                </div>

                <observation-view v-show="new_observation.show" :spring="new_observation.spring" :observation="new_observation" :can_edit="false"></observation-view>
            </div>
            <tailable-pagination :size="'small'" :data="newest_observations" :showNumbers="true" @page-changed="getNewestObservations"></tailable-pagination>
        </div>

        <div class="px-4 py-2 my-2" v-show="measurements">
            <div class="py-1" v-for="new_measurement in newest_measurements.data" :key="new_measurement.id">

                <div class="">
                    <span class="font-semibold hover:underline">
                        <a :href="'/springs/'+ new_measurement.spring.code">{{new_measurement.spring.name || $t('springs.unnamed')}}</a>
                    </span> -
                    <div class="cursor-pointer p-1 inline hover:bg-gray-100 hover:font-bold"
                         @click="showMeasurement(new_measurement)">
                        {{ $moment(new_measurement.analysis_time).format("DD.MM.YYYY H:mm") }}
                        <span class="pl-1" v-if="new_measurement.user">{{ new_measurement.user.name }}</span>
                    </div>
                </div>

                <measurement-view v-show="new_measurement.show" :spring="new_measurement.spring" :measurement="new_measurement" :can_edit="false"></measurement-view>
            </div>
            <tailable-pagination v-show="newest_measurements.data.length > 0" :size="'small'" :data="newest_measurements" :showNumbers="true" @page-changed="getNewestMeasurements"></tailable-pagination>
        </div>

    </div>


</template>
<script>
import JetLabel from "../../Jetstream/Label";
import SpringView from "./SpringView";
import ObservationView from "../Observations/ObservationView";
import MeasurementView from "../Measurements/MeasurementView";
export default {
    components: {
        JetLabel,
        SpringView,
        ObservationView,
        MeasurementView,
    },
    props: ['spring', 'featured_springs', 'newest_springs'],
    data() {
        return {
            featured: this.featured_springs.length>0 ? true: false,
            newest: this.featured_springs.length>0 ? false: true,
            observations: false,
            measurements: false,
            newest_observations: {"data": []},
            newest_measurements: {"data": []},
        }
    },
    methods: {
        showFeatured() {
            this.featured = true;
            this.newest = false;
            this.observations = false;
            this.measurements = false;
        },
        showNewest() {
            this.featured = false;
            this.newest = true;
            this.observations = false;
            this.measurements = false;
        },
        showObservations() {
            this.featured = false;
            this.newest = false;
            this.observations = true;
            this.measurements = false;
        },
        showMeasurements() {
            this.featured = false;
            this.newest = false;
            this.observations = false;
            this.measurements = true;
        },
        getNewestObservations(page = 1) {
            let params = {
                "order_by": "created_at",
                "page": page,
            }
            axios.get('/getObservations', { params }).then(response => {
                this.newest_observations = response.data;
            })
        },
        showObservation(observation) {
            this.$set(observation, 'show', !observation.show)
        },
        getNewestMeasurements(page = 1) {
            let params = {
                "order_by": "created_at",
                "page": page,
            }
            axios.get('/getMeasurements', { params }).then(response => {
                this.newest_measurements = response.data;
            })
        },
        showMeasurement(measurement) {
            this.$set(measurement, 'show', !measurement.show)
        },
    },
    created: function(){
        this.getNewestObservations();
        this.getNewestMeasurements();
    }
}
</script>
