<template>
    <app-layout>
        <template #header>
            <div class="flex w-full">
                <h1 class="w-4/5" v-if="spring.name">
                    {{ spring.name }}
                </h1>
                <h1 class="w-4/5" v-if="!spring.name">
                    {{ $t('springs.unnamed') }}
                </h1>
                <div class="w-1/5" v-if="$page.user">
                    <nav-button v-if="can('add measurement')" :href="'/springs/'+spring.code+'/analyses/create'">
                        {{ $t('springs.add_new_measurement') }}</nav-button>
                </div>
            </div>
        </template>

        <div class="py-6">

            <spring-navigation :spring="spring" :active_tab="'measurements'"></spring-navigation>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                    <div class="px-4 py-5 sm:p-6 ">

                        <div v-if="measurements.length === 0">{{ $t('springs.no_measurements_added') }}</div>

                        <div class="py-1" v-for="measurement in measurements" :key="measurement.id">

                            <div class="cursor-pointer p-1 inline hover:bg-gray-100 hover:font-bold"
                                 @click="showMeasurement(measurement)">
                                {{ measurement.analysis_time }}
                                <span class="pl-2" v-if="measurement.user">{{ measurement.user.name }}</span>
                            </div>
                            <measurement-view :spring="spring" :measurement="measurement" v-show="measurement.show"></measurement-view>

                        </div>

                    </div>

                </div>
            </div>
        </div>

    </app-layout>
</template>
<script>
import AppLayout from './../../Layouts/AppLayout'
import NavButton from '../../Components/NavButton'
import SpringNavigation from '../Springs/SpringNavigation'
import MeasurementView from './MeasurementView'

export default {
    components: {
        AppLayout,
        NavButton,
        SpringNavigation,
        MeasurementView,
    },
    props: ['spring', 'measurements'],
    methods: {
        showMeasurement(measurement) {
            this.$set(measurement, 'show', !measurement.show)
        },
    },
}
</script>
