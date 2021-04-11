<template>

    <div class="max-w-7xl mx-auto pb-4 px-4 sm:px-6 lg:px-8" v-if="spring.status != 'draft'">
        <inertia-link class="mr-4" :href="'/springs/'+spring.code" :class="{'font-bold border-b-2': active_tab === 'view' }">{{ $t('springs.view') }}</inertia-link>
        <inertia-link class="mr-4" :href="'/springs/'+spring.code+'/observations'"
                      :class="{'font-bold border-b-2': active_tab === 'observations' }">
            {{ $t('springs.observations') }}
            <span class="text-gray-500" v-if="observations_count > 0">({{ observations_count }})</span>
        </inertia-link>
        <inertia-link class="mr-4" :href="'/springs/'+spring.code+'/analyses'"
                      :class="{'font-bold border-b-2': active_tab === 'measurements' }">
            {{ $t('springs.measurements') }}
            <span class="text-gray-500" v-if="measurements_count > 0">({{ measurements_count }})</span>
        </inertia-link>
        <inertia-link class="mr-4" :href="'/springs/'+spring.code+'/feedback'"
                      :class="{'font-bold border-b-2': active_tab === 'feedback' }">
            {{ $t('springs.feedback') }}
            <span class="text-gray-500" v-if="feedback_count > 0">({{ feedback_count }})</span>
        </inertia-link>
    </div>

</template>
<script>
    export default {
        components: {},
        props: ['spring', 'active_tab'],
        data() {
            return {
                observations_count: 0,
                measurements_count: 0,
                feedback_count: 0,
            }
        },
        methods: {
            getSpringInfo() {
                let params = {
                    "spring_id": this.spring.id,
                }
                axios.get('/getSpringInfo', {params}).then(response => {
                    let spring_info = response.data;
                    this.observations_count = spring_info['observations_count'];
                    this.measurements_count = spring_info['measurements_count'];
                    this.feedback_count = spring_info['feedback_count'];
                })
            }
        },
        created: function(){
            this.getSpringInfo();
        }
    }
</script>
