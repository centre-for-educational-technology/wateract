<template>

    <jet-action-section>
        <template #title>
            {{ $t('springs.delete_news') }}
        </template>

        <template #content>
            <div class="max-w-xl text-sm text-gray-600">
                {{ $t('springs.delete_news_notification') }}
            </div>

            <jet-danger-button @click.native="confirmNewsDeletion">
                {{ $t('springs.delete_news') }}
            </jet-danger-button>

            <!-- Delete News Confirmation Modal -->
            <jet-dialog-modal :show="confirmingNewsDeletion" @close="confirmingNewsDeletion = false">
                <template #title>
                    {{ $t('springs.delete_news') }}
                </template>

                <template #content>
                    {{ $t('springs.delete_news_confirmation') }}
                </template>

                <template #footer>
                    <jet-secondary-button @click.native="confirmingNewsDeletion = false">
                        {{ $t('springs.nevermind') }}
                    </jet-secondary-button>
                    <jet-danger-button class="ml-2" @click.native="deleteNews" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        {{ $t('springs.delete_news') }}
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
    props: ['news_id'],
    data() {
        return {
            confirmingNewsDeletion: false,
            deleting: false,
            form: this.$inertia.form({
                '_method': 'DELETE',
                news_id: this.news_id,
            }, {
                bag: 'deleteNews'
            })
        }
    },
    methods: {
        confirmNewsDeletion() {
            this.confirmingNewsDeletion = true;
        },
        deleteNews() {
            this.form.post('/news/' + this.news_id , {
                preserveScroll: false
            }).then(response => {
                if (! this.form.hasErrors()) {
                    this.confirmingNewsDeletion = false;
                    document.body.style.overflow = null
                }
            })
        },
    },
}
</script>
