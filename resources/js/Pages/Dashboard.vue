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

        <div class="max-w-7xl mx-auto lg:py-10 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <main-map :springs="springs"></main-map>

                <springs-for-review v-if="can('confirm spring')" class="m-4" />

                <user-springs :user_id="$page.user.id" class="m-4" />

                <user-observations :user_id="$page.user.id" class="m-4" />

                <user-measurements :user_id="$page.user.id" class="m-4" />

                <jet-section-border />

                <jet-action-section class="sm:px-6 lg:px-8 py-4" v-if="can('view users')">

                    <template #title>
                        Export Excel Files
                    </template>

                    <template #description>
                        Download springs and observations .xlsx files.
                    </template>

                    <template #content>

                        <a target="_blank" class="underline" href="/admin/exportSprings">Export Springs</a>
                        <br /><br />
                        <a target="_blank" class="underline" href="/admin/exportObservations">Export Observations</a>
                        <br /><br />
                        <a target="_blank" class="underline" href="/admin/exportMeasurements">Export Measurements</a>

                    </template>

                </jet-action-section>

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
    import JetSectionBorder from './../Jetstream/SectionBorder';
    import JetActionSection from './../Jetstream/ActionSection';

    export default {
        components: {
            AppLayout,
            MainMap,
            SpringsForReview,
            UserSprings,
            UserObservations,
            UserMeasurements,
            JetSectionBorder,
            JetActionSection,
        },
        props: ['springs'],
    }
</script>
