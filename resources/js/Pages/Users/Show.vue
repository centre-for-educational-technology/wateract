<template>
    <app-layout>

        <template #header>
            <h1>
                {{ user.name}}
            </h1>
        </template>

        <div class="max-w-7xl mx-auto lg:py-10 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <main-map :springs="springs"></main-map>

                <user-springs :user_id="user.id" class="m-4" />

                <user-observations :user_id="user.id" class="m-4" />

                <user-measurements :user_id="user.id" class="m-4" />

            </div>
        </div>

    </app-layout>
</template>

<script>
import AppLayout from './../../Layouts/AppLayout'
import MainMap from './../../Components/Maps/MainMap';
import UserSprings from "./../Springs/UserSprings";
import UserObservations from "./../Observations/UserObservations";
import UserMeasurements from "./../Measurements/UserMeasurements";

export default {
    components: {
        AppLayout,
        MainMap,
        UserSprings,
        UserObservations,
        UserMeasurements,
    },
    props: ['user','springs'],
}
</script>
