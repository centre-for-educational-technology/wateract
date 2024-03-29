<template>

    <div class="inline-block">
        <button class="inline mt-1 px-2 sm:px-3 py-1 sm:py-2 text-xs font-semibold leading-normal cursor-pointer bg-blue-100 hover:bg-gray-100 border border-blue-200" v-on:click="showFeedbackWindow">
            {{ $t('springs.leave_feedback') }}
        </button>

            <!-- Feedback Modal -->
            <jet-dialog-modal :show="addFeedback" @close="addFeedback = false">
                <template #title>
                    {{ $t('springs.feedback') }}
                </template>

                <template #content>
                    {{ $t('springs.leave_feedback_text') }}

                    <div class="mt-4">
                        <jet-label class="font-bold">{{ $t('springs.spring_name') }}</jet-label>
                        <jet-input type="text" class="mt-1 block w-3/4" v-model="form.spring_name" />
                        <jet-label class="font-bold">{{ $t('springs.location') }}</jet-label>
                        <jet-input type="number" v-model="form.latitude"></jet-input>
                        <jet-input type="number" v-model="form.longitude"></jet-input>
                        <textarea type="textarea" ref="feedback" class="px-2 mt-1 block w-full border" v-model="form.feedback" :placeholder="$t('springs.write_feedback')" rows="5"></textarea>
                        <jet-input-error :message="form.error('feedback')" class="mt-2" />
                    </div>
                </template>

                <template #footer>
                    <jet-secondary-button @click.native="addFeedback = false">
                        {{ $t('springs.cancel') }}
                    </jet-secondary-button>

                    <jet-button class="ml-2" @click.native="createSpringFeedback" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        {{ $t('springs.submit') }}
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
