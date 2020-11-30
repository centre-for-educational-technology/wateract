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
                    <button class="border text-xs font-semibold px-3 py-2 leading-normal">
                        <inertia-link :href="'/springs/'+spring.code+'/observations/create'">
                            Create new observation</inertia-link>
                    </button>
                </div>
            </div>
        </template>

        <div class="py-6">

            <spring-navigation :spring="spring" :active_tab="'observations'"></spring-navigation>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                    <div v-if="spring.observations.length === 0">No observations added.</div>

                    <div class="px-4" v-for="observation in spring.observations" :key="observation.id">

                        <jet-label :value="observation.measurement_time" @click.native="showObservation(observation)"/>

                        <observation-view  v-show="observation.show" :spring="spring"  :observation="observation"></observation-view>

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
import ObservationView from './ObservationView'

export default {
    components: {
        AppLayout,
        SpringNavigation,
        JetLabel,
        ObservationView
    },
    props: ['spring'],
    methods: {
        showObservation(observation) {
            this.$set(observation, 'show', !observation.show)
        },
    },
}
</script>
