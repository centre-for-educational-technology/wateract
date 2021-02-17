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
                    {{ $t('springs.edit_measurement')}}
                </template>

                <template #form>

                    <div class="py-2">
                        <jet-label class="font-bold" for="analysis_time" :value="$t('springs.analysis_time')" />
                        <datetime :auto="true" :title="$t('springs.analysis_time')" value-zone="local"
                                  :phrases="datetime_phrases"  type="datetime" v-model="form.analysis_time"></datetime>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-4">
                        <div v-for="field in measurement_fields" :key="field.id">
                            <div>
                                <jet-label class="font-bold inline-block" :for="field.name" :value="$t('springs.measurement_fields.'+field.name)" />
                                <span v-if="field.unit">({{ field.unit}})</span>
                                <jet-input :type="field.type" class="mt-1 block w-full" :id="field.name" v-model="field.value" :name="'measurement_values['+ field.name +']'" />
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
import AppLayout from './../../Layouts/AppLayout';
import JetFormSection from "../../Jetstream/FormSection";
import JetLabel from "../../Jetstream/Label";
import JetInput from "../../Jetstream/Input";
import JetInputError from "../../Jetstream/InputError";
import JetButton from "../../Jetstream/Button";
import JetSecondaryButton from "../../Jetstream/SecondaryButton";
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
        datetime: Datetime,
    },
    props: ['spring', 'measurement', 'measurement_fields'],
    data() {
        return {
            datetime_phrases: {ok: this.$i18n.t('springs.ok'), cancel: this.$i18n.t('springs.cancel')},
            form: this.$inertia.form({
                '_method': 'PUT',
                id: this.measurement.id,
                analysis_time: new Date(this.measurement.analysis_time).toISOString(),
                spring_id: this.spring.id,
                measurement_values: this.measurement_fields,
                status: this.measurement.status,
            }, {
                bag: 'editMeasurement',
                resetOnSuccess: false,
            }),
        }
    },
    methods: {
        save: function (data) {
            this.$inertia.post('/measurements/' + data.id, data)
        },
        submit: function (data) {
            data.status = 'submitted';
            this.$inertia.post('/measurements/' + data.id, data)
        },
    }
}
</script>
