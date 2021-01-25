<template>
    <app-layout>

        <template #header>
            <h1>
                {{ $t('springs.about_wateract') }}
            </h1>
        </template>



    </app-layout>
</template>

<script>
import AppLayout from './../../Layouts/AppLayout'

export default {
    components: {
        AppLayout,
    },
}
</script>
