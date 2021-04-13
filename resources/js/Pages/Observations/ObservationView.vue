<template>

    <div class="border rounded px-4 py-2 my-2">

        <div v-if="$page.user">
            <nav-button v-if="( can_edit || ( observation.user_id === $page.user.id ))"
                        class="float-right"
                        :href="'/springs/'+spring.code+'/observations/'+observation.id+'/edit'">
                {{ $t('springs.edit') }}</nav-button>
        </div>

        <div v-if="observation.photos.length > 0">
            <jet-label :value="$t('springs.photos')" />
            <div class="grid grid-cols-6 gap-1">
                <div @click="handlePhotoPreview(photo)" class="border-1 border-white" v-for="photo in observation.photos">
                    <img :src="'/'+photo.thumbnail" />
                </div>
            </div>
        </div>
        <el-dialog :visible.sync="dialogVisible">
            <img width="100%" :src="dialogPhotoUrl" alt="" />
        </el-dialog>

        <div v-if="observation.odor">
            <jet-label :value="$t('springs.odor')" />
            <div>{{ observation.odor}}</div>
        </div>

        <div v-if="observation.taste">
            <jet-label :value="$t('springs.taste')" />
            <div>{{ $t('springs.taste_options.'+observation.taste) }}</div>
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
                    <div v-if="field.type !== 'dropdown'">{{field.value}} {{field.unit}}</div>
                    <div v-if="field.type === 'dropdown'">{{ $t('springs.'+field.value) }}</div>
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
        props: ['spring', 'observation', 'can_edit'],
        data() {
            return {
                observation_fields: [],
                dialogVisible: false,
                dialogPhotoUrl: '',
            }
        },
        methods: {
            getObservationInfo(spring, observation) {
                axios.get('/springs/'+spring.code+'/observations/'+observation.id).then(response => {
                    this.observation_fields = response.data;
                })
            },
            handlePhotoPreview(photo) {
                this.dialogPhotoUrl = '/' + photo.path;
                this.dialogVisible = true;
            },
        },
        created: function(){
            this.getObservationInfo(this.spring, this.observation);
        }
    }
</script>
