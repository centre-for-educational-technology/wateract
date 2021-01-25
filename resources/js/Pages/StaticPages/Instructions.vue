<template>
    <app-layout>

        <template #header>
            <h1>
                {{ $t('springs.instructions') }}
            </h1>
        </template>

        <instructions-et v-if="$i18n.locale == 'et'"></instructions-et>

    </app-layout>
</template>

<script>
import AppLayout from './../../Layouts/AppLayout'
import InstructionsEt from "./et/Instructions";


export default {
    components: {
        AppLayout,
        InstructionsEt,
    },
}
</script>
