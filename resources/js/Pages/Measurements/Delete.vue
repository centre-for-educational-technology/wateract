<template>

    <jet-action-section>
        <template #title>
            {{ $t('springs.delete_measurement') }}
        </template>

        <template #description>
            {{ $t('springs.permanently_delete_measurement') }}
        </template>

        <template #content>
            <div class="max-w-xl text-sm text-gray-600">
                {{ $t('springs.delete_measurement_notification') }}
            </div>

            <jet-danger-button @click.native="confirmMeasurementDeletion">
                {{ $t('springs.delete_measurement') }}
            </jet-danger-button>

            <!-- Delete Measurement Confirmation Modal -->
            <jet-dialog-modal :show="confirmingMeasurementDeletion" @close="confirmingMeasurementDeletion = false">
                <template #title>
                    {{ $t('springs.delete_measurement') }}
                </template>

                <template #content>
                    {{ $t('springs.delete_measurement_confirmation') }}
                </template>

                <template #footer>
                    <jet-secondary-button @click.native="confirmingMeasurementDeletion = false">
                        {{ $t('springs.nevermind') }}
                    </jet-secondary-button>
                    <jet-danger-button class="ml-2" @click.native="deleteMeasurement" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        {{ $t('springs.delete_measurement') }}
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
    props: ['measurement_id'],
    data() {
        return {
            confirmingMeasurementDeletion: false,
            deleting: false,
            form: this.$inertia.form({
                '_method': 'DELETE',
                measurement_id: this.measurement_id,
            }, {
                bag: 'deleteMeasurement'
            })
        }
    },
    methods: {
        confirmMeasurementDeletion() {
            this.confirmingMeasurementDeletion = true;
        },
        deleteMeasurement() {
            this.form.post('/measurements/' + this.measurement_id , {
                preserveScroll: false
            }).then(response => {
                if (! this.form.hasErrors()) {
                    this.confirmingMeasurementDeletion = false;
                    document.body.style.overflow = null
                }
            })
        },
    },
}
</script>
