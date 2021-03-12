<template>

    <div class="border rounded px-4 py-2 my-2">

        <div v-if="$page.user">
            <nav-button v-if="( can_edit || ( measurement.user_id === $page.user.id ))"
                        class="float-right"
                        :href="'/springs/'+spring.code+'/analyses/'+measurement.id+'/edit'">
                {{ $t('springs.edit') }}</nav-button>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div v-for="field in measurement_fields" :key="field.id">
                <div>
                    <jet-label class="font-bold" :for="field.name" :value="$t('springs.measurement_fields.'+field.name)" />
                    <div>{{field.value}} {{field.unit}}</div>
                </div>
            </div>
        </div>

    </div>

</template>
<script>
import JetLabel from "../../Jetstream/Label";
import NavButton from '../../Components/NavButton';
export default {
    components: {
        JetLabel,
        NavButton,
    },
    props: ['spring', 'measurement', 'can_edit'],
    data() {
        return {
            measurement_fields: [],
        }
    },
    methods: {
        getMeasurementInfo(spring, measurement) {
            axios.get('/springs/'+spring.code+'/analyses/'+measurement.id).then(response => {
                this.measurement_fields = response.data;
            })
        },
    },
    created: function(){
        this.getMeasurementInfo(this.spring, this.measurement);
    }
}
</script>
