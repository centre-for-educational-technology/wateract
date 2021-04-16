<template>
    <app-layout>

        <template #header>
            <h1>
                {{ $t('springs.about_springs') }}
            </h1>
        </template>

        <about-springs-et v-if="$i18n.locale == 'et'"></about-springs-et>

        <about-springs-en v-if="$i18n.locale == 'en'"></about-springs-en>

        <about-springs-lv v-if="$i18n.locale == 'lv'"></about-springs-lv>

    </app-layout>
</template>

<script>
import AppLayout from './../../Layouts/AppLayout';
import AboutSpringsEt from './et/AboutSprings';
import AboutSpringsEn from './en/AboutSprings';
import AboutSpringsLv from './lv/AboutSprings';

export default {
    components: {
        AppLayout,
        AboutSpringsEt,
        AboutSpringsEn,
        AboutSpringsLv,
    },
}
</script>
