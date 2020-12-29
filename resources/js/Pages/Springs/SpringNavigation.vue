<template>

    <div class="px-6 pb-4" v-if="spring.status != 'draft'">
        <inertia-link class="mr-4" :href="'/springs/'+spring.code" :class="{'font-bold border-b-2': active_tab === 'view' }">View</inertia-link>
        <inertia-link class="mr-4" :href="'/springs/'+spring.code+'/observations'" :class="{'font-bold border-b-2': active_tab === 'observations' }">Observations</inertia-link>
        <inertia-link class="mr-4" :href="'/springs/'+spring.code+'/measurements'" :class="{'font-bold border-b-2': active_tab === 'measurements' }">Measurements</inertia-link>
        <inertia-link class="mr-4" :href="'/springs/'+spring.code+'/feedback'" :class="{'font-bold border-b-2': active_tab === 'feedback' }">Feedback</inertia-link>
    </div>

</template>
<script>
    export default {
        components: {},
        props: ['spring', 'active_tab'],
    }
</script>
