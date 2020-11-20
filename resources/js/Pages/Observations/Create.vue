<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <inertia-link class="mr-4" :href="'/springs/'+spring.code">{{ spring.name }}</inertia-link>
            </h2>
        </template>


        <div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
            <jet-form-section>

                <template #title>
                    Create observation
                </template>

                <template #form>

                    <div class="flex -mx-2 py-2">
                        <div class="w-1/2 px-2">
                            <jet-label class="font-bold" for="measurement_time" value="Measurement time" />
                            <input type="datetime-local" id="measurement_time" v-model="form.measurement_time" />
                        </div>
                    </div>

                    <div class="flex -mx-2 py-2">
                        <div class="w-full px-2">
                            <jet-label class="font-bold" for="odor" value="Odor" />
                            <jet-input id="odor" type="text" class="mt-1 block w-full" v-model="form.odor" />
                        </div>
                    </div>

                    <div class="flex -mx-2 py-2">
                        <div class="w-1/2 px-2">
                            <jet-label class="font-bold" for="taste" value="Taste" />
                            <select id="taste" v-model="form.taste"
                                class="block w-full bg-white border border-gray-400 hover:border-gray-500 px-2 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                                <option v-for='data in taste_options' :selected="form.taste === data.id" :value='data.id'>{{ data.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex -mx-2 py-2">
                        <div class="w-full px-2">
                            <jet-label class="font-bold" for="color" value="Color and turbidity" />
                            <jet-input id="color" type="text" class="mt-1 block w-full" v-model="form.color" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div v-for="(field, index) in observation_fields" :key="field.id">
                            <div :class="{'pull-right': index % 2 === 0, 'pull-left': index % 2 !== 0 }">
                                <jet-label class="font-bold" :for="field.name" :value="field.name" />
                                <jet-input :type="field.type" class="mt-1 block w-full" :id="field.name" v-model="field.value" />
                            </div>
                        </div>
                    </div>

                    <div class="col-span-6 sm:col-span-4">
                        <jet-label class="font-bold" for="description" value="Description" />
                        <textarea id="description" type="textarea" class="px-2 mt-1 block w-full border" rows="5" v-model="form.description" ></textarea>
                        <small id="description_help_block" class="form-text text-muted">
                            'springs.description_help_text'
                        </small>
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
import AppLayout from './../../Layouts/AppLayout'
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
    props: ['spring', 'observation_fields', 'taste_options'],
    data() {
        return {
            form: this.$inertia.form({
                '_method': 'PUT',
                measurement_time: '',
                odor: '',
                taste: 'fine',
                color: '',
                spring_id: this.spring.id,
                observation_values: this.observation_fields,
                photos: [],
                description: '',
            }, {
                bag: 'addObservation',
                resetOnSuccess: false,
            }),
        }
    },
    methods: {
        saveDraft: function (data) {
            data._method = 'POST';
            this.$inertia.post('/observations', data)
        },
        submit: function (data) {
            data.status = 'submitted';
            data._method = 'POST';
            this.$inertia.post('/observations', data)
        },
    }
}
</script>
