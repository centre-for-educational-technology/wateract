<template>

    <jet-action-section>
        <template #title>
            {{ $t('springs.delete_observation') }}
        </template>

        <template #description>
            {{ $t('springs.permanently_delete_observation') }}
        </template>

        <template #content>
            <div class="max-w-xl text-sm text-gray-600">
                {{ $t('springs.delete_observation_notification') }}
            </div>

            <jet-danger-button @click.native="confirmObservationDeletion">
                {{ $t('springs.delete_observation') }}
            </jet-danger-button>

            <!-- Delete Observation Confirmation Modal -->
            <jet-dialog-modal :show="confirmingObservationDeletion" @close="confirmingObservationDeletion = false">
                <template #title>
                    {{ $t('springs.delete_observation') }}
                </template>

                <template #content>
                    {{ $t('springs.delete_observation_confirmation') }}
                </template>

                <template #footer>
                    <jet-secondary-button @click.native="confirmingObservationDeletion = false">
                        {{ $t('springs.nevermind') }}
                    </jet-secondary-button>
                    <jet-danger-button class="ml-2" @click.native="deleteObservation" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        {{ $t('springs.delete_observation') }}
                    </jet-danger-button>
                </template>
            </jet-dialog-modal>
        </template>
    </jet-action-section>

</template>

<script>
import JetActionSection from './../../Jetstream/ActionSection'
import JetButton from './../../Jetstream/Button'
import JetDialogModal from './../../Jetstream/DialogModal'
import JetDangerButton from './../../Jetstream/DangerButton'
import JetInput from './../../Jetstream/Input'
import JetInputError from './../../Jetstream/InputError'
import JetSecondaryButton from './../../Jetstream/SecondaryButton'

export default {
    components: {
        JetActionSection,
        JetButton,
        JetDangerButton,
        JetDialogModal,
        JetInput,
        JetInputError,
        JetSecondaryButton,
    },
    props: ['observation_id'],
    data() {
        return {
            confirmingObservationDeletion: false,
            deleting: false,
            form: this.$inertia.form({
                '_method': 'DELETE',
                observation_id: this.observation_id,
            }, {
                bag: 'deleteObservation'
            })
        }
    },
    methods: {
        confirmObservationDeletion() {
            this.confirmingObservationDeletion = true;
        },
        deleteObservation() {
            this.form.post('/observations/' + this.observation_id , {
                preserveScroll: false
            }).then(response => {
                if (! this.form.hasErrors()) {
                    this.confirmingObservationDeletion = false;
                    document.body.style.overflow = null
                }
            })
        },
    },
}
</script>
