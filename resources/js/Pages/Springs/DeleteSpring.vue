<template>

    <jet-action-section>
        <template #title>
            {{ $t('springs.delete_spring') }}
        </template>

        <template #description>
            {{ $t('springs.permanently_delete_spring') }}
        </template>

        <template #content>
            <div class="max-w-xl text-sm text-gray-600">
                {{ $t('springs.delete_spring_notification') }}
            </div>

            <jet-danger-button @click.native="confirmSpringDeletion">
                {{ $t('springs.delete_spring') }}
            </jet-danger-button>

            <!-- Delete Account Confirmation Modal -->
            <jet-dialog-modal :show="confirmingSpringDeletion" @close="confirmingSpringDeletion = false">
                <template #title>
                    {{ $t('springs.delete_spring') }}
                </template>

                <template #content>
                    {{ $t('springs.delete_spring_confirmation') }}
                </template>

                <template #footer>
                    <jet-secondary-button @click.native="confirmingSpringDeletion = false">
                        {{ $t('springs.nevermind') }}
                    </jet-secondary-button>
                    <jet-danger-button class="ml-2" @click.native="deleteSpring" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        {{ $t('springs.delete_spring') }}
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
    props: ['spring_id'],
    data() {
        return {
            confirmingSpringDeletion: false,
            deleting: false,
            form: this.$inertia.form({
                '_method': 'DELETE',
                spring_id: this.spring_id,
            }, {
                bag: 'deleteSpring'
            })
        }
    },
    methods: {
        confirmSpringDeletion() {
            this.confirmingSpringDeletion = true;
        },
        deleteSpring() {
            this.form.post('/springs/' + this.spring_id , {
                preserveScroll: false
            }).then(response => {
                if (! this.form.hasErrors()) {
                    this.confirmingSpringDeletion = false;
                    location.href = '/springs';
                }
            })
        },
    },
}
</script>
