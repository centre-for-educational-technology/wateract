<template>
    <app-layout>

        <template #header>
            <h1>
                {{ $t('springs.about_wateract') }}
            </h1>
        </template>

        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="p-10 bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <about-wateract-et v-if="$i18n.locale == 'et'"></about-wateract-et>

                <about-wateract-en v-if="$i18n.locale == 'en'"></about-wateract-en>

                <about-wateract-lv v-if="$i18n.locale == 'lv'"></about-wateract-lv>

                <div class="mt-5 w-1/2">
                    <img src="/images/interreg-est-lat-eu.jpg" />
                </div>

            </div>
        </div>

    </app-layout>
</template>

<script>
import AppLayout from './../../Layouts/AppLayout'
import AboutWateractEt from "./et/AboutWateract";
import AboutWateractEn from "./en/AboutWateract";
import AboutWateractLv from "./lv/AboutWateract";

export default {
    components: {
        AppLayout,
        AboutWateractEt,
        AboutWateractEn,
        AboutWateractLv
    },
}
</script>
