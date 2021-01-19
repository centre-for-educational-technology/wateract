<template>
    <app-layout>
        <template #header>
            <div class="flex w-full">
                <h1 class="inline w-4/5" v-if="spring.name">
                    {{ spring.name }}
                </h1>
                <h1 class="inline w-4/5" v-if="!spring.name">
                    {{ $t('springs.unnamed') }}
                </h1>
                <div class="w-1/5 float-right inline" v-if="$page.user">
                    <button class="float-right border text-xs font-semibold px-3 py-2 leading-normal">
                        <inertia-link :href="'/springs/'+spring.code+'/observations/create'">
                            {{  $t('springs.add_new_observation') }}</inertia-link>
                    </button>
                </div>
            </div>
        </template>

        <div class="py-6">

            <spring-navigation :spring="spring" :active_tab="'observations'"></spring-navigation>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                    <div class="px-4 py-5 sm:p-6 ">

                        <div v-if="observations.length === 0">{{ $t('springs.no_observations_added') }}</div>

                        <div class="py-1" v-for="observation in observations" :key="observation.id">

                            <div class="cursor-pointer p-1 inline hover:bg-gray-100 hover:font-bold"
                                 @click="showObservation(observation)">
                                {{ observation.measurement_time }}
                                <span class="pl-2" v-if="observation.user">{{ observation.user.name }}</span>
                            </div>

                            <observation-view  v-show="observation.show" :spring="spring"  :observation="observation"></observation-view>

                        </div>
                    </div>

                </div>
            </div>

        </div>

    </app-layout>
</template>
<script>
import AppLayout from './../../Layouts/AppLayout'
import SpringNavigation from '../Springs/SpringNavigation'
import ObservationView from './ObservationView'

export default {
    components: {
        AppLayout,
        SpringNavigation,
        ObservationView
    },
    props: ['spring', 'observations'],
    methods: {
        showObservation(observation) {
            this.$set(observation, 'show', !observation.show)
        },
    },
}
</script>
