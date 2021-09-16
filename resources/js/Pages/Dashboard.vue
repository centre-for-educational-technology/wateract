<template>
    <app-layout>

        <template #header>
            <h1>
                {{ $t('springs.dashboard') }}
            </h1>
        </template>

        <template #title>
            {{ $t('springs.my_springs') }}
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                    <main-map :springs="springs"></main-map>

                    <springs-for-review v-if="can('confirm spring')" class="m-4" />

                    <user-springs class="m-4" />

                    <user-observations class="m-4" />

                    <user-measurements class="m-4" />

                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from './../Layouts/AppLayout'
    import MainMap from './../Components/Maps/MainMap';
    import SpringsForReview from "./Springs/SpringsForReview";
    import UserSprings from "./Springs/UserSprings";
    import UserObservations from "./Observations/UserObservations";
    import UserMeasurements from "./Measurements/UserMeasurements";

    export default {
        components: {
            AppLayout,
            MainMap,
            SpringsForReview,
            UserSprings,
            UserObservations,
            UserMeasurements,
        },
        props: ['springs'],
    }
</script>
