<template>
    <app-layout v-if="$page.user">
        <template #header>
            <h1>
                <inertia-link class="mr-4" :href="'/springs/'+spring.code">{{ spring.name }}</inertia-link>
            </h1>
        </template>


        <div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
            <jet-form-section>

                <template #title>
                    {{ $t('springs.add_new_measurement')}}
                </template>

                <template #form>

                    <div class="flex -mx-2 py-2">
                        <div class="w-1/2 px-2">
                            <jet-label class="font-bold" for="analysis_time" :value="$t('springs.analysis_time')" />
                            <input type="datetime-local" id="analysis_time" v-model="form.analysis_time" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div v-for="field in measurement_fields" :key="field.id">
                            <div>
                                <jet-label class="font-bold" :for="field.name" :value="$t('springs.measurement_fields.'+field.name)" />
                                <jet-input :type="field.type" class="mt-1 block w-full" :id="field.name" v-model="field.value" :name="'measurement_values['+ field.name +']'" />
                            </div>
                        </div>
                    </div>

                </template>
                <template #actions>
                    <!--<jet-secondary-button type="submit" @click.native="saveDraft(form)">{{ $t('springs.save_as_draft') }}</jet-secondary-button>-->
                    <jet-button class="ml-2" type="submit" @click.native="submit(form)">{{ $t('springs.submit') }}</jet-button>
                </template>
            </jet-form-section>
        </div>
    </app-layout>
</template>
<script>
import AppLayout from './../../Layouts/AppLayout';
import JetFormSection from "../../Jetstream/FormSection";
import JetLabel from "../../Jetstream/Label";
import JetInput from "../../Jetstream/Input";
import JetInputError from "../../Jetstream/InputError";
import JetButton from "../../Jetstream/Button";
import JetSecondaryButton from "../../Jetstream/SecondaryButton";

export default {
    components: {
        AppLayout,
        JetFormSection,
        JetLabel,
        JetInput,
        JetInputError,
        JetButton,
        JetSecondaryButton,
    },
    props: ['spring','measurement_fields'],
    data() {
        return {
            form: this.$inertia.form({
                '_method': 'PUT',
                analysis_time: this.getNow(),
                spring_id: this.spring.id,
                measurement_values: this.measurement_fields,
                photos: [],
            }, {
                bag: 'addMeasurement',
                resetOnSuccess: false,
            }),
        }
    },
    methods: {
        getMeasurementValues: function() {
            let values = [];
            _.forEach(this.fields, function(field) {
                values.push({
                    id: spring.code,
                    name: spring.title,
                    description: spring.description,
                });
            });
        },
        saveDraft: function (data) {
            data._method = 'POST';
            this.$inertia.post('/measurements', data)
        },
        submit: function (data) {
            //data.status = 'submitted';
            data._method = 'POST';
            this.$inertia.post('/measurements', data)
        },
        getNow: function() {
            return new Date().toJSON().slice(0,19);
        }
    }
}
</script>
