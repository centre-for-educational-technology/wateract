<template>

    <div class="inline-block">
        <button type="submit" class="border text-xs font-semibold px-3 py-2 ml-2 leading-normal" v-on:click="showFeedbackWindow">
            Feedback
        </button>

            <!-- Feedback Modal -->
            <jet-dialog-modal :show="addFeedback" @close="addFeedback = false">
                <template #title>
                    Feedback
                </template>

                <template #content>
                    Please leave us a feedback about this spring.

                    <div class="mt-4">
                        <jet-label class="font-bold">Spring name</jet-label>
                        <jet-input type="text" class="mt-1 block w-3/4" v-model="form.spring_name" />
                        <jet-label class="font-bold">Location</jet-label>
                        <jet-input type="number" v-model="form.latitude"></jet-input>
                        <jet-input type="number" v-model="form.longitude"></jet-input>
                        <textarea type="textarea" ref="feedback" class="px-2 mt-1 block w-full border" v-model="form.feedback" placeholder="Write feedback here" rows="5"></textarea>
                        <jet-input-error :message="form.error('feedback')" class="mt-2" />
                    </div>
                </template>

                <template #footer>
                    <jet-secondary-button @click.native="addFeedback = false">
                        Cancel
                    </jet-secondary-button>

                    <jet-button class="ml-2" @click.native="createSpringFeedback" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Submit
                    </jet-button>
                </template>
            </jet-dialog-modal>

    </div>

</template>

<script>
import JetActionSection from './../../Jetstream/ActionSection'
import JetButton from './../../Jetstream/Button'
import JetDialogModal from './../../Jetstream/DialogModal'
import JetLabel from './../../Jetstream/Label'
import JetInput from './../../Jetstream/Input'
import JetInputError from './../../Jetstream/InputError'
import JetSecondaryButton from './../../Jetstream/SecondaryButton'

export default {
    components: {
        JetActionSection,
        JetButton,
        JetLabel,
        JetDialogModal,
        JetInput,
        JetInputError,
        JetSecondaryButton,
    },
    props: ['spring'],
    data() {
        return {
            addFeedback: false,
            form: this.$inertia.form({
                '_method': 'POST',
                spring_name: this.spring.name,
                latitude: this.spring.latitude,
                longitude: this.spring.longitude,
                feedback: this.spring.feedback,
                spring_id: this.spring.id,
            }, {
                bag: 'addFeedback'
            })
        }
    },
    methods: {
        showFeedbackWindow() {
            this.addFeedback = true;
            setTimeout(() => {
                this.$refs.feedback.focus()
            }, 250)
        },
        createSpringFeedback() {
            this.form.post('/spring_feedback' , {
                preserveScroll: true
            }).then(response => {
                if (! this.form.hasErrors()) {
                    this.addFeedback = false;
                }
            })
        },
    },
}
</script>
