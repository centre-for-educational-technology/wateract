<template>

    <jet-action-section>
        <template #title>
            {{ $t('users.delete_user') }}
        </template>

        <template #description>
            {{ $t('users.permanently_delete_user_account') }}
        </template>

        <template #content>
            <div class="max-w-xl text-sm text-gray-600">
                {{ $t('users.delete_account_notification') }}
            </div>

            <jet-danger-button @click.native="confirmUserDeletion">
                {{ $t('users.delete_user') }}
            </jet-danger-button>

            <!-- Delete Account Confirmation Modal -->
            <jet-dialog-modal :show="confirmingUserDeletion" @close="confirmingUserDeletion = false">
                <template #title>
                    {{ $t('users.delete_user') }}
                </template>

                <template #content>
                    {{ $t('users.delete_account_confirmation') }}

                    <div class="mt-4">
                        <jet-input type="password" class="mt-1 block w-3/4" :placeholder="$t('users.password')"
                                   ref="password"
                                   v-model="form.password"
                                   @keyup.enter.native="deleteUser" />
                        <jet-input-error :message="form.error('password')" class="mt-2" />
                    </div>
                </template>

                <template #footer>
                    <jet-secondary-button @click.native="confirmingUserDeletion = false">
                        {{ $t('users.nevermind') }}
                    </jet-secondary-button>

                    <jet-danger-button class="ml-2" @click.native="deleteUser" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        {{ $t('users.delete_user') }}
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
    props: ['user_id'],
    data() {
        return {
            confirmingUserDeletion: false,
            deleting: false,
            form: this.$inertia.form({
                '_method': 'DELETE',
                password: '',
                user_id: this.user_id,
            }, {
                bag: 'deleteUser'
            })
        }
    },
    methods: {
        confirmUserDeletion() {
            this.form.password = '';
            this.confirmingUserDeletion = true;
            setTimeout(() => {
                this.$refs.password.focus()
            }, 250)
        },
        deleteUser() {
            this.form.post('/admin/users/' + this.user_id , {
                preserveScroll: true
            }).then(response => {
                if (! this.form.hasErrors()) {
                    this.confirmingUserDeletion = false;
                }
            })
        },
    },
}
</script>
