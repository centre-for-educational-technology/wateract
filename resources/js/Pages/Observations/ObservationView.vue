<template>

    <div class="border rounded px-4 py-2 my-2">
        <div v-if="observation.odor">
            <jet-label :value="$t('springs.odor')" />
            <div>{{ observation.odor}}</div>
        </div>

        <div v-if="observation.taste">
            <jet-label :value="$t('springs.taste')" />
            <div>{{ observation.taste}}</div>
        </div>

        <div v-if="observation.color">
            <jet-label :value="$t('springs.color_and_turbidity')" />
            <div>{{ observation.color}}</div>
        </div>

        <div v-if="observation.description">
            <jet-label :value="$t('springs.description')" />
            <div>{{ observation.description}}</div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div v-for="field in observation_fields" :key="field.id">
                <div>
                    <jet-label :for="field.name" :value="$t('springs.'+field.name)" />
                    <div>{{field.value}} {{field.unit}}</div>
                </div>
            </div>
        </div>

    </div>


</template>
<script>
import JetLabel from "../../Jetstream/Label";
    export default {
        components: {
            JetLabel,
        },
        props: ['spring', 'observation'],
        data() {
            return {
                observation_fields: [],
            }
        },
        methods: {
            getObservationInfo(spring, observation) {
                axios.get('/springs/'+spring.code+'/observations/'+observation.id).then(response => {
                    this.observation_fields = response.data;
                })
            },
        },
        created: function(){
            this.getObservationInfo(this.spring, this.observation);
        }
    }
</script>
