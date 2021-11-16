<template>
    <app-layout>

        <template #header>
            <h1>
                {{ $t('springs.instructions') }}
            </h1>
        </template>

        <instructions-et v-if="$i18n.locale == 'et'"></instructions-et>

        <instructions-en v-if="$i18n.locale == 'en'"></instructions-en>

        <instructions-lv v-if="$i18n.locale == 'lv'"></instructions-lv>

        <instructions-en v-if="$i18n.locale == 'ru'"></instructions-en>

        <instructions-en v-if="$i18n.locale == 'fr'"></instructions-en>

    </app-layout>
</template>

<script>
import AppLayout from './../../Layouts/AppLayout';
import InstructionsEt from './et/Instructions';
import InstructionsEn from './en/Instructions';
import InstructionsLv from './lv/Instructions';

export default {
    components: {
        AppLayout,
        InstructionsEt,
        InstructionsEn,
        InstructionsLv,
    },
}
</script>
