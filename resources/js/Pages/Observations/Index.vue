<template>
    <app-layout>
        <template #header>
            <h1>
                {{ spring.name ||  $t('springs.unnamed')}}
            </h1>
        </template>
        <template #header-buttons>
            <nav-button v-if="$page.user" :href="'/springs/'+spring.code+'/observations/create'">
                    {{ $t('springs.add_new_observation') }}</nav-button>
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
                                {{ $moment(observation.measurement_time).format("DD.MM.YYYY H:mm") }}
                                <span class="pl-2" v-if="observation.user">{{ observation.user.name }}</span>
                            </div>

                            <observation-view  v-show="observation.show" :spring="spring"  :observation="observation" :can_edit="can_edit"></observation-view>

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
import ObservationView from './ObservationView'

export default {
    components: {
        AppLayout,
        NavButton,
        SpringNavigation,
        ObservationView
    },
    props: ['spring', 'observations', 'can_edit'],
    methods: {
        showObservation(observation) {
            this.$set(observation, 'show', !observation.show)
        },
    },
}
</script>
