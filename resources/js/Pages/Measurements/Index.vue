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
                    <button v-if="can('add measurement')" class="border text-xs font-semibold px-3 py-2 leading-normal">
                        <inertia-link :href="'/springs/'+spring.code+'/measurements/create'">
                            Create new measurement</inertia-link>
                    </button>
                </div>
            </div>
        </template>

        <div class="py-6">

            <spring-navigation :spring="spring"></spring-navigation>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                    <div v-if="spring.measurements.length === 0">No measurements added.</div>

                    <div class="px-4" v-for="measurement in spring.measurements" :key="measurement.id">

                        <jet-label :value="measurement.analysis_time" @click.native="showMeasurement(measurement)"/>

                        <measurement-view :spring="spring" :measurement="measurement" v-show="measurement.show"></measurement-view>

                    </div>

                    </div>

                </div>
            </div>

    </app-layout>
</template>
<script>
import AppLayout from './../../Layouts/AppLayout'
import SpringNavigation from '../Springs/SpringNavigation'
import JetLabel from "../../Jetstream/Label";
import MeasurementView from './MeasurementView'

export default {
    components: {
        AppLayout,
        SpringNavigation,
        JetLabel,
        MeasurementView,
    },
    props: ['spring'],
    data() {
        return {
            measurementshow: [true, true, true],
        }
    },
    methods: {
        showMeasurement(measurement) {

            //console.log(this.measurementshow[measurement.id] );
                //this.measurementshow[measurement.id] = !this.measurementshow[measurement.id]
            this.$set(measurement, 'show', !measurement.show)
            //console.log(this.measurementshow[measurement.id] );
            //this.$forceUpdate();
            /*axios.get('/springs/'+spring.code+'/measurements/'+measurement.id).then(response => {
                console.log('resp');
                console.log(response);
            })*/
        },
    },
}
</script>
