<template>

    <div>

        <div v-if="spring.photos.length > 0">

            <img :src="spring.photos[0].thumbnail" />

        </div>

        <div class="text-sm px-3 py-2">
            <strong><a class="text-base" :href="'/springs/'+spring.code">{{ spring.name }}</a></strong>
            <div>
                {{ spring.country }}
                {{ spring.county }}
                {{ spring.settlement }}

            </div>
            <div v-if="spring.classification">
                <jet-label class="font-bold">Classification</jet-label>
                <div>{{spring.classification}}</div>
            </div>
            <div v-if="spring.status">
                <jet-label class="font-bold">Status</jet-label>
                <div>{{spring.status}}</div>
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
    props: ['spring'],
    data() {
        return {
            observation_fields: [],
        }
    },
    /*methods: {
        getObservationInfo(spring, observation) {
            axios.get('/springs/'+spring.code+'/observations/'+observation.id).then(response => {
                this.observation_fields = response.data;
            })
        },
    },
    created: function(){
        this.getObservationInfo(this.spring, this.observation);
    }*/
}
</script>
