<template>
    <app-layout>

        <template #header>
            <h1>
                {{ $t('springs.instructions') }}
            </h1>
        </template>

        <instructions-et v-if="$i18n.locale == 'et'"></instructions-et>

        <instructions-en v-if="$i18n.locale == 'en'"></instructions-en>

    </app-layout>
</template>

<script>
import AppLayout from './../../Layouts/AppLayout';
import InstructionsEt from './et/Instructions';
import InstructionsEn from './en/Instructions';

export default {
    components: {
        AppLayout,
        InstructionsEt,
        InstructionsEn,
    },
}
</script>
