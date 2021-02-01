<template>
    <app-layout>

        <template #header>
            <h1>
                {{ $t('springs.about_wateract') }}
            </h1>
        </template>

        <about-wateract-et v-if="$i18n.locale == 'et'"></about-wateract-et>

        <about-wateract-en v-if="$i18n.locale == 'en'"></about-wateract-en>

    </app-layout>
</template>

<script>
import AppLayout from './../../Layouts/AppLayout'
import AboutWateractEt from "./et/AboutWateract";
import AboutWateractEn from "./en/AboutWateract";

export default {
    components: {
        AppLayout,
        AboutWateractEt,
        AboutWateractEn
    },
}
</script>
