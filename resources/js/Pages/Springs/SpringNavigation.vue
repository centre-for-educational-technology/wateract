<template>

    <div class="max-w-7xl mx-auto pb-4 px-4 sm:px-6 lg:px-8" v-if="spring.status != 'draft'">
        <inertia-link class="mr-4" :href="'/springs/'+spring.code" :class="{'font-bold border-b-2': active_tab === 'view' }">{{ $t('springs.view') }}</inertia-link>
        <inertia-link class="mr-4" :href="'/springs/'+spring.code+'/observations'" :class="{'font-bold border-b-2': active_tab === 'observations' }">{{ $t('springs.observations') }}</inertia-link>
        <inertia-link class="mr-4" :href="'/springs/'+spring.code+'/measurements'" :class="{'font-bold border-b-2': active_tab === 'measurements' }">{{ $t('springs.measurements') }}</inertia-link>
        <inertia-link class="mr-4" :href="'/springs/'+spring.code+'/feedback'" :class="{'font-bold border-b-2': active_tab === 'feedback' }">{{ $t('springs.feedback') }}</inertia-link>
    </div>

</template>
<script>
    export default {
        components: {},
        props: ['spring', 'active_tab'],
    }
</script>
