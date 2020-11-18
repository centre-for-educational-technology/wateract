<template>
    <app-layout v-if="$page.user">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ spring.title }}
            </h2>
        </template>


        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <jet-form-section>

                <template #form>

                    <div class="flex -mx-2 py-2">
                        <div class="w-1/2 px-2">
                            <jet-label class="font-bold" for="analysis_time" value="Analysis time" />
                            <input type="datetime-local" id="analysis_time" v-model="form.analysis_time" />
                        </div>
                    </div>

                        <div v-for="field in measurement_fields" :key="field.id">
                            <jet-label class="font-bold" :for="field.name" :value="field.name" />
                            <jet-input :type="field.type" class="mt-1 block w-full" :id="field.name" v-model="field.value" :name="'measurement_values['+ field.name +']'" />
                        </div>

                </template>
                <template #actions>
                    <jet-secondary-button type="submit" @click.native="saveDraft(form)">Save as draft</jet-secondary-button>
                    <jet-button class="ml-2" type="submit" @click.native="submit(form)">Submit</jet-button>
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
            const today = new Date();
            const date = today.getDate()+'.'+(today.getMonth()+1)+'.'+today.getFullYear();
            const time = today.getHours() + ":" + today.getMinutes();
            const dateTime = date +' '+ time;
            //console.log(dateTime);
            return dateTime;
        }
    }
}
</script>
