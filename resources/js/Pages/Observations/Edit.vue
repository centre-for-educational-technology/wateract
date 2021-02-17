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
                    {{ $t('springs.edit_observation') }}
                </template>

                <template #form>

                    <el-dialog :visible.sync="helpDialogVisible">
                        <div class="break-normal">{{ helpText }}</div>
                    </el-dialog>

                    <div>
                        <div>
                            <jet-label class="font-bold inline-block" for="measurement_time" :value="$t('springs.measurement_time')" />
                            <help-button @click.native="showHelpDialog( $t('springs.measurement_time_help_text') )"></help-button>
                        </div>
                        <datetime :auto="true" :title="$t('springs.measurement_time')" value-zone="local"
                                  :phrases="datetime_phrases" type="datetime" v-model="form.measurement_time"></datetime>
                    </div>

                    <div class="py-2">
                        <jet-label class="font-bold inline-block" for="photos" :value="$t('springs.photos')" />
                        <help-button @click.native="showHelpDialog( $t('springs.photos_help_text') )"></help-button>
                        <el-upload
                            :file-list="photos"
                            action="/"
                            list-type="picture-card"
                            accept="image/*"
                            :auto-upload="false"
                            :on-preview="handlePhotoPreview"
                            :on-remove="handlePhotoRemove"
                            :on-change="updatePhotos">
                            <i class="el-icon-plus"></i>
                        </el-upload>
                        <el-dialog :visible.sync="dialogVisible">
                            <img width="100%" :src="dialogPhotoUrl" alt="" />
                        </el-dialog>
                    </div>

                    <div class="flex -mx-2 py-2">
                        <div class="w-full px-2">
                            <jet-label class="font-bold" for="odor" :value="$t('springs.odor')" />
                            <jet-input id="odor" type="text" class="mt-1 block w-full" v-model="form.odor" />
                        </div>
                    </div>

                    <div class="flex -mx-2 py-2">
                        <div class="lg:w-1/2 px-2">
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
                        <jet-label class="font-bold inline-block" for="description" :value="$t('springs.description')" />
                        <help-button @click.native="showHelpDialog( $t('springs.description_help_text') )"></help-button>
                        <textarea id="description" type="textarea" class="px-2 mt-1 block w-full border" rows="5" v-model="form.description" ></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div v-for="(field, index) in observation_fields" :key="field.id">
                            <div :class="{'pull-right': index % 2 === 0, 'pull-left': index % 2 !== 0 }">
                                <jet-label class="font-bold inline-block" :for="field.name" :value="$t('springs.'+field.name)" />
                                <span v-if="field.unit">({{ field.unit}})</span>
                                <help-button v-if="fieldsWithHelpText.includes(field.name)" @click.native="showHelpDialog( $t('springs.'+field.name+'_help_text') )"></help-button>
                                <jet-input :type="field.type" class="mt-1 block w-full" :id="field.name" v-model="field.value" />
                            </div>
                        </div>
                    </div>

                </template>
                <template #actions>
                    <jet-secondary-button v-if="form.status === 'draft'" type="submit"
                                          @click.native="save(form)">
                        {{ $t('springs.save_as_draft') }}</jet-secondary-button>
                    <jet-button v-if="form.status === 'draft'" class="ml-2" type="submit"
                                @click.native="submit(form)">
                        {{  $t('springs.submit') }}</jet-button>
                    <jet-button v-if="can('edit spring') && form.status === 'submitted'"
                                type="submit" @click.native="save(form)">
                        {{ $t('springs.save') }}</jet-button>
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
    props: ['spring', 'observation', 'observation_fields', 'taste_options'],
    data() {
        let photos = [];
        _.forEach(this.observation.photos, function(photo) {
            photos.push({
                id: photo.id,
                name: '',
                url: '/' + photo.path,
            });
        });
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
            photos: photos,
            dialogVisible: false,
            dialogPhotoUrl: '',
            form: this.$inertia.form({
                '_method': 'PUT',
                id: this.observation.id,
                measurement_time: new Date(this.observation.measurement_time).toISOString(),
                odor: this.observation.odor,
                taste: this.observation.taste,
                color: this.observation.color,
                spring_id: this.spring.id,
                observation_values: this.observation_fields,
                description: this.observation.description,
                photos_to_add: [],
                photos_to_delete: [],
                status: this.observation.status,
            }, {
                bag: 'editObservation',
                resetOnSuccess: false,
            }),
        }
    },
    methods: {
        showHelpDialog(helptext) {
            this.helpText = helptext;
            this.helpDialogVisible = true;
        },
        save: function (data) {
            this.$inertia.post('/observations/' + data.id, data)
        },
        submit: function (data) {
            data.status = 'submitted';
            this.$inertia.post('/observations/' + data.id, data)
        },
        updatePhotos(photo) {
            var data = new FormData();
            data.append('photo', photo.raw || '');
            let photo_id;
            axios.post('/photos', data).then(response => {
                photo_id = response.data.photo_id;
                this.form.photos_to_add.push(photo_id);
            })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .then(function () {
                    // always executed
                    console.log('midagi');
                });
            ;
        },
        handlePhotoRemove(photo) {
            this.form.photos_to_delete.push(photo.id);
        },
        handlePhotoPreview(photo) {
            this.dialogPhotoUrl = photo.url;
            this.dialogVisible = true;
        },
    }
}
</script>
