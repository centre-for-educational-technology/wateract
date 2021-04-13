<template>
    <app-layout>
        <template #header>
            <h1>
                <inertia-link class="mr-4" :href="'/springs/'+spring.code">{{ spring.name }}</inertia-link>
            </h1>
        </template>


        <div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
            <jet-form-section>

                <template #title>
                    {{ $t('springs.create_observation')}}
                </template>

                <template #form>

                    <el-dialog :visible.sync="helpDialogVisible">
                        <div class="break-normal">{{ helpText }}</div>
                    </el-dialog>

                    <div class="p-5 bg-blue-50">
                        {{ $t('springs.all_fields_not_required') }}
                    </div>

                    <div>
                        <div>
                            <jet-label class="font-bold inline-block" for="measurement_time" :value="$t('springs.measurement_time')" />
                            <help-button @click.native="showHelpDialog( $t('springs.measurement_time_help_text') )"></help-button>
                        </div>
                        <datetime :auto="true" :title="$t('springs.measurement_time')" value-zone="local"
                                  :phrases="datetime_phrases"  type="datetime" v-model="form.measurement_time"></datetime>
                    </div>

                    <div class="col-span-12 sm:col-span-4">
                        <jet-label class="font-bold inline-block" for="photos" :value="$t('springs.photos')" />
                        <help-button @click.native="showHelpDialog( $t('springs.photos_help_text') )"></help-button>
                        <el-upload
                            action="/"
                            list-type="picture-card"
                            accept="image/*"
                            :auto-upload="false"
                            :on-preview="handlePhotoCardPreview"
                            :on-change="updatePhotos">
                            <i class="el-icon-plus"></i>
                        </el-upload>
                        <el-dialog :visible.sync="dialogVisible">
                            <img width="100%" :src="dialogPhotoUrl" alt="" />
                        </el-dialog>
                    </div>

                    <div class="flex -mx-2 py-2">
                        <div class="w-full px-2">
                            <jet-label class="font-bold inline-block" for="odor" :value="$t('springs.odor')" />
                            <help-button @click.native="showHelpDialog( $t('springs.odor_help_text') )"></help-button>
                            <jet-input id="odor" type="text" class="mt-1 block w-full" v-model="form.odor" />
                        </div>
                    </div>

                    <div class="flex -mx-2 py-2">
                        <div class="w-1/2 px-2">
                            <jet-label class="font-bold inline-block" for="taste" :value="$t('springs.taste')" />
                            <help-button @click.native="showHelpDialog( $t('springs.taste_help_text') )"></help-button>
                            <select id="taste" v-model="form.taste"
                                class="block w-full bg-white border border-gray-400 hover:border-gray-500 px-2 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                                <option value=""></option>
                                <option v-for='data in taste_options' :selected="form.taste === data.id" :value='data.id'>{{ $t(data.name) }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex -mx-2 py-2">
                        <div class="w-full px-2">
                            <jet-label class="font-bold inline-block" for="color" :value="$t('springs.color_and_turbidity')" />
                            <help-button @click.native="showHelpDialog( $t('springs.color_and_turbidity_help_text') )"></help-button>
                            <jet-input id="color" type="text" class="mt-1 block w-full" v-model="form.color" />
                        </div>
                    </div>

                    <div class="col-span-6 sm:col-span-4">
                        <jet-label class="font-bold inline-block" for="description" :value="$t('springs.observation_description')" />
                        <help-button @click.native="showHelpDialog( $t('springs.description_help_text') )"></help-button>
                        <textarea id="description" type="textarea" class="px-2 mt-1 block w-full border" rows="5" v-model="form.description" ></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div v-for="(field, index) in observation_fields" :key="field.id">
                            <div :class="{'pull-right': index % 2 === 0, 'pull-left': index % 2 !== 0 }">
                                <jet-label class="font-bold inline-block" :for="field.name" :value="$t('springs.'+field.name)" />
                                <span v-if="field.unit">({{ field.unit}})</span>
                                <help-button v-if="fieldsWithHelpText.includes(field.name)" @click.native="showHelpDialog( $t('springs.'+field.name+'_help_text') )"></help-button>
                                <jet-input v-if="field.type !== 'dropdown'" :type="field.type" class="mt-1 block w-full" :id="field.name" v-model="field.value" />
                                <select v-if="field.type === 'dropdown'" :id="field.name" v-model="field.value"
                                    class="mt-1 block w-full bg-white border border-gray-400 hover:border-gray-500 px-2 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                                >
                                    <option value=""></option>
                                    <option v-for='data in field_options[field.name]' :value='data.id'>{{ $t('springs.'+data.id) }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </template>
                <template #actions>
                    <jet-secondary-button :class="{ 'opacity-25': processingPhotos }" :disabled="processingPhotos" type="submit" @click.native="saveDraft(form)">
                        {{ $t('springs.save_as_draft') }}</jet-secondary-button>
                    <jet-button :class="{ 'opacity-25': processingPhotos }" :disabled="processingPhotos" class="ml-2" type="submit" @click.native="submit(form)">
                        {{ $t('springs.submit') }}</jet-button>
                </template>
            </jet-form-section>
        </div>
    </app-layout>
</template>
<script>
import AppLayout from './../../Layouts/AppLayout'
import JetFormSection from "../../Jetstream/FormSection";
import JetLabel from "../../Jetstream/Label";
import JetInput from "../../Jetstream/Input";
import JetInputError from "../../Jetstream/InputError";
import JetButton from "../../Jetstream/Button";
import JetSecondaryButton from "../../Jetstream/SecondaryButton";
import HelpButton from '../../Components/HelpButton';
import { Datetime } from 'vue-datetime'
import 'vue-datetime/dist/vue-datetime.css'

export default {
    components: {
        AppLayout,
        JetFormSection,
        JetLabel,
        JetInput,
        JetInputError,
        JetButton,
        JetSecondaryButton,
        HelpButton,
        datetime: Datetime,
    },
    props: ['spring', 'observation_fields', 'taste_options', 'field_options'],
    data() {
        return {
            fieldsWithHelpText: [
                'air_temperature',
                'water_temperature',
                'ph',
                'specific_conductance',
                'electrical_conductivity',
                'dissolved_oxygen_percentage',
                'dissolved_oxygen_ppm',
                'discharge'
            ],
            helpDialogVisible: false,
            helpText: '',
            datetime_phrases: {ok: this.$i18n.t('springs.ok'), cancel: this.$i18n.t('springs.cancel')},
            dialogVisible: false,
            dialogPhotoUrl: '',
            processingPhotos: false,
            form: this.$inertia.form({
                '_method': 'POST',
                measurement_time: new Date().toISOString(),
                photo_ids: [],
                odor: '',
                taste: '',
                color: '',
                description: '',
                spring_id: this.spring.id,
                observation_values: this.observation_fields,
            }, {
                bag: 'addObservation',
                resetOnSuccess: false,
            }),
        }
    },
    methods: {
        showHelpDialog(helptext) {
            this.helpText = helptext;
            this.helpDialogVisible = true;
        },
        handlePhotoCardPreview(photo) {
            this.dialogPhotoUrl = photo.url;
            this.dialogVisible = true;
        },
        updatePhotos(photo) {
            this.processingPhotos = true;
            let file = photo;
            const isIMAGE = (file.raw.type === 'image/jpeg' || file.raw.type === 'image/png');
            if (!isIMAGE) {
                this.$message.error('Only upload jpg/png picture!');
                return false;
            }
            // upload photo
            var data = new FormData();
            data.append('photo', photo.raw || '');
            let photo_id;
            axios.post('/photos', data).then(response => {
                photo_id = response.data.photo_id;
                this.form.photo_ids.push(photo_id);
                this.processingPhotos = false;
            })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .then(function () {
                    // always executed
                    console.log('upload complete');
                });
        },
        saveDraft: function (data) {
            this.$inertia.post('/observations', data)
        },
        submit: function (data) {
            data.status = 'submitted';
            this.$inertia.post('/observations', data)
        },
    }
}
</script>
