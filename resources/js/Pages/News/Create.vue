<template>
    <app-layout v-if="$page.user && is('admin')">
        <template #header>
            <h1>
                {{ $t('springs.add_new_news') }}
            </h1>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <jet-form-section>

                <template #form>

                    <div class="flex -mx-2">
                        <div class="w-full px-2">
                            <jet-label class="inline-block font-bold" for="name" :value="$t('springs.title')" />
                            <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.title" />
                        </div>
                    </div>

                    <div class="col-span-6 sm:col-span-4">
                        <jet-label class="font-bold" for="body_text" :value="$t('springs.body_text')" />
                        <vue-editor v-model="form.body_text" :editorToolbar="customToolbar"></vue-editor>
                    </div>

                    <div class="w-1/2 px-2 py-2">
                        <jet-label class="font-bold" for="locale" :value="$t('springs.locale')" />
                        <select v-model="form.locale" class="border-gray-300 border rounded">
                            <option value="et" :selected="form.locale === 'et'" >et</option>
                            <option value="en" :selected="form.locale === 'en'" >en</option>
                            <option value="lv" :selected="form.locale === 'lv'" >lv</option>
                        </select>
                    </div>

                </template>
                <template #actions>
                    <jet-button class="ml-2" type="submit" @click.native="save(form)">
                        {{ $t('springs.save') }}</jet-button>
                </template>

            </jet-form-section>
        </div>

    </app-layout>
</template>

<script>
import AppLayout from './../../Layouts/AppLayout'
import JetActionMessage from "../../Jetstream/ActionMessage";
import JetButton from "../../Jetstream/Button";
import JetFormSection from "../../Jetstream/FormSection";
import JetInput from "../../Jetstream/Input";
import JetInputError from "../../Jetstream/InputError";
import JetLabel from "../../Jetstream/Label";
import { VueEditor } from "vue2-editor";

export default {
    components: {
        AppLayout,
        JetActionMessage,
        JetButton,
        JetFormSection,
        JetInput,
        JetInputError,
        JetLabel,
        VueEditor,
    },
    props: [],
    data() {
        return {
            customToolbar: [["bold", "italic", "underline"], ["link"]],
            form: this.$inertia.form({
                '_method': 'POST',
                title: this.title,
                body_text: this.body_text,
                locale: this.$i18n.locale,
            }, {
                bag: 'addNews',
                resetOnSuccess: false,
            }),
        }
    },
    methods: {
        save: function (data) {
            this.$inertia.post('/news', data)
        },
    },
}
</script>
