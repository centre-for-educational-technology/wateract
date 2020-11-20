<template>

    <div class="px-6 pb-4">
        <inertia-link class="mr-4" :href="'/springs/'+spring.code">View</inertia-link>
        <inertia-link class="mr-4" :href="'/springs/'+spring.code+'/observations'">Observations</inertia-link>
        <inertia-link class="mr-4" :href="'/springs/'+spring.code+'/measurements'">Measurements</inertia-link>
    </div>

</template>
<script>
    export default {
        components: {},
        props: ['spring'],
    }
</script>
