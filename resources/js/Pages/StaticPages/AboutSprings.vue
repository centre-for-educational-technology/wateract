<template>
    <app-layout>

        <template #header>
            <h1>
                {{ $t('springs.about_springs') }}
            </h1>
        </template>

        <about-springs-et v-if="$i18n.locale == 'et'"></about-springs-et>

    </app-layout>
</template>

<script>
import AppLayout from './../../Layouts/AppLayout'
import AboutSpringsEt from "./et/AboutSprings";

export default {
    components: {
        AppLayout,
        AboutSpringsEt,
    },
}
</script>
